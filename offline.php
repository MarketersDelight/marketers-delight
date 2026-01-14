<?php
/**
 * Offline Template
 *
 * Displayed when the user is offline and the requested page is not cached.
 * Provides helpful options and cached content suggestions.
 *
 * @package Marketers_Delight
 * @since 6.3
 */

if ( ! defined( 'ABSPATH' ) ) exit;

$site_name = get_bloginfo( 'name' );
$site_icon = get_site_icon_url( 192 );
$theme_color = md_setting( array( 'pwa', 'theme_color' ) ) ?: '#5A0FC8';
?>

<div id="content_box" class="content-box content-full style-default">
	<div class="inner">
		<div id="content" class="content">
			<article id="offline-page" class="page post-box offline-page">

				<header class="headline-block headline-text" style="text-align: center; padding: var(--lh-double, 3rem) var(--lh, 1.5rem);">
					<div class="content-inner">
						<!-- Animated Offline Icon -->
						<div class="offline-icon-wrapper">
							<svg class="offline-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
								<line x1="1" y1="1" x2="23" y2="23" class="offline-line"/>
								<path d="M16.72 11.06A10.94 10.94 0 0 1 19 12.55" class="wifi-wave wifi-wave-3"/>
								<path d="M5 12.55a10.94 10.94 0 0 1 5.17-2.39" class="wifi-wave wifi-wave-3"/>
								<path d="M10.71 5.05A16 16 0 0 1 22.58 9" class="wifi-wave wifi-wave-2"/>
								<path d="M1.42 9a15.91 15.91 0 0 1 4.7-2.88" class="wifi-wave wifi-wave-2"/>
								<path d="M8.53 16.11a6 6 0 0 1 6.95 0" class="wifi-wave wifi-wave-1"/>
								<line x1="12" y1="20" x2="12.01" y2="20" class="wifi-dot"/>
							</svg>
						</div>

						<h1 class="headline entry-title"><?php esc_html_e( 'You\'re Offline', 'md' ); ?></h1>
						<p class="offline-subtitle">
							<?php esc_html_e( 'It looks like you\'ve lost your internet connection. Don\'t worry, you can still access some content.', 'md' ); ?>
						</p>
					</div>
				</header>

				<div class="content-text">
					<div class="content-inner">

						<!-- Connection Status -->
						<div class="offline-status-card">
							<div class="offline-status-indicator">
								<span class="status-dot"></span>
								<span class="status-text"><?php esc_html_e( 'No Internet Connection', 'md' ); ?></span>
							</div>
							<p class="offline-status-desc">
								<?php esc_html_e( 'Your device is currently offline. When you reconnect, this page will automatically reload.', 'md' ); ?>
							</p>
						</div>

						<!-- Quick Actions -->
						<div class="offline-actions">
							<h2 class="offline-section-title"><?php esc_html_e( 'What you can do', 'md' ); ?></h2>

							<div class="offline-action-grid">
								<button onclick="window.location.reload()" class="offline-action-card">
									<div class="offline-action-icon">
										<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
											<polyline points="23 4 23 10 17 10"/>
											<polyline points="1 20 1 14 7 14"/>
											<path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"/>
										</svg>
									</div>
									<span class="offline-action-text"><?php esc_html_e( 'Try Again', 'md' ); ?></span>
								</button>

								<button onclick="history.back()" class="offline-action-card">
									<div class="offline-action-icon">
										<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
											<line x1="19" y1="12" x2="5" y2="12"/>
											<polyline points="12 19 5 12 12 5"/>
										</svg>
									</div>
									<span class="offline-action-text"><?php esc_html_e( 'Go Back', 'md' ); ?></span>
								</button>

								<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="offline-action-card">
									<div class="offline-action-icon">
										<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
											<path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
											<polyline points="9 22 9 12 15 12 15 22"/>
										</svg>
									</div>
									<span class="offline-action-text"><?php esc_html_e( 'Homepage', 'md' ); ?></span>
								</a>

								<button onclick="if(navigator.share) navigator.share({title: document.title, url: window.location.href})" class="offline-action-card" id="share-offline">
									<div class="offline-action-icon">
										<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
											<circle cx="18" cy="5" r="3"/>
											<circle cx="6" cy="12" r="3"/>
											<circle cx="18" cy="19" r="3"/>
											<line x1="8.59" y1="13.51" x2="15.42" y2="17.49"/>
											<line x1="15.41" y1="6.51" x2="8.59" y2="10.49"/>
										</svg>
									</div>
									<span class="offline-action-text"><?php esc_html_e( 'Share Link', 'md' ); ?></span>
								</button>
							</div>
						</div>

						<!-- Cached Pages (populated by JS) -->
						<div class="offline-cached-pages" id="cached-pages-section" style="display: none;">
							<h2 class="offline-section-title"><?php esc_html_e( 'Available Offline', 'md' ); ?></h2>
							<p class="offline-section-desc"><?php esc_html_e( 'These pages have been saved and are available offline:', 'md' ); ?></p>
							<ul class="offline-cached-list" id="cached-pages-list"></ul>
						</div>

						<!-- Troubleshooting Tips -->
						<div class="offline-tips">
							<h2 class="offline-section-title"><?php esc_html_e( 'Troubleshooting', 'md' ); ?></h2>
							<ul class="offline-tips-list">
								<li>
									<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
										<path d="M5 12.55a11 11 0 0 1 14.08 0"/>
										<path d="M1.42 9a16 16 0 0 1 21.16 0"/>
										<path d="M8.53 16.11a6 6 0 0 1 6.95 0"/>
										<line x1="12" y1="20" x2="12.01" y2="20"/>
									</svg>
									<?php esc_html_e( 'Check if WiFi or mobile data is turned on', 'md' ); ?>
								</li>
								<li>
									<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
										<rect x="5" y="2" width="14" height="20" rx="2" ry="2"/>
										<line x1="12" y1="18" x2="12.01" y2="18"/>
									</svg>
									<?php esc_html_e( 'Try toggling Airplane mode on and off', 'md' ); ?>
								</li>
								<li>
									<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
										<circle cx="12" cy="12" r="3"/>
										<path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"/>
									</svg>
									<?php esc_html_e( 'Move to an area with better signal', 'md' ); ?>
								</li>
								<li>
									<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
										<polyline points="23 4 23 10 17 10"/>
										<path d="M20.49 15a9 9 0 1 1-2.12-9.36L23 10"/>
									</svg>
									<?php esc_html_e( 'Restart your router or device', 'md' ); ?>
								</li>
							</ul>
						</div>

						<!-- Service Worker Error Message Placeholder -->
						<?php if ( function_exists( 'wp_service_worker_error_message_placeholder' ) ) : ?>
						<div class="offline-error-details">
							<?php wp_service_worker_error_message_placeholder(); ?>
						</div>
						<?php endif; ?>

					</div>
				</div>

			</article>
		</div>
	</div>
</div>

<style>
/* Offline Page Styles */
.offline-page {
	--offline-accent: <?php echo esc_attr( $theme_color ); ?>;
}

.offline-icon-wrapper {
	width: 120px;
	height: 120px;
	margin: 0 auto 1.5rem;
	background: linear-gradient(135deg, #f5f5f5 0%, #e8e8e8 100%);
	border-radius: 50%;
	display: flex;
	align-items: center;
	justify-content: center;
}

.offline-icon {
	width: 60px;
	height: 60px;
	stroke: #888;
}

.offline-icon .offline-line {
	stroke: #e74c3c;
	stroke-width: 2.5;
}

.offline-icon .wifi-wave {
	opacity: 0.3;
}

.offline-icon .wifi-dot {
	stroke-width: 3;
}

@keyframes pulse {
	0%, 100% { opacity: 0.3; }
	50% { opacity: 0.6; }
}

.offline-icon .wifi-wave-1 { animation: pulse 2s ease-in-out infinite; }
.offline-icon .wifi-wave-2 { animation: pulse 2s ease-in-out 0.3s infinite; }
.offline-icon .wifi-wave-3 { animation: pulse 2s ease-in-out 0.6s infinite; }

.offline-subtitle {
	font-size: 1.1rem;
	color: #666;
	max-width: 500px;
	margin: 0 auto;
	line-height: 1.6;
}

.offline-status-card {
	background: linear-gradient(135deg, #fff5f5 0%, #fff 100%);
	border: 1px solid #ffcdd2;
	border-radius: 12px;
	padding: 1.5rem;
	margin-bottom: 2rem;
	text-align: center;
}

.offline-status-indicator {
	display: inline-flex;
	align-items: center;
	gap: 0.5rem;
	padding: 0.5rem 1rem;
	background: #fff;
	border-radius: 50px;
	box-shadow: 0 2px 8px rgba(0,0,0,0.08);
	margin-bottom: 1rem;
}

.status-dot {
	width: 10px;
	height: 10px;
	background: #e74c3c;
	border-radius: 50%;
	animation: blink 1.5s ease-in-out infinite;
}

@keyframes blink {
	0%, 100% { opacity: 1; }
	50% { opacity: 0.4; }
}

.status-text {
	font-weight: 600;
	color: #c62828;
	font-size: 0.9rem;
}

.offline-status-desc {
	color: #666;
	margin: 0;
	font-size: 0.95rem;
}

.offline-section-title {
	font-size: 1.25rem;
	margin-bottom: 1rem;
	color: #333;
}

.offline-section-desc {
	color: #666;
	margin-bottom: 1rem;
}

.offline-action-grid {
	display: grid;
	grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
	gap: 1rem;
	margin-bottom: 2rem;
}

.offline-action-card {
	display: flex;
	flex-direction: column;
	align-items: center;
	padding: 1.5rem 1rem;
	background: #f9f9f9;
	border: 2px solid transparent;
	border-radius: 12px;
	text-decoration: none;
	color: inherit;
	cursor: pointer;
	transition: all 0.2s ease;
}

.offline-action-card:hover {
	background: #fff;
	border-color: var(--offline-accent);
	transform: translateY(-2px);
	box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

.offline-action-icon {
	width: 48px;
	height: 48px;
	display: flex;
	align-items: center;
	justify-content: center;
	background: #fff;
	border-radius: 12px;
	margin-bottom: 0.75rem;
	box-shadow: 0 2px 8px rgba(0,0,0,0.06);
}

.offline-action-icon svg {
	width: 24px;
	height: 24px;
	stroke: var(--offline-accent);
}

.offline-action-text {
	font-weight: 600;
	font-size: 0.9rem;
	color: #333;
}

.offline-cached-list {
	list-style: none;
	padding: 0;
	margin: 0;
}

.offline-cached-list li {
	padding: 0.75rem 1rem;
	background: #f9f9f9;
	border-radius: 8px;
	margin-bottom: 0.5rem;
}

.offline-cached-list a {
	text-decoration: none;
	color: var(--offline-accent);
	font-weight: 500;
}

.offline-tips {
	background: #f9f9f9;
	border-radius: 12px;
	padding: 1.5rem;
	margin-top: 2rem;
}

.offline-tips-list {
	list-style: none;
	padding: 0;
	margin: 0;
}

.offline-tips-list li {
	display: flex;
	align-items: center;
	gap: 0.75rem;
	padding: 0.75rem 0;
	border-bottom: 1px solid #eee;
	color: #555;
}

.offline-tips-list li:last-child {
	border-bottom: none;
}

.offline-tips-list svg {
	width: 20px;
	height: 20px;
	stroke: #888;
	flex-shrink: 0;
}

.offline-error-details {
	margin-top: 2rem;
	padding: 1rem;
	background: #fff3cd;
	border-radius: 8px;
	font-size: 0.9rem;
}

@media (max-width: 600px) {
	.offline-action-grid {
		grid-template-columns: repeat(2, 1fr);
	}

	.offline-icon-wrapper {
		width: 100px;
		height: 100px;
	}

	.offline-icon {
		width: 50px;
		height: 50px;
	}
}
</style>

<script>
// Auto-reload when back online
window.addEventListener('online', function() {
	window.location.reload();
});

// Hide share button if not supported
if (!navigator.share) {
	document.getElementById('share-offline')?.remove();
}

// Try to list cached pages
if ('caches' in window) {
	caches.open('md-pwa-v1').then(function(cache) {
		cache.keys().then(function(requests) {
			var pages = requests.filter(function(req) {
				return req.url.includes(window.location.origin) &&
					   !req.url.match(/\.(js|css|png|jpg|jpeg|gif|svg|woff|woff2)(\?|$)/i);
			});

			if (pages.length > 0) {
				var section = document.getElementById('cached-pages-section');
				var list = document.getElementById('cached-pages-list');

				if (section && list) {
					section.style.display = 'block';
					pages.slice(0, 10).forEach(function(req) {
						var url = new URL(req.url);
						var li = document.createElement('li');
						li.innerHTML = '<a href="' + url.pathname + '">' + (url.pathname === '/' ? 'Homepage' : url.pathname) + '</a>';
						list.appendChild(li);
					});
				}
			}
		});
	}).catch(function() {});
}
</script>
