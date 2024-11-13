<?php
/**
 * Recommended way to include parent theme styles.
 * (Please see http://codex.wordpress.org/Child_Themes#How_to_Create_a_Child_Theme)
 *
 */  

add_action( 'wp_enqueue_scripts', 'hello_elementor_child_style' );
				function hello_elementor_child_style() {
					wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );
					wp_enqueue_style( 'child-style', get_stylesheet_directory_uri() . '/style.css', array('parent-style') );
				}

/**
 * Your code goes below.
 */

// Ist Task Requirement (IP ADDRESS REDIRECT)
function redirect_based_on_ip() {
    // Get the user's IP address
    $user_ip = $_SERVER['REMOTE_ADDR'];

    // Check if the IP address is ::1 (IPv6 localhost) or 127.0.0.1 (IPv4 localhost)
    if ($user_ip === '::1' || strpos($user_ip, '127.0.0.1') === 0) {
        // Redirect to another site or specific URL for localhost
        wp_redirect('https://google.com/'); // Replace with your desired URL
        exit; // Stop further execution after redirect
    }

    // Split the IP address into parts by the dot (.)
    $ip_parts = explode('.', $user_ip);

    // Check if the first two parts of the IP address are equal to "77" and "29" for live server
    if (isset($ip_parts[0]) && isset($ip_parts[1]) && $ip_parts[0] === '77' && $ip_parts[1] === '29') {
        // Redirect the user to an external site or a 403 forbidden page
        wp_redirect('https://google.com/'); // Replace with your desired URL
        exit;
    }
}
add_action('init', 'redirect_based_on_ip');



//ADDING JS FILE FOR JQUERY AJAX CODE 
function enqueue_my_ajax_script() {
    // Use get_stylesheet_directory_uri() if you're in a child theme
    wp_enqueue_script(
        'my-ajax-script',
        get_stylesheet_directory_uri() . '/js/my-ajax-script.js', // Correct path for a child theme
        array('jquery'),
        null,
        true
    );

    // Localize the script to add the AJAX URL
    wp_localize_script(
        'my-ajax-script',
        'ajax_object', // Updated to match best practices for object naming
        array('ajaxurl' => admin_url('admin-ajax.php'))
    );
}
add_action('wp_enqueue_scripts', 'enqueue_my_ajax_script');




// I2ND Task Requirement (ADD CUSTOM POST TYPE PROJECTS AND TAXONOMY PROJECT TYPE)
function register_projects_post_type() {
    register_post_type('projects', [
        'labels' => [
            'name' => 'Projects',
            'singular_name' => 'Project'
        ],
        'public' => true,
        'has_archive' => true,
        'supports' => ['title', 'editor', 'thumbnail'],
        'rewrite' => ['slug' => 'projects'],
    ]);

    register_taxonomy('project_type', 'projects', [
        'labels' => [
            'name' => 'Project Types',
            'singular_name' => 'Project Type',
        ],
        'hierarchical' => true,
        'rewrite' => ['slug' => 'project-type'],
    ]);
}
add_action('init', 'register_projects_post_type');


// 3RD Task Requirement (ADD ARCHIVE TEMPLATE FOR PROJECTS)

// 4TH Task Requirement (AJAX ENDPOINT)
function get_recent_architecture_projects() {
    // Set the number of posts to retrieve based on user login status
    $posts_per_page = is_user_logged_in() ? 6 : 3;

    // Query parameters for fetching the latest "Projects" in the "Architecture" taxonomy
    $args = array(
        'post_type' => 'projects',
        'posts_per_page' => $posts_per_page,
        'tax_query' => array(
            array(
                'taxonomy' => 'project_type',
                'field'    => 'slug',
                'terms'    => 'architecture',
            ),
        ),
    );

    // Perform the query
    $projects_query = new WP_Query($args);

    // Initialize an empty array to store data
    $data = array();

    // Loop through the posts and populate the data array
    if ($projects_query->have_posts()) {
        while ($projects_query->have_posts()) {
            $projects_query->the_post();
            $data[] = array(
                'id'    => get_the_ID(),
                'title' => get_the_title(),
                'link'  => get_the_permalink(),
            );
        }
    }

    // Reset post data after the query
    wp_reset_postdata();

    // Return the response in JSON format
    wp_send_json(array('success' => true, 'data' => $data));
}

// Register the AJAX actions
add_action('wp_ajax_get_recent_architecture_projects', 'get_recent_architecture_projects');
add_action('wp_ajax_nopriv_get_recent_architecture_projects', 'get_recent_architecture_projects');


// 5TH Task Requirement (RANDOM COFFEE API INTEGRATION)
function hs_give_me_coffee() {
    $response = wp_remote_get('https://coffee.alexflipnote.dev/random');
    
    // Check for errors
    if (is_wp_error($response)) {
        return 'Could not retrieve coffee image.';
    }

    $body = wp_remote_retrieve_body($response);
    $data = json_decode($body);

    // Check if the image URL is available
    if (isset($data->file)) {
        return esc_url($data->file);
    }

    return 'No coffee image found.';
}

// 6TH Task Requirement (KANYE QUOTES PAGE)

 