<aside id="sidebar">
<section id="primary" class="widget-area" role="complementary">
<ul class="xoxo">
<?php if ( ! dynamic_sidebar( 'primary-widget-area' ) ) : ?>
	
<li id="search" class="widget-container widget_search">
<?php get_search_form(); ?>
</li>

<li id="archives" class="widget-container">
<h1><?php _e( 'Archives', 'twentyten' ); ?></h1>
<ul>
<?php wp_get_archives( 'type=monthly' ); ?>
</ul>
</li>

<?php endif; // end primary widget area ?>
</ul>
</section> <!-- #primary .widget-area -->

<?php if ( is_active_sidebar( 'secondary-widget-area' ) ) : ?>

<section id="secondary" class="widget-area" role="complementary">
<ul class="xoxo">
<?php dynamic_sidebar( 'secondary-widget-area' ); ?>
</ul>
</section> <!-- #secondary .widget-area -->

<?php endif; ?>

</aside>
