<?php
/**
 * The Template for displaying all single posts
 *
 * @package WordPress
 * @subpackage Twenty_Twelve
 * @since Twenty Twelve 1.0
 */

get_header(); ?>

	<div id="primary" class="site-content">
		<div id="content" role="main">

			<?php while ( have_posts() ) : the_post(); ?>

				<?php
				if ( meetup_is_speaker_page() ) {

					meetup_get_template_part( 'meetup', 'speakers' );

				} elseif ( meetup_is_sponsor_page() ) {

					meetup_get_template_part( 'meetup', 'sponsors' );

				} elseif ( meetup_is_attendies_page() ) {

					meetup_get_template_part( 'meetup', 'attendies' );

				} elseif ( meetup_is_gallery_page() ) {

					meetup_get_template_part( 'meetup', 'gallery' );

				} else {
					meetup_get_template_part( 'meetup', 'content' );
				}
				?>

			<?php endwhile; // end of the loop. ?>

		</div><!-- #content -->
	</div><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>