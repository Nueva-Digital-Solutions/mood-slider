<?php
if (!defined('ABSPATH')) {
    exit;
}

class Mood_Slider_Elementor_Widget extends \Elementor\Widget_Base
{

    public function get_name()
    {
        return 'mood_slider';
    }

    public function get_title()
    {
        return esc_html__('Mood Slider', 'mood-slider');
    }

    public function get_icon()
    {
        return 'eicon-slider-push';
    }

    public function get_categories()
    {
        return ['general'];
    }

    protected function register_controls()
    {
        $this->start_controls_section(
            'content_section',
            [
                'label' => esc_html__('Content', 'mood-slider'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'title',
            [
                'label' => esc_html__('Title', 'mood-slider'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => esc_html__('Where are you right now?', 'mood-slider'),
            ]
        );

        $this->add_control(
            'subtitle',
            [
                'label' => esc_html__('Subtitle', 'mood-slider'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => esc_html__('No labels. Just slide.', 'mood-slider'),
            ]
        );

        $this->add_control(
            'global_note',
            [
                'type' => \Elementor\Controls_Manager::RAW_HTML,
                'raw' => '<div style="background:#f0fafe; padding:10px; border-radius:4px; font-size:12px; color:#333;">Mood steps, emojis, and typography are managed globally in <b>Settings > Mood Slider</b>.</div>',
                'content_classes' => 'elementor-descriptor',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'style_section',
            [
                'label' => esc_html__('Styles', 'mood-slider'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'primary_color',
            [
                'label' => esc_html__('Primary Color (Override)', 'mood-slider'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#0f766e',
                'description' => 'Leave empty to use global setting.',
                'selectors' => [
                    '{{WRAPPER}} .mood-container' => '--ms-primary-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'bg_color',
            [
                'label' => esc_html__('Background Color (Override)', 'mood-slider'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#f9fafb',
                'description' => 'Leave empty to use global setting.',
                'selectors' => [
                    '{{WRAPPER}} .mood-container' => '--ms-bg-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();

        // Use global steps with defaults
        $default_steps = array(
            array('emoji' => '😔', 'text' => 'Heavy', 'description' => 'Feeling heavy.', 'cta_text' => 'Talk', 'cta_link' => '#'),
            array('emoji' => '😐', 'text' => 'Managing', 'description' => 'Just managing.', 'cta_text' => 'Explore', 'cta_link' => '#'),
            array('emoji' => '😊', 'text' => 'Good', 'description' => 'Feeling good.', 'cta_text' => 'Share', 'cta_link' => '#')
        );
        $steps = get_option('ms_mood_steps', $default_steps);
        if (empty($steps) || !is_array($steps)) {
            $steps = $default_steps;
        }

        $config = [
            'steps' => array_values($steps)
        ];
        // Inject Global Styles for this instance if not handled by generic CSS variables
        $global_title_size = get_option('ms_title_size', '24');
        $global_emoji_size = get_option('ms_emoji_size', '32');
        // ... pass these as CSS variables to the container
        ?>
        <div class="mood-container" data-config="<?php echo esc_attr(json_encode($config, JSON_UNESCAPED_UNICODE)); ?>" style="
                 --ms-title-size: <?php echo esc_attr($global_title_size); ?>px;
                --ms-emoji-size: <?php echo esc_attr($global_emoji_size); ?>px;
             ">
            <h2><?php echo esc_html($settings['title']); ?></h2>
            <p class="subtitle"><?php echo esc_html($settings['subtitle']); ?></p>

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
    }
}
