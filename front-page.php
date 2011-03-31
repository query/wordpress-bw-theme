<?php
/**
 * Template Name: Front Page
 */

wp_register_script('jcarousel', get_bloginfo('stylesheet_directory') .
                                '/js/jquery.jcarousel.min.js',
                   array('jquery'), '0.2.7');
wp_register_script('home_carousel', get_bloginfo('stylesheet_directory') .
                                    '/js/home_carousel.js',
                   array('jcarousel'));

wp_enqueue_script('jquery');
wp_enqueue_script('jcarousel');
wp_enqueue_script('home_carousel');

get_header();
?>

<div id="content" role="main">
<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
<?php the_content(); ?>
</article> <!-- #post-<?php the_ID(); ?> -->
<?php endwhile; ?>

<?php
$options = get_option('bw_theme_options');
$featured_pages = array();

switch ($options['featured']) {
    case 'root':
        $issues = new WP_Query(array('posts_per_page' => 1,
                                     'orderby' => 'menu_order', 'order' => 'ASC',
                                     'post_type' => 'page',
                                     'post_parent' => $options['featured_root']));
        
        if ($issues->have_posts()) {
            $issues->the_post();
            $issue = new WP_Query(array('posts_per_page' => -1,
                                        'orderby' => 'menu_order', 'order' => 'ASC',
                                        'post_type' => 'page',
                                        'post_parent' => $post->ID));
            
            while ($issue->have_posts()) {
                $issue->the_post();
                $featured_pages[] = $post->ID;
            }
        }
    break;
    
    case 'pages':
        $featured_pages = $options['featured_pages'];
    break;
}

if (count($featured_pages)) {
?>
<div id="home-primary">
<section id="home-featured">
<h1><?php esc_html_e($options['featured_heading']); ?></h1>

<ul class="carousel jcarousel-skin-none">
<?php
    foreach ($featured_pages as $featured_page) {
        $featured_query = new WP_Query(array('page_id' => $featured_page));

        if ($featured_query->have_posts()) {
            $featured_query->the_post();
?>
<li>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
<a href="<?php the_permalink(); ?>" rel="bookmark">
<figure>
<?php the_post_thumbnail('post-thumbnail', array('alt' => '', 'title' => '')); ?>
</figure>
<h1><?php the_title(); ?></h1>
<div class="excerpt"><?php echo get_post_meta($post->ID, 'excerpt', true); ?></div>
</a>
</article>
</li>
<?php
        }
    }

    wp_reset_postdata();
?>
</ul>
</section> <!-- #home-featured -->
</div> <!-- #home-primary -->
<?php
}

wp_reset_query();
?>

<div id="home-secondary">
<section id="home-blog">
<h1><?php _e('From the Blog'); ?></h1>

<?php

$bw_home = true;
query_posts('post_type=post');
get_template_part( 'loop', 'home' );
wp_reset_query();

?>
</section>

<section id="home-widgets">
<?php dynamic_sidebar( 'home-widget-area' ); ?>
</section>
</div> <!-- #home-secondary -->
</div> <!-- #content -->

<?php get_footer(); ?>
