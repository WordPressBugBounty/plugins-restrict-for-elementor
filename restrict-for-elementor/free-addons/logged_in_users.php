<?php

if ( !class_exists( 'Restrict_Elementor_Addon_Logged_In_User' ) ) {

    class Restrict_Elementor_Addon_Logged_In_User {

        function __construct() {
            add_filter( 'restrict_for_elementor_show_to_main_options', array( $this, 'show_to_main_options' ) );
            add_filter( 'restrict_for_elementor_should_render_logged_in_users', array( $this, 'should_render' ), 10, 2 );
        }

        /**
         * Include onto admin setting options
         *
         * @param $array
         * @return mixed
         */
        function show_to_main_options( $array ) {
            $array[ 'logged_in_users' ] = __( 'Logged in users', 'restrict-for-elementor' );
            return $array;
        }

        /**
         * Render content with conditions in the frontend
         *
         * @param $should_render
         * @param $settings
         * @return bool
         */
        function should_render( $should_render, $settings ) {

            if ( !empty( $settings[ 'restrict_for_elementor_show_to' ] ) ) {

                $action = ( !isset( $settings[ 'restrict_for_elementor_action' ] ) || ( isset( $settings[ 'restrict_for_elementor_action' ] ) && $settings[ 'restrict_for_elementor_action' ] !== 'yes' ) ) ? 'hide' : 'show';

                if ( $settings[ 'restrict_for_elementor_show_to' ] == 'logged_in_users' ) {

                    if ( is_user_logged_in() ) {
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
    }

    new Restrict_Elementor_Addon_Logged_In_User();
}
