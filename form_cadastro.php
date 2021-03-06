<?php

function global_cadastra_form()
{
    if (!empty($_POST)) {

        $area_atuacao = $_POST["area_atuacao"];
        $evento = $_POST["evento"];
        $nome = $_POST["nome"];
        $email = strtolower($_POST["email"]);
        $uf = $_POST["uf"];
        $cidade = $_POST["cidade"];
        $telefone = $_POST["telefone"];
        $cpf = "";
        $crm = $_POST["crm"];
        $crm_uf = $_POST["crm_uf"];
        $especialidade = $_POST["especialidade"];
        $codigo = $_POST["codigo"];
        $pagante = "0";
        $produto = "";
        $valor = "";
        $sabendo = $_POST["sabendo"];
        $termo = $_POST["termo"];
        $senha = $_POST["password"];
        $instituicao = $_POST["instituicao"];
        $cargo = $_POST["cargo"];
		$extra1 = $_POST["extra1"];
		$extra2 = $_POST["extra2"];
		$extra3 = $_POST["extra3"];
		$extra4 = $_POST["extra4"];

        if ($area_atuacao == "Medicina") {
            $role = "medicos";
            $status = "0";
        } else if ($area_atuacao == "Staff") {
            $role = "staff";
            $status = "1";
        } else {
            $role = "nao_medicos";
            $status = "0";
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

        $codigos_arr = json_decode(get_option('codigos_global_new'), true);
        foreach($codigos_arr as &$a){
            if($a['codigo'] == $_POST["codigo"]){
                $a['usados'] = $a['usados'] + 1;
            }
        }
        $codigos_new = json_encode($codigos_arr);
        
        $url = 'https://4k5zxy0dui.execute-api.us-east-1.amazonaws.com/webmodera/webhook';

        $userdata = array(
            'user_login' => $email,
            'user_pass' => $senha,
            'user_email' => $email,
            'first_name' => $nome,
            'show_admin_bar_front' => false,
            'role' => $role,
        );

        //----
        
		global $wpdb;
		$result = $wpdb->get_results ( "
			SELECT count(umeta_id) as qtd
			FROM " . $wpdb->prefix . "usermeta
			WHERE `meta_key` LIKE 'billing_codigo' AND `meta_value` LIKE '" . $codigo . "'
		");
		$limit = $result[0]->qtd;
		
		$str = get_option('codigos_global_new');
		$arr = json_decode($str);

		foreach ( $arr as $obj ){
			if ( $obj->codigo == $codigo ) {
				$qtd = $obj->qtd;
			}
		}
		$total = $qtd - $limit;

        if(get_option('categoria_global') == "1") {
            $total = 1;
        }
		
		if($total <= 0) { 
			echo '<label class="error" for="email">Este c??digo n??o ?? v??lido ou est?? com o seu limite de uso excedido!</label>';
		} else{
			$user_id = wp_insert_user($userdata);

			if (is_wp_error($user_id)) {
				echo '<label class="error" for="email">' . $user_id->get_error_message() . '</label>';
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
				update_user_meta($user_id, 'billing_extra1', $extra1);
				update_user_meta($user_id, 'billing_extra2', $extra2);
				update_user_meta($user_id, 'billing_extra3', $extra3);
				update_user_meta($user_id, 'billing_extra4', $extra4);

				update_option('codigos_global_new', $codigos_new);

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
					"instituicao" => $instituicao,
					"cargo" => $cargo,
					"extra1" => $extra1,
					"extra2" => $extra2,
					"extra3" => $extra3,
					"extra4" => $extra4
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

				//--------------------------
				//------- ENVIA E-MAIL -----
				//--------------------------

				$to = $email;
				$headers = array('Content-Type: text/html; charset=UTF-8');

				if ($area_atuacao == "Medicina") {
					if(get_option('assunto_convidado_global')){
						$subject = get_option('assunto_convidado_global');
						$body = get_option('body_convidado_global');
						wp_mail( $to, $subject, $body, $headers );
					}
				} else if ($area_atuacao == "Staff") {
					if(get_option('assunto_staff_global')){
						$subject = get_option('assunto_staff_global');
						$body = get_option('body_staff_global');
						wp_mail( $to, $subject, $body, $headers );
					}
				} else {
					if(get_option('assunto_convidado_global')){
						$subject = get_option('assunto_convidado_global');
						$body = get_option('body_convidado_global');
						wp_mail( $to, $subject, $body, $headers );
					}
				}


				//$user = get_userdatabylogin( $email );
				//$user_id = $user->ID;
				//wp_set_current_user( $user_id, $user_login );
				//wp_set_auth_cookie( $user_id );
				//do_action( 'wp_login', $user_login );

				echo '<script>window.location.replace("' . get_option('inscrito_global') . '");</script>';

			}
			//----
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

            #cadastramento .md1,
            #cadastramento .md2,
            #cadastramento .nmd,
            #cadastramento .staff,
            #cadastramento .staff2,
            #cadastramento .publico {
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
        <script src='https://www.google.com/recaptcha/api.js' async defer></script>

        <script>
            jQuery(document).ready(function($) {

                $('select[name=area_atuacao]').on('change', function() {

                    if ($(this).val() == "Medicina") {
                        $(".nmd, .staff, .staff2").hide();
                        $('label[for=crm_uf]').html('Estado do CRM *');
                        $('label[for=crm]').html('CRM (somente n??meros) *');
                        $(".md1").css('display', 'inline-block');

                    <?php
                        if (get_option('tem_publico_global') == '1') {

                            $areas = explode(",", get_option('publico_atuacao_global'));
                            foreach ($areas as $area) {

                                echo '} else if ($(this).val() == "' . ltrim($area) . '") {';
                                echo '$(".nmd, .md1, .md2, .staff2").hide();';
                                echo '$(".staff, .staff2, .publico").css("display", "inline-block");';
                                echo '$("#valida-email").hide();';

                            }

                        }
                    ?>


                    } else if ($(this).val() == "Staff") {
                        $(".nmd, .md1, .md2, .staff2, .publico").hide();
                        $(".staff").css('display', 'inline-block');
                    } else if ($(this).val() == "Outros") {
                        $(".nmd, .md1, .md2, .staff2, .publico").hide();
                        $(".staff, .staff2").css('display', 'inline-block');
                        $("#valida-email").hide();
                    } else if ($(this).val() == "") {
                        $(".md, .staff, .md1, .md2, .staff2, .publico").hide();
                    } else {
                        $(".md1, .md2, .staff, .staff2, .publico").hide();
                        $('label[for=crm_uf]').html('Estado do conselho *');
                        $('label[for=crm]').html('N??mero do conselho (somente n??meros) *');
                        $(".nmd").css('display', 'inline-block');
                    }

                    $('input[name=nome]').attr("readonly", false);
                    $('input[type=text]:not([name=codigo]), input[type=email], input[type=password]').val('');
                    $(':checkbox, :radio').prop('checked', false);
                    $('#cadastramento select[name=crm_uf], #cadastramento select[name=especialidade], #cadastramento select[name=uf], #cadastramento select[name=sabendo]').val('');

                });

                $('select[name=crm_uf]').on('change', function() {

                    if ($(this).val() == "RJ" && $("select[name=area_atuacao]").val() == "Medicina") {
                        $('<small id="crm_rj">N??o inserir o 52 a frente do seu registro</small>').insertBefore($('#crm_num'));
                    } else {
                        $('#crm_rj').remove();
                    }

                });

                $("#cadastramento").validate({
                    rules: {
                        password: {
                            minlength: 5
                        },
                        cpassword: {
                            minlength: 5,
                            equalTo: "#password"
                        },
                        hiddenRecaptcha: {
                            required: function () {
                                if (grecaptcha.getResponse() == '') {
                                    return true;
                                } else {
                                    return false;
                                }
                            }
                        }
                    }
                });

                jQuery.extend(jQuery.validator.messages, {
                    required: "Este campo ?? obrigat??rio.",
                    remote: "Please fix this field.",
                    email: "Informe um endere??o de e-mail v??lido.",
                    url: "Please enter a valid URL.",
                    date: "Please enter a valid date.",
                    dateISO: "Please enter a valid date (ISO).",
                    number: "Please enter a valid number.",
                    digits: "Please enter only digits.",
                    creditcard: "Please enter a valid credit card number.",
                    equalTo: "A confirma????o da senha precisa ser igual a senha.",
                    accept: "Please enter a value with a valid extension.",
                    maxlength: jQuery.validator.format("Please enter no more than {0} characters."),
                    minlength: jQuery.validator.format("A senha precisa ter pelo menos {0} caracteres."),
                    rangelength: jQuery.validator.format("Please enter a value between {0} and {1} characters long."),
                    range: jQuery.validator.format("Please enter a value between {0} and {1}."),
                    max: jQuery.validator.format("Please enter a value less than or equal to {0}."),
                    min: jQuery.validator.format("Please enter a value greater than or equal to {0}.")
                });

                $('#valida_crm').click(function() {

                    var uf = $('select[name=crm_uf]').val();
                    var crm = $('input[name=crm]').val();
                    var url = "https://4k5zxy0dui.execute-api.us-east-1.amazonaws.com/webmodera/check-crm/" + uf + "/" + crm;

                    $.getJSON(url, function(result) {

                        if (result.length) {

                            $.each(result, function(i, field) {

                                $('.semi-error').remove();

                                if (field.situacao == 'Ativo') {

                                    $(".nmd, .staff").hide();
                                    $(".md2, .md1").css('display', 'inline-block');
                                    $('input[name=nome]').val(field.nome);
                                    $('input[name=nome]').attr("readonly", true);

                                } else {
                                    $('#crm_error').html('<div class="validation_error semi-error">Somente m??dicos com cadastro ativo podem se cadastrar, verifique o crm e o estado informado para prosseguir.</div>');
                                }

                            });

                        } else {
                            $('#crm_error').html('<div class="validation_error semi-error">Somente m??dicos podem se cadastrar, verifique o crm e o estado informado para prosseguir.</div>');
                        }

                    });

                    return false;
                });

                $('input[name=telefone]').mask('(00) 00000-0000');

                $('select[name=uf]').on('change', function() {

                    var uf = $(this).val();
                    var url = "https://4k5zxy0dui.execute-api.us-east-1.amazonaws.com/webmodera/municipios/" + uf;
                    $("select[name=cidade] option").remove();
                    $("select[name=cidade]").append(new Option("", ""));

                    $.getJSON(url, function(result) {
                        if (result.length) {
                            $.each(result, function(i, field) {
                                $("select[name=cidade]").append(new Option(field.cidade, field.cidade));
                            });
                        } else {
                            console.log('ERRO!!')
                        }

                    });


                });

                var dominios = [];

                <?php

                $dominios = explode(",", get_option('dominios_staff_global'));

                foreach ($dominios as $dominio) {

                    echo 'dominios.push("' . ltrim($dominio) . '");';
                }
                ?>

                $('#valida-email button').click(function() {

                    var noarroba = $('#email input').val();
                    var semarroba = noarroba.substr(noarroba.indexOf("@") + 1)

                    if ($.inArray(semarroba.toLowerCase(), dominios) >= 0) {
                        $(".staff2").css('display', 'inline-block');
                        $("#codigo_errado").remove();
                        $('#email input').attr("readonly", true);
                    } else {
                        $(".p1").hide();
                        $("#codigo_errado").remove();
                        $("<span id='codigo_errado' style='background: #f00; color: #fff; padding: 10px; display: block; margin-top: 0px; border-radius: 3px; font-weight: 700;'>Este email n??o ?? v??lido!</span>").insertAfter("#valida-email");
                    }

                    return false;

                });

            });
        </script>

        <form action="<?php echo $_SERVER['REQUEST_URI'] ?>" method="post" id="cadastramento">

            <input type="hidden" value="<?php echo get_option('evento_global'); ?>" name="evento" />

            <?php if (get_option('categoria_global') == "2" || get_option('categoria_global') == "3") { ?>
				<textarea style="display: none" id="codigos_new"><?php echo get_option('codigos_global_new'); ?></textarea>
                <script>
                    jQuery(document).ready(function($) {

                        $("#area_atuacao").hide();
                        var codigos = $('#codigos_new').val();

                        $('#valida-codigo button').click(function() {
							
							var url = "<?php echo get_site_url() ?>" + "/wp-json/codigos/codigo?cod=" + $('input[name=codigo]').val().toUpperCase();
							var cod = $('input[name=codigo]').val().toUpperCase();
							var codigo = JSON.parse(codigos).filter(({codigo}) => codigo === cod);
							var qtd = codigo[0].qtd;
							
							$.get(url, function(data, status){
								var limit = qtd - data.usos;
								if(limit > 0) {
									$("#area_atuacao").show();
									$("#codigo_errado").remove();
									$('input[name=codigo]').attr("readonly", true);
								} else {
									$("#area_atuacao").hide();
									$("#codigo_errado").remove();
									$("<span id='codigo_errado' style='background: #f00; color: #fff; padding: 10px; display: block; margin-top: 0px; border-radius: 3px; font-weight: 700;'>Este c??digo n??o ?? v??lido ou est?? com limite de usos exedido!</span>").insertAfter("#valida-codigo");
								}
							});
							
                            return false;

                        });

                    });
                </script>

                <div class="wb-70">
                    <label for="codigo">C??digo *</label>
                    <input type="text" name="codigo" required />
                </div>

                <div class="wb-30" id="valida-codigo">
                    <button>Validar C??digo</button>
                </div>

            <?php } ?>

            <div class="wb-100" id="area_atuacao">
                <label for="area_atuacao">??rea de atua????o *</label>

                <select name="area_atuacao" required>
                    <option></option>

                    <?php
                        if (get_option('tem_medico_global') == '1') {
                            echo '<option value="Medicina">Medicina</option>';
                        }
                    ?>

                    <?php
                        if (get_option('tem_nao_medico_global') == '1') {

                            $areas = explode(",", get_option('nao_medico_atuacao_global'));
                            foreach ($areas as $area) {
                                echo '<option value="' . ltrim($area) . '">' . ltrim($area) . '</option>';
                            }
                        }
                    ?>

                    <?php
                        if (get_option('categoria_global') == '1') {
                            echo '<option value="Staff">Staff</option>';
                        }
                    ?>

                    <?php
                        if (get_option('tem_publico_global') == '1') {

                            $areas = explode(",", get_option('publico_atuacao_global'));
                            foreach ($areas as $area) {
                                echo '<option value="' . ltrim($area) . '">' . ltrim($area) . '</option>';
                            }
                        }
                    ?>

                </select>
            </div>

            <div class="wb-33 md1 nmd crm_uf">
                <label for="crm_uf">Estado do CRM *</label>
                <select name="crm_uf" required>
                    <option></option>
                    <option value="AC">AC</option>
                    <option value="AL">AL</option>
                    <option value="AP">AP</option>
                    <option value="AM">AM</option>
                    <option value="BA">BA</option>
                    <option value="CE">CE</option>
                    <option value="DF">DF</option>
                    <option value="ES">ES</option>
                    <option value="GO">GO</option>
                    <option value="MA">MA</option>
                    <option value="MT">MT</option>
                    <option value="MS">MS</option>
                    <option value="MG">MG</option>
                    <option value="PA">PA</option>
                    <option value="PB">PB</option>
                    <option value="PR">PR</option>
                    <option value="PE">PE</option>
                    <option value="PI">PI</option>
                    <option value="RJ">RJ</option>
                    <option value="RN">RN</option>
                    <option value="RS">RS</option>
                    <option value="RO">RO</option>
                    <option value="RR">RR</option>
                    <option value="SC">SC</option>
                    <option value="SP">SP</option>
                    <option value="SE">SE</option>
                    <option value="TO">TO</option>
                </select>
            </div>

            <div class="wb-33 md1 nmd crm">
                <label for="crm">CRM (somente n??meros) *</label>
                <input type="text" name="crm" id="crm_num" required />
            </div>

            <div class="wb-33 md1">
                <button id="valida_crm">Validar CRM</button>
            </div>

            <div class="wb-100" id="crm_error">

            </div>

            <div class="wb-70 md2 nmd staff" id="email">
                <label for="nome">E-mail *</label>
                <input type="email" name="email" required />
            </div>

            <div class="wb-30 staff" id="valida-email">
                <button>Validar E-mail</button>
            </div>

            <div class="wb-100 md2 nmd staff2">
                <label for="nome">Nome Completo *</label>
                <input type="text" name="nome" required />
            </div>

            <div class="wb-100 md2">
                <label for="especialidade">Especialidade *</label>
                <select name="especialidade" required>
                    <option></option>
                    <option value="Acupuntura">Acupuntura</option>
                    <option value="Alergia e imunologia">Alergia e imunologia</option>
                    <option value="Alergia e imunologia pedi??trica">Alergia e imunologia pedi??trica</option>
                    <option value="Anestesiologia">Anestesiologia</option>
                    <option value="Angiologia">Angiologia</option>
                    <option value="Cardiologia">Cardiologia</option>
                    <option value="Cardiologia pedi??trica">Cardiologia pedi??trica</option>
                    <option value="Cirurgia bari??trica">Cirurgia bari??trica</option>
                    <option value="Cirurgia cardiovascular">Cirurgia cardiovascular</option>
                    <option value="Cirurgia da m??o">Cirurgia da m??o</option>
                    <option value="Cirurgia de cabe??a e pesco??o">Cirurgia de cabe??a e pesco??o</option>
                    <option value="Cirurgia do aparelho digestivo">Cirurgia do aparelho digestivo</option>
                    <option value="Cirurgia geral">Cirurgia geral</option>
                    <option value="Cirurgia oncol??gica">Cirurgia oncol??gica</option>
                    <option value="Cirurgia pedi??trica">Cirurgia pedi??trica</option>
                    <option value="Cirurgia pl??stica">Cirurgia pl??stica</option>
                    <option value="Cirurgia tor??cica">Cirurgia tor??cica</option>
                    <option value="Cirurgia vascular">Cirurgia vascular</option>
                    <option value="Cl??nica m??dica">Cl??nica m??dica</option>
                    <option value="Coloproctologia">Coloproctologia</option>
                    <option value="Dermatologia">Dermatologia</option>
                    <option value="Dor">Dor</option>
                    <option value="Endocrinologia e metabologia">Endocrinologia e metabologia</option>
                    <option value="Endocrinologia pedi??trica">Endocrinologia pedi??trica</option>
                    <option value="Endoscopia">Endoscopia</option>
                    <option value="Gastroenterologia">Gastroenterologia</option>
                    <option value="Gastroenterologia pedi??trica">Gastroenterologia pedi??trica</option>
                    <option value="Gen??tica m??dica">Gen??tica m??dica</option>
                    <option value="Geriatria">Geriatria</option>
                    <option value="Ginecologia e obstetr??cia">Ginecologia e obstetr??cia</option>
                    <option value="Hematologia e hemoterapia">Hematologia e hemoterapia</option>
                    <option value="Hematologia e hemoterapia pedi??trica">Hematologia e hemoterapia pedi??trica</option>
                    <option value="Hepatologia">Hepatologia</option>
                    <option value="Homeopatia">Homeopatia</option>
                    <option value="Infectologia">Infectologia</option>
                    <option value="Infectologia pedi??trica">Infectologia pedi??trica</option>
                    <option value="Mastologia">Mastologia</option>
                    <option value="Medicina de emerg??ncia">Medicina de emerg??ncia</option>
                    <option value="Medicina de fam??lia e comunidade">Medicina de fam??lia e comunidade</option>
                    <option value="Medicina de tr??fego">Medicina de tr??fego</option>
                    <option value="Medicina do trabalho">Medicina do trabalho</option>
                    <option value="Medicina esportiva">Medicina esportiva</option>
                    <option value="Medicina f??sica e reabilita????o">Medicina f??sica e reabilita????o</option>
                    <option value="Medicina intensiva">Medicina intensiva</option>
                    <option value="Medicina intensiva pedi??trica">Medicina intensiva pedi??trica</option>
                    <option value="Medicina legal e per??cia m??dica">Medicina legal e per??cia m??dica</option>
                    <option value="Medicina nuclear">Medicina nuclear</option>
                    <option value="Medicina preventiva e social">Medicina preventiva e social</option>
                    <option value="Nefrologia">Nefrologia</option>
                    <option value="Nefrologia pedi??trica">Nefrologia pedi??trica</option>
                    <option value="Neurocirurgia">Neurocirurgia</option>
                    <option value="Neurologia">Neurologia</option>
                    <option value="Neurologia pedi??trica">Neurologia pedi??trica</option>
                    <option value="Nutrologia">Nutrologia</option>
                    <option value="Oftalmologia">Oftalmologia</option>
                    <option value="Oncologia cl??nica">Oncologia cl??nica</option>
                    <option value="Oncologia pedi??trica">Oncologia pedi??trica</option>
                    <option value="Ortopedia e traumatologia">Ortopedia e traumatologia</option>
                    <option value="Otorrinolaringologia">Otorrinolaringologia</option>
                    <option value="Patologia">Patologia</option>
                    <option value="Patologia cl??nica/medicina laboratorial">Patologia cl??nica/medicina laboratorial</option>
                    <option value="Pediatria">Pediatria</option>
                    <option value="Pneumologia">Pneumologia</option>
                    <option value="Pneumologia pedi??trica">Pneumologia pedi??trica</option>
                    <option value="Psiquiatria">Psiquiatria</option>
                    <option value="Radiologia e diagn??stico por imagem">Radiologia e diagn??stico por imagem</option>
                    <option value="Radioterapia">Radioterapia</option>
                    <option value="Reumatologia">Reumatologia</option>
                    <option value="Reumatologia pedi??trica">Reumatologia pedi??trica</option>
                    <option value="Urologia">Urologia</option>
                </select>
            </div>

            <div class="wb-50 md2 nmd staff2">
                <label form="uf">Estado</label>
                <select name="uf" required>
                    <option></option>
                    <option value="AC">AC</option>
                    <option value="AL">AL</option>
                    <option value="AP">AP</option>
                    <option value="AM">AM</option>
                    <option value="BA">BA</option>
                    <option value="CE">CE</option>
                    <option value="DF">DF</option>
                    <option value="ES">ES</option>
                    <option value="GO">GO</option>
                    <option value="MA">MA</option>
                    <option value="MT">MT</option>
                    <option value="MS">MS</option>
                    <option value="MG">MG</option>
                    <option value="PA">PA</option>
                    <option value="PB">PB</option>
                    <option value="PR">PR</option>
                    <option value="PE">PE</option>
                    <option value="PI">PI</option>
                    <option value="RJ">RJ</option>
                    <option value="RN">RN</option>
                    <option value="RS">RS</option>
                    <option value="RO">RO</option>
                    <option value="RR">RR</option>
                    <option value="SC">SC</option>
                    <option value="SP">SP</option>
                    <option value="SE">SE</option>
                    <option value="TO">TO</option>
                </select>
            </div>

            <div class="wb-50 md2 nmd staff2">
                <label form="cidade">Cidade *</label>
                <select name="cidade" required>
                    <option></option>
                </select>
            </div>

            <div class="wb-100 md2 nmd staff2">
                <label form="nome">Telefone (com ddd) *</label>
                <input type="text" name="telefone" required />
            </div>

            <?php if(get_option('mostra_sabendo_global') == "1") { ?>
                <div class="wb-100 md2 nmd">
                    <label form="sabendo">Como ficou sabendo? <?php if(get_option('sabendo_obr_global') == "1") { echo '*'; }?></label>
                    <select name="sabendo" <?php if(get_option('sabendo_obr_global') == "1") { echo 'required'; }?>>
                        <option></option>
                        <option value="E-mail marketing">E-mail marketing</option>
                        <option value="Redes sociais">Redes sociais</option>
                        <option value="Sociedade M??dica">Sociedade M??dica</option>
                        <option value="Representante">Representante</option>
                        <option value="Outros">Outros</option>
                    </select>
                </div>
            <?php } ?>

            <div class="wb-50 publico nmd">
                <label form="nome">Institui????o</label>
                <input type="text" name="instituicao" id="instituicao" required />
            </div>

            <div class="wb-50 staff2">
                <label form="nome">Cargo</label>
                <input type="text" name="cargo" id="cargo" required />
            </div>
			
			<?php if(get_site_url() == "https://www.diabetesnoalvo.com.br") { ?>
			
                <div class="wb-100 md2 nmd staff2">
                    <a href="https://www.diabetesnoalvo.com.br/politica-de-privacidade/" target="_blank">Confira nossa Pol??tica de Privacidade</a>
                </div>

				<div class="wb-100 md2 nmd staff2">
					<input type="checkbox" name="termo2" value="1" required>
					<label form="termo2">Confirmo que tenho mais de 18 anos e aceito a <a href="https://www.diabetesnoalvo.com.br/politica-de-privacidade/" target="_blank">pol??tica de privacidade</a> de dados e os <a href="https://www.diabetesnoalvo.com.br/termos-de-uso/" target="_blank">termos de uso</a> *</label>
				</div>

				<div class="wb-100 md2 nmd staff2">
					<input type="checkbox" name="termo3" value="1" required>
					<label form="termo2">Li e concordo com o uso e acesso pela Abbott dos meus dados pessoais, bem como o compartilhamento destes dados pela Abbott aos seus parceiros indicados na <a href="https://www.diabetesnoalvo.com.br/politica-de-privacidade/" target="_blank">Pol??tica de privacidade de dados.</a> *</label>
				</div>

				<div class="wb-100 md2 nmd staff2">
					<input type="checkbox" name="termo4" value="1" required>
					<label form="termo2">Concordo com a transfer??ncia internacional dos meus dados pessoais, conforme estipulado na  <a href="https://www.diabetesnoalvo.com.br/politica-de-privacidade/" target="_blank">Pol??tica de privacidade de dados.</a>. *</label>
				</div>

				<div class="wb-100 md2 nmd staff2">
					<input type="checkbox" name="termo" value="1">
					<label form="termo">Aceito receber e-mails e mensagens de texto com conte??do informativo, question??rios de pesquisa e promo????es da Abbott e Clannad.</label>
				</div>
			
			<?php } else if(get_site_url() == "https://www.educacaoamryt.com.br") { ?>
			
                <div class="wb-100 md2 nmd">
					<input type="checkbox" name="termo2" value="1" required>
					<label form="termo2">Estou ciente de que este site ?? restrito ao p??blico prescritor e assumo completa responsabilidade pela veracidade das informa????es acima *</label>
				</div>

				<div class="wb-100 md2 nmd">
					<input type="checkbox" name="extra1" value="1">
					<label form="termo2">Aceito receber e-mails com comunicados da Amryt</label>
				</div>

				<div class="wb-100 md2 nmd">
					<input type="checkbox" name="extra2" value="1">
					<label form="termo2">Aceito receber e-mails sobre programas de educa????o continuada, via Editora Clannad</label>
				</div>
			
			<?php } else { ?> 
            
				<div class="wb-100 md2 nmd staff2">
					<input type="checkbox" name="termo" value="1">
					<label form="termo">Aceito receber e-mails sobre programas de educa????o continuada, via Editora Clannad</label>
				</div>

				<div class="wb-100 md2 nmd">
					<input type="checkbox" name="termo2" value="1" required>
					<label form="termo2">Estou ciente de que este site ?? restrito ao p??blico prescritor e assumo completa responsabilidade pela veracidade das informa????es acima *</label>
				</div>

			<?php } ?>

            <div class="wb-100 md2 nmd staff2">
                <strong>
                    Para a seguran??a dos seus dados, recomenda-se o uso de senhas fortes. Utilize caracteres em caixa alta, em caixa baixa, letras, n??meros e s??mbolos.
				</strong>
            </div>

            <div class="wb-50 md2 nmd staff2">
                <label form="nome">Senha</label>
                <input type="password" name="password" id="password" required />
            </div>

            <div class="wb-50 md2 nmd staff2">
                <label form="nome">Confirme sua senha</label>
                <input type="password" name="cpassword" required />
            </div>
            
            <div class="g-recaptcha wb-100 md2 nmd staff2" data-sitekey="6LfngUEgAAAAAOVYtKUXYixRb-YXSskLO3ArHOIB"></div>
            <input type="text" style="visibility: hidden;" class="hiddenRecaptcha required" name="hiddenRecaptcha" id="hiddenRecaptcha">

            <div class="wb-100 md2 nmd staff2">
                <input type="submit" id="cadastro-submit" value="Cadastre-se" />
            </div>
        </form>

<?php }
}

add_shortcode('cadastro', 'global_cadastra_form');
