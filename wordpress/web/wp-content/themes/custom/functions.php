<?php
function twentytwenty_styles() {
	wp_enqueue_style( 'parent', get_template_directory_uri() . '/style.css' );
}
add_action( 'wp_enqueue_scripts', 'twentytwenty_styles');

if ( function_exists('acf_add_options_page') ) {
	acf_add_options_page(array(
		'page_title' 	=> 'Sitewide Options',
		'menu_title'	=> 'Sitewide Options',
		'menu_slug' 	=> 'sitewide-options',
		'capability'	=> 'edit_posts',
		'position' => '1',
		'redirect'		=> true
	));

	acf_add_options_sub_page(array(
		'page_title' 	=> 'Sitewide',
		'menu_title'	=> 'Sitewide',
		'parent_slug'	=> 'sitewide-options',
		'show_in_graphql' => true
	));

	acf_add_options_sub_page(array(
		'page_title' 	=> 'Sitewide Header',
		'menu_title'	=> 'Header',
		'parent_slug'	=> 'sitewide-options',
		'show_in_graphql' => true
	));

	acf_add_options_sub_page(array(
		'page_title' 	=> 'Sitewide Footer',
		'menu_title'	=> 'Footer',
		'parent_slug'	=> 'sitewide-options',
		'show_in_graphql' => true
	));
}

add_filter('acf/settings/save_json', function ( $path = '' ) {
	$path = get_stylesheet_directory() . '/acf-json';
	return $path;
});

add_filter('acf/settings/load_json', function ( $paths = array()) {
	$paths = array( get_stylesheet_directory() . '/acf-json' );
	return $paths;
});

add_filter('use_block_editor_for_post', '__return_false', 10);


// Method 1: Filter.
function my_acf_google_map_api( $api ){
	$api['key'] = 'PLACEHOLDER_GOOGLE_MAPS_API_KEY';
	return $api;
}
add_filter('acf/fields/google_map/api', 'my_acf_google_map_api');

// Method 2: Setting.
function my_acf_init() {
	acf_update_setting('google_api_key', 'PLACEHOLDER_GOOGLE_MAPS_API_KEY');
}
add_action('acf/init', 'my_acf_init');

function add_tertiary_menu() {
  register_nav_menu('tertiary_menu',__( 'Tertiary menu' ));
}
add_action( 'init', 'add_tertiary_menu' );

?>
