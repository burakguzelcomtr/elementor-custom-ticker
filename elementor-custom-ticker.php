<?php
/**
 * Plugin Name: Elementor Custom Ticker
 * Description: A custom ticker widget for Elementor
 * Version: 1.0.3
 * Author: Burak Güzel
 * Text Domain: elementor-custom-ticker
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Main Elementor Custom Ticker Class
 *
 * The main class that initiates and runs the plugin.
 *
 * @since 1.0.3
 */
final class Elementor_Custom_Ticker {

    /**
     * Plugin Version
     *
     * @since 1.0.3
     * @var string The plugin version.
     */
    const VERSION = '1.0.3';

    /**
     * Minimum Elementor Version
     *
     * @since 1.0.0
     * @var string Minimum Elementor version required to run the plugin.
     */
    const MINIMUM_ELEMENTOR_VERSION = '2.0.0';

    /**
     * Minimum PHP Version
     *
     * @since 1.0.0
     * @var string Minimum PHP version required to run the plugin.
     */
    const MINIMUM_PHP_VERSION = '7.0';

    /**
     * Instance
     *
     * @since 1.0.0
     * @access private
     * @static
     * @var Elementor_Custom_Ticker The single instance of the class.
     */
    private static $_instance = null;

    /**
     * Instance
     *
     * Ensures only one instance of the class is loaded or can be loaded.
     *
     * @since 1.0.0
     * @access public
     * @static
     * @return Elementor_Custom_Ticker An instance of the class.
     */
    public static function instance() {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * Constructor
     *
     * @since 1.0.0
     * @access public
     */
    public function __construct() {
        add_action('plugins_loaded', [$this, 'on_plugins_loaded']);
    }

    /**
     * Load Textdomain
     *
     * Load plugin localization files.
     *
     * @since 1.0.0
     * @access public
     */
    public function i18n() {
        load_plugin_textdomain('elementor-custom-ticker');
    }

    /**
     * On Plugins Loaded
     *
     * Checks if Elementor has loaded, and performs actions based on Elementor availability.
     *
     * @since 1.0.0
     * @access public
     */
    public function on_plugins_loaded() {
        if ($this->is_compatible()) {
            add_action('elementor/init', [$this, 'init']);
        }
    }

    /**
     * Compatibility Checks
     *
     * Checks if the installed version of Elementor meets our requirements.
     * Checks if the installed PHP version meets our requirements.
     *
     * @since 1.0.0
     * @access public
     */
    public function is_compatible() {
        // Check if Elementor installed and activated
        if (!did_action('elementor/loaded')) {
            add_action('admin_notices', [$this, 'admin_notice_missing_main_plugin']);
            return false;
        }

        // Check for required Elementor version
        if (!version_compare(ELEMENTOR_VERSION, self::MINIMUM_ELEMENTOR_VERSION, '>=')) {
            add_action('admin_notices', [$this, 'admin_notice_minimum_elementor_version']);
            return false;
        }

        // Check for required PHP version
        if (version_compare(PHP_VERSION, self::MINIMUM_PHP_VERSION, '<')) {
            add_action('admin_notices', [$this, 'admin_notice_minimum_php_version']);
            return false;
        }

        return true;
    }

    /**
     * Admin notice
     *
     * Warning when the site doesn't have Elementor installed or activated.
     *
     * @since 1.0.0
     * @access public
     */
    public function admin_notice_missing_main_plugin() {
        if (isset($_GET['activate'])) unset($_GET['activate']);

        $message = sprintf(
            /* translators: 1: Plugin name 2: Elementor */
            esc_html__('"%1$s" requires "%2$s" to be installed and activated.', 'elementor-custom-ticker'),
            '<strong>' . esc_html__('Elementor Custom Ticker', 'elementor-custom-ticker') . '</strong>',
            '<strong>' . esc_html__('Elementor', 'elementor-custom-ticker') . '</strong>'
        );

        printf('<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message);
    }

    /**
     * Admin notice
     *
     * Warning when the site doesn't have a minimum required Elementor version.
     *
     * @since 1.0.0
     * @access public
     */
    public function admin_notice_minimum_elementor_version() {
        if (isset($_GET['activate'])) unset($_GET['activate']);

        $message = sprintf(
            /* translators: 1: Plugin name 2: Elementor 3: Required Elementor version */
            esc_html__('"%1$s" requires "%2$s" version %3$s or greater.', 'elementor-custom-ticker'),
            '<strong>' . esc_html__('Elementor Custom Ticker', 'elementor-custom-ticker') . '</strong>',
            '<strong>' . esc_html__('Elementor', 'elementor-custom-ticker') . '</strong>',
            self::MINIMUM_ELEMENTOR_VERSION
        );

        printf('<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message);
    }

    /**
     * Admin notice
     *
     * Warning when the site doesn't have a minimum required PHP version.
     *
     * @since 1.0.0
     * @access public
     */
    public function admin_notice_minimum_php_version() {
        if (isset($_GET['activate'])) unset($_GET['activate']);

        $message = sprintf(
            /* translators: 1: Plugin name 2: PHP 3: Required PHP version */
            esc_html__('"%1$s" requires "%2$s" version %3$s or greater.', 'elementor-custom-ticker'),
            '<strong>' . esc_html__('Elementor Custom Ticker', 'elementor-custom-ticker') . '</strong>',
            '<strong>' . esc_html__('PHP', 'elementor-custom-ticker') . '</strong>',
            self::MINIMUM_PHP_VERSION
        );

        printf('<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message);
    }

    /**
     * Initialize
     *
     * Load the widgets and register them in Elementor.
     *
     * @since 1.0.0
     * @access public
     */
    public function init() {
        $this->i18n();

        // Add Plugin actions
        add_action('elementor/widgets/register', [$this, 'init_widgets']);
        add_action('elementor/frontend/after_enqueue_styles', [$this, 'widget_styles']);
        add_action('elementor/frontend/after_register_scripts', [$this, 'widget_scripts']);
    }

    /**
     * Init Widgets
     *
     * Include widgets files and register them
     *
     * @since 1.0.0
     * @access public
     */
    public function init_widgets() {
        // Include Widget files
        require_once(__DIR__ . '/widgets/ticker-widget.php');

        // Register widget
        \Elementor\Plugin::instance()->widgets_manager->register(new \Elementor_Ticker_Widget());
    }

    /**
     * Widget Styles
     *
     * Load required plugin core files.
     *
     * @since 1.0.0
     * @access public
     */
    public function widget_styles() {
        wp_register_style('elementor-custom-ticker', plugins_url('assets/css/ticker.css', __FILE__));
        wp_enqueue_style('elementor-custom-ticker');
    }

    /**
     * Widget Scripts
     *
     * Load required plugin core files.
     *
     * @since 1.0.0
     * @access public
     */
    public function widget_scripts() {
        wp_register_script('elementor-custom-ticker', plugins_url('assets/js/ticker.js', __FILE__), ['jquery'], false, true);
        wp_enqueue_script('elementor-custom-ticker');
    }
}

// Initialize the plugin
Elementor_Custom_Ticker::instance();
