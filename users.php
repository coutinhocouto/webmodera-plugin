<?php

function global_perfil_menu() {
    add_submenu_page(
        'global-admin',
        'Perfis', //page title
        'Perfis', //menu title
        'manage_options', //capability,
        'global-admin-perfil', //menu slug
        'global_campos_perfil_page' //callback function
    );
}
add_action('admin_menu', 'global_perfil_menu');

function global_campos_perfil_page() { ?>
    <div class="wrap">
        <h1>
            <img width="150" src="https://www.globalvideos.com.br/wp-content/uploads/2015/08/global_logo_web_transparente-e1439243390827.png" alt="" />
        </h1>

        <form method="POST" action="options.php">
            <?php
            settings_fields('global-perfil-page');
            do_settings_sections('global-perfil-page');
            submit_button();
            ?>
        </form>
    </div>
<?php
}

add_action('admin_init', 'perfil_settings_init');

function perfil_settings_init() {
    add_settings_section(
        'global_perfil_page_setting_section',
        '',
        '',
        'global-perfil-page'
    );

    //--------------------------------------

    add_settings_field(
        'ativa_perfil_1_global',
        'Ativar perfil 1?',
        'global_ativa_perfil_1_markup',
        'global-perfil-page',
        'global_perfil_page_setting_section'
    );

    register_setting('global-perfil-page', 'ativa_perfil_1_global');

    add_settings_field(
        'role_perfil_1_global',
        'Role do perfil 1?',
        'global_role_perfil_1_markup',
        'global-perfil-page',
        'global_perfil_page_setting_section'
    );

    register_setting('global-perfil-page', 'role_perfil_1_global');

    add_settings_field(
        'cods_perfil_1_global',
        'Códigos para associar o perfil 1?',
        'global_cods_perfil_1_markup',
        'global-perfil-page',
        'global_perfil_page_setting_section'
    );

    register_setting('global-perfil-page', 'cods_perfil_1_global');

    //------------------------------------

    add_settings_field(
        'ativa_perfil_2_global',
        'Ativar perfil 2?',
        'global_ativa_perfil_2_markup',
        'global-perfil-page',
        'global_perfil_page_setting_section'
    );

    register_setting('global-perfil-page', 'ativa_perfil_2_global');

    add_settings_field(
        'role_perfil_2_global',
        'Role do perfil 2?',
        'global_role_perfil_2_markup',
        'global-perfil-page',
        'global_perfil_page_setting_section'
    );

    register_setting('global-perfil-page', 'role_perfil_2_global');

    add_settings_field(
        'cods_perfil_2_global',
        'Códigos para associar o perfil 2?',
        'global_cods_perfil_2_markup',
        'global-perfil-page',
        'global_perfil_page_setting_section'
    );

    register_setting('global-perfil-page', 'cods_perfil_2_global');

    //------------------------------------

    add_settings_field(
        'ativa_perfil_3_global',
        'Ativar perfil 3?',
        'global_ativa_perfil_3_markup',
        'global-perfil-page',
        'global_perfil_page_setting_section'
    );

    register_setting('global-perfil-page', 'ativa_perfil_3_global');

    add_settings_field(
        'role_perfil_3_global',
        'Role do perfil 3?',
        'global_role_perfil_3_markup',
        'global-perfil-page',
        'global_perfil_page_setting_section'
    );

    register_setting('global-perfil-page', 'role_perfil_3_global');

    add_settings_field(
        'cods_perfil_3_global',
        'Códigos para associar o perfil 3?',
        'global_cods_perfil_3_markup',
        'global-perfil-page',
        'global_perfil_page_setting_section'
    );

    register_setting('global-perfil-page', 'cods_perfil_3_global');

    //------------------------------------

    add_settings_field(
        'ativa_perfil_4_global',
        'Ativar perfil 4?',
        'global_ativa_perfil_4_markup',
        'global-perfil-page',
        'global_perfil_page_setting_section'
    );

    register_setting('global-perfil-page', 'ativa_perfil_4_global');

    add_settings_field(
        'role_perfil_4_global',
        'Role do perfil 4?',
        'global_role_perfil_4_markup',
        'global-perfil-page',
        'global_perfil_page_setting_section'
    );

    register_setting('global-perfil-page', 'role_perfil_4_global');

    add_settings_field(
        'cods_perfil_4_global',
        'Códigos para associar o perfil 4?',
        'global_cods_perfil_4_markup',
        'global-perfil-page',
        'global_perfil_page_setting_section'
    );

    register_setting('global-perfil-page', 'cods_perfil_4_global');

    //------------------------------------

    add_settings_field(
        'ativa_perfil_5_global',
        'Ativar perfil 5?',
        'global_ativa_perfil_5_markup',
        'global-perfil-page',
        'global_perfil_page_setting_section'
    );

    register_setting('global-perfil-page', 'ativa_perfil_5_global');

    add_settings_field(
        'role_perfil_5_global',
        'Role do perfil 5?',
        'global_role_perfil_5_markup',
        'global-perfil-page',
        'global_perfil_page_setting_section'
    );

    register_setting('global-perfil-page', 'role_perfil_5_global');

    add_settings_field(
        'cods_perfil_5_global',
        'Códigos para associar o perfil 5?',
        'global_cods_perfil_5_markup',
        'global-perfil-page',
        'global_perfil_page_setting_section'
    );

    register_setting('global-perfil-page', 'cods_perfil_5_global');

    //------------------------------------

}

function global_ativa_perfil_1_markup() { ?>

    <script>
        jQuery(document).ready(function($) {

            jQuery('#role_perfil_1_global').val('<?php echo get_option('role_perfil_1_global'); ?>');
            jQuery('#role_perfil_2_global').val('<?php echo get_option('role_perfil_2_global'); ?>');
            jQuery('#role_perfil_3_global').val('<?php echo get_option('role_perfil_3_global'); ?>');
            jQuery('#role_perfil_4_global').val('<?php echo get_option('role_perfil_4_global'); ?>');
            jQuery('#role_perfil_5_global').val('<?php echo get_option('role_perfil_5_global'); ?>');

            //------------------

            if ($('#ativa_perfil_1_global:checked').length > 0) {
                $("#wpbody .form-table tbody tr:first-child + tr").show();
                $("#wpbody .form-table tbody tr:first-child + tr + tr").show();
            } else {
                $("#wpbody .form-table tbody tr:first-child + tr").hide();
                $("#wpbody .form-table tbody tr:first-child + tr + tr").hide();
            }

            $('#ativa_perfil_1_global').click(function() {
                if ($('#ativa_perfil_1_global:checked').length > 0) {
                    $("#wpbody .form-table tbody tr:first-child + tr").show();
                    $("#wpbody .form-table tbody tr:first-child + tr + tr").show();
                } else {
                    $("#wpbody .form-table tbody tr:first-child + tr").hide();
                    $("#wpbody .form-table tbody tr:first-child + tr + tr").hide();
                }
            })

            //---------------------

            if ($('#ativa_perfil_2_global:checked').length > 0) {
                $("#wpbody .form-table tbody tr:first-child + tr  + tr  + tr  + tr").show();
                $("#wpbody .form-table tbody tr:first-child + tr  + tr  + tr  + tr + tr").show();
            } else {
                $("#wpbody .form-table tbody tr:first-child + tr  + tr  + tr  + tr").hide();
                $("#wpbody .form-table tbody tr:first-child + tr  + tr  + tr  + tr + tr").hide();
            }

            $('#ativa_perfil_2_global').click(function() {
                if ($('#ativa_perfil_2_global:checked').length > 0) {
                    $("#wpbody .form-table tbody tr:first-child + tr  + tr  + tr  + tr").show();
                    $("#wpbody .form-table tbody tr:first-child + tr  + tr  + tr  + tr + tr").show();
                } else {
                    $("#wpbody .form-table tbody tr:first-child + tr  + tr  + tr  + tr").hide();
                    $("#wpbody .form-table tbody tr:first-child + tr  + tr  + tr  + tr + tr").hide();
                }
            })

            //---------------------

            if ($('#ativa_perfil_3_global:checked').length > 0) {
                $("#wpbody .form-table tbody tr:first-child + tr  + tr  + tr  + tr + tr + tr + tr").show();
                $("#wpbody .form-table tbody tr:first-child + tr  + tr  + tr  + tr + tr + tr + tr + tr").show();
            } else {
                $("#wpbody .form-table tbody tr:first-child + tr  + tr  + tr  + tr + tr + tr + tr").hide();
                $("#wpbody .form-table tbody tr:first-child + tr  + tr  + tr  + tr + tr + tr + tr + tr").hide();
            }

            $('#ativa_perfil_3_global').click(function() {
                if ($('#ativa_perfil_3_global:checked').length > 0) {
                    $("#wpbody .form-table tbody tr:first-child + tr  + tr  + tr  + tr + tr + tr + tr").show();
                    $("#wpbody .form-table tbody tr:first-child + tr  + tr  + tr  + tr + tr + tr + tr + tr").show();
                } else {
                    $("#wpbody .form-table tbody tr:first-child + tr  + tr  + tr  + tr + tr + tr + tr").hide();
                    $("#wpbody .form-table tbody tr:first-child + tr  + tr  + tr  + tr + tr + tr + tr + tr").hide();
                }
            })

            //---------------------

            if ($('#ativa_perfil_4_global:checked').length > 0) {
                $("#wpbody .form-table tbody tr:first-child + tr  + tr  + tr  + tr + tr + tr + tr + tr + tr + tr").show();
                $("#wpbody .form-table tbody tr:first-child + tr  + tr  + tr  + tr + tr + tr + tr + tr + tr + tr + tr").show();
            } else {
                $("#wpbody .form-table tbody tr:first-child + tr  + tr  + tr  + tr + tr + tr + tr + tr + tr + tr").hide();
                $("#wpbody .form-table tbody tr:first-child + tr  + tr  + tr  + tr + tr + tr + tr + tr + tr + tr + tr").hide();
            }

            $('#ativa_perfil_4_global').click(function() {
                if ($('#ativa_perfil_4_global:checked').length > 0) {
                    $("#wpbody .form-table tbody tr:first-child + tr  + tr  + tr  + tr + tr + tr + tr + tr + tr + tr").show();
                    $("#wpbody .form-table tbody tr:first-child + tr  + tr  + tr  + tr + tr + tr + tr + tr + tr + tr + tr").show();
                } else {
                    $("#wpbody .form-table tbody tr:first-child + tr  + tr  + tr  + tr + tr + tr + tr + tr + tr + tr").hide();
                    $("#wpbody .form-table tbody tr:first-child + tr  + tr  + tr  + tr + tr + tr + tr + tr + tr + tr + tr").hide();
                }
            })

            //---------------------
            
            if ($('#ativa_perfil_5_global:checked').length > 0) {
                $("#wpbody .form-table tbody tr:first-child + tr  + tr  + tr  + tr + tr + tr + tr + tr + tr + tr + tr + tr + tr").show();
                $("#wpbody .form-table tbody tr:first-child + tr  + tr  + tr  + tr + tr + tr + tr + tr + tr + tr + tr + tr + tr + tr").show();
            } else {
                $("#wpbody .form-table tbody tr:first-child + tr  + tr  + tr  + tr + tr + tr + tr + tr + tr + tr + tr + tr + tr").hide();
                $("#wpbody .form-table tbody tr:first-child + tr  + tr  + tr  + tr + tr + tr + tr + tr + tr + tr + tr + tr + tr + tr").hide();
            }

            $('#ativa_perfil_5_global').click(function() {
                if ($('#ativa_perfil_5_global:checked').length > 0) {
                    $("#wpbody .form-table tbody tr:first-child + tr  + tr  + tr  + tr + tr + tr + tr + tr + tr + tr + tr + tr + tr").show();
                    $("#wpbody .form-table tbody tr:first-child + tr  + tr  + tr  + tr + tr + tr + tr + tr + tr + tr + tr + tr + tr + tr").show();
                } else {
                    $("#wpbody .form-table tbody tr:first-child + tr  + tr  + tr  + tr + tr + tr + tr + tr + tr + tr + tr + tr + tr").hide();
                    $("#wpbody .form-table tbody tr:first-child + tr  + tr  + tr  + tr + tr + tr + tr + tr + tr + tr + tr + tr + tr + tr").hide();
                }
            })

            //---------------------

        });
    </script>

	<style>
		input[type=text], textarea {width: 100%;}
		textarea {height: 150px;}
	</style>

    <input type="checkbox" id="ativa_perfil_1_global" name="ativa_perfil_1_global" value="1" <?php if (get_option('ativa_perfil_1_global') == "1") { echo "checked"; } ?>>

<?php
}

function global_role_perfil_1_markup() { ?>

    <select id="role_perfil_1_global" name="role_perfil_1_global">
        <?php wp_dropdown_roles(); ?>
    </select>

<?php
}

function global_cods_perfil_1_markup() { ?>

    <input type="text" id="cods_perfil_1_global" name="cods_perfil_1_global" value="<?php echo get_option('cods_perfil_1_global'); ?>" >

<?php
}

//-------------------------------------------------

function global_ativa_perfil_2_markup() { ?>

    <input type="checkbox" id="ativa_perfil_2_global" name="ativa_perfil_2_global" value="1" <?php if (get_option('ativa_perfil_2_global') == "1") { echo "checked"; } ?>>

<?php
}

function global_role_perfil_2_markup() { ?>

    <select id="role_perfil_2_global" name="role_perfil_2_global">
        <?php wp_dropdown_roles(); ?>
    </select>

<?php
}

function global_cods_perfil_2_markup() { ?>

    <input type="text" id="cods_perfil_2_global" name="cods_perfil_2_global" value="<?php echo get_option('cods_perfil_2_global'); ?>" >

<?php
}

//-------------------------------------------------

function global_ativa_perfil_3_markup() { ?>

    <input type="checkbox" id="ativa_perfil_3_global" name="ativa_perfil_3_global" value="1" <?php if (get_option('ativa_perfil_3_global') == "1") { echo "checked"; } ?>>

<?php
}

function global_role_perfil_3_markup() { ?>

    <select id="role_perfil_3_global" name="role_perfil_3_global">
        <?php wp_dropdown_roles(); ?>
    </select>

<?php
}

function global_cods_perfil_3_markup() { ?>

    <input type="text" id="cods_perfil_3_global" name="cods_perfil_3_global" value="<?php echo get_option('cods_perfil_3_global'); ?>" >

<?php
}

//-------------------------------------------------

function global_ativa_perfil_4_markup() { ?>

    <input type="checkbox" id="ativa_perfil_4_global" name="ativa_perfil_4_global" value="1" <?php if (get_option('ativa_perfil_4_global') == "1") { echo "checked"; } ?>>

<?php
}

function global_role_perfil_4_markup() { ?>

    <select id="role_perfil_4_global" name="role_perfil_4_global">
        <?php wp_dropdown_roles(); ?>
    </select>

<?php
}

function global_cods_perfil_4_markup() { ?>

    <input type="text" id="cods_perfil_4_global" name="cods_perfil_4_global" value="<?php echo get_option('cods_perfil_4_global'); ?>" >

<?php
}

//-------------------------------------------------

function global_ativa_perfil_5_markup() { ?>

    <input type="checkbox" id="ativa_perfil_5_global" name="ativa_perfil_5_global" value="1" <?php if (get_option('ativa_perfil_5_global') == "1") { echo "checked"; } ?>>

<?php
}

function global_role_perfil_5_markup() { ?>

    <select id="role_perfil_5_global" name="role_perfil_5_global">
        <?php wp_dropdown_roles(); ?>
    </select>

<?php
}

function global_cods_perfil_5_markup() { ?>

    <input type="text" id="cods_perfil_5_global" name="cods_perfil_5_global" value="<?php echo get_option('cods_perfil_5_global'); ?>" >

<?php
}