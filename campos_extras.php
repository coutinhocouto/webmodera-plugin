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

    register_setting('global-extra-page', 'mostra_sabendo_global');
}

function global_mostra_sabendo_markup()
{
?>
    <input type="checkbox" id="mostra_sabendo_global" name="mostra_sabendo_global" value="1" <?php if (get_option('mostra_sabendo_global') == "1") { echo "checked";} ?>>

<?php
}