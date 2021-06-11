
<?php

function ajax_login_init(){

wp_register_script('ajax-login-script', get_template_directory_uri() . '/ajax-login-script.js', array('jquery') ); 
wp_enqueue_script('ajax-login-script');

wp_localize_script( 'ajax-login-script', 'ajax_login_object', array( 
    'ajaxurl' => admin_url( 'admin-ajax.php' ),
    'redirecturl' => home_url(),
    'loadingmessage' => __('Sending user info, please wait...')
));

// Enable the user with no privileges to run ajax_login() in AJAX
add_action( 'wp_ajax_nopriv_ajaxlogin', 'ajax_login' );
}

add_action('init', 'ajax_login_init');

function ajax_login(){

header("Access-Control-Allow-Origin: *");
header('Content-Type: application/json; charset=utf-8');

// Nonce is checked, get the POST data and sign user on
$info = array();
$info['user_login'] = $_POST['username'];
$info['user_password'] = $_POST['password'];
//$info['remember'] = false;

$user = wp_signon( $info, false );
if ( is_wp_error($user) ){
    echo json_encode(array('loggedin'=>false));
} else {
    echo json_encode(array( 'loggedin'=>true, 'id'=>$user->ID) );
}

die();
}