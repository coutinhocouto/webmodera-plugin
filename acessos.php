<?php

function global_acessos()
{

    $current_user = wp_get_current_user();
    global $wp;
    $email = $current_user->user_email;
    $evento = get_option('evento_global');
    $link = home_url( $wp->request );

    ?>

<script>
    jQuery(document).ready(function( $ ){
		
        var dataString='{"evento": <?php echo $evento; ?>, "email":"<?php echo $email; ?>" , "link":"<?php echo $link; ?>","tipo": 1}';

        $.post({
            url:"https://4k5zxy0dui.execute-api.us-east-1.amazonaws.com/webmodera/acessos",
            data: dataString,
            dataType: 'json',
            crossDomain: true,
            cache: false,
            beforeSend: function(){ console.log("enviando")},
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                console.log(XMLHttpRequest);
                console.log(textStatus);
                console.log(errorThrown);
            },
            success: function() {
                console.log("enviou")
            }
        });

    });
	
	window.addEventListener("beforeunload", function (e) {        
      
		var dataString='{"evento": <?php echo $evento; ?>, "email":"<?php echo $email; ?>" , "link":"<?php echo $link; ?>","tipo": 2}';

        jQuery.post({
            url:"https://4k5zxy0dui.execute-api.us-east-1.amazonaws.com/webmodera/acessos",
            data: dataString,
            dataType: 'json',
            crossDomain: true,
            cache: false,
            beforeSend: function(){ console.log("enviando")},
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                console.log(XMLHttpRequest);
                console.log(textStatus);
                console.log(errorThrown);
            },
            success: function() {
                console.log("enviou")
            }
        });
		
		
      return;
    });
</script>

<?php
}

add_shortcode('acessos', 'global_acessos');