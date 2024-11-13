<?php
/* Template Name: Kanye Quotes */
get_header(); ?>

<main id="content" class="site-main">
    <h2>Kanye Quotes Page</h2>

<?php
function get_kanye_quotes($num_quotes = 5) {
    $quotes = [];
    for ($i = 0; $i < $num_quotes; $i++) {
        $response = wp_remote_get('https://api.kanye.rest');
        if (!is_wp_error($response)) {
            $body = wp_remote_retrieve_body($response);
            $data = json_decode($body);
            $quotes[] = $data->quote;
        }
    }
    return $quotes;
}

$kanye_quotes = get_kanye_quotes();

if ($kanye_quotes) :
    foreach ($kanye_quotes as $quote) : ?>
        <blockquote><?php echo esc_html($quote); ?></blockquote>
    <?php endforeach;
else :
    echo 'Unable to retrieve quotes at this time.';
endif;

?>

</main>
<?php

get_footer();


?>