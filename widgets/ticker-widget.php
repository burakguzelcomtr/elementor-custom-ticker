<?php
/**
 * Ticker Widget
 *
 * @since 1.0.0
 */
class Elementor_Ticker_Widget extends \Elementor\Widget_Base {

    /**
     * Get widget name.
     *
     * @since 1.0.0
     * @access public
     * @return string Widget name.
     */
    public function get_name() {
        return 'ticker';
    }

    /**
     * Get widget title.
     *
     * @since 1.0.0
     * @access public
     * @return string Widget title.
     */
    public function get_title() {
        return esc_html__('Ticker', 'elementor-custom-ticker');
    }

    /**
     * Get widget icon.
     *
     * @since 1.0.0
     * @access public
     * @return string Widget icon.
     */
    public function get_icon() {
        return 'eicon-animation-text';
    }

    /**
     * Get widget categories.
     *
     * @since 1.0.0
     * @access public
     * @return array Widget categories.
     */
    public function get_categories() {
        return ['general'];
    }

    /**
     * Get widget keywords.
     *
     * @since 1.0.0
     * @access public
     * @return array Widget keywords.
     */
    public function get_keywords() {
        return ['ticker', 'scroll', 'news', 'marquee'];
    }

    /**
     * Register widget controls.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function register_controls() {
        // Content Section
        $this->start_controls_section(
            'section_content',
            [
                'label' => esc_html__('Content', 'elementor-custom-ticker'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        // Repeater for ticker items
        $repeater = new \Elementor\Repeater();

        $repeater->add_control(
            'show_image',
            [
                'label' => esc_html__('Show Image', 'elementor-custom-ticker'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'elementor-custom-ticker'),
                'label_off' => esc_html__('No', 'elementor-custom-ticker'),
                'return_value' => 'yes',
                'default' => 'no',
            ]
        );

        $repeater->add_control(
            'ticker_image',
            [
                'label' => esc_html__('Image', 'elementor-custom-ticker'),
                'type' => \Elementor\Controls_Manager::MEDIA,
                'default' => [
                    'url' => \Elementor\Utils::get_placeholder_image_src(),
                ],
                'condition' => [
                    'show_image' => 'yes',
                ],
            ]
        );

        $repeater->add_responsive_control(
            'image_size',
            [
                'label' => esc_html__('Image Size', 'elementor-custom-ticker'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 20,
                        'max' => 200,
                        'step' => 1,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 40,
                ],
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}} .elementor-ticker-image img' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'show_image' => 'yes',
                ],
            ]
        );

        $repeater->add_control(
            'image_border_radius',
            [
                'label' => esc_html__('Image Border Radius', 'elementor-custom-ticker'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}} .elementor-ticker-image img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'show_image' => 'yes',
                ],
            ]
        );

        $repeater->add_control(
            'ticker_text',
            [
                'label' => esc_html__('Ticker Text', 'elementor-custom-ticker'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => esc_html__('Ticker Item', 'elementor-custom-ticker'),
                'label_block' => true,
            ]
        );

        $repeater->add_control(
            'ticker_link',
            [
                'label' => esc_html__('Link', 'elementor-custom-ticker'),
                'type' => \Elementor\Controls_Manager::URL,
                'placeholder' => esc_html__('https://your-link.com', 'elementor-custom-ticker'),
                'show_external' => true,
                'default' => [
                    'url' => '',
                    'is_external' => true,
                    'nofollow' => true,
                ],
            ]
        );

        $repeater->add_control(
            'item_background_color',
            [
                'label' => esc_html__('Item Background Color', 'elementor-custom-ticker'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}}' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $repeater->add_control(
            'item_text_color',
            [
                'label' => esc_html__('Item Text Color', 'elementor-custom-ticker'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}}' => 'color: {{VALUE}}',
                ],
            ]
        );

        $repeater->add_control(
            'item_border_radius',
            [
                'label' => esc_html__('Item Border Radius', 'elementor-custom-ticker'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}}' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $repeater->add_responsive_control(
            'item_padding',
            [
                'label' => esc_html__('Item Padding', 'elementor-custom-ticker'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}}' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'ticker_items',
            [
                'label' => esc_html__('Ticker Items', 'elementor-custom-ticker'),
                'type' => \Elementor\Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [
                        'ticker_text' => esc_html__('Ticker Item #1', 'elementor-custom-ticker'),
                    ],
                    [
                        'ticker_text' => esc_html__('Ticker Item #2', 'elementor-custom-ticker'),
                    ],
                    [
                        'ticker_text' => esc_html__('Ticker Item #3', 'elementor-custom-ticker'),
                    ],
                ],
                'title_field' => '{{{ ticker_text }}}',
            ]
        );

        $this->add_control(
            'speed',
            [
                'label' => esc_html__('Scroll Speed', 'elementor-custom-ticker'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 10,
                        'max' => 200,
                        'step' => 5,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 50,
                ],
            ]
        );

        $this->add_control(
            'direction',
            [
                'label' => esc_html__('Direction', 'elementor-custom-ticker'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'left',
                'options' => [
                    'left' => esc_html__('Left', 'elementor-custom-ticker'),
                    'right' => esc_html__('Right', 'elementor-custom-ticker'),
                ],
            ]
        );

        $this->add_control(
            'pause_on_hover',
            [
                'label' => esc_html__('Pause on Hover', 'elementor-custom-ticker'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'elementor-custom-ticker'),
                'label_off' => esc_html__('No', 'elementor-custom-ticker'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'vertical_alignment',
            [
                'label' => esc_html__('Vertical Alignment', 'elementor-custom-ticker'),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'flex-start' => [
                        'title' => esc_html__('Top', 'elementor-custom-ticker'),
                        'icon' => 'eicon-v-align-top',
                    ],
                    'center' => [
                        'title' => esc_html__('Middle', 'elementor-custom-ticker'),
                        'icon' => 'eicon-v-align-middle',
                    ],
                    'flex-end' => [
                        'title' => esc_html__('Bottom', 'elementor-custom-ticker'),
                        'icon' => 'eicon-v-align-bottom',
                    ],
                ],
                'default' => 'center',
                'selectors' => [
                    '{{WRAPPER}} .elementor-ticker-wrapper' => 'align-items: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Style Section
        $this->start_controls_section(
            'section_style',
            [
                'label' => esc_html__('Ticker Style', 'elementor-custom-ticker'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'ticker_background_color',
            [
                'label' => esc_html__('Background Color', 'elementor-custom-ticker'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-ticker-wrapper' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'ticker_typography',
                'selector' => '{{WRAPPER}} .elementor-ticker-item',
            ]
        );

        $this->add_control(
            'ticker_text_color',
            [
                'label' => esc_html__('Text Color', 'elementor-custom-ticker'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-ticker-item' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'ticker_link_color',
            [
                'label' => esc_html__('Link Color', 'elementor-custom-ticker'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-ticker-item a' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'ticker_link_hover_color',
            [
                'label' => esc_html__('Link Hover Color', 'elementor-custom-ticker'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-ticker-item a:hover' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'ticker_padding',
            [
                'label' => esc_html__('Padding', 'elementor-custom-ticker'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .elementor-ticker-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'ticker_item_spacing',
            [
                'label' => esc_html__('Items Spacing', 'elementor-custom-ticker'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 20,
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-ticker-item' => 'margin-right: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'ticker_border',
                'selector' => '{{WRAPPER}} .elementor-ticker-wrapper',
            ]
        );

        $this->add_control(
            'ticker_border_radius',
            [
                'label' => esc_html__('Border Radius', 'elementor-custom-ticker'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .elementor-ticker-wrapper' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Render widget output on the frontend.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function render() {
        $settings = $this->get_settings_for_display();

        if (empty($settings['ticker_items'])) {
            return;
        }

        $this->add_render_attribute('wrapper', 'class', 'elementor-ticker-wrapper');
        $this->add_render_attribute('wrapper', 'data-speed', $settings['speed']['size']);
        $this->add_render_attribute('wrapper', 'data-direction', $settings['direction']);
        $this->add_render_attribute('wrapper', 'data-pause-on-hover', $settings['pause_on_hover']);

        // Ensure vertical_alignment has a value (for backward compatibility)
        $vertical_alignment = isset($settings['vertical_alignment']) ? $settings['vertical_alignment'] : 'center';
        $this->add_render_attribute('wrapper', 'data-vertical-alignment', $vertical_alignment);
        ?>
        <div <?php echo $this->get_render_attribute_string('wrapper'); ?>>
            <div class="elementor-ticker-items">
                <?php foreach ($settings['ticker_items'] as $index => $item) :
                    $item_key = $this->get_repeater_setting_key('ticker_item', 'ticker_items', $index);
                    $this->add_render_attribute($item_key, 'class', [
                        'elementor-ticker-item',
                        'elementor-repeater-item-' . $item['_id'],
                    ]);
                ?>
                    <div <?php echo $this->get_render_attribute_string($item_key); ?>>
                        <?php if (!empty($item['ticker_link']['url'])) :
                            $this->add_link_attributes('ticker-link-' . $index, $item['ticker_link']);
                            $tag_open = '<a ' . $this->get_render_attribute_string('ticker-link-' . $index) . '>';
                            $tag_close = '</a>';
                        else :
                            $tag_open = '';
                            $tag_close = '';
                        endif; ?>

                        <?php if (isset($item['show_image']) && $item['show_image'] === 'yes' && !empty($item['ticker_image']['url'])) : ?>
                            <div class="elementor-ticker-image">
                                <?php echo $tag_open; ?>
                                <img src="<?php echo esc_url($item['ticker_image']['url']); ?>" alt="<?php echo esc_attr($item['ticker_text']); ?>">
                                <?php echo $tag_close; ?>
                            </div>
                        <?php endif; ?>

                        <div class="elementor-ticker-content">
                            <?php echo $tag_open; ?>
                            <?php echo esc_html($item['ticker_text']); ?>
                            <?php echo $tag_close; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php
    }
}
