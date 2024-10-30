// JavaScript Document
jQuery(document).ready(function($) {
	$.validator.addMethod('regexp', function(value, element, param) {
        return this.optional(element) || value.match(param);
    },
    'This value doesn\'t match the acceptable pattern.');
	
 	$('#redeem').validate({
		rules: {
			code: {
				required: true,
				minlength: 16,
				maxlength: 16,
					regexp: /^[A-Za-z0-9]+$/i
			}
		},
		messages: {
			code: "Please enter a valid Apple voucher code.",
		}
	});
	
	
	$('#new_program').validate({
		rules: {
			description: {
				required: true,
			}
		},
		messages: {
			description: "Please enter a description for this program.",
		}
	});
	
	$('#app_request').validate({
		rules: {
			app_name: {
				required: true,
			},
			publisher: {
				required: true,
			},
			quantity: {
				required: true,
			},
			url: {
				required: true,
				url: true
			}
		},
		messages: {
			url: "Please copy the URL from the Apple Education Store.",
		}
	});
	
	$('#app_approve').validate({
		rules: {
			cost: {
				required: true,
			},
			balance: {
				required: true,
			},
			codes: {
				required: true,
				
			}
		}
	});
	
	
	$(".approve_checkbox").click(function() {
		alert('here');
  		$("#108_button").attr("disabled", !this.checked);
	});
	
});