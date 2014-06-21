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
				$part = 'content';

				if ( meetup_is_speaker_page() ) {
					$part = 'speakers';

				} elseif ( meetup_is_sponsor_page() ) {
					$part = 'sponsors';

				} elseif ( meetup_is_schedule_page() ) {
					$part = 'schedule';

				} elseif ( meetup_is_attendies_page() ) {
					$part = 'attendies';

				} elseif ( meetup_is_gallery_page() ) {
					$part = 'gallery';
				}

				$part = apply_filters( 'meetup_single_template_part', $part );

				meetup_get_template_part( 'meetup', $part );
				?>

			<?php endwhile; // end of the loop. ?>

		</div><!-- #content -->
	</div><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>