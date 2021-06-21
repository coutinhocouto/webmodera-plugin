<?php


//------------------------------------------------------------------
//---------------------- API PARA LOGIN AOVIVO ---------------------
//------------------------------------------------------------------

add_action('rest_api_init', 'global_login');

function global_login()
{
	register_rest_route(
		'global-login',
		'global-login',
		array(
			'methods' => 'POST',
			'callback' => 'global_phrase'
		)
	);
}

function global_phrase()
{

	if ($_POST["action"] == 'login_ajax') {

		$check = wp_authenticate_username_password(NULL, $_POST["username"], $_POST["password"]);

		if (is_wp_error($check)) {
			$arr = array('message' => 'não autorizado');
		} else {
			$user = get_user_by('login', $_POST["username"]);
			$id = $user->ID;
			$live = get_option('evento_global');
			$nome = $user->first_name;
			$cidade = get_user_meta($id, 'billing_city', true);
			$uf = get_user_meta($id, 'billing_state', true);
			$email = $_POST["username"];
			$arr = array(
				'nome' => $nome,
				'cidade' => $cidade,
				'uf' => $uf,
				'email' => $email
			);
		}

		echo json_encode($arr);
	}
}
