<?php
if (!defined('WP_UNINSTALL_PLUGIN'))

	exit();

//Remove all options and tables

global $wpdb;

  delete_option("icafe_vpp_version");
	
  delete_option("icafe_vpp_program_manager");
  delete_option("icafe_vpp_program_manager_itunes_account");
  delete_option("icafe_vpp_program_manager_itunes_pw");
  delete_option("icafe_vpp_use_single_facilitator_pw");
  delete_option("icafe_vpp_facilitator_pw");
  delete_option("icafe_vpp_facilitator_ids");
  delete_option("icafe_vpp_plugin_url");
  delete_option("icafe_vpp_global_itunes");
  delete_option("icafe_vpp_current_appleid_pw");
  delete_option('icafe_vpp_program_descriptions');
  delete_option('icafe_vpp_program_description_lable');
  delete_option('global_appleid_sec1');
  delete_option('global_appleid_sec2');
  delete_option('global_appleid_sec3');
  delete_option('global_appleid_sec1_answer');
  delete_option('global_appleid_sec2_answer');
  delete_option('global_appleid_sec3_answer');
  delete_option('icafe_vpp_new_pf_email');
  delete_option('icafe_vpp_new_pf_universal_pw');
  delete_option('icafe_vpp_new_pf_month');
  delete_option('icafe_vpp_new_pf_day');
  delete_option('icafe_vpp_new_pf_year');
  delete_option('icafe_vpp_new_pf_sec_q');
  delete_option('icafe_vpp_new_pf_sec_a');
  delete_option('global_appleid_change_interval');
  
  	global $wpdb;
    $table = $wpdb->prefix . "icafe_vpp_apps";
	$wpdb->query("DROP TABLE IF EXISTS $table");
	$table = $wpdb->prefix . "icafe_vpp_programs";
	$wpdb->query("DROP TABLE IF EXISTS $table");
	$table = $wpdb->prefix . "icafe_vpp_vouchers";
	$wpdb->query("DROP TABLE IF EXISTS $table");
?>