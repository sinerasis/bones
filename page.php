<?php

/*
This is our default page template. It's setup to be much more like a generic informational page instead of a blog-type page.
*/

get_header();

?>

<div id="content">
	<div id="inner-content" class="cf">
		<div class="row wrap">
			<main id="main" class="m-span-12 t-span-12 d-span-12 cf" role="main" itemscope itemprop="mainContentOfPage" itemtype="http://schema.org/Blog">
				<?php if (have_posts()): ?>
				<?php while (have_posts()): ?>
				<?php the_post(); ?>
				
				<article id="post-<?php the_ID(); ?>" <?php post_class( 'cf' ); ?> role="article" itemscope itemtype="http://schema.org/BlogPosting">
					<header class="article-header">
						<h1 class="page-title" itemprop="headline"><?php the_title(); ?></h1>
					</header>
					<section class="entry-content cf" itemprop="articleBody">
						<?php the_content(); ?>
					</section>
					<footer class="article-footer cf">
					</footer>
				</article>
				
				<?php endwhile; ?>
				<?php endif; ?>
			</main>
		</div>
	</div>
</div>

<?php get_footer(); ?>
