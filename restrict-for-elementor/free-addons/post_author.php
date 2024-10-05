<?php

if ( !class_exists( 'Restrict_Elementor_Addon_Post_Author' ) ) {

    class Restrict_Elementor_Addon_Post_Author {

        function __construct() {
            add_filter( 'restrict_for_elementor_show_to_main_options', array( $this, 'show_to_main_options' ) );
            add_filter( 'restrict_for_elementor_should_render_post_author', array( $this, 'should_render' ), 10, 2 );
        }

        function show_to_main_options( $array ) {
            $array[ 'post_author' ] = __( 'Post author', 'restrict-for-elementor' );
            return $array;
        }

        function should_render( $should_render, $settings ) {

            global $post;

            if ( !empty( $settings[ 'restrict_for_elementor_show_to' ] ) ) {

                $action = ( !isset( $settings[ 'restrict_for_elementor_action' ] ) || ( isset( $settings[ 'restrict_for_elementor_action' ] ) && $settings[ 'restrict_for_elementor_action' ] !== 'yes' ) ) ? 'hide' : 'show';
                $current_user_id = get_current_user_id();

                if ( $current_user_id > 0 ) {

                    $post_author_id = get_post_field( 'post_author', $post->ID );

                    if ( $post_author_id == $current_user_id ) {
                        $should_render = ( $action == 'show' ) ? true : false;

                    } else {
                        $should_render = ( $action == 'show' ) ? false : true;
                        do_action( 'restrict_for_elementor_rest_api' );
                    }

                } else {
                    $should_render = ( $action == 'show' ) ? false : true;
                    do_action( 'restrict_for_elementor_rest_api' );
                }
            }

            return $should_render;
        }
    }

    new Restrict_Elementor_Addon_Post_Author();
}