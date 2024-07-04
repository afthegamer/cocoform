<?php

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly.
}

class CocoForm {

	public function __construct() {
		add_action('rest_api_init', array($this, 'register_rest_routes'));
		add_action('init', array($this, 'register_block'));
	}

	public function register_rest_routes() {
		register_rest_route('cocoform/v1', '/form/(?P<name>[a-zA-Z0-9-]+)', array(
			'methods' => 'GET',
			'callback' => array($this, 'get_form'),
			'permission_callback' => '__return_true',
		));
	}

	public function get_form($request) {
		$form_name = sanitize_title($request['name']);
		$option_key = 'cocoform_' . $form_name;
		$form_data = get_option($option_key);

		if (!$form_data) {
			return new WP_Error('no_form', 'Formulaire non trouvé', array('status' => 404));
		}

		return rest_ensure_response($form_data);
	}

	public function register_block() {
		register_block_type(__DIR__ . '/../build');
	}
}


