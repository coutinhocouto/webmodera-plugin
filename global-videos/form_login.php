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

<form name="loginform" id="loginform" action="<?php echo site_url( '/wp-login.php' ); ?>" method="post">
	
<label>E-mail</label>
<input id="user_login" type="text" size="20" value="" name="log">
	
<label>Senha</label>
<input id="user_pass" type="password" size="20" value="" name="pwd">
<input id="rememberme" type="checkbox" value="forever" name="rememberme"> Lembrar?

<input id="wp-submit" type="submit" value="Acessar" name="wp-submit">

<input type="hidden" value="<?php echo esc_attr( $redirect_to ); ?>" name="redirect_to">
<input type="hidden" value="1" name="testcookie">
<br>
<a href="<?php echo wp_lostpassword_url(); ?>">Esqueceu sua senha?</a>

</form>


<?php
	
}}

add_shortcode('form_login', 'global_form_login');