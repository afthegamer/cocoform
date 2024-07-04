<?php

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly.
}

class CocoForm_Admin {

	public function __construct() {
		add_action('admin_menu', array($this, 'add_admin_menu'));
	}

	public function add_admin_menu() {
		add_menu_page(
			'Gérer les Formulaires',       // Titre de la page
			'Formulaires Coco',            // Titre du menu
			'manage_options',              // Capability
			'cocoform',                    // Slug du menu
			array($this, 'render_admin_page'),  // Fonction pour rendre la page
			'dashicons-feedback'           // Icône du menu
		);
	}

	public function get_all_forms() {
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

	public function render_admin_page() {
		$action = isset($_GET['action']) ? $_GET['action'] : '';

		switch ($action) {
			case 'add':
				include(plugin_dir_path(__FILE__) . 'templates/add-form.php');
				break;
			case 'edit':
				$form_id = isset($_GET['id']) ? $_GET['id'] : '';
				include(plugin_dir_path(__FILE__) . 'templates/edit-form.php');
				break;
			default:
				$this->render_form_list();
				break;
		}
	}

	public function render_form_list() {
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
				$formulaires = $this->get_all_forms();
				if (!empty($formulaires)) {
					foreach ($formulaires as $index => $formulaire) {
						$form_name = $formulaire['name'];
						echo "<tr>
                                <td>{$form_name}</td>
                                <td>
                                    <a href='?page=cocoform&action=edit&id={$index}'>Modifier</a> |
                                    <a href='#' class='delete-form' data-form-id='{$index}'>Supprimer</a>
                                </td>
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
			document.addEventListener('DOMContentLoaded', function () {
				document.querySelectorAll('.delete-form').forEach(function (button) {
					button.addEventListener('click', function (event) {
						event.preventDefault();

						if (!confirm('Voulez-vous vraiment supprimer ce formulaire ?')) {
							return;
						}

						var formId = button.dataset.formId;

						var formData = new FormData();
						formData.append('_ajax_nonce', '<?php echo wp_create_nonce("cocoform_save_form_nonce"); ?>');
						formData.append('action', 'cocoform_delete_form');
						formData.append('form_id', formId);

						fetch(ajaxurl, {
							method: 'POST',
							body: new URLSearchParams(formData)
						})
							.then(response => response.json())
							.then(data => {
								if (data.success) {
									alert('Formulaire supprimé avec succès.');
									button.closest('tr').remove();
								} else {
									alert('Erreur : ' + data.data);
								}
							})
							.catch(error => {
								console.error('Erreur:', error);
								alert('Erreur lors de la suppression du formulaire.');
							});
					});
				});
			});
		</script>
		<?php
	}
}

