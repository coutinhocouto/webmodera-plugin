<?php

function global_codigos_checker($codigo) {
    
    global $wpdb;
    $codigos = json_decode(get_option('codigos_global_new'));
    
    $result = $wpdb->get_results ( "
        SELECT count(user_id) as qtd
        FROM " . $wpdb->prefix . "global_codigos
        WHERE `codigo` LIKE '" . $codigo . "'
    ");
    $qtd = 0;
    $usos = intval($result[0]->qtd);
    
    foreach($codigos as $item){
        if($item->codigo  === $codigo){
            $qtd = intval($item->qtd);
        }
    }

    $total = $qtd - $usos;

    return $total;
}

function global_codigos_menu() {
    add_submenu_page(
        'global-admin',
        'Códigos', //page title
        'Códigos', //menu title
        'manage_options', //capability,
        'global-codigos', //menu slug
        'global_campos_codigos_page' //callback function
    );
}
add_action('admin_menu', 'global_codigos_menu');

function global_campos_codigos_page() { ?>
    <div class="wrap">
        <h1>
            <img width="150" src="https://www.globalvideos.com.br/wp-content/uploads/2015/08/global_logo_web_transparente-e1439243390827.png" alt="" />
        </h1>

        <form method="POST" action="options.php">
            <?php
                settings_fields('global-codigos-page');
                do_settings_sections('global-codigos-page');
                submit_button();
            ?>
        </form>
    </div>
<?php
}

add_action('admin_init', 'codigos_settings_init');

function codigos_settings_init() {
    add_settings_section(
        'global_codigos_page_setting_section',
        '',
        '',
        'global-codigos-page'
    );

    //--------------------------------------

    add_settings_field(
        'codigos_global_new',
        'Códigos',
        'global_cogidos_markup',
        'global-codigos-page',
        'global_codigos_page_setting_section'
    );

    register_setting('global-codigos-page', 'codigos_global_new');
}

function global_cogidos_markup() { ?>

    <style>
        .cod_separator {margin-bottom: 10px;}
		#add {margin-bottom: 10px;}
		#codigos_global_new {display: none;}
    </style>

    <script>
		function removeCod(e) {
			e.parentElement.remove();
		}
        jQuery(document).ready(function($) {
            $('#add').click(function(){
                $('#codigos_holder').append('<div class="cod_separator"></div>');
                $('#codigos_holder > div:last-child').append('<input type="text" class="codigo" placeholder="Código" />');
                $('#codigos_holder > div:last-child').append('<input type="number" class="qtd" placeholder="Qtd" />');
                $('#codigos_holder > div:last-child').append('<input type="button" class="button button-primary" onClick="removeCod(this)" value="Remover">');
            });
			$('.wrap form').submit(function(e){
				var codigos = [];
				
				$("#codigos_holder > div").each(function() {
					var codigo = $( this ).find($('.codigo')).val();
					var qtd = $( this ).find($('.qtd')).val();
					codigos.push({codigo: codigo, qtd: qtd});
				});
				
				$('#codigos_global_new').html(JSON.stringify(codigos));
			})
        });
    </script>
	<textarea id="codigos_global_new" name="codigos_global_new" style="width: 100%;">
		<?php echo get_option('codigos_global_new'); ?>
	</textarea>
    <input type="button" name="add" id="add" class="button button-primary" value="Adicionar Código">

    <div id="codigos_holder">
        <?php 
            global $wpdb;
            $codigos = json_decode(get_option('codigos_global_new'));
            foreach ($codigos as $item) {
                $index = array_search($item, $codigos);
                $result = $wpdb->get_results ( "
                    SELECT count(user_id) as qtd
                    FROM " . $wpdb->prefix . "global_codigos
                    WHERE `codigo` LIKE '" . $item->codigo . "'
                ");
                $codigos[$index]->usos = intval($result[0]->qtd);
                $item->qtd = intval($item->qtd);

                echo '<div class="cod_separator">';
                    echo '<input type="text" class="codigo" value="' . $item->codigo . '" placeholder="Código" />';
                    echo '<input type="number" class="qtd" value="' . $item->qtd . '" placeholder="Qtd" />';
                    echo '<input type="number" class="usados" disabled value="' . $item->usos . '" placeholder="Usos" />';
                    echo '<input type="button" class="button button-primary" onClick="removeCod(this)" value="Remover">';
                echo '</div>';
            }
        ?>
    </div>

<?php
}