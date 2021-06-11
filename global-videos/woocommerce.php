<?php

add_action('woocommerce_order_status_completed', 'my_function');
function my_function($order_id)
{

    // order object (optional but handy)
    $order = new WC_Order($order_id);

    foreach ($order->get_items() as $item) {
        $produto = $item['name'];
    }

    $user_id = get_post_meta($order_id, '_customer_user', true);
    $user_info = get_userdata(get_post_meta($order_id, '_customer_user', true));

    $nome = get_user_meta($user_id, 'first_name');
    $sobrenome = get_user_meta($user_id, 'last_name');
    $email = $user_info->user_email;
    $uf = get_user_meta($user_id, 'billing_state');
    $cidade = get_user_meta($user_id, 'billing_city');
    $profissao = get_user_meta($user_id, 'billing_area_atuacao');
    $pais = get_user_meta($user_id, 'billing_country');
    $cep = get_user_meta($user_id, 'billing_postcode');
    $telefone = get_user_meta($user_id, 'billing_phone');
    $cpf = get_user_meta($user_id, 'billing_cpf');
    $crm = get_user_meta($user_id, 'billing_crm');
    $crm_uf = get_user_meta($user_id, 'billing_crm_uf');
    $especialidade = get_user_meta($user_id, 'billing_espec_medica');
    $sexo = get_user_meta($user_id, 'sexo');
    $evento = get_option('evento_global');
    $pagante = 1;
    $codigo = get_user_meta($user_id, 'codigo');

    $valor = $order->get_total();

    $url = 'https://4k5zxy0dui.execute-api.us-east-1.amazonaws.com/webmodera/webhook';

    $data = array(
        "evento" => $evento,
        "email" => $email,
        "nome" => $nome,
        "sobrenome" => $sobrenome,
        "uf" => $uf,
        "cidade" => $cidade,
        "profissao" => $profissao,
        "pais" => $pais,
        "cep" => $cep,
        "telefone" => $telefone,
        "cpf" => $cpf,
        "crm" => $crm,
        "crm_uf" => $crm_uf,
        "especialidade" => $especialidade,
        "codigo" => $codigo,
        "sexo" => $sexo,
        "produto" => $produto,
        "valor" => $valor,
        "pagante" => $pagante
    );

    $postdata = json_encode($data);

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
    print_r($result);
}
