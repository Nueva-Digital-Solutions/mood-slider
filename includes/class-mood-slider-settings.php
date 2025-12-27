<?php
if (!defined('ABSPATH')) {
    exit;
}

class Mood_Slider_Settings
{
    public function __construct()
    {
        add_action('admin_menu', array($this, 'add_settings_page'));
        add_action('admin_init', array($this, 'register_settings'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_assets'));
    }

    public function add_settings_page()
    {
        add_options_page(
            'Mood Slider Settings',
            'Mood Slider',
            'manage_options',
            'mood-slider',
            array($this, 'render_settings_page')
        );
    }

    public function enqueue_admin_assets($hook)
    {
        if ('settings_page_mood-slider' !== $hook) {
            return;
        }
        // Enqueue a simple emoji picker library from CDN or local if possible.
        // For simplicity and immediate effect, we'll use a lightweight script and some custom admin CSS.
        wp_enqueue_style('mood-slider-admin-css', MOOD_SLIDER_URL . 'assets/css/admin-style.css', array(), MOOD_SLIDER_VERSION);
        wp_enqueue_script('mood-slider-admin-js', MOOD_SLIDER_URL . 'assets/js/admin-script.js', array('jquery'), MOOD_SLIDER_VERSION, true);
    }

    public function register_settings()
    {
        // Colors
        register_setting('mood_slider_options', 'ms_primary_color');
        register_setting('mood_slider_options', 'ms_bg_color');

        // Typography & Sizing
        register_setting('mood_slider_options', 'ms_title_size');
        register_setting('mood_slider_options', 'ms_subtitle_size');
        register_setting('mood_slider_options', 'ms_mood_text_size');
        register_setting('mood_slider_options', 'ms_desc_size');
        register_setting('mood_slider_options', 'ms_cta_size');
        register_setting('mood_slider_options', 'ms_emoji_size');

        // Steps
        register_setting('mood_slider_options', 'ms_mood_steps');
    }

    public function render_settings_page()
    {
        $default_steps = array(
            array('emoji' => '😔', 'text' => 'Heavy', 'description' => 'Feeling heavy.', 'cta_text' => 'Talk', 'cta_link' => '#'),
            array('emoji' => '😐', 'text' => 'Managing', 'description' => 'Just managing.', 'cta_text' => 'Explore', 'cta_link' => '#'),
            array('emoji' => '😊', 'text' => 'Good', 'description' => 'Feeling good.', 'cta_text' => 'Share', 'cta_link' => '#')
        );
        $saved_steps = get_option('ms_mood_steps', $default_steps);
        if (!is_array($saved_steps))
            $saved_steps = $default_steps;
        ?>
        <div class="wrap ms-admin-wrapper">
            <h1 class="wp-heading-inline">Mood Slider Design & Settings</h1>
            <p class="ms-intro">Customize the look and feel of your interactive mood slider. These settings apply globally.</p>

            <form method="post" action="options.php" class="ms-admin-form">
                <?php
                settings_fields('mood_slider_options');
                do_settings_sections('mood-slider');
                ?>

                <div class="ms-card">
                    <h2>🎨 Colors & Sizes</h2>
                    <div class="ms-grid-2">
                        <div class="ms-field-group">
                            <label>Primary Color</label>
                            <input type="color" name="ms_primary_color"
                                value="<?php echo esc_attr(get_option('ms_primary_color', '#0f766e')); ?>" />
                            <p class="description">Used for slider thumb, button, and range.</p>
                        </div>
                        <div class="ms-field-group">
                            <label>Background Color</label>
                            <input type="color" name="ms_bg_color"
                                value="<?php echo esc_attr(get_option('ms_bg_color', '#f9fafb')); ?>" />
                            <p class="description">Container background.</p>
                        </div>
                    </div>

                    <hr>

                    <div class="ms-grid-3">
                        <div class="ms-field-group">
                            <label>Emoji Size (px)</label>
                            <input type="number" name="ms_emoji_size"
                                value="<?php echo esc_attr(get_option('ms_emoji_size', '32')); ?>" class="small-text">
                        </div>
                        <div class="ms-field-group">
                            <label>Title Size (px)</label>
                            <input type="number" name="ms_title_size"
                                value="<?php echo esc_attr(get_option('ms_title_size', '24')); ?>" class="small-text">
                        </div>
                        <div class="ms-field-group">
                            <label>Subtitle Size (px)</label>
                            <input type="number" name="ms_subtitle_size"
                                value="<?php echo esc_attr(get_option('ms_subtitle_size', '14')); ?>" class="small-text">
                        </div>
                        <div class="ms-field-group">
                            <label>Mood Text Size (px)</label>
                            <input type="number" name="ms_mood_text_size"
                                value="<?php echo esc_attr(get_option('ms_mood_text_size', '20')); ?>" class="small-text">
                        </div>
                        <div class="ms-field-group">
                            <label>Description Size (px)</label>
                            <input type="number" name="ms_desc_size"
                                value="<?php echo esc_attr(get_option('ms_desc_size', '14')); ?>" class="small-text">
                        </div>
                        <div class="ms-field-group">
                            <label>CTA Button Size (px)</label>
                            <input type="number" name="ms_cta_size"
                                value="<?php echo esc_attr(get_option('ms_cta_size', '14')); ?>" class="small-text">
                        </div>
                    </div>
                </div>

                <div class="ms-card">
                    <h2>😁 Mood Steps</h2>
                    <p>Configure the steps for your slider. Drag and drop reordering coming soon.</p>

                    <div id="ms-steps-wrapper">
                        <?php foreach ($saved_steps as $index => $step):
                            $step = wp_parse_args($step, array('emoji' => '😐', 'text' => '', 'description' => '', 'cta_text' => '', 'cta_link' => ''));
                            ?>
                            <div class="ms-step-item">
                                <div class="ms-step-header">
                                    <span class="ms-step-count">Step <?php echo $index + 1; ?></span>
                                    <button type="button" class="button ms-remove-step">Remove</button>
                                </div>
                                <div class="ms-step-body">
                                    <div class="ms-emoji-picker-wrapper">
                                        <label>Emoji</label>
                                        <input type="text" name="ms_mood_steps[<?php echo $index; ?>][emoji]"
                                            value="<?php echo esc_attr($step['emoji']); ?>" class="ms-emoji-input" readonly />
                                        <div class="ms-emoji-grid" style="display:none;">
                                            <?php
                                            $emojis = ['😔', '😫', '😐', '🙂', '😊', '🥰', '😎', '🤔', '😴', '😭', '😡', '🥳', '😇', '🤢', '🤮', '🤧', '🤒', '🤕', '🤑', '🤠'];
                                            foreach ($emojis as $e) {
                                                echo '<span class="ms-emoji-option">' . $e . '</span>';
                                            }
                                            ?>
                                        </div>
                                    </div>
                                    <div class="ms-flex-grow">
                                        <label>Mood Title</label>
                                        <input type="text" name="ms_mood_steps[<?php echo $index; ?>][text]"
                                            value="<?php echo esc_attr($step['text']); ?>" class="widefat" />
                                    </div>
                                    <div class="ms-full-width">
                                        <label>Description</label>
                                        <textarea name="ms_mood_steps[<?php echo $index; ?>][description]" class="widefat"
                                            rows="2"><?php echo esc_textarea($step['description']); ?></textarea>
                                    </div>
                                    <div class="ms-half-width">
                                        <label>CTA Text</label>
                                        <input type="text" name="ms_mood_steps[<?php echo $index; ?>][cta_text]"
                                            value="<?php echo esc_attr($step['cta_text']); ?>" class="widefat" />
                                    </div>
                                    <div class="ms-half-width">
                                        <label>CTA Link</label>
                                        <input type="text" name="ms_mood_steps[<?php echo $index; ?>][cta_link]"
                                            value="<?php echo esc_attr($step['cta_link']); ?>" class="widefat" />
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <button type="button" class="button button-primary button-large" id="ms-add-step"> + Add New Step</button>
                </div>

                <?php submit_button('Save Settings', 'primary large', 'submit', true); ?>
            </form>
        </div>
        <?php
    }
}
