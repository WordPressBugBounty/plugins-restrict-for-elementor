<?php

namespace Elementor;

if ( !class_exists( 'Restrict_Elementor_Addon_User_Capability' ) ) {

    class Restrict_Elementor_Addon_User_Capability {

        function __construct() {
            add_filter( 'restrict_for_elementor_show_to_main_options', array( $this, 'show_to_main_options' ) );
            add_filter( 'restrict_for_elementor_should_render_user_capability', array( $this, 'should_render' ), 10, 2 );
            add_action( 'restrict_for_elementor_add_controls', array( $this, 'add_control' ), 10, 2 );
        }

        function show_to_main_options( $array ) {
            $array[ 'user_capability' ] = __( 'Users with specific capability', 'restrict-for-elementor' );
            return $array;
        }

        function should_render( $should_render, $settings ) {

            if ( !empty( $settings[ 'restrict_for_elementor_show_to' ] ) ) {

                $action = ( !isset( $settings[ 'restrict_for_elementor_action' ] ) || ( isset( $settings[ 'restrict_for_elementor_action' ] ) && $settings[ 'restrict_for_elementor_action' ] !== 'yes' ) ) ? 'hide' : 'show';

                if ( $settings[ 'restrict_for_elementor_show_to' ] == 'user_capability' && !empty( $settings[ 'user_capability_selection' ] ) ) {

                    if ( current_user_can( $settings[ 'user_capability_selection' ] ) ) {
                        $should_render = ( $action == 'show' ) ? true : false;

                    } else {
                        $should_render = ( $action == 'show' ) ? false : true;
                        do_action( 'restrict_for_elementor_rest_api' );
                    }

                    do_action( 'restrict_for_elementor_clear_cache' );
                }
            }
            return $should_render;
        }

        function add_control( $element, $args ) {

            $element->add_control(
                'restrict_for_elementor_user_capability_hr',
                [
                    'type' => Controls_Manager::DIVIDER,
                    'condition' => [
                        'restrict_for_elementor_show_to' => 'user_capability',
                    ],
                ]
            );

            $element->add_control(
                'user_capability_selection',
                [
                    'label' => __( 'User Capability:', 'restrict-for-elementor' ),
                    'label_block' => true,
                    'type' => Controls_Manager::TEXT,
                    'placeholder' => 'manage_options',
                    'condition' => [
                        'restrict_for_elementor_show_to' => 'user_capability',
                    ],
                ]
            );
        }

    }

    new Restrict_Elementor_Addon_User_Capability();
}