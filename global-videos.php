<?php
/*
Plugin Name: Global Videos
Plugin URI: https://www.globalvideos.com.br
description: Plugins para os sites de eventos
Version: 1.7.2
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

//Set the branch that contains the stable release.
$myUpdateChecker->setBranch('main');

//Optional: If you're using a private repository, specify the access token like this:
//$myUpdateChecker->setAuthentication('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');

add_role('medicos', 'Médicos', array('read' => true));
add_role('nao_medicos', 'Não Médicos', array('read' => true));
add_role('staff', 'Staff', array('read' => true));
add_role('convidado', 'Convidado', array('read' => true));
add_role('vip', 'Vip', array('read' => true));

global $global_db_version;
$global_db_version = '1.7.2';

function global_install() {
	global $wpdb;
	global $global_db_version;

	$table_name = $wpdb->prefix . 'global_codigos';
	
	$charset_collate = $wpdb->get_charset_collate();

	$sql = "CREATE TABLE $table_name (
		id bigint(20) NOT NULL AUTO_INCREMENT,
		user_id bigint(20) NOT NULL,
		codigo DATETIME(20) DEFAULT CURRENT_TIMESTAMP NOT NULL,
		PRIMARY KEY  (id)
	) $charset_collate;";

	require_once ABSPATH . 'wp-admin/includes/upgrade.php';
	dbDelta( $sql );

	add_option( 'global_db_version', $global_db_version );
}
register_activation_hook( __FILE__, 'global_install' );

function myplugin_update_db_check() {
    global $global_db_version;
    if ( get_site_option( 'global_db_version' ) != $global_db_version ) {
        global_install();
    }
}
add_action( 'plugins_loaded', 'myplugin_update_db_check' )

include( plugin_dir_path( __FILE__ ) . 'btn_aovivo.php');
include( plugin_dir_path( __FILE__ ) . 'acessos.php');
include( plugin_dir_path( __FILE__ ) . 'acesso_gravado.php');
include( plugin_dir_path( __FILE__ ) . 'countdown.php');
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
if(get_option('categoria_global') == '2' || get_option('categoria_global') == '3') {
	include( plugin_dir_path( __FILE__ ) . 'codigos.php');
}

