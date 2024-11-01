<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://www.neteuro.pt/
 * @since      1.0.0
 *
 * @package    Turisbook_Booking_System
 * @subpackage Turisbook_Booking_System/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Turisbook_Booking_System
 * @subpackage Turisbook_Booking_System/admin
 * @author     Neteuro <apoiotecnico@neteuro.net>
 */
class Turisbook_Booking_System_Admin {

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
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

		add_action( 'admin_init',  array( $this, 'register_mysettings' ) );
		add_action( 'admin_menu', array( $this, 'tbs_admin_menu' ) );
		add_action('admin_bar_menu',  array( $this, 'add_toolbar_items'), 1000);
		// add_action( 'plugin_action_links_' . TBS_PLUGIN_BASENAME, array( $this, 'ejag_admin_action_links'));

	}

	/**
	 * Register the stylesheets for the admin area.
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

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/turisbook-booking-system-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

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

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/turisbook-booking-system-admin.js', array( 'jquery' ), $this->version, false );
		wp_localize_script($this->plugin_name, 'TBS', array(
			'url' => admin_url('admin-ajax.php'),
			'nonce' => wp_create_nonce('ajax-nonce')
		));

	}


	function tbs_admin_menu() {
		$hook = add_management_page( 'Turisbook Booking System', 'Turisbook Booking System', 'install_plugins', 'turisbook-booking-system', array( $this, 'tbs_admin_page' ) );
		add_action( "load-$hook", array( $this, 'tbs_admin_page_load' ) );
	}

	function tbs_admin_page_load() {

	}

	function tbs_admin_page() {
		global $post;
		$hotel_id = get_option('turisbook-hotel-id',0);
		$token = get_option('turisbook-access-token',"");
		$token = get_option('turisbook-access-token',"");
		$establishment = get_option('turisbook-hotel-name',"");
		$page_prebook = get_option('turisbook-page-prebook',0);
		$page_confirmation = get_option('turisbook-page-confirmation',0);
		$page_availability_search = get_option('turisbook-page-availability-search',0);
		$page_prebook_policy = get_option('turisbook-page-prebook-policy',0);
		$analytics_booking_goal_enabled = get_option('turisbook-analytics-booking-goal-enabled',0);
		$cart_position = get_option('turisbook-cart-position', "-60");
		$cart_background = get_option('turisbook-cart-background', "#a7a7a7");
		$show_menu_apt = get_option('turisbook-show-menu-apt', 0);

		$loggedIn = trim($token) != "" && $hotel_id!=0;
		$args = array( 'post_type'=>'page','numberposts' => -1);
		$posts = get_posts($args);

		include_once(plugin_dir_path( __FILE__ ) .'partials/turisbook-booking-system-admin-display.php');
	}

	public function register_mysettings(){

		// Account Options
		register_setting( 'turisbook', 'turisbook-hotel-id', ['type' => 'intval', 'default'=>0] );
		register_setting( 'turisbook', 'turisbook-hotels', ['type' => 'string', 'default'=>'[]'] );
		register_setting( 'turisbook', 'turisbook-hotel-type', ['type' => 'intval', 'default'=>0] );
		register_setting( 'turisbook', 'turisbook-access-token', ['type' => 'string', 'default'=>'']);
		register_setting( 'turisbook', 'turisbook-hotel-name', ['type' => 'string', 'default'=>'']);
		register_setting( 'turisbook', 'turisbook-hotel-unique-id', ['type' => 'string', 'default'=>'']);
		register_setting( 'turisbook', 'turisbook-version', ['type' => 'string', 'default'=>1]);
		register_setting( 'turisbook', 'turisbook-show-menu-apt', ['type' => 'string', 'default'=>0]);
		register_setting( 'turisbook', 'turisbook-page-prebook', ['type' => 'string', 'default'=>0]);
		register_setting( 'turisbook', 'turisbook-page-confirmation', ['type' => 'string', 'default'=>0]);
		register_setting( 'turisbook', 'turisbook-page-availability-search', ['type' => 'string', 'default'=>0]);
		register_setting( 'turisbook', 'turisbook-page-prebook-policy', ['type' => 'string', 'default'=>0]);
		register_setting( 'turisbook', 'turisbook-hotel-payments', ['type' => 'string', 'default'=>'[]']);
		register_setting( 'turisbook', 'turisbook-display-type', ['type' => 'string', 'default'=>'default']);
		register_setting( 'turisbook', 'turisbook-children-max-age', ['type' => 'intval', 'default'=>12]);

		register_setting( 'turisbook', 'turisbook-analytics-booking-goal-enabled', ['type' => 'intval', 'default'=>'0']);

		register_setting( 'turisbook', 'turisbook-cart-position', ['type' => 'string', 'default'=> -60]);
		register_setting( 'turisbook', 'turisbook-cart-background', ['type' => 'string', 'default'=> "#a7a7a7"]);
	}

	function tbs_admin_action_links ( $actions ) {
		// $mylinks = array(
		// 	'<a href="' . esc_url( admin_url( 'tools.php?page=turisbook-booking-system' ) ) . '">' . __( 'Goals', 'turisbook-booking-system' ) . '</a>'
		// );
		// $actions = array_merge( $actions, $mylinks );
		// return $actions;
	}

	function add_toolbar_items($wp_admin_bar) {
		$icon = '<span class="ab-icon dashicons dashicons-update" style="top:3px"></span>';
		$args = array(
			'id' => 'turisbook-sync-data-top',
			'title' =>$icon.' Sync Turisbook Data',
			'href' =>  "#",
		);
		$wp_admin_bar->add_node($args);
	}

}
