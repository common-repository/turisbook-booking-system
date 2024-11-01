<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://www.neteuro.pt/
 * @since      1.0.0
 *
 * @package    Turisbook_Booking_System
 * @subpackage Turisbook_Booking_System/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Turisbook_Booking_System
 * @subpackage Turisbook_Booking_System/public
 * @author     Neteuro <apoiotecnico@neteuro.net>
 */
class Turisbook_Booking_System_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

		add_filter( 'query_vars', array( $this, 'add_query_vars_filter'));
	}
	
	public function add_query_vars_filter( $vars ){
		$vars[] = "uid"; // $unit_id
		$vars[] = "rid"; // $rate_id
		$vars[] = "unit_rates"; // $unit_rates
		$vars[] = "checkin"; // $checkin
		$vars[] = "checkout"; // $checkout
		$vars[] = "rooms"; // $total_rooms
		$vars[] = "adults"; // $total_adults
		$vars[] = "children"; // $total_children
		return $vars;
	}
	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Turisbook_Booking_System_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Turisbook_Booking_System_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name .'_bootstrap', plugin_dir_url( __FILE__ ) . 'css/bootstrap-iso.css', array(), $this->version, 'all' );
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/turisbook-booking-system-public.css?t='.time(), array(), $this->version, 'all' );
		wp_enqueue_style( $this->plugin_name .'_calendar', plugin_dir_url( __FILE__ ) . 'css/turisbook-booking-system-calendar.css?t='.time(), array(), $this->version, 'all' );
		wp_enqueue_style( $this->plugin_name .'_animation', plugin_dir_url( __FILE__ ) . 'css/turisbook-booking-system-animation.css', array(), $this->version, 'all' );
		wp_enqueue_style( $this->plugin_name .'_ratecategories', plugin_dir_url( __FILE__ ) . 'css/turisbook-booking-system-ratecategories.css', array(), $this->version, 'all' );
		wp_enqueue_style( $this->plugin_name .'_daterangepicker', plugin_dir_url( __FILE__ ) . 'css/daterangepicker.css', array(), $this->version, 'all' );
		wp_enqueue_style( $this->plugin_name .'_slider', plugin_dir_url( __FILE__ ) . 'css/turisbook-booking-system-slider.css', array(), $this->version, 'all' );
		wp_enqueue_style( $this->plugin_name .'_modal', plugin_dir_url( __FILE__ ) . 'css/turisbook-booking-system-modal.css', array(), $this->version, 'all' );

		wp_enqueue_style( $this->plugin_name .'_simplelightbox', plugin_dir_url( __FILE__ ) . 'vendors/simplelightbox/simple-lightbox.min.css', array(), $this->version, 'all' );
		wp_enqueue_style( $this->plugin_name .'_fancybox', plugin_dir_url( __FILE__ ) . 'vendors/fancybox/fancybox.min.css', array(), $this->version, 'all' );
		wp_enqueue_style( $this->plugin_name .'_popover_theme', plugin_dir_url( __FILE__ ) . 'vendors/popover/theme-turisbook.css', array(), $this->version, 'all' );
		wp_enqueue_style( $this->plugin_name .'_flatpickr', plugin_dir_url( __FILE__ ) . 'vendors/flatpickr/4.6.3/flatpickr.css', array(), $this->version, 'all' );


	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		$language = Turisbook_Booking_System::getLanguage();
		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Turisbook_Booking_System_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Turisbook_Booking_System_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name .'_moment', plugin_dir_url( __FILE__ ) . 'js/moment.min.js', array( 'jquery' ), $this->version, false );
		wp_enqueue_script( $this->plugin_name .'_daterangepicker', plugin_dir_url( __FILE__ ) . 'js/daterangepicker.js', array( 'jquery' ), $this->version, false );
		
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/turisbook-booking-system-public.js?t='.time(), array( 'jquery', $this->plugin_name.'_popover', 'wp-i18n' ), $this->version, false );
		wp_set_script_translations( $this->plugin_name, 'turisbook-booking-system' );
		
		// wp_enqueue_script( $this->plugin_name.'-shorcode-calendar', plugin_dir_url( __FILE__ ) . 'js/turisbook-booking-system-shortcode-calendar.js?t='.time(), array( 'jquery' ), $this->version, false );
		wp_enqueue_script( $this->plugin_name.'-slider', plugin_dir_url( __FILE__ ) . 'js/turisbook-booking-system-slider.js?t='.time(), array( 'jquery' ), $this->version, false );
		wp_enqueue_script( $this->plugin_name.'-modal', plugin_dir_url( __FILE__ ) . 'js/turisbook-booking-system-modal.js?t='.time(), array( 'jquery' ), $this->version, false );
		wp_enqueue_script( $this->plugin_name.'-sticky-anchor', plugin_dir_url( __FILE__ ) . 'js/turisbook-booking-system-sticky-anchor.js?t='.time(), array( 'jquery' ), $this->version, false );
		wp_enqueue_script( $this->plugin_name.'-print-element', plugin_dir_url( __FILE__ ) . 'js/jquery.printElement.js', array( 'jquery' ), $this->version, false );
		

		// Vendors
		wp_enqueue_script( $this->plugin_name.'_simplelightbox', plugin_dir_url( __FILE__ ) . 'vendors/simplelightbox/simple-lightbox.min.js', array( 'jquery' ), $this->version, false );
		wp_enqueue_script( $this->plugin_name.'_fancybox', plugin_dir_url( __FILE__ ) . 'vendors/fancybox/fancybox.min.js', array( 'jquery' ), $this->version, false );
		wp_enqueue_script( $this->plugin_name.'_popover', plugin_dir_url( __FILE__ ) . 'vendors/popover/popover.min.js', array( 'jquery' ), $this->version, false );
		wp_enqueue_script( $this->plugin_name.'_flatpickr', plugin_dir_url( __FILE__ ) . 'vendors/flatpickr/4.6.3/flatpickr.js', array( 'jquery' ), $this->version, false );
		wp_enqueue_script( $this->plugin_name.'_flatpickr_locale', plugin_dir_url( __FILE__ ) . 'vendors/flatpickr/4.6.3/l10n/'.$language.'.js', array(  $this->plugin_name.'_flatpickr' ), $this->version, false );
		
		$establishment_types = [];
		$establishment_types[1] = ['slug' => 'room', 'singular' => __( 'Room', 'turisbook-booking-system' ), 'plural' => __( 'Rooms', 'turisbook-booking-system' )];
		$establishment_types[2] = ['slug' => 'apartment', 'singular' => __( 'Apartment', 'turisbook-booking-system' ), 'plural' => __( 'Apartments', 'turisbook-booking-system' )];
		$establishment_types[3] = ['slug' => 'hostel', 'singular' => __( 'Hostel', 'turisbook-booking-system' ), 'plural' => __( 'Hostels', 'turisbook-booking-system' )];
		$establishment_types[4] = ['slug' => 'villa', 'singular' => __( 'Villa', 'turisbook-booking-system' ), 'plural' => __( 'Villas', 'turisbook-booking-system' )];


		$establishment_type_id= get_option('turisbook-hotel-type',1);
		$analytics_booking_goal_enabled= get_option('turisbook-analytics-booking-goal-enabled',0);
		$establishment_type= $establishment_types[$establishment_type_id];
		$type_name = $establishment_type['singular'];
		$type_name_plural = $establishment_type['plural'];

		$vars = [
			'url' => admin_url('admin-ajax.php'),
			'nonce' => wp_create_nonce('ajax-nonce'),
			'lang' => $language,
			'typeSingle' => $type_name,
			'typePlural' => $type_name_plural,
			'translations' => ['quantity' => __('Quantity','turisbook-booking-system')],
			'analytics_booking_goal_enabled' => $analytics_booking_goal_enabled
		];

		wp_localize_script($this->plugin_name, 'TBS', $vars);
		wp_localize_script($this->plugin_name.'-shorcode-calendar', 'TBS', $vars);
		

	}
}
