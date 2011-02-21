<div class="post-list">
<?php /* If there are no posts to display, such as an empty archive page */ ?>
<?php if ( ! have_posts() ) : ?>
	<div>No posts to display.</div>
<?php endif; ?>

<?php while ( have_posts() ) : the_post(); ?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
<h1><a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'twentyten' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark"><?php the_title(); ?></a></h1>

<div class="entry-summary">
    <?php the_excerpt(); ?>
</div><!-- .entry-summary -->
</article> <!-- #post-<?php the_ID(); ?> -->

<?php endwhile; // End the loop. Whew. ?>
</div>