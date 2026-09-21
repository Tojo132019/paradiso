<?php
	global $templates;
	$post_id = get_the_ID();
	$bg_img = get_page_banner($post_id);
?>
<!--Page Title-->
    <section class="page-title">
		<div class="pattern-layer-one" style="background-image: url('<?=$bg_img?>')"></div>
    	<div class="auto-container">
			<?php if (function_exists('header_banner_heading')): header_banner_heading(); endif;?>
			<?php get_template_part( 'template-parts/breadcrumb');?>
        </div>
    </section>
  <!--End Page Title-->




