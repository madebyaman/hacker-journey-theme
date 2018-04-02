<?php

/**
 * Theme customizations
 * 
 * @package   Hacker Journey
 * @author    Aman Thakur
 * @link      http://www.amanthakur.me
 * @copyright Copyright (c) 2018, Aman Thakur
 * @license   GPL-2.0+
 */

load_child_theme_textdomain('hacker');

add_action('genesis_setup', 'hacker_setup', 15);

/**
 * Theme setup.
 * 
 * Attach all of the site-wide functions to the correct hooks and filters. All the functions themselves are defined below this setup function.
 * 
 * @since 1.0.0
 */

function hacker_setup()
{

  // Define theme constants.
  define('CHILD_THEME_NAME', 'Hacker Journey');
  define('CHILD_THEME_URL', 'http://www.amanthakur.me/');
  define('CHILD_THEME_VERSION', '1.0.0');

  // Enqueue stylesheet
  wp_enqueue_style(
    'genesis-fonts',
    '//fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700',
    array(),
    CHILD_THEME_VERSION
  );

  wp_enqueue_style('dashicons');

  wp_enqueue_script(
    'hacker-responsive-menu',
    get_stylesheet_directory_uri() . '/js/responsive-menu.js',
    array('jquery'),
    CHILD_THEME_VERSION,
    true
  );

  wp_localize_script(
    'hacker-responsive-menu',
    'genesis_responsive_menu',
    hacker_responsive_menu_settings()
  );

  // Add HTML5 markup structures
  add_theme_support('html5', array('comment-list', 'comment-form', 'search-form', 'gallery', 'caption'));

  // Add viewport meta tags for mobile browsers
  add_theme_support('genesis-responsive-viewport');

  // Add theme support for accessiblity
  add_theme_support(
    'genesis-accessibility',
    array(
      '404-page',
      'drop-down-menu',
      'headings',
      'rems',
      'search-form',
      'skip-links',
    )

  );

  // Adds custom logo in Customizer > Site Identity.
  add_theme_support(
    'custom-logo',
    array(
      'height' => 120,
      'width' => 700,
      'flex-height' => true,
      'flex-width' => true,
    )
  );

  // Removes output of primary navigation right extras.
  remove_filter('genesis_nav_items', 'genesis_nav_right', 10, 2);
  remove_filter('wp_nav_menu_items', 'genesis_nav_right', 10, 2);

  // Displays custom logo.
  add_action('genesis_site_title', 'the_custom_logo', 0);

  // Repositions primary navigation menu.
  remove_action('genesis_after_header', 'genesis_do_nav');
  add_action('genesis_header', 'genesis_do_nav', 12);

  // Unregister layouts that use sidebars
  genesis_unregister_layout('content-sidebar-sidebar');
  genesis_unregister_layout('sidebar-content-sidebar');
  genesis_unregister_layout('sidebar-sidebar-content');
  genesis_unregister_layout('content-sidebar');
  genesis_unregister_layout('sidebar-content');

  // Remove the primary sidebar
  unregister_sidebar('sidebar');
  remove_action('genesis_sidebar', 'genesis_do_sidebar');

  // Remove the secondary sidebar
  unregister_sidebar('sidebar-alt');
  remove_action('genesis_sidebar_alt', 'genesis_do_sidebar_alt');

  

  // Register widget area for call-to-action
  genesis_register_sidebar(array(
    'id' => 'call-to-action',
    'name' => __('Call to action', 'hacker'),
    'description' => __('This is the call-to-action widget.', 'hacker'),
  ));

  // Modify the Genesis content limit read more link
  add_filter('get_the_content_more_link', 'hacker_read_more_link');
  function hacker_read_more_link()
  {
    return '<p><a class="more-link" href="' . get_permalink() . '">Read More>></a></p>';
  }

  //* Change the footer text
  add_filter('genesis_footer_creds_text', 'hacker_footer_creds_filter');
  function hacker_footer_creds_filter($creds)
  {
    $creds = '[footer_copyright] &middot; <a href="http://www.hackerjourney.com">Hacker Journey</a> &middot; Built on the <a href="http://www.studiopress.com/themes/genesis" title="Genesis Framework">Genesis Framework</a>';
    return $creds;
  }

  //* Modify the size of the Gravatar in comments
  add_filter('genesis_comment_list_args', 'hacker_comments_gravatar');
  function hacker_comments_gravatar($args)
  {
    $args['avatar_size'] = 50;
    return $args;
  }

  // Remove the date and time on comments in Genesis child themes
  add_filter('genesis_show_comment_date', '__return_false');

  // Customize entry meta in entry header
  add_filter('genesis_post_info', 'hacker_entry_meta_header');
  function hacker_entry_meta_header($post_info)
  {
    $post_info = '[post_date] by [post_author_posts_link] [post_edit]';
    return $post_info;
  }

  // Remove the header right widget area
  unregister_sidebar('header-right');

  // Remove entry meta in entry footer from blog page.
  add_filter('genesis_entry_footer', 'remove_entry_footer', 2);
  function remove_entry_footer()
  {
    if (is_single())
      return;
    remove_action('genesis_entry_footer', 'genesis_entry_footer_markup_open', 5);
    remove_action('genesis_entry_footer', 'genesis_post_meta');
    remove_action('genesis_entry_footer', 'genesis_entry_footer_markup_close', 15);
  }


}

/**
 * Defines responsive menu settings.
 *
 * @since 2.3.0
 */
function hacker_responsive_menu_settings()
{
  $settings = array(
    'mainMenu' => __('Menu', 'hacker'),
    'menuIconClass' => 'dashicons-before dashicons-menu',
    'subMenu' => __('Submenu', 'hacker'),
    'subMenuIconClass' => 'dashicons-before dashicons-arrow-down-alt2',
    'menuClasses' => array(
      'combine' => array(
        '.nav-primary',
      ),
      'others' => array(),
    ),
  );
  return $settings;
}

add_action('genesis_meta', 'cta_setup');

function cta_setup()
{
  if (is_active_sidebar('call-to-action')) {
    add_action('genesis_header', 'cta_add', 12);
  }
}

function cta_add()
{
  genesis_widget_area('call-to-action', array(
    'before' => '<div class="call-to-action"><div class="wrap">',
    'after' => '</div></div>',
  ));
}



?>