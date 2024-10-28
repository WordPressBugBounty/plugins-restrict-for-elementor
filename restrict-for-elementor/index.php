<?php

/**
 * Plugin Name: Restrict for Elementor
 * Plugin URI: https://restrict.io/restrict-for-elementor
 * Description: Show or hide Elementor sections, columns and widgets with ease using many different criteria
 * Author: Restrict
 * Author URI: https://restrict.io/
 * Version: 1.0.9
 * Elementor tested up to: 3.21.7
 * Elementor Pro tested up to: 3.21.7
 * Text Domain: restrict-for-elementor
 * Domain Path: /languages
 * Copyright 2021 Restrict (https://restrict.io/)
 */
if ( !defined( 'ABSPATH' ) ) {
    exit;
}
if ( !function_exists( 'rfe_fs' ) ) {
    /**
     * Create a helper function for easy SDK access.
     *
     * @return Freemius
     * @throws Freemius_Exception
     */
    function rfe_fs() {
        global $rfe_fs;
        if ( !isset( $rfe_fs ) ) {
            // Include Freemius SDK.
            require_once dirname( __FILE__ ) . '/freemius/start.php';
            $rfe_fs = fs_dynamic_init( array(
                'id'             => '8493',
                'slug'           => 'restrict-for-elementor',
                'premium_slug'   => 'restrict-for-elementor-pro',
                'type'           => 'plugin',
                'public_key'     => 'pk_31f034b51c5a29f9ec8b91eed7408',
                'is_premium'     => false,
                'premium_suffix' => 'Pro',
                'has_addons'     => false,
                'has_paid_plans' => true,
                'trial'          => array(
                    'days'               => 7,
                    'is_require_payment' => true,
                ),
                'menu'           => array(
                    'slug'    => 'restrict_for_elementor_settings',
                    'contact' => true,
                    'support' => false,
                    'pricing' => true,
                    'account' => true,
                ),
                'is_live'        => true,
            ) );
        }
        return $rfe_fs;
    }

    // Init Freemius.
    rfe_fs();
    // Signal that SDK was initiated.
    do_action( 'rfe_fs_loaded' );
}
if ( !class_exists( 'Restrict_Elementor' ) ) {
    class Restrict_Elementor {
        var $version = '1.0.3';

        var $title = 'Restrict for Elementor';

        var $name = 'restrict-for-elementor';

        var $dir_name = '';

        var $location = 'plugins';

        var $plugin_dir = '';

        var $plugin_url = '';

        var $language = '';

        const MINIMUM_ELEMENTOR_VERSION = '3.0.0';

        function __construct() {
            $this->set_plugin_dir();
            $this->init_vars();
            add_action( 'plugins_loaded', array($this, 'localization'), 9 );
            add_action( 'plugins_loaded', array($this, 'is_compatible'), 9 );
            add_action( 'admin_menu', array($this, 'add_admin_menu') );
            add_action( 'admin_enqueue_scripts', array($this, 'admin_header') );
            require_once $this->plugin_dir . 'control.php';
            if ( rfe_fs()->is__premium_only() && rfe_fs()->can_use_premium_code() ) {
                if ( defined( 'RSC_EL_PLUGIN_TITLE' ) ) {
                    $this->title = RSC_EL_PLUGIN_TITLE;
                }
                rfe_fs()->add_filter(
                    'is_submenu_visible',
                    'rsc_el_admin_submenu_visibility',
                    10,
                    2
                );
                function rsc_el_admin_submenu_visibility(  $is_visible, $submenu_id  ) {
                    $is_whitelabel = defined( 'RSC_EL_PLUGIN_TITLE' ) && RSC_EL_PLUGIN_TITLE !== 'Restrict for Elementor';
                    return ( $is_whitelabel ? false : $is_visible );
                }

            }
            add_action( 'plugins_loaded', array($this, 'load_addons') );
        }

        function admin_notices() {
            // Check for Elementor's minimum working version
            if ( !version_compare( ELEMENTOR_VERSION, self::MINIMUM_ELEMENTOR_VERSION, '>=' ) ) {
                add_action( 'admin_notices', array($this, 'admin_notice_minimum_elementor_version') );
                return;
            }
        }

        function admin_notice_minimum_elementor_version() {
            if ( isset( $_GET['activate'] ) ) {
                unset($_GET['activate']);
            }
            $message = sprintf(
                esc_html__( '%1$s requires %2$s version %3$s or greater.', 'restrict-for-elementor' ),
                '<strong>' . $this->title . '</strong>',
                '<strong>' . esc_html__( 'Elementor', 'restrict-for-elementor' ) . '</strong>',
                self::MINIMUM_ELEMENTOR_VERSION
            );
            printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );
        }

        function add_admin_menu() {
            add_menu_page(
                $this->title,
                $this->title,
                'manage_options',
                'restrict_for_elementor_settings',
                'Restrict_Elementor::admin_settings',
                'dashicons-restrict-for-elementor',
                6
            );
        }

        function admin_header() {
            if ( isset( $_GET['page'] ) && $_GET['page'] == 'restrict_for_elementor_settings' ) {
                wp_enqueue_style(
                    $this->name . '-admin',
                    $this->plugin_url . 'css/admin.css',
                    array(),
                    $this->version
                );
                wp_enqueue_script(
                    $this->name . '-modal-video',
                    $this->plugin_url . 'scripts/jquery-modal-video.min.js',
                    array('jquery'),
                    $this->version
                );
                wp_enqueue_style(
                    $this->name . '-modal-video',
                    $this->plugin_url . 'css/modal-video.min.css',
                    array(),
                    $this->version
                );
                wp_enqueue_script(
                    'rfe-common',
                    $this->plugin_url . 'scripts/common.js',
                    array($this->name . '-modal-video'),
                    $this->version
                );
                wp_enqueue_style(
                    $this->name . '-admin',
                    $this->plugin_url . 'css/admin.css',
                    array(),
                    $this->version
                );
                wp_enqueue_style( 'rsc-roboto', 'https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap' );
            }
            wp_enqueue_style(
                'restrict_for_elementor_dashicons',
                $this->plugin_url . '/css/restrict-for-elementor.css',
                array(),
                $this->version
            );
        }

        public static function admin_settings() {
            require_once plugin_dir_path( __FILE__ ) . '/settings/settings.php';
        }

        public static function maybe_unserialize( $value ) {
            $data = @unserialize( $value );
            return ( $data !== false ? $data : $value );
        }

        function should_load_addon( $addon_defined_name ) {
            if ( rfe_fs()->is__premium_only() && rfe_fs()->can_use_premium_code() ) {
                if ( defined( 'RSC_EL_' . $addon_defined_name ) && constant( 'RSC_EL_' . $addon_defined_name ) == false ) {
                    return false;
                }
            }
            return true;
        }

        function load_addons() {
            if ( $this->should_load_addon( 'LOGGED_IN_USERS' ) ) {
                require_once $this->plugin_dir . 'free-addons/logged_in_users.php';
            }
            if ( $this->should_load_addon( 'USER_ROLE' ) ) {
                require_once $this->plugin_dir . 'free-addons/user_role.php';
            }
            if ( $this->should_load_addon( 'USER_CAPABILITY' ) ) {
                require_once $this->plugin_dir . 'free-addons/user_capability.php';
            }
            if ( $this->should_load_addon( 'USER_META' ) ) {
                require_once $this->plugin_dir . 'free-addons/user_meta.php';
            }
            if ( $this->should_load_addon( 'POST_AUTHOR' ) ) {
                require_once $this->plugin_dir . 'free-addons/post_author.php';
            }
            // Integration with  "Geolocation IP Detection" plugin https://wordpress.org/plugins/geoip-detect/
            if ( $this->should_load_addon( 'GEOLOCATION' ) ) {
                if ( function_exists( 'geoip_detect2_get_external_ip_adress' ) && function_exists( 'geoip_detect2_get_info_from_ip' ) ) {
                    require_once $this->plugin_dir . 'free-addons/location.php';
                }
            }
        }

        function set_plugin_dir() {
            $dir = plugin_basename( __FILE__ );
            $this->dir_name = str_replace( array('/index.php', '\\index.php'), '', $dir );
        }

        function init_vars() {
            if ( defined( 'WP_PLUGIN_URL' ) && defined( 'WP_PLUGIN_DIR' ) && file_exists( WP_PLUGIN_DIR . '/' . $this->dir_name . '/' . basename( __FILE__ ) ) ) {
                $this->location = 'subfolder-plugins';
                $this->plugin_dir = WP_PLUGIN_DIR . '/' . $this->dir_name . '/';
                $this->plugin_url = plugins_url( '/', __FILE__ );
            } elseif ( defined( 'WP_PLUGIN_URL' ) && defined( 'WP_PLUGIN_DIR' ) && file_exists( WP_PLUGIN_DIR . '/' . basename( __FILE__ ) ) ) {
                $this->location = 'plugins';
                $this->plugin_dir = WP_PLUGIN_DIR . '/';
                $this->plugin_url = plugins_url( '/', __FILE__ );
            } elseif ( is_multisite() && defined( 'WPMU_PLUGIN_URL' ) && defined( 'WPMU_PLUGIN_DIR' ) && file_exists( WPMU_PLUGIN_DIR . '/' . basename( __FILE__ ) ) ) {
                $this->location = 'mu-plugins';
                $this->plugin_dir = WPMU_PLUGIN_DIR;
                $this->plugin_url = WPMU_PLUGIN_URL;
            } else {
                wp_die( sprintf( __( 'There was an issue determining where %s is installed. Please reinstall it.', 'restrict-for-elementor' ), $this->title ) );
            }
        }

        function localization() {
            if ( $this->location == 'mu-plugins' ) {
                load_muplugin_textdomain( 'restrict-for-elementor', 'languages/' );
            } elseif ( $this->location == 'subfolder-plugins' ) {
                load_plugin_textdomain( 'restrict-for-elementor', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
            } elseif ( $this->location == 'plugins' ) {
                load_plugin_textdomain( 'restrict-for-elementor', false, 'languages/' );
            }
            $temp_locales = explode( '_', get_locale() );
            $this->language = ( $temp_locales[0] ? $temp_locales[0] : 'en' );
        }

        /**
         * Admin notice to require elementor to be installed and activated.
         */
        function admin_notice_missing_main_plugin() {
            if ( isset( $_GET['activate'] ) ) {
                unset($_GET['activate']);
            }
            $message = sprintf( esc_html__( '%1$s requires %2$s to be installed and activated.', 'restrict-for-elementor' ), '<strong>' . esc_html__( $this->title ) . '</strong>', '<strong><a href="https://elementor.com/?ref=22028" target="_blank">' . esc_html__( 'Elementor Website Builder', 'restrict-for-elementor' ) . '</a></strong> plugin' );
            printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );
        }

        /**
         * Compatibility Check.
         * Display admin notices for further instructions.
         *
         * @return bool
         */
        function is_compatible() {
            // Check if Elementor is installed and activated
            if ( !did_action( 'elementor/loaded' ) ) {
                add_action( 'admin_notices', array($this, 'admin_notice_missing_main_plugin') );
                return false;
            }
            // Additional admin notices
            self::admin_notices();
            return true;
        }

    }

    $Restrict_Elementor = new Restrict_Elementor();
}