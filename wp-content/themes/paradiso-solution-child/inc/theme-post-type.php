<?php
global $templates;
/*::::::::::::::::::::::: CUSTOM POST TYPE :::::::::::::::::::::::*/

/* ====================== Team ====================== */
function cptui_register_my_cpts_team() {

	$labels = array(
		"name"          => __( "Team", "paradiso" ),
		"singular_name" => __( "Team Member", "paradiso" ),
	);

	$args = array(
		"label"               => __( "Team", "paradiso" ),
		"labels"              => $labels,
		"description"         => "",
		"public"              => true,
		"publicly_queryable"  => true,
		"show_ui"             => true,
		"show_in_rest"        => true,
		"has_archive"         => false,
		"show_in_menu"        => true,
		"show_in_nav_menus"   => true,
		"exclude_from_search" => false,
		"capability_type"     => "post",
		"map_meta_cap"        => true,
		"hierarchical"        => false,
		"rewrite"             => array(
			"slug"       => "teams",
			"with_front" => false
		),
		"query_var"           => true,
		"supports"            => array(
			"title",
			"editor",
			"thumbnail"
		),
		"menu_icon"           => "dashicons-groups",
		"menu_position"       => 6,
	);

	register_post_type( "team", $args );
}
add_action( "init", "cptui_register_my_cpts_team" );
/* ====================== Team ====================== */


/* ====================== Solution ====================== */
function cptui_register_my_cpts_solution() {

	$labels = array(
		"name"          => __( "Solutions", "paradiso" ),
		"singular_name" => __( "Solution", "paradiso" ),
	);

	$args = array(
		"label"               => __( "Solutions", "paradiso" ),
		"labels"              => $labels,
		"description"         => "",
		"public"              => true,
		"publicly_queryable"  => true,
		"show_ui"             => true,
		"show_in_rest"        => true,
		"has_archive"         => false,
		"show_in_menu"        => true,
		"show_in_nav_menus"   => true,
		"exclude_from_search" => false,
		"capability_type"     => "post",
		"map_meta_cap"        => true,
		"hierarchical"        => false,
		"rewrite"             => array(
			"slug"       => "solutions",
			"with_front" => false
		),
		"query_var"           => true,
		"supports"            => array(
			"title",
			"editor",
			"thumbnail"
		),
		"menu_icon"           => "dashicons-lightbulb",
		"menu_position"       => 7,
	);

	register_post_type( "solution", $args );
}
add_action( "init", "cptui_register_my_cpts_solution" );
/* ====================== Solution ====================== */

/* ====================== Service Custom Post Type ====================== */
function cptui_register_my_cpts_service() {

	$labels = array(
		"name"          => __( "Services", "paradiso" ),
		"singular_name" => __( "Service", "paradiso" ),
	);

	$args = array(
		"label"               => __( "Services", "paradiso" ),
		"labels"              => $labels,
		"description"         => "Services provided by Paradiso Solutions.",
		"public"              => true,
		"publicly_queryable"  => true,
		"show_ui"             => true,
		"show_in_rest"        => true,
		"has_archive"         => false,
		"show_in_menu"        => true,
		"show_in_nav_menus"   => true,
		"exclude_from_search" => false,
		"capability_type"     => "post",
		"map_meta_cap"        => true,
		"hierarchical"        => false,

		"rewrite" => array(
			"slug"       => "services",
			"with_front" => false
		),

		"query_var" => true,

		"supports" => array(
			"title",
			"editor",
			"thumbnail"
		),

		"taxonomies" => array(
			"service_category"
		),

		"menu_icon"     => "dashicons-admin-tools",
		"menu_position" => 8,
	);

	register_post_type( "service", $args );
}
add_action( "init", "cptui_register_my_cpts_service" );
/* ====================== Service Custom Post Type ====================== */


/* ====================== Service Category Taxonomy ====================== */
function cptui_register_my_taxes_service_cat() {

	$labels = array(
		"name"                       => __( "Service Categories", "paradiso" ),
		"singular_name"              => __( "Service Category", "paradiso" ),
		"menu_name"                  => __( "Categories", "paradiso" ),
		"all_items"                  => __( "All Categories", "paradiso" ),
		"edit_item"                  => __( "Edit Category", "paradiso" ),
		"view_item"                  => __( "View Category", "paradiso" ),
		"update_item"                => __( "Update Category Name", "paradiso" ),
		"add_new_item"               => __( "Add New Category", "paradiso" ),
		"new_item_name"              => __( "New Category Name", "paradiso" ),
		"parent_item"                => __( "Parent Category", "paradiso" ),
		"parent_item_colon"          => __( "Parent Category:", "paradiso" ),
		"search_items"               => __( "Search Categories", "paradiso" ),
		"popular_items"              => __( "Popular Categories", "paradiso" ),
		"separate_items_with_commas" => __( "Separate categories with commas", "paradiso" ),
		"add_or_remove_items"        => __( "Add or remove categories", "paradiso" ),
		"choose_from_most_used"      => __( "Choose from the most used categories", "paradiso" ),
		"not_found"                  => __( "No categories found", "paradiso" ),
		"no_terms"                   => __( "No categories", "paradiso" ),
		"items_list_navigation"      => __( "Categories list navigation", "paradiso" ),
		"items_list"                 => __( "Categories list", "paradiso" ),
		"back_to_items"              => __( "Back to Categories", "paradiso" ),
	);

	$args = array(
		"label"                 => __( "Service Categories", "paradiso" ),
		"labels"                => $labels,
		"public"                => true,
		"publicly_queryable"    => true,
		"hierarchical"          => true, // Works like standard post categories (checkboxes)
		"show_ui"               => true,
		"show_in_menu"          => true,
		"show_in_nav_menus"     => true,
		"query_var"             => true,
		"rewrite"               => array(
			'slug'       => 'service-category',
			'with_front' => false
		),
		"show_admin_column"     => true, // Displays Category column in Service Post Table
		"show_in_rest"          => true, // Enables Gutenberg block editor support
		"rest_base"             => "service_category",
		"show_in_quick_edit"    => true,
	);

	register_taxonomy( "service_category", array( "service" ), $args );
}
add_action( "init", "cptui_register_my_taxes_service_cat" );
/* ====================== Service Category Taxonomy ====================== */
/* ====================== Case Studies ====================== */
function cptui_register_my_cpts_case_study() {

	$labels = array(
		"name"          => __( "Case Studies", "paradiso" ),
		"singular_name" => __( "Case Study", "paradiso" ),
	);

	$args = array(
		"label"               => __( "Case Studies", "paradiso" ),
		"labels"              => $labels,
		"description"         => "Case studies and projects by Paradiso Solutions.",
		"public"              => true,
		"publicly_queryable"  => true,
		"show_ui"             => true,
		"show_in_rest"        => true,
		"has_archive"         => false,
		"show_in_menu"        => true,
		"show_in_nav_menus"   => true,
		"exclude_from_search" => false,
		"capability_type"     => "post",
		"map_meta_cap"        => true,
		"hierarchical"        => false,

		"rewrite" => array(
			"slug"       => "case-studies",
			"with_front" => false
		),

		"query_var" => true,

		"supports" => array(
			"title",
			"editor",
			"thumbnail"
		),

		"menu_icon"     => "dashicons-portfolio",
		"menu_position" => 9,
	);

	register_post_type( "case_study", $args );
}
add_action( "init", "cptui_register_my_cpts_case_study" );
/* ====================== Case Studies ====================== */


