<?php
	global $templates;
	/*:::::::::::::::::::: ACF OPTION ::::::::::::::::::::::::::::::*/
	if( function_exists('acf_add_options_page') ) {		
		acf_add_options_page(array(
			'page_title' 	=> 'Theme Settings',
			'menu_title'	=> 'Theme Settings',
			'menu_slug' 	=> 'global-settings',
			'capability'	=> 'edit_posts',
			'redirect'		=> true,
			'position' => 9
		));
		acf_add_options_sub_page(array(
			'page_title' 	=> 'Common Settings',
			'menu_title'	=> 'Common Settings',
			'menu_slug' 	=> 'common-setting',
			'parent_slug'	=> 'global-settings',
		));
		acf_add_options_sub_page(array(
			'page_title' 	=> 'Social Media Settings',
			'menu_title'	=> 'Social Media Settings',
			'menu_slug' 	=> 'social-media',
			'parent_slug'	=> 'global-settings',
		));
	}	

	/**
 * Register ACF Field Group for Solution Post Type
 */
if ( function_exists( 'acf_add_local_field_group' ) ) :

acf_add_local_field_group( array(
    'key' => 'group_solution_fields',
    'title' => 'Solution Details',
    'fields' => array(
        array(
            'key' => 'field_solution_features',
            'label' => 'Features',
            'name' => 'solution_features',
            'type' => 'repeater',
            'button_label' => 'Add Feature',
            'sub_fields' => array(
                array(
                    'key' => 'field_feature_item',
                    'label' => 'Feature Item',
                    'name' => 'feature_item',
                    'type' => 'text',
                    'placeholder' => 'e.g. Course management',
                ),
            ),
        ),
       
    ),
    'location' => array(
        array(
            array(
                'param' => 'post_type',
                'operator' => '==',
                'value' => 'solution',
            ),
        ),
    ),
) );

endif;