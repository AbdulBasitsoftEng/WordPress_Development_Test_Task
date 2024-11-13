<?php
get_header();

$args = [
    'post_type' => 'projects',
    'posts_per_page' => 6,
    'order' => 'ASC',
    'paged' => get_query_var('paged') ? get_query_var('paged') : 1
];
$projects_query = new WP_Query($args);

if ($projects_query->have_posts()) :
    while ($projects_query->have_posts()) : $projects_query->the_post(); ?>
        <h2><?php the_title(); ?></h2>
        <div><?php the_excerpt(); ?></div>
    <?php endwhile;

    // Pagination
    previous_posts_link('Prev');
    next_posts_link('Next', $projects_query->max_num_pages);

else :
    echo 'No projects found.';
endif;
wp_reset_postdata();

get_footer();
