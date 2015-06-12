<?php
/**
 * Plugin Name: WordCamp BH - Facebook Likebox Widget
 * Plugin URI: https://github.com/WordPressBeloHorizonte/hackathon-exemplos
 * Description: Este plugin permite você utilizar um widget com o Likebox do Facebook.
 * Author: WordCamp Belo Horizonte
 * Author URI: http://belohorizonte.wordcamp.org/
 * Version: 1.0.0
 * License: GPLv2 or later
 * Text Domain: facebook-likebox-widget
 * Domain Path: languages/
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( 'WC_BH_Facebook_Likebox_Widget' ) ) :

/**
 * Facebook likebox widget.
 *
 * Baseado em https://codex.wordpress.org/Widgets_API
 */
class WC_BH_Facebook_Likebox_Widget extends WP_Widget {

	/**
	 * Registra o widget no WordPress WordPress.
	 */
	public function __construct() {
		parent::__construct(
			'wc_bh_facebook_likebox',
			__( 'Facebook Like Box', 'facebook-likebox-widget' ),
			array( 'description' => __( 'This widget includes a facebook likebox on your blog', 'facebook-likebox-widget' ), )
		);
	}

	/**
	 * Formulário do widget no backend do WordPress.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param  array $instance Dados salvos anteriormente no banco de dados.
	 *
	 * @return string
	 */
	public function form( $instance ) {
		$title         = isset( $instance['title'] ) ? $instance['title'] : '';
		$url           = isset( $instance['url'] ) ? $instance['url'] : 'https://www.facebook.com/WordCampBeloHorizonte';
		$width         = isset( $instance['width'] ) ? $instance['width'] : 300;
		$height        = isset( $instance['height'] ) ? $instance['height'] : 500;
		$color_scheme  = isset( $instance['color_scheme'] ) ? $instance['color_scheme'] : 'light';
		$friends_faces = isset( $instance['friends_faces'] ) ? $instance['friends_faces'] : 1;
		$show_posts    = isset( $instance['show_posts'] ) ? $instance['show_posts'] : 0;
		$show_header   = isset( $instance['show_header'] ) ? $instance['show_header'] : 0;
		$show_border   = isset( $instance['show_border'] ) ? $instance['show_border'] : 0;

		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>">
				<?php _e( 'Title:', 'facebook-likebox-widget' ); ?>
				<input id="<?php echo $this->get_field_id( 'title' ); ?>" class="widefat" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
			</label>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'url' ); ?>">
				<?php _e( 'Facebook Page URL:', 'facebook-likebox-widget' ); ?>
				<input id="<?php echo $this->get_field_id( 'url' ); ?>" class="widefat" name="<?php echo $this->get_field_name( 'url' ); ?>" type="text" value="<?php echo esc_attr( $url ); ?>" />
			</label>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'width' ); ?>">
				<?php _e( 'Width:', 'facebook-likebox-widget' ); ?>
				<input id="<?php echo $this->get_field_id( 'width' ); ?>" size="5" name="<?php echo $this->get_field_name( 'width' ); ?>" type="text" value="<?php echo intval( $width ); ?>" />
			</label>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'height' ); ?>">
				<?php _e( 'Height:', 'facebook-likebox-widget' ); ?>
				<input id="<?php echo $this->get_field_id( 'height' ); ?>" size="5" name="<?php echo $this->get_field_name( 'height' ); ?>" type="text" value="<?php echo intval( $height ); ?>" />
			</label>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'color_scheme' ); ?>">
				<?php _e( 'Color Scheme:', 'facebook-likebox-widget' ); ?>
				<select id="<?php echo $this->get_field_id( 'color_scheme' ); ?>" class="widefat" name="<?php echo $this->get_field_name( 'color_scheme' ); ?>">
					<option value="dark" <?php selected( 'dark', $color_scheme, true ); ?>><?php _e( 'Dark','facebook-likebox-widget' ); ?></option>
					<option value="light" <?php selected( 'light', $color_scheme, true ); ?>><?php _e( 'Light','facebook-likebox-widget' ); ?></option>
				</select>
			</label>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'friends_faces' ); ?>">
				<input id="<?php echo $this->get_field_id( 'friends_faces' ); ?>" name="<?php echo $this->get_field_name( 'friends_faces' ); ?>" type="checkbox" value="1" <?php checked( 1, $friends_faces, true ); ?> /> <?php _e( 'Show Friends Faces', 'facebook-likebox-widget' ); ?>
			</label>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'show_posts' ); ?>">
				<input id="<?php echo $this->get_field_id( 'show_posts' ); ?>" name="<?php echo $this->get_field_name( 'show_posts' ); ?>" type="checkbox" value="1" <?php checked( 1, $show_posts, true ); ?> /> <?php _e( 'Show Posts', 'facebook-likebox-widget' ); ?>
			</label>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'show_header' ); ?>">
				<input id="<?php echo $this->get_field_id( 'show_header' ); ?>" name="<?php echo $this->get_field_name( 'show_header' ); ?>" type="checkbox" value="1" <?php checked( 1, $show_header, true ); ?> /> <?php _e( 'Show Header', 'facebook-likebox-widget' ); ?>
			</label>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'show_border' ); ?>">
				<input id="<?php echo $this->get_field_id( 'show_border' ); ?>" name="<?php echo $this->get_field_name( 'show_border' ); ?>" type="checkbox" value="1" <?php checked( 1, $show_border, true ); ?> /> <?php _e( 'Show Border', 'facebook-likebox-widget' ); ?>
			</label>
		</p>
		<?php
	}

	/**
	 * Escapa e limpa dados do formulário antes de salvar.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param  array $new_instance Novos valores enviados.
	 * @param  array $old_instance Valores salvos anteriormente no banco de dados.
	 *
	 * @return array               Dados limpos e prontos para serem salvos.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title']         = ( ! empty( $new_instance['title'] ) ) ? sanitize_text_field( $new_instance['title'] ) : '';
		$instance['url']           = ( ! empty( $new_instance['url'] ) ) ? esc_url( $new_instance['url'] ) : '';
		$instance['width']         = ( ! empty( $new_instance['width'] ) ) ? intval( $new_instance['width'] ) : 300;
		$instance['height']        = ( ! empty( $new_instance['height'] ) ) ? intval( $new_instance['height'] ) : 500;
		$instance['color_scheme']  = ( ! empty( $new_instance['color_scheme'] ) ) ? sanitize_text_field( $new_instance['color_scheme'] ) : 'light';
		$instance['friends_faces'] = ( ! empty( $new_instance['friends_faces'] ) ) ? intval( $new_instance['friends_faces'] ) : 0;
		$instance['show_posts']    = ( ! empty( $new_instance['show_posts'] ) ) ? intval( $new_instance['show_posts'] ) : 0;
		$instance['show_header']   = ( ! empty( $new_instance['show_header'] ) ) ? intval( $new_instance['show_header'] ) : 0;
		$instance['show_border']   = ( ! empty( $new_instance['show_border'] ) ) ? intval( $new_instance['show_border'] ) : 0;

		return $instance;
	}

	/**
	 * Exibe o conteúdo do widget.
	 *
	 * @param  array  $args      Argumentos do Widget (título e HTML que será exibido antes e depois do widget e do título).
	 * @param  array  $instance  Opções do widget.
	 *
	 * @return string
	 */
	public function widget( $args, $instance ) {
		$title = apply_filters( 'widget_title', $instance['title'] );

		echo $args['before_widget'];
		if ( ! empty( $title ) ) {
			echo $args['before_title'] . $title . $args['after_title'];
		}

		echo sprintf(
			'<iframe src="//www.facebook.com/plugins/likebox.php?href=%1$s&amp;width=%2$d&amp;height=%3$d&amp;colorscheme=%4$s&amp;show_faces=%5$d&amp;header=%6$d&amp;stream=%7$d&amp;show_border=%8$d" scrolling="no" frameborder="0" style="border: none; overflow: hidden; width: %2$dpx; height: %3$dpx;" allowTransparency="true"></iframe>',
			$instance['url'],
			$instance['width'],
			$instance['height'],
			$instance['color_scheme'],
			$instance['friends_faces'],
			$instance['show_posts'],
			$instance['show_header'],
			$instance['show_border']
		);

		echo $args['after_widget'];
	}
}

/**
 * Registra o Widget no WordPress.
 */
function wc_bh_register_facebook_likebox_widget() {
	register_widget( 'WC_BH_Facebook_Likebox_Widget' );
}

add_action( 'widgets_init', 'wc_bh_register_facebook_likebox_widget' );

/**
 * Carrega o textdomain do plugin.
 *
 * Documentação: https://codex.wordpress.org/Function_Reference/load_plugin_textdomain
 */
function wc_bh_load_textdomain() {
	load_plugin_textdomain( 'facebook-likebox-widget', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
}

add_action( 'plugins_loaded', 'wc_bh_load_textdomain' );

endif;
