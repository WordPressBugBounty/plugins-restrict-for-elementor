<?php
namespace Elementor;

if ( !class_exists( 'Restrict_Elementor_Addon_User_Meta' ) ) {

    class Restrict_Elementor_Addon_User_Meta {

        function __construct() {
            add_filter( 'restrict_for_elementor_show_to_main_options', array( $this, 'show_to_main_options' ) );
            add_filter( 'restrict_for_elementor_should_render_user_meta', array( $this, 'should_render' ), 10, 2 );
            add_action( 'restrict_for_elementor_add_controls', array( $this, 'add_control' ), 10, 2 );
        }

        function show_to_main_options( $array ) {
            $array[ 'user_meta' ] = __( 'Users with specific meta value', 'restrict-for-elementor' );
            return $array;
        }

        function convert_if_array( $value ) {

            if ( strpos( $value, ',' ) !== false ) {
                $value = explode( ",", $value );

            } elseif ( strpos( $value, 'array' ) !== false ) {

                $value = str_replace( 'array(', '', $value );
                $value = substr_replace( $value, "", -1 );
                $value = explode( ",", $value );

            } else {
                // Do nothing, it's regular string
            }

            return $value;
        }

        function should_render( $should_render, $settings ) {

            if ( !empty( $settings[ 'restrict_for_elementor_show_to' ] ) ) {

                $action = ( !isset( $settings[ 'restrict_for_elementor_action' ] ) || ( isset( $settings[ 'restrict_for_elementor_action' ] ) && $settings[ 'restrict_for_elementor_action' ] !== 'yes' ) ) ? 'hide' : 'show';

                if ( $settings[ 'restrict_for_elementor_show_to' ] == 'user_meta' && !empty( $settings[ 'user_meta_key' ] ) ) {

                    $current_user_id = get_current_user_id();

                    if ( $current_user_id > 0 ) {

                        $user_meta_key = $settings[ 'user_meta_key' ];
                        $user_meta_value = $settings[ 'user_meta_value' ];
                        $user_meta_compare = isset( $settings[ 'user_meta_compare' ] ) ? $settings[ 'user_meta_compare' ] : '=';
                        $user_meta_compare_type = isset( $settings[ 'user_meta_compare_type' ] ) ? $settings[ 'user_meta_compare_type' ] : 'CHAR';

                        $meta_query_array = [];
                        $meta_query_array[ 'key' ] = $user_meta_key;

                        if ( $user_meta_compare == 'BETWEEN' || $user_meta_compare == 'NOT BETWEEN' || $user_meta_compare == 'IN' || $user_meta_compare == 'NOT IN' ) {
                            $meta_query_array[ 'value' ] = $this->convert_if_array( $user_meta_value );

                        } else {
                            $meta_query_array[ 'value' ] = $user_meta_value;
                        }

                        $meta_query_array[ 'compare' ] = $user_meta_compare;
                        $meta_query_array[ 'type' ] = $user_meta_compare_type;

                        $args = array(
                            'count_total' => false,
                            'include' => array( $current_user_id ),
                            'fields' => 'ID',
                            'meta_query' =>
                                array(
                                    array(
                                        'relation' => 'AND',
                                        $meta_query_array,
                                    )
                                )
                        );

                        $users = @get_users( $args );

                        if ( isset( $users ) && count( $users ) > 0 ) {
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
            }

            return $should_render;
        }

        function add_control( $element, $args ) {

            $element->add_control(
                'restrict_for_elementor_user_meta_hr',
                [
                    'type' => Controls_Manager::DIVIDER,
                    'condition' => [
                        'restrict_for_elementor_show_to' => 'user_meta',
                    ],
                ]
            );

            $element->add_control(
                'user_meta_key',
                [
                    'label' => __( 'Meta Key:', 'restrict-for-elementor' ),
                    'label_block' => true,
                    'type' => Controls_Manager::TEXT,
                    'placeholder' => '',
                    'condition' => [
                        'restrict_for_elementor_show_to' => 'user_meta',
                    ],
                ]
            );


            $compare = array(
                "=" => "=",
                "!=" => "!=",
                ">" => ">",
                ">=" => ">=",
                "<" => "<",
                "<=" => "<=",
                "LIKE" => "LIKE",
                "NOT LIKE" => "NOT LIKE",
                "IN" => "IN",
                "NOT IN" => "NOT IN",
                "BETWEEN" => "BETWEEN",
                "NOT BETWEEN" => "NOT BETWEEN",
                "EXISTS" => "EXISTS",
                "NOT EXISTS" => "NOT EXISTS",
            );

            $element->add_control(
                'user_meta_compare',
                [
                    'label' => __( 'Meta Compare:', 'restrict-for-elementor' ),
                    'label_block' => true,
                    'type' => Controls_Manager::SELECT,
                    'options' => $compare,
                    'default' => '=',
                    'conditions' => [
                        'relation' => 'and',
                        'terms' => [
                            [
                                'name' => 'restrict_for_elementor_show_to',
                                'operator' => '=',
                                'value' => 'user_meta'
                            ],
                            [
                                'name' => 'user_meta_key',
                                'operator' => '!==',
                                'value' => ''
                            ],

                        ]
                    ],
                ]
            );

            $compare_types = array(
                "NUMERIC" => "NUMERIC",
                "CHAR" => "CHAR",
                "DATE" => "DATE",
                "DATETIME" => "DATETIME",
                "DECIMAL" => "DECIMAL",
                "TIME" => "TIME",
            );

            $element->add_control(
                'user_meta_compare_type',
                [
                    'label' => __( 'Data Type:', 'restrict-for-elementor' ),
                    'label_block' => true,
                    'type' => Controls_Manager::SELECT,
                    'options' => $compare_types,
                    'default' => 'CHAR',
                    //'description' => __( 'Stretch the section to the full width of the page using JS.', 'restrict-for-elementor' ) . sprintf( ' <a href="%1$s" target="_blank">%2$s</a>', 'https://restrict_for_elementor.com/documentation/', __( 'Learn more.', 'restrict-for-elementor' ) ),
                    'conditions' => [
                        'relation' => 'and',
                        'terms' => [
                            [
                                'name' => 'restrict_for_elementor_show_to',
                                'operator' => '=',
                                'value' => 'user_meta'
                            ],
                            [
                                'name' => 'user_meta_key',
                                'operator' => '!==',
                                'value' => ''
                            ],
                            [
                                'name' => 'user_meta_compare',
                                'operator' => '!in',
                                'value' => array( 'EXISTS', 'NOT EXISTS' )
                            ],
                        ]
                    ],
                ]
            );

            $element->add_control(
                'user_meta_value',
                [
                    'label' => __( 'Meta Value:', 'restrict-for-elementor' ),
                    'label_block' => true,
                    'type' => Controls_Manager::TEXT,
                    'placeholder' => '',
                    'conditions' => [
                        'relation' => 'and',
                        'terms' => [
                            [
                                'name' => 'restrict_for_elementor_show_to',
                                'operator' => '=',
                                'value' => 'user_meta'
                            ],
                            [
                                'name' => 'user_meta_key',
                                'operator' => '!==',
                                'value' => ''
                            ],
                            [
                                'name' => 'user_meta_compare',
                                'operator' => '!in',
                                'value' => [
                                    'EXISTS',
                                    'NOT EXISTS'
                                ]
                            ],
                        ]
                    ],
                ]
            );
        }
    }

    new Restrict_Elementor_Addon_User_Meta();
}
