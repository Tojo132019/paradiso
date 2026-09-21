<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package WordPress
 * @subpackage Twenty_Twenty_One
 * @since Twenty Twenty-One 1.0
 */

get_header();
?>
<!-- ============================= Banner Section ===================== -->
<?php get_template_part( 'template-parts/header-banner');  ?>
<!-- ============================= End: Banner Section ===================== -->
<section class="fof">
	<div class="container">
		<div class="row">
			<div class="col-lg-12">				
				<div class="text-center">
					<h2><?=__('404 Error', 'twentytwenty') ?></h2>
					<p><?=__('The Page you are looking for cannot be found', 'twentytwenty') ?></p>				
					<div class="">					
						<a href="<?php echo get_site_url(); ?>" class="common-button black-line">HOME</a>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
<?php
get_footer();
