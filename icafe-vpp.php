<?PHP
/*
PLUGIN META INFO FOR WORDPRESS LISTINGS
Plugin Name: iCafe Apple VPP Manager
Description: Wordpress plugin to assist in managing Apple's Volume Purchase Program
Version: 1.1
Author: Chris Nilsson
*/

register_activation_hook( __FILE__, 'icafe_vpp_activate' );
register_deactivation_hook( __FILE__, 'icafe_vpp_deactivate' );

add_action('plugins_loaded', 'icafe_vpp_update');
add_action('admin_menu', 'icafe_vpp_admin_menu');
add_action( 'admin_enqueue_scripts', 'icafe_vpp_admin_styles_scripts' );
add_action( 'wp_enqueue_scripts', 'icafe_vpp_styles_scripts' ); 
add_action( 'edit_user_profile', 'icafe_vpp_add_custom_user_profile_fields' );
add_action( 'edit_user_profile_update', 'icafe_vpp_save_custom_user_profile_fields' );
add_action( 'after_setup_theme', 'icafe_vpp_code_download');
add_action('template_redirect','icafe_vpp_restricted');
add_shortcode( 'iCafe_VPP', 'create_VPP_request_page' );
add_shortcode( 'icafe_VPP', 'create_VPP_request_page' );
add_shortcode( 'icafe_vpp', 'create_VPP_request_page' );



//Activate the plugin
function icafe_vpp_activate() {
	
  update_option("icafe_vpp_version", "1.1");

  $current_user = wp_get_current_user();
	
  add_option("icafe_vpp_program_manager", $current_user->ID);
  add_option("icafe_vpp_program_manager_itunes_account", "");
  add_option("icafe_vpp_program_manager_itunes_pw", "");
  add_option("icafe_vpp_use_single_facilitator_pw", "");
  add_option("icafe_vpp_facilitator_pw", "");
  add_option("icafe_vpp_facilitator_ids", "");
  add_option("icafe_vpp_plugin_url", "");
  add_option("icafe_vpp_global_itunes", "");
  add_option("icafe_vpp_current_appleid_pw", "");
  add_option('icafe_vpp_program_descriptions', "");
  add_option('icafe_vpp_program_description_lable', "");
  add_option('global_appleid_sec1', "");
  add_option('global_appleid_sec2', "");
  add_option('global_appleid_sec3', "");
  add_option('global_appleid_sec1_answer', "");
  add_option('global_appleid_sec2_answer', "");
  add_option('global_appleid_sec3_answer', "");
  add_option('icafe_vpp_new_pf_email', "");
  add_option('icafe_vpp_new_pf_universal_pw', "");
  add_option('icafe_vpp_new_pf_month', "");
  add_option('icafe_vpp_new_pf_day', "");
  add_option('icafe_vpp_new_pf_year', "");
  add_option('icafe_vpp_new_pf_sec_q', "");
  add_option('icafe_vpp_new_pf_sec_a', "");
  add_option('global_appleid_change_interval', "");
  

  global $wpdb;
  
 //create apps table
  $table_name = $wpdb->prefix . "icafe_vpp_apps";
  if($wpdb->get_var("show tables like '$table_name'") != $table_name) {
      $sql = "CREATE TABLE " . $table_name . " (
			  `aid` int(11) NOT NULL AUTO_INCREMENT,
			  `pid` int(11) DEFAULT NULL,
			  `app` text COLLATE utf8_unicode_ci,
			  `publisher` text COLLATE utf8_unicode_ci,
			  `quantity` int(11) DEFAULT NULL,
			  `for_user` text COLLATE utf8_unicode_ci,
			  `cost` decimal(11,2) DEFAULT NULL,
			  `url` text COLLATE utf8_unicode_ci,
			  `codes` longblob,
			  `size` int(11) DEFAULT NULL,
			  `type` text COLLATE utf8_unicode_ci,
			  `name` text COLLATE utf8_unicode_ci,
			  `app_status` text COLLATE utf8_unicode_ci,
			  PRIMARY KEY (`aid`)
			) ENGINE=MyISAM AUTO_INCREMENT=166 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";
		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		dbDelta($sql);
  }
//create programs table
  $table_name = $wpdb->prefix . "icafe_vpp_programs";
  if($wpdb->get_var("show tables like '$table_name'") != $table_name) {
      $sql = "CREATE TABLE " . $table_name . " (
			  `pid` int(11) NOT NULL AUTO_INCREMENT,
			  `program_name` text COLLATE utf8_unicode_ci,
			  `program_description` text COLLATE utf8_unicode_ci,
			  `owner` int(11) DEFAULT NULL,
			  `facilitator` int(11) DEFAULT NULL,
			  `apple_id` text COLLATE utf8_unicode_ci,
			  `password` text COLLATE utf8_unicode_ci,
			  `balance` decimal(11,2) DEFAULT NULL,
			  `status` text COLLATE utf8_unicode_ci,
			  PRIMARY KEY (`pid`)
			) ENGINE=MyISAM AUTO_INCREMENT=104 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";
		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		dbDelta($sql);
  }
//create vouchers table		
  $table_name = $wpdb->prefix . "icafe_vpp_vouchers";
  if($wpdb->get_var("show tables like '$table_name'") != $table_name) {
      $sql = "CREATE TABLE " . $table_name . " (
			  `vid` int(11) NOT NULL AUTO_INCREMENT,
			  `voucher` text COLLATE utf8_unicode_ci,
			  `pid` int(11) DEFAULT NULL,
			  `amount` int(11) DEFAULT NULL,
			  `voucher_status` text COLLATE utf8_unicode_ci,
			  PRIMARY KEY (`vid`)
			) ENGINE=MyISAM AUTO_INCREMENT=175 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";
		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		dbDelta($sql);
	}
	
	
}


//deactivate the plugin
function icafe_vpp_deactivate() {
  
}

//check Database Structure
function icafe_vpp_update() {
	global $wpdb;
	
	$new_version = '1.1';
	$current_version = get_option('icafe_vpp_version');
	
	//Check if upgrade is needed
	if ($new_version != $current_version) {
		
		
		update_option("icafe_vpp_version", $new_version);
	}
	
}

//include css and scripts
function icafe_vpp_admin_styles_scripts() {
	wp_register_script('icafe_vpp_admin_js', plugins_url( 'js/icafe-vpp-admin.js', __FILE__ ), array(), '', true );  	
	wp_enqueue_script('icafe_vpp_admin_js');  
}

//include css and scripts
function icafe_vpp_styles_scripts() {
	wp_register_style( 'icafe_vpp_style', plugins_url( 'css/icafe-vpp.css', __FILE__ ), array(), '', 'all' ); 
	wp_enqueue_style( 'icafe_vpp_style' ); 
	wp_enqueue_script('jquery');
   	wp_enqueue_script('jquery-form'); 
	wp_register_script('icafe_vpp_js_validate', plugins_url( 'js/jquery.validate.min.js', __FILE__ ), array('jquery'), '', true );  
	wp_register_script('icafe_vpp_js', plugins_url( 'js/icafe-vpp.js', __FILE__ ), array(), '', true );  
	wp_enqueue_script('icafe_vpp_js_validate'); 
	wp_enqueue_script('icafe_vpp_js');  
}


//build the admin menu locations
function icafe_vpp_admin_menu() {
	//$icon = plugin_dir_url(__FILE__) . 'project-icon.png';
	add_menu_page('iCafe VPP Manager', 'iCafe VPP', 'edit_posts', 'icafe_vpp-admin', 'icafe_vpp_admin');
	add_submenu_page('icafe_vpp-admin', 'Program Manager', 'Program Manager', 'edit_posts', 'icafe_vpp-Program-Manager-Admin', 'icafe_vpp_program_manager_admin');
	add_submenu_page('icafe_vpp-admin', 'Program Admins', 'Program Admins', 'edit_posts', 'icafe_vpp-Program-Facilitator-Admin', 'icafe_vpp_program_facilitator_admin');
	add_submenu_page('icafe_vpp-admin', 'Program Facilitators', 'Program Facilitators', 'edit_posts', 'icafe_vpp-Manage-VPP-Accounts', 'icafe_vpp_manage_vpp_accounts');
	add_submenu_page('icafe_vpp-admin', 'Enterprise Owned iTunes Account', 'Enterprise Owned iTunes Account', 'edit_posts', 'icafe_vpp-Manage-Global_iTunes-Accounts', 'icafe_vpp_manage_globle_itunes_accounts');
	add_submenu_page('icafe_vpp-admin', 'Self-Serve Config', 'Self-Serve Config', 'edit_posts', 'icafe_vpp-Config', 'icafe_vpp_config');
	add_submenu_page('icafe_vpp-admin', 'New Program Config', 'New Program Config', 'edit_posts', 'icafe_vpp_new_program-Config', 'icafe_vpp_new_program_config');
	//add_submenu_page('my_projects-admin', 'General Settings', 'Settings', 'manage_options', 'myprojects-settings', 'myprojects_settings');
}


//build the actual page for the VPP tool
function create_VPP_request_page() {
	
	ob_start(); // begin output buffering
	
	add_filter( 'edit_post_link', '__return_false' );
	//Who is logged in?
	global $user_identity;
	global $user_ID;
	//DB funtions
	global $wpdb;
	
		
	//set a mode to display
	if (!isset($_GET['wpVPP'])) {
		$mode = 'welcome_screen';
		//Get the URL for the VPP page and store it (update each time this page loades in case it moves
		wp_title();
		$uri = explode('?',$_SERVER["REQUEST_URI"]);
		$base_url = (!empty($_SERVER['HTTPS'])) ? "https://".$_SERVER['SERVER_NAME'].$uri[0] : "http://".$_SERVER['SERVER_NAME'].$uri[0];
		update_option('icafe_vpp_plugin_url', $base_url);
	} else {
		$mode = $_GET['wpVPP'];
		$base_url = get_option('icafe_vpp_plugin_url');
	}
	
	$vpp_output = '<div id="icafe-vpp">';
	
	//What mode are we in...controls what we display/process
	switch ($mode) {
	
	//Start Screen	
	case 'welcome_screen':

	//Get the URL for the VPP page and store it (update each time this page loades in case it moves
	wp_title();
	
	update_option('icafe_vpp_plugin_url', $base_url);
	//chris make link dynamic
	$vpp_output .= '</br></br>
							<div id="stylized" class="myform">
								<h1>Step 1</h1>
								<strong>Order an Apple VPP Voucher</strong>
								</br></br>
									$100 - MC758LL/A
								</br>
									$500 - MC759LL/A
							</div>
							
						</br></br>
							<div id="stylized" class="myform">
								<h1>Step 2</h1>
								<strong><a href="'.$base_url.'?wpVPP=voucher_request">Redeem your Voucher</a></strong>
								</br></br>
									Once your voucher arrives, click the link above redeem your voucher. Once your voucher has been processed, you will receive an email confirming that your account is ready to purchase Apps.
							</div>
							
							</br></br>
							<div id="stylized" class="myform">
								<h1>Step 3</h1>
								<strong><a href="'.$base_url.'?wpVPP=app_request">Request App Purchases</a></strong>
								</br></br>
									Once your voucher has been processed, you may request app purchases at any time as long as you have funds remaining. You may always purchase additional Vouchers to add money to your program. Once your app request has been processed, you will receive an email with codes to install the apps.
							</div>
							
							</br></br>
			';
			
		$global_appleid = stripslashes(get_option('icafe_vpp_global_itunes'));
		
		if ($global_appleid == '') {
			
			$vpp_output .= '
							<div id="stylized" class="myform">
								<h1>Step 4</h1>
								<strong><a href="'.$base_url.'?wpVPP=lookup_pw">Redeem Codes</a></strong>
								</br></br>
									Once you have your app codes, you may redeem them using your personal iTunes account Click "Redeem Codes" above for instructions </br>
							</div>
			
			';
			
			
		} else {
			
							
			$vpp_output .= '
							<div id="stylized" class="myform">
								<h1>Step 4</h1>
								<strong><a href="'.$base_url.'?wpVPP=lookup_pw">Redeem Codes</a></strong>
								</br></br>
									Once you have your app codes, you must install them using the enterprise owned iTunes account. This account changes passwords every '.get_option('global_appleid_change_interval').' hours. Click "Redeem Codes" above to lookup the current credentials. </br></br><strong><div style="color:red; text-align:center">ENTERPRISE PURCHASED APPS MUST BE INSTALLED USING THE ENTERPRISE ITUNES ACCOUNT.</div></strong>
							</div>
			
			';

		}
		
	break;
		
		
		
	//Create screens for entering and processing new vouchers. New program creation also happens here
	case 'voucher_request':
	
	//have they filled out the form?
	if (isset($_POST['redeem_voucher'])) {//lets process the first screen form
		//grab the form values
		$program = $_POST['program'];
		$code = $_POST['code'];
		$amount = str_replace("$","",$_POST['amount']);
		
		//the program exists (or was just created) so let's add the request to the DB and send some emails
		
			//grab the form values
			$pid = $_POST['program'];
		

			//insert the data into the DB
			if ($pid == 'new') { //is this a new program?
				//grab the location because this is a new program
				$location = $_POST['location'];
				
				//insert new program into the DB
				$table_name = $wpdb->prefix . "icafe_vpp_programs";			
				$wpdb->insert($table_name, array('program_name' => "$user_identity", 'program_description' => "$location", 'owner' => "$user_ID", 'balance' => "0", 'status' => "pending"));
				$pid = $wpdb->insert_id;
				
				//prepare and send email to program manager that a new program is ready to be created
				
				//link to wpVPP plugin admin page for programs
				$admin_url = admin_url('admin.php?page=icafe_vpp-Manage-VPP-Accounts');
				$program_manager_id = get_option('icafe_vpp_program_manager');
				$program_manager_info = get_userdata($program_manager_id);    			
				$program_manager_email = $program_manager_info->user_email;
				$proposed_apple_id = stripslashes(get_option('icafe_vpp_new_pf_email'));
				$proposed_email = str_replace("*", $pid, $proposed_apple_id);
				
				$body = '
							<h2><strong>Apple VPP New Program Request</strong></h2>
							<p><strong>Requestor: </strong><font color="568794">'.$program_manager_email.'</font><br>
							<strong>New AppleID: </strong><font color="568794">'.$proposed_email.'</font><br>
							  
							</p>
							<strong>Step 1: <a href="http://edu-vpp.apple.com/asvpp_manager/">Click to Create Program</a></strong><br>
							<br>
							<p><strong>Step 2: <a href="'.$admin_url.'">Click to Approve Program</a></strong></p>
							';
				
				//send the email to program manager to alert to the new request
				add_filter('wp_mail_content_type',create_function('', 'return "text/html";'));
				
				
				wp_mail($program_manager_email, 'VPP New Program Request', $body);
				
				
			} 
			
				//grab the information about the program
				$table_name = $wpdb->prefix . "icafe_vpp_programs";
				$program_data = $wpdb->get_row("SELECT * FROM $table_name WHERE pid = $pid");
				
				//insert voucher into the DB
				$table_name = $wpdb->prefix . "icafe_vpp_vouchers";			
				$wpdb->insert($table_name, array('voucher' => "$code" , 'pid' => "$pid", 'amount' => "$amount", 'voucher_status' => "pending"));
				$vid = $wpdb->insert_id;
				
				//send email to facilitator that a voucher request exisits IF the program is approved already
				if ($program_data->status == 'approved') {
				
					$owner_info = get_userdata($program_data->owner);
					$facilitator_info = get_userdata($program_data->facilitator);
					
					//Email facilitator that voucher nees to be redeemed
					$vpp_key = $vid;
					$url = $base_url.'?wpVPP=voucher_approve&vpp_key='.$vpp_key.'&approve=FALSE';
					$body = '
								<h2><strong>Apple VPP Voucher Redemption Request</strong></h2>
								<p>'.$owner_info->display_name.' has requested a voucher redemption.</p>
								<p><strong>Apple ID: </strong><font color="568794">'.$program_data->apple_id.'</font><br>
								  <strong>Voucher Number: </strong><font color="568794">'.$code.'</font><br>
								  <strong>Voucher Amount: </strong><font color="568794">$'.$amount.'</font><br>
								</p>
								<strong>Step 1: <a href="https://volume.itunes.apple.com/WebObjects/MZFinance.woa/wa/login?cc=us">Click to Redeem VPP Voucher with Apple</a></strong><br>
								<br>
								<p><strong>Step 2: <a href="'.$url.'">Click to Process Voucher Redemption</a></strong></p>
								
								';
				
					//send the email to program facilitator to alert to the new request
					add_filter('wp_mail_content_type',create_function('', 'return "text/html";'));
					wp_mail($facilitator_info->user_email, 'VPP Voucher Request', $body);
				}
			 
			//FINAL VOUCHER REDEMPTION SCREEN
			
			//grab the information about the program
				$table_name = $wpdb->prefix . "icafe_vpp_programs";
				$program_data = $wpdb->get_row("SELECT * FROM $table_name WHERE pid = $pid");
				$owner_info = get_userdata($program_data->owner);
				$facilitator_info = get_userdata($program_data->facilitator);
			//chris make the from email a variable that the admin can override
			$vpp_output .= '</br></br>
							<div id="stylized" class="myform">
							<h1>Apple Volume Purchase Request</h1>
							<strong>Your voucher request has been sent to your program administrator '.$facilitator_info->display_name.'</strong>
							</br></br>
							Once your voucher has been processed you will recieve an email from '.get_bloginfo().' with instructions for requesting app purchases.
							</br></br>
							<a href="'.$base_url.'?wpVPP=voucher_request">Redeem Another Voucher</a>
							</div>
			
			';
		
		
	
	} else { //First Voucher Redemption screen
       	
		//First Voucher Redemption Screen
		
		//does this person have an existing program created?
		
		
		$table_name = $wpdb->prefix . "icafe_vpp_programs";
		$active_programs = $wpdb->get_var("SELECT COUNT(*) FROM $table_name WHERE owner = $user_ID");
		$locations = '';
		if ($active_programs == 0) {
			$existing_program = '<input name="program" type="hidden" value="new" />';	
			$program_descriptions = stripslashes(get_option('icafe_vpp_program_descriptions'));
			$program_description_lable = stripslashes(get_option('icafe_vpp_program_description_lable'));
			$locations = '';
			if ($program_description_lable != '') {
				$locations = '
						<label>'.$program_description_lable.'
					</label>
					<select name="location">';
					
						$locations .= icafe_vpp_dropdown_from_textarea($program_descriptions);
					
				$locations .= '</select>';
			}
			
		} else { //has a program
			$pid = $wpdb->get_var("SELECT pid FROM $table_name WHERE owner = $user_ID");
			$existing_program = '<input name="program" type="hidden" value="'.$pid.'" />';	
		}
		//$wpdb->show_errors();
		//create the voucher redeem form
		$vpp_output .= '			
		</br></br>
		<div id="stylized" class="myform">
		<form id="redeem" name="redeem" method="post">
		<h1>Redeem an Apple Volume Voucher</h1>
		<strong><p>Use the form below to redeem your voucher.
		</br></strong>
		Vouchers must be redeemed before you can request Apps.
		</p>
		'.$locations.'
		<label>Voucher Code
		<span class="small">Code under the scratch off on the back of your card</span>
		</label>
		<label for="code" class="error" generated="true"></label>
		<input type="text" name="code" id="code" maxlength="16" />
		
		<label>Voucher Amount
		<span class="small"></span>
		</label>
		<select name="amount">
			<option value="100">$100</option>
			<option value="500">$500</option>
			<option value="1000">$1000</option>
			<option value="5000">$5000</option>
			
		</select>
				
		<input name="redeem_voucher" type="hidden" value="true" />
		'.$existing_program.'
		<button type="submit">Submit</button>
		<div class="spacer"></div>		
		</form>
		</div>
		';
	}
	
	break;
		
		
		
	//Screens for program facilitators to confirm that vouchers have been redeemed in the Apple VPP	
	case 'voucher_approve':
     
	  //check that this is a valid vid key
		if (isset($_GET['vpp_key'])) {
			
			//chris create these routines
			//is this a valid key
			$is_valid = TRUE;
			
			//decode the vid
			$vid = $_GET['vpp_key'];
		}
		
		if ($is_valid) {
			//get the voucher data
			$table_name = $wpdb->prefix . "icafe_vpp_vouchers";
			$voucher_data = $wpdb->get_row("SELECT * FROM $table_name WHERE vid = $vid");
			$pid = $voucher_data->pid;
			$table_name = $wpdb->prefix . "icafe_vpp_programs";
			$program_data = $wpdb->get_row("SELECT * FROM $table_name WHERE pid = $pid");
			$owner_info = get_userdata($program_data->owner);
			$facilitator_info = get_userdata($program_data->facilitator);
			
			if ($_GET['approve'] == 'TRUE') { //are we approving

				//mark voucher approved in the DB
				$table_name = $wpdb->prefix . "icafe_vpp_vouchers";			
				$wpdb->UPDATE($table_name, array('voucher_status' => "approved"), array('vid' => "$vid"));
				
				//update the program balance to include the new voucher
				$new_balance = $voucher_data->amount + $program_data->balance;
				$table_name = $wpdb->prefix . "icafe_vpp_programs";			
				$wpdb->UPDATE($table_name, array('balance' => "$new_balance"), array('pid' => "$pid"));
				
				//Email the program owner that they are ready to request apps
				$url = $base_url.'?wpVPP=app_request';
			
						$body = '
								<h2><strong>Apple VPP Voucher Redemption Approved</strong></h2>
								<p>'.$facilitator_info->display_name.' has processed your VPP Voucher.</p>
								<p><strong>Program Name: </strong><font color="568794">'.$program_data->program_name.'</font><br>
								  <strong>Voucher Number: </strong><font color="568794">'.$voucher_data->voucher.'</font><br>
								  <strong>Voucher Amount: </strong><font color="568794">$'.$voucher_data->amount.'</font><br>
								</p>
								<h3>You are now ready to request app purchases!</h3><br>
								<br>
								<p><strong><a href="'.$url.'">Click to Request an App Purchase</a></strong></p>
								
								';
						
						//send the email
						add_filter('wp_mail_content_type',create_function('', 'return "text/html";'));
						//chris switch below
						//wp_mail('cnilsson@lcisd.org', 'VPP Voucher Processed', $body);
						wp_mail($owner_info->user_email, 'VPP Voucher Request', $body);
				
				
				//FINAL VOUCHER APPROVAL SCREEN
				$vpp_output .= '</br></br>
						<div id="stylized" class="myform">
						<h1>Apple VPP Voucher Redemption Confirmed</h1>
						<strong>Thank you for confirming voucher '.$voucher_data->voucher.'.</strong>
						</br></br>
						'.$owner_info->display_name.' has been notified via email that they may now request app purchases.
						</div>
					';
			
			
			} else { //are we showing the first screen for voucher redemption processing
	  
			  $url = $base_url.'?wpVPP=voucher_approve&vpp_key='.$vid.'&approve=TRUE';
			  
			  $vpp_output .= '
							<br><br>
							<div id="stylized" class="myform">
							<h1>Apple VPP Voucher Redemption Confirmation</h1>
							<p><strong>&nbsp;&nbsp;&nbsp;Requestor: </strong><font color="568794">'.$owner_info->display_name.'</font><br>
							<strong>&nbsp;&nbsp;&nbsp;Apple ID: </strong><font color="568794">'.$program_data->apple_id.'</font><br>
							  <strong>&nbsp;&nbsp;&nbsp;Approver: </strong><font color="568794">'.$facilitator_info->display_name.'</font><br>
							  <strong>&nbsp;&nbsp;&nbsp;Voucher: </strong><font color="568794">'.$voucher_data->voucher.'</font><br>
							  <strong>&nbsp;&nbsp;&nbsp;Amount: </strong><font color="568794">$'.$voucher_data->amount.'</font><br>
							</p>
							
							<h3>Have you redeemed this voucher in the Apple VPP Portal?</strong></h3>
							<br>
							<strong>&nbsp;&nbsp;&nbsp;No: <a href="https://volume.itunes.apple.com/WebObjects/MZFinance.woa/wa/login?cc=us" target="_new">Reedem Voucher Now</a></strong>
							<br>
							<br><strong>&nbsp;&nbsp;&nbsp;Yes: <a href="'.$url.'">Click to Confirm Voucher Redemption</a></strong>
									  
			  ';
			}
		
		} else {//bad vid_key
				$program_manager = stripslashes(get_option('icafe_vpp_program_manager'));
				$vpp_output .= '</br></br>
						<div id="stylized" class="myform">
						<h1>Apple VPP Voucher Redemption Error</h1>
						<strong>Sorry, this voucher cannot be located.</strong>
						</br></br>
						Please notify your Apple Program Manager '.$program_manager.'
						</div>
				';
				
		}

	   
	break;
		
		
		
		
	case 'app_request':
       
	   if(isset($_POST['request_app'])) {//process the app request
			
			//grab the form values			
			$pid = $_POST['program'];
			$app_name = $_POST['app_name'];
			$publisher = $_POST['publisher'];
			$quantity = $_POST['quantity'];
			$cost_one = str_replace("$","",$_POST['cost_one']);
			$cost_volume = str_replace("$","",$_POST['cost_volume']);
			$for_user = $_POST['for_user'];
			$app_url = $_POST['url'];
						
			//insert the data into the DB			
			$table_name = $wpdb->prefix . "icafe_vpp_apps";			
			$wpdb->insert($table_name, array('pid' => "$pid" , 'app' => "$app_name", 'publisher' => "$publisher", 'quantity' => "$quantity", 'for_user' => "$for_user", 'url' => "$app_url", 'app_status' => "pending"));
			$aid = $wpdb->insert_id;
			
			
			
			//grab the information about the program
			$table_name = $wpdb->prefix . "icafe_vpp_programs";
			$program_data = $wpdb->get_row("SELECT * FROM $table_name WHERE pid = $pid");
			$owner_info = get_userdata($program_data->owner);
			$facilitator_info = get_userdata($program_data->facilitator);
			
			//email program facilitator that there is a new app request
			$app_key = $aid;
			$url = $base_url.'?wpVPP=app_approve&app_key='.$app_key.'&approve=FALSE';
			$body = '
						<h2><strong>Apple VPP App Purchase Request</strong></h2>
						<p>'.$owner_info->display_name.' has requested an app purchase.</p>
						<p><strong>Apple ID: </strong><font color="568794">'.$program_data->apple_id.'</font><br>
						  <strong>App: </strong><font color="568794">'.$app_name.'</font><br>
						  <strong>Publisher: </strong><font color="568794">'.$publisher.'</font><br>
						  <strong>Price each 1-19 copies: </strong><font color="568794">$'.$cost_one.'</font><br>
						  <strong>Price each 20+ copies: </strong><font color="568794">$'.$cost_volume.'</font><br>
						  <strong>Quantity Requested: </strong><font color="568794">'.$quantity.'</font><br>
						  <strong>Requested For: </strong><font color="568794">'.$for_user.'</font><br>
						</p>
						<strong>Step 1: <a href="'.$app_url.'">Click to Purchase App</a></strong><br>
						<br>
						<p><strong>Step 2: <a href="'.$url.'">Click to Process App Purchase</a></strong></p>
						
						';
			
				//send the email to program manager to alert to the new request
				add_filter('wp_mail_content_type',create_function('', 'return "text/html";'));
				//chris switch below
				//wp_mail('cnilsson@lcisd.org', 'VPP App Request', $body);
				wp_mail($facilitator_info->user_email, 'VPP App Request', $body);
			
			
			//FINAL VOUCHER APPROVAL SCREEN
				$vpp_output .= '</br></br>
						<div id="stylized" class="myform">
						<h1>Apple VPP App Request Pending</h1>
						<p><strong>Your app request has been sent to your program administrator, '.$facilitator_info->display_name.'</strong>
						</br></br>
						Once your purchase has been processed you will receive an email with instructions for installing your apps.</p>
						<strong>App: </strong><font color="568794">'.$app_name.'</font><br>
						<strong>Publisher: </strong><font color="568794">'.$publisher.'</font><br>
						<strong>Price each 1-19 copies: </strong><font color="568794">$'.$cost_one.'</font><br>
						<strong>Price each 20+ copies: </strong><font color="568794">$'.$cost_volume.'</font><br>
						<strong>Quantity Requested: </strong><font color="568794">'.$quantity.'</font><br>
						<strong>Requested For: </strong><font color="568794">'.$for_user.'</font><br><br>
						<a href="'.$base_url.'?wpVPP=app_request">Request Another App</a>
						</div>
					';
					
					
		} else {//show the app request form
			
			$table_name = $wpdb->prefix . "icafe_vpp_programs";
			$active_program = $wpdb->get_row("SELECT pid, owner, balance FROM $table_name WHERE owner = $user_ID AND status = 'approved'");
			$owner_info = get_userdata($active_program->owner);
			$has_program = false;
			
			//make sure this program has a processed voucher associated with it
			$table_name = $wpdb->prefix . "icafe_vpp_vouchers";
			$pid = $active_program->pid;
			$active_vouchers = $wpdb->get_var("SELECT COUNT(*) FROM $table_name WHERE pid = $pid AND voucher_status = 'approved'");
			if ($active_vouchers != 0) {
				$has_program = true;
			}
			

			//only show the page if they have a program and voucher
			if ($has_program) {
				$vpp_output .= '			
								</br></br>
								<div id="stylized" class="myform">
								<form id="app_request" name="app_request" method="post">
								<h1>Request an App Purchase</h1>
								<p><strong>Use the form below to request an app purchase using your Volume Voucher.</strong>
								</br><br/>Vouchers must be <a href="'.$url.'?wpVPP=voucher_request">redeemed</a> before you can request Apps.
								</br></br>
								<span style="color: #8B0000"> You must first locate your app in the </span><a href="https://volume.itunes.apple.com/us/store/" target="_new">Apple Education Store.</a></p>
								
								<h1>'.$owner_info->display_name.', you have approximately $'.$active_program->balance.' remaining on your vouchers.</h1>
								
								<label>App Name
								<span class="small">&nbsp;</span>
								</label>
								<label for="app_name" class="error" generated="true"></label>
								<input type="text" name="app_name" id="app_name"/>
								
								<label>Publisher
								<span class="small">Who is this app "By"?</span>
								</label>
								<label for="publisher" class="error" generated="true"></label>
								<input type="text" name="publisher" id="publisher"/>
								
								<label>Quantitiy
								<span class="small">How many copies do you need (one per device)</span>
								</label>
								<label for="quantity" class="error" generated="true"></label>
								<input type="text" name="quantity" id="quantity"/>
								
								<label>Price each
								<span class="small">1-19 copies</span>
								</label>
								<label for="cost_one" class="error" generated="true"></label>
								<input type="text" name="cost_one" id="cost_one"/>
								
								<label>Price each
								<span class="small">20+ copies</span>
								</label>
								<label for="cost_volume" class="error" generated="true"></label>
								<input type="text" name="cost_volume" id="cost_volume"/>
								
								<label>Purchasing For
								<span class="small">List the names of other users receiving this app<br /><strong>Seperate with commas</strong></span>
								</label>
								<label for="for_user" class="error" generated="true"></label>
								<textarea name="for_user" id="for_user" cols="2" rows="2"></textarea>
								
								<label>App URL
								<span class="small">Copy the URL from the Apple Education Store (link above)</span>
								</label>
								<label for="url" class="error" generated="true"></label>
								<input type="text" name="url" id="url"/>
								
							
								
								
								<input name="program" type="hidden" value="'.$pid.'" />
								<input name="request_app" type="hidden" value="true" />
								
								<button type="submit">Request</button>
								<div class="spacer"></div>
								
								</form>
								</div>
							';
			}else{ //no program or no vouchers processed
				
				$vpp_output .= '</br></br>
							<div id="stylized" class="myform">
							<h1>Apple Volume Purchase Request</h1>
							<strong>You must first redeem an Apple Volume Voucher.</strong>
							</br></br>
							If you have already entered a voucher you must wait until your program administrator processes your request. You will receive an email when the system is ready for you to request apps.
							</div>
			
			';
			
			}
		}
	   
	break;
		
		
		
		
	case 'app_approve':
       
	  //check that this is a valid aid key
		if (isset($_GET['app_key'])) {
			
			//chris create these routines
			//is this a valid key
			$is_valid = TRUE;
			
			//decode the aid
			$aid = $_GET['app_key'];
			$app_key = $_GET['app_key'];
		}
		
		if ($is_valid) {
			//get the app data
			$table_name = $wpdb->prefix . "icafe_vpp_apps";
			$app_data = $wpdb->get_row("SELECT * FROM $table_name WHERE aid = $aid");
			$pid = $app_data->pid;
			$table_name = $wpdb->prefix . "icafe_vpp_programs";
			$program_data = $wpdb->get_row("SELECT * FROM $table_name WHERE pid = $pid");
			$owner_info = get_userdata($program_data->owner);
			$facilitator_info = get_userdata($program_data->facilitator);
			
			//get the data from the form
			$cost = str_replace("$","",$_POST['cost']);
			$balance = str_replace("$","",$_POST['balance']);
			//$cost_one = $_POST['cost_one'];
			
			if (($_GET['approve'] == 'TRUE') && ($_FILES['codes']['size'] > 0)) { //are we approving and is there a file
			
			//read in the file data
			$fileName = $_FILES['codes']['name'];
			$tmpName  = $_FILES['codes']['tmp_name'];
			$fileSize = $_FILES['codes']['size'];
			$fileType = $_FILES['codes']['type'];
			
			$fp      = fopen($tmpName, 'r');
			$content = fread($fp, filesize($tmpName));
			$content = ($content);
			fclose($fp);
			
			if(!get_magic_quotes_gpc())
			{
				$fileName = ($fileName);
			}

			//$wpdb->show_errors();
				//mark app purchased in the DB and store the code excel file
				$table_name = $wpdb->prefix . "icafe_vpp_apps";			
				$wpdb->UPDATE($table_name, array('cost' => "$cost", 'codes' => "$content", 'size' => "$fileSize", 'type' => "$fileType", 'name' => "$fileName", 'codes' => "$content", 'app_status' => "purchased"), array('aid' => "$aid"));
				$table_name = $wpdb->prefix . "icafe_vpp_programs";			
				$wpdb->UPDATE($table_name, array('balance' => "$balance"), array('pid' => "$pid"));
				
				$url = $base_url.'?wpVPP=app_approve&app_key='.$app_key.'&codes=TRUE';
			
				//Email the program owner their app information
						
						$body = '
								<h2><strong>Apple VPP App Purchase</strong></h2>
								<p>'.$facilitator_info->display_name.' has purchased your requested app.</p>
								<p><strong>Program Name: </strong><font color="568794">'.$program_data->program_name.'</font><br>
								  <strong>App Purchased: </strong><a href="'.$app_data->url.'"><font color="568794">'.$app_data->app.'</font></a><br>
								  <strong>Quantity: </strong><font color="568794">'.$app_data->quantity.'</font><br>
								  <strong>Cost: </strong><font color="568794">$'.$cost.'</font><br>
								  <strong>Remaining Program Balance: </strong><font color="568794">$'.$balance.'</font><br>
								</p>
								<h3>You are now ready to install your apps!</h3><br>
								<br>
								</p>
								<strong>Step 1: <a href="'.$url.'">Download your app codes</a></strong><br>
								<br>
								<strong>Step 2: If you purchased this app for other users, distribute one code per installation to users. Include the link below</strong><br>
								<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$app_data->for_user.'
								<p><strong>Step 3: <a href="'.$base_url.'?wpVPP=lookup_pw">Instructions for installing your app</a></strong></p>
								
								';
						
						//send the email
						add_filter('wp_mail_content_type',create_function('', 'return "text/html";'));
						//chris switch below
						//wp_mail('cnilsson@lcisd.org', 'VPP App Purchased', $body);
						wp_mail($owner_info->user_email, 'VPP App Request', $body);
				
						$vpp_output .= '</br></br>
							<div id="stylized" class="myform">
							<h1>Apple VPP App Purchase Confirmedt</h1>
							<strong>Thank you for purchasing '.$app_data->app.'.</strong>
							</br></br>
							'.$owner_info->display_name.' has been emailed the app installation redemption codes and instructions on how to install.
							</div>
						';
						
			} else {//are we showing the first screen for app purchase processing
	  
			  $url = $base_url.'?wpVPP=app_approve&app_key='.$aid.'&approve=TRUE';
			  
			  //Approval form for updating program balance and uploading codes
			  $vpp_output .= '
			 				</br></br>
<div id="stylized" class="myform">
			  				<form id="app_approve" name="app_approve" method="post" action="'.$url.'" enctype="multipart/form-data">
							<h3><strong>Apple VPP App Purchase Confirmation</strong></h3>
							<p><strong>&nbsp;&nbsp;&nbsp;Requestor: </strong><font color="568794">'.$owner_info->display_name.'</font><br>
							<strong>&nbsp;&nbsp;&nbsp;Apple ID: </strong><font color="568794">'.$program_data->apple_id.'</font><br>
							  <strong>&nbsp;&nbsp;&nbsp;Purchaser: </strong><font color="568794">'.$facilitator_info->display_name.'</font><br>
							  <strong>&nbsp;&nbsp;&nbsp;App: </strong><font color="568794">'.$app_data->app.'</font><br>
							  <strong>&nbsp;&nbsp;&nbsp;Quantity: </strong><font color="568794">'.$app_data->quantity.'</font><br>
							  <strong>&nbsp;&nbsp;&nbsp;Requested For: </strong><font color="568794">'.$app_data->for_user.'</font><br>
							</p>
							
							<h3>Have you purchased this app in the Apple VPP Education Store?</strong></h3>
							<p><strong>No: <a href="'.$app_data->url.'" target="_new">Purchase App Now</a></strong>
							<br>
							<br><strong>Yes: Proceed below</strong></p>
							
							<label>Total Cost
							<span class="small">&nbsp;</span>
							</label>
							<label for="cost" class="error" generated="true"></label>
							<input type="text" name="cost" id="cost"/>
							
							<label>Remaining Balance
							<span class="small">Program balance after the purchase</span>
							</label>
							<label for="balance" class="error" generated="true"></label>
							<input type="text" name="balance" id="balance"/>
							
							<label>Redemption Codes
							<span class="small">Upload the Excel Spreadsheet you recieved from the Apple Store</span>
							</label>
							<label for="codes" class="error" generated="true"></label></br>
							
							<input name="codes" type="file" />
							
							<button type="submit">Submit</button>
							</form>
							</div>
			  ';
			}
		
		} else {//bad aid_key
				//chris style this
				$vpp_output .= 'sorry that app request cannot be located';
				
		}

	   
	break;
		
		
	//generate and store new itunes passwords used to power auto password change feature	
	case 'generate_new_pw':
	//grab the global account credentials
		$global_appleid = stripslashes(get_option('icafe_vpp_global_itunes'));
				
		if ($_GET['changekey'] == '98jh298yan39Ojiyuia879UHYTHJ32') {
			
			//check to see if new password is being set
			if ($_GET['update_pw'] == 'true') {
				$new = $_GET['new'];				
				update_option('icafe_vpp_current_appleid_pw', $new);				
			}
			
			//check to see if an account has been provisioned
			if ($_GET['provision'] == 'true') {
				$prov_pid = $_GET['pid'];
				$prov_apple_id = $_GET['appleid'];
				$prov_pw = $_GET['pf_pw'];				
				
				//mark app purchased in the DB and store the code excel file
				$table_name = $wpdb->prefix . "icafe_vpp_programs";			
				$wpdb->UPDATE($table_name, array('apple_id' => "$prov_apple_id", 'password' => "$prov_pw", 'status' => "provisioned"), array('pid' => "$prov_pid"));
			}
		
			
			//grab the current settings  
			$global_appleid = stripslashes(get_option('icafe_vpp_global_itunes'));
			$global_appleid_current_pw = stripslashes(get_option('icafe_vpp_current_appleid_pw'));
			$global_appleid_sec1 = get_option('global_appleid_sec1');
			$global_appleid_sec2 = get_option('global_appleid_sec2');
			$global_appleid_sec3 = get_option('global_appleid_sec3');
			$global_appleid_sec1_answer = stripslashes(get_option('global_appleid_sec1_answer'));
			$global_appleid_sec2_answer = stripslashes(get_option('global_appleid_sec2_answer'));
			$global_appleid_sec3_answer = stripslashes(get_option('global_appleid_sec3_answer'));
			$email = stripslashes(get_option('icafe_vpp_new_pf_email'));
			$universal_pw = stripslashes(get_option('icafe_vpp_new_pf_universal_pw'));
			$reset_month = get_option('icafe_vpp_new_pf_month');
			$reset_day = get_option('icafe_vpp_new_pf_day');
			$reset_year = get_option('icafe_vpp_new_pf_year');
			$reset_question = stripslashes(get_option('icafe_vpp_new_pf_sec_q'));
			$reset_answer = stripslashes(get_option('icafe_vpp_new_pf_sec_a'));
			$program_manager_itunes_account = stripslashes(get_option('icafe_vpp_program_manager_itunes_account'));
			$program_manager_itunes_pw = stripslashes(get_option('icafe_vpp_program_manager_itunes_pw'));

	
			
			$capital = range ('A','Z');
			$small = range ('a','z');
			$number = range ('0','9');
			$special = array ("#","$","@");
			
			$new_pw =	$small[array_rand($small)] .
						$number[array_rand($number)] .
						$number[array_rand($number)] .
						$number[array_rand($number)] .
						$number[array_rand($number)] .
						$number[array_rand($number)] .
						$number[array_rand($number)] .
						$capital[array_rand($capital)]; 
								
			
			$update_pw_url = $base_url.'?wpVPP=generate_new_pw&update_pw=true&changekey=98jh298yan39Ojiyuia879UHYTHJ32&new='.$new_pw;					
							
			
			
			echo '
			<table width="100%" border="1" cellspacing="0" cellpadding="0">
			 <tr>				
				<td>'.base64_encode($global_appleid).'</td>
			  </tr>
			  <tr>
				
				<td>'.base64_encode($global_appleid_current_pw).'</td>
			  </tr>
			  <tr>				
				<td>'.base64_encode($new_pw).'</td>
			  </tr>
			  <tr>				
				<td>'.base64_encode($global_appleid_sec1).'</td>
			  </tr>
			  <tr>				
				<td>'.base64_encode($global_appleid_sec1_answer).'</td>
			  </tr>
			  <tr>			
				<td>'.base64_encode($global_appleid_sec2).'</td>
			  </tr>
			  <tr>				
				<td>'.base64_encode($global_appleid_sec2_answer).'</td>
			  </tr>
			  <tr>				
				<td>'.base64_encode($global_appleid_sec3).'</td>
			  </tr>
			  <tr>				
				<td>'.base64_encode($global_appleid_sec3_answer).'</td>
			  </tr>
			</table>';

				echo '
			<table width="100%" border="1" cellspacing="0" cellpadding="0">
			 <tr>				
				<td>'.base64_encode($program_manager_itunes_account).'</td>
			  </tr>
			  <tr>
				
				<td>'.base64_encode($program_manager_itunes_pw).'</td>
			  </tr>
			  <tr>				
				<td>'.base64_encode($reset_month).'</td>
			  </tr>
			  <tr>				
				<td>'.base64_encode($reset_day).'</td>
			  </tr>
			  <tr>				
				<td>'.base64_encode($reset_year).'</td>
			  </tr>
			  <tr>			
				<td>'.base64_encode($reset_question).'</td>
			  </tr>
			  <tr>				
				<td>'.base64_encode($reset_answer).'</td>
			  </tr>
			</table>';
			
			$table_name = $wpdb->prefix . "icafe_vpp_programs";
			$pending_programs = $wpdb->get_results("SELECT * FROM $table_name WHERE status = 'pending'");
			$pending_rows = '';
			if ($pending_programs != null) {
				foreach ($pending_programs as $pending) {
					$user_info = get_userdata($pending->owner);
					//build email template
					$program_email = str_replace("*", $pending->pid, $email);
				
							$pending_rows .= '<tr>
										  <td>'.base64_encode($user_info->display_name).'</td>									  
										  <td>'.base64_encode($program_email).'</td>
										  <td>'.base64_encode($universal_pw).'</td>
										  <td>'.base64_encode($pending->pid).'</td>
										</tr>';
				
				}
						
				echo '
				<table width="100%" border="1" cellspacing="0" cellpadding="0">
				'.$pending_rows.'
				</table>';	
			} else {
				echo 'no_pending<br />';
			}
					
				
			
			
			echo '
			<a href="'.$update_pw_url.'">Save New Password</a>
			';
		}
	
	break;	
	
	case 'lookup_pw':
		$global_appleid = stripslashes(get_option('icafe_vpp_global_itunes'));
		$global_appleid_current_pw = stripslashes(get_option('icafe_vpp_current_appleid_pw'));
		$image_path = plugins_url('icafe-vpp-manager/css/img/' , _FILE_);
		
		if ($global_appleid == '') {
			
			$display_password = '
					
					<table width="100%" cellspacing="0" cellpadding="0">
					<tbody>
					<tr>
					<td class="bottom_border"><strong>Step 1:</strong> On your iOS device click on the App Store.</td>
					<td class="bottom_border"> <a href="'.$image_path.'1.png"><img class="aligncenter size-full title="1" src="'.$image_path.'1.png" alt="" width="64" height="64" /></a></td>
					</tr>
					
					<tr>
					<td class="bottom_border"><strong>Step 7:</strong> Scroll to the bottom and click "Redeem", enter the App Code you received and click the blue"Redeem" button.</td>
					<td class="bottom_border"> <a href="'.$image_path.'7-300x177.png"><img class="aligncenter size-medium" title="7" src="'.$image_path.'7-300x177.png" alt="" width="300" height="177" /></a></td>
					</tr>
					
					
					<tr>
					<td><strong>Step 10:</strong> Your App will be loading on your home screen.</td>
					<td></td>
					</tr>
					</tbody>
					</table>
					
					';
			
		} else {
			
			if (isset($_GET['accepted'])) {
				if ($_GET['accepted'] == 'yes') {
					
					$display_password = '
					
					<table width="100%" cellspacing="0" cellpadding="0">
					<tbody>
					<tr>
					<td class="bottom_border"><strong>Step 1:</strong> On your iOS device click on the App Store.</td>
					<td class="bottom_border"> <a href="'.$image_path.'1.png"><img class="aligncenter size-full title="1" src="'.$image_path.'1.png" alt="" width="64" height="64" /></a></td>
					</tr>
					<tr>
					<td class="bottom_border"><strong>Step 2:</strong> Scroll to the bottom and click the currently signed in Apple ID button .</td>
					<td class="bottom_border"> <a href="'.$image_path.'21.png"><img class="aligncenter" title="2" src="'.$image_path.'21.png" alt="" width="314" height="45" /></a></td>
					</tr>
					<tr>
					<td class="bottom_border"><strong>Step 3:</strong> Click "Sign Out".</td>
					<td class="bottom_border"> <a href="'.$image_path.'3.png"><img class="aligncenter" title="3" src="'.$image_path.'3.png" alt="" width="308" height="327" /></a></td>
					</tr>
					<tr>
					<td class="bottom_border"><strong>Step 4:</strong> Click "Sign In".</td>
					<td class="bottom_border"> <a href="'.$image_path.'4.png"><img class="aligncenter" title="4" src="'.$image_path.'4.png" alt="" width="305" height="42" /></a></td>
					</tr>
					<tr>
					<td class="bottom_border"><strong>Step 5:</strong> Choose "Use Existing Apple ID".</td>
					<td class="bottom_border"> <a href="'.$image_path.'5.png"><img class="aligncenter" title="5" src="'.$image_path.'5.png" alt="" width="307" height="245" /></a></td>
					</tr>
					<tr>
					<td class="bottom_border"><strong>Step 6:</strong> Enter the Enterprise Owned iTunes Account information shown to the right and click "OK".</td>
					<td class="bottom_border">
					<div style="background-image:url(\''.$image_path.'password_box.png\'); width:276px; height:122px; font:21px helvetica; color:#303030">
											<br>
											<br>&nbsp;&nbsp;&nbsp;
											<strong>'.$global_appleid.'</strong>
											<br>&nbsp;&nbsp;&nbsp;
											<strong>'.$global_appleid_current_pw.'</strong>
											</div>
					
					</td>
					</tr>
					<tr>
					<td class="bottom_border"><strong>Step 7:</strong> Click "Redeem", enter the App Code you received and click the blue"Redeem" button.</td>
					<td class="bottom_border"> <a href="'.$image_path.'7-300x177.png"><img class="aligncenter size-medium" title="7" src="'.$image_path.'7-300x177.png" alt="" width="300" height="177" /></a></td>
					</tr>
					<tr>
					<td class="bottom_border"><strong>Step 8:</strong> Click the Apple ID: enterprise account name button and choose "Sign Out".</td>
					<td class="bottom_border"> <a href="'.$image_path.'8.png"><img class="aligncenter" title="8" src="'.$image_path.'8.png" alt="" width="299" height="42" /></a></td>
					</tr>
					<tr>
					<td><strong>Step 9:</strong> You may now sign back in with the Apple ID normally used on this device.</br></td>
					<td></td>
					</tr>
					<tr>
					<td><strong>Step 10:</strong> Your App will be loading on your home screen. <br />You do not need to wait for it to load before signing out of the enterprise account.</td>
					<td></td>
					</tr>
					</tbody>
					</table>
					
					';
					
				}
			} else {
				$url = $base_url.'?wpVPP=lookup_pw&accepted=yes';
				$display_password = '
				<strong>When to use:</strong>
					<ul>
						<li>You or someone else has purchased one or more Apps using the <a href="'.$base_url.'">Volume Purchase Program</a></li>
						<li>Codes for installing those Apps have been provided to you</li>
					</ul>
					<strong>When NOT to use:</strong>
					<ul>
						<li>Installing free Apps</li>
						<li>Installing Apps on NON-District devices</li>
						<li>To browse and install district owned apps WITHOUT an installation code (if you want something...ask someone to purchase it for you!)</li>
					</ul>
					<span style="color: #800000;"><strong>The Enterprise iTunes account changes passwords every '.get_option('global_appleid_change_interval').' hour(s).<br /></strong></span>
					<div><img class="alignleft size-full" title="apple-logo" src="'.$image_path.'apple-logo1.png" alt="" width="128" height="128" />
					</div>
					<div>
					<br /><br /><br />Click below to agree to the terms and lookup the account information<br />
					<h2><strong><span style="color: #800000;"><a href="'.$url.'"><span style="color: #800000;">Look up current Enterprise iTunes Credintials</span></a></span></strong></h2></div>
								
				
					
						
										';
			}
		}
		
		echo $display_password;	
	
	break;
		
   
}

$vpp_output .= '</div>';
	//output to the browser
	echo $vpp_output;
	
	$display_vpp_output = ob_get_contents(); // end output buffering

    ob_end_clean(); // grab the buffer contents and empty the buffer

    return $display_vpp_output;

	
}



//allow code downloads
function icafe_vpp_code_download() {
 
  if(isset($_GET['codes']) && $_GET['codes'] == 'TRUE') { //retrieve codes for download
    //check that this is a valid aid key
		if (isset($_GET['app_key'])) {
			
			//chris create these routines
			//is this a valid key
			$is_valid = TRUE;
			
			//decode the aid
			$aid = $_GET['app_key'];
		
				global $wpdb;
				$table_name = $wpdb->prefix . "icafe_vpp_apps";	
				$codes = $wpdb->get_row("SELECT * FROM $table_name WHERE aid = $aid");
				$size = $codes->size;
				$type = $codes->type;
				$name = $codes->name;
				header("Content-length: $size");
				header("Content-type: $type");
				header("Content-Disposition: attachment; filename=$name");
				echo $codes->codes;
		}		
  }
			
}




//build the main landing page
function icafe_vpp_admin() {
	wp_vpp_check_admin_access ();
	echo get_option('plugin_error');
 echo <<<________EOS
		<div class="wrap">
            <h2>Apple VPP Manager Instructions</h2>
			<p>
			The Apple Volume Purchase Program offers educational institutions and certain businesses the opportunity to purchase Apps in bulk and at a discount. Unfortunately, it is a poorly designed, labor intensive system.<br />
			iCafe VPP Manager is designed to facilitate voucher redemptions, program facilitator accounts, app purchases, and app redemptions. iCafe VPP Manager can even automate many of the common tasks Program Managers must perform in the Apple VPP Portal.<br /><br />
			iCafe VPP Manager is designed to be as simple as possible for end users but because it is managing a complicated system (VPP) it requires a complex setup.<br /><br />
			To facilitate the proper configuration of iCafe VPP Manager, please take the time to watch each of the videos below. These videos offer the best explanation for each of the features and configurations of the plugin.<br /><br />
			Thanks,<br />
			Chris Nilsson<br />
			chrisdnilsson@gmail.com<br />
			@chrisnilsson

			</p>
			
			<table width="800px" border="0" cellspacing="2" cellpadding="2">
				<tr>
					<td colspan="2" align="center"><h3>Introduction</h3></td>
			  	</tr>
				
			  	<tr>
					<td width="440"><iframe width="420" height="315" src="http://www.youtube.com/embed/DT2EgJZJ864" frameborder="0" allowfullscreen></iframe></td>
					
					<td>iCafe VPP Manager works by creating a new layer in the Apple VPP workflow.<br /><br />
			Program Manger > <font color="#990000">Program Administrators</font> > Program Facilitators > End Users<br /><br />
			This new Program Administrator layer gives  enterprises the ability to manage hundreds of VPP accounts with only a few employees investing minimal time.<br /><br />
			This video is essential to understanding the role the iCafe VPP Manager Plugin can play in your enterprise.
					</td>
					
			  	</tr>
				
				<tr>
					<td colspan="2" align="center"><h3>End User Workflow </h3></td>
			  	</tr>
				
			  	<tr>
					<td width="440"><iframe width="420" height="236" src="http://www.youtube.com/embed/Po_2URco62M" frameborder="0" allowfullscreen></iframe></td>
					
					<td>It all comes down to what your end users experience. Take a quick look at how simple it is for end users to redeem vouchers, request app purchases, and redeem app installation codes (Even under an enterprise iTunes Account!)
					</td>
					
			  	</tr>
				
				<tr>
					<td colspan="2" align="center"><h3>Program Administrator Workflow </h3></td>
			  	</tr>
				
			  	<tr>
					<td width="440"><iframe width="420" height="236" src="http://www.youtube.com/embed/Ep60SQG2RGI" frameborder="0" allowfullscreen></iframe></td>
					
					<td>Program Administrators do not exist in a traditional VPP workflow.<br />
iCafe VPP Manager creates this layer to allow a few employees to simply manage hundreds of Program Facilitator Accounts.<br />
To do this, the workflow for Program Administrators must be quick and simple. This video will walk you through the workflow for a Program Administrator.

					</td>
					
			  	</tr>
				
				<tr>
					<td colspan="2" align="center"><h3>Program Manager Workflow </h3></td>
			  	</tr>
				
			  	<tr>
					<td width="440"><iframe width="420" height="236" src="http://www.youtube.com/embed/YE2EWBm03f8" frameborder="0" allowfullscreen></iframe></td>
					
					<td>Program Manager…the king of the castle! Unfortunately, being a Program Manager means you are responsible for creating potentially hundreds of Program Facilitator accounts and somehow managing thousands of app purchases without a tool to assist you.<br />
iCafe VPP Manager provides a much needed organizational and automation system for stressed Program Managers

					</td>
					
			  	</tr>
				
				<tr>
					<td colspan="2" align="center"><h3>System View with Data </h3></td>
			  	</tr>
				
			  	<tr>
					<td width="440"><iframe width="420" height="236" src="http://www.youtube.com/embed/L7P9UCfB5c0" frameborder="0" allowfullscreen></iframe></td>
					
					<td>Take a peek inside a live system that is managing tens of thousands of dollars in app purchase spread over hundreds of Program Facilitator accounts. 
					</td>
					
			  	</tr>
				
				<tr>
					<td colspan="2" align="center"><h3>Settings: Program Manager</h3></td>
			  	</tr>
				
			  	<tr>
					<td width="440"><iframe width="420" height="236" src="http://www.youtube.com/embed/852QNoaFIGk" frameborder="0" allowfullscreen></iframe></td>
					
					<td>Time to configure you installation of the iCafe VPP Manager Plugin.<br />
Step One: Program Manager Options

					</td>
					
			  	</tr>
				
				<tr>
					<td colspan="2" align="center"><h3>Settings: Program Administrators </h3></td>
			  	</tr>
				
			  	<tr>
					<td width="440"><iframe width="420" height="236" src="http://www.youtube.com/embed/LJ7eOQjppW0" frameborder="0" allowfullscreen></iframe></td>
					
					<td>Time to configure you installation of the iCafe VPP Manager Plugin.<br />
Step Two: Program Administrator Options

					</td>
					
			  	</tr>
				
				<tr>
					<td colspan="2" align="center"><h3>Settings Program Facilitators </h3></td>
			  	</tr>
				
			  	<tr>
					<td width="440"><iframe width="420" height="236" src="http://www.youtube.com/embed/bYRKgwDeiwQ" frameborder="0" allowfullscreen></iframe></td>
					
					<td>Time to configure you installation of the iCafe VPP Manager Plugin.<br />
Step Three: Program Facilitator Options

					</td>
					
			  	</tr>
				
				<tr>
					<td colspan="2" align="center"><h3>Settings: Enterprise iTunes Account</h3></td>
			  	</tr>
				
			  	<tr>
					<td width="440"><iframe width="420" height="236" src="http://www.youtube.com/embed/fuI42V_BOcY" frameborder="0" allowfullscreen></iframe></td>
					
					<td>Time to configure you installation of the iCafe VPP Manager Plugin.<br />
Step Four: Enterprise iTunes Account Options

					</td>
					
			  	</tr>
				
				<tr>
					<td colspan="2" align="center"><h3>Settings: Self-Serve Config </h3></td>
			  	</tr>
				
			  	<tr>
					<td width="440"><iframe width="420" height="236" src="http://www.youtube.com/embed/QjoSb90KI2Q" frameborder="0" allowfullscreen></iframe></td>
					
					<td>Time to configure you installation of the iCafe VPP Manager Plugin.<br />
Step Five: Self-Serve Config Options

					</td>
					
			  	</tr>
				
				<tr>
					<td colspan="2" align="center"><h3>Settings: New Program Config </h3></td>
			  	</tr>
				
			  	<tr>
					<td width="440"><iframe width="420" height="236" src="http://www.youtube.com/embed/oXUcHmoqBRU" frameborder="0" allowfullscreen></iframe></td>
					
					<td>Time to configure you installation of the iCafe VPP Manager Plugin.<br />
Step Six: New Program Config Options

					</td>
					
			  	</tr>
				
				<tr>
					<td colspan="2" align="center"><h3>Auto Account Creation Utility</h3></td>
			  	</tr>
				
			  	<tr>
					<td width="440"><iframe width="420" height="236" src="http://www.youtube.com/embed/iq4dp_X3gOk" frameborder="0" allowfullscreen></iframe></td>
					
					<td>How about some AUTOMATION?!?<br />
iCafe VPP Manager can be set up to automatically create new Program Facilitator accounts in the Apple VPP Portal,  automatically change the default “Apple123” password, and setup the account recovery security question options.

					</td>
					
			  	</tr>
				
				<tr>
					<td colspan="2" align="center"><h3>Auto Password Utility </h3></td>
			  	</tr>
				
			  	<tr>
					<td width="440"><iframe width="420" height="236" src="http://www.youtube.com/embed/zJK3Caq_8M4" frameborder="0" allowfullscreen></iframe></td>
					
					<td>How about some AUTOMATION?!?<br />
Do you want your staff to install all purchased Apps under a single enterprise owned account?<br />
This utility will allow you to schedule automatic password changes to that account keeping the credentials secure for trusted staff only.

					</td>
					
			  	</tr>
				
				<tr>
					<td colspan="2" align="center"><h3>Create Main VPP Page (shortcode) </h3></td>
			  	</tr>
				
			  	<tr>
					<td width="440"><iframe width="420" height="236" src="http://www.youtube.com/embed/tcorBkP4ehc" frameborder="0" allowfullscreen></iframe></td>
					
					<td>If you’ve made it this far then you are ready to get started!<br />
Simply create a page and insert the<br />
[icafe-vpp]<br />
shortcode.

					</td>
					
			  	</tr>
 
			</table>
			
  		</div>              
________EOS;
}

//returns a comma seperated list of ID of users with roles sufficiant to act at program administrator
function get_non_members() { 

    $roles = array('Super Admin', 'Administrator', 'Editor', 'Author', 'Contributor');
$test = 'Administrator';
	$non_members = '';
	$comma = '';
    foreach ($roles as $role) {
		$args = array(
			'role' => $role
		);
       $users = get_users($args);
      
	   foreach ($users as $user) {
		  $non_members .= $comma.$user->ID;
		  $comma = ',';

	   }
	}
return $non_members;
}

//build the main admin menu
function icafe_vpp_program_manager_admin() {
	wp_vpp_check_admin_access ();
	global $title;
	if ($_POST) {//update program mangager options
			echo '<div id="message" class="updated fade"><p>Your new settings were saved successfully.</p></div>';
			if ($_POST['menu'] == 'settings') {
			
				$program_manager = $_POST['program_manager'];
				update_option('icafe_vpp_program_manager', $program_manager);
				$program_manager_itunes_account = $_POST['program_manager_itunes_account'];
				update_option('icafe_vpp_program_manager_itunes_account', $program_manager_itunes_account);
				$program_manager_itunes_pw = $_POST['program_manager_itunes_pw'];
				update_option('icafe_vpp_program_manager_itunes_pw', $program_manager_itunes_pw);
				
				
			}
			
	}
	
	$program_manager_id = get_option('icafe_vpp_program_manager');
	$program_manager_info = get_userdata($program_manager_id);
	$program_manager_itunes_account = stripslashes(get_option('icafe_vpp_program_manager_itunes_account'));
	$program_manager_itunes_pw = stripslashes(get_option('icafe_vpp_program_manager_itunes_pw'));
	$url = get_option('icafe_vpp_plugin_url');
	
	$current_user = wp_get_current_user();


	//Is this someone who can edit the program manager?
	if (current_user_can('manage_options') || $current_user->ID == $program_manager_id) {
		
		$args = array(
			'include'	=> get_non_members(),
			'selected'	=> $program_manager_id,
			'name'		=> 'program_manager'
		);
		//$dropdown = wp_dropdown_users($args);
        echo <<<________EOS
		<div class="wrap">
            <h2>Apple VPP Program Manager</h2>
			<p>Select a WordPress user to act as the Apple Program Manager.<br /><br />
			This person will receive all new facilitator account requests via email and is responsible <br />for creating those accounts in the <a target="_new" href="https://daw.apple.com/cgi-bin/WebObjects/DSAuthWeb.woa/wa/login?appIdKey=ad21ac3831eec18ea9d3b7fd7619dfbcfb384492fb562e03883fc63b673b938e&path=/asvpp_manager/index.php">"Apple VPP for Education Account Manager Portal"</a>.<br /><br />
			 This is also the user who will assign new accounts to an existing <a href="admin.php?page=icafe_vpp-Program-Facilitator-Admin">Program Administrators</a>. <br /><br />
			 If you are using the <a href="admin.php?page=icafe_vpp_new_program-Config">Automated Account Creation Utility</a> then you must enter the Program Manager accout credentials below.</p>
			
            <form method="post" id="vpp_pm_options">
                
                <fieldset class="options">
                  
                    <ul>
					  <li>
                            <label for="program_manager"><strong>Apple VPP Program Manager</strong></label>
                            <br />
________EOS;
                            wp_dropdown_users($args);
echo <<<________EOS
                            (Apple allows only ONE Program Manager for VPP) 
                            <br />
                            <em>Any users with contributor role or higher may be selected</em>
							<br />
							<br />
							
                        </li>
						<li>
                            <label for="program_manager_itunes_account"><strong>VPP Program Manager iTunes Username</strong></label>
                            <br />
                            <input type="text" name="program_manager_itunes_account" value="$program_manager_itunes_account" size="40"/>
                            (Account used to manage Apple VPP) 
                            <br />
                            <em>This is the iTunes account that can be used to create new program facilitators. This is a special account that cannot make purchases.</em>
							<br />
							<br />
                        </li>
						<li>
                            <label for="program_manager_itunes_pw"><strong>VPP Program Manager iTunes Password</strong></label>
                            <br />
                            <input type="text" name="program_manager_itunes_pw" value="$program_manager_itunes_pw" size="40"/>
                            (required if you use the auto account creation utiltiy) 
                            <br />
                            <em>Enter your Program Manager password here as a reminder only. Entering it here does not affect the actual password on the AppleID site.</em>
                        </li>
                    </ul>
                </fieldset>
				<input name="menu" type="hidden" value="settings" />
				 <p class="submit"><input type="submit" name="icafe_vpp_PMSave" value="Update Options &raquo;" /></p>
            </form>
		</div>
________EOS;

} else {
	echo <<<________EOS
	<h2>Apple VPP Program Manager</h2>
	<br />
	<strong>$program_manager_info->display_name</strong>
________EOS;
}
	
   
}


//Manage all program facilitators and their accounts
function icafe_vpp_program_facilitator_admin() {
	wp_vpp_check_access ();
	
	//DB funtions
	global $wpdb;
	
	
	//Is this the program manager or a facilitator?
	$program_manager_id = get_option('icafe_vpp_program_manager');
	$current_user = wp_get_current_user();
	
	
	if ($_GET['state'] == 'vouchers') {//show the pending vouchers
		$fid = $_GET['fid'];
		$owner_info = get_userdata($fid);
		$base_url = get_option('icafe_vpp_plugin_url');
		
		$voucher_table_name = $wpdb->prefix . "icafe_vpp_vouchers";
		$program_table_name = $wpdb->prefix . "icafe_vpp_programs";		
		$pending_vouchers = $wpdb->get_results("SELECT p.program_name, p.program_description, p.apple_id, p.password, v.vid, v.voucher, v.amount FROM $voucher_table_name v INNER JOIN $program_table_name p ON p.pid = v.pid WHERE p.facilitator = $fid AND v.voucher_status = 'pending'");
		$vouchers = '';
		foreach ($pending_vouchers as $pending_voucher) {
			
			$url = $base_url.'?wpVPP=voucher_approve&vpp_key='.$pending_voucher->vid.'&approve=FALSE';
			$vouchers .= '<tr>
							  <td style="text-align:left">'.$pending_voucher->program_name.'</td>
							  <td style="text-align:left">'.$pending_voucher->program_description.'</td>
							  <td style="text-align:center">'.$pending_voucher->voucher.'</td>
							  <td style="text-align:center">'.$pending_voucher->amount.'</td>
							  <td style="text-align:left">'.$pending_voucher->apple_id.'</td>
							  <td style="text-align:left">'.$pending_voucher->password.'</td>
							  <td style="text-align:center"><a href="'.$url.'">Redeem Voucher</a></td>
							</tr>';
		}
		
		//output the screen		
		echo <<<________EOS
			<div class="wrap">
				<h2>$owner_info->display_name Pending Vouchers</h2>
				<p></p>
				
				   <table class="widefat">
						  <thead>
							<tr>
							  <th style="text-align:left">Program Name</th>
							  <th style="text-align:left">Program Descripton</th>
							  <th style="text-align:center">Voucher</th>
							  <th style="text-align:center">Amount</th>
							  <th style="text-align:left">Apple ID</th>
							  <th style="text-align:left">Password</th>
							  <th style="text-align:center">Process</th>
							</tr>
						  </thead>
						  <tbody>
							 $vouchers
						  </tbody>
						</table>
					
			</div>
________EOS;

	} else if ($_GET['state'] == 'apps') {//show the pending apps
		$fid = $_GET['fid'];
		$owner_info = get_userdata($fid);
		$base_url = get_option('icafe_vpp_plugin_url');
		
		$app_table_name = $wpdb->prefix . "icafe_vpp_apps";
		$program_table_name = $wpdb->prefix . "icafe_vpp_programs";		
		$pending_apps = $wpdb->get_results("SELECT p.program_name, p.program_description, p.apple_id, p.password, a.aid, a.app, a.quantity FROM $app_table_name a INNER JOIN $program_table_name p ON p.pid = a.pid WHERE p.facilitator = $fid AND a.app_status = 'pending'");
	
		$apps = '';
		foreach ($pending_apps as $pending_app) {
			
			$url = $base_url.'?wpVPP=app_approve&app_key='.$pending_app->aid.'&approve=FALSE';
			$apps .= '<tr>
							  <td style="text-align:left">'.$pending_app->program_name.'</td>
							  <td style="text-align:left">'.$pending_app->program_description.'</td>
							  <td style="text-align:left">'.$pending_app->app.'</td>
							  <td style="text-align:center">'.$pending_app->quantity.'</td>
							  <td style="text-align:left">'.$pending_app->apple_id.'</td>
							  <td style="text-align:left">'.$pending_app->password.'</td>
							  <td style="text-align:center"><a href="'.$url.'">Purchase App</a></td>
							</tr>';
		}
		
		//output the screen		
		echo <<<________EOS
			<div class="wrap">
				<h2>$owner_info->display_name Pending App Requests</h2>
				<p></p>
				
				   <table class="widefat">
						  <thead>
							<tr>
							  <th style="text-align:left">Program Name</th>
							  <th style="text-align:left">Program Descripton</th>
							  <th style="text-align:left">App</th>
							  <th style="text-align:center">Quantity</th>
							  <th style="text-align:left">Apple ID</th>
							  <th style="text-align:left">Password</th>
							  <th style="text-align:center">Process</th>
							</tr>
						  </thead>
						  <tbody>
							 $apps
						  </tbody>
						</table>
					
			</div>
________EOS;


	} else if ($_GET['state'] == 'app_codes') {//show the purchased apps
		$fid = $_GET['fid'];
		$owner_info = get_userdata($fid);
		$base_url = get_option('icafe_vpp_plugin_url');
		
		$app_table_name = $wpdb->prefix . "icafe_vpp_apps";
		$program_table_name = $wpdb->prefix . "icafe_vpp_programs";		
		$purchased_apps = $wpdb->get_results("SELECT p.program_name, p.program_description, a.aid, a.app, a.publisher, a.quantity, a.cost FROM $app_table_name a INNER JOIN $program_table_name p ON p.pid = a.pid WHERE p.facilitator = $fid AND a.app_status = 'purchased'");
	
		$apps = '';
		foreach ($purchased_apps as $purchased_app) {
			
			$url = $base_url.'?wpVPP=app_approve&app_key='.$purchased_app->aid.'&codes=TRUE';
			$apps .= '<tr>
							  <td style="text-align:left">'.$purchased_app->program_name.'</td>
							  <td style="text-align:left">'.$purchased_app->program_description.'</td>
							  <td style="text-align:left">'.$purchased_app->app.'</td>
							  <td style="text-align:left">'.$purchased_app->publisher.'</td>
							  <td style="text-align:center">'.$purchased_app->quantity.'</td>
							  <td style="text-align:center">'.$purchased_app->cost.'</td>
							  <td style="text-align:center"><a href="'.$url.'">Download</a></td>
							</tr>';
		}
	
		//output the screen		
		echo <<<________EOS
			<div class="wrap">
				<h2>$owner_info->display_name Purchased Apps</h2>
				<p></p>
				
				   <table class="widefat">
						  <thead>
							<tr>
							  <th style="text-align:left">Program Name</th>
							  <th style="text-align:left">Program Descripton</th>
							  <th style="text-align:left">App</th>
							  <th style="text-align:left">Publisher</th>
							  <th style="text-align:center">Quantity</th>
							  <th style="text-align:center">Cost</th>
							  <th style="text-align:center">Codes</th>
							</tr>
						  </thead>
						  <tbody>
							 $apps
						  </tbody>
						</table>
					
			</div>
________EOS;


	} else {
		//Is this someone who can edit the program manager?
		if ($current_user->ID == $program_manager_id) { //is this the program manager?
			$facilitator_ids_array = get_users(array('meta_key' => 'facilitator', 'meta_value' => 'TRUE'));
		} else {
			$facilitator_ids_array = get_users(array('include' => $current_user->ID));
		}
	
		
		$facilitators = '';
		foreach ($facilitator_ids_array as $facilitator) {
			
			$fid = $facilitator->ID;
			//grab the count of programs they manage
			$table_name = $wpdb->prefix . "icafe_vpp_programs";		
			$program_count = $wpdb->get_var("SELECT COUNT(*) FROM $table_name WHERE facilitator = $fid");
			
			//grab the count of approved apps
			$app_table_name = $wpdb->prefix . "icafe_vpp_apps";
			$program_table_name = $wpdb->prefix . "icafe_vpp_programs";		
			$processed_apps = $wpdb->get_var("SELECT COUNT(*) FROM $app_table_name INNER JOIN $program_table_name ON $program_table_name.pid = $app_table_name.pid WHERE $program_table_name.facilitator = $fid AND $app_table_name.app_status = 'purchased'");
			
			//grab the count of pending vouchers
			$voucher_table_name = $wpdb->prefix . "icafe_vpp_vouchers";
			$program_table_name = $wpdb->prefix . "icafe_vpp_programs";		
			$pending_vouchers = $wpdb->get_var("SELECT COUNT(*) FROM $voucher_table_name INNER JOIN $program_table_name ON $program_table_name.pid = $voucher_table_name.pid WHERE $program_table_name.facilitator = $fid AND $voucher_table_name.voucher_status = 'pending'");
			
			//grab the count of pending app requests
			$app_table_name = $wpdb->prefix . "icafe_vpp_apps";
			$program_table_name = $wpdb->prefix . "icafe_vpp_programs";		
			$pending_apps = $wpdb->get_var("SELECT COUNT(*) FROM $app_table_name INNER JOIN $program_table_name ON $program_table_name.pid = $app_table_name.pid WHERE $program_table_name.facilitator = $fid AND $app_table_name.app_status = 'pending'");
			
			//var_dump( $wpdb->last_query );
			//var_dump ($wpdb->last_error);
			//exit;
					
			$facilitators .= '<tr>
							  <td style="text-align:left">'.$facilitator->display_name.'</td>
							  <td style="text-align:center"><a href="'.admin_url().'admin.php?page=icafe_vpp-Manage-VPP-Accounts&fid_filter='.$facilitator->ID.'">'.$program_count.'</a></td>
							  <td style="text-align:center"><a href="'.admin_url().'admin.php?page=icafe_vpp-Program-Facilitator-Admin&state=app_codes&fid='.$facilitator->ID.'">'.$processed_apps.'</a></td>
							  <td style="text-align:center"><a href="'.admin_url().'admin.php?page=icafe_vpp-Program-Facilitator-Admin&state=vouchers&fid='.$facilitator->ID.'">'.$pending_vouchers.'</a></td>
							  <td style="text-align:center"><a href="'.admin_url().'admin.php?page=icafe_vpp-Program-Facilitator-Admin&state=apps&fid='.$facilitator->ID.'">'.$pending_apps.'</a></td>
							</tr>';
		}
		
		//output the screen
		
		echo <<<________EOS
			<div class="wrap">
				<h2>VPP Program Administrators</h2>
				<p>Apple allows you to create as many Program Facilitators as needed. iCafe VPP Manager allows you to create a layer of Program Administrators that can each oversee many Program Facilitator Accounts.<br /> <br />
				
				This allows anyone with a Voucher to redeem the voucher, request apps, and track spending without providing the Program Facilitator credentials or access to the Education Portal to them.<br /><br />
				
				This allows a smaller group of Program Administrators to oversee all transactions. In effect, a Program Administrator acts on behalf of multiple Program Facilitators.<br /><br />
				New Program Administrators can be created from any Wordpress subscriber or higher. Simply check the VPP Program Administrator box in thier Wordpress user profile screen.</p>
				
				   <table class="widefat">
						  <thead>
							<tr>
							  <th style="text-align:center">Program Administrar</th>
							  <th style="text-align:center">Program Count</th>
							  <th style="text-align:center">Processed Apps</th>
							  <th style="text-align:center">Pending Vouchers</th>
							  <th style="text-align:center">Pending App Requests</th>
							</tr>
						  </thead>
						  <tbody>
							 $facilitators
						  </tbody>
						</table>
					
			</div>
________EOS;
	
	} 
	
}



//lookup all VPP accounts and process program creation requests
function icafe_vpp_manage_vpp_accounts() {
 	wp_vpp_check_access ();
	//DB funtions
	global $wpdb;
	$url = get_option('icafe_vpp_plugin_url');
	//check for filters
	if (isset($_GET['fid_filter'])) {
		$fid_filter = $_GET['fid_filter'];
	} else {
		$fid_filter = 0;
	}
	//did they approve a new program?
	if (isset($_POST['pid'])) {
		//grab the form values
		$pid = $_POST['pid'];
		$facilitator = $_POST[$pid.'|facilitator'];
		$apple_id = $_POST[$pid.'|apple_id'];
		$password = $_POST[$pid.'|password'];
		$owner = $_POST[$pid.'|owner'];
		$owner_info = get_userdata($owner);
		$facilitator_info = get_userdata($facilitator);
		//update the DB
		//$wpdb->show_errors();
		$table_name = $wpdb->prefix . "icafe_vpp_programs";			
		$wpdb->UPDATE($table_name, array('facilitator' => "$facilitator", 'apple_id' => "$apple_id", 'password' => "$password", 'status' => "approved"), array('pid' => "$pid"));

		echo '<div id="message" class="updated fade"><p>Your new changes were saved successfully.</p></div>';
		
		//Email facilitator that a program is setup and ready for a voucher to be redeemed
		//grab the information about the program and voucher
		$table_name = $wpdb->prefix . "icafe_vpp_vouchers";
		$vouchers = $wpdb->get_results("SELECT * FROM $table_name WHERE pid = $pid");
		
		foreach ($vouchers as $voucher_data) {
		
			$vpp_key = $voucher_data->vid;
			$url = $url.'?wpVPP=voucher_approve&vpp_key='.$vpp_key.'&approve=FALSE';
					$send_to = $owner_info->user_email;
					$body = '
								<h2><strong>Apple VPP Voucher Redemption Request</strong></h2>
								<p>'.$owner_info->display_name.' has requested a voucher redemption.</p>
								<p><strong>Apple ID: </strong><font color="568794">'.$apple_id.'</font><br>
								  <strong>Voucher Number: </strong><font color="568794">'.$voucher_data->voucher.'</font><br>
								  <strong>Voucher Amount: </strong><font color="568794">$'.$voucher_data->amount.'</font><br>
								</p>
								<strong>Step 1: <a href="https://volume.itunes.apple.com/WebObjects/MZFinance.woa/wa/login?cc=us">Click to Redeem VPP Voucher with Apple</a></strong><br>
								<br>
								<p><strong>Step 2: <a href="'.$url.'">Click to Process Voucher Redemption</a></strong></p>
								
								';
					//$attachments = array(WP_CONTENT_DIR . '/uploads/file_to_attach.zip');
					//send the email to program manager to alert to the new request
					add_filter('wp_mail_content_type',create_function('', 'return "text/html";'));
					//chris switch below
					//wp_mail('cnilsson@lcisd.org', 'VPP Voucher Request', $body);
					wp_mail($facilitator_info->user_email, 'VPP Voucher Request', $body);
					
		}
				
				
		
	}
	//Load all program facilitators	into a dropdown
	$facilitator_ids_array = get_users(array('meta_key' => 'facilitator', 'meta_value' => 'TRUE'));
	$facilitator_options = '';
	//var_dump($facilitator_ids_array);
	foreach ($facilitator_ids_array as $facilitator) {
		$facilitator_options .= '<option value="'.$facilitator->ID.'">'.$facilitator->display_name .'</option>';
	}
	
	//find programs
	//Is this the program manager or a facilitator?
	$program_manager_id = get_option('icafe_vpp_program_manager');
	$program_manager = get_userdata($program_manager_id);
	$current_user = wp_get_current_user();
	$table_name = $wpdb->prefix . "icafe_vpp_programs";
	$pending_programs = $wpdb->get_results("SELECT * FROM $table_name");
			
	$universal_pw = stripslashes(get_option('icafe_vpp_new_pf_universal_pw'));
	$email = stripslashes(get_option('icafe_vpp_new_pf_email'));
	
	
	$pending_rows = '';
	$approved_rows = '';
	$approved_title = 'Approved Program Facilitator Accounts';
	foreach ($pending_programs as $pending) {
		$user_info = get_userdata($pending->owner);
		$program_email = str_replace("*", $pending->pid, $email);
		if ($pending->status == 'pending') { //pending request
			if ($current_user->ID == $program_manager_id) { //only program manager can create accounts
				$pending_rows .= '<tr>
							  <td>'.$user_info->display_name.'</td>
							  <td>'.$pending->program_description.'</td>
							  <td>
								<select name="'.$pending->pid.'|facilitator">
								  <option value="select" selected>Select an Administrator</option>
								  '.$facilitator_options.'
								</select>
							  </td>
							  <td><input name="'.$pending->pid.'|apple_id" type="text" value="'.$program_email.'"/></td>
							  <td><input name="'.$pending->pid.'|password" type="text" value="'.$universal_pw.'"/><input name="'.$pending->pid.'|owner" type="hidden" value="'.$pending->owner.'" /></td>
							  <td style="text-align:center"><input id="'.$pending->pid.'" type="checkbox" value="1" class="approve_checkbox"/></td>
							  <td><button type="submit" value="'.$pending->pid.'" name="pid" id="'.$pending->pid.'_button" disabled="disabled">Approve</button></td>
							</tr>';
			} else {
				$pending_rows .= '<tr>
							  <td>'.$user_info->display_name.'</td>
							  <td>'.$pending->program_description.'</td>
							  <td>
								<select name="'.$pending->pid.'|facilitator" disabled="disabled">
								  <option value="select" selected>Select an Administrator</option>
								  '.$facilitator_options.'
								</select>
							  </td>
							  <td><input name="'.$pending->pid.'|apple_id" type="text" value="'.$program_email.'" disabled="disabled"/></td>
							  <td><input name="'.$pending->pid.'|password" type="text" value="'.$universal_pw.'" disabled="disabled"/><input name="'.$pending->pid.'|owner" type="hidden" value="'.$pending->owner.'" /></td>
							  <td style="text-align:center"><input id="'.$pending->pid.'" type="checkbox" value="1" class="approve_checkbox" disabled="disabled"/></td>
							  <td>Pending Approval by '.$program_manager->display_name.'</td>
							</tr>';
			}
			
			
		} else if ($pending->status == 'provisioned') { //provisioned request
		if ($current_user->ID == $program_manager_id) { //only program manager can create accounts
				$pending_rows .= '<tr>
							  <td>'.$user_info->display_name.'</td>
							  <td>'.$pending->program_description.'</td>
							  <td>
								<select name="'.$pending->pid.'|facilitator">
								  <option value="select" selected>Select an Administrator</option>
								  '.$facilitator_options.'
								</select>
							  </td>
							  <td>'.$pending->apple_id.'<input name="'.$pending->pid.'|apple_id" value="'.$pending->apple_id.'" type="hidden"/></td>
							  <td>'.$pending->password.'<input name="'.$pending->pid.'|password" value="'.$pending->password.'" type="hidden"/><input name="'.$pending->pid.'|owner" type="hidden" value="'.$pending->owner.'" /></td>
							  <td style="text-align:center"><input id="'.$pending->pid.'" type="checkbox" value="1" class="approve_checkbox" checked disabled="disabled"/></td>
							  <td><button type="submit" value="'.$pending->pid.'" name="pid">Approve</button></td>
							</tr>';
			} else {
				$pending_rows .= '<tr>
							  <td>'.$user_info->display_name.'</td>
							  <td>'.$pending->program_description.'</td>
							  <td>
								<select name="'.$pending->pid.'|facilitator" disabled="disabled">
								  <option value="select" selected>Select an Administrator</option>
								  '.$facilitator_options.'
								</select>
							  </td>
							  <td>'.$pending->apple_id.'<input name="'.$pending->pid.'|apple_id" type="hidden" value="'.$pending->apple_id.'"/></td>
							  <td>'.$pending->password.'<input name="'.$pending->pid.'|password" type="hidden" value="'.$pending->password.'"/><input name="'.$pending->pid.'|owner" type="hidden" value="'.$pending->owner.'" /></td>
							  <td style="text-align:center"><input id="'.$pending->pid.'" type="checkbox" value="1" class="approve_checkbox" checked disabled="disabled"/></td>
							  <td>Pending Approval by '.$program_manager->display_name.'</td>
							</tr>';
			}
		
		
	
		
		} else {//all approved programs
			$facilitator_info = get_userdata($pending->facilitator);
			$return_url = admin_url('admin.php?page=icafe_vpp-Manage-VPP-Accounts');
			if ($fid_filter <> 0) {
				if ($facilitator_info->ID == $fid_filter) {
					$approved_title = '	Approved Program Facilitator Accounts for '.$facilitator_info->display_name.' <a href="'.$return_url.'"> Show All</a>';
					$approved_rows .= '<tr>
								  <td>'.$user_info->display_name.'</td>
								  <td>'.$pending->program_description.'</td>
								  <td>'.$facilitator_info->display_name.'</td>
								  <td>'.$pending->apple_id.'</td>
								  <td>'.$pending->password.'</td>
								  <td>$'.$pending->balance.'</td>
								</tr>';
				}
			} else {
			
				if (($current_user->ID == $program_manager_id) || ($facilitator_info->ID == $current_user->ID)) {
					$approved_title = '	Approved Program Facilitator Accounts';
					$approved_rows .= '<tr>
								  <td>'.$user_info->display_name.'</td>
								  <td>'.$pending->program_description.'</td>
								  <td>'.$facilitator_info->display_name.'</td>
								  <td>'.$pending->apple_id.'</td>
								  <td>'.$pending->password.'</td>
								  <td>$'.$pending->balance.'</td>
								</tr>';
				}
			}
		}
		
	}	
	//output the screen
	
    echo <<<________EOS
		<div class="wrap">
            <h2>VPP Program Facilitator Accounts</h2>
			<p>When a user redeems a voucher for the first time a new Program Facilitator account is requested.<br /><br />
			The <a href="admin.php?page=icafe_vpp-Program-Manager-Admin">Program Manager</a> must create those accounts in the <a target="_new" href="https://daw.apple.com/cgi-bin/WebObjects/DSAuthWeb.woa/wa/login?appIdKey=ad21ac3831eec18ea9d3b7fd7619dfbcfb384492fb562e03883fc63b673b938e&path=/asvpp_manager/index.php">"Apple VPP for Education Account Manager Portal"</a> and then assign that new account to an iCafe VPP Program Administrator. New Program Administrators can be created from any Wordpress subscriber or higher. Simply check the VPP Program Administrator box in thier Wordpress user profile screen.<br /><br />
			Once you have created the Account with Apple and selected a Program Administrator you can approve the account. From this point forward the Program Administrator will provide all service to this account.<br /><br />
			You can also use the <a href="admin.php?page=icafe_vpp_new_program-Config">Automated Account Creation Utility</a> to create all Facilitator Accounts within your Apple Education Portal. After running the utility you simply need to assign the Program Administrator and approve the account below.
			
			</p>
               <legend><strong><u>Pending Program Facilitator Creation Requests</u></strong></legend>  
			   <form method="post" id="vpp_program_approve">
                   <table class="widefat">
					  <thead>
						<tr>
						  <th>Requestor</th>
						  <th>Location</th>
						  <th>Administrator</th>
						  <th>iTunes Login</th>
						  <th>iTunes Password</th>
						  <th>Apple Account Created</th>
						  <th>Approve</th>
						</tr>
					  </thead>
					  <tbody>
						 $pending_rows
						 </tbody>
					</table>
				</form>
				
			
			<p>&nbsp;</p>	        
			<p>&nbsp;</p>
			
               <legend><strong><u>$approved_title</u></strong></legend>  
			  
                   <table class="widefat">
					  <thead>
						<tr>
						  <th>Requestor</th>
						  <th>Location</th>
						  <th>Administrator</th>
						  <th>iTunes Login</th>
						  <th>iTunes Password</th>
						  <th>Approximate Balance</th>
						</tr>
					  </thead>
					  <tbody>
						 $approved_rows
						 </tbody>
					</table>
				
		</div>
________EOS;
	
}

//manage global iTunes account
function icafe_vpp_manage_globle_itunes_accounts() {
	wp_vpp_check_access ();
	global $title;
	$program_manager_id = get_option('icafe_vpp_program_manager');
	$program_manager = get_userdata($program_manager_id);
	$program_manager_name = $program_manager->display_name;
	//grab the current settings  
	$global_appleid = stripslashes(get_option('icafe_vpp_global_itunes'));
	$global_appleid_current_pw = stripslashes(get_option('icafe_vpp_current_appleid_pw'));
	$global_appleid_sec1 = get_option('global_appleid_sec1');
	$global_appleid_sec2 = get_option('global_appleid_sec2');
	$global_appleid_sec3 = get_option('global_appleid_sec3');
	$global_appleid_sec1_answer = get_option('global_appleid_sec1_answer');
	$global_appleid_sec2_answer = get_option('global_appleid_sec2_answer');
	$global_appleid_sec3_answer = get_option('global_appleid_sec3_answer');
	$change_interval = get_option('global_appleid_change_interval');


	if ($_POST) {//update options
			echo '<div id="message" class="updated fade"><p>Your new settings were saved successfully.</p></div>';
			if ($_POST['menu'] == 'settings') {
			
				$global_appleid = $_POST['global_appleid'];
				update_option('icafe_vpp_global_itunes', $global_appleid);
				$global_appleid_current_pw = $_POST['global_appleid_current_pw'];
				update_option('icafe_vpp_current_appleid_pw', $global_appleid_current_pw);
				$global_appleid_sec1 = $_POST['global_appleid_sec1'];
				update_option('global_appleid_sec1', $global_appleid_sec1);
				$global_appleid_sec2 = $_POST['global_appleid_sec2'];
				update_option('global_appleid_sec2', $global_appleid_sec2);
				$global_appleid_sec3 = $_POST['global_appleid_sec3'];
				update_option('global_appleid_sec3', $global_appleid_sec3);
				$global_appleid_sec1_answer = $_POST['global_appleid_sec1_answer'];
				update_option('global_appleid_sec1_answer', $global_appleid_sec1_answer);
				$global_appleid_sec2_answer = $_POST['global_appleid_sec2_answer'];
				update_option('global_appleid_sec2_answer', $global_appleid_sec2_answer);
				$global_appleid_sec3_answer = $_POST['global_appleid_sec3_answer'];
				update_option('global_appleid_sec3_answer', $global_appleid_sec3_answer);
				$change_interval = $_POST['change_interval'];
				update_option('global_appleid_change_interval', $change_interval);
				
				//$hash = $_POST['hash'];
				
				//$hash_output = base64_decode($hash);
			
			}
			
	}
	
	$current_user = wp_get_current_user();
	
	switch ($global_appleid_sec1) {
		
		case "best friend":
			$qa1 = 'selected="selected"';
			break;
		case "first pet":
			$qa2 = 'selected="selected"';
			break;
		case "learned to cook":
			$qa3 = 'selected="selected"';
			break;
		case "first film":
			$qa4 = 'selected="selected"';
			break;
		case "flew on a plane":
			$qa5 = 'selected="selected"';
			break;
		case "elementary school teacher":
			$qa6 = 'selected="selected"';
			break;
		
	}
	
	switch ($global_appleid_sec2) {
		
		case "first car":
			$qb1 = 'selected="selected"';
			break;
		case "dream job":
			$qb2 = 'selected="selected"';
			break;
		case "children’s book":
			$qb3 = 'selected="selected"';
			break;
		case "childhood nickname":
			$qb4 = 'selected="selected"';
			break;
		case "character in school":
			$qb5 = 'selected="selected"';
			break;
		case "favorite singer":
			$qb6 = 'selected="selected"';
			break;
		
	}
	
	switch ($global_appleid_sec3) {
		
		case "favorite sports team":
			$qc1 = 'selected="selected"';
			break;
		case "parents meet":
			$qc2 = 'selected="selected"';
			break;
		case "first boss":
			$qc3 = 'selected="selected"';
			break;
		case "name of the street":
			$qc4 = 'selected="selected"';
			break;
		case "first beach you visited":
			$qc5 = 'selected="selected"';
			break;
		
	}

	//Is this the program manager?
	if ($current_user->ID == $program_manager_id) {
	$images_dir = network_site_url( '/' ).'wp-content/plugins/icafe-vpp-manager/css/img';
		echo <<<________EOS
			<div class="wrap">
				<h2>Enterprise iTunes Account Information</h2>
				<p>
				One of the most frustrating issues with the Apple approach to enterprise purchases is that you are encouraged to give the apps away and repurchase as needed. Apple does allow for an enterprise to maintain ownership as long as the codes are redeemed using an enterprise owned iTunes account. iOS devices allow apps to be loaded under an unlimited number of iTunes accounts and as of iOS 6.0+ the users do not need to provide credentials for each account used during app updates. They simply need to sign out of their personal iTunes account and sign in with an enterprise account before redeeming a VPP code. They can then sign back in with their personal account and continue to use and update thier device as normal.<br /><br />
				Should you choose to use this feature, iCafe VPP Manager provides a secure way to display the enterprise iTunes credentials to approved organizational members (Wordpress users with subscriber or higher rights). Simply fill out section one below to enable this feature.<br /><br />
				As an added security feature, iCafe VPP Manager can change your enterprise password at any interval you decide. Simply fill out section two and download the utility below on a Windows computer. Then, using Windows Scheduler, set the utility to run as often as you like!
				</p>
				<form method="post" id="vpp_appleid">
					
					<fieldset class="options">
						<ul>
						<h3>Section One</h3>
						<em>Required to display account credentials to authenticated organization members</em>
						<br />
						<br />
						  <li>
								<label for="global_appleid"><strong>Global iTunes Apple ID</strong></label>
								<br />
								<input type="text" name="global_appleid" value="$global_appleid" size="40"/>
								(What is the AppleID to use for VPP Code Redemption) 
								<br />
								<em>Do you have a single account for all VPP codes to be redeemed under? This allows the district/company to maintain ownership of apps.<br />
								<strong>Leave blank to allow users to redeem codes using personal iTunes accounts</strong></em>
							</li>
							<li>
							<br />
								<label for="global_appleid_current_pw"><strong>Current Password</strong></label>
								<br />
								<input type="text" name="global_appleid_current_pw" value="$global_appleid_current_pw" size="40"/>
								 (What is the Password to use for VPP Code Redemption) 
								<br />
								<em>If you are using the automated password changing feature this field tracks the current password.<br />
								If they become out of sync, simply login to your Apple ID site and reset the password. Be sure to update this field so the automated system can function.</em>
							</li>
							<br />
							<h3>Section Two</h3>
							<em>Required only if you are using the automated password changing script</em>
							<br />
							<br />
							<li>
								<label><strong>Apple ID Security Questions</strong> <em>answers must match the Apple ID site exactly</em></label>
								<br />
								<br />
								<select name="global_appleid_sec1">
									<option value="0">Please select</option>
									<option value="best friend" $qa1>What is the first name of your best friend in high school?</option>
									<option value="first pet" $qa2>What was the name of your first pet?</option>
									<option value="learned to cook" $qa3>What was the first thing you learned to cook?</option>
									<option value="first film" $qa4>What was the first film you saw in the theater?</option>
									<option value="flew on a plane" $qa5>Where did you go the first time you flew on a plane?</option>
									<option value="elementary school teacher" $qa6>What is the last name of your favorite elementary school teacher?</option>
								</select>							
								<br />
								Answer <input type="text" name="global_appleid_sec1_answer" value="$global_appleid_sec1_answer" size="40"/>
								<br />
								<br />
								<select name="global_appleid_sec2">
									<option value="0">Please select</option>
									<option value="first car" $qb1>What was the model of your first car?</option>
									<option value="dream job" $qb2>What is your dream job?</option>
									<option value="children’s book" $qb3>What is your favorite children’s book?</option>
									<option value="childhood nickname" $qb4>What was your childhood nickname?</option>
									<option value="character in school" $qb5>Who was your favorite film star or character in school?</option>
									<option value="favorite singer" $qb6>Who was your favorite singer or band in high school?</option>
								</select>							
								<br />
								Answer <input type="text" name="global_appleid_sec2_answer" value="$global_appleid_sec2_answer" size="40"/>
								<br />
								<br />
								<select name="global_appleid_sec3">
									<option value="0">Please select</option>
									<option value="favorite sports team" $qc1>What is the name of your favorite sports team?</option>
									<option value="parents meet" $qc2>In what city did your parents meet?</option>
									<option value="first boss" $qc3>What was the first name of your first boss?</option>
									<option value="name of the street" $qc4>What is the name of the street where you grew up?</option>
									<option value="first beach you visited" $qc5>What is the name of the first beach you visited?</option>
								</select>							
								<br />
								Answer <input type="text" name="global_appleid_sec3_answer" value="$global_appleid_sec3_answer" size="40"/>
								
							</li>
							 <li>
								<label for="change_interval"><strong>How often does this password change?</strong></label>
								<br />
								<input type="text" name="change_interval" value="$change_interval" size="5"/>
								hour(s)
								<br />
								<em>Schedule the Password Change Utility below to run on a Windows PC at this interval.</em>
							</li>
						</ul>
					</fieldset>
					<input name="menu" type="hidden" value="settings" />
					 <p class="submit"><input type="submit" name="icafe_vpp_AppleISave" value="Update Options &raquo;" /></p>
				</form>
			</div>
			<br />
			<br />
			<div style="float:left">
			<a href="http://chrisnilsson.com/downloads/iCafe_VPP_Automation.zip">
   				<img src="$images_dir/utilities.gif" />
			</a>
			</div>
			<a href="http://chrisnilsson.com/downloads/iCafe_VPP_Automation.zip">
  				 <h3>Download Automation Utilities version 1.1 (requires Windows Computer)</h3>
 			</a> 
________EOS;
	} else {
		echo <<<________EOS
			<div class="wrap">
				<h2>Enterprise iTunes Account Information</h2>
				Only $program_manager_name can edit
				<form method="post" id="vpp_appleid">
	
					<fieldset class="options">
						<ul>
						  <li>
								<label for="global_appleid"><strong>Global iTunes Apple ID</strong></label>
								<br />
								<input type="text" name="global_appleid" value="$global_appleid" size="40" disabled="disabled"/>
								(What is the AppleID for VPP Purchases) 
								<br />
								<em>Do you have a single account for all VPP codes to be redeemed under? This allows the district/company to maintain ownership of apps</em>
							</li>
							<li>
								<label for="global_appleid_current_pw"><strong>Current Password</strong></label>
								<br />
								<input type="text" name="global_appleid_current_pw" value="$global_appleid_current_pw" size="40" disabled="disabled"/>
								 
								<br />
								<em>If you are using the automated password changing feature this is the current password. </em>
							</li>
							
						</ul>
					</fieldset>
					<input name="menu" type="hidden" value="settings" />
					 
				</form>
			</div>
________EOS;
	}
}

function icafe_vpp_config () {
	wp_vpp_check_access ();
	global $title;
	$program_manager_id = get_option('icafe_vpp_program_manager');
	$program_manager = get_userdata($program_manager_id);
	$program_manager_name = $program_manager->display_name;
	
	
	
	if ($_POST) {//update options

			if ($_POST['config'] == 'update') {
				echo '<div id="message" class="updated fade"><p>Your new settings were saved successfully.</p></div>';
				$program_descriptions = $_POST['detail_options'];
				$program_description_lable = $_POST['detail_name'];
				update_option('icafe_vpp_program_descriptions', $program_descriptions);
				update_option('icafe_vpp_program_description_lable', $program_description_lable);
				
			
			}
			
	}

	//grab the current settings  
	$program_descriptions = stripslashes(get_option('icafe_vpp_program_descriptions'));
	$program_description_lable = stripslashes(get_option('icafe_vpp_program_description_lable'));
	$current_user = wp_get_current_user();
	

	//Is this the program manager?
	if ($current_user->ID == $program_manager_id) {
		 echo <<<________EOS
		<div class="wrap">
            <h2>iCafe Self_Serve Configuration</h2>
			<p>
			Using the iCafe VPP Manager you can allow any WordPress Subscriber or higher to redeem Apple VPP Vouchers and request App purcheses while maintaining account security and an audit trail of purchases and ownership.<br /><br />
			Part of this process involves users "redeeming" their vouchers within the iCafe VPP managment system and the <a href="admin.php?page=icafe_vpp-Program-Manager-Admin">Program Manager</a> assigning a Program Administrator to oversee the account. Sometimes it is difficult for the Program Manager to select the correct Program Administrator based only on the name of the requester.<br /><br />
			Use the form below to ask for additional information from the person "redeeming" a voucher. Leave blank if you do not wish to collect any additional information.
			</p>			
            <form method="post" id="vpp_config_options">
                
                <fieldset class="options">
                  
                    <ul>
					  
						<li>
                            <label for="detail_name"><strong>Additional Detail Title</strong></label>
                            <br />
                            <input type="text" name="detail_name" value="$program_description_lable" size="40"/>
                            
                            <br />
                            <em>Are you asking for Location, Building, Department, etc. Label the dropdown users will see.</em>
                        </li>
						<li>
                            <label for="detail_options"><strong>Additional Detail Options</strong></label>
                            <br />
							<textarea name="detail_options" cols="20" rows="10">$program_descriptions</textarea>
                             
                            <br />
                            <em>Enter each additional detail option on a new line, use an astrick for group headings<br /><br />
							Example:<br />
							<br />
							Please Select One<br />
							*High Schools<br />
							Austin<br />
							Travis<br />
							Bush<br />
							*Middle Schools<br />
							Smith<br />
							Rogers<br />
							Kline</em>
							<br />
							<br />
                        </li>
						<h3>Additional Detail Preview</h3>
						<li>
                            <label for="description_demo"><strong>$program_description_lable</strong></label>
                            <br />
								<select name="description_demo">
								
________EOS;
                           echo icafe_vpp_dropdown_from_textarea($program_descriptions);
echo <<<________EOS

								</select>
                            (What your users see. Can be edited above) 
                            <br />
                            <em>Any members of your WordPress site can enter a voucher for redemption. Use this field to ask them for additional data. Perhaps a building, location, or department. This allows the Program Manager to better assign a Program Administrator to the new VPP account.</em>
							<br />
							<br />
							
                        </li>
						
                    </ul>
                </fieldset>
				<input name="config" type="hidden" value="update" />
				 <p class="submit"><input type="submit" name="icafe_vpp_PMSave" value="Update Options &raquo;" /></p>
            </form>
		</div>
________EOS;
	} else {
		echo 'no';
	}
}

function icafe_vpp_new_program_config() {
	
		wp_vpp_check_access ();
	global $title;
	$program_manager_id = get_option('icafe_vpp_program_manager');
	$program_manager = get_userdata($program_manager_id);
	$program_manager_name = $program_manager->display_name;
	/*
add_option('icafe_vpp_new_pf_universal_pw', "");
add_option('icafe_vpp_new_pf_month', "");
add_option('icafe_vpp_new_pf_day', "");
add_option('icafe_vpp_new_pf_year', "");
add_option('icafe_vpp_new_pf_sec_q', "");
add_option('icafe_vpp_new_pf_sec_a', "");
icafe_vpp_new_pf_email
  */
	
	
	if ($_POST) {//update options

			if ($_POST['config'] == 'update') {
				echo '<div id="message" class="updated fade"><p>Your new settings were saved successfully.</p></div>';

				$email = $_POST['email'];
				$universal_pw = $_POST['universal_pw'];
				$reset_month = $_POST['reset_month'];
				$reset_day = $_POST['reset_day'];
				$reset_year = $_POST['reset_year'];
				$reset_question = $_POST['reset_question'];
				$reset_answer = $_POST['reset_answer'];
			
				update_option('icafe_vpp_new_pf_email', $email);
				update_option('icafe_vpp_new_pf_universal_pw', $universal_pw);
				update_option('icafe_vpp_new_pf_month', $reset_month);
				update_option('icafe_vpp_new_pf_day', $reset_day);
				update_option('icafe_vpp_new_pf_year', $reset_year);
				update_option('icafe_vpp_new_pf_sec_q', $reset_question);
				update_option('icafe_vpp_new_pf_sec_a', $reset_answer);
				
			
			}
			
	}

	//grab the current settings  
	$email = stripslashes(get_option('icafe_vpp_new_pf_email'));
	$universal_pw = stripslashes(get_option('icafe_vpp_new_pf_universal_pw'));
	$reset_month = get_option('icafe_vpp_new_pf_month');
	$reset_day = get_option('icafe_vpp_new_pf_day');
	$reset_year = get_option('icafe_vpp_new_pf_year');
	$reset_question = stripslashes(get_option('icafe_vpp_new_pf_sec_q'));
	$reset_answer = stripslashes(get_option('icafe_vpp_new_pf_sec_a'));
	
	$current_user = wp_get_current_user();
	
	$images_dir = network_site_url( '/' ).'wp-content/plugins/icafe-vpp-manager/css/img';
	//Is this the program manager?
	if ($current_user->ID == $program_manager_id) {
		 echo <<<________EOS
		<div class="wrap">
            <h2>Apple VPP New Program Facilitator Account Configuration</h2>
				Below you can configure the options for creating new program facitator accounts. These options are only required if you are using the automatic account creation utility. If you are manually creating accounts then the settings below are optional but may still be helpful for generating unique email addresses to use as an AppleID.
            <form method="post" id="vpp_new_pf_config_options">
                
                <fieldset class="options">
                  
                    <ul>
					  
						<li>
                            <label for="email"><strong>New Program Facilitator Email Template</strong></label>
                            <br />
                            <input type="text" name="email" value="$email" size="40"/> Ex: VPP_*@npisd.edu (* will be replaced automatically with an account ID)
                            
                            <br />
                            <em>Program Facilitator accounts must use an email address to login to the Apple Education VPP portal but the email addresses DO NOT need to be functional</em>
							<br />
							<br />
                        </li>
						<li>
                            <label for="universal_pw"><strong>New Program Facilitator Universal Password</strong></label>
                            <br />
                            <input type="text" name="universal_pw" value="$universal_pw" size="40"/> Password is only visible to your designated Program Administrators
                            
                            <br />
                            <em>Leave this blank if you want to assign unique passwords to each Program Facilitator account. You will still be able to use the automatic account creation utility.</em>
							<br />
							<br />
							<br />
							
                        </li>
						<h3>Program Facilitar Account Recovery Options</h3>
						All new Program Facilitator Accounts created using the auto account creation utility will use the following information for password resets and proof of ownership with Apple
						<br />
						<br />
						<li>
                            <label for="reset_month"><strong>Birthday</strong></label>
                            <br />
                            <select name="reset_month" id="reset_month" class="month" escapehtml="false">
							  <option value="0" selected="selected">Month</option>
________EOS;
                           echo gw_bd_dropdown($reset_month, 'month');
echo <<<________EOS
							 
							</select>
							
							
							<select name="reset_day" id="reset_day" class="date" escapehtml="false">
							  <option value="0" selected="selected">Day</option>
________EOS;
                           echo gw_bd_dropdown($reset_day, 'day');
echo <<<________EOS
							</select>
							
							
							<select name="reset_year" id="reset_year" class="year" escapehtml="false">
							  <option value="0" selected="selected">Year</option>
________EOS;
                           echo gw_bd_dropdown($reset_year, 'year');
echo <<<________EOS
							</select>

                            
                            
                            
							<br />
							<br />
                        </li>
						<li>
                            <label for="reset_question"><strong>Security Question</strong></label>
                            <br />
                            <input type="text" name="reset_question" value="$reset_question" size="80"/> 
                            
                            <br />
                            <em>What do you want to be asked when claiming ownership of Program Facilitator accounts with Apple?</em>
							<br />
							<br />
                        </li>
						<li>
                            <label for="reset_answer"><strong>Answer</strong></label>
                            <br />
                            <input type="text" name="reset_answer" value="$reset_answer" size="40"/>
                            
                            </em>
							<br />
							<br />
                        </li>
                    </ul>
                </fieldset>
				<input name="config" type="hidden" value="update" />
				 <p class="submit"><input type="submit" name="icafe_vpp_PMSave" value="Update Options &raquo;" /></p>
            </form>
		</div>
			<br />
			<br />
			<div style="float:left">
			<a href="http://chrisnilsson.com/downloads/iCafe_VPP_Automation.zip">
   				<img src="$images_dir/utilities.gif" />
			</a>
			</div>
			<a href="http://chrisnilsson.com/downloads/iCafe_VPP_Automation.zip">
  				 <h3>Download Automation Utilities version 1.1 (requires Windows Computer)</h3>
 			</a> 
________EOS;
	} else {
		echo 'no';
	}
	
	
}

//build the program facilitator bd dropdowns
function gw_bd_dropdown($current_option, $name) {
	
	switch ($name) {
		
		case "month":
		$options_arr = array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');			
			break;
		case "day":
			$options_arr = array('14', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12', '13', '14', '15', '16', '17', '18', '19', '20', '21', '22', '23', '24', '25', '26', '27', '28', '29', '30', '31');
			break;
		case "year":
			$options_arr = array('2013', '2012', '2011', '2010', '2009', '2008', '2007', '2006', '2005', '2004', '2003', '2002', '2001', '2000', '1999', '1998', '1997', '1996', '1995', '1994', '1993', '1992', '1991', '1990', '1989', '1988', '1987', '1986', '1985', '1984', '1983', '1982', '1981', '1980', '1979', '1978', '1977', '1976', '1975', '1974', '1973', '1972', '1971', '1970', '1969', '1968', '1967', '1966', '1965', '1964', '1963', '1962', '1961', '1960', '1959', '1958', '1957', '1956', '1955', '1954', '1953', '1952', '1951', '1950', '1949', '1948', '1947', '1946', '1945', '1944', '1943', '1942', '1941', '1940', '1939', '1938', '1937', '1936', '1935', '1934', '1933', '1932', '1931', '1930', '1929', '1928', '1927', '1926', '1925', '1924', '1923', '1922', '1921', '1920', '1919', '1918', '1917', '1916', '1915', '1914', '1913', '1912', '1911', '1910', '1909', '1908', '1907', '1906', '1905', '1904', '1903', '1902', '1901');
			break;
			
	}

	//build dropdown options
	$options = '';
	foreach ($options_arr as &$option) {
    	 $s = ($current_option == $option)? ' selected="selected"' : '';
		 $options .= '<option  value="'.$option.'" '.$s.'>'.$option.'</option>';
	}

	return $options;
}


function icafe_vpp_dropdown_from_textarea($textarea) { //convert a textarea into a dropdown element
$selection = explode("\n", $textarea);
reset($selection);
$selection_list = '';
foreach($selection as $option) {
	if(substr($option,0,1) == '*')
	 {
	  $option= ltrim ($option,'*');
	  $selection_list .= '<optgroup label="'.$option.'">';
	 } else {
  		$selection_list .= '<option value="'.$option.'">'.$option.'</option>';
	 }
}
return $selection_list;
}

function wp_vpp_check_access () {
	$program_manager_id = get_option('icafe_vpp_program_manager');
	$current_user = wp_get_current_user();
	$test = get_user_meta($current_user->ID, 'facilitator', true);
	
	if ((get_user_meta($current_user->ID, 'facilitator', true) == 'true') || ($current_user->ID == $program_manager_id)) {

	} else {
			echo '<h2>Apple Volume Purchase Program Admin Section</h2>This section is for Approved users only. Please contact your system adminstrator if you believe this message to be an error.';
	exit;
	}
	
}

function wp_vpp_check_admin_access () {
	$program_manager_id = get_option('icafe_vpp_program_manager');
	$current_user = wp_get_current_user();
	
	if ((get_user_meta($current_user->ID, 'facilitator', true)) || ($current_user->ID == $program_manager_id) || (current_user_can('manage_options'))) {

	} else {
			echo '<h2>Apple Volume Purchase Program Admin Section</h2>This section is for Approved users only. Please contact your system adminstrator if you believe this message to be an error.';
	exit;
	}
	
}

//control what users can be program faciltators
function icafe_vpp_add_custom_user_profile_fields( $user ) {
    ?>
    <h3><?php _e('iCafe VPP Manager Settings', 'your_textdomain'); ?></h3>
    <table class="form-table">
    	<tr>
    		<th>
    			<label for="facilitator"><?php _e('Program Administrator', 'your_textdomain'); ?>
    			</label>
            </th>
    		<td>
    			<input type="checkbox" name="facilitator" id="facilitator" value="true" <?php if (esc_attr( get_the_author_meta( "facilitator", $user->ID )) == "true") echo "checked"; ?> />
          		<label for="show_email">User can be an Apple VPP Program Administrator</label> 
    		</td>
    	</tr>
    </table>
    <?php 
}
//save faclitator settings
function icafe_vpp_save_custom_user_profile_fields( $user_id ) {
    if ( !current_user_can( 'edit_user', $user_id ) )
   		 return FALSE;
	if (!isset($_POST['facilitator'])) $_POST['facilitator'] = "false"; 
    update_usermeta( $user_id, 'facilitator', $_POST['facilitator'] );
    }
 
function icafe_vpp_restricted() {
	if ($_GET['wpVPP']) {//OK...we are not showing the first page

		//must login to view pages
		if (!is_user_logged_in()) { 
			auth_redirect(); 
		}
	}
}

?>