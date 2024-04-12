<?php
/**
 * The Template for displaying all block single posts.
 *
 * @package cartify
 */

get_header(); ?>

<div id="primary" class="content-area page-slider">
    <main id="main" class="site-main">        
    	<?php while ( have_posts() ) : the_post(); ?>
            <?php the_content();  ?>
		<?php endwhile; // end of the loop. ?>
    </main>
</div>

<?php get_footer(); ?>