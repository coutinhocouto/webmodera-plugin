<?php

add_action('add_meta_boxes', 'add_sbem_meta_box');
function add_sbem_meta_box()
{
    add_meta_box(
        'team_meta_box',
        'Sbem',
        'sbem_meta_box_callback',
        ['page', 'product'],
        'normal',
        'default'
    );
}

function sbem_meta_box_callback($post)
{
    wp_nonce_field('sbem_meta_box_nonce', 'meta_box_nonce');
    $checked = get_post_meta($post->ID, 'block_sbem', true) == '1' ? 'checked' : '';
?>
    <input type="checkbox" name="block_sbem" value="1" <?php echo $checked; ?> /> <label>Bloquear com login SBEM?</label>
<?php
}

add_action('save_post', 'save_sbem_meta_data');
function save_sbem_meta_data($post_id)
{
    if (!isset($_POST['meta_box_nonce'])) {
        return;
    }
    if (!wp_verify_nonce($_POST['meta_box_nonce'], 'sbem_meta_box_nonce')) {
        return;
    }
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }
    update_post_meta($post_id, 'block_sbem', sanitize_text_field($_POST['block_sbem']));
}

add_shortcode('sbem_block', 'sbem_block_function');
function sbem_block_function()
{
    ob_start();
?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/js-cookie/2.0.4/js.cookie.min.js" integrity="sha512-Nonc2AqL1+VEN+97F3n4YxucBOAL5BgqNwEVc2uUjdKOWAmzwj5ChdJQvN2KldAxkCxE4OenuJ/RL18bWxGGzA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <style>
        #sbem_login {
            width: 300px;
            margin: 100px auto;
        }

        #sbem_login input {
            width: 100%;
        }

        #hidden_content, .product .price, .product .woocommerce-product-details__short-description, .product .cart, .product .variations, .product .single_variation_wrap {
            display: none;
        }
    </style>
    <script>
        jQuery(document).ready(function($) {
            "use strict";

            if (typeof Cookies.get('cpf_login_sbem') !== 'undefined') {
                $('#sbem_login').hide();
                $('#hidden_content, .product .price, .product .woocommerce-product-details__short-description, .product .cart, .product .variations, .product .single_variation_wrap').show();
            }

            $('#sbem_login input[type=submit]').click(function() {

                var emails = $('#sbem_login input[type=text]').val();
                var pwds = $('#sbem_login input[type=password]').val();
				jQuery("#loader").show();

                $.ajax({
                    data: {
                        login: emails,
                        senha: pwds,
                    },
                    type: "POST",
                    url: "https://icase.sbem.itarget.com.br/api/login/",

                    //-------------------- DATA -----------------------------
                    success: function(data) {
						jQuery("#loader").hide();
                        if (data.data.logado == false) {
                            $("#sbem_login input").show();
                            $('.response').html('<span style="color: red; display: block">Usuário ou senha SBEM incorretos!<span>');
                        } else {
                            $('.response').html('');

                            $.ajax({
                                type: "GET",
                                url: "https://icase.sbem.itarget.com.br/api/endereco/?token=01f5215c95babc4f6e1063c8e1e61eef192d4906&corresp=S&pessoa_id=" + data.data.id,

                                //------------------DATA 2 ------------------------------
                                success: function(data2) {

                                    $.ajax({
                                        type: "GET",
                                        url: "https://icase.sbem.itarget.com.br/api/pessoa/?token=01f5215c95babc4f6e1063c8e1e61eef192d4906&id=" + data.data.id,

                                        //------------------ DATA 3 ------------------------------
                                        success: function(data3) {

                                            Cookies.set('cpf_login_sbem', data3.data[0].cpf, {
                                                expires: 0.17
                                            });
                                            $.ajax({
                                                data: {
                                                    cpf: data3.data[0].cpf,
                                                    email: data3.data[0].email,
                                                    uf: data2.data[0].uf,
                                                    cidade: data2.data[0].municipio,
                                                    crm: data3.data[0].crm,
                                                    nome: data.data.nome
                                                },
                                                type: "POST",
                                                url: "https://www.universidadeonlinesbem.com.br/webservice/inserir.php"

                                            });

                                            $('#sbem_login').hide();
                                            $('#hidden_content, .product .price, .product .woocommerce-product-details__short-description, .product .cart, .product .variations, .product .single_variation_wrap').show();
                                        }
                                    });
                                }
                            });
                        }

                    }

                });

                return false;

            });

        });
    </script>
    <fieldset id="sbem_login">
        <span class="response"></span>
        <img src="https://s3.amazonaws.com/www.sieexsbem.com.br/wp-content/uploads/2024/02/27124748/loader.gif" style="width: 100px; height: 100px; margin: 0 auto; display: none;" id="loader" /><br>
		<strong style="margin-bottom: 20px; display: block;">Para acessar esta página é necessário entrar com seu login e senha SBEM</strong>
        <input type="text" placeholder="Login SBEM" />
        <input type="password" placeholder="Senha SBEM" />
        <input type="submit" value="entrar" id="Entrar" />
    </fieldset>
<?php
    return ob_get_clean();
}

add_filter('the_content', 'content_sbem_blocked');
function content_sbem_blocked($content)
{
    global $post;
    $block = get_post_meta($post->ID, 'block_sbem', true);

    if ($block == 1 && is_page()) :
        $shortcode_content = do_shortcode('[sbem_block]');
        return $shortcode_content . '<div id="hidden_content">' . $content . '</div>';
    else :
        return $content;
    endif;
}

add_action( 'woocommerce_single_product_summary', 'summary_sbem_blocked', 10 );
function summary_sbem_blocked()
{
    global $post;
    $block = get_post_meta($post->ID, 'block_sbem', true);

    if ($block == 1) :
       	echo do_shortcode('[sbem_block]');
    endif;
}