<?php
/**
 * Plugin Name: WordCamp BH - Share Buttons
 * Plugin URI: https://github.com/WordPressBeloHorizonte/hackathon-exemplos
 * Description: Este plugin permite você adicionar botões de compartilhar em seus posts ou páginas.
 * Author: WordCamp Belo Horizonte
 * Author URI: http://belohorizonte.wordcamp.org/
 * Version: 1.0.0
 * License: GPLv2 or later
 * Text Domain: share-buttons
 * Domain Path: languages/
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Carrega o textdomain do plugin.
 *
 * Documentação: https://codex.wordpress.org/Function_Reference/load_plugin_textdomain
 */
function wc_bh_share_buttons_load_textdomain() {
	load_plugin_textdomain( 'share-buttons', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
}

add_action( 'plugins_loaded', 'wc_bh_share_buttons_load_textdomain' );



//////////////////////////////
// Funções de administração //
//////////////////////////////



/**
 * Adiciona uma tela de opções.
 *
 * Documentação: https://codex.wordpress.org/Function_Reference/add_options_page
 */
function wc_bh_share_buttons_add_options_page() {
	add_options_page(
		__( 'Share Buttons', 'share-buttons' ), // Título da página
		__( 'Share Buttons', 'share-buttons' ), // Nome no menu
		'manage_options', // Tipo de permissão necessária para editar o plugin
		'share-buttons',  // ID da página
		'wc_bh_share_buttons_options_page' // Função que mostra página
	);
}

add_action( 'admin_menu', 'wc_bh_share_buttons_add_options_page' );

/**
 * Página de opções.
 */
function wc_bh_share_buttons_options_page() {
	?>
	<div class="wrap">
		<h2><?php _e( 'Share Buttons Options', 'share-buttons' ); ?></h2>
		<form method="post" action="options.php">
			<?php
				// Inicia as opções.
				// Documentação: https://codex.wordpress.org/Function_Reference/settings_fields
				settings_fields( 'share_buttons_options' );

				// Mostra as opções.
				// Documentação: https://codex.wordpress.org/Function_Reference/do_settings_sections
				do_settings_sections( 'share_buttons_options' );

				// Exibe botão para salvar.
				// Documentação: https://codex.wordpress.org/Function_Reference/submit_button
				submit_button();
			?>
		</form>
	</div>
	<?php
}

/**
 * Adiciona as opções do plugin.
 *
 * Documentação: https://codex.wordpress.org/Settings_API
 */
function wc_bh_share_buttons_plugin_options() {
	// ID da opções
	$id = 'share_buttons_options';

	/**
	 * Display options.
	 */

	// Adiciona uma sessão de opções.
	// Documentação: https://codex.wordpress.org/Function_Reference/add_settings_section
	add_settings_section(
		'share_buttons_display',
		__( 'Display', 'share-buttons' ),
		'__return_false',
		$id
	);

	// Adiciona uma opção.
	// Documentação: https://codex.wordpress.org/Function_Reference/add_settings_field
	add_settings_field(
		'display',
		__( 'Display buttons in', 'share-buttons' ),
		'wc_bh_share_buttons_option_display_callback',
		$id,
		'share_buttons_display'
	);

	/**
	 * Twitter options.
	 */
	add_settings_section(
		'share_buttons_twitter',
		__( 'Twitter', 'share-buttons' ),
		'__return_false',
		$id
	);
	add_settings_field(
		'twitter_username',
		__( 'Username', 'share-buttons' ),
		'wc_bh_share_buttons_option_twitter_username_callback',
		$id,
		'share_buttons_twitter'
	);

	/**
	 * Facebook options.
	 */
	add_settings_section(
		'share_buttons_facebook',
		__( 'Facebook', 'share-buttons' ),
		'__return_false',
		$id
	);
	add_settings_field(
		'facebook_app_id',
		__( 'APP ID', 'share-buttons' ),
		'wc_bh_share_buttons_option_facebook_app_id_callback',
		$id,
		'share_buttons_facebook'
	);

	// Registra as configurações
	// Documentação: https://codex.wordpress.org/Function_Reference/register_setting
	register_setting( $id, $id, 'wc_bh_share_buttons_validate_options' );
}

add_action( 'admin_init', 'wc_bh_share_buttons_plugin_options' );

/**
 * Exibe campo para selecionar se os botões irão aparecer dentro de loops ou dentro de páginas.
 */
function wc_bh_share_buttons_option_display_callback() {
	$options = get_option( 'share_buttons_options', array() );
	$saved   = isset( $options['display'] ) ? $options['display'] : 'all';
	?>
		<select name="share_buttons_options[display]">
			<option value="all" <?php selected( $saved, 'all', true ); ?>><?php _e( 'All pages', 'share-buttons' ); ?></option>
			<option value="home_archives" <?php selected( $saved, 'home_archives', true ); ?>><?php _e( 'Home and archives only', 'share-buttons' ); ?></option>
			<option value="posts_pages" <?php selected( $saved, 'posts_pages', true ); ?>><?php _e( 'Inside posts and pages only', 'share-buttons' ); ?></option>
		</select>
	<?php
}

/**
 * Exibe campo para inserir username do Twitter.
 */
function wc_bh_share_buttons_option_twitter_username_callback() {
	$options = get_option( 'share_buttons_options', array() );
	$saved   = isset( $options['twitter_username'] ) ? $options['twitter_username'] : 'wordcampbh';
	?>
	<input type="text" name="share_buttons_options[twitter_username]" class="regular-text" value="<?php echo esc_attr( $saved ); ?>" />
	<?php
}

/**
 * Exibe campo para inserir ID do APP no Facebook.
 */
function wc_bh_share_buttons_option_facebook_app_id_callback() {
	$options = get_option( 'share_buttons_options', array() );
	$saved   = isset( $options['facebook_app_id'] ) ? $options['facebook_app_id'] : '148189008594516';
	?>
	<input type="text" name="share_buttons_options[facebook_app_id]" class="regular-text" value="<?php echo esc_attr( $saved ); ?>" />
	<?php
}

/**
 * Limpa os campos salvos.
 * sanitize_text_field() vai eliminar qualquer elemento HTML.
 */
function wc_bh_share_buttons_validate_options( $input ) {
	$output = array();

	foreach ( $input as $key => $value ) {
		if ( isset( $input[ $key ] ) ) {
			$output[ $key ] = sanitize_text_field( $input[ $key ] );
		}
	}

	return $output;
}



/////////////////////////
// Funções do frontend //
/////////////////////////



/**
 * Função para ajudar a pegar as opções do plugin.
 */
function wc_bh_share_buttons_get_options() {
	$default = array(
		'display'          => 'all',
		'twitter_username' => 'wordcampbh',
		'facebook_app_id'  => '148189008594516',
	);

	return get_option( 'share_buttons_options', $default );
}

/**
 * Função para verificar se pode exibir os botões ou não baseado na opção de "Display".
 */
function wc_bh_share_buttons_check_if_can_display( $where ) {
	switch ( $where ) {
		case 'home_archives' :
			$display = is_front_page() || is_home() || is_archive();
			break;
		case 'posts_pages' :
			$display = is_singular();
			break;

		default :
			$display = true;
			break;
	}

	return $display;
}

/**
 * Exibe os botões de baixo do conteúdo com o filtro the_content.
 *
 * Documentação: https://codex.wordpress.org/Plugin_API/Filter_Reference/the_content
 */
function wc_bh_share_buttons_display_buttons( $content ) {
	$options = wc_bh_share_buttons_get_options();

	if ( wc_bh_share_buttons_check_if_can_display( $options['display'] ) ) {
		$twitter = '<a href="https://twitter.com/share" class="twitter-share-button" data-url="' . get_permalink() . '" data-text="' . get_the_title() . '" data-via="' . esc_attr( $options['twitter_username'] ) . '">' . __( 'Tweet', 'share-buttons' ) . '</a>';

		$facebook = '<div class="fb-like" data-href="' . get_permalink() . '" data-layout="button_count" data-action="like" data-show-faces="false" data-share="true"></div>';

		return $content . '<div class="share-buttons"> ' . $twitter . $facebook . ' </div>';
	} else {
		return $content;
	}
}

add_filter( 'the_content', 'wc_bh_share_buttons_display_buttons', 30 );

/**
 * Adicionar scripts do Facebook e Twitter no rodapé do site, antes da tag </body>.
 *
 * Documentação: https://codex.wordpress.org/Function_Reference/wp_footer
 */
function wc_bh_share_buttons_footer_scripts() {
	$locale  = get_locale();
	$options = wc_bh_share_buttons_get_options();
	?>
	<!-- Twitter -->
	<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>
	<!-- Facebook -->
	<div id="fb-root"></div>
	<script>(function(d, s, id) {
		var js, fjs = d.getElementsByTagName(s)[0];
		if (d.getElementById(id)) return;
		js = d.createElement(s); js.id = id;
		js.src = "//connect.facebook.net/<?php echo esc_attr( $locale ); ?>/sdk.js#xfbml=1&version=v2.3&appId=<?php echo esc_js( $options['facebook_app_id'] ); ?>";
		fjs.parentNode.insertBefore(js, fjs);
	}(document, 'script', 'facebook-jssdk'));</script>
	<?php
}

add_action( 'wp_footer', 'wc_bh_share_buttons_footer_scripts', 999 );

/**
 * Carrega os arquivos de CSS.
 *
 * Documentação: https://codex.wordpress.org/Function_Reference/wp_enqueue_style
 */
function wc_bh_share_buttons_styles() {
	wp_enqueue_style( 'share-buttons', plugins_url( '/assets/css/share-buttons.css' , __FILE__ ), array(), '1.0.0', 'all' );
}

add_action( 'wp_enqueue_scripts', 'wc_bh_share_buttons_styles' );
