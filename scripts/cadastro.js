jQuery(document).ready(function ($) {

    $('select[name=crm_uf]').on('change', function () {

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
            },
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

    $('#valida_crm').click(function () {

        var uf = $('select[name=crm_uf]').val();
        var crm = $('input[name=crm]').val();
        
        // Clear previous errors
        $('.semi-error').remove();
        
        // First check if CRM already exists in our database
        var checkUrl = window.location.origin + "/wp-json/crm-check/crm-check?crm=" + crm + "&crm_uf=" + uf;
        
        $.getJSON(checkUrl, function (duplicateResult) {
            
            if (duplicateResult.exists) {
                // Show duplicate error message
                $('#crm_error').html('<div class="validation_error semi-error">Você já está cadastrado. Se não souber sua senha, clique em "Esqueci minha senha" no menu acima</div>');
                return false;
            }
            
            // If no duplicate found, proceed with original CRM validation
            var url = "https://4k5zxy0dui.execute-api.us-east-1.amazonaws.com/webmodera/check-crm/" + uf + "/" + crm;

            $.getJSON(url, function (result) {

                if (result.length) {

                    $.each(result, function (i, field) {

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

            }).fail(function() {
                $('#crm_error').html('<div class="validation_error semi-error">Erro ao validar CRM. Tente novamente.</div>');
            });
            
        }).fail(function() {
            // If duplicate check fails, proceed with original validation
            var url = "https://4k5zxy0dui.execute-api.us-east-1.amazonaws.com/webmodera/check-crm/" + uf + "/" + crm;

            $.getJSON(url, function (result) {

                if (result.length) {

                    $.each(result, function (i, field) {

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
        });

        return false;
    });

    $('input[name=telefone]').mask('(00) 00000-0000');

    $('select[name=uf]').on('change', function () {

        var uf = $(this).val();
        var url = "https://4k5zxy0dui.execute-api.us-east-1.amazonaws.com/webmodera/municipios/" + uf;
        $("select[name=cidade] option").remove();
        $("select[name=cidade]").append(new Option("", ""));

        $.getJSON(url, function (result) {
            if (result.length) {
                $.each(result, function (i, field) {
                    $("select[name=cidade]").append(new Option(field.cidade, field.cidade));
                });
            } else {
                console.log('ERRO!!')
            }

        });


    });

});