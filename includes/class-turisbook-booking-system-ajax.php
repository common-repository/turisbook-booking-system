<?php
function Turisbook_bs_Establishments(){
	$tbs = new Turisbook_Booking_System();

	$all = defined('TURISBOOK_ADMIN_VERSION');

	$result = $tbs->getEstablishments($all);
	$toreturn = [];
	$toreturn['result'] = $result;

	$toreturn['httpcode'] = $result['httpcode'];
	if($result['httpcode']==200){

		$establishments = $result['establishments'];
		$toreturn['status'] = true;

		ob_start();
		include TURISBOOK_ADMIN_PATH.'/partials/turisbook-booking-system-admin-select-establishment.php';
		$buffer = ob_get_clean();
		$toreturn['data'] = $buffer;
	}else if($result['httpcode'] == 401){
		ob_start();
		include TURISBOOK_ADMIN_PATH.'/partials/turisbook-booking-system-admin-login.php';
		$buffer = ob_get_clean();
		$toreturn['data'] = $buffer;
	}

	return $toreturn;
}


function Turisbook_bs_savesettings_Ajax(){

	$prebook = isset($_POST['turisbook-page-prebook']) && is_numeric($_POST['turisbook-page-prebook']) ? (int)$_POST['turisbook-page-prebook'] : 0;
	$confirmation = isset($_POST['turisbook-page-confirmation']) && is_numeric($_POST['turisbook-page-confirmation']) ? (int)$_POST['turisbook-page-confirmation'] : 0;
	$availabilities = isset($_POST['turisbook-page-availability-search']) && is_numeric($_POST['turisbook-page-availability-search']) ? (int)$_POST['turisbook-page-availability-search'] : 0;
	$cart_position = isset($_POST['turisbook-cart-position']) && is_numeric($_POST['turisbook-cart-position']) ? $_POST['turisbook-cart-position'] : 0;
	$show_menu_apt = isset($_POST['turisbook-show-menu-apt']) && is_numeric($_POST['turisbook-show-menu-apt']) ? $_POST['turisbook-show-menu-apt'] : 0;
	$cartText = isset($_POST['turisbook-cart-background']) && $_POST['turisbook-cart-background'] ? $_POST['turisbook-cart-background'] : "";
	$turisbookAnalyticsBookingGoalEnabled = isset($_POST['turisbook-analytics-booking-goal-enabled']) && $_POST['turisbook-analytics-booking-goal-enabled'] ? $_POST['turisbook-analytics-booking-goal-enabled'] : "";

	$prebook_update = update_option( 'turisbook-page-prebook', $prebook, true);
	$confirmation_update = update_option( 'turisbook-page-confirmation', $confirmation, true);
	$availabilities_update = update_option( 'turisbook-page-availability-search', $availabilities, true);
	$cartPicker_update = update_option( 'turisbook-cart-position', $cart_position, true);
	$show_menu_apt_update = update_option( 'turisbook-show-menu-apt', $show_menu_apt, true);
	$cart_position_update = update_option( 'turisbook-cart-background', $cartText, true);
	update_option( 'turisbook-analytics-booking-goal-enabled', $turisbookAnalyticsBookingGoalEnabled, true);
	$toreturn = ["status" => true];

	echo json_encode($toreturn);
	wp_die();

}

Turisbook_Booking_System::registerAjax('Turisbook_bs_savesettings', 'Turisbook_bs_savesettings_Ajax');

function Turisbook_bs_login_Ajax(){

	$api_token = isset($_POST['token']) ? sanitize_text_field($_POST['token']) : "";

	$tbs = new Turisbook_Booking_System();
	$result = $tbs->login($api_token);
	$code = wp_remote_retrieve_response_code($result);

	// $result = TurisbookDumbLogin();

	$toreturn = ["token" => $api_token,'code' => $code];

	if($code == 200){
		$json = json_decode( wp_remote_retrieve_body($result));
		$token =  $json->access_token;
		$token_updated = update_option( 'turisbook-access-token', $token, true);


		$toreturn['token_updated'] = $token_updated;
		//$token = get_option('turisbook-access-token',"");

		$toreturn = Turisbook_bs_Establishments();
	}
	echo json_encode($toreturn);
	wp_die();
}

Turisbook_Booking_System::registerAjax('Turisbook_bs_login', 'Turisbook_bs_login_Ajax');


function Turisbook_bs_lockEstablishment_Ajax(){
	$tbs = new Turisbook_Booking_System();
	$toreturn = ["status" => true];
	$establishmentId = isset($_POST['id']) && is_numeric($_POST['id']) ? (int)$_POST['id'] : 0;
	$establishmentType = isset($_POST['type']) && is_numeric($_POST['type']) ? (int)$_POST['type'] : 1;
	$uniqueId = isset($_POST['unique_id']) ? sanitize_text_field($_POST['unique_id']) :"";
	$establishment = isset($_POST['name']) ? sanitize_text_field($_POST['name']) :"";

	update_option('turisbook-hotel-id',$establishmentId);
	update_option('turisbook-hotel-type',$establishmentType);
	update_option('turisbook-hotel-name',$establishment);
	update_option('turisbook-hotel-unique-id',$uniqueId);
	$tbs->syncEstablishmentMeta();
	ob_start();
	include TURISBOOK_ADMIN_PATH.'/partials/turisbook-booking-system-admin-loggedin.php';
	$buffer = ob_get_clean();
	$toreturn['data'] = $buffer;
	
	echo json_encode($toreturn);
	wp_die();
}

Turisbook_Booking_System::registerAjax('Turisbook_bs_lockEstablishment', 'Turisbook_bs_lockEstablishment_Ajax');

function Turisbook_bs_logout_Ajax(){
	$toreturn = ["status" => true];

	update_option('turisbook-hotel-id',0);
	update_option('turisbook-hotel-type',1);
	update_option('turisbook-hotel-name',"");
	update_option('turisbook-hotel-unique-id',"");
	update_option('turisbook-access-token',"");

	ob_start();
	include TURISBOOK_ADMIN_PATH.'partials/turisbook-login.php';
	$buffer = ob_get_clean();
	$toreturn['data'] = $buffer;
	
	echo json_encode($toreturn);
	wp_die();
}

Turisbook_Booking_System::registerAjax('Turisbook_bs_logout', 'Turisbook_bs_logout_Ajax');


function Turisbook_bs_syncdata_Ajax(){
	$toreturn = ["status" => true];

	$tbs = new Turisbook_Booking_System();

	$tbs->syncAmenities();
	$toreturn['result'] =  $tbs->syncEstablishments();
	// $tbs->syncRooms();

	echo json_encode($toreturn);
	wp_die();
}

Turisbook_Booking_System::registerAjax('Turisbook_bs_syncdata', 'Turisbook_bs_syncdata_Ajax');


function Turisbook_bs_Calendar_Ajax(){
	$tbs = new Turisbook_Booking_System();
	$months = $tbs->getMonths();
	// Actual Month
	$actual_year = isset($_POST['year']) && (int)$_POST['year'] >= date('Y') ? (int)$_POST['year'] : date("Y");
	$actual_month = isset($_POST['month']) && (int)$_POST['month'] > 0 && (int)$_POST['month'] < 13 ? (int)$_POST['month'] : date("m");
	$unit_id = isset($_POST['unit_id']) ? (int)$_POST['unit_id'] : 0;
	$hotel_id = isset($_POST['hotel_id']) ? (int)$_POST['hotel_id'] : 0;
	$default_language_slug = '';
	$room_id=0;

	if($unit_id>0){
		if($tbs->PolylangIsActive()){
			$default_language_slug = pll_default_language('slug');
			$unit_id = pll_get_post($unit_id, $default_language_slug);
		}
		$room_id = get_post_meta($unit_id,'turisbook_room_id',true);
	}
	
	$today_year = date('Y'); 
	$today_month = date('m'); 
	if($actual_month!=$today_month || ($actual_month==$today_month && $actual_year!=$today_year)){
		$actual_month = $actual_month - 1 == 0 ? 12 : $actual_month - 1;
		$actual_year = $actual_month == 12 ?   $actual_year - 1 : $actual_year;
	}

	$actual_month_name = $months[$actual_month];
	$actual_month_days = cal_days_in_month(CAL_GREGORIAN, $actual_month, $actual_year);


	$toreturn = ["status" => false,'html' => ""];

	// Actual month end
	// Actual Month 2

	$actual_month_2 = $actual_month + 1 == 13 ? 1 : $actual_month + 1;
	$actual_year_2 = $actual_month_2 == 1 ?   $actual_year + 1 : $actual_year;

	$actual_month_2_name = $months[$actual_month_2];
	$actual_month_2_days = cal_days_in_month(CAL_GREGORIAN, $actual_month_2, $actual_year_2);
	
	// Actual month 2 end
	// Previous Month


	$prev_month = $actual_month - 1 == 0 ? 12 : $actual_month - 1;
	$prev_year = $prev_month == 12 ?  $actual_year - 1 : $actual_year;

	$prev_month_name = $months[$prev_month];
	$prev_month_days = cal_days_in_month(CAL_GREGORIAN, $prev_month, $prev_year);

	// Previous month end
	// Next Month

	$next_month = $actual_month_2 + 1 == 13 ? 1 : $actual_month_2 + 1;
	$next_year = $next_month == 1 ?   $actual_year_2 + 1 : $actual_year_2;


	$next_month_name = $months[$next_month];
	$next_month_days = cal_days_in_month(CAL_GREGORIAN, $next_month, $next_year);

	// Next month end

	$weekday = date('w', mktime(0, 0, 0, $actual_month, 1, $actual_year));
	$weekday_2 = date('w', mktime(0, 0, 0, $actual_month_2, 1, $actual_year_2));

	$today_timestamp = strtotime(date('Y-m-d')); 

	$today_day = date('d');

	$day_init = $actual_month == $today_month ? $today_day : 1;

	$init = $actual_year . '-' . str_pad($actual_month, 2, '0', STR_PAD_LEFT) . '-' . str_pad($day_init, 2, '0', STR_PAD_LEFT);
	$end = $actual_year_2 . '-' . str_pad($actual_month_2, 2, '0', STR_PAD_LEFT) . '-'. str_pad($actual_month_2_days, 2, '0', STR_PAD_LEFT);

	$result = $tbs->getAvailabilities($hotel_id, $room_id, $init, $end);
	$availabilities = (array)($result['availabilities']);
	$a_keys = array_keys($availabilities);
	$post_type_link = get_post_type_archive_link( 'turisbook_room' ); 

	ob_start();

	include TURISBOOK_PATH . '/public/partials/turisbook-calendar.php';
	$toreturn['html'] = ob_get_clean();



	$toreturn["availabilities"]=$result;
	$toreturn["status"]=true;
	// $toreturn["status"]=true;
	$toreturn["unit_id"]=$unit_id;
	$toreturn["hotel_id"]=$hotel_id;
	echo json_encode($toreturn);
	wp_die();
}

Turisbook_Booking_System::registerAjax('Turisbook_bs_Calendar', 'Turisbook_bs_Calendar_Ajax');



function Turisbook_bs_getRatecategories_Ajax(){
	$toreturn = ["status" => true];

	$tbs = new Turisbook_Booking_System();
	// $id_hotel = get_option('turisbook-hotel-id',0);



	$unit_id = isset($_POST['unit_id']) && is_numeric($_POST['unit_id']) ? (int)$_POST['unit_id'] : 0;
	$total_rooms = isset($_POST['rooms']) && is_numeric($_POST['rooms']) ? (int)$_POST['rooms'] : 1;
	$total_adults = isset($_POST['adults']) && is_numeric($_POST['adults']) ? (int)$_POST['adults'] : 1;
	$total_children = isset($_POST['children']) && is_numeric($_POST['children']) ? (int)$_POST['children'] : 0;

	$post_checkin = isset($_POST['checkin']) ? sanitize_text_field($_POST['checkin']) : "";
	$post_checkout = isset($_POST['checkout']) ? sanitize_text_field($_POST['checkout']) : "";
	$checkin_checkout = $tbs->validateCheckinCheckout($post_checkin, $post_checkout,'d-m-Y');
	$checkin = $checkin_checkout['checkin'];
	$checkout = $checkin_checkout['checkout'];

	$default_language_slug = '';

	if($tbs->PolylangIsActive()){
		$default_language_slug = pll_default_language('slug');
		$unit_id = pll_get_post($unit_id, $default_language_slug);
	}


	$room_id = get_post_meta($unit_id,'turisbook_room_id',true);
	$hotel_id = get_post_meta($unit_id,'turisbook_room_establishment', true);
	$room = get_post($unit_id);
	$result = $tbs->getRoomsAvailabilities($hotel_id, $checkin, $checkout, $total_rooms, $total_adults,$room_id);
	$rooms = $result['rooms'];
	$room = $rooms[0];
	$ratecategories = $room->active_ratecategories;

	$page_prebook_id = get_option('turisbook-page-prebook',0);
	$page_prebook = get_permalink($page_prebook_id);

	$post_title = get_the_title($unit_id);
	$post_link = get_permalink($unit_id);

	ob_start();
	include TURISBOOK_PATH . '/public/partials/turisbook-booking-system-ratecategories.php';
	$toreturn['html'] = ob_get_clean();


	$toreturn["resul2"]=$result;
	$toreturn["result"]=$result['url'];
	$toreturn["status"]=true;
	$toreturn["hotel_id"]=$hotel_id;
	echo json_encode($toreturn);
	wp_die();


}

Turisbook_Booking_System::registerAjax('Turisbook_bs_getRatecategories', 'Turisbook_bs_getRatecategories_Ajax');

function Turisbook_bs_preBook_Ajax(){

	$toreturn = ["status" => true];

	$prebook = [];
	
	$tbs = new Turisbook_Booking_System();

	$establishment_id = 0;
	$default_language_slug="";
	$unit_rates = isset($_POST['unit_rates']) ? json_decode(str_replace("\\",'',$_POST['unit_rates'])) : [];
	$prebook['room_rates'] = [];	
	foreach($unit_rates as $unit){
		$unit_id = $unit->room;

		if($tbs->PolylangIsActive()){
			$default_language_slug = pll_default_language('slug');
			$unit_id = pll_get_post($unit_id, $default_language_slug);
		}

		$room_id = get_post_meta($unit_id,'turisbook_room_id',true);
		$temp = [
			'room' => $room_id,
			'lines' => $unit->lines
		];

		if($establishment_id == 0 ){
			$establishment_id = get_post_meta($unit_id,'turisbook_room_establishment', true);
		}

		$prebook['room_rates'][] = $temp;
	}
	

	$post_checkin = isset($_POST['checkin']) ? sanitize_text_field($_POST['checkin']) : "";
	$post_checkout = isset($_POST['checkout']) ? sanitize_text_field($_POST['checkout']) : "";
	$checkin_checkout = $tbs->validateCheckinCheckout($post_checkin, $post_checkout,'d-m-Y');
	$prebook['checkin'] = $checkin_checkout['checkin'];
	$prebook['checkout'] = $checkin_checkout['checkout'];
	$prebook['hotel'] = $establishment_id;

	setcookie( 'tbs-prebook-cookie', json_encode($prebook),  ['expires' => time() + 3600 ,'path' => '/','secure' => true,'httponly' => true,'samesite' => 'Lax' ] );

	$toreturn["status"]=true;
	$toreturn["prebook"]=$prebook;
	echo json_encode($toreturn);

	wp_die();
}

Turisbook_Booking_System::registerAjax('Turisbook_bs_preBook', 'Turisbook_bs_preBook_Ajax');


function Turisbook_bs_sendcardex_Ajax(){
	$toreturn = ["status" => true];

	$tbs = new Turisbook_Booking_System();

	$birthday_array = [];

	$birthday_array['birthday_day'] = isset($_POST['birthday_day']) ? (int)$_POST['birthday_day'] : "";
	$birthday_array['birthday_month'] = isset($_POST['birthday_month']) ? (int)$_POST['birthday_month'] : "";
	$birthday_array['birthday_year'] = isset($_POST['birthday_year']) ? (int)$_POST['birthday_year'] : "";

	$birthday = implode('-',$birthday_array);

	$vars = [];
	$c_prebook =  $tbs->getPrebookCookie();

	$vars['room_rates'] = json_encode($c_prebook->room_rates);


	$vars['uid'] = isset($_POST['uid']) ? sanitize_text_field($_POST['uid']) : "";
	$vars['full_name'] = isset($_POST['full_name']) ? sanitize_text_field($_POST['full_name']) : "";
	$vars['birthday'] = $birthday;
	$vars['place_birthday'] = isset($_POST['place_birthday']) ? sanitize_text_field($_POST['place_birthday']) : "";
	$vars['nationality'] = isset($_POST['nationality']) ? sanitize_text_field($_POST['nationality']) : "";
	$vars['place_residence'] = isset($_POST['place_residence']) ? sanitize_text_field($_POST['place_residence']) : "";
	$vars['country_residence'] = isset($_POST['country_residence']) ? sanitize_text_field($_POST['country_residence']) : "";
	$vars['nif'] = isset($_POST['nif']) ? sanitize_text_field($_POST['nif']) : "";
	$vars['document_type'] = isset($_POST['document_type']) ? sanitize_text_field($_POST['document_type']) : "";
	$vars['document_number'] = isset($_POST['document_number']) ? sanitize_text_field($_POST['document_number']) : "";
	$vars['document_country'] = isset($_POST['document_country']) ? sanitize_text_field($_POST['document_country']) : "";
	$vars['card_holder'] = isset($_POST['cc_name']) ? sanitize_text_field($_POST['cc_name']) : "";
	$vars['card_number'] = isset($_POST['cc_number']) ? sanitize_text_field($_POST['cc_number']) : "";
	$vars['card_validity_month'] = isset($_POST['cc_month']) ? sanitize_text_field($_POST['cc_month']) : "";
	$vars['card_validity_year'] = isset($_POST['cc_year']) ? sanitize_text_field($_POST['cc_year']) : "";
	$vars['card_cvv'] = isset($_POST['cc_cvv']) ? sanitize_text_field($_POST['cc_cvv']) : "";

	$result = $tbs->sendCardex($vars);

	$toreturn["status"]=true;
	$toreturn["result"]=$result;

	echo json_encode($toreturn);

	wp_die();
}

Turisbook_Booking_System::registerAjax('Turisbook_bs_sendcardex', 'Turisbook_bs_sendcardex_Ajax');

function Turisbook_bs_book_Ajax(){
	$toreturn = ["status" => true];

	$tbs = new Turisbook_Booking_System();

	$pc1 = isset($_POST['pc1']) ? (int)$_POST['pc1'] : "";
	$pc2 = isset($_POST['pc2']) ? (int)$_POST['pc2'] : "";


	$vars = [];
	$c_prebook =  $tbs->getPrebookCookie();

	$vars['room_rates'] = json_encode($c_prebook->room_rates);
	$vars['hotel'] = json_encode($c_prebook->hotel);


	$vars['first_name'] = isset($_POST['first_name']) ? sanitize_text_field($_POST['first_name']) : "";
	$vars['last_name'] = isset($_POST['last_name']) ? sanitize_text_field($_POST['last_name']) : "";
	$vars['email'] = isset($_POST['email']) ? sanitize_text_field($_POST['email']) : "";
	$vars['phone'] = isset($_POST['phone']) ? sanitize_text_field($_POST['phone']) : "";
	$vars['address'] = isset($_POST['address']) ? sanitize_text_field($_POST['address']) : "";
	$vars['postal_code'] = $pc1."-".$pc2;
	$vars['city'] = isset($_POST['city']) ? sanitize_text_field($_POST['city']) : "";
	$vars['pais'] = isset($_POST['country']) ? sanitize_text_field($_POST['country']) : "";

	$vars['titular'] = isset($_POST['cc_name']) ? sanitize_text_field($_POST['cc_name']) : "";
	$vars['numero'] = isset($_POST['cc_number']) ? sanitize_text_field($_POST['cc_number']) : "";
	$vars['mescartao'] = isset($_POST['cc_month']) ? sanitize_text_field($_POST['cc_month']) : "";
	$vars['anocartao'] = isset($_POST['cc_year']) ? sanitize_text_field($_POST['cc_year']) : "";
	$vars['cvv'] = isset($_POST['cc_cvv']) ? sanitize_text_field($_POST['cc_cvv']) : "";
	$vars['payment'] = isset($_POST['payment']) && is_numeric($_POST['payment']) ? (int)$_POST['payment'] : 1;
	$vars['accept'] = isset($_POST['accept']) && $_POST['payment'] == 1 ? 1 : false;


	$cookie_checkin = isset($c_prebook->checkin) ? sanitize_text_field($c_prebook->checkin) : "";
	$cookie_checkout = isset($c_prebook->checkout) ? sanitize_text_field($c_prebook->checkout) : "";
	$checkin_checkout = $tbs->validateCheckinCheckout($cookie_checkin, $cookie_checkout,'d-m-Y');

	$vars['checkin'] = $checkin_checkout['checkin'];
	$vars['checkout'] = $checkin_checkout['checkout'];

	$vars['cupon'] = isset($c_prebook->cupon_id) && is_numeric($c_prebook->cupon_id) ? (int)$c_prebook->cupon_id : 0;
	$toreturn["vars"]=$vars;

	$result = $tbs->book($c_prebook->hotel, $vars);



	setcookie( 'tbs-prebook-cookie', '', ['expires' => time() - 3600 ,'path' => '/','secure' => true,'httponly' => true,'samesite' => 'Lax' ] );
	setcookie( 'tbs-book_id-cookie', $result['book_id'], ['expires' => time() + 3600 ,'path' => '/','secure' => true,'httponly' => true,'samesite' => 'Lax' ]);
	setcookie( 'tbs-book_hotel_id-cookie', $c_prebook->hotel, ['expires' => time() + 3600 ,'path' => '/','secure' => true,'httponly' => true,'samesite' => 'Lax' ]);

	$toreturn["result"]=$result;
	$toreturn["status"]=true;
	$toreturn["book_id"]=$result['book_id'];
	// $toreturn["c_prebook"]=$c_prebook;
	// $toreturn["checkin_checkout"]=$checkin_checkout;
	echo json_encode($toreturn);

	wp_die();
}

Turisbook_Booking_System::registerAjax('Turisbook_bs_book', 'Turisbook_bs_book_Ajax');


function Turisbook_bs_apply_cupon_Ajax(){
	$tbs = new Turisbook_Booking_System();
	$c_prebook =  $tbs->getPrebookCookie();
	$room_rates = $c_prebook->room_rates;
	$checkin = $c_prebook->checkin;
	$checkout = $c_prebook->checkout;
	$total_rooms = $c_prebook->rooms;
	$total_adults = $c_prebook->adults;
	$total_children = $c_prebook->children;
	$hotel = $c_prebook->hotel;

	$cupon = isset($_POST['cupon']) ? sanitize_text_field($_POST['cupon']) : '';


	$result = $tbs->getPrebook($hotel, $room_rates, $checkin, $checkout, $total_rooms, $total_adults, $cupon);
	$prebook = $result['prebook'];

	$toreturn = [];
	$toreturn['total'] = $prebook->price_with_discounts_and_taxes;
	$toreturn['prepay'] = $prebook->prepay;
	$toreturn['cupon_id'] = $prebook->cupon_id;
	$toreturn['msg'] = "";
	$toreturn['status'] = true;
	if($prebook->cupon_id > 0){
// var_dump($prebook);
		$c_prebook->cupon = $cupon;
		setcookie( 'tbs-prebook-cookie', json_encode($c_prebook),  ['expires' => time() + 3600 ,'path' => '/','secure' => true,'httponly' => true,'samesite' => 'Lax' ] );
		ob_start();
		include TURISBOOK_PATH . '/public/partials/turisbook-booking-system-resume-body-price.php';
		$toreturn['html'] = ob_get_clean();
		// $toreturn['c_prebook'] = $c_prebook;

	}else{
		$toreturn['msg'] = esc_attr( 'Invalid Promotional Code', 'turisbook-booking-system' );
		$toreturn['status'] = false;
	}


	// $toreturn['prebook'] = $prebook;
	// $toreturn['result'] = $result;

	echo json_encode($toreturn);

	wp_die();
}
Turisbook_Booking_System::registerAjax('Turisbook_bs_apply_cupon', 'Turisbook_bs_apply_cupon_Ajax');


function Turisbook_bs_update_line_Ajax(){
	$toreturn = [];
	$toreturn['msg'] = "";
	$toreturn['status'] = false;
	$options = isset($_POST['options']) ? json_decode(str_replace("\\",'',$_POST['options'])) : false;
	$line_id = isset($_POST['line_id']) ? (int)$_POST['line_id'] : false;
	$room_id = isset($_POST['room_id']) ? (int)$_POST['room_id'] : false;
	$toreturn['options'] = $options;

	if($line_id && $options && $room_id){
		$tbs = new Turisbook_Booking_System();
		$c_prebook =  $tbs->getPrebookCookie();
		$hotel = $c_prebook->hotel;
		$temp_prebook = new stdClass();

		$room_rates = [];

		

		foreach($c_prebook->room_rates as $rt){
			if((int)$rt->room == (int)$room_id){
				$temp_lines = [];
				foreach($rt->lines as $line){
					if((int)$line->line_id == (int)$line_id){
						$temp_options = [];
						$count = 0;
						foreach($line->options as $op){
							if((int)$op->row_count == (int)$options->row_count){
								foreach($options as $key=>$value){
									$op->$key = $value;
								}
								$count++;
							}
							$temp_options[] = $op;
						}

						if($count==0) $temp_options[] = $options;
						$line->options = $temp_options;
					}
					$temp_lines[] = $line;
				}
				$rt->lines = $temp_lines;
			}
			$room_rates[] = $rt;
		}

		$c_prebook->room_rates = $room_rates;

		setcookie( 'tbs-prebook-cookie', json_encode($c_prebook),  ['expires' => time() + 3600 ,'path' => '/','secure' => true,'httponly' => true,'samesite' => 'Lax' ] );
		
		$result = $tbs->getPrebook($hotel,$room_rates, $c_prebook->checkin, $c_prebook->checkout, $total_rooms, $total_adults, $c_prebook->cupon);
		$prebook =$result['prebook'];

		ob_start();
		include TURISBOOK_PATH . '/public/partials/turisbook-booking-system-resume-body-price.php';
		$toreturn['html'] = ob_get_clean();
		$toreturn['status'] = true;
		$toreturn['url'] = $result['url'];
		$toreturn['prebook'] = $prebook;
		$toreturn['c_prebook'] = $c_prebook;
	}else{
		$toreturn['nope'] = 'there you go!';		
	}

	echo json_encode($toreturn);

	wp_die();

}
Turisbook_Booking_System::registerAjax('Turisbook_bs_update_line', 'Turisbook_bs_update_line_Ajax');


function Turisbook_bs_Load_More_Ajax(){
	$page = isset($_POST['page']) ? (int)$_POST['page'] : 0;

	$tbs = new Turisbook_Booking_System();
	$toreturn = [];
	$toreturn['msg'] = "";
	$toreturn['status'] = false;
	$toreturn['html'] = '<div>Page '.$page.'</div>';

	$args = array(
		'numberposts'      => 6,
		'paged' 		   => $page,
		'orderby'          => 'menu_order',
		'order'            => 'ASC',
		'post_type'        => 'turisbook_room',
		'post_status' 	   => 'publish'
	);

	$posts = get_posts($args);

	ob_start();
	foreach($posts as $post){
		$unit_id = $post->ID;
		if($tbs->PolylangIsActive() && Turisbook_Booking_System::getLanguage() != Turisbook_Booking_System::calcLanguage(pll_get_post_language($unit_id))) continue;
		$post_link = get_permalink($unit_id);
		$slider_string = get_post_meta($unit_id,'turisbook_room_gallery',true);
		$slider_ids = explode(',', $slider_string);
		$lotation_base = (int)get_turisbook_room_metabox('turisbook_room_lotation_base',$unit_id);
		$extrabed_max = (int)get_turisbook_room_metabox('turisbook_room_extrabed_max',$unit_id);
		$turisbook_room_location_name = get_turisbook_room_metabox('turisbook_room_location_name',$unit_id);
		

		$sleeps = (int)get_turisbook_room_metabox('turisbook_room_sleeps',$unit_id);
		include(TURISBOOK_PUBLIC_PATH . '/partials/archive-turisbook_room_single.php');

	}
	$toreturn['posts_count'] = count($posts);
	$toreturn['html'] = ob_get_clean();



	echo json_encode($toreturn);
	wp_die();

}

Turisbook_Booking_System::registerAjax('Turisbook_bs_Load_More', 'Turisbook_bs_Load_More_Ajax');
