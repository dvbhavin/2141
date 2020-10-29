/* swal functions defaults */
var swal_title = {
	'error': 'Error!',
	'success': 'Success !!',
	'warning': 'Warning!',
	'info': 'Information',
	'question': 'Are you sure?'
};

var swal_confirmButtonColor = {
	'error': '#f44336',
	'success': '#4caf50',
	'warning': '#ff9800',
	'info': '#00bcd4',
	'question': '#87adbd'
};

var swal_confirmButtonClass = {
	'error': 'danger',
	'success': 'success',
	'warning': 'warning',
	'info': 'info',
	'question': 'default'
};

/* Notify functions defaults */
var notify_icon_material = {
	'danger': 'error',
	'success': 'check',
	'warning': 'warning',
	'info': 'info'
};

var notify_title = {
	'danger': 'Error!',
	'success':'Success !!',
	'warning':'Warning!',
	'info':'Info..'
};

/* common validation function function_name */
function validateName(name){
	var nameReg = /^[a-zA-Z\'\-\s]+$/;
	return nameReg.test(name);
}

function validateNumber(num){
	var numReg = /^[0-9]+$/;
	return numReg.test(num);
}

function validateMobile(num){
	var mobReg = /^\d{10}$/;
	return mobReg.test(num);
}

function validateLandline(num){
	var mobReg = /^\d{12}$/;
	return mobReg.test(num);
}

function validateOtp(num){
	var otpReg = /^\d{6}$/;
	return otpReg.test(num);
}

function validateEmail(email) {
	var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
	return emailReg.test(email);
}

function validateGST(num) {
    var gstReg = /^\d{15}$/;
    return gstReg.test(num);
}

function validateNumSpcl(num) {
	var Numreg = /[^0-9\.]/g;
	//var Numreg = /^[0-9-+()]*$/;
	return Numreg.test(num);
}
function validateSplChr(name) {
	var nameReg = /[~!@#$^&*()_|+\-=?;:<>\{\}\[\]\\\/]/g;
	return nameReg.test(name);
}

function vaidateDecimal(num) {
 	var Decimalreg = /^[0-9]+([.][0-9]+)?$/g;
 	return Decimalreg.test(num);
}

function validateMultiEmail(email) {
	var emailMulReg = /^(\w+((-\w+)|(\.\w+))*\@[A-Za-z0-9]+((\.|-)[A-Za-z0-9]+)*\.[A-Za-z0-9]{2,4}\s*?,?\s*?)+$/;
	return emailMulReg.test(email);
}

function validateUrl(url) {
 	var urlReg = /^(http|https|ftp):\/\/[a-z0-9]+([\-\.]{1}[a-z0-9]+)*\.[a-z]{2,5}(:[0-9]{1,5})?(\/.*)?$/i;
 	return urlReg.test(url);
}

/*function validatePassword(password) {
	var pasReg = ^(?=[a-zA-Z0-9#@$?]{8,}$)(?=.*?[a-z])(?=.*?[A-Z])(?=.*?[0-9]).*;
	return pasReg.test(password);
}*/

/* quick notify (material design) bootstrap notify
	type: 	string		'success','danger','warning','info'
	message: string		Message to display
 */

function quick_alert(type, message) {
	if (type == 'success') {
		$('#alt-box').removeClass('d-none');
		$('#alt-box').addClass('alert-light-primary');
		$('#alt-icon').html('<i class="flaticon-information"></i>');
		$('#alt-message').html(message);
	} else if (type == 'warning') {
		$('#alt-box').removeClass('d-none');
		$('#alt-box').addClass('alert-light-warning');
		$('#alt-icon').html('<i class="flaticon2-warning"></i>');
		$('#alt-message').html(message);
	} else if (type == 'danger') {
		$('#alt-box').removeClass('d-none');
		$('#alt-box').addClass('alert-light-danger');
		$('#alt-icon').html('<i class="flaticon2-delete"></i>');
		$('#alt-message').html(message);
	}
	setTimeout(function() {
		$('#alt-box').addClass('d-none');
	}, 3000);
};

/* quick_swal (material design)
	type: 		string		'success','danger','warning','info','question'
	message: 	string		Message to display
	cancelBtn: 	boolean		Display cancel button default false
 */

function quick_swal(type, message, cancelBtn = false, outsideClick = true, closeBtn = true) {
	setTimeout(function() {
		Swal.fire({
			title: swal_title[type],
			type: type,
			html: message,
			animation: false,
			buttonsStyling:false,
			confirmButtonColor : swal_confirmButtonColor[type],
			confirmButtonClass : 'btn btn-'+swal_confirmButtonClass[type],
			showCancelButton : cancelBtn,
			cancelButtonClass : 'btn btn-default',
			showCloseButton: closeBtn,
			allowOutsideClick: outsideClick,
		});
	},500);
};

function go_to_home() {
	window.location = base_url;
}

function report_error() {
	window.open(error_report_mail, '_blank');
}

function ajax_error_swal(error_code = '500') {
	setTimeout(function() {
		Swal.fire({
			title: "Oops..!",
			type: 'error',
			html: "<b>Something went wrong!!<br/><br/></b>Hit the refresh button or try again after sometime. Click on Back to home button and explore the site.<br/><br/><small>Please make sure that your session is not expired. If yes then kindly login again.</small><br/><br/><small>Our team might be working on it. If the issue persist, please report this to our team. To report simply click the button below. While reporting mention your problem, the error message, responce code and link or action that caused it.<br/>Response Code: "+ error_code +"<br/></small><br/><a href='javascript:;' onclick='report_error();' class='btn btn-danger'>Report Error</a><a href='javascript:;' onclick='go_to_home();' class='btn btn-default'>Back to Home</a>",
			animation: false,
			buttonsStyling:false,
			showConfirmButton: false,
			allowOutsideClick: false,
			onClose: go_to_home,
		});
	}, 1000);
}

function show_sess_exp_error() {
	swal({
		title: "Error!",
		type: 'error',
		html: "<b>Your session has been expired!!<br/><br/></b>Click on continue button to get back to login page.<br/><br/><small></small><br/><a href='javascript:;' onclick='location.reload();' class='btn btn-danger'>Continue</a>",
		animation: false,
		buttonsStyling:false,
		showConfirmButton: false,
		allowOutsideClick: false,
		onClose: go_to_home,
	});
}

$.urlParam = function(name) {
	var results = new RegExp('[\?&]' + name + '=([^&#]*)').exec(window.location.href);
	if (results == null) {
		return null;
	} else {
		return decodeURI(results[1]) || 0;
	}
}

$(function() {
	/* validation to allow numbers only */
	$('.numbers_only').keydown(function (e) {
		if ($.inArray(e.keyCode, [46, 8, 9, 27, 13]) !== -1 || (e.keyCode == 65 && ( e.ctrlKey === true || e.metaKey === true ) ) || (e.keyCode >= 35 && e.keyCode <= 40)) {
			return;
		}
		if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
			e.preventDefault();
		}
	});
});

$(function () {
	/* validation to allow decimal only */
    $('.amount_only').keydown(function (e) {
        if (e.shiftKey == true) {
            e.preventDefault();
        }
        if ((e.keyCode >= 48 && e.keyCode <= 57) || (e.keyCode >= 96 && e.keyCode <= 105) || e.keyCode == 8 || e.keyCode == 9 || e.keyCode == 37 || e.keyCode == 39 || e.keyCode == 46 || e.keyCode == 190 || e.keyCode == 110) {
        } else {
            e.preventDefault();
        }
        if($(this).val().indexOf('.') !== -1 && e.keyCode == 190)
            e.preventDefault();
    });
});