<?php
if (is_page() && !is_front_page()):
?>
<nav id="breadcrumb">
<ul>
<li itemscope itemtype="http://data-vocabulary.org/Breadcrumb">
    <a href="<?php echo home_url( '/' ); ?>" itemprop="url"><?php bloginfo('name'); ?></a>
</li>

<?php
$ancestors = $post->ancestors;
array_unshift($ancestors, $post->ID);

for ($i = count($ancestors) - 1; $i >= 0; $i--) {
    $ancestor_page = new WP_Query(array('page_id' => $ancestors[$i]));
    $ancestor_page->the_post();
?>
<li itemscope itemtype="http://data-vocabulary.org/Breadcrumb">
    <a<?php if ($i > 0) { ?> href="<?php the_permalink(); ?>" itemprop="url"<?php } ?>><?php the_title(); ?></a>
</li>
<?php
}
?>
</ul>
</nav>
<?php
endif; // breadcrumb
?>
</div> <!-- #content -->

<footer role="contentinfo">
<div id="copyright">
© 2007&ndash;<?php echo date('Y'); ?>

<a href="<?php echo home_url( '/' ) ?>" rel="home">
<?php bloginfo( 'name' ); ?>
</a>.
Original design by <a href="http://www.amandamcphersondesign.com/">Amanda McPherson</a>.

<?php
printf( __('Proudly powered by %s.', 'twentyten'),
    '<a href="' . esc_url( __('http://wordpress.org/', 'twentyten') ) . '" ' .
    'title="' . esc_attr('Semantic Personal Publishing Platform', 'twentyten') . '" rel="generator">' .
    'WordPress</a>'
);
?>
</a>
</div>

<ul id="meta">
<?php wp_register(); ?>
<li><?php wp_loginout(); ?></li>
<?php wp_meta(); ?>
</ul>
</footer>
</div> <!-- #wrapper -->
<?php wp_footer(); ?>
</body>
</html>
