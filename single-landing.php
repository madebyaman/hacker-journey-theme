<?php

/**
 * This file adds the Landing page template to the Showcase Pro Theme.
 *
 * @author Aman Thakur
 * @package Hacker Journey Theme
 */

//* Add landing body class to the head
add_filter('body_class', 'hacker_add_body_class');
function hacker_add_body_class($classes)
{

  $classes[] = 'hacker-landing';
  return $classes;

}

add_action('genesis_after_content', 'signup_form');
function signup_form()
{
  ?><div class="signup-form">
  <form id="signup-form" action="<?php the_field('form-action'); ?>" method="post"  target="_blank" name="landing-signup-form">
		<input type="email" value="" id="subbox" placeholder="Enter your e-mail address" name="email" required="required" />
    <input type="submit" value="Get your free gift" id="subbutton" />
  </form>
  <p class="safe-text">No spam. Unsubscribe anytime. 100% privacy</p>
<?php

}

// Remove call to action widget
unregister_sidebar('call-to-action');

//* Remove navigation
remove_theme_support('genesis-menus');

//* Remove breadcrumbs
remove_action('genesis_before_loop', 'genesis_do_breadcrumbs');

//* Remove site footer elements
remove_action('genesis_footer', 'genesis_footer_markup_open', 5);
remove_action('genesis_footer', 'genesis_do_footer');
remove_action('genesis_footer', 'genesis_footer_markup_close', 15);


// Display Featured Image on top of the page 
add_action('genesis_before_entry', 'featured_post_image', 8);
function featured_post_image()
{
  the_post_thumbnail('post-image');
}

// Remove the entry meta in the entry header (requires HTML5 theme support)
remove_action('genesis_entry_header', 'genesis_post_info', 12);

//* Run the Genesis loop
genesis();
