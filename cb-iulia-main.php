<?php
// Includes - Infrastruktur
require_once(__DIR__ . '/cb-iulia-admin.php');
require_once(__DIR__ . '/cb-iulia-include.php');

// Komponenten
$iuliaComponents = array(
	'Dev-Dashboard'			=>	'cbra-devadmin',
	'Postfilter'			=>	'cbra-postfilter',
	'Testcode'				=>	'cb-testcode-a',
);

// Includes - Komponenten
foreach($iuliaComponents as $name => $repo) {
	cb_iulia_include($repo);
}

// Styles
function cb_iulia_enqueue_admin_styles() {
	wp_enqueue_style('cb-iulia-dashboard-css', get_stylesheet_directory_uri() . '/cb-iulia/css/cb-iulia-dashboard.css');
}
add_action('admin_enqueue_scripts', 'cb_iulia_enqueue_admin_styles');
?>
