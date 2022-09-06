<?php 

add_role('medicos', 'Médicos', array('read' => true));
add_role('nao_medicos', 'Não Médicos', array('read' => true));
add_role('staff', 'Staff', array('read' => true));
add_role('convidado', 'Convidado', array('read' => true));
add_role('vip', 'Vip', array('read' => true));

/* ------------------------------------------------------------ */

register_activation_hook(__file__, 'installer');
function installer(){
    global $wpdb;
    $table_name = $wpdb->prefix . "global_codigos";
    $global_version = '1.7.4';
    $charset_collate = $wpdb->get_charset_collate();

    if ( $wpdb->get_var("SHOW TABLES LIKE '{$table_name}'") != $table_name ) {

        $sql = "CREATE TABLE $table_name (
            id bigint(20) NOT NULL AUTO_INCREMENT,
            user_id bigint(20) NOT NULL,
            codigo varchar(255) NOT NULL, 
            usado DATETIME DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY  (id)
        ) $charset_collate;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
        add_option('webmodera', $global_version);
    }
}
