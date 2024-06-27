<?php
/**
 * Plugin Name:       Coco Form
 * Description:       Example block scaffolded with Create Block tool.
 * Requires at least: 6.1
 * Requires PHP:      7.0
 * Version:           0.1.0
 * Author:            The WordPress Contributors
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       cocoform
 *
 * @package CreateBlock
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

function create_block_cocoform_block_init() {
	register_block_type( __DIR__ . '/build' );
}

function cocoform_add_admin_menu() {
	add_menu_page(
		'Gérer les Formulaires',       // Titre de la page
		'Formulaires Coco',            // Titre du menu
		'manage_options',              // Capability
		'cocoform',                    // Slug du menu
		'cocoform_render_admin_page',  // Fonction pour rendre la page
		'dashicons-feedback'           // Icône du menu
	);
}

add_action('admin_menu', 'cocoform_add_admin_menu');

function cocoform_get_all_forms() {
	global $wpdb;
	$prefix = 'cocoform_';
	$sql = $wpdb->prepare(
		"SELECT option_name, option_value FROM $wpdb->options WHERE option_name LIKE %s",
		$wpdb->esc_like($prefix) . '%'
	);
	$results = $wpdb->get_results($sql, ARRAY_A);

	$forms = [];
	foreach ($results as $row) {
		$forms[$row['option_name']] = maybe_unserialize($row['option_value']);
	}
	return $forms;
}

function cocoform_render_admin_page() {
	$action = isset($_GET['action']) ? $_GET['action'] : '';

	switch ($action) {
		case 'add':
			include(plugin_dir_path(__FILE__) . 'add-form.php');
			break;
		case 'edit':
			$form_id = isset($_GET['id']) ? $_GET['id'] : '';
			include(plugin_dir_path(__FILE__) . 'edit-form.php');
			break;
		default:
			?>
			<div class="wrap">
				<h1>Gérer les Formulaires</h1>
				<a href="?page=cocoform&action=add" class="page-title-action">Ajouter un formulaire</a>
				<table class="wp-list-table widefat fixed striped">
					<thead>
					<tr>
						<th>Nom du formulaire</th>
						<th>Actions</th>
					</tr>
					</thead>
					<tbody>
					<?php
					$formulaires = cocoform_get_all_forms();
					if (!empty($formulaires)) {
						foreach ($formulaires as $index => $formulaire) {
							$form_name = $formulaire['name'];
							$option_key = 'cocoform_' . sanitize_title($form_name); // Form key for URL
							echo "<tr id='form-row-{$option_key}'>
                                    <td>{$form_name}</td>
                                    <td><a href='?page=cocoform&action=edit&id={$option_key}'>Modifier</a> | <button class='delete-form' data-form-id='{$option_key}'>Supprimer</button></td>
                                    </tr>";
						}
					} else {
						echo "<tr><td colspan='2'>Aucun formulaire trouvé.</td></tr>";
					}
					?>
					</tbody>
				</table>
			</div>
			<script>
				document.addEventListener('DOMContentLoaded', function() {
					document.querySelectorAll('.delete-form').forEach(button => {
						button.addEventListener('click', function() {
							const formId = this.dataset.formId;
							if (confirm('Êtes-vous sûr de vouloir supprimer ce formulaire ?')) {
								fetch(ajaxurl, {
									method: 'POST',
									headers: {
										'Content-Type': 'application/x-www-form-urlencoded',
									},
									body: new URLSearchParams({
										'action': 'cocoform_delete_form',
										'form_id': formId,
										'_ajax_nonce': '<?php echo wp_create_nonce("cocoform_delete_form_nonce"); ?>'
									})
								})
									.then(response => response.json())
									.then(data => {
										if (data.success) {
											document.getElementById('form-row-' + formId).remove();
											alert('Formulaire supprimé avec succès.');
										} else {
											alert('Erreur : ' + data.data);
										}
									})
									.catch(error => {
										console.error('Erreur:', error);
										alert('Erreur lors de la suppression du formulaire.');
									});
							}
						});
					});
				});
			</script>
			<?php
			break;
	}
}

add_action( 'init', 'create_block_cocoform_block_init' );

function cocoform_register_ajax_endpoints() {
	add_action('wp_ajax_cocoform_delete_form', 'cocoform_delete_form');
}

function cocoform_delete_form() {
	if (!current_user_can('manage_options')) {
		wp_send_json_error('Permissions insuffisantes.');
	}

	if (!isset($_POST['form_id'])) {
		wp_send_json_error('ID du formulaire manquant.');
	}

	$form_id = sanitize_text_field($_POST['form_id']);
	$option_key = $form_id;

	if (delete_option($option_key)) {
		// Mettre à jour la liste des formulaires
		$formulaires = get_option('cocoform_formulaires', []);
		$short_id = str_replace('cocoform_', '', $form_id);
		if (isset($formulaires[$short_id])) {
			unset($formulaires[$short_id]);
			update_option('cocoform_formulaires', $formulaires);
		}

		wp_send_json_success('Formulaire supprimé.');
	} else {
		wp_send_json_error('Erreur lors de la suppression du formulaire.');
	}
}

add_action('admin_init', 'cocoform_register_ajax_endpoints');
?>