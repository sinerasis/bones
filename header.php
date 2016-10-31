<?php
// check for a go live date
$golive = get_theme_mod('bones_frontend_launch_golive');
if (strlen($golive)) {
	// make sure we use our wordpress installations timezone setting!
	date_default_timezone_set(get_option('timezone_string'));
	
	// get our ip whitelist
	$golive_whitelist = get_theme_mod('bones_frontend_launch_ip_whitelist');
	if (strlen($golive_whitelist)) {
		$golive_whitelist = explode(",", $golive_whitelist);
	}
	
	if (!in_array($_SERVER['REMOTE_ADDR'], $golive_whitelist)) {
		// output pre go live text
		$golive_text = get_theme_mod('bones_frontend_launch_golive_text');
		$golive = strtotime($golive);
		if (time() < $golive) {
			die($golive_text);
		}
	}
}
?>

<!doctype html>

<!--[if lt IE 7]><html <?php language_attributes(); ?> class="no-js lt-ie9 lt-ie8 lt-ie7"><![endif]-->
<!--[if (IE 7)&!(IEMobile)]><html <?php language_attributes(); ?> class="no-js lt-ie9 lt-ie8"><![endif]-->
<!--[if (IE 8)&!(IEMobile)]><html <?php language_attributes(); ?> class="no-js lt-ie9"><![endif]-->
<!--[if gt IE 8]><!--> <html <?php language_attributes(); ?> class="no-js"><!--<![endif]-->

	<head>
		<meta charset="utf-8">

		<?php // force Internet Explorer to use the latest rendering engine available ?>
		<meta http-equiv="X-UA-Compatible" content="IE=edge">

		<title><?php wp_title(''); ?></title>

		<?php // mobile meta (hooray!) ?>
		<meta name="HandheldFriendly" content="True">
		<meta name="MobileOptimized" content="320">
		<meta name="viewport" content="width=device-width, initial-scale=1"/>

		<?php // icons & favicons (for more: http://www.jonathantneal.com/blog/understand-the-favicon/) ?>
		<link rel="apple-touch-icon" href="<?php echo get_template_directory_uri(); ?>/library/images/apple-touch-icon.png">
		<link rel="icon" href="<?php echo get_template_directory_uri(); ?>/favicon.png">
		<!--[if IE]>
			<link rel="shortcut icon" href="<?php echo get_template_directory_uri(); ?>/favicon.ico">
		<![endif]-->
		<?php // or, set /favicon.ico for IE10 win ?>
		<meta name="msapplication-TileColor" content="#f01d4f">
		<meta name="msapplication-TileImage" content="<?php echo get_template_directory_uri(); ?>/library/images/win8-tile-icon.png">
            <meta name="theme-color" content="#121212">

		<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>">

		<?php // wordpress head functions ?>
		<?php wp_head(); ?>
		<?php // end of wordpress head ?>
		
		<?php
		// customizer styles
		$bones_frontend_colors = get_theme_mod('bones_frontend_colors', false);
		if ($bones_frontend_colors !== false) {
			echo '<style type="text/css">';
			foreach ($bones_frontend_colors as $selector => $properties) { 
				echo $selector . ' {';
				foreach ($properties as $property => $value) {
					echo $property . ': ' . $value . ';';
				}
				echo '}';
				
				// menus are special because we want sub-menu background colors to match
				if (in_array($selector, Array('.main-navigation', '.footer-navigation')) && $property == 'background-color') {
					echo $selector . ' ul {';
					echo $property . ': ' . $value . ';';
					echo '}';
				}
			}
			echo '</style>';
		}
		?>
		
		<?php $ga_tracking_id = get_theme_mod('bones_admin_tracking_google_analytics'); ?>
		<?php if(strlen($ga_tracking_id) > 0): ?>
		<!-- Google Analytics -->
		<script type="text/javascript">
			var _gaq = _gaq || [];
			_gaq.push(['_setAccount', '<?php echo $ga_tracking_id; ?>']);
			_gaq.push(['_trackPageview']);

			(function() {
			var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
			ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
			var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
			})();
		</script>
		<?php endif; ?>

	</head>

	<body <?php body_class(); ?> itemscope itemtype="http://schema.org/WebPage">

		<div id="container">

			<header class="header" role="banner" itemscope itemtype="http://schema.org/WPHeader">

				<div id="inner-header" class="wrap cf">

					<?php // to use a image just replace the bloginfo('name') with your img src and remove the surrounding <p> ?>
					<p id="logo" class="h1" itemscope itemtype="http://schema.org/Organization"><a href="<?php echo home_url(); ?>"><?php bloginfo('name'); ?></a></p>

					<?php // if you'd like to use the site description you can un-comment it below ?>
					<?php // bloginfo('description'); ?>
				</div>
			</header>
			
			<div class="main-navigation">
				<div class="wrap cf">
					<div class="mobile-burger">
						<div>
							<div class="burger">
								<span></span>
								<span></span>
								<span></span>
							</div>
							<?php
							$burger_text = get_theme_mod('bones_frontend_burger_text');
							if (strlen($burger_text)) {
								echo $burger_text;
							}
							?>
							
						</div>
					</div>
					<nav role="navigation" itemscope itemtype="http://schema.org/SiteNavigationElement">
						<?php wp_nav_menu(array(
    					         'container' => false,                           // remove nav container
    					         'container_class' => 'menu cf',                 // class of container (should you choose to use it)
    					         'menu' => __( 'The Main Menu', 'bonestheme' ),  // nav name
    					         'menu_class' => 'nav top-nav cf',               // adding custom nav class
    					         'theme_location' => 'main-nav',                 // where it's located in the theme
    					         'before' => '',                                 // before the menu
        			               'after' => '',                                  // after the menu
        			               'link_before' => '',                            // before each link
        			               'link_after' => '',                             // after each link
        			               'depth' => 0,                                   // limit the depth of the nav
    					         'fallback_cb' => ''                             // fallback function (if there is one)
						)); ?>
					</nav>
				</div>
			</div>
