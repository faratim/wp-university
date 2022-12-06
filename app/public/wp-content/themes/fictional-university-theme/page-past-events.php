<!-- This is the generic event archive screen template. -->

<!-- HEADER -->
<?php 
  get_header(); 
  pageBanner(array(
    'title' => 'Past Events',
    'subtitle' => 'A recap of our past events.'
  ));
?>

<!-- ALL EVENTS CONTAINER -->
<div class="container container--narrow page-section">



<!-- Get the posts(events) -->
<?php 
   // only show past events
   $today = date('Ymd');
   $pastEvents = new WP_Query(array(
      'paged' => get_query_var('paged', 1), //needed for pagination with custom query
      'post_type' => 'event',
       'meta_key' => 'event_date',
       'orderby' => 'meta_value_num',
       'order' => 'ASC',
       'meta_query' => array(
         array(
           'key' => 'event_date',
           'compare' => '<',
           'value' => $today,
           'type' => 'numeric'
         )
       )
   ));

   while($pastEvents->have_posts()) {
    $pastEvents->the_post(); 
    get_template_part('template-parts/content-event');
  }
  //paginate_links works with default queries unless you give it parameters of your custom query
  echo paginate_links(array(
   'total' => $pastEvents->max_num_pages
  ));
  ?>

</div>

<!-- FOOTER -->
<?php get_footer();

?>