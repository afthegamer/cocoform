<?php
// Assurez-vous que WordPress est chargé
if (!defined('ABSPATH')) {
	exit; // Ne pas exécuter le fichier directement
}

// Récupérer l'ID du formulaire depuis la requête
$form_id = isset($_GET['id']) ? $_GET['id'] : '';
$option_key = $form_id;
$form_data = get_option($option_key);

// Vérifiez si le formulaire existe
if (!$form_data) {
	echo "Formulaire non trouvé.";
	exit;
}

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	// Collecter les données du formulaire
	$form_name = sanitize_text_field($_POST['form_name']);
	$fields = [];

	// Ajouter les champs Email et Objet avec leurs valeurs par défaut
	if (isset($_POST['field_email'])) {
		$fields[] = ['label' => 'Email', 'type' => 'email', 'default' => sanitize_email($_POST['field_email'])];
	}

	if ($_POST['field_subject_type'] === 'text') {
		$fields[] = ['label' => 'Objet', 'type' => 'text', 'default' => sanitize_text_field($_POST['field_subject_text'])];
	} else {
		$options = array_map('sanitize_text_field', $_POST['field_subject_options']);
		$fields[] = ['label' => 'Objet', 'type' => 'select', 'options' => $options];
	}

	// Vérifier et ajouter les champs dynamiques
	if (isset($_POST['field_label']) && is_array($_POST['field_label'])) {
		$field_types = $_POST['field_type'];
		foreach ($_POST['field_label'] as $index => $label) {
			if (isset($field_types[$index]) && $label !== 'Email' && $label !== 'Objet') {
				$fields[] = ['label' => sanitize_text_field($label), 'type' => sanitize_text_field($field_types[$index])];
			}
		}
	}

	// Préparation de la structure à enregistrer
	$form_data = [
		'name' => $form_name,
		'fields' => $fields
	];

	// Enregistrement des données dans la base de données WordPress
	update_option($option_key, $form_data);

	// Redirection vers la page de gestion des formulaires
	wp_redirect(admin_url('admin.php?page=cocoform'));
	exit;
}

// Extraction des champs Email et Objet pour les afficher correctement dans le formulaire
$email_value = '';
$subject_value = '';
$subject_type = 'text';
$subject_options = [];
$additional_fields = [];

foreach ($form_data['fields'] as $field) {
	if ($field['label'] === 'Email' && isset($field['default'])) {
		$email_value = $field['default'];
	} elseif ($field['label'] === 'Objet') {
		if ($field['type'] === 'text') {
			$subject_value = $field['default'];
			$subject_type = 'text';
		} elseif ($field['type'] === 'select') {
			$subject_options = $field['options'];
			$subject_type = 'select';
		}
	} else {
		$additional_fields[] = $field;
	}
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
	<meta charset="UTF-8">
	<title>Modifier Formulaire</title>
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

		.select-options {
			margin-top: 10px;
			margin-bottom: 10px;
		}

		.option-field {
			display: flex;
			align-items: center;
			margin-bottom: 5px;
		}

		.option-field input[type="text"] {
			flex: 1;
			margin-right: 10px;
		}

		.option-field button {
			padding: 5px 10px;
			background-color: #dc3232;
			color: white;
			border: none;
			border-radius: 4px;
			cursor: pointer;
		}

		.option-field button:hover {
			background-color: #a00;
		}
	</style>
</head>
<body>
<div class="wrap">
	<h1>Modifier le formulaire: <?php echo esc_html($form_data['name']); ?></h1>
	<form id="formEditor" method="post">
		<div class="form-name">
			<label for="form_name">Nom du formulaire:</label>
			<input type="text" id="form_name" name="form_name" value="<?php echo esc_attr($form_data['name']); ?>" required>
		</div>
		<div id="formFields" class="form-fields-container">
			<!-- Champs fixes pour l'email et l'objet du message -->
			<div class="field">
				<label for="field_email">Email:</label>
				<input type="email" name="field_email" value="<?php echo esc_attr($email_value); ?>" placeholder="Adresse email" required />
			</div>
			<div class="field">
				<label for="field_subject_type">Objet:</label>
				<select id="field_subject_type" name="field_subject_type" required>
					<option value="text" <?php echo $subject_type === 'text' ? 'selected' : ''; ?>>Texte libre</option>
					<option value="select" <?php echo $subject_type === 'select' ? 'selected' : ''; ?>>Liste déroulante</option>
				</select>
				<input type="text" id="field_subject_text" name="field_subject_text" value="<?php echo esc_attr($subject_value); ?>" placeholder="Objet du message" <?php echo $subject_type === 'select' ? 'style="display:none;"' : ''; ?> />
				<div id="field_subject_options" class="select-options" <?php echo $subject_type === 'text' ? 'style="display:none;"' : ''; ?>>
					<?php
					if (!empty($subject_options)) {
						foreach ($subject_options as $index => $option) {
							echo '<div class="option-field">';
							echo '<input type="text" name="field_subject_options[]" value="' . esc_attr($option) . '" placeholder="Option ' . ($index + 1) . '" />';
							echo '<button type="button" onclick="removeOption(this)">Supprimer</button>';
							echo '</div>';
						}
					}
					?>
					<button type="button" id="add-option-btn" onclick="addSubjectOption()">Ajouter une option</button>
				</div>
			</div>
			<!-- Fin des champs fixes -->
			<?php foreach ($additional_fields as $field): ?>
				<div class="field">
					<span class="handle">☰</span>
					<input type="text" name="field_label[]" value="<?php echo esc_attr($field['label']); ?>" required />
					<select name="field_type[]">
						<option value="text" <?php echo $field['type'] === 'text' ? 'selected' : ''; ?>>Texte</option>
						<option value="email" <?php echo $field['type'] === 'email' ? 'selected' : ''; ?>>Email</option>
						<option value="number" <?php echo $field['type'] === 'number' ? 'selected' : ''; ?>>Nombre</option>
					</select>
					<button type="button" onclick="removeField(this)">Supprimer</button>
				</div>
			<?php endforeach; ?>
		</div>
		<button type="button" onclick="addField()">Ajouter un champ</button>
		<input type="submit" value="Enregistrer les modifications">
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

		document.querySelector('#field_subject_type').addEventListener('change', function() {
			var subjectType = this.value;
			var subjectText = document.getElementById('field_subject_text');
			var subjectOptions = document.getElementById('field_subject_options');

			if (subjectType === 'text') {
				subjectText.style.display = 'block';
				subjectText.required = true;
				subjectOptions.style.display = 'none';
				subjectOptions.querySelectorAll('input').forEach(function(input) {
					input.required = false;
				});
			} else if (subjectType === 'select') {
				subjectText.style.display = 'none';
				subjectText.required = false;
				subjectOptions.style.display = 'block';
				subjectOptions.querySelectorAll('input').forEach(function(input) {
					input.required = true;
				});
			}
		});

		document.querySelector('#formEditor').addEventListener('submit', function(event) {
			var subjectType = document.querySelector('#field_subject_type').value;
			var subjectText = document.getElementById('field_subject_text');
			if (subjectType === 'select') {
				subjectText.required = false;  // Désactiver 'required' si non visible
			}

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
						alert('Formulaire modifié avec succès.');
						window.location.href = '<?php echo admin_url('admin.php?page=cocoform'); ?>';
					} else {
						alert('Erreur : ' + data.data);
					}
				})
				.catch(error => {
					console.error('Erreur:', error);
					alert('Erreur lors de la soumission du formulaire.');
				});

			event.preventDefault();
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

	function addSubjectOption() {
		var container = document.getElementById('field_subject_options');
		var optionCount = container.querySelectorAll('.option-field').length;
		var newOption = document.createElement('div');
		newOption.className = 'option-field';
		newOption.innerHTML = `
        <input type="text" name="field_subject_options[]" placeholder="Option ${optionCount + 1}" />
        <button type="button" onclick="removeOption(this)">Supprimer</button>
    `;
		container.insertBefore(newOption, document.getElementById('add-option-btn'));
	}

	function removeOption(button) {
		button.parentElement.remove();
	}
</script>
</body>
</html>
