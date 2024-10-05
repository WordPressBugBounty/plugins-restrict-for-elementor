<?php

namespace Elementor;

if ( !class_exists( 'Restrict_Elementor_Elementor_Control' ) ) {
    class Restrict_Elementor_Elementor_Control {
        function __construct() {
            add_action( 'elementor/element/common/_section_style/after_section_end', array($this, 'elementor_add_section') );
            add_action( 'elementor/element/column/section_advanced/after_section_end', array($this, 'elementor_add_section') );
            add_action( 'elementor/element/section/section_advanced/after_section_end', array($this, 'elementor_add_section') );
            add_action( 'elementor/element/container/_section_responsive/after_section_end', array($this, 'elementor_add_section') );
            add_action(
                'elementor/element/common/restrict_for_elementor_section/before_section_end',
                array($this, 'elementor_add_controls'),
                10,
                2
            );
            add_action(
                'elementor/element/section/restrict_for_elementor_section/before_section_end',
                array($this, 'elementor_add_controls'),
                10,
                2
            );
            add_action(
                'elementor/element/column/restrict_for_elementor_section/before_section_end',
                array($this, 'elementor_add_controls'),
                10,
                2
            );
            add_action(
                'elementor/element/container/restrict_for_elementor_section/before_section_end',
                array($this, 'elementor_add_controls'),
                10,
                2
            );
            add_filter(
                'elementor/widget/render_content',
                array($this, 'widget_render_content'),
                999,
                2
            );
            add_filter(
                'elementor/frontend/section/should_render',
                array($this, 'elementor_should_render'),
                10,
                2
            );
            add_filter(
                'elementor/frontend/column/should_render',
                array($this, 'elementor_should_render'),
                10,
                2
            );
            add_filter(
                'elementor/frontend/widget/should_render',
                array($this, 'elementor_should_render'),
                10,
                2
            );
            add_filter(
                'elementor/frontend/repeater/should_render',
                array($this, 'elementor_should_render'),
                10,
                2
            );
            add_filter(
                'elementor/frontend/container/should_render',
                array($this, 'elementor_should_render'),
                10,
                2
            );
            add_action( 'restrict_for_elementor_rest_api', array($this, 'elementor_restrict_rest_api') );
        }

        function widget_render_content( $content, $widget ) {
            $settings = $widget->get_settings();
            if ( !Restrict_Elementor_Elementor_Control::elementor_should_render_helper( $settings ) && !\Elementor\Plugin::$instance->editor->is_edit_mode() ) {
                do_action(
                    'restrict_for_elementor_widget_render_content',
                    $settings,
                    $content,
                    $widget
                );
                return '';
            }
            return $content;
        }

        function elementor_should_render( $should_render, $section ) {
            $settings = $section->get_settings();
            if ( !Restrict_Elementor_Elementor_Control::elementor_should_render_helper( $settings ) ) {
                return false;
            }
            return $should_render;
        }

        private function hide_control() {
            if ( rfe_fs()->can_use_premium_code() ) {
                return true;
            }
            return false;
        }

        function elementor_add_section( $element ) {
            $label = 'Restrict 🔒';
            $element->start_controls_section( 'restrict_for_elementor_section', [
                'tab'   => Controls_Manager::TAB_CONTENT,
                'label' => $label,
            ] );
            $element->end_controls_section();
        }

        function elementor_add_controls( $element, $args ) {
            $element->add_control( 'restrict_for_elementor_action', [
                'description'  => __( 'Show or hide content based on the selected criteria', 'restrict-for-elementor' ),
                'label'        => __( 'Action: ', 'restrict-for-elementor' ),
                'show_label'   => true,
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => __( 'Show', 'restrict-for-elementor' ),
                'label_off'    => __( 'Hide', 'restrict-for-elementor' ),
                'return_value' => 'yes',
                'default'      => 'yes',
                'conditions'   => [
                    'relation' => 'and',
                    'terms'    => [[
                        'name'     => 'restrict_for_elementor_show_to',
                        'operator' => '!==',
                        'value'    => 'everyone',
                    ]],
                ],
            ] );
            $main_options = apply_filters( 'restrict_for_elementor_show_to_main_options', array(
                'everyone' => __( '-- No Restrictions --', 'restrict-for-elementor' ),
            ) );
            $element->add_control( 'restrict_for_elementor_show_to', [
                'label'       => __( 'Criteria:', 'restrict-for-elementor' ),
                'show_label'  => false,
                'label_block' => true,
                'type'        => Controls_Manager::SELECT,
                'default'     => 'everyone',
                'options'     => $main_options,
            ] );
            do_action( 'restrict_for_elementor_add_controls', $element, $args );
            if ( !$this->hide_control() ) {
                $element->add_control( 'restrict_for_elementor_go_pro_hr', [
                    'type' => Controls_Manager::DIVIDER,
                ] );
                $element->add_control( 'restrict_for_elementor_go_pro_message', [
                    'label'           => __( 'Go Pro', 'restrict-for-elementor' ),
                    'label_block'     => true,
                    'show_label'      => false,
                    'type'            => Controls_Manager::RAW_HTML,
                    'raw'             => '<a href="' . admin_url( 'admin.php?page=restrict_for_elementor_settings-pricing' ) . '" target="_blank">GO PRO and get all Restrict options today!</a>',
                    'content_classes' => 'restrict_for_elementor_go_pro_message',
                ] );
            }
        }

        function elementor_content_change( $content, $widget ) {
            if ( Plugin::$instance->editor->is_edit_mode() ) {
                return $content;
            }
            $settings = $widget->get_settings();
            if ( !Restrict_Elementor_Elementor_Control::elementor_should_render_helper( $settings ) ) {
                return;
            }
            return $content;
        }

        /**
         * Restrict elementor's contents handler.
         *
         * @param $settings 'Element's configured settings'
         * @return bool|mixed|void
         */
        public static function elementor_should_render_helper( $settings ) {
            $should_render = true;
            if ( $settings['restrict_for_elementor_show_to'] !== 'everyone' ) {
                $should_render = apply_filters( 'restrict_for_elementor_should_render_' . $settings['restrict_for_elementor_show_to'], $should_render, $settings );
            }
            return $should_render;
        }

        function elementor_restrict_rest_api() {
            if ( wp_is_serving_rest_request() ) {
                /**
                 * Requiring password on excerpt to ensure data is not disclosed via REST API.
                 * filter hook 'rest_post_dispatch' in wp-includes/rest-api/class-wp-rest-server.php at serve_request function
                 *
                 * @param $result
                 * @param $class
                 * @param $request
                 * @return mixed
                 */
                add_filter( 'rest_post_dispatch', function ( $result ) {
                    $excerpt = wp_trim_excerpt( '' );
                    $result->data['excerpt']['rendered'] = $excerpt;
                    return $result;
                }, 10 );
            }
        }

    }

    new Restrict_Elementor_Elementor_Control();
}