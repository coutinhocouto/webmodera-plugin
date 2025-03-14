<?php

function global_extra_menu()
{
    add_submenu_page(
        'global-admin',
        'Campos Extras', //page title
        'Campos Extras', //menu title
        'manage_options', //capability,
        'global-admin-campos-extras', //menu slug
        'global_campos_extras_page' //callback function
    );
}
add_action('admin_menu', 'global_extra_menu');

function global_campos_extras_page()
{
?>
    <div class="wrap">
        <h1>
            <img width="150" src="https://www.globalvideos.com.br/wp-content/uploads/2015/08/global_logo_web_transparente-e1439243390827.png" alt="" />
        </h1>

        <form method="POST" action="options.php">
            <?php
            settings_fields('global-extra-page');
            do_settings_sections('global-extra-page');
            submit_button();
            ?>
        </form>
    </div>
<?php
}

add_action('admin_init', 'extra_settings_init');

function extra_settings_init()
{
    add_settings_section(
        'global_extra_page_setting_section',
        '',
        '',
        'global-extra-page'
    );

    add_settings_field(
        'mostra_sabendo_global',
        'Exibir campo "como ficou sabendo?"',
        'global_mostra_sabendo_markup',
        'global-extra-page',
        'global_extra_page_setting_section'
    );

    add_settings_field(
        'sabendo_obr_global',
        'O campo "como ficou sabendo?" é obrigatório?',
        'global_sabendo_obr_markup',
        'global-extra-page',
        'global_extra_page_setting_section'
    );

    add_settings_field(
        'mostra_extra',
        'Exibir campo extra?',
        'global_mostra_extra_markup',
        'global-extra-page',
        'global_extra_page_setting_section'
    );

    add_settings_field(
        'texto_extra',
        'Texto do campo extra',
        'global_texto_extra_markup',
        'global-extra-page',
        'global_extra_page_setting_section'
    );

    add_settings_field(
        'obrigatorio_extra',
        'Campo extra é obrigatório?',
        'global_obrigatorio_extra_markup',
        'global-extra-page',
        'global_extra_page_setting_section'
    );
	
	add_settings_field(
        'mostra_extra2',
        'Exibir campo extra2?',
        'global_mostra_extra_markup2',
        'global-extra-page',
        'global_extra_page_setting_section'
    );

    add_settings_field(
        'texto_extra2',
        'Texto do campo extra2',
        'global_texto_extra_markup2',
        'global-extra-page',
        'global_extra_page_setting_section'
    );

    add_settings_field(
        'obrigatorio_extra2',
        'Campo extra2 é obrigatório?',
        'global_obrigatorio_extra_markup2',
        'global-extra-page',
        'global_extra_page_setting_section'
    );


    register_setting('global-extra-page', 'mostra_sabendo_global');
    register_setting('global-extra-page', 'sabendo_obr_global');

    register_setting('global-extra-page', 'mostra_extra');
    register_setting('global-extra-page', 'texto_extra');
    register_setting('global-extra-page', 'obrigatorio_extra');
	
	register_setting('global-extra-page', 'mostra_extra2');
    register_setting('global-extra-page', 'texto_extra2');
    register_setting('global-extra-page', 'obrigatorio_extra2');
}

function global_mostra_sabendo_markup()
{
?>
    <input type="checkbox" id="mostra_sabendo_global" name="mostra_sabendo_global" value="1" <?php if (get_option('mostra_sabendo_global') == "1") { echo "checked";} ?>>

<?php
}

function global_sabendo_obr_markup()
{
?>
    <input type="checkbox" id="sabendo_obr_global" name="sabendo_obr_global" value="1" <?php if (get_option('sabendo_obr_global') == "1") { echo "checked";} ?>>
<?php
}

function global_mostra_extra_markup()
{
?>
    <input type="checkbox" id="mostra_extra" name="mostra_extra" value="1" <?php if (get_option('mostra_extra') == "1") { echo "checked";} ?>>
<?php
}

function global_texto_extra_markup()
{
?>
    <input type="text" id="texto_extra" name="texto_extra" value="<?php echo get_option('texto_extra'); ?>" style="width: 100%;">
<?php
}

function global_obrigatorio_extra_markup()
{
?>
    <input type="checkbox" id="obrigatorio_extra" name="obrigatorio_extra" value="1" <?php if (get_option('obrigatorio_extra') == "1") { echo "checked";} ?>>
<?php
}

function global_mostra_extra_markup2()
{
?>
    <input type="checkbox" id="mostra_extra2" name="mostra_extra2" value="1" <?php if (get_option('mostra_extra2') == "1") { echo "checked";} ?>>
<?php
}

function global_texto_extra_markup2()
{
?>
    <input type="text" id="texto_extra2" name="texto_extra2" value="<?php echo get_option('texto_extra2'); ?>" style="width: 100%;">
<?php
}

function global_obrigatorio_extra_markup2()
{
?>
    <input type="checkbox" id="obrigatorio_extra2" name="obrigatorio_extra2" value="1" <?php if (get_option('obrigatorio_extra2') == "1") { echo "checked";} ?>>
<?php
}