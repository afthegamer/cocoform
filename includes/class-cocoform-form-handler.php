<?php

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly.
}

class CocoForm_Form_Handler {

	public function __construct() {
		add_action('wp_ajax_cocoform_save_form', array($this, 'save_form'));
		add_action('wp_ajax_cocoform_delete_form', array($this, 'delete_form'));
	}

	public function save_form() {
		check_ajax_referer('cocoform_save_form_nonce', '_ajax_nonce');

		if (!current_user_can('manage_options')) {
			wp_send_json_error('Permissions insuffisantes.');
		}

		$form_name = sanitize_text_field($_POST['form_name']);
		$fields = [];

		if (isset($_POST['field_email'])) {
			$fields[] = ['label' => 'Email', 'type' => 'email', 'default' => sanitize_email($_POST['field_email'])];
		}
		if (isset($_POST['field_subject_type']) && $_POST['field_subject_type'] === 'text') {
			$fields[] = ['label' => 'Objet', 'type' => 'text', 'default' => sanitize_text_field($_POST['field_subject_text'])];
		} else {
			$options = array_map('sanitize_text_field', $_POST['field_subject_options']);
			$fields[] = ['label' => 'Objet', 'type' => 'select', 'options' => $options];
		}

		if (isset($_POST['field_label']) && is_array($_POST['field_label'])) {
			$field_types = $_POST['field_type'];
			foreach ($_POST['field_label'] as $index => $label) {
				if (isset($field_types[$index]) && $label !== 'Email' && $label !== 'Objet') {
					$fields[] = ['label' => sanitize_text_field($label), 'type' => sanitize_text_field($field_types[$index])];
				}
			}
		}

		$form_data = [
			'name' => $form_name,
			'fields' => $fields
		];

		$option_key = 'cocoform_' . sanitize_title($form_name);
		update_option($option_key, $form_data);

		wp_send_json_success('Formulaire ajouté.');
	}

	public function delete_form() {
		check_ajax_referer('cocoform_save_form_nonce', '_ajax_nonce');

		if (!current_user_can('manage_options')) {
			wp_send_json_error('Permissions insuffisantes.');
		}

		if (!isset($_POST['form_id'])) {
			wp_send_json_error('ID du formulaire manquant.');
		}

		$form_id = sanitize_text_field($_POST['form_id']);
		$option_key = $form_id;

		if (delete_option($option_key)) {
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
}

//new CocoForm_Form_Handler();
