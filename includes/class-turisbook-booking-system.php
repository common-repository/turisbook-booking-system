<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://www.neteuro.pt/
 * @since      1.0.0
 *
 * @package    Turisbook_Booking_System
 * @subpackage Turisbook_Booking_System/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Turisbook_Booking_System
 * @subpackage Turisbook_Booking_System/includes
 * @author     Neteuro <apoiotecnico@neteuro.net>
 */
class Turisbook_Booking_System {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Turisbook_Booking_System_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'TURISBOOK_BOOKING_SYSTEM_VERSION' ) ) {
			$this->version = TURISBOOK_BOOKING_SYSTEM_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'turisbook-booking-system';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Turisbook_Booking_System_Loader. Orchestrates the hooks of the plugin.
	 * - Turisbook_Booking_System_i18n. Defines internationalization functionality.
	 * - Turisbook_Booking_System_Admin. Defines all hooks for the admin area.
	 * - Turisbook_Booking_System_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-turisbook-booking-system-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-turisbook-booking-system-i18n.php';


		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/turisbook-booking-system-cron.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/post_types/turisbook-establishments-post-type.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/post_types/turisbook-rooms-post-type.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/taxonomies/turisbook-room-amenity-taxonomy.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/metaboxes/turisbook-room-metaboxes.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/metaboxes/turisbook-room-gallery.php';

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-turisbook-booking-system-ajax.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/shortcodes/inc.php';
		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-turisbook-booking-system-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-turisbook-booking-system-public.php';

		$this->loader = new Turisbook_Booking_System_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Turisbook_Booking_System_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Turisbook_Booking_System_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Turisbook_Booking_System_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Turisbook_Booking_System_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Turisbook_Booking_System_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}


	static public function checkdate($d){
		if(empty(trim($d))) return false;

		$date = new DateTime($d . ' 00:00:00');
		return checkdate($date->format('m'), $date->format('d'), $date->format('Y'));

	} 

	public static function registerAjax($name, $fn, $public=true){add_action('wp_ajax_'.$name, $fn); if($public)add_action('wp_ajax_nopriv_'.$name, $fn);}
	static public function getMonths(){

		return [1 => __("January","turisbook-booking-system"), 2 => __("February","turisbook-booking-system"), 3 => __("March","turisbook-booking-system"), 4 => __("April","turisbook-booking-system"), 5 => __("May","turisbook-booking-system"), 6 => __("June","turisbook-booking-system"), 7 => __("July","turisbook-booking-system"), 8 => __("August","turisbook-booking-system"), 9 => __("September","turisbook-booking-system"), 10 => __("October","turisbook-booking-system"), 11 => __("November","turisbook-booking-system"), 12 => __("December","turisbook-booking-system"), ];
	}

	public function getKeys(){
		$toreturn = [
			'post_type' => 'turisbook_room',
			'amenity_taxonomy' => 'turisbook_amenity',
			'gallery' => 'turisbook_room_gallery'
		];
		return $toreturn;
	}


	private function getVisitorIP(){

		if(!empty($_SERVER['HTTP_CLIENT_IP'])) $ip = $_SERVER['HTTP_CLIENT_IP'];
		else if(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		else $ip = $_SERVER['REMOTE_ADDR'];

		return $ip;
	}

	public function getVisitorInfo(){
		$toreturn = [
			'website' => get_site_url(),
			'ip' => $this->getVisitorIp(),
			'user_agent' => $_SERVER['HTTP_USER_AGENT']
		];

		return $toreturn;
	}


	private function sanitizePrebookLines($lines){
		$sanitized = [];

		foreach($lines as $line){
			$l = new stdClass();
			foreach($line as $key => $value){
				if(is_array($value)){
					$l->$key = $this->sanitizePrebookLines($value);
				}else{
					$l->$key = (int)$value;
				}
			}
			$sanitized[] = $l;
		}
		return $sanitized;

	}
	public function getPrebookCookie(){
		if(isset($_COOKIE['tbs-prebook-cookie'])){
			$c_prebook = json_decode(str_replace("\\", '', $_COOKIE['tbs-prebook-cookie']));
			$c_prebook->invalid = false;
			$roomrates = $c_prebook->room_rates;
		}else{
			$c_prebook = new stdClass();
			$c_prebook->invalid = true;
			$roomrates = [];
		}


		$sanitized_prebook = new stdClass();
		$sanitized_prebook->room_rates = [];
		$sanitized_prebook->invalid = $c_prebook->invalid;
		$sanitized_prebook->checkin = isset($c_prebook->checkin) ? esc_attr($c_prebook->checkin) : '';
		$sanitized_prebook->checkout = isset($c_prebook->checkout) ? esc_attr($c_prebook->checkout) : '';
		$sanitized_prebook->cupon = isset($c_prebook->cupon) ? $c_prebook->cupon : '';
		$sanitized_prebook->hotel = isset($c_prebook->hotel) ? $c_prebook->hotel : 0;
		foreach($roomrates as $rr){
			$srr = new stdCLass();
			$srr->room = esc_attr($rr->room);
			$srr->lines = $this->sanitizePrebookLines($rr->lines);
			$sanitized_prebook->room_rates[] = $srr;
		}

		return $sanitized_prebook;
	}

	public function validateDate($date, $format){
		if(!$date || trim($date) == '') return false;
		$date = DateTime::createFromFormat($format, $date);
		$date_errors = DateTime::getLastErrors();
		return $date_errors ? $date_errors['warning_count'] + $date_errors['error_count'] == 0 : true;
	}

	public function validateCheckinCheckout($test_checkin, $test_checkout, $format='d-m-Y'){
		$validate_checkin = $this->validateDate($test_checkin, $format);
		$hasdates = false;
		if(!$validate_checkin){
			$checkin = date($format);
			$date = new DateTime($checkin . ' 00:00:00');
			$date->modify('+1 day');
			$checkout = $date->format($format);
			$hasdates = true;
		}else{
			$checkin = $test_checkin;
			$validate_checkout = $this->validateDate($test_checkout, $format);
			if(!$validate_checkout){
				$date = new DateTime($checkin . ' 00:00:00');
				$date->modify('+1 day');
				$checkout = $date->format($format);
			}else{
				$checkout = $test_checkout;
				$checkin_time = strtotime($checkin);
				$checkout_time = strtotime($checkout);

				if($checkout_time < $checkin_time){
					$temp = $checkin;
					$checkin = $checkout;
					$checkout = $temp;
				}else if($checkout_time == $checkin_time){
					$date = new DateTime($checkin . ' 00:00:00');
					$date->modify('+1 day');
					$checkout = $date->format($format);
				}
			}
			$hasdates = true;
		}

		
		
		$hasdates = $hasdates && $test_checkin !== false;

		return ['checkin'=>$checkin, 'checkout'=>$checkout, 'hasdates' => $hasdates, 'validate_checkin' => $validate_checkin];

	}

	static function getLanguage(){ return Turisbook_Booking_System::calcLanguage( get_bloginfo ( 'language' ));}
	static function calcLanguage($code){ return substr( $code, 0, 2 );}
	static public function getCountries(){
		$handle = fopen(TURISBOOK_ADMIN_PATH.'/files/countries.csv','r');
		$countries = [];
		while ( ($data = fgetcsv($handle) ) !== FALSE ) {
			$countries[$data[0]] = $data[1];
		}

		return $countries;
	}
	public function PolylangIsActive(){
		return function_exists('pll_languages_list');
	}

	public function getLanguagesList(){
		$languages = [];
		if (function_exists('pll_languages_list')) {
			$langs = pll_languages_list(array('fields' => array()));

			foreach ($langs as $lang){
				$languages[] = $lang->slug;
			}
		}
		return $languages;
	}

	public function login($token){
		$url = TURISBOOK_API_URL."/tokenLogin/".$token;

		$options = [
			'method' => 'GET',
			'timeout' => 60,
			'redirection' => 5,
			'sslverify' => false,
			'httpversion' => '1.0',
			'blocking' => true,
			'headers' => $headr,
			'cookies' => array()
		];

		$result = wp_remote_get($url,$options);
		return $result;
	}



	public function getEstablishments($all = false){
		$token = get_option( 'turisbook-access-token', "" );
		$url = TURISBOOK_API_URL . "/establishments";
		$url .= "?nowrap";
		if($all) $url .= "&showall";
		$headr = array();
		$headr['Content-type'] = 'application/json';
		$headr['Accept'] = 'application/json';


		$headr['Authorization'] = 'Bearer ' . $token;

		$options = [
			'method' => 'GET',
			'timeout' => 60,
			'redirection' => 5,
			'sslverify' => false,
			'httpversion' => '1.0',
			'blocking' => true,
			'headers' => $headr,
			'cookies' => array()
		];


		$result = wp_remote_get($url,$options);

		$toreturn = [];
		$toreturn['httpcode'] = wp_remote_retrieve_response_code($result);


		if($toreturn['httpcode']==200){
			$toreturn['establishments'] = json_decode(wp_remote_retrieve_body($result));
			update_option('turisbook-hotels',wp_remote_retrieve_body($result));
		}
		return $toreturn;
	}

	public function getCardex($uid){
		$token = get_option( 'turisbook-access-token', "" );
		$url = TURISBOOK_API_URL . "/cardex/uid/".$uid;
		$headr = array();
		$headr['Content-type'] = 'application/json';
		$headr['Accept'] = 'application/json';

		$headr['Authorization'] = 'Bearer ' . $token;

		$options = [
			'method' => 'GET',
			'timeout' => 60,
			'redirection' => 5,
			'sslverify' => false,
			'httpversion' => '1.0',
			'blocking' => true,
			'headers' => $headr,
			'cookies' => array()
		];


		$result = wp_remote_get($url,$options);

		$toreturn = [];
		$toreturn['cardex'] = [];
		$toreturn['url'] = $url;
		$toreturn['httpcode'] = wp_remote_retrieve_response_code($result);


		if($toreturn['httpcode']==200){
			$json = json_decode(wp_remote_retrieve_body($result));
			$toreturn['cardex'] = $json->data;
		}else{
			$toreturn['response'] = $result;

		}
		return $toreturn;
	}

	private function getTermByMeta($taxonomy, $meta_key, $value, $compare="="){
		$args = array(
			'hide_empty' => false,
			'meta_query' => array(
				array(
					'key'       => $meta_key,
					'value'     => $value,
					'compare'   => $compare
				)
			),
			'taxonomy'  => $taxonomy,
		);
		return get_terms( $args );
	}



	public function getPostByMeta( $args = array() )
	{
		$args = ( object )wp_parse_args( $args );
		$op = array(
			'meta_query' => array(
				array(
					'key'       => $args->meta_key,
					'value'     => $args->meta_value
				)
			),
			'post_type' => empty($args->post_type) ? 'post' : $args->post_type,
			'posts_per_page'    => '1',
			'suppress_filters' => true,
		);

		$posts = get_posts( $op );
		if ( !$posts || is_wp_error( $posts ) ) {
		 	// var_dump($posts);
			return false;
		}
		return $posts[0];
	}

	private function syncAmenitiesNolang($amenities){
		$keys = $this->getKeys();
		$taxonomy = $keys['amenity_taxonomy'];
		$term_meta_key = 'turisbook_amenity_id';
		foreach($amenities as $amenity){
			$terms = $this->getTermByMeta($taxonomy, $term_meta_key ,$amenity->id);
			foreach($amenity->translations->data as $tr){
				if(!$tr->isdefault) continue;
				if(count($terms) > 0){
					$term = $terms[0];
					wp_update_term( $term->term_id, $taxonomy, ['name' => $tr->name] );
					break;
				}else{
					$term = wp_insert_term( $tr->name, $taxonomy);
					$slug = $tr->name;
					while ( is_wp_error($term) ){
						$slug = sanitize_title($slug . " " . $tr->code);
						$term = wp_insert_term( $tr->name, $taxonomy,['slug'=>$slug]);
					}

					if(is_array($term) && isset($term['term_id'])){
						add_term_meta( $term['term_id'], $term_meta_key, $amenity->id);
					}	
					break;
				}
			}
		}
	}
	private function syncAmenitiesPolylang($amenities){
		$keys = $this->getKeys();
		$taxonomy = $keys['amenity_taxonomy'];
		$term_meta_key = 'turisbook_amenity_id';
		$languages = $this->getLanguagesList();
		$default_language_slug = pll_default_language('slug');

		foreach($amenities as $amenity){
			$amenities_languages = [];
			$terms = $this->getTermByMeta($taxonomy, $term_meta_key ,$amenity->id);


			foreach($amenity->translations->data as $tr){
				if(!in_array($tr->code, $languages)) continue;

				if(count($terms) > 0){
					$term = $terms[0];
					$tt_id = pll_get_term($term->term_id, $tr->code);

					if($tt_id){
						wp_update_term( $tt_id, $taxonomy, ['name' => $tr->name] );
					}else{
						$term_new = wp_insert_term( $tr->name, $taxonomy);
						$new_slug = $tr->name;
						while ( is_wp_error($term) ){
							$new_slug = sanitize_title($new_slug . " " . $tr->code);
							$term_new = wp_insert_term( $tr->name, $taxonomy,['slug'=>$new_slug]);
						}

						if(!is_wp_error($term_new)){
							pll_set_term_language($term_new['term_id'], $tr->code);
							$tt_id = $term_new['term_id'];
						}else{
							error_log($term_new->get_error_message());

						}
					}
					$amenities_languages[$tr->code] =  $tt_id;
				}else{
					$term = wp_insert_term( $tr->name, $taxonomy);
					$new_slug = $tr->name;

					while ( is_wp_error($term) ){
						$new_slug = sanitize_title($new_slug . " " . $tr->code);
						$term = wp_insert_term( $tr->name, $taxonomy,['slug'=>$new_slug]);
					}

					if(is_array($term) && isset($term['term_id'])){
						pll_set_term_language($term['term_id'], $tr->code);
						$amenities_languages[$tr->code] = $term['term_id'];
						if($tr->code == $default_language_slug)	add_term_meta( $term['term_id'], $term_meta_key, $amenity->id);
					}						
				}
			}
			pll_save_term_translations($amenities_languages);
		}
	}

	public function syncAmenities(){
		$token = get_option( 'turisbook-access-token', "" );
		$url = TURISBOOK_API_URL . "/amenities?expand=translations";
		$headr = array();
		$headr['Content-type'] = 'application/json';
		$headr['Accept'] = 'application/json';

		$headr['Authorization'] = 'Bearer ' . $token;

		$options = ['method' => 'GET', 'timeout' => 60, 'redirection' => 5, 'sslverify' => false, 'httpversion' => '1.0', 'blocking' => true, 'headers' => $headr, 'cookies' => array() ];

		$result = wp_remote_get($url,$options);

		$code = wp_remote_retrieve_response_code($result);

		if($code==200){
			$json = json_decode(wp_remote_retrieve_body($result));

			$amenities = $json->data;

			if($this->PolylangIsActive()){
				$this->syncAmenitiesPolylang($amenities);
			}else{
				$this->syncAmenitiesNolang($amenities);
			}

		}
	}

	private function syncRoomImages($post_id, $images){

		require_once(ABSPATH . 'wp-admin/includes/image.php');
		require_once(ABSPATH . "wp-admin" . '/includes/media.php');
		global $user_ID;
		$upload_dir = wp_get_upload_dir()["basedir"];
		$keys = $this->getKeys();
		$gallery_key = $keys['gallery'];
		$ids = [];
		$cover_id = 0;
		foreach($images as $img){
			$local_img = $this->getPostByMeta(['meta_key' => 'turisbook_img_id', 'meta_value' => $img->id, "post_type"=>"attachment"]); 
			$attach_id = 0;
			if(!$local_img){
				$tmp_file = download_url( $img->meta->href, $timeout = 300);

				$folder_path = $upload_dir . '/turisbook/';

				if (!file_exists($folder_path)) {
					mkdir($folder_path, 0777, true);
				}
				$filepath = $folder_path.$img->filename;

				if(copy( $tmp_file, $filepath )){
					$filetype = wp_check_filetype( $img->filename, null );


					$file_array = array(
						'name' => basename( $filepath ),
						'type' => $filetype['type'],
						'tmp_name' => $filepath,
						'error' => 0,
						'size' => filesize( $filepath )
					);

					$attachment = array(
						'post_mime_type' => $filetype['type'],
						'post_title' => sanitize_file_name( $img->filename ),
						'post_content' => '',
						'post_status' => 'inherit'
					);

					$attach_id = wp_insert_attachment( $attachment, $filepath, $post_id);
					$attach_data = wp_generate_attachment_metadata( $attach_id, $filepath );

					wp_update_attachment_metadata( $attach_id, $attach_data );
					update_post_meta($attach_id,"turisbook_img_id",$img->id);

					$ids[] = $attach_id;
				}

				@unlink( $tmp_file );


			}else{
				$ids[] = $local_img->ID;
				$attach_id = $local_img->ID;
			}


			if($img->iscover==1 || $cover_id == 0) $cover_id = $attach_id;
		}
		update_post_meta($post_id,"_thumbnail_id",$cover_id);
		update_post_meta($post_id, $gallery_key,implode(',',$ids));
	}

	private function updateRoomMeta($post_id, $room){
					// Seasons
		update_post_meta($post_id,"turisbook_seasons",$room->seasons);
					// update_post_meta('turisbook_room_rnal', $room->);
		$sleeps = (int)$room->lotation_base + (int)$room->extrabed_max;
		update_post_meta($post_id,'turisbook_room_sleeps', $sleeps);
		update_post_meta($post_id,'turisbook_room_adult_capacity', $room->lotation);
		update_post_meta($post_id,'turisbook_room_children_capacity', $room->lotation_kids);
		update_post_meta($post_id,'turisbook_room_extrabed_children', $room->extrabed_children);
		update_post_meta($post_id,'turisbook_room_extrabed_adults', $room->extrabed_adults);
		update_post_meta($post_id,'turisbook_room_extrabed_max', $room->extrabed_max);
		update_post_meta($post_id,'turisbook_room_rooms', $room->bedrooms);
		update_post_meta($post_id,'turisbook_room_bathrooms', $room->bathrooms);
		update_post_meta($post_id,'turisbook_room_lotation_base', $room->lotation_base);
		update_post_meta($post_id,'turisbook_room_latitude', $room->latitude);
		update_post_meta($post_id,'turisbook_room_longitude', $room->longitude);
		update_post_meta($post_id,'turisbook_room_location_name', $room->location_name);
		update_post_meta($post_id,'turisbook_room_location_id', $room->location_id);
		update_post_meta($post_id,'turisbook_room_establishment', $room->establishment);
	}

	private function syncRoomsNolang($rooms){
		$post_type = 'turisbook_room';
		$post_meta_key = 'turisbook_room_id';
		$term_meta_key = 'turisbook_amenity_id';
		$taxonomy = "turisbook_amenity";
		$rooms_ids = [];
		foreach($rooms as $room){


			$post = $this->getPostByMeta(['meta_key' => $post_meta_key, 'meta_value' => $room->id,'post_type'=>$post_type]);
			$post_id=0;

			$rooms_ids[] = $room->id;

			$post_languages = [];
			foreach($room->translations->data as $tr){

				if(!$tr->isdefault){ continue; }

				if($post){
					$post_id = $post->ID;
					wp_update_post( ['ID' => $post_id,'post_title' => $tr->name,'post_content' => $tr->description, 'post_status' => 'publish' ,'post_excerpt' => $tr->small_description, 'menu_order' => $room->ordering] );
				}else{
					$new_post = array('post_title' => $tr->name,'post_content' => $tr->description,'post_excerpt' => $tr->small_description,'post_status' => 'publish','post_date' => date('Y-m-d H:i:s'),'post_author' => 1,'post_type' => $post_type,'post_category' => [], 'menu_order' => $room->ordering);
					$post_id = wp_insert_post($new_post);

					$new_slug = $tr->name;
					while ( is_wp_error($post_id) ){
						$new_slug .= " ".$tr->code; 
						$new_post['post_name'] = sanitize_title($new_slug);
						$post_id = wp_insert_post($new_post);
					}
					add_post_meta($post_id,"turisbook_room_id",$room->id);
				}

				if($post_id > 0){

					$this->updateRoomMeta($post_id, $room);
					$this->updateRoomColors($post_id,$room);
					
					// Room Amenities
					$a_ids = [];

					foreach($room->amenities->data as $amenity){
						$a_ids[] = $amenity->id;
					}
					if(count($a_ids)>0){
						$terms = $this->getTermByMeta($taxonomy, $term_meta_key ,$a_ids,'IN');
						$terms_ids = [];
						foreach($terms as $term){
							$terms_ids[] = $term->term_id;
						}
						wp_set_object_terms( $post_id, $terms_ids, $taxonomy,false);
					}else{
						wp_set_object_terms( $post_id, [], $taxonomy,false);
					}

					// Room Gallery
					$this->syncRoomImages($post_id, $room->images->data);
				}
			}
		}
		//if(count($room->id) > 0) $this->DisabledRoomsToDraft(['meta_key' => $post_meta_key, 'meta_value' => $rooms_ids,'post_type'=>$post_type]);
	}

	private function syncRoomsPolylang($rooms){

		$languages = $this->getLanguagesList();
		$default_language_slug = pll_default_language('slug');
		$post_type = 'turisbook_room';
		$post_meta_key = 'turisbook_room_id';
		$term_meta_key = 'turisbook_amenity_id';
		$taxonomy = "turisbook_amenity";
		$rooms_ids = [];

		foreach($rooms as $room){

			$post = $this->getPostByMeta(['meta_key' => $post_meta_key, 'meta_value' => $room->id,'post_type'=>$post_type]);
			$rooms_ids[] = $room->id;
			$post_id=0;

			$post_languages = [];
			foreach($room->translations->data as $tr){
				if(!in_array($tr->code, $languages)){ continue; }
				$new_post = array('post_title' => $tr->name,'post_content' => $tr->description,'post_excerpt' => $tr->small_description,'post_status' => 'publish','post_date' => date('Y-m-d H:i:s'),'post_author' => 1,'post_type' => $post_type,'post_category' => [], 'menu_order' => $room->ordering);

				if($post){
					$post_id = pll_get_post($post->ID, $tr->code);

					if($post_id){
						wp_update_term( $post_id, $taxonomy, ['name' => $tr->name] );
						wp_update_post( ['ID' => $post_id,'post_title' => $tr->name,'post_content' => $tr->description,'post_excerpt' => $tr->small_description, 'post_status' => 'publish', 'menu_order' => $room->ordering] );
					}else{
						$post_id = wp_insert_post($new_post);
						$new_slug = $tr->name;
						while ( is_wp_error($post_id) ){
							$new_slug .= " ".$tr->code; 
							$new_post['post_name'] = sanitize_title($new_slug);
							$post_id = wp_insert_post($new_post);
						}
						pll_set_post_language($post_id, $tr->code);
						add_post_meta($post_id,"turisbook_room_id",$room->id);
					}
					$post_languages[$tr->code] =  $post_id;
				}else{
					$post_id = wp_insert_post($new_post);

					$new_slug = $tr->name;

					while ( is_wp_error($post_id) ){
						$new_slug .= " ".$tr->code; 
						$new_post['post_name'] = sanitize_title($new_slug);
						$post_id = wp_insert_post($new_post);
					}
					pll_set_post_language($post_id, $tr->code);
					$post_languages[$tr->code] = $post_id;

					add_post_meta($post_id,"turisbook_room_id",$room->id);
				}

				if($post_id > 0){
					$this->updateRoomMeta($post_id, $room);

					$this->updateRoomColors($post_id,$room);
					

					// Room Amenities
					$a_ids = [];

					foreach($room->amenities->data as $amenity){
						$a_ids[] = $amenity->id;
					}
					if(count($a_ids)>0){
						$terms = $this->getTermByMeta($taxonomy, $term_meta_key ,$a_ids,'IN');
						$terms_ids = [];
						foreach($terms as $term){
							$tt_id = pll_get_term($term->term_id, $tr->code);

							if($tt_id){
								$terms_ids[] = $tt_id;
							}
						}
						wp_set_object_terms( $post_id, $terms_ids, $taxonomy,false);
					}else{
						wp_set_object_terms( $post_id, [], $taxonomy,false);
					}

					// Room Gallery
					$this->syncRoomImages($post_id, $room->images->data);
				}
			}
			pll_save_post_translations($post_languages);
		}
		//if(count($rooms_ids) > 0) $this->DisabledRoomsToDraft(['meta_key' => $post_meta_key, 'meta_value' => $rooms_ids,'post_type'=>$post_type]);
	}

	public function syncRoomsInactiveOrDeleted($id_hotel = -1){
		$toreturn = [];
		$languages = $this->getLanguagesList();
		$default_language_slug = pll_default_language('slug');
		$post_type = 'turisbook_room';
		$post_meta_key = 'turisbook_room_id';
		$term_meta_key = 'turisbook_amenity_id';
		$taxonomy = "turisbook_amenity";

		global $user_ID;
		$token = get_option('turisbook-access-token',"");
		$id_hotel = $id_hotel == -1 ? get_option('turisbook-hotel-id',-1) : $id_hotel;
		if($token=='' || $id_hotel == -1) return false;

		$url_arr = [];
		$url_arr[] = TURISBOOK_API_URL;
		if($id_hotel > 0) $url_arr[] = $id_hotel;
		$url_arr[] = "InactiveAndRemoved";


		$url = implode('/',$url_arr);
		$headr = array();
		$headr['Content-type'] = 'application/json';
		$headr['Accept'] = 'application/json';

		$headr['Authorization'] = 'Bearer ' . $token;

		$options = ['method' => 'GET', 'timeout' => 60, 'redirection' => 5, 'sslverify' => false, 'httpversion' => '1.0', 'blocking' => true, 'headers' => $headr, 'cookies' => array() ];

		$result = wp_remote_get($url,$options);
		$code = wp_remote_retrieve_response_code($result);
		if($code==200){
			$json = json_decode(wp_remote_retrieve_body($result));

			$inactive = $json->inactive;
			$removed = $json->removed;

			if(count($inactive) > 0) $this->DisableOrDeleteRooms('disable',['meta_key' => $post_meta_key, 'meta_value' => $inactive,'post_type'=>$post_type]);
			if(count($removed) > 0) $this->DisableOrDeleteRooms('remove',['meta_key' => $post_meta_key, 'meta_value' => $removed,'post_type'=>$post_type]);

		}
	}

	public function DisableOrDeleteRooms( $type='', $args = array() )
	{

		if(!in_array($type, ['remove','disable'])) return false; 

		$args = ( object )wp_parse_args( $args );
		$op = array(
			'meta_query' => array(
				array(
					'key'       => $args->meta_key,
					'value'     => $args->meta_value,
					'compare'     => 'IN'
				)
			),
			'post_type' => empty($args->post_type) ? 'post' : $args->post_type,
			'posts_per_page'    => '-1',
			'suppress_filters' => true,
		);

		$languages = $this->getLanguagesList();
		$default_language_slug = pll_default_language('slug');

		$posts = get_posts( $op );
		if ( $posts && !is_wp_error( $posts ) ){

			foreach($posts as $p){
				$postid = $p->ID;

				if($type=='disable') wp_update_post(['ID' => $postid,'post_status' => 'draft']);
				else if($type=='remove') wp_delete_post($postid, true);

				if($this->PolylangIsActive()){
					foreach($languages as $lang){
						if($default_language_slug == $lang) continue;
						$post_id = pll_get_post($postid, $lang);
						if($type=='disable') wp_update_post(['ID' => $post_id,'post_status' => 'draft']);
						else if($type=='remove') wp_delete_post($post_id, true);
					}
				}
			}
		}
	}

	public function updateRoomColors($post_id, $room){
		$estab = $this->getEstablishmentById($room->establishment);

		$color_background = get_post_meta($estab->ID,'turisbook_color_background',true);
		$color_text = get_post_meta($estab->ID,'turisbook_color_text',true);
		$color_title = get_post_meta($estab->ID,'turisbook_color_title',true);

		update_post_meta($post_id, 'turisbook_color_background', $color_background);
		update_post_meta($post_id, 'turisbook_color_text', $color_text);
		update_post_meta($post_id, 'turisbook_color_title', $color_title);

	}

	public function syncRooms($id_hotel = -1){
		$toreturn = [];
		global $user_ID;
		$token = get_option('turisbook-access-token',"");
		$id_hotel = $id_hotel == -1 ? get_option('turisbook-hotel-id',-1) : $id_hotel;
		if($token=='' || $id_hotel == -1) return false;

		$url_arr = [];
		$url_arr[] = TURISBOOK_API_URL;
		if($id_hotel > 0) $url_arr[] = $id_hotel;
		$url_arr[] = "rooms?expand=images,translations,amenities";


		$url = implode('/',$url_arr);
		$headr = array();
		$headr['Content-type'] = 'application/json';
		$headr['Accept'] = 'application/json';

		$headr['Authorization'] = 'Bearer ' . $token;

		$options = ['method' => 'GET', 'timeout' => 60, 'redirection' => 5, 'sslverify' => false, 'httpversion' => '1.0', 'blocking' => true, 'headers' => $headr, 'cookies' => array() ];


		$result = wp_remote_get($url,$options);
		$code = wp_remote_retrieve_response_code($result);
		if($code==200){
			$json = json_decode(wp_remote_retrieve_body($result));

			$rooms = $json->data;

			if($this->PolylangIsActive()){
				$this->syncRoomsPolylang($rooms);
			}else{
				$this->syncRoomsNolang($rooms);
			}
		}

		$toreturn['code'] = $code;
		$toreturn['url'] = $url;

		return $toreturn;
	}



	public function getEstablishmentById($id){
		return $this->getPostByMeta(['meta_key' => 'turisbook_establishment_id', 'meta_value' => $id,'post_type'=>'turisbook_estab']);
	}

	public function syncEstablishments(){
		$toreturn = [];
		global $user_ID;
		$token = get_option('turisbook-access-token',"");
		$id_hotel = get_option('turisbook-hotel-id',-1);
		if($token=='' || $id_hotel == -1) return false;

		$url_arr = [];
		$url_arr[] = TURISBOOK_API_URL;
		$url_arr[] = "establishments";
		//if($id_hotel>0) $url_arr[] = $id_hotel;
		if(defined('TURISBOOK_ADMIN_VERSION')) $url_arr[] = "?showall";



		$url = implode('/',$url_arr);

		$headr = array();
		$headr['Content-type'] = 'application/json';
		$headr['Accept'] = 'application/json';

		$headr['Authorization'] = 'Bearer ' . $token;

		$options = ['method' => 'GET', 'timeout' => 60, 'redirection' => 5, 'sslverify' => false, 'httpversion' => '1.0', 'blocking' => true, 'headers' => $headr, 'cookies' => array() ];


		$result = wp_remote_get($url,$options);
		$code = wp_remote_retrieve_response_code($result);
		if($code==200){
			$json = json_decode(wp_remote_retrieve_body($result));

			$establishments = $json->data;

			$post_type = 'turisbook_estab';
			$post_meta_key = 'turisbook_establishment_id';
			$establishments_ids = [];

			$toreturn['result'] = $establishments;
			foreach($establishments as $establishment){
				$post = $this->getPostByMeta(['meta_key' => $post_meta_key, 'meta_value' => $establishment->id,'post_type'=>$post_type]);
				$establishments_ids[] = $establishment->id;
				$post_id=0;


				if($post){
					$post_id = $post->ID;
					wp_update_post( ['ID' => $post_id,'post_title' => $establishment->name,'post_content' => "", 'post_status' => 'publish' ,'post_excerpt' => ""] );
				}else{
					$new_post = array('post_title' => $establishment->name,'post_content' => "",'post_excerpt' => "",'post_status' => 'publish','post_date' => date('Y-m-d H:i:s'),'post_author' => 1,'post_type' => $post_type);
					$post_id = wp_insert_post($new_post);

					$new_slug = $establishment->unique_id;
					$i=0;
					while ( is_wp_error($post_id) ){
						$i++;
						$new_slug .= "-" . $i; 
						$new_post['post_name'] = sanitize_title($new_slug);
						$post_id = wp_insert_post($new_post);
					}
					add_post_meta($post_id,"turisbook_establishment_id",$establishment->id);
				}

				if($post_id > 0)
				{
					if($establishment->logo) $this->syncEstablishmentLogo($post_id, $establishment->logo);
					$this->updateEstablishmentMeta($post_id, $establishment);
				}

				if($id_hotel>0 && $id_hotel == $establishment->id){
					$this->syncRooms($establishment->id);
				}
				$this->syncRoomsInactiveOrDeleted($establishment->id);
			}
		}

		$toreturn['code'] = $code;
		$toreturn['url'] = $url;
		return $toreturn;
	}

	private function syncEstablishmentLogo($post_id, $img){
		require_once(ABSPATH . 'wp-admin/includes/image.php');
		require_once(ABSPATH . "wp-admin" . '/includes/media.php');
		global $user_ID;
		$upload_dir = wp_get_upload_dir()["basedir"];
		$keys = $this->getKeys();

		$local_img = $this->getPostByMeta(['meta_key' => 'turisbook_img_id', 'meta_value' => $img->id, "post_type"=>"attachment"]); 
		$attach_id = 0;
		if(!$local_img){
			$tmp_file = download_url( $img->meta->href, $timeout = 300);

			$folder_path = $upload_dir . '/turisbook/';

			if (!file_exists($folder_path)) {
				mkdir($folder_path, 0777, true);
			}
			$filepath = $folder_path.$img->filename;

			if(copy( $tmp_file, $filepath )){
				$filetype = wp_check_filetype( $img->filename, null );


				$file_array = array(
					'name' => basename( $filepath ),
					'type' => $filetype['type'],
					'tmp_name' => $filepath,
					'error' => 0,
					'size' => filesize( $filepath )
				);

				$attachment = array(
					'post_mime_type' => $filetype['type'],
					'post_title' => sanitize_file_name( $img->filename ),
					'post_content' => '',
					'post_status' => 'inherit'
				);

				$attach_id = wp_insert_attachment( $attachment, $filepath, $post_id);
				$attach_data = wp_generate_attachment_metadata( $attach_id, $filepath );

				wp_update_attachment_metadata( $attach_id, $attach_data );
				update_post_meta($attach_id,"turisbook_img_id",$img->id);

			}

			@unlink( $tmp_file );


		}else{
			$attach_id = $local_img->ID;
		}



		update_post_meta($post_id,"_thumbnail_id",$attach_id);
	}

	public function updateEstablishmentMeta($post_id, $establishment){
		update_post_meta($post_id,'turisbook_children_max_age', $establishment->meta->children_max_age);
		update_post_meta($post_id,'turisbook_payments', json_encode($establishment->payments));
		update_post_meta($post_id,'turisbook_color_background', $establishment->colors->color_background);
		update_post_meta($post_id,'turisbook_color_text', $establishment->colors->color_text);
		update_post_meta($post_id,'turisbook_color_title', $establishment->colors->color_title);
		update_post_meta($post_id,'turisbook_locations', json_encode($establishment->locations));
	}

	public function getAvailabilities($id_hotel,$room_id, $init, $end){
		$token = get_option( 'turisbook-access-token', "" );

		if($id_hotel == 0 || $token=='') return false;
		$url_arr = [];
		$url_arr[] = TURISBOOK_API_URL;
		$url_arr[] = $id_hotel;
		if($room_id>0){
			$url_arr[] = 'rooms';
			$url_arr[] = $room_id;
		}
		$url_arr[] = 'availabilities';
		$url_arr[] = $init;
		$url_arr[] = $end;

		$url = implode('/',$url_arr);
		$headr = array();
		$headr['Content-type'] = 'application/json';
		$headr['Accept'] = 'application/json';

		$headr['Authorization'] = 'Bearer ' . $token;

		$options = ['method' => 'GET', 'timeout' => 60, 'redirection' => 5, 'sslverify' => false, 'httpversion' => '1.0', 'blocking' => true, 'headers' => $headr, 'cookies' => array() ];

		$result = wp_remote_get($url,$options);
		$toreturn = [];
		$toreturn['availabilities'] = [];
		$toreturn['httpcode'] = wp_remote_retrieve_response_code($result);
		$toreturn['url'] = $url;

		if($toreturn['httpcode']==200){
			$json = json_decode(wp_remote_retrieve_body($result));
			$toreturn['availabilities'] = $json->data;
		}

		return $toreturn;
	}

	public function getRatecategories($room_id, $init, $end, $total_rooms, $total_adults){
		$token = get_option( 'turisbook-access-token', "" );
		$id_hotel = get_option('turisbook-hotel-id',0);

		if($id_hotel == 0 || $token=='') return false;


		$url = TURISBOOK_API_URL . "/" . $id_hotel . "/rooms/" . $room_id . "/prebook/ratecategories?checkin=" . $init . "&checkout=" . $end."&adults=".$total_adults."&rooms=".$total_rooms;
		$headr = array();
		$headr['Content-type'] = 'application/json';
		$headr['Accept'] = 'application/json';

		$headr['Authorization'] = 'Bearer ' . $token;

		$options = ['method' => 'GET', 'timeout' => 60, 'redirection' => 5, 'sslverify' => false, 'httpversion' => '1.0', 'blocking' => true, 'headers' => $headr, 'cookies' => array() ];

		$result = wp_remote_get($url,$options);
		$toreturn = [];
		$toreturn['ratecategories'] = [];
		$toreturn['url'] = $url;
		$toreturn['httpcode'] = wp_remote_retrieve_response_code($result);

		if($toreturn['httpcode']==200){
			$json = json_decode(wp_remote_retrieve_body($result));
			$toreturn['ratecategories'] = $json->data->ratecategories;
		}

		return $toreturn;
	}


	public function getRoomsAvailabilities($id_hotel, $checkin, $checkout, $total_adults, $total_rooms, $room=0){
		$token = get_option( 'turisbook-access-token', "" );
		// $id_hotel = get_option('turisbook-hotel-id',0);
		$language = Turisbook_Booking_System::getLanguage();

		if($id_hotel == 0 || $token=='') return false;

		$vars = [];
		$vars['checkin'] = $checkin;
		$vars['checkout'] = $checkout;
		$vars['total_adults_qtt'] = $total_adults;
		$vars['total_rooms_qtt'] = $total_rooms;
		$vars['language'] = $language;


		$url_arr = [];
		$url_arr[] = TURISBOOK_API_URL;
		$url_arr[] = $id_hotel;
		$url_arr[] = "rooms";
		if($room>0) $url_arr[] = $room;
		$url_arr[] = "availabilities";
		$url = implode('/',$url_arr);
		$sep = "?";
		foreach($vars as $key => $value){
			$url .= $sep . $key . "=" . $value;  
			$sep="&";
		}

		$headr = array();

		$headr['Content-type'] = 'application/json';
		$headr['Accept'] = 'application/json';

		$headr['Authorization'] = 'Bearer ' . $token;

		$options = ['method' => 'GET', 'timeout' => 60, 'redirection' => 5, 'sslverify' => false, 'httpversion' => '1.0', 'blocking' => true, 'headers' => $headr, 'cookies' => array() ];

		$result = wp_remote_get($url, $options);
		$toreturn = [];
		$toreturn['rooms'] = [];
		$toreturn['url'] = $url;
		$toreturn['httpcode'] = wp_remote_retrieve_response_code($result);


		if($toreturn['httpcode']==200){
			$json = json_decode(wp_remote_retrieve_body($result));
			$toreturn['rooms'] = $json->data->rooms;
			$toreturn['ndays'] = isset($json->data->ndays) ? isset($json->data->ndays) : 1;
		}

		return $toreturn;
	}


	public function getPrebook($id_hotel, $room_rates, $checkin, $checkout, $total_rooms, $total_adults,$cupon=""){
		$token = get_option( 'turisbook-access-token', "" );

		$id_hotel = $id_hotel==0 ? get_option('turisbook-hotel-id',0) : $id_hotel;

		$language = Turisbook_Booking_System::getLanguage();

		if($id_hotel == 0 || $token=='') return false;

		$vars = [];
		$vars['room_rates'] = json_encode($room_rates);
		$vars['checkin'] = $checkin;
		$vars['checkout'] = $checkout;
		$vars['total_adults_qtt'] = $total_adults;
		$vars['total_rooms_qtt'] = $total_rooms;
		$vars['cupon'] = $cupon;
		$vars['language'] = $language;

		$url = TURISBOOK_API_URL . "/" . $id_hotel . "/prebook";

		$sep = "?";
		foreach($vars as $key => $value){
			$url .= $sep . $key . "=" . $value;  
			$sep="&";
		}

		$headr = array();
		$headr['Content-type'] = 'application/json';
		$headr['Accept'] = 'application/json';

		$headr['Authorization'] = 'Bearer ' . $token;

		$options = ['method' => 'GET', 'timeout' => 60, 'redirection' => 5, 'sslverify' => false, 'httpversion' => '1.0', 'blocking' => true, 'headers' => $headr, 'cookies' => array() ];

		$result = wp_remote_get($url, $options);

		$toreturn = [];
		$toreturn['prebook'] = [];
		$toreturn['url'] = $url;
		$toreturn['httpcode'] = wp_remote_retrieve_response_code($result);

		if($toreturn['httpcode']==200){
			$json = json_decode(wp_remote_retrieve_body($result));
			$toreturn['prebook'] = $json->data;
		}

		return $toreturn;
	}

	public function book($id_hotel,$vars){
		$token = get_option( 'turisbook-access-token', "" );
		$id_hotel = $id_hotel == 0 ? get_option('turisbook-hotel-id',0) : $id_hotel;
		$language = Turisbook_Booking_System::getLanguage();

		if($id_hotel == 0 || $token=='') return false;

		$vars['language'] = $language;
		$vars['visitor_info'] = json_encode($this->getVisitorInfo());
		$url = TURISBOOK_API_URL . "/" . $id_hotel . "/book";
		$headr = array();
		$headr['Content-type'] = 'application/json;';
		$headr['Accept'] = 'application/json';

		$headr['Authorization'] = 'Bearer ' . $token;

		$options = ['method' => 'POST', 'timeout' => 60, 'redirection' => 5, 'sslverify' => false, 'httpversion' => '1.0', 'blocking' => true, 'headers' => $headr, 'cookies' => array(), 'body' => json_encode($vars) ];

		$result = wp_remote_post($url, $options);

		$toreturn = [];
		$toreturn['book'] = [];
		$toreturn['url'] = $url;
		$toreturn['httpcode'] = wp_remote_retrieve_response_code($result);

		if($toreturn['httpcode']==200){
			$json = json_decode(wp_remote_retrieve_body($result));
			$toreturn['status'] = $json->data->status;
			$toreturn['book_id'] = $json->data->book_id;

			$toreturn['data'] = $json->data;
		}else{
			$toreturn['data'] = wp_remote_retrieve_body($result);
		}


		return $toreturn;
	}

	public function sendCardex($vars){
		$token = get_option( 'turisbook-access-token', "" );
		$id_hotel = get_option('turisbook-hotel-id',0);
		$language = Turisbook_Booking_System::getLanguage();

		if($id_hotel == 0 || $token=='') return false;

		$url = TURISBOOK_API_URL . "/cardex/uid/" . $vars['uid'];
		$headr = array();
		$headr['Content-type'] = 'application/json;';
		$headr['Accept'] = 'application/json';

		$headr['Authorization'] = 'Bearer ' . $token;

		$options = ['method' => 'POST', 'timeout' => 60, 'redirection' => 5, 'sslverify' => false, 'httpversion' => '1.0', 'blocking' => true, 'headers' => $headr, 'cookies' => array(), 'body' => json_encode($vars) ];

		$result = wp_remote_post($url, $options);

		$toreturn = [];
		$toreturn['url'] = $url;
		$toreturn['httpcode'] = wp_remote_retrieve_response_code($result);

		if($toreturn['httpcode']==200){
			$json = json_decode(wp_remote_retrieve_body($result));
			$toreturn['status'] = $json->data->status;

			$toreturn['data'] = $json->data;
		}else{
			$toreturn['data'] = wp_remote_retrieve_body($result);
		}


		return $toreturn;
	}

	public function syncEstablishmentMeta(){
		$toreturn = [];
		$token = get_option('turisbook-access-token',"");
		$id_hotel = get_option('turisbook-hotel-id',0);
		if($id_hotel == 0 || $token=='') return false;
		$url = TURISBOOK_API_URL . "/".$id_hotel."/meta";
		$headr = array();
		$headr['Content-type'] = 'application/json';
		$headr['Accept'] = 'application/json';

		$headr['Authorization'] = 'Bearer ' . $token;

		$options = ['method' => 'GET', 'timeout' => 60, 'redirection' => 5, 'sslverify' => false, 'httpversion' => '1.0', 'blocking' => true, 'headers' => $headr, 'cookies' => array() ];


		$result = wp_remote_get($url,$options);
		$code = wp_remote_retrieve_response_code($result);
		if($code==200){
			$json = json_decode(wp_remote_retrieve_body($result));

			$data = $json->data;

			update_option( 'turisbook-children-max-age', $data->children_max_age, true);
		}
		return $toreturn;
	}

	public function syncPaymentTypes(){
		$toreturn = [];
		$token = get_option('turisbook-access-token',"");
		$id_hotel = get_option('turisbook-hotel-id',0);
		if($id_hotel == 0 || $token=='') return false;
		$token = get_option( 'turisbook-access-token', "" );
		$url = TURISBOOK_API_URL . "/".$id_hotel."/payments";
		$headr = array();
		$headr['Content-type'] = 'application/json';
		$headr['Accept'] = 'application/json';

		$headr['Authorization'] = 'Bearer ' . $token;

		$options = ['method' => 'GET', 'timeout' => 60, 'redirection' => 5, 'sslverify' => false, 'httpversion' => '1.0', 'blocking' => true, 'headers' => $headr, 'cookies' => array() ];


		$result = wp_remote_get($url,$options);
		$code = wp_remote_retrieve_response_code($result);
		if($code==200){
			$json = json_decode(wp_remote_retrieve_body($result));

			$payments = $json->data;

			$ids = [];

			foreach($payments as $p){
				$pay = [
					'id' => $p->id,
					'extra' => $p->extra
				];
				$ids[$p->id] = $pay;
			}

			update_option( 'turisbook-hotel-payments', json_encode($ids), true);
		}
		return $toreturn;
	}


	public function getBook($id_hotel,$id){
		$toreturn = [];
		$token = get_option('turisbook-access-token',"");
		$id_hotel = $id_hotel == 0 ? get_option('turisbook-hotel-id',0) : $id_hotel;
		if($id_hotel == 0 || $token=='') return false;
		$token = get_option( 'turisbook-access-token', "" );
		$url = TURISBOOK_API_URL . "/".$id_hotel."/bookings/".$id.'?expand=lines,promotion,translations';
		$headr = array();
		$headr['Content-type'] = 'application/json';
		$headr['Accept'] = 'application/json';

		$headr['Authorization'] = 'Bearer ' . $token;

		$options = ['method' => 'GET', 'timeout' => 60, 'redirection' => 5, 'sslverify' => false, 'httpversion' => '1.0', 'blocking' => true, 'headers' => $headr, 'cookies' => array() ];

		$result = wp_remote_get($url,$options);
		$code = wp_remote_retrieve_response_code($result);

		$toreturn['url'] = $url;
		$toreturn['code'] = $code;
		if($code==200){
			$json = json_decode(wp_remote_retrieve_body($result));

			$toreturn['book'] = $json->data;
		}
		return $toreturn;
	}
}
