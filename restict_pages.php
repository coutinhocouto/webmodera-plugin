<?php

//------------------------------------------------------------------
//------------------- CAMPO BLOQUEADOR DE PÁGINAS ------------------
//------------------------------------------------------------------

function wporg_add_custom_box()
{
	$screens = ['page'];
	foreach ($screens as $screen) {
		add_meta_box(
			'wporg_box_id',                 // Unique ID
			'Global bloqueio de páginas',      // Box title
			'wporg_custom_box_html',  // Content callback, must be of type callable
			$screen                            // Post type
		);
	}
}
add_action('add_meta_boxes', 'wporg_add_custom_box');

function wporg_custom_box_html($post)
{
	$value = get_post_meta($post->ID, 'global_block', true);
?>

	<script>
		jQuery(document).ready(function($) {

			if ($('#global_block:checked').length > 0) {
				$("#global_roles").show();
			} else {
				$("#global_roles").hide();
			}

			$('#global_block').click(function() {
				if ($('#global_block:checked').length > 0) {
					$("#global_roles").show();
				} else {
					$("#global_roles").hide();
				}
			})

		});
	</script>

	<br>
	<input id="global_block" type="checkbox" name="global_block" value="1" <?php if ($value == '1') echo 'checked="checked"'; ?> /> Bloquear Página?

	<div id="global_roles">
		<br>
		<hr>

		<h4>Quais niveis de usuário poderão acessar a página?</h4>


		<?php

		$postmeta = maybe_unserialize(get_post_meta($post->ID, 'global_roles', true));

		$editable_roles = get_editable_roles();
		foreach ($editable_roles as $role => $details) {

			$sub['role'] = esc_attr($role);
			$sub['name'] = translate_user_role($details['name']);

			if ($role != "administrator") {
				if (is_array($postmeta) && in_array($role, $postmeta)) {
					$checked = 'checked="checked"';
				} else {
					$checked = null;
				}

				echo '<input  type="checkbox" name="global_roles[]" value="' . $role . '" ' . $checked . ' /> ' . $details['name'] . "<br>";
			}
		}

		?>
	</div>

<?php
}

function wporg_save_postdata($post_id)
{
	// If the checkbox was not empty, save it as array in post meta
	if (!empty($_POST['global_block'])) {
		update_post_meta($post_id, 'global_block', $_POST['global_block']);

		// Otherwise just delete it if its blank value.
	} else {
		delete_post_meta($post_id, 'global_block');
	}

	// If the checkbox was not empty, save it as array in post meta
	if (!empty($_POST['global_roles'])) {
		update_post_meta($post_id, 'global_roles', $_POST['global_roles']);

		// Otherwise just delete it if its blank value.
	} else {
		update_post_meta($post_id, 'global_roles', $_POST['global_roles']);
	}
}
add_action('save_post', 'wporg_save_postdata');

//------------------------------------------------------------------
//----------------- EXECUTA O BLOQUEIO DAS PÁGINAS -----------------
//------------------------------------------------------------------

add_action('wp', 'wpse69369_special_thingy');
function wpse69369_special_thingy()
{
	if ('page' === get_post_type() && is_singular()) {

		$post_id = get_the_ID();
		$global_block = get_post_meta($post_id, 'global_block', true);
		$global_roles = maybe_unserialize(get_post_meta($post_id, 'global_roles', true));
		$url = get_option('bloqueio_global');
		$user = wp_get_current_user();

		$user_id = get_current_user_id();
		$users = new WP_User($user_id);

		if (!empty($users->roles) && is_array($users->roles)) {
			foreach ($users->roles as $role)
				$user_role = $role;
		}

		if ($global_block == '1') {
			if (!is_user_logged_in()) {
				wp_redirect($url);
				exit();
			} else {
				if (!empty($global_roles)) {
					if ($user_role != 'administrator' && !in_array($user_role, $global_roles)) {
						wp_redirect($url);
						exit();
					}
				}
			}
		}
	}
}
