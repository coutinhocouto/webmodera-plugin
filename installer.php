<?php 

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
}
register_activation_hook(__FILE__,'global_table_install');
