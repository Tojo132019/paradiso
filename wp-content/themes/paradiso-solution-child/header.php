<?php
/**
 * Main Header Template
 *
 * @package Paradiso
 */

$paradiso_theme_uri          = get_stylesheet_directory_uri();
$paradiso_home_url           = home_url( '/' );
$paradiso_site_name          = get_bloginfo( 'name' );
$paradiso_email              = get_field( 'site_email_id', 'option' );
$paradiso_phone              = get_field( 'site_phone_number', 'option' );
$paradiso_logo               = get_field( 'site_logo', 'option' );
$paradiso_phone_link         = $paradiso_phone ? preg_replace( '/[^0-9+]/', '', $paradiso_phone ) : '';
$paradiso_woocommerce_active = class_exists( 'WooCommerce' );

$paradiso_current_user = null;
$paradiso_cart_count   = 0;
$paradiso_cart_url     = '';
$paradiso_account_url  = '';

if ( $paradiso_woocommerce_active ) {
	if ( is_user_logged_in() ) {
		$paradiso_current_user = wp_get_current_user();
	}
	if ( function_exists( 'WC' ) && WC()->cart ) {
		$paradiso_cart_count = WC()->cart->get_cart_contents_count();
	}
	if ( function_exists( 'wc_get_cart_url' ) ) {
		$paradiso_cart_url = wc_get_cart_url();
	}
	if ( function_exists( 'wc_get_page_permalink' ) ) {
		$paradiso_account_url = wc_get_page_permalink( 'myaccount' );
	}
}

// Optimization: Cache menu output into variable to avoid running database queries & walker twice.
$paradiso_primary_menu = '';
if ( has_nav_menu( 'primary' ) ) {
	$paradiso_primary_menu = wp_nav_menu(
		array(
			'theme_location' => 'primary',
			'container'      => false,
			'menu_class'     => 'navigation clearfix',
			'fallback_cb'    => false,
			'depth'          => 3,
			'echo'           => false,
		)
	);
}
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<div id="page" class="page-wrapper">
	<!-- Main Header -->
	<header class="main-header header-style-one">
		<!-- Header Top -->
		<div class="header-top">
			<div class="auto-container">
				<div class="clearfix">
					<!-- Top Left -->
					<div class="top-left">
						<ul class="info-list">
							<?php if ( $paradiso_email ) : ?>
								<li>
									<a href="mailto:<?php echo esc_attr( $paradiso_email ); ?>">
										<span class="icon flaticon-email"></span>
										<?php echo esc_html( $paradiso_email ); ?>
									</a>
								</li>
							<?php endif; ?>
							<?php if ( $paradiso_phone ) : ?>
								<li>
									<a href="tel:<?php echo esc_attr( $paradiso_phone_link ); ?>">
										<span class="icon flaticon-telephone"></span>
										<?php echo esc_html( $paradiso_phone ); ?>
									</a>
								</li>
							<?php endif; ?>
						</ul>
					</div>
					<!-- Top Right -->
					<div class="top-right pull-right">
						<div class="header-user-box">
							<?php if ( $paradiso_woocommerce_active ) : ?>
								<!-- Shopping Cart -->
								<?php if ( $paradiso_cart_url ) : ?>
									<div class="cart-box">
										<a href="<?php echo esc_url( $paradiso_cart_url ); ?>" class="cart-btn" title="<?php esc_attr_e( 'View Shopping Cart', 'paradiso' ); ?>">
											<span class="icon flaticon-shopping-cart"></span>
											<span class="cart-count"><?php echo esc_html( $paradiso_cart_count ); ?></span>
										</a>
									</div>
								<?php endif; ?>
								<!-- My Account -->
								<?php if ( $paradiso_account_url ) : ?>
									<div class="account-box">
										<a href="<?php echo esc_url( $paradiso_account_url ); ?>" class="account-btn" title="<?php esc_attr_e( 'My Account', 'paradiso' ); ?>">
											<span class="icon flaticon-user"></span>
											<span class="account-text">
												<?php if ( $paradiso_current_user ) : ?>
													<?php echo esc_html( $paradiso_current_user->display_name ); ?>
												<?php else : ?>
													<?php esc_html_e( 'Login / Register', 'paradiso' ); ?>
												<?php endif; ?>
											</span>
										</a>
									</div>
								<?php endif; ?>
							<?php endif; ?>
							<!-- Social Media -->
							<?php if ( have_rows( 'manage_social_media', 'option' ) ) : ?>
								<ul class="social-box">
									<?php while ( have_rows( 'manage_social_media', 'option' ) ) : the_row(); ?>
										<?php
										$paradiso_social_link = get_sub_field( 'social_media_link' );
										$paradiso_social_icon = get_sub_field( 'social_media_icon' );
										?>
										<?php if ( $paradiso_social_link ) : ?>
											<li>
												<a href="<?php echo esc_url( $paradiso_social_link ); ?>" target="_blank" rel="noopener noreferrer" aria-label="<?php esc_attr_e( 'Social Media', 'paradiso' ); ?>">
													<?php echo wp_kses_post( $paradiso_social_icon ); ?>
												</a>
											</li>
										<?php endif; ?>
									<?php endwhile; ?>
								</ul>
							<?php endif; ?>
						</div>
					</div>
				</div>
			</div>
		</div>

		<!-- Header Upper -->
		<div class="header-upper">
			<div class="auto-container clearfix">
				<?php if ( $paradiso_logo ) : ?>
					<div class="pull-left logo-box">
						<div class="logo">
							<a href="<?php echo esc_url( $paradiso_home_url ); ?>">
								<img src="<?php echo esc_url( $paradiso_logo ); ?>" alt="<?php echo esc_attr( $paradiso_site_name ); ?>" width="200" height="60" loading="eager" fetchpriority="high">
							</a>
						</div>
					</div>
				<?php endif; ?>
				<div class="nav-outer clearfix">
					<nav class="main-menu navbar-expand-md">
						<div class="navbar-collapse collapse clearfix" id="navbarSupportedContent">
							<?php echo $paradiso_primary_menu; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						</div>
					</nav>
				</div>
			</div>
		</div>
		<!-- End Header Upper -->

		<!-- Sticky Header -->
		<div class="sticky-header">
			<div class="auto-container clearfix">
				<?php if ( $paradiso_logo ) : ?>
					<div class="logo pull-left">
						<a href="<?php echo esc_url( $paradiso_home_url ); ?>">
							<img src="<?php echo esc_url( $paradiso_logo ); ?>" alt="<?php echo esc_attr( $paradiso_site_name ); ?>" width="200" height="60" loading="lazy">
						</a>
					</div>
				<?php endif; ?>
				<div>
					<nav class="main-menu pull-right">
						<div class="navbar-collapse collapse clearfix">
							<?php echo $paradiso_primary_menu; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						</div>
					</nav>
				</div>
			</div>
		</div>
		<!-- End Sticky Header -->
	</header>
	<!-- End Main Header -->
	<main>