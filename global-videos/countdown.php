<?php

function global_countdown($atts)
{

    $atts = shortcode_atts(
        array(
            'live' => '',
			'data' => ''
        ),
        $atts,
        'global_cadastra_form'
    );

    $current_user = wp_get_current_user();
    $nome = $current_user->user_firstname . " " . $current_user->user_lastname;
    $cidade = get_user_meta($current_user->ID, "billing_city", true);
    $uf = get_user_meta($current_user->ID, "billing_state", true);
    $email = $current_user->user_email;
    $id = $current_user->ID;
    $url = get_option('aovivo_global');

    echo do_shortcode('[ujicountdown id="global" expire="' . $atts['data'] . '" hide="true" url=' . $url . '/?usuario=' . $id . '&live=' . $atts['live'] . '&nome=' . $nome .  '&cidade=' . $cidade .  '&uf=' . $uf .  '&email=' . $email . '" subscr="" recurring="2" rectype="second" repeats=""]');
	
}

add_shortcode('contador', 'global_countdown');

