<?php
/**
 * A unique identifier is defined to store the options in the database and reference them from the theme.
 * By default it uses the theme name, in lowercase and without spaces, but this can be changed if needed.
 * If the identifier changes, it'll appear as if the options have been reset.
 *
 */

function optionsframework_option_name() {

        // This gets the theme name from the stylesheet
        $themename = wp_get_theme();
        $themename = preg_replace("/\W/", "_", strtolower($themename) );

        $optionsframework_settings = get_option( 'optionsframework' );
        $optionsframework_settings['id'] = $themename;
        update_option( 'optionsframework', $optionsframework_settings );
}

/**
 * Defines an array of options that will be used to generate the settings page and be saved in the database.
 * When creating the 'id' fields, make sure to use all lowercase and no spaces.
 *
 */

function optionsframework_options() {

		// Layout options
		$site_layout = array('pull-left' => __('Right Sidebar', 'gsnbasic'),'pull-right' => __('Left Sidebar', 'gsnbasic'));

       // Test data
        $test_array = array(
                'one' => __('One', 'options_framework_theme'),
                'two' => __('Two', 'options_framework_theme'),
                'three' => __('Three', 'options_framework_theme'),
                'four' => __('Four', 'options_framework_theme'),
                'five' => __('Five', 'options_framework_theme')
        );

        // Multicheck Array
        $multicheck_array = array(
                'one' => __('French Toast', 'options_framework_theme'),
                'two' => __('Pancake', 'options_framework_theme'),
                'three' => __('Omelette', 'options_framework_theme'),
                'four' => __('Crepe', 'options_framework_theme'),
                'five' => __('Waffle', 'options_framework_theme')
        );

        // Multicheck Defaults
        $multicheck_defaults = array(
                'one' => '1',
                'five' => '1'
        );

        // Typography Defaults
        $typography_defaults = array(
                'size' => '14px',
                'face' => 'Open Sans',
                'style' => 'normal',
                'color' => '#6B6B6B' );

        // Typography Options
        $typography_options = array(
                'sizes' => array( '6','10','12','14','15','16','18','20','24','28','32','36','42','48' ),
                'faces' => array(
													'arial'     => 'Arial',
													'verdana'   => 'Verdana, Geneva',
													'trebuchet' => 'Trebuchet',
													'georgia'   => 'Georgia',
													'times'     => 'Times New Roman',
													'tahoma'    => 'Tahoma, Geneva',
													'Open Sans' 	=> 'Open Sans',
													'palatino'  => 'Palatino',
													'helvetica' => 'Helvetica',
													'Helvetica Neue' => 'Helvetica Neue'
				),
                'styles' => array( 'normal' => 'Normal','bold' => 'Bold' ),
                'color' => true
        );

        // $radio = array('0' => __('No', 'gsnbasic'),'1' => __('Yes', 'gsnbasic'));

     // Pull all the categories into an array
        $options_categories = array();
        $options_categories_obj = get_categories();
        foreach ($options_categories_obj as $category) {
                $options_categories[$category->cat_ID] = $category->cat_name;
        }

        // Pull all tags into an array
        $options_tags = array();
        $options_tags_obj = get_tags();
        foreach ( $options_tags_obj as $tag ) {
                $options_tags[$tag->term_id] = $tag->name;
        }


        // Pull all the pages into an array
        $options_pages = array();
        $options_pages_obj = get_pages('sort_column=post_parent,menu_order');
        $options_pages[''] = 'Select a page:';
        foreach ($options_pages_obj as $page) {
                $options_pages[$page->ID] = $page->post_title;
        }

        // If using image radio buttons, define a directory path
        $imagepath =  get_template_directory_uri() . '/images/';


		// fixed or scroll position
		$fixed_scroll = array('scroll' => 'Scroll', 'fixed' => 'Fixed');

		$options = array();

		$options[] = array( 'name' => __('Main', 'gsnbasic'),
							'type' => 'heading');

		$options[] = array( 'name' => __('Do You want to display image slider on the Home Page?','gsnbasic'),
							'desc' => __('Check if you want to enable slider', 'gsnbasic'),
							'id' => 'gsnbasic_slider_checkbox',
							'std' => 0,
							'type' => 'checkbox');

		$options[] = array( 'name' => __('Slider Category', 'gsnbasic'),
							'desc' => __('Select a category for the featured post slider', 'gsnbasic'),
							'id' => 'gsnbasic_slide_categories',
							'type' => 'select',
							'class' => 'hidden',
							'options' => $options_categories);

		$options[] = array( 'name' => __('Number of slide items', 'gsnbasic'),
							'desc' => __('Enter the number of slide items', 'gsnbasic'),
							'id' => 'gsnbasic_slide_number',
							'std' => '3',
							'class' => 'hidden',
							'type' => 'text');

		$options[] = array( 'name' => __('Website Layout Options', 'gsnbasic'),
							'desc' => __('Choose between Left and Right sidebar options to be used as default', 'gsnbasic'),
							'id' => 'site_layout',
							'std' => 'pull-left',
							'type' => 'select',
							'class' => 'mini',
							'options' => $site_layout);

		$options[] = array( 'name' => __('Element color', 'gsnbasic'),
							'desc' => __('Default used if no color is selected', 'gsnbasic'),
							'id' => 'element_color',
							'std' => '',
							'type' => 'color');

		$options[] = array( 'name' => __('Element color on hover', 'gsnbasic'),
							'desc' => __('Default used if no color is selected', 'gsnbasic'),
							'id' => 'element_color_hover',
							'std' => '',
							'type' => 'color');

		$options[] = array( 'name' => __('Custom Favicon', 'gsnbasic'),
							'desc' => __('Upload a 32px x 32px PNG/GIF image that will represent your websites favicon', 'gsnbasic'),
							'id' => 'custom_favicon',
							'std' => '',
							'type' => 'upload');

		$options[] = array( 'name' => __('Action Button', 'gsnbasic'),
							'type' => 'heading');

		$options[] = array( 'name' => __('Call For Action Text', 'gsnbasic'),
							'desc' => __('Enter the text for call for action section', 'gsnbasic'),
							'id' => 'w2f_cfa_text',
							'std' => '',
							'type' => 'textarea');

		$options[] = array( 'name' => __('Call For Action Button Title', 'gsnbasic'),
							'desc' => __('Enter the title for Call For Action button', 'gsnbasic'),
							'id' => 'w2f_cfa_button',
							'std' => '',
							'type' => 'text');

		$options[] = array( 'name' => __('CFA button link', 'gsnbasic'),
							'desc' => __('Enter the link for Call For Action button', 'gsnbasic'),
							'id' => 'w2f_cfa_link',
							'std' => '',
							'type' => 'text');

		$options[] = array( 'name' => __('Call For Action Text Color', 'gsnbasic'),
							'desc' => __('Default used if no color is selected', 'gsnbasic'),
							'id' => 'cfa_color',
							'std' => '',
							'type' => 'color');

		$options[] = array( 'name' => __('Call For Action Background Color', 'gsnbasic'),
							'desc' => __('Default used if no color is selected', 'gsnbasic'),
							'id' => 'cfa_bg_color',
							'std' => '',
							'type' => 'color');

		$options[] = array( 'name' => __('Call For Action Button Border Color', 'gsnbasic'),
							'desc' => __('Default used if no color is selected', 'gsnbasic'),
							'id' => 'cfa_btn_color',
							'std' => '',
							'type' => 'color');
		$options[] = array( 'name' => __('Call For Action Button Text Color', 'gsnbasic'),
							'desc' => __('Default used if no color is selected', 'gsnbasic'),
							'id' => 'cfa_btn_txt_color',
							'std' => '',
							'type' => 'color');

		$options[] = array( 'name' => __('Typography', 'gsnbasic'),
							'type' => 'heading');

		$options[] = array( 'name' => __('Main Body Text', 'gsnbasic'),
							'desc' => __('Used in P tags', 'gsnbasic'),
							'id' => 'main_body_typography',
							'std' => $typography_defaults,
							'type' => 'typography',
							'options' => $typography_options );

		$options[] = array( 'name' => __('Heading Color', 'gsnbasic'),
							'desc' => __('Color for all headings (h1-h6)', 'gsnbasic'),
							'id' => 'heading_color',
							'std' => '',
							'type' => 'color');

		$options[] = array( 'name' => __('Link Color', 'gsnbasic'),
							'desc' => __('Default used if no color is selected', 'gsnbasic'),
							'id' => 'link_color',
							'std' => '',
							'type' => 'color');

		$options[] = array( 'name' => __('Link:hover Color', 'gsnbasic'),
							'desc' => __('Default used if no color is selected', 'gsnbasic'),
							'id' => 'link_hover_color',
							'std' => '',
							'type' => 'color');

		$options[] = array( 'name' => __('Header', 'gsnbasic'),
							'type' => 'heading');

		$options[] = array( 'name' => __('Top nav background color', 'gsnbasic'),
							'desc' => __('Default used if no color is selected', 'gsnbasic'),
							'id' => 'nav_bg_color',
							'std' => '',
							'type' => 'color');

		$options[] = array( 'name' => __('Top nav item color', 'gsnbasic'),
							'desc' => __('Link color', 'gsnbasic'),
							'id' => 'nav_link_color',
							'std' => '',
							'type' => 'color');

		$options[] = array( 'name' => __('Top nav item hover color', 'gsnbasic'),
							'desc' => __('Link:hover color', 'gsnbasic'),
							'id' => 'nav_item_hover_color',
							'std' => '',
							'type' => 'color');

		$options[] = array( 'name' => __('Top nav dropdown background color', 'gsnbasic'),
							'desc' => __('Background of dropdown item hover color', 'gsnbasic'),
							'id' => 'nav_dropdown_bg',
							'std' => '',
							'type' => 'color');

		$options[] = array( 'name' => __('Top nav dropdown item color', 'gsnbasic'),
							'desc' => __('Dropdown item color', 'gsnbasic'),
							'id' => 'nav_dropdown_item',
							'std' => '',
							'type' => 'color');

		$options[] = array( 'name' => __('Top nav dropdown item hover color', 'gsnbasic'),
							'desc' => __('Dropdown item hover color', 'gsnbasic'),
							'id' => 'nav_dropdown_item_hover',
							'std' => '',
							'type' => 'color');

		$options[] = array( 'name' => __('Top nav dropdown item background hover color', 'gsnbasic'),
							'desc' => __('Background of dropdown item hover color', 'gsnbasic'),
							'id' => 'nav_dropdown_bg_hover',
							'std' => '',
							'type' => 'color');

		$options[] = array( 'name' => __('Footer', 'gsnbasic'),
							'type' => 'heading');

		$options[] = array( 'name' => __('Footer widget area background color', 'gsnbasic'),
							'id' => 'footer_widget_bg_color',
							'std' => '',
							'type' => 'color');

		$options[] = array( 'name' => __('Footer background color', 'gsnbasic'),
							'id' => 'footer_bg_color',
							'std' => '',
							'type' => 'color');

		$options[] = array( 'name' => __('Footer text color', 'gsnbasic'),
							'id' => 'footer_text_color',
							'std' => '',
							'type' => 'color');

		$options[] = array( 'name' => __('Footer link color', 'gsnbasic'),
							'id' => 'footer_link_color',
							'std' => '',
							'type' => 'color');

		$options[] = array(	'name' => __('Footer information', 'gsnbasic'),
        			'desc' => __('Copyright text in footer', 'gsnbasic'),
        			'id' => 'custom_footer_text',
        			'std' => '<a href="' . esc_url( home_url( '/' ) ) . '" title="' . esc_attr( get_bloginfo( 'name', 'display' ) ) . '" >' . get_bloginfo( 'name', 'display' ) . '</a>  All rights reserved.',
        			'type' => 'textarea');

		$options[] = array( 'name' => __('Social', 'gsnbasic'),
							'type' => 'heading');

		$options[] = array( 'name' => __('Social icon color', 'gsnbasic'),
							'desc' => __('Default used if no color is selected', 'gsnbasic'),
							'id' => 'social_color',
							'std' => '',
							'type' => 'color');

		$options[] = array( 'name' => __('Footer social icon color', 'gsnbasic'),
							'desc' => __('Default used if no color is selected', 'gsnbasic'),
							'id' => 'social_footer_color',
							'std' => '',
							'type' => 'color');

		$options[] = array(	'name' => __('Add full URL for your social network profiles', 'gsnbasic'),
        			'desc' => __('Facebook', 'gsnbasic'),
        			'id' => 'social_facebook',
        			'std' => '',
        			'class' => 'mini',
        			'type' => 'text');

		$options[] = array(	'id' => 'social_twitter',
							'desc' => __('Twitter', 'gsnbasic'),
        			'std' => '',
        			'class' => 'mini',
        			'type' => 'text');

		$options[] = array(	'id' => 'social_googleplus',
							'desc' => __('Google+', 'gsnbasic'),
        			'std' => '',
        			'class' => 'mini',
        			'type' => 'text');

		$options[] = array(	'id' => 'social_youtube',
							'desc' => __('Youtube', 'gsnbasic'),
        			'std' => '',
        			'class' => 'mini',
        			'type' => 'text');

		$options[] = array(	'id' => 'social_vimeo',
							'desc' => __('Vimeo', 'gsnbasic'),
        			'std' => '',
        			'class' => 'mini',
        			'type' => 'text');

		$options[] = array(	'id' => 'social_linkedin',
							'desc' => __('LinkedIn', 'gsnbasic'),
        			'std' => '',
        			'class' => 'mini',
        			'type' => 'text');

		$options[] = array(	'id' => 'social_pinterest',
							'desc' => __('Pinterest', 'gsnbasic'),
        			'std' => '',
        			'class' => 'mini',
        			'type' => 'text');

		$options[] = array(	'id' => 'social_rss',
							'desc' => __('RSS Feed', 'gsnbasic'),
        			'std' => '',
        			'class' => 'mini',
        			'type' => 'text');

		$options[] = array(	'id' => 'social_tumblr',
							'desc' => __('Tumblr', 'gsnbasic'),
        			'std' => '',
        			'class' => 'mini',
        			'type' => 'text');

    $options[] = array(	'id' => 'social_flickr',
							'desc' => __('Flickr', 'gsnbasic'),
        			'std' => '',
        			'class' => 'mini',
        			'type' => 'text');

    $options[] = array(	'id' => 'social_instagram',
							'desc' => __('Instagram', 'gsnbasic'),
        			'std' => '',
        			'class' => 'mini',
        			'type' => 'text');

    $options[] = array(	'id' => 'social_dribbble',
							'desc' => __('Dribbble', 'gsnbasic'),
        			'std' => '',
        			'class' => 'mini',
        			'type' => 'text');

    $options[] = array(	'id' => 'social_skype',
							'desc' => __('Skype', 'gsnbasic'),
        			'std' => '',
        			'class' => 'mini',
        			'type' => 'text');

    $options[] = array(	'id' => 'social_foursquare',
							'desc' => __('Foursquare', 'gsnbasic'),
        			'std' => '',
        			'class' => 'mini',
        			'type' => 'text');

		$options[] = array(	'id' => 'social_soundcloud',
							'desc' => __('SoundCloud', 'gsnbasic'),
        			'std' => '',
        			'class' => 'mini',
        			'type' => 'text');

		$options[] = array(	'id' => 'social_github',
					'desc' => __('GitHub', 'gsnbasic'),
        			'std' => '',
        			'class' => 'mini',
        			'type' => 'text');

		$options[] = array( 'name' => __('Other', 'gsnbasic'),
							'type' => 'heading');

		$options[] = array( 'name' => __('Custom CSS', 'gsnbasic'),
							'desc' => __('Additional CSS', 'gsnbasic'),
							'id' => 'custom_css',
							'std' => '',
							'type' => 'textarea');

		return $options;
}