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
//---------------------- CONTEUDO BLOQUEADO ------------------------
//------------------------------------------------------------------

function bloqueado_shortcode( $atts = array(), $content = null ) {
    if(is_user_logged_in()) {
		return $content;
	}
}

add_shortcode( 'bloqueado', 'bloqueado_shortcode' );

//------------------------------------------------------------------
//---------------------- CADASTRO VIP ------------------------------
//------------------------------------------------------------------

function global_cadastra_vip_form()
{
    if (!empty($_POST)) {

        $area_atuacao = "";
        $evento = $_POST["evento"];
        $nome = $_POST["nome"];
        $email = strtolower($_POST["email"]);
        $uf = "";
        $cidade = "";
        $telefone = "";
        $cpf = "";
        $crm = "";
        $crm_uf = "";
        $especialidade = "";
        $codigo = "";
        $pagante = "0";
        $produto = "";
        $valor = "";
        $sabendo = "";
        $termo = "";
        $senha = "";
        $cargo = "";
        $status = "2";

        $url = 'https://4k5zxy0dui.execute-api.us-east-1.amazonaws.com/webmodera/webhook';

        $userdata = array(
            'user_login' => $email,
            'user_pass' => $senha,
            'user_email' => $email,
            'first_name' => $nome,
            'show_admin_bar_front' => false,
            'role' => "vip",
        );

        //print_r($userdata);

        $user_id = wp_insert_user($userdata);

        if (is_wp_error($user_id)) {

            echo '<label class="error" for="email">' . $user_id->get_error_message() . '</label>';

        } else {
            update_user_meta($user_id, 'billing_state', $uf);
            update_user_meta($user_id, 'billing_city', $cidade);
            update_user_meta($user_id, 'billing_phone', $telefone);
            update_user_meta($user_id, 'billing_pagante', $pagante);
            update_user_meta($user_id, 'billing_sabendo', $sabendo);
            update_user_meta($user_id, 'billing_termo', $termo);

            $data = array(
                "evento" => $evento,
                "email" => $email,
                "nome" => $nome,
                "uf" => $uf,
                "cidade" => $cidade,
                "telefone" => $telefone,
                "cpf" => $cpf,
                "crm" => $crm,
                "crm_uf" => $crm_uf,
                "codigo" => $codigo,
                "especialidade" => $especialidade,
                "pagante" => $pagante,
                "sabendo" => $sabendo,
                "termo" => $termo,
                "produto" => $produto,
                "valor" => $valor,
                "profissao" => $area_atuacao,
                "status" => $status,
                "cargo" => $cargo
            );

            $postdata = json_encode($data);

            //print_r($data);

            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
            $result = curl_exec($ch);
            curl_close($ch);
            //print_r($result);

            $creds = array(
                'user_login'    => $email,
                'user_password' => $senha,
                'remember'      => true
            );
            $user = wp_signon($creds, false);

            $to = $email;
			$headers = array('Content-Type: text/html; charset=UTF-8');
            if(get_option('assunto_vip_global')){
                $subject = get_option('assunto_vip_global');
                $body = get_option('body_vip_global');
                wp_mail( $to, $subject, $body, $headers );
            }

            echo '<script>window.location.replace("' . get_option('inscrito_global') . '");</script>';
        }
    } else {

?>

        <style>
            #cadastramento label {
                margin-bottom: 10px;
            }

            #cadastramento input,
            #cadastramento select,
            #cadastramento button {
                display: block;
                width: 100%;
                border-radius: 3px;
            }

            #cadastramento input[type=checkbox] {
                display: inline-block;
                width: auto;
            }

            #cadastramento .wb-100,
            #cadastramento .wb-50,
            #cadastramento .wb-33,
            #cadastramento .wb-70,
            #cadastramento .wb-30 {
                display: inline-block;
                margin-bottom: 30px;
            }

            #cadastramento .wb-100 {
                width: 100%;
            }

            #cadastramento .wb-70 {
                width: 69%;
            }

            #cadastramento .wb-50 {
                width: 49.4%;
            }

            #cadastramento .wb-30 {
                width: 30%;
            }

            #cadastramento .wb-33 {
                width: 32.7%;
            }

            #cadastramento input[type=submit],
            #cadastramento button {
                background: #17a2b8;
                color: #fff;
                border: none;
            }

            #cadastramento input[type=submit]:hover,
            #cadastramento button:hover {
                background: #000;
            }

            #cadastramento .p1 {
                display: none;
            }

            .validation_error {
                padding: 10px;
                background: #f00;
                color: #fff;
                border-radius: 3px;
            }

            #cadastramento small {
                display: block;
                color: #f00;
            }

            label.error {
                background: #f00;
                color: #fff;
                padding: 5px;
                display: block;
                margin-top: 5px;
                border-radius: 3px;
            }

            @media only screen and (max-width: 800px) {

                #cadastramento .wb-70,
                #cadastramento .wb-50,
                #cadastramento .wb-33,
                #cadastramento .wb-30 {
                    width: 100%;
                }
            }
        </style>

        <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.3/dist/jquery.validate.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js" integrity="sha512-pHVGpX7F/27yZ0ISY+VVjyULApbDlD0/X0rgGbTqCE7WFW5MezNTWG/dnhtbBuICzsd0WQPgpE4REBLv+UqChw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

        <script>
            jQuery(document).ready(function($) {

                $("#cadastramento").validate({
                    rules: {
                        password: {
                            minlength: 5
                        },
                        cpassword: {
                            minlength: 5,
                            equalTo: "#password"
                        }
                    }
                });

                jQuery.extend(jQuery.validator.messages, {
                    required: "Este campo é obrigatório.",
                    remote: "Please fix this field.",
                    email: "Informe um endereço de e-mail válido.",
                    url: "Please enter a valid URL.",
                    date: "Please enter a valid date.",
                    dateISO: "Please enter a valid date (ISO).",
                    number: "Please enter a valid number.",
                    digits: "Please enter only digits.",
                    creditcard: "Please enter a valid credit card number.",
                    equalTo: "A confirmação da senha precisa ser igual a senha.",
                    accept: "Please enter a value with a valid extension.",
                    maxlength: jQuery.validator.format("Please enter no more than {0} characters."),
                    minlength: jQuery.validator.format("A senha precisa ter pelo menos {0} caracteres."),
                    rangelength: jQuery.validator.format("Please enter a value between {0} and {1} characters long."),
                    range: jQuery.validator.format("Please enter a value between {0} and {1}."),
                    max: jQuery.validator.format("Please enter a value less than or equal to {0}."),
                    min: jQuery.validator.format("Please enter a value greater than or equal to {0}.")
                });

            });
        </script>

        <form action="<?php echo $_SERVER['REQUEST_URI'] ?>" method="post" id="cadastramento">

            <input type="hidden" value="<?php echo get_option('evento_global'); ?>" name="evento" />

            <div class="wb-100" id="email">
                <label for="nome">E-mail *</label>
                <input type="email" name="email" required />
            </div>

            <div class="wb-100">
                <label for="nome">Nome Completo *</label>
                <input type="text" name="nome" required />
            </div>

            <div class="wb-50">
                <label form="nome">Senha</label>
                <input type="password" name="password" id="password" required />
            </div>

            <div class="wb-50">
                <label form="nome">Confirme sua senha</label>
                <input type="password" name="cpassword" required />
            </div>

            <div class="wb-100">
                <input type="submit" value="Cadastre-se" />
            </div>
        </form>

<?php }
}

add_shortcode('cadastro_vip', 'global_cadastra_vip_form');


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
//---------------------- PÁGINA PARA SAIR --------------------------
//------------------------------------------------------------------

function sair_shortcode() {
    
    echo '<script>window.location.replace("' . home_url() . '");</script>';

    if(is_user_logged_in()) {
		wp_logout();
	}
}

add_shortcode( 'sair', 'sair_shortcode' );

//------------------------------------------------------------------
//--------- EXIBE NOME NO TEMA ASTRA - CSS CUSTOMIZADO -------------
//------------------------------------------------------------------

add_action('wp_head', 'global_nome_head');
function global_nome_head(){

    $user_info = get_userdata(get_current_user_id());
    $first_name = $user_info->first_name;	

    if(is_user_logged_in()) {
        ?>

            <style>
                .global-elemento-deslogado {display: none;}
            </style>
            
            <script>
                jQuery(document).ready(function($) {
                    
                    $('#ast-desktop-header ul').append('<li><span style="color: #fff; padding: 6px 0 5px; display: block;">Olá, <?php echo $first_name;?></span></li>');
                    
                })
            </script>

        <?php
    }
};


//------------------------------------------------------------------
//---------------------- ESCONDE ADMIN BAR -------------------------
//------------------------------------------------------------------

add_action('after_setup_theme', 'remove_admin_bar');
function remove_admin_bar() {
	if (!current_user_can('administrator') && !is_admin()) {
		show_admin_bar(false);
	}
}

//------------------------------------------------------------------
//---- REDICIONA NON ADMIN QUE TENTAM ACESSAR O WP-ADMIN -----------
//------------------------------------------------------------------
//
function redirect_non_admin_user(){
    if ( is_user_logged_in() ) {
        if ( !defined( 'DOING_AJAX' ) && !current_user_can('administrator') ){
            wp_redirect( site_url() );  exit;
        }
    }
}
add_action( 'admin_init', 'redirect_non_admin_user' );

//------------------------------------------------------------------
//------------------------ CSS GLOBAL ------------------------------
//------------------------------------------------------------------

function global_custom_css() {
    echo "<link href='" . plugin_dir_url( __FILE__ ). "styles/global.css' rel='stylesheet' type='text/css'>";
}
add_action( 'wp_head', 'global_custom_css' );

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
//------------------------ API PARA CADASTRO -----------------------
//------------------------------------------------------------------

add_action('rest_api_init', 'global_codigos_api');
function global_codigos_api()
{
	register_rest_route( 
		'codigos', 
		'codigo', 
		array(
        'methods' => 'GET',
        'callback' => 'global_cadastro_api_func',
    ) );
}

function global_cadastro_api_func($data)
{
	global $wpdb;
	$cod = $data->get_param( 'cod' );
	
	$result = $wpdb->get_results ( "
		SELECT count(umeta_id) as qtd
		FROM " . $wpdb->prefix . "usermeta
		WHERE `meta_key` LIKE 'billing_codigo' AND `meta_value` LIKE '" . $cod . "'
	");

	
	echo json_encode(array('usos' => $result[0]->qtd ));
}