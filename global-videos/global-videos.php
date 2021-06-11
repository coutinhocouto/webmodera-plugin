<?php
/*
Plugin Name: Global Videos
Plugin URI: https://www.globalvideos.com.br
description: >- Plugins para os sites de eventos
Version: 1.0.0
Author: Global Videos
Author URI: https://www.globalvideos.com.br
License: GPL2
 */

add_role('medicos', 'Médicos', array('read' => true));
add_role('nao_medicos', 'Não Médicos', array('read' => true));
add_role('staff', 'Staff', array('read' => true));
add_role('convidado', 'Convidado', array('read' => true));

//include( plugin_dir_path( __FILE__ ) . 'btn_aovivo.php');
include( plugin_dir_path( __FILE__ ) . 'countdown.php');
include( plugin_dir_path( __FILE__ ) . 'form_cadastro.php');
include( plugin_dir_path( __FILE__ ) . 'form_login.php');
include( plugin_dir_path( __FILE__ ) . 'painel_admin.php');
include( plugin_dir_path( __FILE__ ) . 'restict_pages.php');
include( plugin_dir_path( __FILE__ ) . 'woocommerce.php');
include( plugin_dir_path( __FILE__ ) . 'custom_functions.php');

