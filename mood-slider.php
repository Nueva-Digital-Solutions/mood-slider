<?php
/**
 * Plugin Name: Mood Slider
 * Plugin URI: https://github.com/Nueva-Digital-Solutions/mood-slider
 * Description: A beautiful, interactive mood slider for your WordPress site.
 * Version: 1.0.0
 * Author: Nueva digital solutions
 * Author URI: https://nuevadigital.co.in
 * License: GPL2
 * Text Domain: mood-slider
 */

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly.
}

// Define plugin constants
define('MOOD_SLIDER_VERSION', '1.1.0');
define('MOOD_SLIDER_PATH', plugin_dir_path(__FILE__));
define('MOOD_SLIDER_URL', plugin_dir_url(__FILE__));

// Include necessary files
require_once MOOD_SLIDER_PATH . 'includes/class-mood-slider-shortcode.php';
require_once MOOD_SLIDER_PATH . 'includes/class-mood-slider-settings.php';

// Initialize the plugin
function mood_slider_init()
{
	new Mood_Slider_Shortcode();
	if (is_admin()) {
		new Mood_Slider_Settings();
	}

	// Register Elementor widget if Elementor is active
	add_action('elementor/widgets/register', 'mood_slider_register_elementor_widget');
}
add_action('plugins_loaded', 'mood_slider_init');

function mood_slider_register_elementor_widget($widgets_manager)
{
	require_once MOOD_SLIDER_PATH . 'includes/class-mood-slider-elementor.php';
	$widgets_manager->register(new \Mood_Slider_Elementor_Widget());
}

// Enqueue styles and scripts
function mood_slider_enqueue_assets()
{
	wp_enqueue_style('mood-slider-style', MOOD_SLIDER_URL . 'assets/css/mood-slider.css', array(), MOOD_SLIDER_VERSION);
	wp_enqueue_script('mood-slider-script', MOOD_SLIDER_URL . 'assets/js/mood-slider.js', array(), MOOD_SLIDER_VERSION, true);
}
add_action('wp_enqueue_scripts', 'mood_slider_enqueue_assets');
