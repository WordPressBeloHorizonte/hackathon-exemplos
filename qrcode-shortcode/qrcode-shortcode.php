<?php
/**
 * Plugin Name: WordCamp BH - QRCode Shortcode
 * Plugin URI: https://github.com/WordPressBeloHorizonte/hackathon-exemplos
 * Description: Este plugin permite você criar QRCodes dentro dos seus posts utilizando shortcodes.
 * Author: WordCamp Belo Horizonte
 * Author URI: http://belohorizonte.wordcamp.org/
 * Version: 1.0.0
 * License: GPLv2 or later
 * Text Domain: qrcode-shortcode
 * Domain Path: languages/
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Adiciona o shortcode para QRCode baseado no jquery.qrcode.js
 *
 * Documentação da API de shortcodes: https://codex.wordpress.org/Shortcode_API
 * Documentação do jquery.qrcode.js: https://github.com/jeromeetienne/jquery-qrcode
 *
 * Exemplo do shortcode:
 *
 * [qrcode width="100" height="100" content="http://belohorizonte.wordcamp.org/"]
 */
function wc_bh_qrcode_shortcode_register( $atts ) {
	$args = shortcode_atts( array(
		'width'   => 100,
		'height'  => 100,
		'content' => 'http://belohorizonte.wordcamp.org/'
	), $atts );

	// Escapa os dados inseridos pelo usuário.
	$width   = intval( $args['width'] );
	$height  = intval( $args['height'] );
	$content = esc_js( sanitize_text_field( $args['content'] ) );

	// Exibe o conteúdo do shortcode.
	return "<div class='qrcode-shortcode'></div>
	<script>
		jQuery( document ).ready( function( $ ) {
			$( '.qrcode-shortcode' ).qrcode({
				width: $width,
				height: $height,
				text: '$content'
			});
		});
	</script>";
}

add_shortcode( 'qrcode', 'wc_bh_qrcode_shortcode_register' );

/**
 * Carrega os arquivos de JS.
 *
 * Documentação: https://codex.wordpress.org/Function_Reference/wp_enqueue_script
 */
function wc_bh_qrcode_shortcode_scripts() {
	wp_enqueue_script( 'jquery-qrcode', plugins_url( '/assets/js/jquery.qrcode.min.js' , __FILE__ ), array( 'jquery' ), '1.0.0', true );
}

add_action( 'wp_enqueue_scripts', 'wc_bh_qrcode_shortcode_scripts' );

/**
 * Carrega o textdomain do plugin.
 *
 * Documentação: https://codex.wordpress.org/Function_Reference/load_plugin_textdomain
 */
function wc_bh_qrcode_shortcode_load_textdomain() {
	load_plugin_textdomain( 'qrcode-shortcode', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
}

add_action( 'plugins_loaded', 'wc_bh_qrcode_shortcode_load_textdomain' );
