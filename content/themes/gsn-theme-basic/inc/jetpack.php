<?php
/**
 * Jetpack Compatibility File
 * See: http://jetpack.me/
 *
 * @package gsnbasic
 */

/**
 * Add theme support for Infinite Scroll.
 * See: http://jetpack.me/support/infinite-scroll/
 */
function gsnbasic_jetpack_setup() {
	add_theme_support( 'infinite-scroll', array(
		'type' 		=> 'click',
		'container' => 'main',
		'footer'    => 'page',
	) );
}
add_action( 'after_setup_theme', 'gsnbasic_jetpack_setup' );
