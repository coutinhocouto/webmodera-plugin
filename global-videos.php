<?php
/*
Plugin Name: Global Videos
Plugin URI: https://www.globalvideos.com.br
description: Plugins para os sites de eventos
Version: 1.9.6
Author: Global Videos
Author URI: https://www.globalvideos.com.br
License: GPL2
 */

require 'plugin-update-checker/plugin-update-checker.php';
$myUpdateChecker = Puc_v4_Factory::buildUpdateChecker(
	'https://github.com/coutinhocouto/webmodera-plugin',
	__FILE__,
	'global-videos'
);
$myUpdateChecker->setBranch('main');

/* ------------------------------------------------------------ */

include( plugin_dir_path( __FILE__ ) . 'api.php');
include( plugin_dir_path( __FILE__ ) . 'btn_aovivo.php');
include( plugin_dir_path( __FILE__ ) . 'acessos.php');
include( plugin_dir_path( __FILE__ ) . 'acesso_gravado.php');
include( plugin_dir_path( __FILE__ ) . 'countdown.php');
include( plugin_dir_path( __FILE__ ) . 'countdown_novo.php');
include( plugin_dir_path( __FILE__ ) . 'form_cadastro.php');
include( plugin_dir_path( __FILE__ ) . 'form_staff.php');
include( plugin_dir_path( __FILE__ ) . 'form_login.php');
include( plugin_dir_path( __FILE__ ) . 'painel_admin.php');
include( plugin_dir_path( __FILE__ ) . 'restict_pages.php');
include( plugin_dir_path( __FILE__ ) . 'woocommerce.php');
include( plugin_dir_path( __FILE__ ) . 'custom_functions.php');
include( plugin_dir_path( __FILE__ ) . 'campos_extras.php');
include( plugin_dir_path( __FILE__ ) . 'emails.php');
include( plugin_dir_path( __FILE__ ) . 'users.php');
include( plugin_dir_path( __FILE__ ) . 'sbem_blocker.php');
if(get_option('categoria_global') == '2' || get_option('categoria_global') == '3') {
	include( plugin_dir_path( __FILE__ ) . 'codigos.php');
}

/* ------------------------------------------------------------ */

add_role('medicos', 'Médicos', array('read' => true));
add_role('nao_medicos', 'Não Médicos', array('read' => true));
add_role('staff', 'Staff', array('read' => true));
add_role('convidado', 'Convidado', array('read' => true));
add_role('vip', 'Vip', array('read' => true));

/* ------------------------------------------------------------ */

function global_table_install() {
    global $wpdb;
    global $charset_collate;
    $table_name = $wpdb->prefix . 'global_codigos';
    $sql = "CREATE TABLE IF NOT EXISTS $table_name (
        id bigint(20) NOT NULL AUTO_INCREMENT,
        user_id bigint(20) NOT NULL,
        codigo varchar(255) NOT NULL, 
        usado DATETIME DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY  (id)
    )$charset_collate;";
     require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
     dbDelta( $sql );
     echo 'PLUGIN ATIVADO COM SUCESSO';
}
register_activation_hook(__FILE__,'global_table_install');


