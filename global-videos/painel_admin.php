<?php

function global_menu()
{
    add_menu_page('Global Videos', 'Global Videos', 'manage_options', 'global.php', 'global_admin_page', 'dashicons-tickets', 2);
}
add_action('admin_menu', 'global_menu');


function global_admin_page()
{
?>
    <div class="wrap">
        <h1>
            <img width="150" src="https://www.globalvideos.com.br/wp-content/uploads/2015/08/global_logo_web_transparente-e1439243390827.png" alt="" />
        </h1>

        <form method="POST" action="options.php">
            <?php
            settings_fields('global-page');
            do_settings_sections('global-page');
            submit_button();
            ?>
        </form>
    </div>
<?php
}

add_action('admin_init', 'my_settings_init');

function my_settings_init()
{

    add_settings_section(
        'global_page_setting_section',
        '',
        '',
        'global-page'
    );

    add_settings_field(
        'categoria_global',
        'Categoria do site',
        'global_categoria_markup',
        'global-page',
        'global_page_setting_section'
    );

    add_settings_field(
        'codigos_global',
        'Códigos dos convidados',
        'global_codigos_markup',
        'global-page',
        'global_page_setting_section'
    );

    add_settings_field(
        'evento_global',
        'Evento',
        'global_evento_markup',
        'global-page',
        'global_page_setting_section'
    );

    add_settings_field(
        'aovivo_global',
        'Link do aovivo',
        'global_aovivo_markup',
        'global-page',
        'global_page_setting_section'
    );

    add_settings_field(
        'inscrito_global',
        'Link de redirecionamento (após registro)',
        'global_inscrito_markup',
        'global-page',
        'global_page_setting_section'
    );

    add_settings_field(
        'inscrito_global',
        'Link de redirecionamento (após registro)',
        'global_inscrito_markup',
        'global-page',
        'global_page_setting_section'
    );
	
	add_settings_field(
        'login_global',
        'Link de redirecionamento (após login)',
        'global_login_markup',
        'global-page',
        'global_page_setting_section'
    );

    add_settings_field(
        'tem_medico_global',
        'Evento para médicos?',
        'global_tem_medico_markup',
        'global-page',
        'global_page_setting_section'
    );

    add_settings_field(
        'tem_nao_medico_global',
        'Evento para não médicos?',
        'global_tem_nao_medico_markup',
        'global-page',
        'global_page_setting_section'
    );

    add_settings_field(
        'nao_medico_atuacao_global',
        'Áreas de atuação dos não médicos?',
        'global_nao_medico_atuacao_markup',
        'global-page',
        'global_page_setting_section'
    );

    register_setting('global-page', 'evento_global');
    register_setting('global-page', 'aovivo_global');
    register_setting('global-page', 'inscrito_global');
	register_setting('global-page', 'login_global');
    register_setting('global-page', 'categoria_global');
    register_setting('global-page', 'codigos_global');
    register_setting('global-page', 'tem_medico_global');
    register_setting('global-page', 'tem_nao_medico_global');
    register_setting('global-page', 'nao_medico_atuacao_global');
}

function global_categoria_markup()
{
?>

    <script>
        jQuery(document).ready(function($) {

            var url = "https://4k5zxy0dui.execute-api.us-east-1.amazonaws.com/webmodera/list-events";
            $.getJSON(url, function(result) {
                $.each(result, function(i, field) {

                    $("#evento_global").append(new Option(field.name, field.id));

                });
            });

            $('#categoria_global').on('change', function() {

                if ($(this).val() == "2") {
                    $("#wpbody .form-table tbody tr:first-child + tr").show();
                } else {
                    $("#wpbody .form-table tbody tr:first-child + tr").hide();
                }
            });

            if ($('#categoria_global').val() == "2") {
                $("#wpbody .form-table tbody tr:first-child + tr").show();
            } else {
                $("#wpbody .form-table tbody tr:first-child + tr").hide();
            }

            if ($('#tem_nao_medico_global:checked').length > 0) {
                $("#wpbody .form-table tbody tr:first-child + tr + tr + tr + tr + tr + tr + tr + tr").show();
            } else {
                $("#wpbody .form-table tbody tr:first-child + tr + tr + tr + tr + tr + tr + tr + tr").hide();
            }

            $('#tem_nao_medico_global').click(function() {
                if ($('#tem_nao_medico_global:checked').length > 0) {
                    $("#wpbody .form-table tbody tr:first-child + tr + tr + tr + tr + tr + tr + tr + tr").show();
                } else {
                    $("#wpbody .form-table tbody tr:first-child + tr + tr + tr + tr + tr + tr + tr + tr").hide();
                }
            })

        });
        jQuery(document).ajaxComplete(function() {
            jQuery('#evento_global').val(jQuery("#evento_global_val").val());
        });
    </script>

    <style>
        .global_field {
            width: 100%;
            max-width: 100% !important;
        }
    </style>

    <select id="categoria_global" name="categoria_global" class="global_field">
        <option value="1" <?php if (get_option('categoria_global') == "1") {
                                echo 'selected';
                            } ?>>PEC / Curso</option>
        <option value="2" <?php if (get_option('categoria_global') == "2") {
                                echo 'selected';
                            } ?>>Evento</option>
    </select>
<?php
}

function global_evento_markup()
{
?>
    <select id="evento_global" name="evento_global" class="global_field">
    </select>
    <input type="hidden" id="evento_global_val" value="<?php echo get_option('evento_global'); ?>" />
<?php
}

function global_codigos_markup()
{
?>
    <input type="text" class="global_field" id="codigos_global" name="codigos_global" value="<?php echo get_option('codigos_global'); ?>">
<?php
}

function global_aovivo_markup()
{
?>
    <input type="text" class="global_field" id="aovivo_global" name="aovivo_global" value="<?php echo get_option('aovivo_global'); ?>">
<?php
}

function global_login_markup()
{
?>
    <input type="text" class="global_field" id="login_global" name="login_global" value="<?php echo get_option('login_global'); ?>">
<?php
}

function global_inscrito_markup()
{
?>
    <input type="text" class="global_field" id="inscrito_global" name="inscrito_global" value="<?php echo get_option('inscrito_global'); ?>">
<?php
}

function global_tem_medico_markup()
{
?>
    <input type="checkbox" id="tem_medico_global" name="tem_medico_global" value="1" <?php if (get_option('tem_medico_global') == "1") {
                                                                                            echo "checked";
                                                                                        } ?>>
<?php
}

function global_tem_nao_medico_markup()
{
?>
    <input type="checkbox" id="tem_nao_medico_global" name="tem_nao_medico_global" value="1" <?php if (get_option('tem_nao_medico_global') == "1") {
                                                                                                    echo "checked";
                                                                                                } ?>>
<?php
}

function global_nao_medico_atuacao_markup()
{
?>
    <textarea rows="5" class="global_field" id="nao_medico_atuacao_global" name="nao_medico_atuacao_global"><?php echo get_option('nao_medico_atuacao_global'); ?></textarea>
<?php
}
