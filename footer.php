			<div class="footer-navigation">
				<div class="wrap cf">
					<nav role="navigation">
						<?php wp_nav_menu(array(
    					'container' => 'div',                           // enter '' to remove nav container (just make sure .footer-links in _base.scss isn't wrapping)
    					'container_class' => 'footer-links cf',         // class of container (should you choose to use it)
    					'menu' => __( 'Footer Links', 'bonestheme' ),   // nav name
    					'menu_class' => 'nav footer-nav cf',            // adding custom nav class
    					'theme_location' => 'footer-links',             // where it's located in the theme
    					'before' => '',                                 // before the menu
    					'after' => '',                                  // after the menu
    					'link_before' => '',                            // before each link
    					'link_after' => '',                             // after each link
    					'depth' => 0,                                   // limit the depth of the nav
    					'fallback_cb' => 'bones_footer_links_fallback'  // fallback function
						)); ?>
					</nav>
				</div>
			</div>
			<?php
			$copyright_year = get_theme_mod('bones_frontend_copyright_year');
			$copyright_text = get_theme_mod('bones_frontend_copyright_text');
			
			$copyright = '';
			if ($copyright_year) {
				$copyright .= '&copy; ' . date('Y');
			}
			if (strlen($copyright_text)) {
				if ($copyright_year) {
					$copyright .= '&nbsp;';
				}
				$copyright .= $copyright_text;
			}
			?>
			<?php if (strlen($copyright)): ?>
			<footer class="footer" role="contentinfo" itemscope itemtype="http://schema.org/WPFooter">
				<div id="inner-footer" class="wrap cf">
					<p class="source-org copyright">
						<?php echo $copyright; ?>
					</p>
				</div>
			</footer>
			<?php endif; ?>
		</div>
		<?php // all js scripts are loaded in library/bones.php ?>
		<?php wp_footer(); ?>
	</body>
</html>
