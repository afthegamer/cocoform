<?php
// Assurez-vous que WordPress est chargé
if (!defined('ABSPATH')) {
	exit; // Ne pas exécuter le fichier directement
}

// Récupérer l'ID du formulaire depuis la requête
$form_id = isset($_GET['id']) ? $_GET['id'] : '';

if (!empty($form_id)) {
	// Supprimer l'option du formulaire
	delete_option($form_id);

	// Mettre à jour la liste des formulaires
	$formulaires = get_option('cocoform_formulaires', []);
	$short_id = str_replace('cocoform_', '', $form_id); // Supprimer le préfixe
	if (isset($formulaires[$short_id])) {
		unset($formulaires[$short_id]);
		update_option('cocoform_formulaires', $formulaires);
	}

	// Rediriger vers la page d'administration des formulaires
	wp_redirect(admin_url('admin.php?page=cocoform'));
	exit;
} else {
	echo "Formulaire non trouvé.";
	exit;
}
?>
