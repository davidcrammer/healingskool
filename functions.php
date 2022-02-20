<?php 

function wp_set_scripts () {
    wp_enqueue_style('main_styles', get_stylesheet_uri());
    wp_enqueue_style('theme_styles_main', get_theme_file_uri('/styles/theme-style.css'));
    wp_enqueue_style('adobe_fonts', "https://use.typekit.net/thy4eik.css");
}

add_action('wp_enqueue_scripts', 'wp_set_scripts');


//allows you to use wp_redirect after the header is sent
function app_output_buffer() {
    ob_start();   
}


add_action('init', 'app_output_buffer');

register_post_type('church', $args = array(
    'show_in_rest' => false,
    'supports' => array('title'),
    'public' => true,
    'menu_icon' => 'dashicons-admin-home',
    'labels' => array(
        'name'=>'Churches',
        'add_new_item'=> 'Add New Church',
        'edit_item'=> 'Edit Church',
        'all_items'=> 'View all Churches',
        'singular_name'=>'Church'
    ),
));


register_post_type('submission', $args = array(
    'show_in_rest' => false,
    'supports' => array('title'),
    'public' => true,
    'menu_icon' => 'dashicons-archive',
    'labels' => array(
        'name'=>'Submissions',
        'add_new_item'=> 'Add New Submission',
        'edit_item'=> 'Edit Submission',
        'all_items'=> 'View all Submissions',
        'singular_name'=>'Submission'
    ),
));

register_post_type('sub_comment', $args = array(
    'show_in_rest' => false,
    'supports' => array('title', 'editor'),
    'public' => true,
    'menu_icon' => 'dashicons-format-chat',
    'publicly_queryable'  => false,
    'labels' => array(
        'name'=>'Sub Comments',
        'add_new_item'=> 'Add New Comment',
        'edit_item'=> 'Edit Comment',
        'all_items'=> 'View all Comments',
        'singular_name'=>'Submission Comment'
    ),
));


add_action( 'template_redirect', 'process_church_form' );

function process_church_form() {
    if( ! isset($_POST["church-form"]) || ! isset( $_POST["church-form-nonce"])) {
        return;
    }

    if ( ! wp_verify_nonce( $_POST['church-form-nonce'], 'do-it' ) ) {
		return;
	}

    //process the form
    $email = sanitize_text_field( $_POST["message_email"] );
    $full_name = sanitize_text_field( $_POST["message_full_name"] );
    $doctor = sanitize_text_field( $_POST["message_doctor"]);

    $url_array = explode( '?', wp_get_referer() );
    $url = $url_array[0];

    if( ! is_email($email)) {
        $url = add_query_arg('err', 'inv-email', $url);

    } elseif( $full_name == '') {
        $url = add_query_arg('err', 'inv-name', $url);
    
    } else {
        //it's good to go, so do some stuff with it
        
        //create submission post with info 
        $newSubmission = wp_insert_post(array(
            'post_type' => 'submission',
            'post_title' => $full_name,
            'post_status' => 'publish',
        ));
        update_field('church', get_queried_object(), $newSubmission);
        update_field('email', $email, $newSubmission);
        update_field('full_name', $full_name, $newSubmission);
        update_field('doctor', $doctor, $newSubmission);

        $url = add_query_arg('success', 1, $url);
    }

    //redirect back to the form
    wp_safe_redirect( $url );
	exit();
}






function form_validation_messages() {

    if( isset( $_GET['err'])){
        $error = sanitize_title( $_GET['err'] );
        
        switch($error){
            case 'inv-email':
                $message_content = 'Invalid email address.';
                break;

            case 'inv-name':
                $message_content = 'Invalid name.';
                break;
            
            default:
                $message_content = 'Something went wrong.';
                break;

        }

        echo '<div class="notification error"><p>Error: ' . $message_content . '</p></div>'; 

    } 

    if( isset( $_GET['success'])){

        echo '<div class="notification success"><p>Success! We will get back to you shortly. </p></div>'; 

    }

}



// requires login and automatically filters submissions by what Churches that user is a part of

function logged_in() {
    global $post;
    
    if (!is_user_logged_in()) {
        if(wp_redirect(wp_login_url(get_permalink()))) {
            exit;
        }
    } else {
        
        return 1;
    }
}   




//process the comment form on submissions
add_action( 'template_redirect', 'process_comment_form' );

function process_comment_form() {
    if( ! isset($_POST["comment-form"]) || ! isset( $_POST["comment-form-nonce"])) {
        return;
    }
    if ( ! wp_verify_nonce( $_POST['comment-form-nonce'], 'do-it-2' ) ) {
		return;
	}

    //process the form
    $cu = wp_get_current_user();
    $commentBody = sanitize_text_field( $_POST["comment_field"] );

    $url_array = explode( '?', wp_get_referer() );
    $url = $url_array[0];
    
    if(empty($commentBody)){
        $url = add_query_arg('error', 'no-body', $url);
    } else {
        //it's good to go, so do some stuff with it
            
        //create submission post with info 
        $newComment = wp_insert_post(array(
            'post_type' => 'sub_comment',
            'post_status' => 'publish',
            'post_author' => $cu->ID,
            'post_content' => esc_html($commentBody),
        ));
        update_field('submission', get_queried_object(), $newComment);

        $url = add_query_arg('success', 1, $url);
    }

    //redirect back to the form
    wp_safe_redirect( $url );
	exit();
}