<?php
/*
Author: Eddie Machado
URL: http://themble.com/bones/

This is where you can drop your custom functions or
just edit things like thumbnail sizes, header images,
sidebars, comments, etc.
*/

// //define theme domain for translation plugins:
// $string_domain = 'example_website';



// LOAD BONES CORE (if you remove this, the theme will break)
require_once( 'library/bones.php' );

// CUSTOMIZE THE WORDPRESS ADMIN (off by default)
// require_once( 'library/admin.php' );

/*********************
LAUNCH BONES
Let's get everything up and running.
*********************/

function bones_ahoy() {

  global $string_domain;

  //Allow editor style.
  add_editor_style( get_stylesheet_directory_uri() . '/assets/css/editor-style.css' );

  // let's get language support going, if you need it
  load_theme_textdomain( $string_domain, get_template_directory() . '/library/translation' );

  // USE THIS TEMPLATE TO CREATE CUSTOM POST TYPES EASILY
 // require_once( 'library/custom-post-type.php' );

  // launching operation cleanup
  add_action( 'init', 'bones_head_cleanup' );
  // A better title
  add_filter( 'wp_title', 'rw_title', 10, 3 );
  // remove WP version from RSS
  add_filter( 'the_generator', 'bones_rss_version' );
  // remove pesky injected css for recent comments widget
  add_filter( 'wp_head', 'bones_remove_wp_widget_recent_comments_style', 1 );
  // clean up comment styles in the head
  add_action( 'wp_head', 'bones_remove_recent_comments_style', 1 );
  // clean up gallery output in wp
  add_filter( 'gallery_style', 'bones_gallery_style' );

  // enqueue base scripts and styles
  add_action( 'wp_enqueue_scripts', 'bones_scripts_and_styles', 999 );
  // ie conditional wrapper

  // launching this stuff after theme setup
  bones_theme_support();

  // adding sidebars to Wordpress (these are created in functions.php)
  add_action( 'widgets_init', 'bones_register_sidebars' );

  // cleaning up random code around images
  add_filter( 'the_content', 'bones_filter_ptags_on_images' );
  // cleaning up excerpt
  add_filter( 'excerpt_more', 'bones_excerpt_more' );

} /* end bones ahoy */

// let's get this party started
add_action( 'after_setup_theme', 'bones_ahoy' );


/************* OEMBED SIZE OPTIONS *************/

if ( ! isset( $content_width ) ) {
	$content_width = 680;
}

/************* THUMBNAIL SIZE OPTIONS *************/

// Thumbnail sizes
add_image_size( 'img-400', 400, 99999999 );
add_image_size( 'img-800', 800, 99999999 );
add_image_size( 'img-1200', 1200, 99999999 );



/*
to add more sizes, simply copy a line from above
and change the dimensions & name. As long as you
upload a "featured image" as large as the biggest
set width or height, all the other sizes will be
auto-cropped.

To call a different size, simply change the text
inside the thumbnail function.

For example, to call the 300 x 100 sized image,
we would use the function:
<?php the_post_thumbnail( 'bones-thumb-300' ); ?>
for the 600 x 150 image:
<?php the_post_thumbnail( 'bones-thumb-600' ); ?>

You can change the names and dimensions to whatever
you like. Enjoy!
*/

add_filter( 'image_size_names_choose', 'bones_custom_image_sizes' );

function bones_custom_image_sizes( $sizes ) {
    return array_merge( $sizes, array(
        'img-400' => __('400px'),
        'img-800' => __('800px'),
        'img-1200' => __('1200px'),
    ) );
}

/*
The function above adds the ability to use the dropdown menu to select
the new images sizes you have just created from within the media manager
when you add media to your content blocks. If you add more image sizes,
duplicate one of the lines in the array and name it according to your
new image size.
*/

/************* THEME CUSTOMIZE *********************/

/*
  A good tutorial for creating your own Sections, Controls and Settings:
  http://code.tutsplus.com/series/a-guide-to-the-wordpress-theme-customizer--wp-33722

  Good articles on modifying the default options:
  http://natko.com/changing-default-wordpress-theme-customization-api-sections/
  http://code.tutsplus.com/tutorials/digging-into-the-theme-customizer-components--wp-27162

  To do:
  - Create a js for the postmessage transport method
  - Create some sanitize functions to sanitize inputs
  - Create some boilerplate Sections, Controls and Settings
*/

function bones_theme_customizer($wp_customize) {
  // $wp_customize calls go here.
  //
  // Uncomment the below lines to remove the default customize sections

  // $wp_customize->remove_section('title_tagline');
  // $wp_customize->remove_section('colors');
  // $wp_customize->remove_section('background_image');
  // $wp_customize->remove_section('static_front_page');
  // $wp_customize->remove_section('nav');

  // Uncomment the below lines to remove the default controls
  // $wp_customize->remove_control('blogdescription');

  // Uncomment the following to change the default section titles
  // $wp_customize->get_section('colors')->title = __( 'Theme Colors' );
  // $wp_customize->get_section('background_image')->title = __( 'Images' );
}

add_action( 'customize_register', 'bones_theme_customizer' );

/************* ACTIVE SIDEBARS ********************/

// Sidebars & Widgetizes Areas
function bones_register_sidebars() {

  global $string_domain;

	register_sidebar(array(
		'id' => 'sidebar1',
		'name' => __( 'Sidebar 1', $string_domain ),
		'description' => __( 'The first (primary) sidebar.', $string_domain ),
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h4 class="widgettitle">',
		'after_title' => '</h4>',
	));

	/*
	to add more sidebars or widgetized areas, just copy
	and edit the above sidebar code. In order to call
	your new sidebar just use the following code:

	Just change the name to whatever your new
	sidebar's id is, for example:

	register_sidebar(array(
		'id' => 'sidebar2',
		'name' => __( 'Sidebar 2', $string_domain ),
		'description' => __( 'The second (secondary) sidebar.', $string_domain ),
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h4 class="widgettitle">',
		'after_title' => '</h4>',
	));

	To call the sidebar in your template, you can just copy
	the sidebar.php file and rename it to your sidebar's name.
	So using the above example, it would be:
	sidebar-sidebar2.php

	*/
} // don't remove this bracket!


/************* COMMENT LAYOUT *********************/

// Comment Layout
function bones_comments( $comment, $args, $depth ) {
   $GLOBALS['comment'] = $comment; ?>
  <div id="comment-<?php comment_ID(); ?>" <?php comment_class('cf'); ?>>
    <article  class="cf">
      <header class="comment-author vcard">
        <?php
        /*
          this is the new responsive optimized comment image. It used the new HTML5 data-attribute to display comment gravatars on larger screens only. What this means is that on larger posts, mobile sites don't have a ton of requests for comment images. This makes load time incredibly fast! If you'd like to change it back, just replace it with the regular wordpress gravatar call:
          echo get_avatar($comment,$size='32',$default='<path_to_url>' );
        */
        ?>
        <?php // custom gravatar call ?>
        <?php
          // create variable
          $bgauthemail = get_comment_author_email();
        ?>
        <img data-gravatar="http://www.gravatar.com/avatar/<?php echo md5( $bgauthemail ); ?>?s=40" class="load-gravatar avatar avatar-48 photo" height="40" width="40" src="<?php echo get_template_directory_uri(); ?>/library/images/nothing.gif" />
        <?php // end custom gravatar call ?>
        <?php printf(__( '<cite class="fn">%1$s</cite> %2$s', $string_domain ), get_comment_author_link(), edit_comment_link(__( '(Edit)', $string_domain ),'  ','') ) ?>
        <time datetime="<?php echo comment_time('Y-m-j'); ?>"><a href="<?php echo htmlspecialchars( get_comment_link( $comment->comment_ID ) ) ?>"><?php comment_time(__( 'F jS, Y', $string_domain )); ?> </a></time>

      </header>
      <?php if ($comment->comment_approved == '0') : ?>
        <div class="alert alert-info">
          <p><?php _e( 'Your comment is awaiting moderation.', $string_domain ) ?></p>
        </div>
      <?php endif; ?>
      <section class="comment_content cf">
        <?php comment_text() ?>
      </section>
      <?php comment_reply_link(array_merge( $args, array('depth' => $depth, 'max_depth' => $args['max_depth']))) ?>
    </article>
  <?php // </li> is added by WordPress automatically ?>
<?php
} // don't remove this bracket!


/**
 *
 * Browser-sync script loader
 * to enable script/style injection
 *
 */

if( WP_DEBUG ) :

add_action( 'wp_head', function () { ?>
    <script type='text/javascript' id="__bs_script__">//<![CDATA[
    document.write("<script async src='http://HOST:3000/browser-sync/browser-sync-client.2.7.6.js'><\/script>".replace("briefstarter.local", location.hostname));
//]]></script>
<?php }, 999);

endif;




/* ==========================================================================
   Debug Function
   ==========================================================================
   Use it for printing variables and objects to the page for debug
   ========================================================================== */

if(!function_exists('pr')) :
  function pr($object){
    echo '<pre class="debug">';
      if($object == false)
        var_dump($object);
      else
        print_r($object);
    echo '</pre>';
  }
endif;


/* ==========================================================================
   Flush Rewrite Rules
   ==========================================================================
   This is needed when using custom post types with rewrite slugs
   ========================================================================== */

// Flush rewrite rules for custom post types
add_action( 'after_switch_theme', 'brief_flush_rewrite_rules' );

// Flush your rewrite rules
if(!function_exists('brief_flush_rewrite_rules')) :
  function brief_flush_rewrite_rules() {
    flush_rewrite_rules();
  }
endif;



/* ==========================================================================
   Disable Wordpress Toolbar on Frontend
   ========================================================================== */

//add_filter( 'show_admin_bar', '__return_false' );




/* ==========================================================================
   Remove Wordpress logo from Toolbar on Backoffice
   ========================================================================== */

if(!function_exists('annointed_admin_bar_remove')) :
  function annointed_admin_bar_remove() {
          global $wp_admin_bar;

          /* Remove their stuff */
          $wp_admin_bar->remove_menu('wp-logo');
  }
  add_action('wp_before_admin_bar_render', 'annointed_admin_bar_remove', 0);
endif;



/* ==========================================================================
   Change footer text on Backoffice
   ========================================================================== */

if(!function_exists('dashboard_footer')) :
  function dashboard_footer () {
    echo 'Developed by <a href="http://www.brief.pt" target="_blank">Brief Creatives</a>';
  }
  add_filter('admin_footer_text', 'dashboard_footer');
endif;




/* ==========================================================================
   Thumbnail upscale
   ==========================================================================
   When Wordpress creates the scaled copies of an image, it never enlarges it,
   but only make smaller copies: e.g. if the original uploaded image is 400x500 px, and the
   'medium' thumbnail size is 800x600 px, the medium size and/or larger are not created.
   This functions fixes this and always upscale the image to fit larger thumbnail sizes.
   That way your layout will never break, but remember that if an image is small and needs
   to be upscaled, it will always lose quality
   ========================================================================== */

if(!function_exists('alx_thumbnail_upscale')) :
  function alx_thumbnail_upscale( $default, $orig_w, $orig_h, $new_w, $new_h, $crop ){
      if ( !$crop ) return null; // let the wordpress default function handle this

      $aspect_ratio = $orig_w / $orig_h;
      $size_ratio = max($new_w / $orig_w, $new_h / $orig_h);

      $crop_w = round($new_w / $size_ratio);
      $crop_h = round($new_h / $size_ratio);

      $s_x = floor( ($orig_w - $crop_w) / 2 );
      $s_y = floor( ($orig_h - $crop_h) / 2 );

      return array( 0, 0, (int) $s_x, (int) $s_y, (int) $new_w, (int) $new_h, (int) $crop_w, (int) $crop_h );
  }
endif;
add_filter( 'image_resize_dimensions', 'alx_thumbnail_upscale', 10, 6 );



// Custom CSS for the login page
// Create wp-login.css in your theme folder
function wpfme_loginCSS() {
  echo '<link rel="stylesheet" type="text/css" href="'.get_bloginfo('template_directory').'assets/css/login.min.css"/>';
}
add_action('login_head', 'wpfme_loginCSS');




//create a permalink after the excerpt
function wpfme_replace_excerpt($content) {
  return str_replace('[...]',
    '<a class="readmore" href="'. get_permalink() .'">Continue Reading</a>',
    $content
  );
}
add_filter('the_excerpt', 'wpfme_replace_excerpt');


function wpfme_has_sidebar($classes) {
    if (is_active_sidebar('sidebar')) {
        // add 'class-name' to the $classes array
        $classes[] = 'has_sidebar';
    }
    // return the $classes array
    return $classes;
}
add_filter('body_class','wpfme_has_sidebar');


// Stop images getting wrapped up in p tags when they get dumped out with the_content() for easier theme styling
function wpfme_remove_img_ptags($content){
  return preg_replace('/<p>\s*(<a .*>)?\s*(<img .* \/>)\s*(\/a>)?\s*<\/p>/iU', '\1\2\3', $content);
}
add_filter('the_content', 'wpfme_remove_img_ptags');


// Remove the version number of WP
// Warning - this info is also available in the readme.html file in your root directory - delete this file!
remove_action('wp_head', 'wp_generator');


// Obscure login screen error messages
function wpfme_login_obscure(){ return '<strong>Sorry</strong>: Think you have gone wrong somwhere!';}
add_filter( 'login_errors', 'wpfme_login_obscure' );


// Disable the theme / plugin text editor in Admin
define('DISALLOW_FILE_EDIT', true);

/* ==========================================================================
   CHANGE UPLOAD LIMIT
   ========================================================================== */

add_filter( 'upload_size_limit', 'brief_increase_upload' );

function brief_increase_upload( $bytes )
{
    return 33554432; // 32 megabytes
}




/**
 * Add theme support for infinite scroll.
 *
 * @uses add_theme_support
 * @return void
 */
function ip_infinite_scroll_init() {
    add_theme_support( 'infinite-scroll', array(
        'footer'    => 'footer',
        'type'           => 'scroll',
        'footer_widgets' => false,
        'container'      => 'content-grid',
        'wrapper'        => false,
        // 'render'         => 'show_work_item',
        'posts_per_page' => -1
    ) );
}
add_action( 'after_setup_theme', 'ip_infinite_scroll_init' );


function show_work_item(){
  while( have_posts() ) {
    the_post();
    get_template_part('post-formats/item','portfolio');
  }
}



/* DON'T DELETE THIS CLOSING TAG */ ?>
