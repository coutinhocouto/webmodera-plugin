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

//-----------------------------------------------------------
//---------------------  CSS --------------------------------
//-----------------------------------------------------------

function billing_css (){

    echo '
		<style>
			.woocommerce-checkout .col-6 {width: 100% !important;}
			.woocommerce-additional-fields, .woocommerce-shipping-fields {display: none;}
			.form-row-first, .form-row-last {width: 50%; display: inline-block;}
            .woocommerce-checkout select {width: 100%;}
		</style>
	';

}

//-----------------------------------------------------------
//---------------------  CRM --------------------------------
//-----------------------------------------------------------

function billing_crm_details()
{
    $checkout = WC()->checkout;

    echo '<div class="billing_crm form-row-first">';

    woocommerce_form_field('billing_crm', array(
        'type'          => 'text',
        'label'         => 'Número do conselho',
        'placeholder'   => '',
        'class'         => array('billing_crm'),
        'required'      => true, // or false
    ), $checkout->get_value('billing_crm'));

    echo '</div>';
}

// Custom checkout fields validation
add_action('woocommerce_checkout_process', 'billing_crm_field_process');
function billing_crm_field_process()
{
    if (isset($_POST['billing_crm']) && empty($_POST['billing_crm']))
        wc_add_notice(__('CRM é obrigatório!'), 'error');
}

// Save custom checkout fields the data to the order
add_action('woocommerce_checkout_create_order', 'billing_crm_field_update_meta', 10, 2);
function billing_crm_field_update_meta($order, $data)
{
    if (isset($_POST['billing_crm']) && !empty($_POST['billing_crm']))
        $order->update_meta_data('billing_crm', sanitize_text_field($_POST['billing_crm']));
}

//-----------------------------------------------------------
//---------------------  CRM UF -----------------------------
//-----------------------------------------------------------

function billing_crm_uf_field($checkout)
{

    $checkout = WC()->checkout;
    woocommerce_form_field(
        'billing_crm_uf',
        array(
            'type'          => 'select',
            'class'         => array('form-row-last'),
            'label'         => 'Selecione o estado conselho',
            'required'      => true, // or false
            'options'       => array(
                'blank' =>  "Selecione um estado",
                "AC"    =>  "AC",
                "AL"    =>  "AL",
                "AP"    =>  "AP",
                "AM"    =>  "AM",
                "BA"    =>  "BA",
                "CE"    =>  "CE",
                "DF"    =>  "DF",
                "ES"    =>  "ES",
                "GO"    =>  "GO",
                "MA"    =>  "MA",
                "MT"    =>  "MT",
                "MS"    =>  "MS",
                "MG"    =>  "MG",
                "PA"    =>  "PA",
                "PB"    =>  "PB",
                "PR"    =>  "PR",
                "PE"    =>  "PE",
                "PI"    =>  "PI",
                "RJ"    =>  "RJ",
                "RN"    =>  "RN",
                "RS"    =>  "RS",
                "RO"    =>  "RO",
                "RR"    =>  "RR",
                "SC"    =>  "SC",
                "SP"    =>  "SP",
                "SE"    =>  "SE",
                "TO"    =>  "TO"
            )

        ),
        $checkout->get_value('billing_crm_uf')
    );
}
//* Process the checkout

add_action('woocommerce_checkout_process', 'billing_crm_uf_field_process');
function billing_crm_uf_field_process()
{
    global $woocommerce;
    // Check if set, if its not set add an error.
    if ($_POST['billing_crm_uf'] == "blank")
        wc_add_notice('<strong>É necessário informar o estado do conselho!', 'error');
}

//* Update the order meta with field value

add_action('woocommerce_checkout_update_order_meta', 'billing_crm_uf_update_order_meta');
function billing_crm_uf_update_order_meta($order_id)
{
    if ($_POST['billing_crm_uf']) update_post_meta($order_id, 'billing_crm_uf', esc_attr($_POST['billing_crm_uf']));
}

//* Display field value on the order edition page

add_action('woocommerce_admin_order_data_after_billing_address', 'billing_crm_uf_display_admin_order_meta', 10, 1);
function billing_crm_uf_display_admin_order_meta($order)
{
    echo '<p><strong>' . __('Estado do conselho') . ':</strong> ' . get_post_meta($order->id, 'billing_crm_uf', true) . '</p>';
}

//-----------------------------------------------------------
//---------------------  ADICIONAR CAMPOS -------------------
//-----------------------------------------------------------

add_action('woocommerce_checkout_after_customer_details', 'billing_css', 19);
add_action('woocommerce_checkout_after_customer_details', 'billing_crm_details', 20);
add_action('woocommerce_checkout_after_customer_details', 'billing_crm_uf_field', 21);