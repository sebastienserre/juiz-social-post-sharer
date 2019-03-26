<?php if ( ! defined( 'ABSPATH' ) ) {
	die( 'Cheatin&#8217; uh?' );
}

include_once('register-settings.php');

if ( ! function_exists( 'juiz_sps_plugin_action_links' ) ) {
	/**
	 * Add link to settings into Plugins Page
	 * @param  array $links  Array of links.
	 * @param  string $file  File root.
	 * @return array         New array of links.
	 *
	 * @since 1.0.0
	 * @updated 1.5.0
	 * @author Geoffrey Crofte
	 */
	function juiz_sps_plugin_action_links( $links, $file ) {
		$links[] = '<a href="' . admin_url( 'options-general.php?page=' . JUIZ_SPS_SLUG ) . '">' . __( 'Settings' ) . '</a>';
		return $links;
	}
	add_filter( 'plugin_action_links_' . plugin_basename( JUIZ_SPS_FILE ), 'juiz_sps_plugin_action_links',  10, 2 );
}

if ( ! function_exists( 'add_juiz_sps_settings_page' ) ) {
	/**
	 * Create a page of options
	 * @since  1.0.0
	 * @updated 1.5.0
	 * @author  Geoffrey Crofte
	 */
	function add_juiz_sps_settings_page() {
		add_submenu_page( 
			'options-general.php', 
			__( 'Social Post Sharer', 'juiz-social-post-sharer' ),
			__( 'Social Post Sharer', 'juiz-social-post-sharer' ),
			'administrator',
			JUIZ_SPS_SLUG ,
			'juiz_sps_settings_page' 
		);
	}
	add_action( 'admin_menu', 'add_juiz_sps_settings_page' );
}

if ( ! function_exists( 'jsps_load_custom_wp_admin_assets' ) ) {
	/**
	 * Include Admin dedicated scripts.
	 * @return void
	 * @author Geoffrey Crofte
	 * @since 1.5
	 */
	function jsps_load_custom_wp_admin_assets() {
		global $current_screen;

		if ( $current_screen->base !== 'settings_page_juiz-social-post-sharer' ) {
			return;
		}
		
		wp_enqueue_script( 'jsps-admin-script', JUIZ_SPS_PLUGIN_ASSETS . 'admin/admin.js', array('jquery'), JUIZ_SPS_VERSION );
		wp_enqueue_style( 'jsps-admin-styles', JUIZ_SPS_PLUGIN_ASSETS . 'admin/admin.css', 
			array(), JUIZ_SPS_VERSION, 'all' );
	}
	add_action( 'admin_enqueue_scripts', 'jsps_load_custom_wp_admin_assets' );
}

/**
 * Sections and fields for settings.
 */
function add_juiz_sps_plugin_options() {
	// all options in single registration as array
	register_setting( JUIZ_SPS_SETTING_NAME, JUIZ_SPS_SETTING_NAME, 'juiz_sps_sanitize' );
	
	add_settings_section( 'juiz_sps_plugin_main', __( 'Themes &amp; Networks', 'juiz-social-post-sharer' ), 'juiz_sps_section_text', JUIZ_SPS_SLUG);
	add_settings_field( 'juiz_sps_style_choice', __( 'Choose a style to display', 'juiz-social-post-sharer' ), 'juiz_sps_setting_radio_style_choice', JUIZ_SPS_SLUG, 'juiz_sps_plugin_main');
	add_settings_field( 'juiz_sps_temp_submit', get_submit_button( __( 'Save Changes' ), 'secondary' ), '__return_empty_string', JUIZ_SPS_SLUG, 'juiz_sps_plugin_main' );
	add_settings_field( 'juiz_sps_network_selection', __( 'Display those following social networks:', 'juiz-social-post-sharer' ) , 'juiz_sps_setting_checkbox_network_selection', JUIZ_SPS_SLUG, 'juiz_sps_plugin_main' );
	add_settings_field( 'juiz_sps_twitter_user', __( 'What is your Twitter user name to be mentioned?', 'juiz-social-post-sharer' ) , 'juiz_sps_setting_input_twitter_user', JUIZ_SPS_SLUG, 'juiz_sps_plugin_main' );
	add_settings_field( 'juiz_sps_temp_submit_1', get_submit_button( __( 'Save Changes' ), 'primary' ), '__return_empty_string', JUIZ_SPS_SLUG, 'juiz_sps_plugin_main' );


	add_settings_section( 'juiz_sps_plugin_display_in', __( 'Display settings','juiz-social-post-sharer'), 'juiz_sps_section_text_display', JUIZ_SPS_SLUG );
	add_settings_field( 'juiz_sps_display_in_types', __( 'What type of content must have buttons?', 'juiz-social-post-sharer' ), 'juiz_sps_setting_checkbox_content_type', JUIZ_SPS_SLUG, 'juiz_sps_plugin_display_in' );
	add_settings_field( 'juiz_sps_display_where', __( 'Where do you want to display buttons?','juiz-social-post-sharer' ), 'juiz_sps_setting_radio_where', JUIZ_SPS_SLUG, 'juiz_sps_plugin_display_in' );
	add_settings_field( 'juiz_sps_hide_social_name', __( 'Show only social icon?', 'juiz-social-post-sharer' ) . '<br /><em>(' . __( 'hide text, show it on mouse over or focus', 'juiz-social-post-sharer' ) . ')</em>', 'juiz_sps_setting_radio_hide_social_name', JUIZ_SPS_SLUG, 'juiz_sps_plugin_display_in' );
	add_settings_field( 'juiz_sps_temp_submit_2', get_submit_button( __( 'Save Changes' ), 'primary' ), '__return_empty_string', JUIZ_SPS_SLUG, 'juiz_sps_plugin_display_in' );


	add_settings_section( 'juiz_sps_plugin_advanced', __( 'Advanced settings','juiz-social-post-sharer' ), 'juiz_sps_section_text_advanced', JUIZ_SPS_SLUG );
	add_settings_field( 'juiz_sps_target_link', __( 'Open links in a new window?', 'juiz-social-post-sharer' ).'<br /><em>(' . sprintf( __( 'adds a %s attribute', 'juiz-social-post-sharer' ), '<code>target="_blank"</code>' ) . ')</em>', 'juiz_sps_setting_radio_target_link', JUIZ_SPS_SLUG, 'juiz_sps_plugin_advanced');
	add_settings_field( 'juiz_sps_force_pinterest_snif', __( 'Force Pinterest button sniffing all images of the page?', 'juiz-social-post-sharer' ) . '<br /><em>(' . __( 'need JavaScript', 'juiz-social-post-sharer' ) . ')</em>', 'juiz_sps_setting_radio_force_snif', JUIZ_SPS_SLUG, 'juiz_sps_plugin_advanced');
	add_settings_field( 'juiz_sps_counter', __( 'Display counter of sharing?', 'juiz-social-post-sharer' ) . '<br /><em>(' . __( 'need JavaScript', 'juiz-social-post-sharer' ) . ')</em>', 'juiz_sps_setting_radio_counter', JUIZ_SPS_SLUG, 'juiz_sps_plugin_advanced' );
	add_settings_field( 'juiz_sps_counter_option', __( 'For this counter, you want to display:', 'juiz-social-post-sharer' ), 'juiz_sps_setting_radio_counter_option', JUIZ_SPS_SLUG, 'juiz_sps_plugin_advanced' );
	add_settings_field( 'juiz_sps_temp_submit_3', get_submit_button( __( 'Save Changes' ), 'primary' ), '__return_empty_string', JUIZ_SPS_SLUG, 'juiz_sps_plugin_advanced' );


	add_settings_section( 'juiz_sps_plugin_mail_informations', __( 'Customize mail texts', 'juiz-social-post-sharer' ), 'juiz_sps_section_text_mail', JUIZ_SPS_SLUG );
	add_settings_field( 'juiz_sps_mail_subject', __( 'Mail subject:', 'juiz-social-post-sharer' ), 'juiz_sps_setting_input_mail_subject', JUIZ_SPS_SLUG, 'juiz_sps_plugin_mail_informations' );
	add_settings_field( 'juiz_sps_mail_body', __( 'Mail body:', 'juiz-social-post-sharer'), 'juiz_sps_setting_textarea_mail_body', JUIZ_SPS_SLUG, 'juiz_sps_plugin_mail_informations' );
	add_settings_field( 'juiz_sps_temp_submit_4', get_submit_button( __( 'Save Changes' ), 'primary' ), '__return_empty_string', JUIZ_SPS_SLUG, 'juiz_sps_plugin_mail_informations' );


}
add_filter( 'admin_init', 'add_juiz_sps_plugin_options' );


// sanitize posted data

function juiz_sps_sanitize( $options ) {
	
	if ( is_array( $options['juiz_sps_networks'] ) ) {
		
		$temp_array = array( 'facebook' => 0, 'twitter' => 0, 'google' => 0, 'pinterest' => 0, 'viadeo' => 0, 'linkedin' => 0, 'digg' => 0, 'stumbleupon' => 0, 'weibo' => 0, 'mail' => 0, 'vk' => 0 );
		$juiz_sps_opt = jsps_get_option();

		// new option (1.2.0)
		if ( ! in_array( 'weibo', $juiz_sps_opt['juiz_sps_networks'] ) ) {
			$juiz_sps_opt['juiz_sps_networks']['weibo'] = array( 0, __( 'Weibo', 'juiz-social-post-sharer' ) );
		}
		// new option (1.3.0)
		if ( ! in_array( 'vk', $juiz_sps_opt['juiz_sps_networks'] ) ) {
			$juiz_sps_opt['juiz_sps_networks']['vk'] = array( 0, __( 'VKontakte', 'juiz-social-post-sharer' ) );
		}
		// new option (1.4.1)
		if ( ! in_array( 'tumblr', $juiz_sps_opt['juiz_sps_networks'] ) ) {
			$juiz_sps_opt['juiz_sps_networks']['tumblr'] = array( 0, __( 'Tumblr', 'juiz-social-post-sharer' ) );
			$juiz_sps_opt['juiz_sps_networks']['delicious'] = array( 0, __( 'Delicious', 'juiz-social-post-sharer' ) );
			$juiz_sps_opt['juiz_sps_networks']['reddit'] = array( 0, __( 'Reddit', 'juiz-social-post-sharer' ) );
		}
		// new option (1.4.2)
		if ( ! in_array( 'bookmark', $juiz_sps_opt['juiz_sps_networks'] ) ) {
			$juiz_sps_opt['juiz_sps_networks']['bookmark'] = array( 0, __( 'Bookmark', 'juiz-social-post-sharer' ) );
			$juiz_sps_opt['juiz_sps_networks']['print'] = array( 0, __( 'Print', 'juiz-social-post-sharer' ) );
		}

		foreach ( $options['juiz_sps_networks'] as $nw ) {
			$temp_array[ $nw ] = 1;
		}

		foreach ( $temp_array as $k => $v ) {
			$juiz_sps_opt['juiz_sps_networks'][ $k ][0] = $v;
		}

		$newoptions['juiz_sps_networks'] = $juiz_sps_opt['juiz_sps_networks'];
	}


	$newoptions['juiz_sps_style'] = esc_attr( $options['juiz_sps_style'] );
	$newoptions['juiz_sps_hide_social_name'] = (int) $options['juiz_sps_hide_social_name'] == 1 ? 1 : 0;
	$newoptions['juiz_sps_target_link'] = (int) $options['juiz_sps_target_link'] == 1 ? 1 : 0;
	$newoptions['juiz_sps_counter'] = (int) $options['juiz_sps_counter'] == 1 ? 1 : 0;

	// new options (1.1.0)
	$newoptions['juiz_sps_write_css_in_html'] = isset( $options['juiz_sps_write_css_in_html'] ) && (int) $options['juiz_sps_write_css_in_html'] == 1 ? 1 : 0;
	$newoptions['juiz_sps_twitter_user'] = preg_replace( "#@#", '', sanitize_key( $options['juiz_sps_twitter_user'] ) );
	$newoptions['juiz_sps_mail_subject'] = sanitize_text_field( $options['juiz_sps_mail_subject'] );
	$newoptions['juiz_sps_mail_body'] = sanitize_text_field( $options['juiz_sps_mail_body'] );

	if ( is_array( $options['juiz_sps_display_in_types'] ) && count( $options['juiz_sps_display_in_types'] ) > 0 ) {
		$newoptions['juiz_sps_display_in_types'] = $options['juiz_sps_display_in_types'];
	}
	else {
		wp_redirect( admin_url( 'options-general.php?page=' . JUIZ_SPS_SLUG . '&message=1337' ) );
		exit;
	}
	$newoptions['juiz_sps_display_where'] = in_array( $options['juiz_sps_display_where'], array( 'bottom', 'top', 'both', 'nowhere' ) ) ? $options['juiz_sps_display_where'] : 'bottom';
	

	// new options (1.2.5)
	$newoptions['juiz_sps_force_pinterest_snif'] = (int) $options['juiz_sps_force_pinterest_snif'] == 1 ? 1 : 0;

	// new options (1.3.3.7)
	$newoptions['juiz_sps_counter_option'] = in_array( $options['juiz_sps_counter_option'], array( 'both', 'total', 'subtotal' ) ) ? $options['juiz_sps_counter_option'] : 'both';
	
	return $newoptions;
}

// first section text
if ( ! function_exists( 'juiz_sps_section_text' ) ) {
	function juiz_sps_section_text() {
		echo '<p class="juiz_sps_section_intro">' . __( 'Edit the style of your buttons and the networks you want to activate.', 'juiz-social-post-sharer' ) . '</p>';
	}
}

// Radio fields styles choice
if ( ! function_exists( 'juiz_sps_setting_radio_style_choice' ) ) {
function juiz_sps_setting_radio_style_choice() {

	$options       = jsps_get_option();
	$core_themes   = jsps_get_core_themes();
	$custom_themes = jsps_get_custom_themes();

	if ( is_array( $options ) && is_array( $core_themes ) ) {
		
		// Slug of theme activated.
		$current_theme = $options['juiz_sps_style'];
		$all_themes    = $core_themes + $custom_themes;

		foreach ( $all_themes as $slug => $theme ) {
			$theme_author = isset( $theme['author'] ) ? esc_html( $theme['author'] ) : '';
			$theme_author = isset( $theme['author_url'] ) ? '<a href="' . esc_url( $theme['author_url'] ) . '" target="_blank">' . $theme_author . '</a>' : $theme_author;
			$theme_name   = isset( $theme['name'] ) ? esc_html( $theme['name'] ) : __( "This theme doesn't have a name", 'juiz-social-post-sharer' );
			$theme_name   = isset( $theme['author'] ) ? sprintf( __( '%1$s by %2$s', 'juiz-social-post-sharer' ), $theme_name, $theme_author) : $theme_name;
			$demo_src     = isset( $theme['demo_url'] ) ? esc_url( $theme['demo_url'] ) : JUIZ_SPS_PLUGIN_URL . 'themes/' . $slug . '/demo.png';
			$demo_src_2x  = isset( $theme['demo_url_2x'] ) ? esc_url( $theme['demo_url_2x'] ) : JUIZ_SPS_PLUGIN_URL . 'themes/' . $slug . '/demo@2x.png';

			// Print the themes.
			echo '<p class="juiz_sps_styles_options">
					<input id="jsps_style_' . esc_attr( $slug ) . '" value="' . esc_attr( $slug ) . '" name="' . JUIZ_SPS_SETTING_NAME . '[juiz_sps_style]" type="radio" ' . ( $current_theme === $slug ? ' checked="checked"' : '' ) . ' />
					<label for="jsps_style_' . esc_attr( $slug ) . '">
						<span class="juiz_sps_demo_styles">
							<img src="' . $demo_src . '" srcset="' . $demo_src_2x . ' 2x">
						</span>
						<span class="juiz_sps_style_name">' . $theme_name . '</span>
					</label>
				</p>';
		}
	}
}
}

// checkboxes fields for networks
if ( ! function_exists( 'juiz_sps_setting_checkbox_network_selection' ) ) {
function juiz_sps_setting_checkbox_network_selection() {
	$y = $n = '';
	$options = jsps_get_option();
	if ( is_array( $options ) ) {
		
		echo '<div class="juiz-sps-squared-options">';

		foreach ( $options['juiz_sps_networks'] as $k => $v ) {

			$is_checked = ( $v[0] == 1 ) ? ' checked="checked"' : '';
			$is_js_test = ( $k == 'pinterest' ) ? ' <em>(' . __( 'uses JavaScript to work', 'juiz-social-post-sharer' ) . ')</em>' : '';
			$network_name = isset( $v[1] ) ? $v[1] : $k;

			echo '<p class="juiz_sps_options_p">
					<input id="jsps_network_selection_' . $k . '" value="' . $k . '" name="' . JUIZ_SPS_SETTING_NAME . '[juiz_sps_networks][]" type="checkbox"
				' . $is_checked . ' />
			  		<label for="jsps_network_selection_' . $k . '">
			  			<span class="jsps_demo_icon">
			  				<i class="jsps-icon-' . $k . '" aria-hidden="true"></i>
			  			</span>
			  			<span class="jsps_demo_name">' . $network_name . '' . $is_js_test . '</span>
			  		</label>
			  	</p>';
		}

		if ( ! is_array( $options['juiz_sps_networks']['weibo'] ) ) {
			echo '<p class="juiz_sps_options_p"><input id="jsps_network_selection_weibo" value="weibo" name="' . JUIZ_SPS_SETTING_NAME . '[juiz_sps_networks][]" type="checkbox"> <label for="jsps_network_selection_weibo"><span class="jsps_demo_icon jsps_demo_icon_weibo"></span>Weibo</label> <!--em class="jsps_new">(' . __( 'New social network!', 'juiz-social-post-sharer' ) . ')</em--></p>';
		}

		echo '</div>';

	}
}
}

// input for twitter username
if ( ! function_exists( 'juiz_sps_setting_input_twitter_user' ) ) {
function juiz_sps_setting_input_twitter_user() {
	$options = jsps_get_option();
	if ( is_array( $options ) ) {
		$username = isset( $options['juiz_sps_twitter_user'] ) ? $options['juiz_sps_twitter_user'] : '';
	echo '<p class="juiz_sps_options_p">
			<input id="juiz_sps_twitter_user" value="' . esc_attr( $username ) . '" name="' . JUIZ_SPS_SETTING_NAME . '[juiz_sps_twitter_user]" type="text"> <em>(' . __( 'Username without "@"', 'juiz-social-post-sharer' ) . ')</em>
	  	</p>';
	}
}
}


// Advanced section text
if ( ! function_exists( 'juiz_sps_section_text_display' ) ) {
function juiz_sps_section_text_display() {
	echo '<p class="juiz_sps_section_intro">' . __( 'You can choose precisely the types of content that will benefit from the sharing buttons.', 'juiz-social-post-sharer' ) . '</p>';
}
}
// checkbox for type of content
if ( ! function_exists( 'juiz_sps_setting_checkbox_content_type' ) ) {
function juiz_sps_setting_checkbox_content_type() {
	$pts	= get_post_types( array( 'public'=> true, 'show_ui' => true, '_builtin' => true ) );
	$cpts	= get_post_types( array( 'public'=> true, 'show_ui' => true, '_builtin' => false ) );
	$options = jsps_get_option();
	$all_lists_icon = '<span class="dashicons-before dashicons-editor-ul"></span>';
	$all_lists_selected = '';
	if ( is_array( $options['juiz_sps_display_in_types'] ) ) {
		$all_lists_selected = in_array( 'all_lists', $options['juiz_sps_display_in_types'] ) ? 'checked="checked"' : '';
	}

	if ( is_array( $options ) && isset( $options['juiz_sps_display_in_types'] ) && is_array( $options['juiz_sps_display_in_types'] ) ) {
		
		global $wp_post_types;
		$no_icon = '<span class="jsps_no_icon">&#160;</span>';

		// classical post type listing
		foreach ( $pts as $pt ) {

			$selected = in_array( $pt, $options['juiz_sps_display_in_types'] ) ? 'checked="checked"' : '';
			$icon = '';

			switch( $wp_post_types[ $pt ]->name ) {
				case 'post' :
					$icon = 'dashicons-before dashicons-admin-post';
					break;
				case 'page' :
					$icon = 'dashicons-before dashicons-admin-page';
					break;
				case 'attachment' :
					$icon = 'dashicons-before dashicons-admin-media';
					break;
			}
			echo '<p class="juiz_sps_options_p"><input type="checkbox" name="' . JUIZ_SPS_SETTING_NAME . '[juiz_sps_display_in_types][]" id="' . $pt . '" value="' . $pt . '" ' . $selected . '> <label for="' . $pt . '"><span class="' . $icon . '"></span> ' . $wp_post_types[ $pt ]->label . '</label></p>';
		}

		// custom post types listing
		if ( is_array( $cpts ) && ! empty( $cpts ) ) {
			foreach ( $cpts as $cpt ) {

				$selected = in_array( $cpt, $options['juiz_sps_display_in_types'] ) ? 'checked="checked"' : '';
				$icon = $no_icon;

				// image & dashicons support
				if ( isset( $wp_post_types[ $cpt ]->menu_icon ) && $wp_post_types[ $cpt ]->menu_icon ) {
					$icon = preg_match('#dashicons#', $wp_post_types[ $cpt ]->menu_icon) ?
							'<span class="dashicons ' . $wp_post_types[ $cpt ]->menu_icon . '"></span>'
							:
							'<img alt="&#8226;" src="' . esc_url( $wp_post_types[ $cpt ]->menu_icon ) . '" />';
				}

				echo '<p class="juiz_sps_options_p"><input type="checkbox" name="' . JUIZ_SPS_SETTING_NAME . '[juiz_sps_display_in_types][]" id="' . $cpt . '" value="' . $cpt . '" ' . $selected . '> <label for="' . $cpt . '">' . $icon . ' ' . $wp_post_types[ $cpt ]->label . '</label></p>';
			}
		}
	}
	echo '<p class="juiz_sps_options_p"><input type="checkbox" name="' . JUIZ_SPS_SETTING_NAME . '[juiz_sps_display_in_types][]" id="all_lists" value="all_lists" ' . $all_lists_selected . '> <label for="all_lists">' . $all_lists_icon . ' ' . sprintf( __( 'Lists of articles %s(blog, archives, search results, etc.)%s', 'juiz-social-post-sharer' ), '<em>', '</em>' ) . '</label></p>';
}
}

// where display buttons
// radio fields styles choice
if ( ! function_exists( 'juiz_sps_setting_radio_where' ) ) {
function juiz_sps_setting_radio_where() {

	$options = jsps_get_option();

	$w_bottom = $w_top = $w_both = $w_nowhere = '';
	if ( is_array( $options ) && isset( $options['juiz_sps_display_where'] ) )
		${'w_' . $options['juiz_sps_display_where']} = ' checked="checked"';
	
	echo '<p class="juiz_sps_options_p">
			<input id="jsps_w_b" value="bottom" name="' . JUIZ_SPS_SETTING_NAME . '[juiz_sps_display_where]" type="radio" ' . $w_bottom . ' />
			<label for="jsps_w_b">' . __( 'Content bottom', 'juiz-social-post-sharer' ) . '</label>
		</p>
		<p class="juiz_sps_options_p">
			<input id="jsps_w_t" value="top" name="' . JUIZ_SPS_SETTING_NAME . '[juiz_sps_display_where]" type="radio" ' . $w_top . ' />
			<label for="jsps_w_t">'. __( 'Content top', 'juiz-social-post-sharer' ) . '</label>
		</p>
		<p class="juiz_sps_options_p">	
			<input id="jsps_w_2" value="both" name="' . JUIZ_SPS_SETTING_NAME . '[juiz_sps_display_where]" type="radio" ' . $w_both . ' />
			<label for="jsps_w_2">' . __( 'Both', 'juiz-social-post-sharer' ) . '</label>
		</p>
		<p class="juiz_sps_options_p">
			<input id="jsps_w_0" value="nowhere" name="' . JUIZ_SPS_SETTING_NAME . '[juiz_sps_display_where]" type="radio" ' . $w_nowhere . ' />
			<label for="jsps_w_0">' . __( "I'm a ninja, I want to use the shortcode only!", 'juiz-social-post-sharer') . '</label>
		</p>'; // nowhere new in 1.2.2
}
}



// Advanced section text
if ( ! function_exists( 'juiz_sps_section_text_advanced' ) ) {
function juiz_sps_section_text_advanced() {
	echo '<p class="juiz_sps_section_intro">' . __( 'Modify advanced settings of the plugin. Some of them needs JavaScript (only one file loaded)', 'juiz-social-post-sharer' ) . '</p>';
}
}


// radio fields Y or N for hide text
if ( ! function_exists( 'juiz_sps_setting_radio_hide_social_name' ) ) {
function juiz_sps_setting_radio_hide_social_name() {
	$y = $n = '';
	$options = jsps_get_option();

	if ( is_array( $options ) )
		( isset( $options['juiz_sps_hide_social_name'] ) && $options['juiz_sps_hide_social_name'] == 1 ) ? $y = ' checked="checked"' : $n = ' checked="checked"';
	
	echo '
		<img class="jsps-gif-demo" src="' . JUIZ_SPS_PLUGIN_ASSETS . 'img/jsps-icon-only.gif" width="350" height="60">

		<input id="jsps_hide_name_y" value="1" name="' . JUIZ_SPS_SETTING_NAME . '[juiz_sps_hide_social_name]" type="radio" ' . $y . ' />
		<label for="jsps_hide_name_y">' . __( 'Yes', 'juiz-social-post-sharer' ) . '</label>
			
		<input id="jsps_hide_name_n" value="0" name="' . JUIZ_SPS_SETTING_NAME . '[juiz_sps_hide_social_name]" type="radio" ' . $n . ' />
		<label for="jsps_hide_name_n">' . __( 'No', 'juiz-social-post-sharer' ) . '</label>';
}
}

// radio fields Y or N for target _blank
if ( ! function_exists( 'juiz_sps_setting_radio_target_link' ) ) {
function juiz_sps_setting_radio_target_link() {
	$y = $n = '';
	$options = jsps_get_option();

	if ( is_array( $options ) )
		( isset( $options['juiz_sps_target_link'] ) && $options['juiz_sps_target_link'] == 1 ) ? $y = ' checked="checked"' : $n = ' checked="checked"';
	
	echo '<input id="jsps_target_link_y" value="1" name="' . JUIZ_SPS_SETTING_NAME . '[juiz_sps_target_link]" type="radio" ' . $y . ' />
		<label for="jsps_target_link_y">' . __( 'Yes', 'juiz-social-post-sharer' ) . '</label>
			
		<input id="jsps_target_link_n" value="0" name="' . JUIZ_SPS_SETTING_NAME . '[juiz_sps_target_link]" type="radio" ' . $n . ' />
		<label for="jsps_target_link_n">' .  __( 'No', 'juiz-social-post-sharer' ) . '</label>';
}
}

// radio fields Y or N for pinterest sniffing
if ( ! function_exists( 'juiz_sps_setting_radio_force_snif' ) ) {
function juiz_sps_setting_radio_force_snif() {
	$y = $n = '';
	$options = jsps_get_option();

	if ( is_array( $options ) )
		( isset( $options['juiz_sps_force_pinterest_snif'] ) && $options['juiz_sps_force_pinterest_snif'] == 1 ) ? $y = ' checked="checked"' : $n = ' checked="checked"';
	
	echo '	<input id="jsps_forcer_snif_y" value="1" name="' . JUIZ_SPS_SETTING_NAME . '[juiz_sps_force_pinterest_snif]" type="radio" ' . $y . ' />
			<label for="jsps_forcer_snif_y">' . __( 'Yes', 'juiz-social-post-sharer' ) . '</label>
			
			<input id="jsps_forcer_snif_n" value="0" name="' . JUIZ_SPS_SETTING_NAME . '[juiz_sps_force_pinterest_snif]" type="radio" ' . $n . ' />
			<label for="jsps_forcer_snif_n">' . __( 'No', 'juiz-social-post-sharer' ) . '</label>';
}
}

// radio fields Y or N for counter
if ( ! function_exists( 'juiz_sps_setting_radio_counter' ) ) {
function juiz_sps_setting_radio_counter() {

	$y = $n = '';
	$options = jsps_get_option();

	if ( is_array( $options ) )
		( isset( $options['juiz_sps_counter'] ) && $options['juiz_sps_counter'] == 1 ) ? $y = ' checked="checked"' : $n = ' checked="checked"';
	
	echo '	<input id="jsps_counter_y" value="1" name="' . JUIZ_SPS_SETTING_NAME . '[juiz_sps_counter]" type="radio" ' . $y . ' />
			<label for="jsps_counter_y">'. __('Yes', 'juiz-social-post-sharer') . '</label>
			
			<input id="jsps_counter_n" value="0" name="' . JUIZ_SPS_SETTING_NAME . '[juiz_sps_counter]" type="radio" ' . $n . ' />
			<label for="jsps_counter_n">' . __( 'No', 'juiz-social-post-sharer' ) . '</label>';
}
}

// radio fields for what to display as counter
if ( ! function_exists( 'juiz_sps_setting_radio_counter_option' ) ) {
function juiz_sps_setting_radio_counter_option() {

	$options = jsps_get_option();
	if ( is_array( $options ) ) {
		$both 		= ( isset( $options['juiz_sps_counter_option'] ) && $options['juiz_sps_counter_option'] == 'both' ) ? ' checked="checked"' : '';
		$total 		= ( isset( $options['juiz_sps_counter_option'] ) && $options['juiz_sps_counter_option'] == 'total' ) ? ' checked="checked"' : '';
		$subtotal 	= ( isset( $options['juiz_sps_counter_option'] ) && $options['juiz_sps_counter_option'] == 'subtotal' ) ? ' checked="checked"' : '';

		if ( $both == '' && $total == '' && $subtotal == '' ) {
			$both = 'checked="checked"';
		}
	}
	
	echo '	<input id="jsps_counter_both" value="both" name="' . JUIZ_SPS_SETTING_NAME . '[juiz_sps_counter_option]" type="radio" ' . $both . ' />
			<label for="jsps_counter_both">' . __( 'Total &amp; Sub-totals', 'juiz-social-post-sharer' ) . '</label>
			
			<input id="jsps_counter_total" value="total" name="' . JUIZ_SPS_SETTING_NAME . '[juiz_sps_counter_option]" type="radio" ' . $total . ' />
			<label for="jsps_counter_total">' . __( 'Only Total', 'juiz-social-post-sharer' ) . '</label>

			<input id="jsps_counter_subtotal" value="subtotal" name="' . JUIZ_SPS_SETTING_NAME . '[juiz_sps_counter_option]" type="radio" ' . $subtotal . ' />
			<label for="jsps_counter_subtotal">' . __( 'Only Sub-totals', 'juiz-social-post-sharer' ) . '</label>

			<p><strong>' . sprintf( esc_html__( 'Important: Twitter doesn\'t support counter anymore since %sthat decision%s.', 'juiz-social-post-sharer' ), '<a href="https://twittercommunity.com/t/a-new-design-for-tweet-and-follow-buttons/52791" target="_blank">', '</a>' ) . '</strong><span class="juiz-twitter-alternative">' . sprintf( esc_html__( 'I suggest you an alternative using %sNewShareCounts%s. Click the link, enter your website address and click the big blue Twitter button. Authorize this app with your Twitter Account, and that\'s all. You will retrieve your Twitter counts.', 'juiz-social-post-sharer' ), '<a href="http://newsharecounts.com" target="_blank">', '</a>' ) . '</span></p>';
}
}

// Mail section text
if ( ! function_exists( 'juiz_sps_section_text_mail' ) ) {
function juiz_sps_section_text_mail() {
	echo '<p class="juiz_sps_section_intro">' . __( 'You can customize texts to display when visitors share your content by mail button', 'juiz-social-post-sharer' ) . '.<br>' . sprintf( __( 'To perform customization, you can use %s%%%%title%%%%%s, %s%%%%siteurl%%%%%s or %s%%%%permalink%%%%%s variables.', 'juiz-social-post-sharer' ), '<code>', '</code>', '<code>', '</code>', '<code>', '</code>' ) . '</p>';
}
}
if ( ! function_exists( 'juiz_sps_setting_input_mail_subject' ) ) {
function juiz_sps_setting_input_mail_subject() {
	$options = jsps_get_option();
	if ( isset( $options['juiz_sps_mail_subject'] ) ) {
		echo '<input id="juiz_sps_mail_subject" value="' . esc_attr( $options['juiz_sps_mail_subject'] ) . '" name="' . JUIZ_SPS_SETTING_NAME . '[juiz_sps_mail_subject]" type="text">';
	}
}
}
if ( ! function_exists( 'juiz_sps_setting_textarea_mail_body' ) ) {
function juiz_sps_setting_textarea_mail_body() {
	$options = jsps_get_option();
	if ( isset( $options['juiz_sps_mail_body'] ) ) {
		echo '<textarea id="juiz_sps_mail_body" name="' . JUIZ_SPS_SETTING_NAME . '[juiz_sps_mail_body]" cols="55" rows="7">' . esc_textarea( $options['juiz_sps_mail_body'] ) . '</textarea>';
	}
}
}

// The page layout/form
if ( ! function_exists( 'juiz_sps_settings_page' ) ) {
	function juiz_sps_settings_page() {
	?>

	<div id="juiz-sps" class="wrap">
		<div class="jsps-main-content">
			<div class="jsps-main-header">
				<h1><i class="dashicons dashicons-share" aria-hidden="true"></i>&nbsp;<?php echo JUIZ_SPS_PLUGIN_NAME; ?>&nbsp;<small>v.&nbsp;<?php echo JUIZ_SPS_VERSION; ?></small></h1>
			</div>

			<div class="jsps-main-body">
				<?php if ( isset( $_GET['message'] ) && $_GET['message'] = '1337' ) { ?>
				<div class="error settings-error">
					<p>
						<strong><?php echo sprintf( __( 'You must chose at least one %stype of content%s.', 'juiz-social-post-sharer' ), '<a href="#post">', '</a>' ); ?></strong><br>
						<em><?php _e( 'Is you don\'t want to use this plugin more longer, go to Plugins section and deactivate it.', 'juiz-social-post-sharer' ); ?></em></p>
				</div>
				<?php } ?>
				<form method="post" action="options.php">
					<?php
						settings_fields( JUIZ_SPS_SETTING_NAME );
						do_settings_sections( JUIZ_SPS_SLUG );
					?>
				</form>

				<p class="jsps_info">
					<?php echo sprintf( __( 'You can use %s[juiz_sps]%s or %s[juiz_social]%s shortcode with an optional attribute "buttons" listing the social networks you want.', 'juiz-social-post-sharer' ), '<code>','</code>', '<code>','</code>' ); ?>
					<br />
					<?php _e( 'Example with all the networks available:', 'juiz-social-post-sharer'); ?>
					<code>[juiz_sps buttons="facebook, twitter, google, pinterest, digg, weibo, linkedin, viadeo, stumbleupon, vk, tumblr, reddit, delicious, mail, bookmark, print"]</code>
				</p>
			</div><!-- .jsps-main-body -->
		</div><!-- .jsps-main-content -->

		<div class="juiz_bottom_links">
			<div class="jsps-links-header">
				<p><i class="dashicons dashicons-format-status" aria-hidden="true"></i>&nbsp;<?php esc_html_e( 'Hey!', 'juiz-social-post-sharer' ); ?></p>
			</div>
			<p class="juiz_bottom_links_p">
				<em><?php _e( 'Like it? Support this plugin! Thank you.', 'juiz-social-post-sharer' ); ?></em>
				<a class="juiz_btn_link juiz_paypal" target="_blank" href="https://www.paypal.com/cgi-bin/webscr?cmd=_donations&amp;business=P39NJPCWVXGDY&amp;lc=FR&amp;item_name=Juiz%20Social%20Post%20Sharer%20%2d%20WP%20Plugin&amp;item_number=%23wp%2djsps&amp;currency_code=EUR&amp;bn=PP%2dDonationsBF%3abtn_donate_SM%2egif%3aNonHosted"><i class="dashicons dashicons-money" aria-hidden="true"></i>&nbsp;<?php _e( 'Donate', 'juiz-social-post-sharer' ); ?></a>

				<a class="juiz_btn_link juiz_flattr" href="https://flattr.com/submit/auto?user_id=CreativeJuiz&amp;url=http://wordpress.org/plugins/juiz-social-post-sharer/&amp;title=Juiz%20Social%20Post%20Sharer%20-%20WordPress%20Plugin&amp;description=Awesome%20WordPress%20Plugin%20helping%20you%20to%20add%20buttons%20at%20the%20beginning%20or%20the%20end%20of%20your%20WordPress%20contents%20easily&amp;tags=WordPress,Social,Share,Buttons,Network,Twitter,Facebook,Linkedin&amp;category=software" lang="en" hreflang="en"><i class="dashicons dashicons-thumbs-up" aria-hidden="true"></i>&nbsp;<?php _e( 'Flattr this!', 'juiz-social-post-sharer' ); ?></a>
				
				<a class="juiz_btn_link juiz_twitter" target="_blank" href="https://twitter.com/intent/tweet?source=webclient&amp;hastags=WordPress,Plugin&amp;text=Juiz%20Social%20Post%20Sharer%20is%20an%20awesome%20WordPress%20plugin%20to%20share%20content!%20Try%20it!&amp;url=http://wordpress.org/extend/plugins/juiz-social-post-sharer/&amp;related=geoffrey_crofte&amp;via=geoffrey_crofte"><i class="dashicons dashicons-twitter" aria-hidden="true"></i>&nbsp;<?php _e( 'Tweet it', 'juiz-social-post-sharer' ); ?></a>

				<a class="juiz_btn_link juiz_rate" target="_blank" href="http://wordpress.org/support/view/plugin-reviews/juiz-social-post-sharer"><i class="dashicons dashicons-star-filled" aria-hidden="true"></i>&nbsp;<?php _e( 'Rate it', 'juiz-social-post-sharer' ); ?></a>

				<em><?php _e( 'Want to customize everything? Take a look at the documentation.', 'juiz-social-post-sharer' ); ?></em>

				<a class="juiz_btn_link juiz_doc" target="_blank" href="<?php echo JUIZ_SPS_PLUGIN_URL; ?>documentation.html"><i class="dashicons dashicons-welcome-learn-more" aria-hidden="true"></i>&nbsp;<?php _e( 'Doc', 'juiz-social-post-sharer' ); ?> (en)</a>
			</p>
		</div><!-- . juiz_bottom_links -->
	</div><!-- .wrap -->

	<?php
	} // Eo juiz_sps_settings_page()
} // Eo If function_exists()