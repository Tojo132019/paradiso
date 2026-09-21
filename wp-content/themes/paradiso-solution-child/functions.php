<?php
/**
 * Child Theme functions for Paradiso Solutions Theme
 *
 * @package Paradiso
 */

/**
 * 1. Theme Setup (Nav Menus & WooCommerce Support)
 */
function paradiso_child_theme_setup() {
	register_nav_menus(
		array(
			'primary' => __( 'Primary Menu', 'paradiso' ),
			'footer'  => __( 'Footer Menu', 'paradiso' ),
		)
	);

	add_theme_support( 'woocommerce' );
}
add_action( 'after_setup_theme', 'paradiso_child_theme_setup' );

/**
 * 2. Resource Hints (Preconnect External CDNs for Speed)
 */
function paradiso_resource_hints( $urls, $relation_type ) {
	if ( 'preconnect' === $relation_type ) {
		$urls[] = array( 'href' => 'https://fonts.googleapis.com' );
		$urls[] = array( 'href' => 'https://fonts.gstatic.com', 'crossorigin' => 'anonymous' );
		$urls[] = array( 'href' => 'https://cdnjs.cloudflare.com', 'crossorigin' => 'anonymous' );
	}
	return $urls;
}
add_filter( 'wp_resource_hints', 'paradiso_resource_hints', 10, 2 );

/**
 * 3. Enqueue Stylesheets
 */
function paradiso_enqueue_theme_styles() {
	$theme_version = wp_get_theme()->get( 'Version' );

	wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );
	wp_enqueue_style( 'paradiso-google-fonts', 'https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap', array(), null );
	wp_enqueue_style( 'font-awesome-6', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css', array(), '6.3.0' );

	wp_enqueue_style( 'bootstrap', get_stylesheet_directory_uri() . '/assets/css/bootstrap.css', array(), '4.0.0' );
	wp_enqueue_style( 'paradiso-style', get_stylesheet_directory_uri() . '/assets/css/style.css', array( 'parent-style', 'bootstrap' ), $theme_version );
	wp_enqueue_style( 'paradiso-responsive', get_stylesheet_directory_uri() . '/assets/css/responsive.css', array( 'paradiso-style' ), $theme_version );
}
add_action( 'wp_enqueue_scripts', 'paradiso_enqueue_theme_styles' );

/**
 * 4. Asynchronously Load FontAwesome CSS
 */
function paradiso_async_fontawesome( $html, $handle, $href, $media ) {
	if ( 'font-awesome-6' === $handle ) {
		return sprintf(
			'<link rel="stylesheet" id="%s-css" href="%s" media="print" onload="this.media=\'all\'" />' . "\n",
			esc_attr( $handle ),
			esc_url( $href )
		);
	}
	return $html;
}
add_filter( 'style_loader_tag', 'paradiso_async_fontawesome', 10, 4 );

/**
 * 5. Preload LCP Hero Banner Image in <head>
 */
function paradiso_preload_lcp_banner_image() {
	if ( is_front_page() && function_exists( 'get_field' ) ) {
		$home_slides = get_field( 'manage_home_page_slider' );

		if ( is_array( $home_slides ) && ! empty( $home_slides ) && ! empty( $home_slides[0]['home_page_banner_image'] ) ) {
			$banner_img = $home_slides[0]['home_page_banner_image'];
			$img_url    = '';

			if ( is_array( $banner_img ) ) {
				$img_url = ! empty( $banner_img['sizes']['large'] ) ? $banner_img['sizes']['large'] : ( $banner_img['url'] ?? '' );
			} elseif ( is_numeric( $banner_img ) ) {
				$img_url = wp_get_attachment_image_url( absint( $banner_img ), 'large' );
			} elseif ( is_string( $banner_img ) ) {
				$img_url = $banner_img;
			}

			if ( $img_url ) {
				echo '<link rel="preload" as="image" href="' . esc_url( $img_url ) . '" fetchpriority="high">' . "\n";
			}
		}
	}
}
add_action( 'wp_head', 'paradiso_preload_lcp_banner_image', 1 );

/**
 * 6. Enqueue All JavaScript Files Safely (All Deferred)
 */
function paradiso_enqueue_theme_scripts() {
	$theme_uri     = get_stylesheet_directory_uri();
	$theme_version = wp_get_theme()->get( 'Version' );

	$script_args = array(
		'in_footer' => true,
		'strategy'  => 'defer',
	);

	wp_enqueue_script( 'popper', $theme_uri . '/assets/js/popper.min.js', array( 'jquery' ), '1.0.0', $script_args );
	wp_enqueue_script( 'bootstrap', $theme_uri . '/assets/js/bootstrap.min.js', array( 'jquery', 'popper' ), '4.0.0', $script_args );
	wp_enqueue_script( 'mcustomscrollbar', $theme_uri . '/assets/js/jquery.mCustomScrollbar.concat.min.js', array( 'jquery' ), '3.1.5', $script_args );
	wp_enqueue_script( 'appear', $theme_uri . '/assets/js/appear.js', array( 'jquery' ), '1.0.0', $script_args );
	wp_enqueue_script( 'parallax', $theme_uri . '/assets/js/parallax.min.js', array( 'jquery' ), '1.0.0', $script_args );
	wp_enqueue_script( 'tilt-jquery', $theme_uri . '/assets/js/tilt.jquery.min.js', array( 'jquery' ), '1.0.0', $script_args );
	wp_enqueue_script( 'jquery-paroller', $theme_uri . '/assets/js/jquery.paroller.min.js', array( 'jquery' ), '1.0.0', $script_args );
	wp_enqueue_script( 'owl-carousel', $theme_uri . '/assets/js/owl.js', array( 'jquery' ), '2.3.4', $script_args );
	wp_enqueue_script( 'wow', $theme_uri . '/assets/js/wow.js', array( 'jquery' ), '1.3.0', $script_args );
	wp_enqueue_script( 'nav-tool', $theme_uri . '/assets/js/nav-tool.js', array( 'jquery' ), '1.0.0', $script_args );
	wp_enqueue_script( 'jquery-ui-custom', $theme_uri . '/assets/js/jquery-ui.js', array( 'jquery' ), '1.12.1', $script_args );

	// Main Theme Script
	wp_enqueue_script( 'paradiso-script', $theme_uri . '/assets/js/script.js', array( 'jquery' ), $theme_version, $script_args );
}
add_action( 'wp_enqueue_scripts', 'paradiso_enqueue_theme_scripts' );

/**
 * 7. Theme Includes
 */
require_once get_theme_file_path( 'inc/theme-acf.php' );
require_once get_theme_file_path( 'inc/theme-post-type.php' );
require_once get_theme_file_path( 'inc/theme-function.php' );

/**
 * 8. Admin Bar Display
 */
function paradiso_admin_bar() {
	if ( function_exists( 'is_user_logged_in' ) && is_user_logged_in() ) {
		add_filter( 'show_admin_bar', '__return_true', 1000 );
	}
}
add_action( 'init', 'paradiso_admin_bar' );

/**
 * 9. WooCommerce Single Product Title Action
 */
add_action( 'init', function() {
	remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_title', 5 );
} );

/**
 * 10. Dynamic WooCommerce Cart Count Fragment
 */
function paradiso_header_cart_count_fragment( $fragments ) {
	ob_start();
	?>
	<span class="cart-count">
		<?php echo ( function_exists( 'WC' ) && WC()->cart ) ? esc_html( WC()->cart->get_cart_contents_count() ) : 0; ?>
	</span>
	<?php
	$fragments['span.cart-count'] = ob_get_clean();
	return $fragments;
}
add_filter( 'woocommerce_add_to_cart_fragments', 'paradiso_header_cart_count_fragment' );