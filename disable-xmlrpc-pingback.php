<?php
/**
 * Plugin Name:       Disable XML-RPC & Pingback Completely
 * Plugin URI:        https://jobforweb.ru/
 * Description:       Полностью отключает XML-RPC, pingback/trackback, HTTP-заголовок X-Pingback и тег <link rel="pingback"> на страницах сайта, включая WooCommerce.
 * Version:           1.1.0
 * Requires at least: 5.3
 * Requires PHP:      7.4
 * Author:            LVI
 * Author URI:        https://jobforweb.ru
 * Text Domain:       disable-xmlrpc-pingback
 * License:           GPLv2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 *
 * @package DXP
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! defined( 'DXP_VERSION' ) ) {
	define( 'DXP_VERSION', '1.1.0' );
}

/**
 * Load plugin translations.
 *
 * @return void
 */
function dxp_load_textdomain() {
	load_plugin_textdomain(
		'disable-xmlrpc-pingback',
		false,
		dirname( plugin_basename( __FILE__ ) ) . '/languages'
	);
}
add_action( 'init', 'dxp_load_textdomain' );

/**
 * Make every XML-RPC method unavailable.
 *
 * @return array Empty list of allowed methods.
 */
function dxp_xmlrpc_methods() {
	return array();
}
add_filter( 'xmlrpc_methods', 'dxp_xmlrpc_methods' );

/**
 * Remove the X-Pingback header from HTTP responses (case-insensitive).
 *
 * @param array $headers Response headers.
 * @return array
 */
function dxp_remove_x_pingback_header( $headers ) {
	foreach ( array_keys( $headers ) as $name ) {
		if ( 0 === strcasecmp( 'X-Pingback', (string) $name ) ) {
			unset( $headers[ $name ] );
		}
	}
	return $headers;
}
add_filter( 'wp_headers', 'dxp_remove_x_pingback_header' );

/**
 * Suppress the value returned by bloginfo( 'pingback_url' ).
 *
 * @param string $output   Current output.
 * @param string $property Requested bloginfo property.
 * @return string
 */
function dxp_empty_pingback_url( $output, $property ) {
	return 'pingback_url' === $property ? '' : $output;
}
add_filter( 'bloginfo_url', 'dxp_empty_pingback_url', 11, 2 );

/**
 * Strip <link rel="pingback"> from the final HTML document.
 *
 * @param string $html Buffered document.
 * @return string
 */
function dxp_strip_pingback_link( $html ) {
	if ( ! is_string( $html ) || '' === $html ) {
		return $html;
	}

	$result = preg_replace( '/<link\b[^>]*\brel\s*=\s*(["\'])pingback\1[^>]*>\s*/i', '', $html );

	return null === $result ? $html : $result;
}

/**
 * Start output buffering for front-end HTML documents only.
 *
 * @return void
 */
function dxp_start_output_buffer() {
	if ( is_admin() || is_feed() || is_trackback() || is_robots() || wp_is_json_request() ) {
		return;
	}

	ob_start( 'dxp_strip_pingback_link' );
}
add_action( 'template_redirect', 'dxp_start_output_buffer', 0 );

// Core-level switches: XML-RPC protocol, pingbacks, discovery links.
add_filter( 'xmlrpc_enabled', '__return_false' );
add_filter( 'pings_open', '__return_false', PHP_INT_MAX );
remove_action( 'wp_head', 'rsd_link' );
remove_action( 'wp_head', 'wlwmanifest_link' );
