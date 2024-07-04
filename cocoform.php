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

// Inclure les classes nécessaires
require_once plugin_dir_path(__FILE__) . 'includes/class-cocoform.php';
require_once plugin_dir_path(__FILE__) . 'includes/class-cocoform-admin.php';
require_once plugin_dir_path(__FILE__) . 'includes/class-cocoform-form-handler.php';

// Initialiser les classes
new CocoForm();
new CocoForm_Admin();
new CocoForm_Form_Handler();
