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

    $nome =  $user_info->first_name . " " . $user_info->last_name;
    $email = $user_info->user_email;
    $uf = get_user_meta($user_id, 'billing_state');
    $cidade = get_user_meta($user_id, 'billing_city');
    $profissao = get_post_meta($order->id, 'billing_area_atuacao', true);
    $pais = get_user_meta($user_id, 'billing_country');
    $cep = get_user_meta($user_id, 'billing_postcode');
    $telefone = get_user_meta($user_id, 'billing_phone');
    $cpf = get_user_meta($user_id, 'billing_cpf');
    $crm = get_post_meta($order->id, 'billing_crm', true);
    $crm_uf = get_post_meta($order->id, 'billing_crm_uf', true);
    $especialidade = get_post_meta($order->id, 'billing_espec_medica', true);
	$termo = get_post_meta($order->id, 'billing_termo', true);
	$sabendo = get_post_meta($order->id, 'billing_sabendo', true);
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
        "pagante" => $pagante,
		"termo" => $termo,
        "sabendo" => $sabendo
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

function billing_css()
{

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

    woocommerce_form_field('billing_crm', array(
        'type'          => 'text',
        'label'         => 'N??mero do conselho',
        'placeholder'   => '',
        'class'         => array('form-row-first'),
        'required'      => true, // or false
    ), $checkout->get_value('billing_crm'));
}

// Custom checkout fields validation
add_action('woocommerce_checkout_process', 'billing_crm_field_process');
function billing_crm_field_process()
{
    if (isset($_POST['billing_crm']) && empty($_POST['billing_crm']))
        wc_add_notice(__('CRM ?? obrigat??rio!'), 'error');
}

// Save custom checkout fields the data to the order
add_action('woocommerce_checkout_create_order', 'billing_crm_field_update_meta', 10, 2);
function billing_crm_field_update_meta($order, $data)
{
    if (isset($_POST['billing_crm']) && !empty($_POST['billing_crm'])){
        
        $order->update_meta_data('billing_crm', sanitize_text_field($_POST['billing_crm']));

    }
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
        wc_add_notice('<strong>?? necess??rio informar o estado do conselho!</strong>', 'error');
}

//* Update the order meta with field value

add_action('woocommerce_checkout_update_order_meta', 'billing_crm_uf_update_order_meta');
function billing_crm_uf_update_order_meta($order_id)
{
    if ($_POST['billing_crm_uf']) {
        update_post_meta($order_id, 'billing_crm_uf', esc_attr($_POST['billing_crm_uf']));
    }
}

//-----------------------------------------------------------
//---------------------  ??REA DE ATUA????O --------------------
//-----------------------------------------------------------

function billing_area_atuacao_field($checkout)
{
    $checkout = WC()->checkout;
    woocommerce_form_field(
        'billing_area_atuacao',
        array(
            'type'          => 'select',
            'class'         => array('form-row-wide'),
            'label'         => '??rea de atua????o',
            'required'      => true, // or false
            'options'       => array(
                'blank' =>  "Selecione uma ??rea de atua????o",
            )
        ),
        $checkout->get_value('billing_area_atuacao')
    );

?>

    <script>
        jQuery(document).ready(function($) {

            <?php
            if (get_option('tem_medico_global') == '1') {
                echo '$("#billing_area_atuacao").append(new Option("Medicina", "Medicina"));';
            }
            ?>

            <?php
            if (get_option('tem_nao_medico_global') == '1') {

                $areas = explode(",", get_option('nao_medico_atuacao_global'));

                foreach ($areas as $area) {
                    echo '$("#billing_area_atuacao").append(new Option("' . ltrim($area) . '", "' . ltrim($area) . '"));';
                }
            }
            ?>

            $('#billing_espec_medica_field').hide();

            $('#billing_area_atuacao').on('change', function() {

                console.log($(this).val());

                if ($(this).val() == "Medicina") {
                    $('#billing_espec_medica_field').show();
                } else {
                    $('#billing_espec_medica_field').hide();
                }

            });

        });
    </script>

<?php
}
//* Process the checkout

add_action('woocommerce_checkout_process', 'billing_area_atuacao_field_process');
function billing_area_atuacao_field_process()
{
    global $woocommerce;
    // Check if set, if its not set add an error.
    if ($_POST['billing_area_atuacao'] == "blank")
        wc_add_notice('<strong>?? necess??rio informar a ??rea de atua????o!</strong>', 'error');
}

//* Update the order meta with field value

add_action('woocommerce_checkout_update_order_meta', 'billing_area_atuacao_update_order_meta');
function billing_area_atuacao_update_order_meta($order_id)
{
    if ($_POST['billing_area_atuacao']){
        update_post_meta($order_id, 'billing_area_atuacao', esc_attr($_POST['billing_area_atuacao']));
    }
}

//-----------------------------------------------------------
//---------------------  ESPECIALIDADE ----------------------
//-----------------------------------------------------------

function billing_espec_medica_field($checkout)
{

    $checkout = WC()->checkout;
    woocommerce_form_field(
        'billing_espec_medica',
        array(
            'type'          => 'select',
            'class'         => array('form-row-wide'),
            'label'         => 'Especialidade m??dica',
            'required'      => true, // or false
            'options'       => array(
                'blank' =>  "Selecione uma especialidade m??dica",
                "Acupuntura"    =>  "Acupuntura",
                "Alergia e imunologia"    =>  "Alergia e imunologia",
                "Alergia e imunologia pedi??trica"    =>  "Alergia e imunologia pedi??trica",
                "Anestesiologia"    =>  "Anestesiologia",
                "Angiologia"    =>  "Angiologia",
                "Cardiologia"    =>  "Cardiologia",
                "Cardiologia pedi??trica"    =>  "Cardiologia pedi??trica",
                "Cirurgia bari??trica"    =>  "Cirurgia bari??trica",
                "Cirurgia cardiovascular"    =>  "Cirurgia cardiovascular",
                "Cirurgia da m??o"    =>  "Cirurgia da m??o",
                "Cirurgia de cabe??a e pesco??o"    =>  "Cirurgia de cabe??a e pesco??o",
                "Cirurgia do aparelho digestivo"    =>  "Cirurgia do aparelho digestivo",
                "Cirurgia geral"    =>  "Cirurgia geral",
                "Cirurgia oncol??gica"    =>  "Cirurgia oncol??gica",
                "Cirurgia pedi??trica"    =>  "Cirurgia pedi??trica",
                "Cirurgia pl??stica"    =>  "Cirurgia pl??stica",
                "Cirurgia tor??cica"    =>  "Cirurgia tor??cica",
                "Cirurgia vascular"    =>  "Cirurgia vascular",
                "Cl??nica m??dica"    =>  "Cl??nica m??dica",
                "Coloproctologia"    =>  "Coloproctologia",
                "Dermatologia"    =>  "Dermatologia",
                "Dor"    =>  "Dor",
                "Endocrinologia e metabologia"    =>  "Endocrinologia e metabologia",
                "Endocrinologia pedi??trica"    =>  "Endocrinologia pedi??trica",
                "Endoscopia"    =>  "Endoscopia",
                "Gastroenterologia"    =>  "Gastroenterologia",
                "Gastroenterologia pedi??trica"    =>  "Gastroenterologia pedi??trica",
                "Gen??tica m??dica"    =>  "Gen??tica m??dica",
                "Geriatria"    =>  "Geriatria",
                "Ginecologia e obstetr??cia"    =>  "Ginecologia e obstetr??cia",
                "Hematologia e hemoterapia"    =>  "Hematologia e hemoterapia",
                "Hematologia e hemoterapia pedi??trica"    =>  "Hematologia e hemoterapia pedi??trica",
                "Hepatologia"    =>  "Hepatologia",
                "Homeopatia"    =>  "Homeopatia",
                "Infectologia"    =>  "Infectologia",
                "Infectologia pedi??trica"    =>  "Infectologia pedi??trica",
                "Mastologia"    =>  "Mastologia",
                "Medicina de emerg??ncia"    =>  "Medicina de emerg??ncia",
                "Medicina de fam??lia e comunidade"    =>  "Medicina de fam??lia e comunidade",
                "Medicina de tr??fego"    =>  "Medicina de tr??fego",
                "Medicina do trabalho"    =>  "Medicina do trabalho",
                "Medicina esportiva"    =>  "Medicina esportiva",
                "Medicina f??sica e reabilita????o"    =>  "Medicina f??sica e reabilita????o",
                "Medicina intensiva"    =>  "Medicina intensiva",
                "Medicina intensiva pedi??trica"    =>  "Medicina intensiva pedi??trica",
                "Medicina legal e per??cia m??dica"    =>  "Medicina legal e per??cia m??dica",
                "Medicina nuclear"    =>  "Medicina nuclear",
                "Medicina preventiva e social"    =>  "Medicina preventiva e social",
                "Nefrologia"    =>  "Nefrologia",
                "Nefrologia pedi??trica"    =>  "Nefrologia pedi??trica",
                "Neurocirurgia"    =>  "Neurocirurgia",
                "Neurologia"    =>  "Neurologia",
                "Neurologia pedi??trica"    =>  "Neurologia pedi??trica",
                "Nutrologia"    =>  "Nutrologia",
                "Oftalmologia"    =>  "Oftalmologia",
                "Oncologia cl??nica"    =>  "Oncologia cl??nica",
                "Oncologia pedi??trica"    =>  "Oncologia pedi??trica",
                "Ortopedia e traumatologia"    =>  "Ortopedia e traumatologia",
                "Otorrinolaringologia"    =>  "Otorrinolaringologia",
                "Patologia"    =>  "Patologia",
                "Patologia cl??nica/medicina laboratorial"    =>  "Patologia cl??nica/medicina laboratorial",
                "Pediatria"    =>  "Pediatria",
                "Pneumologia"    =>  "Pneumologia",
                "Pneumologia pedi??trica"    =>  "Pneumologia pedi??trica",
                "Psiquiatria"    =>  "Psiquiatria",
                "Radiologia e diagn??stico por imagem"    =>  "Radiologia e diagn??stico por imagem",
                "Radioterapia"    =>  "Radioterapia",
                "Reumatologia"    =>  "Reumatologia",
                "Reumatologia pedi??trica"    =>  "Reumatologia pedi??trica",
                "Urologia"    =>  "Urologia"
            )

        ),
        $checkout->get_value('billing_espec_medica')
    );

}
//* Process the checkout

add_action('woocommerce_checkout_process', 'billing_espec_medica_field_process');
function billing_espec_medica_field_process()
{
    global $woocommerce;
    // Check if set, if its not set add an error.
    if ($_POST['billing_area_atuacao'] == "Medicina") {
        if ($_POST['billing_espec_medica'] == "blank")
            wc_add_notice('<strong>?? necess??rio sua especialidade m??dica!</strong>', 'error');
    }
}

//* Update the order meta with field value

add_action('woocommerce_checkout_update_order_meta', 'billing_espec_medica_update_order_meta');
function billing_espec_medica_update_order_meta($order_id)
{
    if ($_POST['billing_espec_medica']) {
        update_post_meta($order_id, 'billing_espec_medica', esc_attr($_POST['billing_espec_medica']));
    }
}

//-----------------------------------------------------------
//---------------------  SABENDO ----------------------------
//-----------------------------------------------------------

function billing_sabendo_field($checkout)
{

    $checkout = WC()->checkout;
    woocommerce_form_field(
        'billing_sabendo',
        array(
            'type'          => 'select',
            'class'         => array('form-row-wide'),
            'label'         => 'Como ficou sabendo?',
            'required'      => true, // or false
            'options'       => array(
                'blank' =>  "Selecione",
                'E-mail marketing'      =>  "E-mail marketing",
                'Redes sociais'      =>  "Redes sociais",
                'Sociedade M??dica'      =>  "Sociedade M??dica",
                'Representante'      =>  "Representante",
                'Outros'      =>  "Outros"
            )

        ),
        $checkout->get_value('billing_sabendo')
    );
}
//* Process the checkout

add_action('woocommerce_checkout_process', 'billing_sabendo_field_process');
function billing_sabendo_field_process()
{
    global $woocommerce;
    // Check if set, if its not set add an error.
    if ($_POST['billing_sabendo'] == "blank")
        wc_add_notice('<strong>?? necess??rio informar como ficou sabendo!</strong>', 'error');
}

//* Update the order meta with field value

add_action('woocommerce_checkout_update_order_meta', 'billing_sabendo_update_order_meta');
function billing_sabendo_update_order_meta($order_id)
{
    if ($_POST['billing_sabendo']) {
        update_post_meta($order_id, 'billing_sabendo', esc_attr($_POST['billing_sabendo']));
    }
}

//-----------------------------------------------------------
//---------------------  TERMO 1 ----------------------------
//-----------------------------------------------------------

function billing_termo_field($checkout)
{

    $checkout = WC()->checkout;
    woocommerce_form_field(
        'billing_termo',
        array(
            'type'          => 'checkbox',
            'class'         => array('form-row-wide'),
            'label'         => 'Aceito receber e-mails sobre programas de educa????o continuada, via Editora Clannad',
            'required'      => false, // or false
        ),
        $checkout->get_value('billing_termo')
    );
}

//* Update the order meta with field value
add_action('woocommerce_checkout_update_order_meta', 'billing_termo_update_order_meta');
function billing_termo_update_order_meta($order_id)
{
    if ($_POST['billing_termo']){
        update_post_meta($order_id, 'billing_termo', esc_attr($_POST['billing_termo']));
    }
}

//-----------------------------------------------------------
//---------------------  TERMO 2 ----------------------------
//-----------------------------------------------------------

function billing_termo_2_field($checkout)
{

    $checkout = WC()->checkout;
    woocommerce_form_field(
        'billing_termo_2',
        array(
            'type'          => 'checkbox',
            'class'         => array('form-row-wide'),
            'label'         => 'Estou ciente de que este site ?? restrito ao p??blico prescritor e assumo completa responsabilidade pela veracidade das informa????es acima',
            'required'      => true, // or false
        ),
        $checkout->get_value('billing_termo_2')
    );
}
//* Process the checkout

add_action('woocommerce_checkout_process', 'billing_termo_2_checkbox_warning');
/**
 * Alert if checkbox not checked
 */
function billing_termo_2_checkbox_warning()
{
    if (!(int) isset($_POST['billing_termo_2'])) {
        wc_add_notice(__('<strong>?? necess??rio confirmar com o termo de responsabilidade!</strong>'), 'error');
    }
}

//* Update the order meta with field value
add_action('woocommerce_checkout_update_order_meta', 'billing_termo_2_update_order_meta');
function billing_termo_2_update_order_meta($order_id)
{
    if ($_POST['billing_termo_2']){
        update_post_meta($order_id, 'billing_termo_2', esc_attr($_POST['billing_termo_2']));
    }
}

//-----------------------------------------------------------
//--------------  CAMPOS NA EDI????O DOS PEDIDOS --------------
//----------------------------------------------------------- 

add_action('woocommerce_admin_order_data_after_billing_address', 'billing_display_admin_order_meta', 10, 1);
function billing_display_admin_order_meta($order)
{
    $termo = 'N??o';
    if(get_post_meta($order->id, 'billing_termo_2', true) == '1'){
        $termo = 'Ok';
    }

    $termo2 = 'N??o aceito';
    if(get_post_meta($order->id, 'billing_termo', true) == '1'){
        $termo2 = 'Aceito';
    }

    echo '<h3>Dados Adicionais</h3>';
    echo '<p><strong>' . __('CRM') . ':</strong> ' . get_post_meta($order->id, 'billing_crm', true) . ' - ' . get_post_meta($order->id, 'billing_crm_uf', true) . '<br>';
    echo '<strong>' . __('??rea de atua????o') . ':</strong> ' . get_post_meta($order->id, 'billing_area_atuacao', true) . '<br>';
    echo '<strong>' . __('Especialidade M??dica') . ':</strong> ' . get_post_meta($order->id, 'billing_espec_medica', true) . '<br>';
    echo '<strong>' . __('Termo de responsabilidade') . ': </strong> ' . $termo . '<br>';
    echo '<strong>' . __('Aceito receber e-mails sobre programas de educa????o continuada, via Editora Clannad') . ': </strong> ' . $termo2 . '<br>';
    echo '<strong>' . __('Como ficou sabendo?') . '</strong> ' . get_post_meta($order->id, 'billing_sabendo', true) . '</p>';
}


//-----------------------------------------------------------
//---------------------  ADICIONAR CAMPOS -------------------
//-----------------------------------------------------------

add_action('woocommerce_after_checkout_billing_form', 'billing_css', 19);
add_action('woocommerce_after_checkout_billing_form', 'billing_area_atuacao_field', 20);
add_action('woocommerce_after_checkout_billing_form', 'billing_crm_details', 21);
add_action('woocommerce_after_checkout_billing_form', 'billing_crm_uf_field', 22);
add_action('woocommerce_after_checkout_billing_form', 'billing_espec_medica_field', 23);
add_action('woocommerce_after_checkout_billing_form', 'billing_sabendo_field', 24);
add_action('woocommerce_after_checkout_billing_form', 'billing_termo_field', 25);
add_action('woocommerce_after_checkout_billing_form', 'billing_termo_2_field', 26);



//------------------------------------------------------------------
//---------------- API PARA LISTAR TODOS OS PRODUTOS ---------------
//------------------------------------------------------------------

add_action('rest_api_init', 'global_woo');

function global_woo()
{
	register_rest_route(
		'global-login',
		'woocommerce',
		array(
			'methods' => 'GET',
			'callback' => 'global_woo_phrase'
		)
	);
}

function global_woo_phrase()
{
	
	header("Access-Control-Allow-Origin: *");
	
	$param = array( 'limit' => -1 );
	
	$orders = wc_get_orders($param);

	$arr = [];
	
	foreach ( $orders as $order ) {
		$order_id = $order->get_id();
		$order_data = $order->get_data();
		
		array_push($arr, (object)[
			'id' =>  $order_data['id'],
			'nome' => $order_data['billing']['first_name'] . " " . $order_data['billing']['last_name'],
			'email' => $order_data['billing']['email'],
			'uf' => $order_data['billing']['state'],
			'cidade' => $order_data['billing']['city'],
			'crm' => get_post_meta($order_data['id'], 'billing_crm', true),
			'cpf' => get_post_meta($order_data['id'], '_billing_cpf', true),
            'telefone' => get_post_meta($order_data['id'], '_billing_phone', true),
			'area_atuacao' => get_post_meta($order_data['id'], 'billing_area_atuacao', true),
			'status' => $order_data['status'],
			'valor' => $order_data['total'],
			'data' =>  $order_data['date_created']->date('Y-m-d H:i:s'),
			'endereco' => get_post_meta($order_data['id'], '_billing_address_1', true) . ", "  . get_post_meta($order_data['id'], '_billing_number', true) . " "  . get_post_meta($order_data['id'], '_billing_neighborhood', true) . " - CEP: " . get_post_meta($order_data['id'], '_billing_postcode', true)
		]);
		
	}
	
	echo json_encode($arr);
	
};

//------------------------------------------------------------------
//----------------- API MOVER PEDIDO PARA LIXO ---------------------
//------------------------------------------------------------------

add_action('rest_api_init', 'global_order_trash_func');

function global_order_trash_func()
{
	register_rest_route(
		'global-login',
		'order-trash',
		array(
			'methods' => 'POST',
			'callback' => 'global_order_trash'
		)
	);
}

function global_order_trash()
{

	if ($_POST["action"] == 'order_trash') {

        $post_id = $_POST["order"];

        wp_update_post(array(
            'ID'    =>  $post_id,
            'post_status'   =>  'trash'
        ));

        if (is_wp_error($post_id)) {
            $arr = array('message' => $post_id->get_error_messages(), 'code' => 0);
        } else {
            $arr = array('message' => 'O pedido foi movido para a lixeira', 'code' => 1);
        }

		echo json_encode($arr);
	}
};

//------------------------------------------------------------------
//----------------- API PEDIDO MUDAR STATUS ------------------------
//------------------------------------------------------------------

add_action('rest_api_init', 'global_order_edit_func');

function global_order_edit_func()
{
	register_rest_route(
		'global-login',
		'order-edit',
		array(
			'methods' => 'POST',
			'callback' => 'global_order_edit'
		)
	);
}

function global_order_edit()
{

	if ($_POST["action"] == 'order_change') {

        $post_id = $_POST["order"];
		$post_status = $_POST["status"];

		$order = wc_get_order( $post_id );
		
		if (!$post_id) {
            $arr = array('message' => "?? preciso informar o id do pedido", 'code' => 0);
		} else if (!$post_status){
			$arr = array('message' => "?? preciso informar o status do pedido", 'code' => 0);
        } else {
			$order->update_status( $post_status );
            $arr = array('message' => 'O pedido foi atualizado', 'code' => 1);
        }

		echo json_encode($arr);
	}
};