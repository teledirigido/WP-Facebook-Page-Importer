<?php

/*
Plugin Name: Facebook Page Importer
Plugin URI:  https://github.com/teledirigido/WP-Facebook-Page-Importer
Description: Dead simple post importer for your Facebook Page
Version:     0.1a
Author:      Miguel Garrido
Author URI:  http://blacksheepdesign.co.nz
License:     GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: fpi

Facebook Page Importer is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.
 
Facebook Page Importer is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.
 
You should have received a copy of the GNU General Public License
Facebook Page Importer. If not, see {License URI}.

*/

// Helpers
require_once 'helpers/post_type.php';
require_once __DIR__ . '/helpers/facebook-sdk-v5/autoload.php';

// Admin
require_once 'controller.facebook-sdk.php';
require_once 'admin.fpi.php';
require_once 'controller.fpi.php';

// JS Scripts
add_action('admin_enqueue_scripts',function($hook){
	
	if( $hook !== 'tools_page_fpi-settings' )
		return false;

	wp_enqueue_style( 'fpi-css',
		plugin_dir_url( __FILE__ ) . 'css/style.css', 
		__FILE__,
		'1.0',
		'screen'
	);
	
	/*

	DEPRECATED

	----------

	wp_enqueue_script('fpi-scripts', 
		plugin_dir_url( __FILE__ ) . 'js/scripts.js', 
		array('jquery'), 
		'1.0', 
		true 
	);

	wp_enqueue_script('fpi-import', 
		plugin_dir_url( __FILE__ ) . 'js/import.js', 
		array('jquery'), 
		'1.0', 
		true 
	);

	// wp_localize_script( 'fpi-import', 'fpiimport', array(
	// 	'ajaxurl' => admin_url( 'admin-ajax.php' )
	// ));


	wp_enqueue_script('fpi-mustache', 
		plugin_dir_url( __FILE__ ) . 'helpers/mustache.min.js', 
		array('jquery'), 
		'1.0', 
		true 
	);
	
	*/


});
