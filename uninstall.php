<?php
if (!defined('WP_UNINSTALL_PLUGIN')) {
	exit; // Exit if accessed directly.
}

// Supprimer toutes les options de formulaire
global $wpdb;
$prefix = 'cocoform_';
$options = $wpdb->get_results(
	$wpdb->prepare(
		"SELECT option_name FROM $wpdb->options WHERE option_name LIKE %s",
		$wpdb->esc_like($prefix) . '%'
	),
	ARRAY_A
);

foreach ($options as $option) {
	delete_option($option['option_name']);
}

// Supprimer l'option cocoform_formulaires
delete_option('cocoform_formulaires');
