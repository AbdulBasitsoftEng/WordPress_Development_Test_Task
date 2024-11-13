jQuery(document).ready(function($) {
    $.ajax({
        url: ajax_object.ajaxurl,
        type: 'POST',
        data: {
            action: 'get_recent_architecture_projects'
        },
        success: function(response) {
            if (response.success) {
                console.log(response.data); // This will output the array of projects
                // Here you can handle the response data as needed
            } else {
                console.log('No projects found.');
            }
        },
        error: function() {
            console.log('An error occurred.');
        }
    });
});
