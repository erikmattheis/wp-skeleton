<?php
/*
Plugin Name: GSN Web Services
Plugin URI: http://wordpress.org/extend/plugins/gsn-web-services
Description: Includes the GSN Web Services PHP libraries, stores client keys, and allows other plugins to hook into it
Author: Tom Noogen
Version: 0.0.1
Author URI: http://groceryshopping.net
Text Domain: gsn-web-services
Domain Path: /languages/
Network: True
*/

// Copyright (c) 2015 Grocery Shopping Network. All rights reserved.
//
// Released under the GPL license
// http://www.opensource.org/licenses/gpl-license.php
//
// **********************************************************************
// This program is distributed in the hope that it will be useful, but
// WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
// **********************************************************************

$GLOBALS['gsn_meta']['gsn-web-services']['version'] = '0.0.1';

add_action( 'init', 'gsn_web_services_init' );

/**
 * Fire up the plugin if compatibility checks have been met
 */
function gsn_web_services_require_files() {
	$abspath = dirname( __FILE__ );
	require_once $abspath . '/classes/gsn-apiclient.php';
	require_once $abspath . '/classes/gsn-plugin-base.php';
	require_once $abspath . '/classes/gsn-web-services.php';
}

function gsn_web_services_init() {
	$abspath = dirname( __FILE__ );
	gsn_web_services_require_files();
  global $gsn_web_services;
	$gsn_web_services = new Gsn_Web_Services( __FILE__ );
}

// base on http://codex.wordpress.org/Plugin_API/Action_Reference
// we intercept at the very earliest event "parse_request" to handle json proxy
// parse_request is the earliest in order to have access to the database
add_action( 'parse_request', 'gsn_json_proxy_load');

function gsn_json_proxy_load() {
  if ( preg_match( '/proxy\/(.*)/i', $_SERVER["REQUEST_URI"] ) ) {
    include( dirname( __FILE__ ) . '/gsn-json-proxy.php' );
    die(0);
  }

  return;
}
