<?php
/**
 * Custom functions that act independently of the theme templates
 *
 * Eventually, some of the functionality here could be replaced by core features
 *
 * @package gsnbasic
 */

/**
 * Get our wp_nav_menu() fallback, wp_page_menu(), to show a home link.
 *
 * @param array $args Configuration arguments.
 * @return array
 */
function gsnbasic_page_menu_args( $args ) {
  $args['show_home'] = true;
  return $args;
}
add_filter( 'wp_page_menu_args', 'gsnbasic_page_menu_args' );

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function gsnbasic_body_classes( $classes ) {
  // Adds a class of group-blog to blogs with more than 1 published author.
  if ( is_multi_author() ) {
    $classes[] = 'group-blog';
  }

  return $classes;
}
add_filter( 'body_class', 'gsnbasic_body_classes' );


if ( version_compare( $GLOBALS['wp_version'], '4.1', '<' ) ) :
  /**
   * Filters wp_title to print a neat <title> tag based on what is being viewed.
   *
   * @param string $title Default title text for current view.
   * @param string $sep Optional separator.
   * @return string The filtered title.
   */
  function gsnbasic_wp_title( $title, $sep ) {
    if ( is_feed() ) {
      return $title;
    }
    global $page, $paged;
    // Add the blog name
    $title .= get_bloginfo( 'name', 'display' );
    // Add the blog description for the home/front page.
    $site_description = get_bloginfo( 'description', 'display' );
    if ( $site_description && ( is_home() || is_front_page() ) ) {
      $title .= " $sep $site_description";
    }
    // Add a page number if necessary:
    if ( ( $paged >= 2 || $page >= 2 ) && ! is_404() ) {
      $title .= " $sep " . sprintf( __( 'Page %s', 'gsnbasic' ), max( $paged, $page ) );
    }
    return $title;
  }
  add_filter( 'wp_title', 'gsnbasic_wp_title', 10, 2 );
  /**
   * Title shim for sites older than WordPress 4.1.
   *
   * @link https://make.wordpress.org/core/2014/10/29/title-tags-in-4-1/
   * @todo Remove this function when WordPress 4.3 is released.
   */
  function gsnbasic_render_title() {
    ?>
    <title><?php wp_title( '|', true, 'right' ); ?></title>
    <?php
  }
  add_action( 'wp_head', 'gsnbasic_render_title' );
endif;


// Mark Posts/Pages as Untiled when no title is used
add_filter( 'the_title', 'gsnbasic_title' );

function gsnbasic_title( $title ) {
  if ( $title == '' ) {
    return 'Untitled';
  } else {
    return $title;
  }
}

/**
 * Sets the authordata global when viewing an author archive.
 *
 * This provides backwards compatibility with
 * http://core.trac.wordpress.org/changeset/25574
 *
 * It removes the need to call the_post() and rewind_posts() in an author
 * template to print information about the author.
 *
 * @global WP_Query $wp_query WordPress Query object.
 * @return void
 */
function gsnbasic_setup_author() {
  global $wp_query;

  if ( $wp_query->is_author() && isset( $wp_query->post ) ) {
    $GLOBALS['authordata'] = get_userdata( $wp_query->post->post_author );
  }
}
add_action( 'wp', 'gsnbasic_setup_author' );

/************* search form *****************/

// Search Form
function gsnbasic_wpsearch( $form ) {
    $form = '<form method="get" class="form-search" action="' . home_url( '/' ) . '">
  <div class="row">
    <div class="col-lg-12">
      <div class="input-group">
        <input type="text" class="form-control search-query" value="' . get_search_query() . '" name="s" id="s" placeholder="'. esc_attr__('Search...','gsnbasic') .'">
        <span class="input-group-btn">
          <button type="submit" class="btn btn-default" name="submit" id="searchsubmit" value="Go"><span class="glyphicon glyphicon-search"></span></button>
        </span>
      </div>
    </div>
  </div>
</form>';
    return $form;
} // don't remove this bracket!

/****************** password protected post form *****/

add_filter( 'the_password_form', 'custom_password_form' );

function custom_password_form() {
  global $post;
  $label = 'pwbox-'.( empty( $post->ID ) ? rand() : $post->ID );
  $o = '<form class="protected-post-form" action="' . get_option('siteurl') . '/wp-login.php?action=postpass" method="post">
  <div class="row">
    <div class="col-lg-10">
        ' . __( "<p>This post is password protected. To view it please enter your password below:</p>" ,'gsnbasic') . '
        <label for="' . $label . '">' . __( "Password:" ,'gsnbasic') . ' </label>
      <div class="input-group">
        <input class="form-control" value="' . get_search_query() . '" name="post_password" id="' . $label . '" type="password">
        <span class="input-group-btn"><button type="submit" class="btn btn-default" name="submit" id="searchsubmit" vvalue="' . esc_attr__( "Submit",'gsnbasic' ) . '">' . __( "Submit" ,'gsnbasic') . '</button>
        </span>
      </div>
    </div>
  </div>
</form>';
  return $o;
}

// Add Bootstrap classes for table
add_filter( 'the_content', 'gsnbasic_add_custom_table_class' );
function gsnbasic_add_custom_table_class( $content ) {
    return str_replace( '<table>', '<table class="table table-hover">', $content );
}

if ( ! function_exists( 'gsnbasic_social' ) ) :
/**
 * Display social links in footer and widgets if enabled
 */
function gsnbasic_social(){
  $services = array (
    'facebook'    => 'Facebook',
    'twitter'     => 'Twitter',
    'googleplus'  => 'Google+',
    'youtube'     => 'Youtube',
    'vimeo'       => 'Vimeo',
    'linkedin'    => 'LinkedIn',
    'pinterest'   => 'Pinterest',
    'rss'         => 'RSS',
    'tumblr'      => 'Tumblr',
    'flickr'      => 'Flickr',
    'instagram'   => 'Instagram',
    'dribbble'    => 'Dribbble',
    'skype'       => 'Skype',
    'foursquare'  => 'Foursquare',
    'soundcloud'  => 'SoundCloud',
    'github'      => 'GitHub'
    );

  echo '<div class="social-icons">';

  foreach ( $services as $service => $name ) :

      $active[ $service ] = of_get_option ( 'social_'.$service );
      if ( $active[$service] ) { echo '<a href="'. esc_url( $active[$service] ) .'" title="'. __('Follow us on ','gsnbasic').$name.'" class="'. $service .'" target="_blank"><i class="social_icon fa fa-'.$service.'"></i></a>';}

  endforeach;
  echo '</div>';

}
endif;

if ( ! function_exists( 'gsnbasic_header_menu' ) ) :
/**
 * Header menu (should you choose to use one)
 */
function gsnbasic_header_menu() {
  // display the WordPress Custom Menu if available
  wp_nav_menu(array(
    'menu'              => 'primary',
    'theme_location'    => 'primary',
    'depth'             => 2,
    'container'         => 'div',
    'container_class'   => 'collapse navbar-collapse navbar-ex1-collapse',
    'menu_class'        => 'nav navbar-nav',
    'fallback_cb'       => 'wp_bootstrap_navwalker::fallback',
    'walker'            => new wp_bootstrap_navwalker()
  ));
} /* end header menu */
endif;

if ( ! function_exists( 'gsnbasic_footer_links' ) ) :
/**
 * Footer menu (should you choose to use one)
 */
function gsnbasic_footer_links() {
  // display the WordPress Custom Menu if available
  wp_nav_menu(array(
    'container'       => '',                              // remove nav container
    'container_class' => 'footer-links clearfix',   // class of container (should you choose to use it)
    'menu'            => __( 'Footer Links', 'gsnbasic' ),   // nav name
    'menu_class'      => 'nav footer-nav clearfix',      // adding custom nav class
    'theme_location'  => 'footer-links',             // where it's located in the theme
    'before'          => '',                                 // before the menu
    'after'           => '',                                  // after the menu
    'link_before'     => '',                            // before each link
    'link_after'      => '',                             // after each link
    'depth'           => 0,                                   // limit the depth of the nav
    'fallback_cb'     => 'gsnbasic_footer_links_fallback'  // fallback function
  ));
} /* end gsnbasic footer link */
endif;


if ( ! function_exists( 'gsnbasic_call_for_action' ) ) :
/**
 * Call for action text and button displayed above content
 */
function gsnbasic_call_for_action() {
  if ( is_front_page() && of_get_option( 'w2f_cfa_text' )!=''){
    echo '<div class="cfa">';
      echo '<div class="container">';
        echo '<div class="col-sm-8">';
          echo '<span class="cfa-text">'. of_get_option( 'w2f_cfa_text' ).'</span>';
          echo '</div>';
          echo '<div class="col-sm-4">';
          echo '<a class="btn btn-lg cfa-button" href="'. of_get_option( 'w2f_cfa_link' ). '">'. of_get_option( 'w2f_cfa_button' ). '</a>';
          echo '</div>';
      echo '</div>';
    echo '</div>';
  }
}
endif;

if ( ! function_exists( 'gsnbasic_featured_slider' ) ) :
/**
 * Featured image slider, displayed on front page for static page and blog
 */
function gsnbasic_featured_slider() {
  if ( is_front_page() && of_get_option( 'gsnbasic_slider_checkbox' ) == 1 ) {
    echo '<div class="flexslider">';
      echo '<ul class="slides">';

        $count = of_get_option( 'gsnbasic_slide_number' );
        $slidecat =of_get_option( 'gsnbasic_slide_categories' );

        $query = new WP_Query( array( 'cat' =>$slidecat,'posts_per_page' =>$count ) );
        if ($query->have_posts()) :
          while ($query->have_posts()) : $query->the_post();

          echo '<li><a href="'. get_permalink() .'">';
            if ( (function_exists( 'has_post_thumbnail' )) && ( has_post_thumbnail() ) ) :
              echo get_the_post_thumbnail();
            endif;

              echo '<div class="flex-caption">';
                  if ( get_the_title() != '' ) echo '<h2 class="entry-title">'. get_the_title().'</h2>';
                  if ( get_the_excerpt() != '' ) echo '<div class="excerpt">' . get_the_excerpt() .'</div>';
              echo '</div>';

              endwhile;
            endif;

          echo '</a></li>';
      echo '</ul>';
    echo ' </div>';
  }
}
endif;

/**
 * function to show the footer info, copyright information
 */
function gsnbasic_footer_info() {
global $gsnbasic_footer_info;
  printf( __( '© %1$s | %2$s', 'gsnbasic' ) , '<a href="http://groceryshopping.net/" target="_blank">Grocery Shopping Network</a>', '<a href="http://patft1.uspto.gov/netacgi/nph-Parser?Sect1=PTO1&amp;Sect2=HITOFF&amp;d=PALL&amp;p=1&amp;u=%2Fnetahtml%2FPTO%2Fsrchnum.htm&amp;r=1&amp;f=G&amp;l=50&amp;s1=7734514.PN.&amp;OS=PN/7734514&amp;RS=PN/7734514" target="_blank">Patent No US 7,734,514 B2.</a>');
}

if ( ! function_exists( 'get_gsnbasic_theme_options' ) ) {
/**
 * Get information from Theme Options and add it into wp_head
 */
    function get_gsnbasic_theme_options(){

      echo '<style type="text/css">';

      if ( of_get_option('link_color')) {
        echo 'a, #infinite-handle span, #secondary .widget .post-content a {color:' . of_get_option('link_color') . '}';
      }
      if ( of_get_option('link_hover_color')) {
        echo 'a:hover, a:active, #secondary .widget .post-content a:hover {color: '.of_get_option('link_hover_color').';}';
      }
      if ( of_get_option('element_color')) {
        echo '.btn-default, .label-default, .flex-caption h2, .btn.btn-default.read-more, button {background-color: '.of_get_option('element_color').'; border-color: '.of_get_option('element_color').';} .site-main [class*="navigation"] a, .more-link { color: '.of_get_option('element_color').'}';
      }
      if ( of_get_option('element_color_hover')) {
        echo '.btn-default:hover, .label-default[href]:hover, .tagcloud a:hover, button, .main-content [class*="navigation"] a:hover, .label-default[href]:focus, #infinite-handle span:hover, .btn.btn-default.read-more:hover, .btn-default:hover, .scroll-to-top:hover, .btn-default:focus, .btn-default:active, .btn-default.active, .site-main [class*="navigation"] a:hover, .more-link:hover, #image-navigation .nav-previous a:hover, #image-navigation .nav-next a:hover, .cfa-button:hover { background-color: '.of_get_option('element_color_hover').'; border-color: '.of_get_option('element_color_hover').'; }';
      }
      if ( of_get_option('cfa_bg_color')) {
        echo '.cfa { background-color: '.of_get_option('cfa_bg_color').'; } .cfa-button:hover a {color: '.of_get_option('cfa_bg_color').';}';
      }
      if ( of_get_option('cfa_color')) {
        echo '.cfa-text { color: '.of_get_option('cfa_color').';}';
      }
      if ( of_get_option('cfa_btn_color') || of_get_option('cfa_btn_txt_color') ) {
        echo '.cfa-button {border-color: '.of_get_option('cfa_btn_color').'; color: '.of_get_option('cfa_btn_txt_color').';}';
      }
      if ( of_get_option('heading_color')) {
        echo 'h1, h2, h3, h4, h5, h6, .h1, .h2, .h3, .h4, .h5, .h6, .entry-title {color: '.of_get_option('heading_color').';}';
      }
      if ( of_get_option('nav_bg_color')) {
        echo '.navbar.navbar-default {background-color: '.of_get_option('nav_bg_color').';}';
      }
      if ( of_get_option('nav_link_color')) {
        echo '.navbar-default .navbar-nav > li > a, .navbar-default .navbar-nav > .open > a, .navbar-default .navbar-nav > .open > a:hover, .navbar-default .navbar-nav > .open > a:focus, .navbar-default .navbar-nav > .active > a, .navbar-default .navbar-nav > .active > a:hover, .navbar-default .navbar-nav > .active > a:focus { color: '.of_get_option('nav_link_color').';}';
      }
      if ( of_get_option('nav_item_hover_color')) {
        echo '.navbar-default .navbar-nav > li > a:hover, .navbar-default .navbar-nav > .active > a, .navbar-default .navbar-nav > .active > a:hover, .navbar-default .navbar-nav > .active > a:focus, .navbar-default .navbar-nav > li > a:hover, .navbar-default .navbar-nav > li > a:focus, .navbar-default .navbar-nav > .open > a, .navbar-default .navbar-nav > .open > a:hover, .navbar-default .navbar-nav > .open > a:focus, .entry-title a:hover {color: '.of_get_option('nav_item_hover_color').';}';
      }
      if ( of_get_option('nav_dropdown_bg')) {
        echo '.dropdown-menu {background-color: '.of_get_option('nav_dropdown_bg').';}';
      }
      if ( of_get_option('nav_dropdown_item')) {
        echo '.navbar-default .navbar-nav .open .dropdown-menu > li > a, .dropdown-menu > li > a { color: '.of_get_option('nav_dropdown_item').';}';
      }
      if ( of_get_option('nav_dropdown_bg_hover') || of_get_option('nav_dropdown_item_hover') ) {
        echo '.dropdown-menu > li > a:hover, .dropdown-menu > li > a:focus, .navbar-default .navbar-nav .open .dropdown-menu > li > a:hover, .navbar-default .navbar-nav .open .dropdown-menu > li > a:focus, .dropdown-menu > .active > a, .dropdown-menu > .active > a:hover, .dropdown-menu > .active > a:focus {background-color: '.of_get_option('nav_dropdown_bg_hover').'; color:'.of_get_option('nav_dropdown_item_hover').'}';
      }
      if ( of_get_option('footer_bg_color')) {
        echo '#colophon {background-color: '.of_get_option('footer_bg_color').';}';
      }
      if ( of_get_option('footer_text_color')) {
        echo '#footer-area, .site-info {color: '.of_get_option('footer_text_color').';}';
      }
      if ( of_get_option('footer_widget_bg_color')) {
        echo '#footer-area {background-color: '.of_get_option('footer_widget_bg_color').';}';
      }
      if ( of_get_option('footer_link_color')) {
        echo '.site-info a, #footer-area a {color: '.of_get_option('footer_link_color').';}';
      }
      if ( of_get_option('social_color')) {
        echo '.well .social-icons a {background-color: '.of_get_option('social_color').' !important ;}';
      }
      if ( of_get_option('social_footer_color')) {
        echo '#footer-area .social-icons a {background-color: '.of_get_option('social_footer_color').' ;}';
      }
      $typography = of_get_option('main_body_typography');
      if ( $typography ) {
        echo '.entry-content {font-family: ' . $typography['face'] . '; font-size:' . $typography['size'] . '; font-weight: ' . $typography['style'] . '; color:'.$typography['color'] . ';}';
      }
      if ( of_get_option('custom_css')) {
        echo of_get_option( 'custom_css', 'no entry' );
      }
        echo '</style>';
    }
}
add_action( 'wp_head', 'get_gsnbasic_theme_options', 10 );

// Theme Options sidebar
add_action( 'optionsframework_after', 'gsnbasic_options_display_sidebar' );

function gsnbasic_options_display_sidebar() { ?>
<?php }

/**
 * Add Bootstrap thumbnail styling to images with captions
 * Use <figure> and <figcaption>
 *
 * @link http://justintadlock.com/archives/2011/07/01/captions-in-wordpress
 */
function gsnbasic_caption($output, $attr, $content) {
  if (is_feed()) {
    return $output;
  }

  $defaults = array(
    'id'      => '',
    'align'   => 'alignnone',
    'width'   => '',
    'caption' => ''
  );

  $attr = shortcode_atts($defaults, $attr);

  // If the width is less than 1 or there is no caption, return the content wrapped between the [caption] tags
  if ($attr['width'] < 1 || empty($attr['caption'])) {
    return $content;
  }

  // Set up the attributes for the caption <figure>
  $attributes  = (!empty($attr['id']) ? ' id="' . esc_attr($attr['id']) . '"' : '' );
  $attributes .= ' class="thumbnail wp-caption ' . esc_attr($attr['align']) . '"';
  $attributes .= ' style="width: ' . (esc_attr($attr['width']) + 10) . 'px"';

  $output  = '<figure' . $attributes .'>';
  $output .= do_shortcode($content);
  $output .= '<figcaption class="caption wp-caption-text">' . $attr['caption'] . '</figcaption>';
  $output .= '</figure>';

  return $output;
}
add_filter('img_caption_shortcode', 'gsnbasic_caption', 10, 3);

/**
 * Skype URI support for social media icons
 */
function gsnbasic_allow_skype_protocol( $protocols ){
    $protocols[] = 'skype';
    return $protocols;
}
add_filter( 'kses_allowed_protocols' , 'gsnbasic_allow_skype_protocol' );

/*
 * This one shows/hides the an option when a checkbox is clicked.
 */
add_action( 'optionsframework_custom_scripts', 'optionsframework_custom_scripts' );

function optionsframework_custom_scripts() { ?>

<script type="text/javascript">
jQuery(document).ready(function() {

  jQuery('#gsnbasic_slider_checkbox').click(function() {
      jQuery('#section-gsnbasic_slide_categories').fadeToggle(400);
  });

  if (jQuery('#gsnbasic_slider_checkbox:checked').val() !== undefined) {
    jQuery('#section-gsnbasic_slide_categories').show();
  }

  jQuery('#gsnbasic_slider_checkbox').click(function() {
      jQuery('#section-gsnbasic_slide_number').fadeToggle(400);
  });

  if (jQuery('#gsnbasic_slider_checkbox:checked').val() !== undefined) {
    jQuery('#section-gsnbasic_slide_number').show();
  }

});
</script>

<?php
}
