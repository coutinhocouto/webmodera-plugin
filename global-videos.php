<?php
/*
Plugin Name: Global Videos
Plugin URI: https://www.globalvideos.com.br
description: Plugins para os sites de eventos
Version: 1.3.1
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
$myUpdateChecker->setAuthentication('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');

add_role('medicos', 'Médicos', array('read' => true));
add_role('nao_medicos', 'Não Médicos', array('read' => true));
add_role('staff', 'Staff', array('read' => true));
add_role('convidado', 'Convidado', array('read' => true));
add_role('vip', 'Vip', array('read' => true));

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

