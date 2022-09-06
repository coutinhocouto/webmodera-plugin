<?php
/*
Plugin Name: Global Videos
Plugin URI: https://www.globalvideos.com.br
description: Plugins para os sites de eventos
Version: 1.7.4
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

include( plugin_dir_path( __FILE__ ) . 'installer.php');
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

