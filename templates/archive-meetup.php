<?php
/**
 * You can override this template
 *
 * If you would like to edit this file, copy it to your current theme's directory in "meetup" folder and edit it there.
 * Meetup will always look in your theme's directory first, before using this default template.
 *
 * @package Meetup
 */

get_header(); ?>

	<section id="primary" class="site-content">
		<div id="content" role="main">

		<?php if ( have_posts() ) : ?>
			<header class="archive-header">
				<h1 class="archive-title"><?php _e( 'Meetups', 'meetup' ); ?></h1>
			</header><!-- .archive-header -->

			<?php
			/* Start the Loop */
			while ( have_posts() ) : the_post();
				?>
				<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

    				<?php meetup_get_template_part( 'meetup', 'header' ); ?>

    				<div class="entry-content">
    					<?php the_content(); ?>
    				</div>

    			</article>
				<?php
			endwhile;

			meetup_content_nav( 'nav-below' );
			?>

		<?php else : ?>
			<?php get_template_part( 'content', 'none' ); ?>
		<?php endif; ?>

		</div><!-- #content -->
	</section><!-- #primary -->

<?php get_sidebar( 'meetup' ); ?>
<?php get_footer(); ?>