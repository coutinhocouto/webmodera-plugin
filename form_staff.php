<?php

function global_cadastra_staff_form()
{
    if (!empty($_POST)) {

        $area_atuacao = "";
        $evento = $_POST["evento"];
        $nome = $_POST["nome"];
        $email = strtolower($_POST["email"]);
        $uf = $_POST["uf"];
        $cidade = $_POST["cidade"];
        $telefone = $_POST["telefone"];
        $cpf = $_POST["cpf"];
        $crm = "";
        $crm_uf = "";
        $especialidade = "g";
        $codigo = "";
        $pagante = "0";
        $produto = "";
        $valor = "";
        $sabendo = "";
        $termo = $_POST["termo"];
        $senha = $_POST["password"];
        $cargo = $_POST["cargo"];
        $status = "1";

        $url = 'https://4k5zxy0dui.execute-api.us-east-1.amazonaws.com/webmodera/webhook';

        $userdata = array(
            'user_login' => $email,
            'user_pass' => $senha,
            'user_email' => $email,
            'first_name' => $nome,
            'show_admin_bar_front' => false,
            'role' => "staff",
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
            update_user_meta($user_id, 'billing_cpf', $cpf);

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
            if(get_option('assunto_staff_global')){
                $subject = get_option('assunto_staff_global');
                $body = get_option('body_staff_global');
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

            #cadastramento input[type=submit]:disabled {
                background-color: #666;
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

            .g-recaptcha {
                display: flex;
                align-items: center;
                justify-content: center;
                margin-bottom: 20px;
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

        <?php if(get_option('captcha_key')) { ?>
            <script src="https://www.google.com/recaptcha/api.js" async defer></script>
        <?php } ?>
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

                $('input[name=telefone]').mask('(00) 00000-0000');

                $('select[name=uf]').on('change', function() {

                    var uf = $(this).val();
                    var url = "https://4k5zxy0dui.execute-api.us-east-1.amazonaws.com/webmodera/municipios/" + uf;
                    $("select[name=cidade] option").remove();
                    $("select[name=cidade]").append(new Option("", "Selecione"));

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
                        $(".p1").css('display', 'inline-block');
                        $("#codigo_errado").remove();
                        $('#email input').attr("readonly", true);
                    } else {
                        $(".p1").hide();
                        $("#codigo_errado").remove();
                        $("<span id='codigo_errado' style='background: #f00; color: #fff; padding: 10px; display: block; margin-top: 0px; border-radius: 3px; font-weight: 700;'>Este email não é válido!</span>").insertAfter("#valida-email");
                    }

                    return false; 

                });

                $('#cadastramento').on('submit', function(e) {
                    const cpf = $('#cpf-input').val();
                    if (!validateCPF(cpf)) {
                        $('#cpf').append('<span id="cpf-error" style="background: #f00; color: #fff; padding: 5px; display: block; margin-top: 5px; border-radius: 3px;">CPF inválido.</span>');
                        e.preventDefault();
                    }
                });

            });

            function recaptchaCallback() {
                document.getElementById('cadastro-submit').disabled = false;
            }

            function expiredCallback() {
                document.getElementById('cadastro-submit').disabled = true;
            }

            function validateCPF(cpf) {
                cpf = cpf.replace(/[^\d]+/g, '');
                if (cpf.length !== 11 || /^(\d)\1+$/.test(cpf)) {
                    return false;
                }
                let sum, remainder;
                sum = 0;
                for (let i = 1; i <= 9; i++) {
                    sum += parseInt(cpf.substring(i - 1, i)) * (11 - i);
                }
                remainder = (sum * 10) % 11;
                if (remainder === 10 || remainder === 11) remainder = 0;
                if (remainder !== parseInt(cpf.substring(9, 10))) return false;
                sum = 0;
                for (let i = 1; i <= 10; i++) {
                    sum += parseInt(cpf.substring(i - 1, i)) * (12 - i);
                }
                remainder = (sum * 10) % 11;
                if (remainder === 10 || remainder === 11) remainder = 0;
                if (remainder !== parseInt(cpf.substring(10, 11))) return false;
                return true;
            }

            document.addEventListener('DOMContentLoaded', function() {
                const cpfInput = document.getElementById('cpf-input');

                cpfInput.addEventListener('input', function(event) {
                    let value = event.target.value;

                    // Remove any non-digit characters
                    value = value.replace(/\D/g, '');

                    // Add periods and dash at the appropriate places
                    if (value.length > 3 && value.length <= 6) {
                        value = value.replace(/(\d{3})(\d+)/, '$1.$2');
                    } else if (value.length > 6 && value.length <= 9) {
                        value = value.replace(/(\d{3})(\d{3})(\d+)/, '$1.$2.$3');
                    } else if (value.length > 9) {
                        value = value.replace(/(\d{3})(\d{3})(\d{3})(\d{2})/, '$1.$2.$3-$4');
                    }

                    // Update the input value with the formatted value
                    event.target.value = value;
                });
            });
        </script>

        <form action="<?php echo $_SERVER['REQUEST_URI'] ?>" method="post" id="cadastramento">

            <input type="hidden" value="<?php echo get_option('evento_global'); ?>" name="evento" />

            <div class="wb-70" id="email">
                <label for="nome">E-mail *</label>
                <input type="email" name="email" required />
            </div>

            <div class="wb-30" id="valida-email">
                <button>Validar E-mail</button>
            </div>

            <div class="wb-100 p1" id="cpf">
                <label for="cpf-input">CPF *</label>
                <input type="text" name="cpf" maxlength="14" id="cpf-input" required />
            </div>

            <div class="wb-100 p1">
                <label for="nome">Nome Completo *</label>
                <input type="text" name="nome" required />
            </div>

            <div class="wb-50 p1">
                <label form="uf">Estado</label>
                <select name="uf" required>
                    <option>Selecione</option>
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

            <div class="wb-50 p1">
                <label form="cidade">Cidade *</label>
                <select name="cidade" required>
                    <option></option>
                </select>
            </div>

            <div class="wb-50 p1">
                <label form="nome">Telefone (com ddd) *</label>
                <input type="text" name="telefone" required />
            </div>

            <div class="wb-50 p1">
                <label form="nome">Cargo</label>
                <input type="text" name="cargo" id="cargo" required />
            </div>

            <div class="wb-100 p1">
                <input type="checkbox" name="termo" value="1">
                <label form="termo">Aceito receber e-mails sobre programas de educação continuada, via Editora Clannad</label>
            </div>

            <div class="wb-100 p1">
                <input type="checkbox" name="termo2" value="1" required>
                <label form="termo2">Estou ciente de que este site é restrito ao público prescritor e assumo completa responsabilidade pela veracidade das informações acima *</label>
            </div>

            <div class="wb-50 p1">
                <label form="nome">Senha</label>
                <input type="password" name="password" id="password" required />
            </div>

            <div class="wb-50 p1">
                <label form="nome">Confirme sua senha</label>
                <input type="password" name="cpassword" required />
            </div>

            <div class="wb-100 p1">
                <?php if(get_option('captcha_key')) { ?>
                    <div class="g-recaptcha" data-sitekey="<?php echo get_option('captcha_key'); ?>"  data-expired-callback="expiredCallback" data-callback="recaptchaCallback"></div>
                <?php } ?>
                <input type="submit" value="Cadastre-se" id="cadastro-submit" <?php if(get_option('captcha_key')) echo 'disabled' ?> />
            </div>
        </form>

<?php }
}

add_shortcode('cadastro_staff', 'global_cadastra_staff_form');
