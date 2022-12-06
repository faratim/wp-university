<?php

function pageBanner() {
    // php logic will live here

    ?>
    <div class="page-banner">
        <div 
            class="page-banner__bg-image" 
            style="background-image: 
                url(<?php $pageBannerImage = get_field('page_banner_background_image'); echo $pageBannerImage['sizes']['pageBanner'] ?>)">
        </div>
        <div class="page-banner__content container container--narrow">
            <h1 class="page-banner__title">
                <?php the_title(); ?>
            </h1>
            <div class="page-banner__intro">
                <p>
                    <?php the_field('page_banner_subtitle') ?>
                </p>
            </div>
        </div>
    </div>
    <?php
}

function university_files() {
    wp_enqueue_script('main-university-javascript', get_theme_file_uri('/build/index.js'), array('jquery'), '1.0', true);
    wp_enqueue_style('custom-google-fonts', '//fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i|Roboto:100,300,400,400i,700,700i');
    wp_enqueue_style('font-awesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');
    wp_enqueue_style('university_main_styles', get_theme_file_uri('/build/style-index.css'));
    wp_enqueue_style('university_extra_styles', get_theme_file_uri('/build/index.css'));
}

add_action('wp_enqueue_scripts', 'university_files');


function university_features()  {

    // *** IF WE WANT WORDPRESS DYNAMIC MENU, WHICH WE WON'T FOR THIS PROJECT *** //
    //     register_nav_menu('headerMenuLocation', 'Header Menu Location');
    //     register_nav_menu('footerLocationOne', 'Footer Location One');
    //     register_nav_menu('footerLocationTwo', 'Footer Location Two');
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_image_size('professorLandscape', 400, 260, true);
    add_image_size('professorPortrait', 480, 650, true);
    add_image_size('pageBanner', 1500, 350, true);
}

add_action('after_setup_theme', 'university_features');

// Right before we get the posts with the WP Query, WP will pass us the WPQuery object which we will use (a variable passed in as $query)
function university_adjust_queries($query) {
    // only use this filter if you're not in admin, it's a program archive page, and it's not a custom query
    if(!is_admin() AND is_post_type_archive('program') AND is_main_query()) {
        // show all programs in alphabetical order
        $query->set('orderby', 'title');
        $query->set('order', 'ASC');
        $query->set('posts_per_page', -1);
    }
    
     // only use this filter if you're not in admin, it's not an event page, and it's not a custom query
    if (!is_admin() AND is_post_type_archive('event') AND $query->is_main_query()) {
        // filter out past events, order ASC
        $today = date('Ymd');
        $query->set('meta_key', 'event_date');
        $query->set('orderby', 'meta_value_num');
        $query->set('order', 'ASC');
        $query->set('meta_query', array(
            array(
              'key' => 'event_date',
              'compare' => '>=',
              'value' => $today,
              'type' => 'numeric'
            )
            ));
    }
}

add_action('pre_get_posts', 'university_adjust_queries');
?>