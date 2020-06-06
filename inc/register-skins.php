<?php if ( ! defined( 'ABSPATH' ) ) {
	die( 'Cheatin&#8217; uh?' );
}

/**
 * Register the Core Skins being used in the admin/front
 * 
 * @param  (array) $core_skins  An array of the Core Skins.
 * @return (array)              The filtered array of Core Skins.
 *
 * @since  2.0.0
 * @author Geoffrey Crofte
 */
function jsps_register_core_skins( $core_skins ) {

	$core_skins['1'] = array(
		'name'        => __( 'Juizy Light Tone', 'juiz-social-post-sharer' ),
		'demo_url_2x' => JUIZ_SPS_PLUGIN_URL . 'skins/1/demo@2x.png',
	);

	$core_skins['2'] = array(
		'name'        => __( 'Juizy Light Tone Reversed', 'juiz-social-post-sharer' ),
		'demo_url_2x' => JUIZ_SPS_PLUGIN_URL . 'skins/2/demo@2x.png',
	);

	$core_skins['3'] = array(
		'name'        => __( 'Blue Metro Style', 'juiz-social-post-sharer' ),
		'demo_url_2x' => JUIZ_SPS_PLUGIN_URL . 'skins/3/demo@2x.png',
	);

	$core_skins['4'] = array(
		'name'        => __( 'Gray Metro Style', 'juiz-social-post-sharer' ),
		'demo_url_2x' => JUIZ_SPS_PLUGIN_URL . 'skins/4/demo@2x.png',
	);

	$core_skins['5'] = array(
		'name'        => __( 'Modern Style', 'juiz-social-post-sharer' ),
		'author'      => 'Tony Trancard',
		'author_url'  => 'https://tonytrancard.fr/',
		'demo_url_2x' => JUIZ_SPS_PLUGIN_URL . 'skins/5/demo@2x.png',
	);

	$core_skins['6'] = array(
		'name'        => __( 'Black', 'juiz-social-post-sharer' ),
		'author'      => 'Fandia',
		'author_url'  => 'http://fandia.w.pw/',
		'demo_url_2x' => JUIZ_SPS_PLUGIN_URL . 'skins/6/demo@2x.png',
	);

	$core_skins['7'] = array(
		'name'        => __( 'Brands Colors', 'juiz-social-post-sharer' ),
		'demo_url_2x' => JUIZ_SPS_PLUGIN_URL . 'skins/7/demo@2x.png',
	);

	$core_skins['8'] = array(
		'name'        => __( 'Material Brand Colors', 'juiz-social-post-sharer' ),
		'demo_url_2x' => JUIZ_SPS_PLUGIN_URL . 'skins/8/demo@2x.png',
	);

	return apply_filters( 'jsps_register_core_skins', $core_skins );
}
add_filter( 'jsps_register_core_skin', 'jsps_register_core_skins' );

/**
 * Register the current theme buttons' skin(s) if found.
 * 
 * @param  (array) $custom_skins  An array of the Custom button Skins.
 * @return (array)                The filtered array of Custom Skins.
 *
 * @see    register-skin.php jsps_get_custom_skins() function desc for array composition.
 *
 * @since  2.0.0
 * @author Geoffrey Crofte
 */

function jsps_register_current_template_skins( $custom_skins ) {

	$skins = array();
	$css = juiz_sps_get_skin_css_name();
	$img = juiz_sps_get_skin_img_name();
	$dir = new DirectoryIterator( get_template_directory() );

	foreach ($dir as $fileinfo) {
		if ($fileinfo->isDir() && $fileinfo->getFilename() === 'juiz-sps' ) {

			// If we find the styles.css file here, don't dive deeper.
			if ( file_exists( get_template_directory() . '/juiz-sps/styles.css') ) {
				$skins[0] = array(
					'css' => get_template_directory_uri() . '/juiz-sps/' . $css,
					'img' => get_template_directory_uri() . '/juiz-sps/' . $img,
				);
				break;
			}

			// Else look for styles.css file(s) in subfolders.
			$dir = new DirectoryIterator( get_template_directory() . '/juiz-sps' );

			foreach ($dir as $fileinfo) {
				// If it's a folder and not a "." or ".." folder
				if ( $fileinfo->isDir() && !$fileinfo->isDot() ) {
					// try to get a styles.css file
					$baseurl = '/juiz-sps/' . $fileinfo->getFilename() . '/';
					$cssfilename = $baseurl . $css;
					$imgfilename = $baseurl . $img;

					if ( file_exists( get_template_directory() . $cssfilename ) ) {
						$skins[ $fileinfo->getFilename() ] = array(
							'css' => get_template_directory_uri() . $cssfilename,
							'img' => get_template_directory_uri() . $imgfilename
						);
					}
				}
			}
		}
	}

	// Let's use some info about the current theme to describe the Buttons' skin.
	// Should I use https://developer.wordpress.org/reference/functions/get_file_data/ ?
	// get_file_data() to get style.css info
	$themeinf  = wp_get_theme();
	$auth      = apply_filters( 'juiz_sps_custom_skin_author', $themeinf->get('Author'), $skins );
	$authurl   = apply_filters( 'juiz_sps_custom_skin_author_url', $themeinf->get('AuthorURI'), $skins );
	$themename = $themeinf->get('Name');
	$compname  = esc_html__( 'Button Skin', 'juiz-social-post-sharer' );

	foreach ($skins as $slug => $files) {
		$custom_skins[ $slug ] = array(
			'name'       => esc_html( apply_filters( 'juiz_sps_custom_skin_name', $themename . ( $slug !== 0 ? ' - ' . $slug : ' - '. $compname ), $skins ) ),
			'author'     => esc_html( $auth ),
			'author_url' => $authurl,
			'css_url'    => $files['css'],
			'demo_url'   => $files['img'],
		);
	}

	return apply_filters( 'jsps_register_current_template_skins', $custom_skins );

}
add_filter( 'jsps_register_custom_skin', 'jsps_register_current_template_skins', 10, 1 );
