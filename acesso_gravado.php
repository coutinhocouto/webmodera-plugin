<?php

function global_acesso_gravado($atts)
{

    $atts = shortcode_atts(
        array(
            'live' => '',
			'data' => '',
        ),
        $atts,
        'global_cadastra_form'
    );

    $current_user = wp_get_current_user();
    $email = $current_user->user_email;
    $live = $atts['live'];
	$data = $atts['data'];

    ?>

<script>
    jQuery(document).ready(function( $ ){

        var dataString='{"live": <?php echo $live; ?>, "email":"<?php echo $email; ?>","tipo": 2}';
		
		var TodayDate = new Date();
		var endDate= new Date('<?php echo $data; ?>');
		
		if (endDate < TodayDate) {
			$.post({
				url:"https://4k5zxy0dui.execute-api.us-east-1.amazonaws.com/webmodera/inscrito-aovivo",
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
				}
			});
		}

    });
</script>

<?php
}

add_shortcode('acesso_gravado', 'global_acesso_gravado');