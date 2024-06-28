<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	// Collecter les données du formulaire
	$form_name = sanitize_text_field($_POST['form_name']);
	$fields = [];
	if (isset($_POST['field_label']) && is_array($_POST['field_label'])) {
		$field_types = $_POST['field_type'];
		foreach ($_POST['field_label'] as $index => $label) {
			if (isset($field_types[$index])) {
				$fields[] = ['label' => $label, 'type' => $field_types[$index]];
			}
		}
	}

	// Email et objet sont traités séparément s'ils sont présents
	if (isset($_POST['field_email'], $_POST['field_subject'])) {
		$fields[] = ['label' => 'Email', 'type' => 'email', 'default' => $_POST['field_email']];
		$fields[] = ['label' => 'Objet', 'type' => 'text', 'default' => $_POST['field_subject']];
	}

	// Préparation de la structure à enregistrer
	$form_data = [
		'name' => $form_name,
		'fields' => $fields
	];

	// Enregistrement des données dans la base de données WordPress
	$option_key = 'cocoform_' . sanitize_title($form_name);
	update_option($option_key, $form_data, 'no');
	// Mise à jour de la liste des formulaires
	$formulaires = get_option('cocoform_formulaires', []);
	$formulaires[sanitize_title($form_name)] = $form_name;
	update_option('cocoform_formulaires', $formulaires);

// Redirection vers la page d'édition du formulaire
	$redirect_url = admin_url('admin.php?page=cocoform&action=edit&id=' . urlencode(sanitize_title($form_name)));
	wp_redirect($redirect_url);
	exit;
//
//
//	// Redirection vers la page d'édition du formulaire
//	$redirect_url = admin_url('admin.php?page=cocoform&action=edit&id=' . urlencode(sanitize_title($form_name)));
//	wp_redirect($redirect_url);
//	exit; // Assurez-vous d'arrêter l'exécution du script après la redirection
}
?>

<!DOCTYPE html>
<!DOCTYPE html>
<html lang="fr">
<head>
	<meta charset="UTF-8">
	<title>Ajouter un Formulaire</title>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.14.0/Sortable.min.js"></script>
	<style>
		.wrap {
			max-width: 600px;
			margin: 20px auto;
			padding: 20px;
			background: #fff;
			box-shadow: 0 0 8px rgba(0, 0, 0, 0.1);
		}

		h1 {
			color: #333;
			font-size: 24px;
		}

		.field, .form-name {
			display: flex;
			align-items: center;
			margin-bottom: 10px;
		}

		.field input[type="text"],
		.field select,
		.form-name input[type="text"],
		.field input[type="email"] {
			flex: 1;
			padding: 8px;
			margin-right: 10px;
			border: 1px solid #ccc;
			border-radius: 4px;
		}

		label {
			min-width: 150px; /* Assurez-vous que le label a une largeur suffisante pour un espacement clair */
		}

		button {
			padding: 10px 20px;
			background-color: #0073aa;
			color: white;
			border: none;
			border-radius: 4px;
			cursor: pointer;
		}

		button:hover {
			background-color: #005177;
		}

		button[type="button"] {
			background-color: #dc3232;
		}

		button[type="button"]:hover {
			background-color: #a00;
		}

		input[type="submit"] {
			margin-top: 20px;
			width: 100%;
			box-sizing: border-box;
		}

		.handle {
			cursor: move;
			margin-right: 10px;
			color: #ccc;
			user-select: none; /* Empêche la sélection du texte lors du drag */
		}
	</style>
</head>
<body>
<div class="wrap">
	<h1>Ajouter un formulaire</h1>
	<form id="formCreator" method="post">
		<div class="form-name">
			<label for="form_name">Nom du formulaire:</label>
			<input id="form_name" name="form_name" type="text" required />
		</div>
		<div id="formFields" class="form-fields-container">
			<!-- Champs fixes pour l'email et l'objet du message -->
			<div class="field">
				<label for="field_email">Email:</label>
				<input type="email" name="field_email" placeholder="Adresse email" required />
			</div>
			<div class="field">
				<label for="field_subject">Objet:</label>
				<input type="text" name="field_subject" placeholder="Objet du message" required />
			</div>
			<!-- Fin des champs fixes -->
			<div class="field">
				<span class="handle">☰</span>
				<input type="text" name="field_label[]" placeholder="Label du champ" required />
				<select name="field_type[]">
					<option value="text">Texte</option>
					<option value="email">Email</option>
					<option value="number">Nombre</option>
				</select>
				<button type="button" onclick="removeField(this)">Supprimer</button>
			</div>
		</div>
		<button type="button" onclick="addField()">Ajouter un champ</button>
		<input type="submit" value="Enregistrer le formulaire">
	</form>
</div>

<script>
	document.addEventListener('DOMContentLoaded', function () {
		var el = document.getElementById('formFields');
		var sortable = Sortable.create(el, {
			animation: 150,
			ghostClass: 'sortable-ghost',
			handle: '.handle'
		});

		document.querySelector('#formCreator').addEventListener('submit', function(event) {
			event.preventDefault();

			var formData = new FormData(event.target);
			formData.append('_ajax_nonce', '<?php echo wp_create_nonce("cocoform_save_form_nonce"); ?>');
			formData.append('action', 'cocoform_save_form');

			fetch(ajaxurl, {
				method: 'POST',
				body: new URLSearchParams(formData)
			})
				.then(response => response.json())
				.then(data => {
					if (data.success) {
						alert('Formulaire ajouté avec succès.');
						window.location.href = '<?php echo admin_url('admin.php?page=cocoform'); ?>';
					} else {
						alert('Erreur : ' + data.data);
					}
				})
				.catch(error => {
					console.error('Erreur:', error);
					alert('Erreur lors de la soumission du formulaire.');
				});
		});
	});

	function addField() {
		var container = document.getElementById('formFields');
		var newField = document.createElement('div');
		newField.className = 'field';
		newField.innerHTML = `
        <span class="handle">☰</span>
        <input type="text" name="field_label[]" placeholder="Label du champ" required />
        <select name="field_type[]">
            <option value="text">Texte</option>
            <option value="email">Email</option>
            <option value="number">Nombre</option>
        </select>
        <button type="button" onclick="removeField(this)">Supprimer</button>
    `;
		container.appendChild(newField);
	}

	function removeField(button) {
		button.parentNode.remove();
	}
</script>
</body>
</html>

