<?php

function global_cadastra_form()
{
    if (!empty($_POST)) {

        $area_atuacao = $_POST["area_atuacao"];
        $evento = $_POST["evento"];
        $nome = $_POST["nome"];
        $email = $_POST["email"];
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

        if ($area_atuacao == "Medicina") {
            $role = "medicos";
        } else if ($area_atuacao == "Staff") {
            $role = "staff";
        } else {
            $role = "nao_medicos";
        }

        $url = 'https://4k5zxy0dui.execute-api.us-east-1.amazonaws.com/webmodera/webhook';

        $userdata = array(
            'user_login' => $email,
            'user_pass' => $senha,
            'user_email' => $email,
            'first_name' => $nome,
            'show_admin_bar_front' => false,
            'role' => $role,
        );

        //print_r($userdata);

        $user_id = wp_insert_user($userdata);

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
            "profissao" => $area_atuacao
        );

        $postdata = json_encode($data);

        print_r($data);

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

        $creds = array(
            'user_login'    => $email,
            'user_password' => $senha,
            'remember'      => true
        );
        $user = wp_signon($creds, false);

        if (is_wp_error($user)) {
            echo $user->get_error_message();
        }

        echo '<script>window.location.replace("' . get_option('inscrito_global') . '");</script>';

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
                width: 33%;
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
            #cadastramento .staff {
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

                $('select[name=area_atuacao]').on('change', function() {

                    if ($(this).val() == "Medicina") {
                        $(".nmd, .staff").hide();
                        $('label[for=crm_uf]').html('Estado do CRM *');
                        $('label[for=crm]').html('CRM (somente números) *');
                        $(".md1").css('display', 'inline-block');
                    } else if ($(this).val() == "Staff") {
                        $(".nmd, .md1, .md2").hide();
                        $(".staff").css('display', 'inline-block');
                    } else if ($(this).val() == "") {
                        $(".md, .staff, .md1, .md2").hide();
                    } else {
                        $(".md1, .md2, .staff").hide();
                        $('label[for=crm_uf]').html('Estado do conselho *');
                        $('label[for=crm]').html('Número do conselho (somente números) *');
                        $(".nmd").css('display', 'inline-block');
                    }
                    console.log($(this).val());

                    $('input[name=nome]').attr("readonly", false);
                    $('input[type=text]:not([name=codigo]), input[type=email], input[type=password]').val('');
                    $(':checkbox, :radio').prop('checked', false);
                    $('#cadastramento select[name=crm_uf], #cadastramento select[name=especialidade], #cadastramento select[name=uf], #cadastramento select[name=sabendo]').val('');

                });

                $('select[name=crm_uf]').on('change', function() {

                    if ($(this).val() == "RJ" && $("select[name=area_atuacao]").val() == "Medicina") {
                        $('<small id="crm_rj">Não inserir o 52 a frente do seu registro</small>').insertBefore($('#crm_num'));
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

                $('#valida_crm').click(function() {

                    var uf = $('select[name=crm_uf]').val();
                    var crm = $('input[name=crm]').val();
                    var url = "https://4k5zxy0dui.execute-api.us-east-1.amazonaws.com/webmodera/check-crm/" + uf + "/" + crm;

                    console.log(url)

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
                                    $('#crm_error').html('<div class="validation_error semi-error">Somente médicos com cadastro ativo podem se cadastrar, verifique o crm e o estado informado para prosseguir.</div>');
                                }

                            });

                        } else {
                            $('#crm_error').html('<div class="validation_error semi-error">Somente médicos podem se cadastrar, verifique o crm e o estado informado para prosseguir.</div>');
                        }

                    });

                    return false;
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

            });
        </script>

        <form action="<?php echo $_SERVER['REQUEST_URI'] ?>" method="post" id="cadastramento">

            <input type="hidden" value="<?php echo get_option('evento_global'); ?>" name="evento" />

            <?php if (get_option('categoria_global') == "2") { ?>

                <script>
                    jQuery(document).ready(function($) {

                        $("#area_atuacao").hide();
                        var codigos = [];

                        <?php

                        $areas = explode(",", get_option('codigos_global'));

                        foreach ($areas as $area) {
                            echo 'codigos.push("' . ltrim($area) . '");';
                        }

                        ?>

                        $('#valida-codigo button').click(function() {

                            if ($.inArray($('input[name=codigo]').val().toUpperCase(), codigos) >= 0) {
                                $("#area_atuacao").show();
                                $("#codigo_errado").remove();
                                $('input[name=codigo]').attr("readonly", true);
                            } else {
                                $("#area_atuacao").hide();
                                $("#codigo_errado").remove();
                                $("<span id='codigo_errado' style='background: #f00; color: #fff; padding: 10px; display: block; margin-top: 0px; border-radius: 3px; font-weight: 700;'>Este código não é válido!</span>").insertAfter("#valida-codigo");
                            }

                            return false;

                        });

                    });
                </script>

                <div class="wb-70">
                    <label for="codigo">Código *</label>
                    <input type="text" name="codigo" required />
                </div>

                <div class="wb-30" id="valida-codigo">
                    <button>Validar Código</button>
                </div>

            <?php } ?>

            <div class="wb-100" id="area_atuacao">
                <label for="area_atuacao">Área de atuação *</label>

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
                <label for="crm">CRM (somente números) *</label>
                <input type="text" name="crm" id="crm_num" required />
            </div>

            <div class="wb-33 md1">
                <button id="valida_crm">Validar CRM</button>
            </div>

            <div class="wb-100" id="crm_error">

            </div>

            <div class="wb-100 md2 nmd staff">
                <label for="nome">Nome Completo *</label>
                <input type="text" name="nome" required />
            </div>

            <div class="wb-100 md2 nmd staff">
                <label for="nome">E-mail *</label>
                <input type="email" name="email" required />
            </div>

            <div class="wb-100 md2">
                <label for="especialidade">Especialidade *</label>
                <select name="especialidade" required>
                    <option></option>
                    <option value="Acupuntura">Acupuntura</option>
                    <option value="Alergia e imunologia">Alergia e imunologia</option>
                    <option value="Alergia e imunologia pediátrica">Alergia e imunologia pediátrica</option>
                    <option value="Anestesiologia">Anestesiologia</option>
                    <option value="Angiologia">Angiologia</option>
                    <option value="Cardiologia">Cardiologia</option>
                    <option value="Cardiologia pediátrica">Cardiologia pediátrica</option>
                    <option value="Cirurgia bariátrica">Cirurgia bariátrica</option>
                    <option value="Cirurgia cardiovascular">Cirurgia cardiovascular</option>
                    <option value="Cirurgia da mão">Cirurgia da mão</option>
                    <option value="Cirurgia de cabeça e pescoço">Cirurgia de cabeça e pescoço</option>
                    <option value="Cirurgia do aparelho digestivo">Cirurgia do aparelho digestivo</option>
                    <option value="Cirurgia geral">Cirurgia geral</option>
                    <option value="Cirurgia oncológica">Cirurgia oncológica</option>
                    <option value="Cirurgia pediátrica">Cirurgia pediátrica</option>
                    <option value="Cirurgia plástica">Cirurgia plástica</option>
                    <option value="Cirurgia torácica">Cirurgia torácica</option>
                    <option value="Cirurgia vascular">Cirurgia vascular</option>
                    <option value="Clínica médica">Clínica médica</option>
                    <option value="Coloproctologia">Coloproctologia</option>
                    <option value="Dermatologia">Dermatologia</option>
                    <option value="Dor">Dor</option>
                    <option value="Endocrinologia e metabologia">Endocrinologia e metabologia</option>
                    <option value="Endocrinologia pediátrica">Endocrinologia pediátrica</option>
                    <option value="Endoscopia">Endoscopia</option>
                    <option value="Gastroenterologia">Gastroenterologia</option>
                    <option value="Gastroenterologia pediátrica">Gastroenterologia pediátrica</option>
                    <option value="Genética médica">Genética médica</option>
                    <option value="Geriatria">Geriatria</option>
                    <option value="Ginecologia e obstetrícia">Ginecologia e obstetrícia</option>
                    <option value="Hematologia e hemoterapia">Hematologia e hemoterapia</option>
                    <option value="Hematologia e hemoterapia pediátrica">Hematologia e hemoterapia pediátrica</option>
                    <option value="Hepatologia">Hepatologia</option>
                    <option value="Homeopatia">Homeopatia</option>
                    <option value="Infectologia">Infectologia</option>
                    <option value="Infectologia pediátrica">Infectologia pediátrica</option>
                    <option value="Mastologia">Mastologia</option>
                    <option value="Medicina de emergência">Medicina de emergência</option>
                    <option value="Medicina de família e comunidade">Medicina de família e comunidade</option>
                    <option value="Medicina de tráfego">Medicina de tráfego</option>
                    <option value="Medicina do trabalho">Medicina do trabalho</option>
                    <option value="Medicina esportiva">Medicina esportiva</option>
                    <option value="Medicina física e reabilitação">Medicina física e reabilitação</option>
                    <option value="Medicina intensiva">Medicina intensiva</option>
                    <option value="Medicina intensiva pediátrica">Medicina intensiva pediátrica</option>
                    <option value="Medicina legal e perícia médica">Medicina legal e perícia médica</option>
                    <option value="Medicina nuclear">Medicina nuclear</option>
                    <option value="Medicina preventiva e social">Medicina preventiva e social</option>
                    <option value="Nefrologia">Nefrologia</option>
                    <option value="Nefrologia pediátrica">Nefrologia pediátrica</option>
                    <option value="Neurocirurgia">Neurocirurgia</option>
                    <option value="Neurologia">Neurologia</option>
                    <option value="Neurologia pediátrica">Neurologia pediátrica</option>
                    <option value="Nutrologia">Nutrologia</option>
                    <option value="Oftalmologia">Oftalmologia</option>
                    <option value="Oncologia clínica">Oncologia clínica</option>
                    <option value="Oncologia pediátrica">Oncologia pediátrica</option>
                    <option value="Ortopedia e traumatologia">Ortopedia e traumatologia</option>
                    <option value="Otorrinolaringologia">Otorrinolaringologia</option>
                    <option value="Patologia">Patologia</option>
                    <option value="Patologia clínica/medicina laboratorial">Patologia clínica/medicina laboratorial</option>
                    <option value="Pediatria">Pediatria</option>
                    <option value="Pneumologia">Pneumologia</option>
                    <option value="Pneumologia pediátrica">Pneumologia pediátrica</option>
                    <option value="Psiquiatria">Psiquiatria</option>
                    <option value="Radiologia e diagnóstico por imagem">Radiologia e diagnóstico por imagem</option>
                    <option value="Radioterapia">Radioterapia</option>
                    <option value="Reumatologia">Reumatologia</option>
                    <option value="Reumatologia pediátrica">Reumatologia pediátrica</option>
                    <option value="Urologia">Urologia</option>
                </select>
            </div>

            <div class="wb-50 md2 nmd staff">
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

            <div class="wb-50 md2 nmd staff">
                <label form="cidade">Cidade *</label>
                <select name="cidade" required>
                    <option></option>
                </select>
            </div>

            <div class="wb-100 md2 nmd staff">
                <label form="nome">Telefone (com ddd) *</label>
                <input type="text" name="telefone" required />
            </div>

            <div class="wb-100 md2 nmd">
                <label form="sabendo">Como ficou sabendo? *</label>
                <select name="sabendo" required>
                    <option></option>
                    <option value="E-mail marketing">E-mail marketing</option>
                    <option value="Redes sociais">Redes sociais</option>
                    <option value="Sociedade Médica">Sociedade Médica</option>
                    <option value="Representante">Representante</option>
                    <option value="Outros">Outros</option>
                </select>
            </div>

            <div class="wb-100 md2 nmd staff">
                <input type="checkbox" name="termo" value="1">
                <label form="termo">Aceito receber e-mails sobre programas de educação continuada, via Editora Clannad</label>
            </div>

            <div class="wb-100 md2 nmd">
                <input type="checkbox" name="termo2" value="1" required>
                <label form="termo2">Estou ciente de que este site é restrito ao público prescritor e assumo completa responsabilidade pela veracidade das informações acima *</label>
            </div>

            <div class="wb-50 md2 nmd staff">
                <label form="nome">Senha</label>
                <input type="password" name="password" id="password" required />
            </div>

            <div class="wb-50 md2 nmd staff">
                <label form="nome">Confirme sua senha</label>
                <input type="password" name="cpassword" required />
            </div>

            <div class="wb-100 md2 nmd staff">
                <input type="submit" value="Cadastre-se" />
            </div>
        </form>

<?php }
}

add_shortcode('cadastro', 'global_cadastra_form');
