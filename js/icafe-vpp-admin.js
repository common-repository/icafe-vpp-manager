// JavaScript Document
jQuery(document).ready(function($) {

	
	//enable approve button when provisioned is checked
	$(".approve_checkbox").click(function() {
		 var box_id=$(this).attr('id');
  		$("#"+box_id+"_button").attr("disabled", !this.checked);
	});
	
});