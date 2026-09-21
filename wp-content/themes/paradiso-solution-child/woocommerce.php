<?php
/**
 * WooCommerce wrapper for the Paradiso Solution child theme.
 *
 * The custom archive is used only for Shop/product taxonomy archives.
 * More specific WooCommerce templates remain available for product pages.
 *
 * @package Paradiso_Solution_Child
 */

defined( 'ABSPATH' ) || exit;

if ( is_shop() || is_product_taxonomy() ) {
    require get_stylesheet_directory() . '/woocommerce/archive-product.php';
    return;
}

// If WooCommerce reaches this wrapper for another page, use the parent theme's
// wrapper when it exists instead of incorrectly rendering the Shop archive.
$parent_woocommerce = get_template_directory() . '/woocommerce.php';

if ( file_exists( $parent_woocommerce ) ) {
    require $parent_woocommerce;
    return;
}

get_header();
?>
<div class="sidebar-page-container custom-woocommerce-shop">
    <div class="auto-container">
        <?php woocommerce_content(); ?>
    </div>
</div>
<?php
get_footer();
