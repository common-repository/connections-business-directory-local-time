<?php
/**
 * The local time widget.
 *
 * @package     Connections Local Time
 * @subpackage  Local Time Widget
 * @copyright   Copyright (c) 2017, Steven A. Zahm
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

class cnClock_Widget extends WP_Widget {

	/**
	 * Register widget.
	 */
	public function __construct() {
		$options = array(
			'description' => __( 'Show the local time of a business or individual based on their address.', 'connections-local-time' )
		);

		parent::__construct(
			'cnw_clock',
			'Connections : ' . __( 'Local Time', 'Connections_Local_Time' ),
			$options
		);
	}

	/**
	 * Registers the widget with the WordPress Widget API.
	 *
	 * @access public
	 * @since  2.0
	 *
	 * @return void
	 */
	public static function register() {

		register_widget( __CLASS__ );
	}

	protected function defaults() {

		return array(
			'show_title'    => TRUE,
			'title'         => __( 'Local Time', 'connections-local-time' ),
			'show_timezone' => TRUE,
			'show_analog'   => TRUE,
			'show_digital'  => TRUE,
			'show_date'     => FALSE,
			'time_format'   => 'hh:mm A',
			'date_format'   => 'MM/DD/YYYY',
			'skin'          => 1,
		);
	}

	/**
	 * Process updates from the widget form.
	 *
	 * @access public
	 * @since  1.0
	 *
	 * @param array $new
	 * @param array $old
	 *
	 * @return array
	 */
	public function update( $new, $old ) {

		$new = wp_parse_args( $new, $this->defaults() );

		$new['show_title'] = '1' === $new['show_title'] ? '1' : '0';
		$new['title']      = sanitize_text_field( $new['title'] );

		$new['show_timezone'] = '1' === $new['show_timezone'] ? '1' : '0';
		$new['show_analog']   = '1' === $new['show_analog'] ? '1' : '0';
		$new['show_digital']  = '1' === $new['show_digital'] ? '1' : '0';
		$new['show_date']     = '1' === $new['show_date'] ? '1' : '0';

		$new['time_format'] = sanitize_text_field( $new['time_format'] );
		$new['date_format'] = sanitize_text_field( $new['date_format'] );

		$new['skin'] = ! preg_match('/^(?:[1-5])$/', $new['skin'] ) ? 1 : $new['skin'];

		// Clear the widget group fragment cache.
		cnFragment::clear( TRUE, 'cnw_clock-' . $this->number );

		return $new;
	}

	/**
	 * Callback to display the widget's settings in the admin.
	 *
	 * @access public
	 * @since  1.0
	 *
	 * @param array $instance
	 * @return void
	 */
	public function form( $instance ) {

		$instance = wp_parse_args( $instance, $this->defaults() );

		cnHTML::text(
			array(
				'prefix' => '',
				'class'  => 'widefat',
				'id'     => $this->get_field_id('title'),
				'name'   => $this->get_field_name('title'),
				'label'  => __( 'Title:', 'connections-local-time' ),
				'before' => '<p>',
				'after'  => '</p>',
			),
			esc_attr( $instance['title'] )
		);

		cnHTML::field(
			array(
				'type'     => 'checkbox',
				'prefix'   => '',
				'id'       => $this->get_field_id('show_title'),
				'name'     => $this->get_field_name('show_title'),
				'label'    => __( 'Display widget title?', 'connections-local-time' ),
				'before'   => '<p>',
				'after'    => '</p>',
				'layout'   => '%field% %label%',
			),
			cnFormatting::toBoolean( $instance['show_title'] ) ? '1' : FALSE
		);

		cnHTML::field(
			array(
				'type'     => 'select',
				'prefix'   => '',
				'id'       => $this->get_field_id('skin'),
				'name'     => $this->get_field_name('skin'),
				'label'    => __( 'Choose skin for the analog clock:', 'connections_widgets' ),
				'before'   => '<p>',
				'after'    => '</p>',
				'layout'   => '%label%<br>%field%',
				'options'  => array(
					'1'  => __( 'Skin 1', 'connections-local-time' ),
					'2'  => __( 'Skin 2', 'connections-local-time' ),
					'3'  => __( 'Skin 3', 'connections-local-time' ),
					'4'  => __( 'Skin 4', 'connections-local-time' ),
					'5'  => __( 'Skin 5', 'connections-local-time' ),
				),
			),
			absint( $instance['skin'] )
		);

		cnHTML::field(
			array(
				'type'     => 'checkbox',
				'prefix'   => '',
				'id'       => $this->get_field_id('show_timezone'),
				'name'     => $this->get_field_name('show_timezone'),
				'label'    => __( 'Display the local time zone?', 'connections-local-time' ),
				'before'   => '<p>',
				'after'    => '</p>',
				'layout'   => '%field% %label%',
			),
			cnFormatting::toBoolean( $instance['show_timezone'] ) ? '1' : FALSE
		);

		cnHTML::field(
			array(
				'type'     => 'checkbox',
				'prefix'   => '',
				'id'       => $this->get_field_id('show_date'),
				'name'     => $this->get_field_name('show_date'),
				'label'    => __( 'Display the local date?', 'connections-local-time' ),
				'before'   => '<p>',
				'after'    => '</p>',
				'layout'   => '%field% %label%',
			),
			cnFormatting::toBoolean( $instance['show_date'] ) ? '1' : FALSE
		);

		cnHTML::field(
			array(
				'type'     => 'checkbox',
				'prefix'   => '',
				'id'       => $this->get_field_id('show_analog'),
				'name'     => $this->get_field_name('show_analog'),
				'label'    => __( 'Display the analog clock?', 'connections-local-time' ),
				'before'   => '<p>',
				'after'    => '</p>',
				'layout'   => '%field% %label%',
			),
			cnFormatting::toBoolean( $instance['show_analog'] ) ? '1' : FALSE
		);

		cnHTML::field(
			array(
				'type'     => 'checkbox',
				'prefix'   => '',
				'id'       => $this->get_field_id('show_digital'),
				'name'     => $this->get_field_name('show_digital'),
				'label'    => __( 'Display the digital clock?', 'connections-local-time' ),
				'before'   => '<p>',
				'after'    => '</p>',
				'layout'   => '%field% %label%',
			),
			cnFormatting::toBoolean( $instance['show_digital'] ) ? '1' : FALSE
		);

		cnHTML::text(
			array(
				'prefix' => '',
				'class'  => 'widefat',
				'id'     => $this->get_field_id('time_format'),
				'name'   => $this->get_field_name('time_format'),
				'label'  => __( 'Time format:', 'connections-local-time' ),
				'before' => '<p>',
				'after'  => '</p>',
			),
			esc_attr( $instance['time_format'] )
		);

		cnHTML::text(
			array(
				'prefix' => '',
				'class'  => 'widefat',
				'id'     => $this->get_field_id('date_format'),
				'name'   => $this->get_field_name('date_format'),
				'label'  => __( 'Date format:', 'connections-local-time' ),
				'before' => '<p>',
				'after'  => '</p>',
			),
			esc_attr( $instance['date_format'] )
		);
	}

	/**
	 * Callback to display the widget on the frontend.
	 *
	 * @access public
	 * @since  1.0
	 * @param array $atts
	 * @param array $instance
	 *
	 * @return void
	 */
	public function widget( $atts, $instance ) {

		// Only process and display the widget if displaying a single entry.
		if ( cnQuery::getVar( 'cn-entry-slug' ) ) {

			$instance = wp_parse_args( $instance, $this->defaults() );

			// Query the entry.
			$result = Connections_Directory()->retrieve->entry( urldecode( cnQuery::getVar( 'cn-entry-slug' ) ) );

			// Setup the entry object
			$entry = new cnEntry( $result );

			// Ensure the scripts are enqueued that are required by the widget.
			wp_enqueue_script( 'jquery-jClocksGMT' );

			/**
			 * @var string $before_widget
			 * @var string $before_title
			 * @var string $after_title
			 * @var string $after_widget
			 */
			extract( $atts );

			/*
			 * --> START <--
			 * Setup the default widget options if they were not set when they were added to the sidebar;
			 * the user did not click the "Save" button on the widget.
			 */

			// Add the `widget_title` filter to match the WP core widgets.
			$show_title = cnFormatting::toBoolean( $instance['show_title'] );
			$title      = apply_filters( 'widget_title', $instance['title'], $instance, $this->id_base, $this );

			/*
			 * --> END <--
			 * Setup the default widget options if they were not set when they were added to the sidebar;
			 * the user did not click the "Save" button on the widget.
			 */

			//$user = wp_get_current_user();

			$key   = 'cn-entry-' . $entry->getId();
			$group = 'cnw_clock-' . $this->number;

			//cnCache::clear( TRUE, 'transient', 'cnw' );
			$fragment = new cnFragment( $key, $group );

			ob_start();

			if ( ! $fragment->get() ) {

				$timezone = null;
				$offset   = null;

				$preferred = $entry->addresses->getPreferred();

				if ( $preferred instanceof cnAddress ) {

					$timezone = $preferred->getTimezone();

					if ( $timezone instanceof cnTimezone ) {

						$offset = $timezone->get_utc_offset( 'g' );
					}

				} else {

					/** @var cnAddress|null $address */
					$address = $entry->addresses->getCollection()->first();

					if ( $address instanceof cnAddress ) {

						$timezone = $address->getTimezone();

						if ( $timezone instanceof cnTimezone ) {

							$offset = $timezone->get_utc_offset( 'g' );
						}
					}
				}

				if ( ! is_null( $offset ) && $timezone instanceof cnTimezone ) {

					Connections_Local_Time::render(
						array(
							'title'      => cnFormatting::toBoolean( $instance['show_timezone'] ) ? $timezone->get_name() : '',
							'offset'     => (string) $offset,
							'dst'        => FALSE, // This is taken into account in {$offset}
							'digital'    => cnFormatting::toBoolean( $instance['show_digital'] ),
							'analog'     => cnFormatting::toBoolean( $instance['show_analog'] ),
							'timeformat' => $instance['time_format'],
							'date'       => cnFormatting::toBoolean( $instance['show_date'] ),
							'dateformat' => $instance['date_format'],
							'skin'       => $instance['skin'],
						)
					);
				}

				$fragment->save( DAY_IN_SECONDS );
			}

			$out = ob_get_clean();

			$widget = compact( 'before_widget', 'show_title', 'before_title', 'title', 'after_title', 'after_widget' );

			if ( ! empty( $out ) ) {

				$this->render( $widget, $out );

			} elseif ( $instance['display_no_results'] ) {

				//$out = '<ul class="cn-widget cn-upcoming-birthdays">';
				//$out .= '<li class="cat-item cn-entry"><span class="cn-widget cn-no-results">' . $message . '</span></li>';
				//$out .= '</ul>';

				//$this->render( $widget, $out );
			}
		}

	}

	/**
	 * Echoes the widget.
	 *
	 * @access public
	 * @since  1.0
	 *
	 * @param array  $widget
	 * @param string $html
	 */
	public function render( $widget, $html ) {

		/**
		 * @var string $before_widget
		 * @var bool   $show_title
		 * @var string $before_title
		 * @var string $title
		 * @var string $after_title
		 * @var string $after_widget
		 */
		extract( $widget );

		echo $before_widget;

		if ( $show_title ) {

			echo $before_title . $title . $after_title . PHP_EOL;
		}

		echo $html;

		echo $after_widget;
	}
}
