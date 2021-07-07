<style type="text/css">

/*------------------------------*\
	$ICONS & EFFECTS
\*------------------------------*/

/* BOUNCE */

@keyframes bounce {
	0%, 20%, 50%, 80%, 100% { transform: translateY(0); }
	40% { transform: translateY(-7px); }
	60% { transform: translateY(-4px); }
}

.bounce { animation: bounce 3s infinite; }

/* SPIN */

@keyframes spin {
	from { transform: rotate(0deg); }
	to { transform: rotate(360deg); }
}

.spin, .md-icon-loading { animation: spin 2s linear infinite; }

.button-loading .md-icon-loading { display: none; }

.is-loading .md-icon-loading {
	display: inline-block;
	margin-left: <?php echo $small; ?>px;
}

/* ICONS */

<?php
	foreach ( md_icons() as $icon => $fields ) {
		if ( ! isset( $fields['unicode'] ) ) continue;
		$selectors = '';
		if ( isset( $fields['classes'] ) )
			foreach ( $fields['classes'] as $selector )
				$selectors .= ",{$selector}:before";
		echo '.md-icon-' . $icon . ":before{$selectors}{content:'\\" . $fields['unicode'] . '\'}';
	}
?>