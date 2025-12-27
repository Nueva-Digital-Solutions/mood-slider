<?php
if (!defined('ABSPATH')) {
	exit;
}

class Mood_Slider_Shortcode
{
	public function __construct()
	{
		add_shortcode('mood_slider', array($this, 'render_shortcode'));
	}

	public function render_shortcode($atts)
	{
		$atts = shortcode_atts(array(
			'title' => 'Where are you right now?',
			'subtitle' => 'No labels. Just slide.',
		), $atts, 'mood_slider');

		// Get global settings (mocked for now, will be implemented in settings class)
		$primary_color = get_option('ms_primary_color', '#0f766e');
		$bg_color = get_option('ms_bg_color', '#f9fafb');

		// Fetch sizes
		$title_size = get_option('ms_title_size', '24');
		$subtitle_size = get_option('ms_subtitle_size', '14');
		$text_size = get_option('ms_mood_text_size', '20');
		$desc_size = get_option('ms_desc_size', '14');
		$cta_size = get_option('ms_cta_size', '14');
		$emoji_size = get_option('ms_emoji_size', '32');

		// Fetch global steps
		$default_steps = array(
			array('emoji' => '😔', 'text' => 'Heavy', 'description' => 'Feeling heavy.', 'cta_text' => 'Talk', 'cta_link' => '#'),
			array('emoji' => '😐', 'text' => 'Managing', 'description' => 'Just managing.', 'cta_text' => 'Explore', 'cta_link' => '#'),
			array('emoji' => '😊', 'text' => 'Good', 'description' => 'Feeling good.', 'cta_text' => 'Share', 'cta_link' => '#')
		);
		$steps = get_option('ms_mood_steps', $default_steps);

		// If empty (e.g. saved as empty array), force defaults
		if (empty($steps) || !is_array($steps)) {
			$steps = $default_steps;
		}

		$config = [
			'steps' => array_values($steps) // Ensure it's a list, not an object with indices
		];

		ob_start();
		?>
		<div class="mood-container" data-config="<?php echo esc_attr(json_encode($config, JSON_UNESCAPED_UNICODE)); ?>" style="
				--ms-primary-color: <?php echo esc_attr($primary_color); ?>; 
				--ms-bg-color: <?php echo esc_attr($bg_color); ?>;
				
				--ms-title-size: <?php echo esc_attr($title_size); ?>px;
				--ms-subtitle-size: <?php echo esc_attr($subtitle_size); ?>px;
				--ms-mood-text-size: <?php echo esc_attr($text_size); ?>px;
				--ms-desc-size: <?php echo esc_attr($desc_size); ?>px;
				--ms-cta-size: <?php echo esc_attr($cta_size); ?>px;
				--ms-emoji-size: <?php echo esc_attr($emoji_size); ?>px;
			">
			<h2><?php echo esc_html($atts['title']); ?></h2>
			<p class="subtitle"><?php echo esc_html($atts['subtitle']); ?></p>

			<div class="mood-slider-wrapper">
				<div class="mood-emoji-bubble">😐</div>
				<input type="range" min="0" max="100" value="50" class="mood-slider" />
			</div>

			<div class="mood-output">
				<h3 class="mood-text">Just managing</h3>
				<p class="mood-description">You don’t need fixing. Just a place that fits.</p>
			</div>

			<a href="#" class="cta-btn">
				Explore quietly
			</a>
		</div>
		<?php
		return ob_get_clean();
	}
}
