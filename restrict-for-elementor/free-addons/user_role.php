<?php
namespace Elementor;

if ( !class_exists( 'Restrict_Elementor_Addon_User_Role' ) ) {

    class Restrict_Elementor_Addon_User_Role {

        function __construct() {
            add_filter( 'restrict_for_elementor_show_to_main_options', array( $this, 'show_to_main_options' ) );
            add_filter( 'restrict_for_elementor_should_render_user_role', array( $this, 'should_render' ), 10, 2 );
            add_action( 'restrict_for_elementor_add_controls', array( $this, 'add_control' ), 10, 2 );
        }

        public static function get_current_user_role() {

            if ( is_user_logged_in() ) {
                global $current_user;
                return $current_user->roles;
            }

            return false;
        }

        function show_to_main_options( $array ) {
            $array[ 'user_role' ] = __( 'Users with specific role', 'restrict-for-elementor' );
            return $array;
        }

        function should_render( $should_render, $settings ) {

            if ( !empty( $settings[ 'restrict_for_elementor_show_to' ] ) ) {

                $action = ( !isset( $settings[ 'restrict_for_elementor_action' ] ) || ( isset( $settings[ 'restrict_for_elementor_action' ] ) && $settings[ 'restrict_for_elementor_action' ] !== 'yes' ) ) ? 'hide' : 'show';

                if ( $settings[ 'restrict_for_elementor_show_to' ] == 'user_role' && !empty( $settings[ 'user_role_selection' ] ) ) {

                    $role_found = false;
                    $user_role_selection = $settings[ 'user_role_selection' ];
                    $current_user_roles = Restrict_Elementor_Addon_User_Role::get_current_user_role();

                    if ( $current_user_roles ) {

                        $rsc_user_role = $user_role_selection;

                        foreach ( (array) $rsc_user_role as $key => $role ) {

                            $role = \Restrict_Elementor::maybe_unserialize( $role );

                            if ( is_array( $role ) ) {

                                foreach ( $role as $_role ) {

                                    if ( in_array( $_role, $current_user_roles ) ) {
                                        $should_render = ( $action == 'show' ) ? true : false;
                                        do_action( 'restrict_for_elementor_rest_api' );
                                        $role_found = true;
                                        break 2;
                                    }
                                }

                            } else {

                                if ( in_array( $role, $current_user_roles ) ) {
                                    $should_render = ( $action == 'show' ) ? true : false;
                                    do_action( 'restrict_for_elementor_rest_api' );
                                    $role_found = true;
                                    break;
                                }
                            }
                        }

                        if ( ! $role_found ) {
                            $should_render = ( $action == 'show' ) ? false : true;
                            do_action( 'restrict_for_elementor_rest_api' );
                        }

                    } else {
                        $should_render = ( $action == 'show' ) ? false : true;
                        do_action( 'restrict_for_elementor_rest_api' );
                    }
                }
            }

            return $should_render;
        }

        function add_control( $element, $args ) {

            if ( !function_exists( 'get_editable_roles' ) ) {
                require_once( ABSPATH . '/wp-admin/includes/user.php' );
            }

            $editable_roles = array_reverse( get_editable_roles() );
            $roles_array = array();

            foreach ( $editable_roles as $role => $details ) {
                $roles_array[ esc_attr( $role ) ] = translate_user_role( $details[ 'name' ] );
            }

            $element->add_control(
                'restrict_for_elementor_user_role_hr',
                [
                    'type' => Controls_Manager::DIVIDER,
                    'condition' => [
                        'restrict_for_elementor_show_to' => 'user_role',
                    ],
                ]
            );

            $element->add_control(
                'user_role_selection',
                [
                    'label' => __( 'Select user role:', 'restrict-for-elementor' ),
                    'label_block' => true,
                    'multiple' => true,
                    'options' => $roles_array,
                    'type' => Controls_Manager::SELECT2,
                    'condition' => [
                        'restrict_for_elementor_show_to' => 'user_role',
                    ],
                ]
            );
        }
    }

    new Restrict_Elementor_Addon_User_Role();
}
