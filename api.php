<?php

//------------------------------------------------------------------
//---------------------- API PARA LOGIN AOVIVO ---------------------
//------------------------------------------------------------------

if( !function_exists('get_user_role_name') ){
    function get_user_role_name($user_ID){
        global $wp_roles;

        $user_data = get_userdata($user_ID);
        $user_role_slug = $user_data->roles[0];
        return translate_user_role($wp_roles->roles[$user_role_slug]['name']);
    }
}

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

		$check = wp_authenticate_email_password(NULL, $_POST["username"], $_POST["password"]);

		if (is_wp_error($check)) {
			$arr = array('message' => 'não autorizado');
		} else {
			$user = get_user_by('email', $_POST["username"]);
			$id = $user->ID;
			$live = get_option('evento_global');
			$nome = $user->first_name;
			$cidade = get_user_meta($id, 'billing_city', true);
			$uf = get_user_meta($id, 'billing_state', true);
			$email = $_POST["username"];
			$role = get_user_role_name($id);
			$arr = array(
				'nome' => $nome,
				'cidade' => $cidade,
				'uf' => $uf,
				'email' => $email,
				'role' => $role
			);
		}

		echo json_encode($arr);
	}
};

//------------------------------------------------------------------
//---------------------- API ALTERAR SENHA -------------------------
//------------------------------------------------------------------

add_action('rest_api_init', 'global_reset_password');

function global_reset_password()
{
	register_rest_route(
		'global-login',
		'global-reset',
		array(
			'methods' => 'POST',
			'callback' => 'global_reset_phrase'
		)
	);
}

function global_reset_phrase()
{

	if ($_POST["action"] == 'change_pass') {

		$userdata = get_user_by( 'email', $_POST["email"] );
 
        if ( ! $userdata ) {
            $arr = array('message' => 'Não existe nenhum usuário cadastrado com este e-mail!', 'code' => 0);
        } else if ( !$_POST["password"] ) {
            $arr = array('message' => 'É necessário informar uma senha!', 'code' => 0);
        } else {
            wp_set_password( $_POST["password"], $userdata->ID );
            $arr = array('message' => 'A senha do usuário foi atualizada', 'code' => 1);

        }

		echo json_encode($arr);
	}
};

//------------------------------------------------------------------
//------------------------ API PARA CADASTRO -----------------------
//------------------------------------------------------------------

add_action('rest_api_init', 'global_cadastro');

function global_cadastro()
{
	register_rest_route(
		'global-cadastro',
		'global-cadastro',
		array(
			'methods' => 'POST',
			'callback' => 'global_cadastro_api'
		)
	);
}

function global_cadastro_api()
{

	if ($_POST["action"] == 'cadastro_global') {

        $area_atuacao = $_POST["area_atuacao"];
        $evento = $_POST["evento"];
        $nome = $_POST["nome"];
        $email = strtolower($_POST["email"]);
        $uf = $_POST["uf"];
        $cidade = $_POST["cidade"];
        $telefone = $_POST["telefone"];
        $crm = $_POST["crm"];
        $crm_uf = $_POST["crm_uf"];
        $especialidade = $_POST["especialidade"];
        $codigo = $_POST["codigo"];
        $pagante = "0";
        $sabendo = $_POST["sabendo"];
        $termo = $_POST["termo"];
        $senha = $_POST["password"];
        $instituicao = $_POST["instituicao"];
        $cargo = $_POST["cargo"];

        if ($area_atuacao == "Medicina") {
            $role = "medicos";
        } else if ($area_atuacao == "Staff") {
            $role = "staff";
        } else {
            $role = "nao_medicos";
        }

        if (get_option('ativa_perfil_1_global') == '1') {

            if (strpos(get_option('cods_perfil_1_global'), $_POST["codigo"]) !== false) {
                $role = get_option('role_perfil_1_global');
            }
            
        }

        if (get_option('ativa_perfil_2_global') == '1') {
            
            if (strpos(get_option('cods_perfil_2_global'), $_POST["codigo"]) !== false) {
                $role = get_option('role_perfil_2_global');
            }
            
        }

        if (get_option('ativa_perfil_3_global') == '1') {
            
            if (strpos(get_option('cods_perfil_3_global'), $_POST["codigo"]) !== false) {
                $role = get_option('role_perfil_3_global');
            }
            
        }

        if (get_option('ativa_perfil_4_global') == '1') {
            
            if (strpos(get_option('cods_perfil_4_global'), $_POST["codigo"]) !== false) {
                $role = get_option('role_perfil_4_global');
            }
            
        }

        if (get_option('ativa_perfil_5_global') == '1') {
            
            if (strpos(get_option('cods_perfil_5_global'), $_POST["codigo"]) !== false) {
                $role = get_option('role_perfil_5_global');
            }
            
        }

        $userdata = array(
            'user_login' => $email,
            'user_pass' => $senha,
            'user_email' => $email,
            'first_name' => $nome,
            'show_admin_bar_front' => false,
            'role' => $role,
        );

        if (is_wp_error($user_id)) {
            echo json_encode(array('message' => 'Erro no cadastramento!'));
        } else { 

            update_user_meta($user_id, 'billing_area_atuacao', $area_atuacao);
            update_user_meta($user_id, 'billing_state', $uf);
            update_user_meta($user_id, 'billing_city', $cidade);
            update_user_meta($user_id, 'billing_phone', $telefone);
            update_user_meta($user_id, 'billing_crm', $crm);
            update_user_meta($user_id, 'billing_crm_uf', $crm_uf);
            update_user_meta($user_id, 'billing_espec_medica', $especialidade);
            update_user_meta($user_id, 'billing_codigo', $codigo);
            update_user_meta($user_id, 'billing_pagante', $pagante);
            update_user_meta($user_id, 'billing_sabendo', $sabendo);
            update_user_meta($user_id, 'billing_termo', $termo);
            update_user_meta($user_id, 'billing_instituicao', $instituicao);

        }

	} else {
        echo json_encode(array('message' => 'Não autorizado'));
    }
};

//------------------------------------------------------------------
//---------------- API CÓDIGOS PASSAPORTE CLANNAD ------------------
//------------------------------------------------------------------

add_action('rest_api_init', 'global_pass_api');
function global_pass_api()
{
	register_rest_route( 
		'pass-codigos', 
		'pass-codigos', 
		array(
        'methods' => 'GET',
        'callback' => 'global_codigos_pass',
    ) );
}

function global_codigos_pass($data)
{
	global $wpdb;
	$codigos = json_decode(get_option('codigos_global_new'));
	foreach ($codigos as $item) {
		$index = array_search($item, $codigos);
		$result = $wpdb->get_results ( "
			SELECT count(user_id) as qtd
			FROM " . $wpdb->prefix . "global_codigos
			WHERE `codigo` LIKE '" . $item->codigo . "'
		");
		$codigos[$index]->usos = intval($result[0]->qtd);
		$item->qtd = intval($item->qtd);
	}
	
	echo json_encode($codigos);
}