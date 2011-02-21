<?php get_header(); ?>

<div id="content" role="main">

<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
<header class="entry-title">
<?php
if (has_post_thumbnail()) {
?>
<figure><?php the_post_thumbnail('post-thumbnail', array('alt' => '', 'title' => '')); ?></figure>
<?php
}
?>
<hgroup>
<h1><?php the_title(); ?></h1>
<?php
$author = bw_get_author();
if ($author) {
?>
<h2>by <?php echo bw_get_author(); ?></h2>
<?php
}
?>
</hgroup>

<div class="excerpt"><?php echo get_post_meta($post->ID, 'excerpt', true); ?></div>
</header>

<div class="entry-content">
<?php the_content(); ?>

<footer>
<?php wp_link_pages( array( 'before' => '<div class="page-link">' . __( 'Pages:', 'twentyten' ), 'after' => '</div>' ) ); ?>
<?php edit_post_link( __( 'Edit', 'twentyten' ), '<span class="edit-link">', '</span>' ); ?>
</footer>
</div> <!-- .entry-content -->
</article> <!-- #post-<?php the_ID(); ?> -->

<?php comments_template( '', true ); ?>

<?php endwhile; ?>

</div><!-- #content -->

<?php get_footer(); ?>
