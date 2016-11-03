<?php
/*
Author: Eddie Machado
URL: http://themble.com/bones/

This is where you can drop your custom functions or
just edit things like thumbnail sizes, header images,
sidebars, comments, etc.
*/

//Remove hentry class everywhere to get rid of structured data errors
function remove_hentry( $classes ) {
    $classes = array_diff( $classes, array( 'hentry' ) );
    return $classes;
}
add_filter( 'post_class','remove_hentry' );

// LOAD BONES CORE (if you remove this, the theme will break)
require_once( 'library/bones.php' );

// CUSTOMIZE THE WORDPRESS ADMIN (off by default)
// require_once( 'library/admin.php' );

/*********************
LAUNCH BONES
Let's get everything up and running.
*********************/

function bones_ahoy() {

  //Allow editor style.
  add_editor_style( get_stylesheet_directory_uri() . '/library/css/editor-style.css' );

  // let's get language support going, if you need it
  load_theme_textdomain( 'bonestheme', get_template_directory() . '/library/translation' );

  // USE THIS TEMPLATE TO CREATE CUSTOM POST TYPES EASILY
  require_once( 'library/custom-post-type.php' );

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
add_image_size( 'bones-thumb-600', 600, 150, true );
add_image_size( 'bones-thumb-300', 300, 100, true );

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
        'bones-thumb-600' => __('600px by 150px'),
        'bones-thumb-300' => __('300px by 100px'),
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
  // We're removing the default "colors"" section because we're setting these options in the "color picker" sections below.
  $wp_customize->remove_section('colors');
  // $wp_customize->remove_section('background_image');
  // $wp_customize->remove_section('static_front_page');
  // $wp_customize->remove_section('nav');

  // Uncomment the below lines to remove the default controls
  // $wp_customize->remove_control('blogdescription');
  
  // Uncomment the following to change the default section titles
  // $wp_customize->get_section('colors')->title = __( 'Theme Colors' );
  // $wp_customize->get_section('background_image')->title = __( 'Images' );
  
  /* COLOR PICKERS */
  $color_pickers = Array(
    'Header' => Array(
      // header
      'bones_frontend_colors[.header][background-color]' => 'Header Background',
      'bones_frontend_colors[.header a, .header a:visited][color]' => 'Header Links',
      'bones_frontend_colors[.header a:hover, .header a:focus, .header a:visted:hover, .header a:focus:hover][color]' => 'Header Links (hover)',
      
      // header navigation
      'bones_frontend_colors[.main-navigation][background-color]' => 'Main Nav Background',
      'bones_frontend_colors[.main-navigation a, .main-navigation a:visited][color]' => 'Main Nav Links',
      'bones_frontend_colors[.main-navigation a:hover, .main-navigation a:focus, .main-navigation a:visited:hover, .main-navigation a:focus:hover][color]' => 'Main Nav Links (hover)',
    ),
    'Body' => Array(
      // body
      'bones_frontend_colors[body][background-color]' => 'Body Background',
      'bones_frontend_colors[a, a:visited][color]' => 'Links',
      'bones_frontend_colors[a:hover, a:focus, a:visited:hover, a:focus:hover][color]' => 'Links (hover)',
    ),
    'Footer' => Array(
      // footer navigation
      'bones_frontend_colors[.footer-navigation][background-color]' => 'Footer Nav Background',
      'bones_frontend_colors[.footer-navigation a, .footer-navigation a:visited][color]' => 'Footer Nav Links',
      'bones_frontend_colors[.footer-navigation a:hover, .footer-navigation a:focus, .footer-navigation a:visited:hover, .footer-navigation a:focus:hover][color]' => 'Footer Nav Links (hover)',
      
      // footer
      'bones_frontend_colors[.footer][background-color]' => 'Footer Background',
      'bones_frontend_colors[.footer a, .footer a:visited][color]' => 'Footer Links',
      'bones_frontend_colors[.footer a:hover, .footer a:focus, .footer a:visited:hover, .footer a:focus:hover][color]' => 'Footer Links (hover)',
    ),
  );
  // place our sections directly after the "Site Identity" section
  $priority = 41;
	foreach ($color_pickers as $section => $pickers) {
		// add sections
		$section_id = 'bones_frontend_' . strtolower($section);
		$wp_customize->add_section($section_id, Array(
			'title' => $section,
      // priority is simply incremented in the order of our array above
			'priority' => $priority++,
		));
		// add controls to sections
		foreach ($pickers as $identifier => $label) {
			$wp_customize->add_setting($identifier, Array(
				'default' => '',
				'transport' => 'refresh',
			));
			$wp_customize->add_control(
				new WP_Customize_Color_Control(
					$wp_customize,
					$identifier,
					array(
						'label' => __($label, 'sinBones'),
						'section' => $section_id,
						'setting' => $identifier,
					)
				)
			);
		}
	}
  /* BURGER MENU TEXT */
  $wp_customize->add_setting('bones_frontend_burger_text', Array(
    'default' => 'Menu',
    'transport' => 'refresh',
  ));
  $wp_customize->add_control('bones_frontend_burger_text', Array(
    'label' => 'Hamburger Menu Text',
    'type' => 'text',
    'section' => 'bones_frontend_header',
  ));
  /* FOOTER COPYRIGHT SYMBOL/YEAR */
  $wp_customize->add_setting('bones_frontend_copyright_year', Array(
    'default' => true,
    'transport' => 'refresh',
  ));
  $wp_customize->add_control('bones_frontend_copyright_year', Array(
    'label' => 'Show &copy; ' . date('Y') . ' in Footer?',
    'type' => 'checkbox',
    'section' => 'bones_frontend_footer',
  ));
  /* FOOTER COPYRIGHT TEXT */
  $wp_customize->add_setting('bones_frontend_copyright_text', Array(
    'default' => get_bloginfo('name') . '. All rights reserved.',
    'transport' => 'refresh',
  ));
  $wp_customize->add_control('bones_frontend_copyright_text', Array(
    'label' => 'Copyright Text',
    'type' => 'text',
    'section' => 'bones_frontend_footer',
  ));
  /* ADMIN IMAGE SECTION */
  $wp_customize->add_section('bones_admin_image', Array(
    'title' => 'Admin Image',
    'priority' => 1000
  ));
  /* CUSTOM ADMIN IMAGE */
  $wp_customize->add_setting('bones_admin_image_uri', Array(
    'default' => '',
    'transport' => 'postMessage'
  ));
  $wp_customize->add_control(
    new WP_Customize_Image_Control(
      $wp_customize,
      'bones_admin_image',
      array(
        'label' => __('Admin Page Image', 'bones'),
        'section' => 'bones_admin_image',
        'settings' => 'bones_admin_image_uri',
        'context' => 'your_setting_context'
      )
    )
  );
  /* CUSTOM ADMIN IMAGE LINK */
  $wp_customize->add_setting('bones_admin_image_link', Array(
    'default' => '/',
    'transport' => 'postMessage'
  ));
  $wp_customize->add_control('bones_admin_image_link', Array(
    'label' => 'Link URL',
    'description' => 'Default: /',
    'type' => 'text',
    'section' => 'bones_admin_image',
  ));
  /* CUSTOM ADMIN IMAGE TOOLTIP */
  $wp_customize->add_setting('bones_admin_image_tooltip', Array(
    'default' => get_bloginfo("name"),
    'transport' => 'postMessage'
  ));
  $wp_customize->add_control('bones_admin_image_tooltip', Array(
    'label' => 'Tooltip Text',
    'description' => 'Default: blog/site title',
    'type' => 'text',
    'section' => 'bones_admin_image',
  ));
  
  /* GOOGLE ANALYTICS SECTION */
  $wp_customize->add_section('bones_admin_tracking', Array(
    'title' => 'Tracking',
    'priority' => 1001,
  ));
  /* GOOGLE ANALYTICS ID STRING */
  $wp_customize->add_setting('bones_admin_tracking_google_analytics', Array(
    'default' => '',
    'transport' => 'postMessage',
  ));
  $wp_customize->add_control('bones_admin_tracking_google_analytics', Array(
    'label' => 'Google Analytics ID',
    'description' => 'Example: UA-XXXXX-X',
    'type' => 'text',
    'section' => 'bones_admin_tracking',
  ));
  /* LAUNCH SETTINGS */ 
  $wp_customize->add_section('bones_frontend_launch', Array(
	  'title' => 'Launch Settings',
	  'priority' => 21,
  ));
  $wp_customize->add_setting('bones_frontend_launch_golive', Array(
	  'default' => '',
	  'transport' => 'refresh',
  ));
  $wp_customize->add_control('bones_frontend_launch_golive', Array(
	  'label' => 'Go Live Date',
	  'description' => 'Make note of your servers time zone setting!',
	  'type' => 'date',
	  'section' => 'bones_frontend_launch',
  ));
  $wp_customize->add_setting('bones_frontend_launch_golive_text', Array(
	  'default' => 'Coming soon!',
	  'transport' => 'refresh',
  ));
  $wp_customize->add_control('bones_frontend_launch_golive_text', Array(
	  'label' => '(Pre) Go Live Text',
	  'description' => 'This text will be output if the site is requested before the go live date defined above.',
	  'type' => 'text',
	  'section' => 'bones_frontend_launch',
  ));
  $wp_customize->add_setting('bones_frontend_launch_ip_whitelist', Array(
	  'default' => '',
	  'transport' => 'refresh',
  ));
  $wp_customize->add_control('bones_frontend_launch_ip_whitelist', Array(
	  'label' => 'IP Whitelist',
	  'description' => 'These IP(s) will view the site normally even if the go live date is in the future. Enter a comma separated list.',
	  'type' => 'textarea',
	  'section' => 'bones_frontend_launch',
  ));
  /* FONTS */
  $wp_customize->add_section('bones_frontend_font', Array(
	  'title' => 'Fonts',
	  'priority' => 30,
  ));
  for ($i = 1; $i <= 3; $i++) {
	  $wp_customize->add_setting('bones_frontend_font_uris[' . $i . ']', Array(
		  'default' => '',
		  'transport' => 'refresh',
	  ));
	  $wp_customize->add_control('bones_frontend_font_uris[' . $i . ']', Array(
		  'label' => 'Stylesheet URI',
		  'description' => 'Appropriate protocol will be replaced at time of request (http/https).',
		  'type' => 'text',
		  'section' => 'bones_frontend_font',
	  ));
  }
}

add_action('customize_register', 'bones_theme_customizer');

/************* ACTIVE SIDEBARS ********************/

// Sidebars & Widgetizes Areas
function bones_register_sidebars() {
	register_sidebar(array(
		'id' => 'sidebar1',
		'name' => __( 'Sidebar 1', 'bonestheme' ),
		'description' => __( 'The first (primary) sidebar.', 'bonestheme' ),
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
		'name' => __( 'Sidebar 2', 'bonestheme' ),
		'description' => __( 'The second (secondary) sidebar.', 'bonestheme' ),
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
        <?php printf(__( '<cite class="fn">%1$s</cite> %2$s', 'bonestheme' ), get_comment_author_link(), edit_comment_link(__( '(Edit)', 'bonestheme' ),'  ','') ) ?>
        <time datetime="<?php echo comment_time('Y-m-j'); ?>"><a href="<?php echo htmlspecialchars( get_comment_link( $comment->comment_ID ) ) ?>"><?php comment_time(__( 'F jS, Y', 'bonestheme' )); ?> </a></time>

      </header>
      <?php if ($comment->comment_approved == '0') : ?>
        <div class="alert alert-info">
          <p><?php _e( 'Your comment is awaiting moderation.', 'bonestheme' ) ?></p>
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


/*
This is a modification of a function found in the
twentythirteen theme where we can declare some
external fonts. If you're using Google Fonts, you
can replace these fonts, change it in your scss files
and be up and running in seconds.
*/
function bones_fonts() {
	// If you want to add additional fonts to style text generically, you can add them here:
	// This should be a simple array of URL's to font stylesheets. Keep in mind each entry is an additional HTTP request on page load.
	// If you have more than a couple, you should probably combine them and host locally.
	// Protocol (http/https) will be stripped and prefixed appropriatly at request time.
	$font_enqueue = Array(
		// 'http://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css',
		
	);
	
	// Determine protocol
	$protocol = 'http://';
	if (is_ssl()) {
		$protocol = 'https://';
	}
	
	// Fonts from the customizer, should probably just stick to icon type fonts (like Font Awesome, Material Icons, etc)
	$bones_fonts = get_theme_mod('bones_frontend_font_uris');
	
	// Merge font arrays. Ideally keys are unique here, but it's not really a big deal for our use. WordPress likes it though.
	$fonts = array_merge($bone_fonts, $font_enqueue);
	
	foreach ($fonts as $handle => $source) {
		if (strlen($source)) {
			// removes protocol and appends the protocal we've deteremined previously
			$source = $protocol . preg_replace('#^https?://#', '', $source);
			
			wp_enqueue_style($handle, $source);
		}
	}
}

add_action('wp_enqueue_scripts', 'bones_fonts');

/*
ADMIN LOGIN LOGO
Changes the admin login image to libraray/images/login-logo.png
This will get the image dimensions automatically, so we just need to replace the image and go instead of deal with code edits.
No more plugin for this simple task!
*/
function my_login_logo() {
    // get the image uri defined in theme customizer
    $file = get_theme_mod('bones_admin_image_uri');
    
    // if no image is defined, use the default bones image
    if(strlen($file) < 1) {
      $file = get_stylesheet_directory_uri() . '/library/images/login-logo.png';
    }
    
    // get dimensions of the image
    $dimensions = getimagesize($file);
    
    // output appropriate css
    echo '
    <style type="text/css">
        #login h1 a, .login h1 a {
            background-image: url(' . $file . ');
            background-size: 100%;
            width: ' . $dimensions[0] . 'px;
            height: ' . $dimensions[1] . 'px;
        }
    </style>';
}
add_action( 'login_enqueue_scripts', 'my_login_logo' );

/*
ADMIN LOGO LINK
Changes where the admin logo image links to.
We're changing this to simply direct the user to the document root.
*/
function loginpage_custom_link() {
  // get url defined in theme customizer
  $url = get_theme_mod('bones_admin_image_link');
  
  // if there is a url defined, return it
  if(strlen($url) > 0) {
    return $url;
  }
  
  // as a fallback, simply use the sites root/homepage
  return "/";
}
add_filter('login_headerurl', 'loginpage_custom_link');

/*
ADMIN LOGO TOOLTIP
Changes the text displayed in tooltip for the admin logo image.
We're changing it to simply display the name of our blog/site.
*/
function change_title_on_logo() {
  // get tooltip defined in theme customizer
  $tooltip = get_theme_mod('bones_admin_image_tooltip');
  
  // if there is a tooltip defined, return it
  if(strlen($tooltip) > 0) {
    return $tooltip;
  }
  
  // as a fallback, use the blog/site name
  return get_bloginfo("name");
}
add_filter('login_headertitle', 'change_title_on_logo');

/* DON'T DELETE THIS CLOSING TAG */ ?>
