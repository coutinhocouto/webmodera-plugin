<?php

function global_btn_aovivo($atts)
{

    $atts = shortcode_atts(
        array(
            'live' => '',
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
    $protocols = array('http://', 'http://www.', 'www.', 'https://www.', 'https://');
    $url = str_replace($protocols, '', get_bloginfo('wpurl'));

    echo $url;
    echo '<a style="color: #fff; padding: 10px; background: #17A2B8;" href="https://aovivo.' + $url + '/?usuario=' . $id . '&live=' . $atts['live'] . '&nome=' . $nome .  '&cidade=' . $cidade .  '&uf=' . $uf .  '&email=' . $email . '" target="_blank">Acessar Aovivo</a>';
}

add_shortcode('btn_aovivo', 'global_btn_aovivo');