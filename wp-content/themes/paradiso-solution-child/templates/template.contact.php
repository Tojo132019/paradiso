<?php
/*
 * Template Name: Contact Us template
 * Description: Template file for the Contact Us Page Only
 */
 
get_header(); ?>
<!-- ============================= Banner Section ===================== -->
<?php get_template_part( 'template-parts/header-banner');  ?>
<!-- ============================= End: Banner Section ===================== -->
	<!-- Contact Info Section -->
	<section class="contact-info-section">
		<div class="auto-container">
			<!-- Sec Title -->
			<div class="row clearfix">
				<!-- Info Column -->
				<?php if (get_field('address', 'option')) : ?>
				<div class="info-column col-lg-4 col-md-6 col-sm-12">
					<div class="inner-column">
						<div class="content">
							<div class="icon-box"><span class="flaticon-pin"></span></div>
							<ul>
								<li><strong><?= 'Address'; ?></strong></li>
								<li><?= esc_html(get_field('address', 'option')); ?></li>
							</ul>
						</div>
					</div>
				</div>
				<?php endif; ?>
				<!-- Info Column -->
				<?php if (get_field('site_phone_number', 'option')) : ?>
				<div class="info-column col-lg-4 col-md-6 col-sm-12">
					<div class="inner-column">
						<div class="content">
							<div class="icon-box"><span class="flaticon-phone-call"></span></div>
							<ul>
								<li><strong><?= 'Phone'; ?></strong></li>
								<li><?= esc_html(get_field('site_phone_number', 'option')); ?></li>
							</ul>
						</div>
					</div>
				</div>
				<?php endif; ?>
				<!-- Info Column -->
				<?php if (get_field('site_email_id', 'option')) : ?>
				<div class="info-column col-lg-4 col-md-6 col-sm-12">
					<div class="inner-column">
						<div class="content">
							<div class="icon-box"><span class="flaticon-email-1"></span></div>
							<ul>
								<li><strong><?= 'E-Mail'; ?></strong></li>
								<li><?= esc_html(get_field('site_email_id', 'option')); ?></li>
							</ul>
						</div>
					</div>
				</div>
				<?php endif; ?>
			</div>
		</div>
	</section>
	<!-- End Contact Info Section -->
	<!-- Map Section -->
	<?php if (get_field('google_map_address', 'option')) : ?>
	<section class="contact-map-section">
		<div class="auto-container">
			<!-- Map Boxed -->
			<div class="map-boxed">
				<!--Map Outer-->
				<div class="map-outer">
					<?=get_field('google_map_address', 'option'); ?>
				</div>
			</div>
		</div>
	</section>
	<?php endif; ?>
	<!-- End Map Section -->
	<!-- Contact Map Section -->
	<section class="contact-map-section">
		<div class="auto-container">
			<!-- Sec Title -->
			<div class="sec-title">
				<div class="clearfix">
					<div class="pull-left">
						<h2><?= 'Send Your Message'; ?></h2>
					</div>
					
				</div>
			</div>
			<!-- Contact Form -->
			<div class="contact-form">	
			<?php echo do_shortcode('[contact-form-7 id="32145c2" title="Contact form 1"]'); ?>
			</div>
			<!-- End Contact Form -->
		</div>
	</section>
	<!-- End Contact Map Section -->
<?php get_footer(); ?>
