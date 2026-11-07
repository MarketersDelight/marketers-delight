<?php
/**
 * Every registered field may only use $field_keys on md_validate. How a
 * field draws (description, placeholder, classes...) belongs in its template.
 * Checks the theme and every active Drop-in on a real site.
 *
 * Run: wp eval-file wp-content/themes/marketers-delight/tests/integration/registration-keys.php --exec='define( "WP_ADMIN", true );'
 */

wp_set_current_user( 1 );

$problems = array();

function md_test_registration_keys( $fields, $where, &$problems ) {
	foreach ( (array) $fields as $key => $field ) {
		if ( ! is_array( $field ) )
			continue;

		if ( ! isset( $field['type'] ) ) {
			md_test_registration_keys( $field, "$where.$key", $problems );
			continue;
		}

		$validate = new md_validate;

		foreach ( array_diff( array_keys( $field ), $validate->field_keys ) as $unknown )
			$problems["$where.$key"][] = $unknown;

		if ( isset( $field['fields'] ) )
			md_test_registration_keys( $field['fields'], "$where.$key", $problems );
	}
}

foreach ( md_register() as $settings => $groups )
	foreach ( $groups as $group => $registration )
		md_test_registration_keys( isset( $registration['fields'] ) ? $registration['fields'] : array(), "$settings/$group", $problems );

if ( $problems ) {
	foreach ( $problems as $field => $keys )
		echo "$field: " . implode( ', ', $keys ) . "\n";

	echo count( $problems ) . " registered fields use keys that belong in templates.\n";
	exit( 1 );
}

echo "Every registered field uses only registration keys.\n";
