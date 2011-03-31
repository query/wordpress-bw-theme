<?php
/**
 * Template Name: Issue
 */

get_header();
?>

<div id="content" role="main">

<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>
<h1 class="entry-title"><?php the_title(); ?></h1>

<div class="entry-content">
<?php the_content(); ?>

<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
<dl>
<?php
$issue = new WP_Query(array('posts_per_page' => -1,
                            'orderby' => 'menu_order', 'order' => 'ASC',
                            'post_type' => 'page',
                            'post_parent' => $post->ID));

while ($issue->have_posts()) {
    $issue->the_post();
?>
<dt>
<a href="<?php echo the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'twentyten' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark"><?php the_title(); ?></a>
by <?php echo bw_get_author(); ?>
</dt>
<dd><?php echo get_post_meta($post->ID, 'excerpt', true); ?></dd>
<?php
}

wp_reset_postdata();
?>
</dl>

<footer>
<?php wp_link_pages( array( 'before' => '<div class="page-link">' . __( 'Pages:', 'twentyten' ), 'after' => '</div>' ) ); ?>
<?php edit_post_link( __( 'Edit', 'twentyten' ), '<span class="edit-link">', '</span>' ); ?>
</footer>
</div> <!-- .entry-content -->
</div> <!-- #post-<?php the_ID(); ?> -->

<?php comments_template( '', true ); ?>

<?php endwhile; ?>

</div><!-- #content -->

<?php get_footer(); ?>
