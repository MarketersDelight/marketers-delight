# Loop settings inheritance — findings

Backed by `tests/inheritance/*Test.php` (run with `php tests/bin/phpunit.phar -c phpunit-inheritance.xml`
from the theme root). Every claim below is pinned down by a passing assertion, not just read from the source.

## TL;DR

The 4-tier cascade (global default → post type → taxonomy → term) that the admin UI implies —
"leave the taxonomy tab blank and it inherits the post type's setting" — **used to not be what the
code actually did** for `posts_per_page`, `order`, `orderby`, `columns`, and `featured`. That's
fixed now: every Loop field cascades post-type → taxonomy → term uniformly, with one deliberate
exception for term-ID-shaped fields. See "Resolution" below for the final design and why.

## 1. The taxonomy tier used to NOT inherit from the post-type tier by default (fixed)

`md_api::loop_query_vars()` (`api/api.php:430-460`, drives the main archive query) used to resolve
`posts_per_page`/`order`/`orderby` for a taxonomy tab left blank by defaulting to the **global**
default (WP's `posts_per_page` option, or nothing for order/orderby) — **not** the post type's
configured value — unless one narrow condition was met (category-ish `loop_type` + a term with
children). `md_get_loop()` (`functions/loop-functions.php:273-395`) independently reproduced the
same problem for `posts_per_page`, `columns`, and `featured` via a reset-strip step.

**Practical effect (before the fix):** an admin who set "3 columns" on the Post post-type settings
page, then visited a Category term's taxonomy tab and left Columns blank expecting it to inherit
"3," would actually get the hardcoded default of 1 column — a Documentation-post-type scenario
(archive-level `loop_type = category`, 3 columns, categories with mixed subcat depth) is what
surfaced this originally.

**Fixed**: both `loop_query_vars()` and `md_get_loop()` now cascade every Loop field
unconditionally, post type → taxonomy → term, no render-mode gating.

**Second bug found during live verification, also fixed**: `md_get_loop()`'s
`array_merge( $post_type, $tax, $single )` (`functions/loop-functions.php:307-308`) requests the
*entire* `'loop'` container per tier, not individual leaf fields. On a real site, opening a
taxonomy tab in the admin and saving it — even with every field left blank — writes the *full*
registered field schema into storage, padded with `''` for every untouched field, not a
sparse/missing entry. `array_merge()` doesn't know the difference between "genuinely set to ''"
and "never touched," so that blank container silently overwon the post-type tier's real values —
reintroducing the exact bug just fixed above, for any taxonomy tab an admin had ever opened and
saved. Confirmed live on `docs_category` (a real taxonomy tab that had been saved blank at some
point): `columns: 3` set at the post-type tier rendered as `columns: 1` on a leaf term until this
was fixed. `loop_query_vars()` was unaffected (it requests individual leaf keys, e.g.
`array('loop','posts_per_page')`, and `md_taxonomy_field()`'s own internal `!empty()` checks
already treat a blank *leaf* value as unset — it's specifically the whole-container-at-once
request that skipped that treatment).

Fix: `$tax` and `$single` are now passed through `array_filter()` before merging, so blank
(`''`/empty-array) fields don't override the post-type tier — `$loop = array_merge( $post_type,
array_filter( $tax ), array_filter( $single ) )`. Verified by
`GetLoopTest::test_saved_but_blank_taxonomy_tab_does_not_override_post_type_tier`, and live
re-verification on the real `docs_category` data showed `columns: 3`/`posts_per_page: 4`
correctly cascading afterward, with `category_exclude` still correctly *not* leaking.

If an inherited value looks
wrong in a specific context (e.g. 3 grid columns inherited onto what's now a flat post listing),
the admin fixes it explicitly in that taxonomy's tab — same as any other setting, no hidden
auto-behavior. Verified by `LoopQueryVarsTest::test_unset_taxonomy_tier_inherits_post_type_value`
and `GetLoopTest::test_post_type_only_columns_cascades_onto_taxonomy_archive`.

## 2. One deliberate exception: term-ID-shaped fields don't cascade from post type

`category_include`, `category_exclude`, `include_cats`, `exclude_cats` hold term IDs, which are
only meaningful relative to where they're set. A post-type-tier exclude list referencing top-level
category IDs, inherited onto an unrelated subcategory's own term page, can silently produce an
**empty result** (not just a stylistic mismatch) if those IDs aren't even that term's siblings —
the same category of problem as the `loop_type`/`by_category` render-mode switch (a childless term
rendering `loop_type = category` must fall back to a post listing, or the page is structurally
broken). That switch is a rendering-mode decision and was untouched by this fix.

These four fields resolve via `md_module( $keys, null, null, array( 'inherit_post_type' => false )
)` in `md_get_loop()` instead of riding the wholesale tier merge — term's own value, else the
taxonomy tab's own value, else nothing; never the post-type tier. Verified by
`GetLoopTest::test_post_type_tier_category_exclude_does_not_leak_onto_taxonomy_archive` and
siblings.

## 3. `md_module()` gets one new `$args` parameter, not a new function

Considered adding a dedicated function for the "don't inherit from post type" need (used by #2
above), but that would grow an already-easy-to-confuse family
(`md_module`/`md_post_type_field`/`md_taxonomy_field`/`md_term_meta`) to five near-synonymous
names. Instead, `md_module()` (`functions/theme-functions.php:508-`) — already the general "give me
this field for wherever we currently are" reader, already implementing the term_meta → taxonomy →
post_type cascade — gained one optional `array $args` parameter (an array, not a single boolean, so
a future need is "add a key," not a new parameter or function). `array( 'inherit_post_type' =>
false )` stops the cascade before the post-type tier. Default (`true`, or omitting `$args`
entirely) is identical to prior behavior for every existing caller — confirmed via `ModuleFallbackTest`.

## 4. `md_module()`'s singular/404 branch was missing a tier (fixed)

While auditing `md_taxonomy_field()` and `md_module()` for other holes: `md_module()`'s
`is_singular() || is_404()` branch read `md_post_meta()` only, with **no `md_post_type_field()`
fallback** — unlike every hand-rolled cascade elsewhere in the theme (e.g. `md_media_position()`'s
singular branch explicitly does `md_post_type_field()` → `md_post_meta()`). Checked whether fixing
this could leak post-type-tier Loop settings (like `columns`) onto singular-post rendering — likely
the original reason for the gap — but `md_loop()` already gates the columns-bearing wrapper markup
behind `! is_singular()`, so a leaked `columns` value there is inert. **Fixed**: the singular/404
branch now falls through to `md_post_type_field()` the same way the taxonomy branch already did (also
respecting the new `inherit_post_type` flag). Verified by
`ModuleFallbackTest::test_singular_branch_falls_through_to_post_type_tier_when_post_meta_unset`.

## 5. `md_taxonomy_field()` itself is sound; the same hand-rolled cascade exists at 5+ other sites

Audited every real call site of `md_taxonomy_field()` (`grep -rn "md_taxonomy_field("`). All of
them — `title-functions.php`, `media-functions.php` — correctly guard the call with `is_category()
|| is_tax()` and rely on the same implicit `get_queried_object()` context `md_module()` uses,
always in current-page-rendering context. **No holes in the function itself.**

But the audit found the same `post_type → taxonomy → term` cascade hand-rolled at 5+ call sites
outside `md_module()`. Checked each for whether consolidating onto `md_module()` is a safe,
behavior-preserving simplification:

- **`title-functions.php` byline builder** — same shape as `md_module()`'s default cascade.
  **Consolidated.**
- **`title-functions.php` `archives_title`** — cascades `term_meta → taxonomy →
  single_term_title()` fallback, deliberately skipping the post-type tier (a post-type's own
  archive title, e.g. "Blog," must not leak onto an unrelated category's title). **Consolidated**
  using `array( 'inherit_post_type' => false )`, since `md_module()`'s null-coalescing shape
  reproduces this almost exactly (one negligible edge case: an explicitly-saved *blank* taxonomy
  value is treated as "set" by `md_module()`'s null-check vs. "unset" by the original truthy-check).
- **`title-functions.php` `archives_text`** — order is `term_meta → category_description() →
  taxonomy_field`, i.e. it prefers WP's native term description **over** the custom taxonomy-tier
  override. Swapping to `md_module()` would flip that precedence. **Left unchanged**, with a code
  comment — a real product decision, not a simplification.
- **`media-functions.php` `md_cover()`** — does a genuine field-level `array_merge()` of taxonomy +
  term cover settings (so a term can override just one sub-field, e.g. overlay color, without
  redefining the whole cover config). `md_module()` only does whole-value override. **Left
  unchanged**, with a code comment — would be a feature loss, not a cleanup.

The common thread behind the two "left unchanged" cases: `archives_text` and `page_cover` (like
`archives_title`) are page-identity/branding fields whose post-type-tier value represents "this
post type's own archive," not something that should bleed onto unrelated term pages — the same
category of problem as the term-ID fields in #2, already correctly handled by not inheriting from
post-type.

## 6. `hide_subcategory`/`show_subcategory` — corrected, not a bug

Originally flagged this as an inconsistency worth collapsing into one field. On closer inspection
(`functions/loop-functions.php:429-436`) it's a **deliberate, sensible context-dependent default**:
subcategory links show by default on a term's own page (opt-out via `hide_subcategory`) and are
hidden by default everywhere else (opt-in via `show_subcategory`). Not a bug — removed from
recommendations. A single field with a context-aware default instead of two field names remains a
purely optional future cleanup, not a defect.

## Status

1. ~~Rename/fix `$use_tax_defaults`~~ — **done**, `$inherit_post_type_default` in `api/api.php`.
2. ~~Decide + fix the actual inheritance behavior~~ — **done**, see #1/#2 above.
3. ~~Shared helper for the render-mode/inheritance decision~~ — **resolved by simplification**, not
   extraction. The render-mode gating that caused the original Loop duplication is gone entirely;
   the two implementations now agree by design (verified in `DivergenceTest`), not by coincidence.
4. ~~`md_module()`'s implicit current-page-context reliance~~ — **still open**. Audited every real
   call site; none is currently affected by it (all current callers are in current-page-rendering
   context already), but the risk stands for a future caller that resolves a field for a term other
   than the one currently rendering. Would need `md_module()` to accept explicit
   `$post_type`/`$taxonomy` args like `md_taxonomy_field()`/`md_post_type_field()` do.
5. ~~`hide_subcategory`/`show_subcategory`~~ — **corrected, not a bug** (see #6 above). Optional
   future cleanup only.
