<?php
get_header(); ?>


<main id="content" class="site-main">
    <h2>Archive Projects Template </h2>
<?php

$args = [
    'post_type' => 'projects',
    'posts_per_page' => 6,
    'order' => 'ASC',
    'paged' => get_query_var('paged') ? get_query_var('paged') : 1
];
$projects_query = new WP_Query($args);
?>

<div class="projects">
    <?php
if ($projects_query->have_posts()) :
    while ($projects_query->have_posts()) : $projects_query->the_post(); ?>
      <div class="inner-pro">
        <h2><?php the_title(); ?></h2>
        <p><?php the_excerpt(); ?></p>
</div>
    <?php endwhile;
    
    ?>
     </div>
    <?php

    // Pagination
    previous_posts_link('Prev');
    next_posts_link('Next', $projects_query->max_num_pages);

else :
    echo 'No projects found.';
endif;
wp_reset_postdata();
?>
</div>

</main>

<?php
get_footer();
?>