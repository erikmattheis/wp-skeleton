<?php
/**
 * gsnbasic functions and definitions
 *
 * @package gsnbasic
 */

/**
 * Set the content width based on the theme's design and stylesheet.
 */
if ( ! isset( $content_width ) ) {
	$content_width = 648; /* pixels */
}

/**
 * Set the content width for full width pages with no sidebar.
 */
function gsnbasic_content_width() {
  if ( is_page_template( 'page-fullwidth.php' ) ) {
    global $content_width;
    $content_width = 1008; /* pixels */
  }
}
add_action( 'template_redirect', 'gsnbasic_content_width' );

function remove_menus () {
	global $menu;
	$restricted = array(__('Posts'), __('Comments'));
	end ($menu);
	while (prev($menu)){
		$value = explode( ' ', $menu[key($menu)][0]);
		if (in_array($value[0] != NULL ? $value[0] : "", $restricted)){unset($menu[key($menu)]);}
	}
}
add_action( 'admin_menu', 'remove_menus' );

function remove_admin_bar_links() {
		global $wp_admin_bar;
		$wp_admin_bar->remove_menu('wp-logo');          // Remove the WordPress logo
		$wp_admin_bar->remove_menu('about');            // Remove the about WordPress link
		$wp_admin_bar->remove_menu('wporg');            // Remove the WordPress.org link
		$wp_admin_bar->remove_menu('documentation');    // Remove the WordPress documentation link
		$wp_admin_bar->remove_menu('support-forums');   // Remove the support forums link
		$wp_admin_bar->remove_menu('feedback');         // Remove the feedback link
		$wp_admin_bar->remove_menu('comments');         // Remove the comments link
		$wp_admin_bar->remove_menu('new-content');      // Remove the content link
}
add_action( 'wp_before_admin_bar_render', 'remove_admin_bar_links' );

if ( ! function_exists( 'gsnbasic_main_content_bootstrap_classes' ) ) :
/**
 * Add Bootstrap classes to the main-content-area wrapper.
 */
function gsnbasic_main_content_bootstrap_classes() {
	if ( is_page_template( 'page-fullwidth.php' ) ) {
		return 'col-sm-12 col-md-12';
	}
	return 'col-sm-12 col-md-8';
}
endif; // gsnbasic_main_content_bootstrap_classes

if ( ! function_exists( 'gsnbasic_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function gsnbasic_setup() {

	/*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 */
	load_theme_textdomain( 'gsnbasic', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/**
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link http://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
	 */
	add_theme_support( 'post-thumbnails' );

  add_image_size( 'gsnbasic-featured', 750, 410, true );
	add_image_size( 'tab-small', 60, 60 , true); // Small Thumbnail

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
		'primary' => __( 'Primary Menu', 'gsnbasic' ),
		'footer-links' => __( 'Footer Links', 'gsnbasic' ) // secondary nav in footer
	) );

	// Enable support for Post Formats.
	add_theme_support( 'post-formats', array( 'aside', 'image', 'video', 'quote', 'link' ) );

	// Setup the WordPress core custom background feature.
	add_theme_support( 'custom-background', apply_filters( 'gsnbasic_custom_background_args', array(
		'default-color' => 'F2F2F2',
		'default-image' => '',
	) ) );

  // Enable support for HTML5 markup.
  add_theme_support( 'html5', array(
    'comment-list',
    'search-form',
    'comment-form',
    'gallery',
    'caption',
  ) );

  /*
   * Let WordPress manage the document title.
   * By adding theme support, we declare that this theme does not use a
   * hard-coded <title> tag in the document head, and expect WordPress to
   * provide it for us.
   */
  add_theme_support( 'title-tag' );

}
endif; // gsnbasic_setup
add_action( 'after_setup_theme', 'gsnbasic_setup' );

/**
 * Register widgetized area and update sidebar with default widgets.
 */
function gsnbasic_widgets_init() {
  	register_sidebar( array(
  		'name'          => __( 'Sidebar', 'gsnbasic' ),
  		'id'            => 'sidebar-1',
  		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
  		'after_widget'  => '</aside>',
  		'before_title'  => '<h3 class="widget-title">',
  		'after_title'   => '</h3>',
  	) );

    register_sidebar(array(
    	'id' => 'home-widget-1',
    	'name' => __( 'Homepage Widget 1', 'gsnbasic' ),
    	'description' => __( 'Displays on the Home Page', 'gsnbasic' ),
    	'before_widget' => '<div id="%1$s" class="widget %2$s">',
    	'after_widget' => '</div>',
    	'before_title' => '<h3 class="widgettitle">',
    	'after_title' => '</h3>',
    ));

    register_sidebar(array(
      'id' => 'home-widget-2',
      'name' =>  __( 'Homepage Widget 2', 'gsnbasic' ),
      'description' => __( 'Displays on the Home Page', 'gsnbasic' ),
      'before_widget' => '<div id="%1$s" class="widget %2$s">',
      'after_widget' => '</div>',
      'before_title' => '<h3 class="widgettitle">',
      'after_title' => '</h3>',
    ));

    register_sidebar(array(
      'id' => 'home-widget-3',
      'name' =>  __( 'Homepage Widget 3', 'gsnbasic' ),
      'description' =>  __( 'Displays on the Home Page', 'gsnbasic' ),
      'before_widget' => '<div id="%1$s" class="widget %2$s">',
      'after_widget' => '</div>',
      'before_title' => '<h3 class="widgettitle">',
      'after_title' => '</h3>',
    ));

    register_sidebar(array(
    	'id' => 'footer-widget-1',
    	'name' =>  __( 'Footer Widget 1', 'gsnbasic' ),
    	'description' =>  __( 'Used for footer widget area', 'gsnbasic' ),
    	'before_widget' => '<div id="%1$s" class="widget %2$s">',
    	'after_widget' => '</div>',
    	'before_title' => '<h3 class="widgettitle">',
    	'after_title' => '</h3>',
    ));

    register_sidebar(array(
      'id' => 'footer-widget-2',
      'name' =>  __( 'Footer Widget 2', 'gsnbasic' ),
      'description' =>  __( 'Used for footer widget area', 'gsnbasic' ),
      'before_widget' => '<div id="%1$s" class="widget %2$s">',
      'after_widget' => '</div>',
      'before_title' => '<h3 class="widgettitle">',
      'after_title' => '</h3>',
    ));

    register_sidebar(array(
      'id' => 'footer-widget-3',
      'name' =>  __( 'Footer Widget 3', 'gsnbasic' ),
      'description' =>  __( 'Used for footer widget area', 'gsnbasic' ),
      'before_widget' => '<div id="%1$s" class="widget %2$s">',
      'after_widget' => '</div>',
      'before_title' => '<h3 class="widgettitle">',
      'after_title' => '</h3>',
    ));

    register_widget( 'gsnbasic_social_widget' );

}
add_action( 'widgets_init', 'gsnbasic_widgets_init' );



/* --------------------------------------------------------------
       Theme Widgets
-------------------------------------------------------------- */
require_once(get_template_directory() . '/inc/widgets/widget-social.php');

/**
 * adding the gsnbasic search form (created in extra.php)
 */

add_filter( 'get_search_form', 'gsnbasic_wpsearch' );

/**
 * This function removes inline styles set by WordPress gallery.
 */
function gsnbasic_remove_gallery_css( $css ) {
  return preg_replace( "#<style type='text/css'>(.*?)</style>#s", '', $css );
}

add_filter( 'gallery_style', 'gsnbasic_remove_gallery_css' );

/**
 * Enqueue scripts and styles.
 */
function gsnbasic_scripts() {

  // Add Bootstrap default CSS
  wp_enqueue_style( 'gsnbasic-bootstrap', get_template_directory_uri() . '/inc/css/bootstrap.min.css' );

  // Add Font Awesome stylesheet
  wp_enqueue_style( 'gsnbasic-icons', get_template_directory_uri().'/inc/css/font-awesome.min.css' );

  // Add Google Fonts
  wp_register_style( 'gsnbasic-fonts', '//fonts.googleapis.com/css?family=Open+Sans:400italic,400,600,700|Roboto+Slab:400,300,700');

  wp_enqueue_style( 'gsnbasic-fonts' );

  // Add slider CSS only if is front page ans slider is enabled
  if( ( is_home() || is_front_page() ) && of_get_option('gsnbasic_slider_checkbox') == 1 ) {
		wp_enqueue_style( 'flexslider-css', get_template_directory_uri().'/inc/css/flexslider.css' );
  }

  // Add main theme stylesheet
	wp_enqueue_style( 'gsnbasic-style', get_stylesheet_uri() );

  // Add Modernizr for better HTML5 and CSS3 support
  wp_enqueue_script('gsnbasic-modernizr', get_template_directory_uri().'/inc/js/modernizr.min.js', array('jquery') );

  // Add Bootstrap default JS
	wp_enqueue_script('gsnbasic-bootstrapjs', get_template_directory_uri().'/inc/js/bootstrap.min.js', array('jquery') );

  // Add slider JS only if is front page ans slider is enabled
	if( ( is_home() || is_front_page() ) && of_get_option('gsnbasic_slider_checkbox') == 1 ) {
		wp_enqueue_script( 'flexslider-js', get_template_directory_uri() . '/inc/js/flexslider.min.js', array('jquery'), '20140222', true );
	}

  // Flexslider customization
  if( ( is_home() || is_front_page() ) && of_get_option('gsnbasic_slider_checkbox') == 1 ) {
    wp_enqueue_script( 'flexslider-customization', get_template_directory_uri() . '/inc/js/flexslider-custom.js', array('jquery', 'flexslider-js'), '20140716', true );
  }

  // Main theme related functions
	wp_enqueue_script( 'gsnbasic-functions', get_template_directory_uri() . '/inc/js/functions.min.js', array('jquery') );

	// This one is for accessibility
  wp_enqueue_script( 'gsnbasic-skip-link-focus-fix', get_template_directory_uri() . '/inc/js/skip-link-focus-fix.js', array(), '20140222', true );

  // Treaded comments
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'gsnbasic_scripts' );

/*
 * Loads the Options Panel
 *
 * If you're loading from a child theme use stylesheet_directory
 * instead of template_directory
 */

define('OPTIONS_FRAMEWORK_URL', get_template_directory() . '/inc/admin/');
define('OPTIONS_FRAMEWORK_DIRECTORY', get_template_directory_uri() . '/inc/admin/');
require_once (OPTIONS_FRAMEWORK_URL . 'options-framework.php');

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Custom functions that act independently of the theme templates.
 */
require get_template_directory() . '/inc/extras.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
require get_template_directory() . '/inc/jetpack.php';

/**
 * Load custom nav walker
 */
require get_template_directory() . '/inc/navwalker.php';
