<?php

function global_form_login()
{ 
$redirect_to = get_option('login_global');
	
	if ( is_user_logged_in() ) {
    $current_user = wp_get_current_user();
?>

Olá, <?php echo $current_user->user_firstname . " " . $current_user->user_lastname;?>.<br><br>
<a href="<?php echo wp_logout_url( home_url() ); ?>">Deseja sair?</a>

<?php } else {
   
	?>


<style>
	#loginform label {margin-bottom: 5px;}
	#loginform input[type=password], #loginform input[type=text], #loginform input[type=submit] {width: 100%; margin-bottom: 20px}
</style>

<?php
$args = array(
    'redirect' => get_option('login_global'),
	'label_username' => __( 'E-mail' ),
   ) 
;?>
<?php wp_login_form($args); ?>
<a href="<?php echo wp_lostpassword_url(); ?>">Esqueceu sua senha?</a>

<?php
	
}}

add_shortcode('form_login', 'global_form_login');