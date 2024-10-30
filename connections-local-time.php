<?php
/**
 * An extension for Connections Business Directory which adds the ability to show the local time of a business based or individual based on their address.
 *
 * @package   Connections Business Directory Extension - Local Time
 * @category  Extension
 * @author    Steven A. Zahm
 * @license   GPL-2.0+
 * @link      https://connections-pro.com
 * @copyright 2021 Steven A. Zahm
 *
 * @wordpress-plugin
 * Plugin Name:       Connections Business Directory Extension - Local Time
 * Plugin URI:        https://connections-pro.com/add-on/local-time/
 * Description:       An extension for Connections Business Directory which adds the ability to show the local time of a business based or individual based on their address.
 * Version:           1.2.1
 * Author:            Steven A. Zahm
 * Author URI:        https://connections-pro.com
 * License:           GPL-2.0+
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       connections-local-time
 * Domain Path:       /languages
 */

if ( ! class_exists( 'Connections_Local_Time' ) ) {

	final class Connections_Local_Time {

		const VERSION = '1.2.1';

		/**
		 * @var Connections_Local_Time Stores the instance of this class.
		 *
		 * @access private
		 * @since 1.0
		 */
		private static $instance;

		/**
		 * @var string The absolute path this this file.
		 *
		 * @access private
		 * @since 1.0
		 */
		private static $file = '';

		/**
		 * @var string The URL to the plugin's folder.
		 *
		 * @access private
		 * @since 1.0
		 */
		private static $url = '';

		/**
		 * @var string The absolute path to this plugin's folder.
		 *
		 * @access private
		 * @since 1.0
		 */
		private static $path = '';

		/**
		 * @var string The basename of the plugin.
		 *
		 * @access private
		 * @since 1.0
		 */
		private static $basename = '';

		/**
		 * A dummy constructor to prevent the class from being loaded more than once.
		 *
		 * @access public
		 * @since 1.0
		 */
		public function __construct() { /* Do nothing here */ }

		/**
		 * @access public
		 * @since  1.0
		 * @static
		 *
		 * @return Connections_Local_Time
		 */
		public static function instance() {

			if ( ! isset( self::$instance ) && ! ( self::$instance instanceof Connections_Local_Time ) ) {

				self::$file       = __FILE__;
				self::$url        = plugin_dir_url( self::$file );
				self::$path       = plugin_dir_path( self::$file );
				self::$basename   = plugin_basename( self::$file );

				self::loadDependencies();

				self::$instance = new Connections_Local_Time;

				self::hooks();
			}

			return self::$instance;
		}

		private static function loadDependencies() {

			require_once( self::$path . 'includes/class.clock-widget.php' );
		}

		/**
		 * Register all the hooks that makes this thing run.
		 *
		 * @access private
		 * @since  1.0
		 * @static
		 */
		private static function hooks() {

			// Register the widgets.
			add_action( 'widgets_init', array( 'cnClock_Widget', 'register' ) );

			// Add an action to purge widget fragment caches after adding/editing and entry.
			add_action( 'cn_clean_entry_cache', array( __CLASS__, 'clearFragments' ) );
			add_action( 'cn_clean_term_cache', array( __CLASS__, 'clearFragments' ) );

			// Add ajax action to delete fragment cache when a widget is deleted.
			add_action( 'wp_ajax_save-widget', array( __CLASS__, 'deleteFragment' ), -1 );

			// Register the scripts.
			add_action( 'wp_enqueue_scripts', array( __CLASS__, 'registerScripts' ) );
			add_action( 'wp_enqueue_scripts', array( __CLASS__, 'enqueueScripts' ) );
		}

		/**
		 * Register the scripts.
		 *
		 * @access private
		 * @since  1.0
		 * @static
		 */
		public static function registerScripts() {

			// If SCRIPT_DEBUG is set and TRUE load the non-minified JS files, otherwise, load the minified files.
			$min = defined('SCRIPT_DEBUG') && SCRIPT_DEBUG ? '' : '.min';
			$url = cnURL::makeProtocolRelative( self::$url );

			// Register CSS.
			wp_register_style( 'cn-clock-public', self::$url . 'includes/vendor/jClocksGMT/css/jClocksGMT.css', array(), '2.0.2' );

			// Register JavaScript.
			wp_register_script( 'jquery-rotate' , self::$url . 'includes/vendor/jClocksGMT/js/jquery.rotate.js', array( 'jquery' ) , '2.3' );
			wp_register_script( 'jquery-jClocksGMT' , self::$url . 'includes/vendor/jClocksGMT/js/jClocksGMT.js', array( 'jquery-rotate' ) , '2.0.2', TRUE );
		}

		/**
		 * Enqueue the scripts.
		 *
		 * @access private
		 * @since  1.0
		 * @static
		 */
		public static function enqueueScripts() {

			//wp_enqueue_script( 'jquery-jClocksGMT' );
			wp_enqueue_style( 'cn-clock-public' );
		}

		public static function render( $atts ) {

			$atts = shortcode_atts(
				array(
					'title'      => 'Greenwich, England',
					'offset'     => '0',
					'dst'        => TRUE,
					'digital'    => TRUE,
					'analog'     => TRUE,
					'timeformat' => 'hh:mm A',
					'date'       => FALSE,
					'dateformat' => 'MM/DD/YYYY',
					'angleSec'   => 0,
					'angleMin'   => 0,
					'angleHour'  => 0,
					'skin'       => 1,
					'imgpath'    => self::$url . 'includes/vendor/jClocksGMT/',
				),
				$atts,
				'cn-clock'
			);

			$options = json_encode( $atts );
			$class   = array( 'jclockgmt' );
			$uid     = uniqid();

			if ( empty( $atts['title'] ) ) {
				$class[] = 'jclockgmt-no-title';
			}

			$css    = '<style>.widget.widget_cnw_clock:after { content: "";display: table;clear: both;} #cn-clock-' . $uid . '.jclockgmt-no-title .jcgmt-lbl {display: none;} .jcgmt-container {float: none;} .jcgmt-digital, .jcgmt-date {margin: 0;}</style>';
			$markup = '<div id="cn-clock-' . $uid . '" ' . cnHTML::attribute( 'class', $class ) . '></div>';
			$script = '<script>jQuery(document).ready( function(){ jQuery("#cn-clock-' . $uid . '").jClocksGMT(' . $options . ');' . '});</script>';

			echo $css . $markup . $script;
		}

		/**
		 * Purge widget fragment caches after adding/editing and entry.
		 *
		 * @access public
		 * @since  1.0
		 * @static
		 */
		public static function clearFragments() {

			cnCache::clear( TRUE, 'transient', 'cnw' );
		}

		/**
		 * Purge the widget group fragment cache when a widget is deleted.
		 *
		 * @access private
		 * @since  1.0
		 * @static
		 */
		public static function deleteFragment() {

			check_ajax_referer( 'save-sidebar-widgets', 'savewidgets' );

			if ( ! current_user_can('edit_theme_options') || ! isset( $_POST['id_base'] ) ) {

				wp_die( -1 );
			}

			if ( isset( $_POST['delete_widget'] ) && $_POST['delete_widget'] ) {

				// Clear the widget group fragment cache.
				cnFragment::clear( TRUE, substr( $_POST['widget-id'], 0 ) );
			}

		}
	}

	/**
	 * Start up the extension.
	 *
	 * @access public
	 * @since  1.0
	 *
	 * @return Connections_Local_Time|false
	 */
	function Connections_Local_Time() {

		if ( class_exists( 'connectionsLoad' ) ) {

			return Connections_Local_Time::instance();

		} else {

			add_action(
				'admin_notices',
				function() {
					echo '<div id="message" class="error"><p><strong>ERROR:</strong> Connections must be installed and active in order use Connections Local Time.</p></div>';
				}
			);

			return false;
		}
	}

	/**
	 * Since Connections loads at default priority 10, and this extension is dependent on Connections,
	 * we'll load with priority 11 so we know Connections will be loaded and ready first.
	 */
	add_action( 'plugins_loaded', 'Connections_Local_Time', 11 );
}
