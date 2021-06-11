<?php
add_action( 'rest_api_init', 'global_login' );

function global_login() {
    register_rest_route( 'global-login', 'global-login', array(
			'methods' => 'POST',
			'callback' => 'global_phrase'
		)
	);
}

function global_phrase() {

	if($_POST["action"] == 'login_ajax') {

		$check = wp_authenticate_username_password( NULL, $_POST["username"], $_POST["password"]);

		if ( is_wp_error( $check ) ) {
			$arr = array('mensage' => 'não autorizado');
		} else{
			$arr = array('mensage' => 'autorizado');
		}

		echo json_encode($arr);
		
	}
	
}