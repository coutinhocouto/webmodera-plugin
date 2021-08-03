<?php

function global_email_menu()
{
    add_submenu_page(
        'global-admin',
        'E-mails', //page title
        'E-mails', //menu title
        'manage_options', //capability,
        'global-admin-email', //menu slug
        'global_campos_email_page' //callback function
    );
}
add_action('admin_menu', 'global_email_menu');

function global_campos_email_page()
{
?>
    <div class="wrap">
        <h1>
            <img width="150" src="https://www.globalvideos.com.br/wp-content/uploads/2015/08/global_logo_web_transparente-e1439243390827.png" alt="" />
        </h1>

        <form method="POST" action="options.php">
            <?php
            settings_fields('global-email-page');
            do_settings_sections('global-email-page');
            submit_button();
            ?>
        </form>
    </div>
<?php
}

add_action('admin_init', 'email_settings_init');

function email_settings_init()
{
    add_settings_section(
        'global_email_page_setting_section',
        '',
        '',
        'global-email-page'
    );

    add_settings_field(
        'assunto_convidado_global',
        'Assunto para e-mail de boas vindas dos convidados',
        'global_assunto_convidado_markup',
        'global-email-page',
        'global_email_page_setting_section'
    );
	
	add_settings_field(
        'body_convidado_global',
        'Mensagem do e-mail de boas vindas dos convidados',
        'global_body_convidado_markup',
        'global-email-page',
        'global_email_page_setting_section'
    );
	
	add_settings_field(
        'assunto_staff_global',
        'Assunto para e-mail de boas vindas dos staffs',
        'global_assunto_staff_markup',
        'global-email-page',
        'global_email_page_setting_section'
    );
	
	add_settings_field(
        'body_staff_global',
        'Mensagem do e-mail de boas vindas dos staffs',
        'global_body_staff_markup',
        'global-email-page',
        'global_email_page_setting_section'
    );
	
	add_settings_field(
        'assunto_vip_global',
        'Assunto para e-mail de boas vindas dos vips',
        'global_assunto_vip_markup',
        'global-email-page',
        'global_email_page_setting_section'
    );
	
	add_settings_field(
        'body_vip_global',
        'Mensagem do e-mail de boas vindas dos vips',
        'global_body_vip_markup',
        'global-email-page',
        'global_email_page_setting_section'
    );

    register_setting('global-email-page', 'assunto_convidado_global');
	register_setting('global-email-page', 'body_convidado_global');
	register_setting('global-email-page', 'assunto_staff_global');
	register_setting('global-email-page', 'body_staff_global');
	register_setting('global-email-page', 'assunto_vip_global');
	register_setting('global-email-page', 'body_vip_global');
}

function global_assunto_convidado_markup()
{
?>

	<style>
		input[type=text], textarea {width: 100%;}
		textarea {height: 150px;}
	</style>

<input type="text" class="global_field" id="assunto_convidado_global" name="assunto_convidado_global" value="<?php echo get_option('assunto_convidado_global'); ?>">

<?php
}

function global_body_convidado_markup()
{
?>

<?php wp_editor(get_option('body_convidado_global'), 'body_convidado_global')?>

<?php
}

function global_assunto_staff_markup()
{
?>

<input type="text" class="global_field" id="assunto_staff_global" name="assunto_staff_global" value="<?php echo get_option('assunto_staff_global'); ?>">

<?php
}

function global_body_staff_markup()
{
?>

<?php wp_editor(get_option('body_staff_global'), 'body_staff_global')?>

<?php
}

function global_assunto_vip_markup()
{
?>

<input type="text" class="global_field" id="assunto_vip_global" name="assunto_vip_global" value="<?php echo get_option('assunto_vip_global'); ?>">

<?php
}

function global_body_vip_markup()
{
?>

<?php wp_editor(get_option('body_vip_global'), 'body_vip_global')?>

<?php
}