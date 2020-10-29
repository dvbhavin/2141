<?php
$config = array(
	'login_form' => array(
		array(
			'field' => 'email_id',
			'label' => 'Email address',
			'rules' => 'trim|required',
		), array(
			'field' => 'password',
			'label' => 'Password',
			'rules' => 'required'
		),
	),
	'forgot_password_form' => array(
		array(
			'field' => 'email',
			'label' => 'Email',
			'rules' => 'trim|required|valid_email',
			'errors' => array(
                'is_unique' => 'This %s already exists.'
			)
		),
	),
	'reset_password_form' => array(
		array(
			'field' => 'reset_code',
			'label' => 'Reset Code',
			'rules' => 'required'
		), array(
			'field' => 'new_password',
			'label' => 'New Password',
			'rules' => 'required'
		), array(
			'field' => 'confirm_password',
			'label' => 'Confirm Password',
			'rules' => 'required|matches[new_password]'
		),
	),
	'change_password_form' => array(
		array(
			'field' => 'current_password',
			'label' => 'Current Password',
			'rules' => 'required'
		), array(
			'field' => 'new_password',
			'label' => 'New Password',
			'rules' => 'required'
		), array(
			'field' => 'confirm_password',
			'label' => 'Confirm Password',
			'rules' => 'required|matches[new_password]'
		),
	),
	'add_category' => array(
		array(
			'field' => 'name',
			'label' => 'Category Name',
			'rules' => 'required'
		)
	),
	'edit_category' => array(
		array(
			'field' => 'category_id',
			'label' => 'Category ID',
			'rules' => 'required'
		), array(
			'field' => 'name',
			'label' => 'Category Name',
			'rules' => 'required'
		)
	),
	'add_member' => array(
		array(
			'field' => 'first_name',
			'label' => 'First name',
			'rules' => 'required'
		), array(
			'field' => 'last_name',
			'label' => 'Last name',
			'rules' => 'required'
		),
	),
	'add_advertise' => array(
		array(
			'field' => 'category_id',
			'label' => 'Choose Category',
			'rules' => 'required'
		), array(
			'field' => 'user_id',
			'label' => 'Choose Vendor',
			'rules' => 'required'
		),
	),
	'edit_advertise' => array(
		array(
			'field' => 'advertise_id',
			'label' => 'Advertise ID',
			'rules' => 'required'
		), array(
			'field' => 'category_id',
			'label' => 'Choose Category',
			'rules' => 'required'
		), array(
			'field' => 'user_id',
			'label' => 'Choose Vendor',
			'rules' => 'required'
		),
	),

	// REST API VALIDATION
	'rs_sign_in' => array(
		array(
			'field' => 'mobile',
			'label' => 'Mobile',
			'rules' => 'trim|required|exact_length[10]|numeric',
			'errors' => array(
				'exact_length' => '%s no must be exactly 10 digit',
				'numeric'      => '%s no must be contain only numbers'
			)
		), array(
			'field' => 'password',
			'label' => 'Password',
			'rules' => 'trim|required|min_length[6]',
		)
	),
	'rs_verify_otp' => array(
		array(
			'field' => 'user_id',
			'label' => 'User ID',
			'rules' => 'trim|required|numeric|max_length[5]',
			'errors' => array(
				'numeric' => '%s must contain only numbers'
			)
		), array(
			'field' => 'otp',
			'label' => 'OTP',
			'rules' => 'trim|required|exact_length[6]|numeric',
			'errors' => array(
				'exact_length' => '%s must be exactly 6 digit',
				'numeric'      => '%s must contain only numbers'
			)
		)
	),
	'rs_resend_otp' => array(
		array(
			'field' => 'user_id',
			'label' => 'User ID',
			'rules' => 'trim|required|numeric|max_length[5]',
			'errors' => array(
				'numeric' => '%s must contain only numbers'
			)
		), array(
			'field' => 'mobile',
			'label' => 'Mobile',
			'rules' => 'trim|required|exact_length[10]|numeric',
			'errors' => array(
				'exact_length' => '%s no must be exactly 10 digit'
			)
		)
	),
	'rs_add_vendor' => array(
		array(
			'field' => 'user_id',
			'label' => 'User ID',
			'rules' => 'trim|required|numeric|max_length[5]',
			'errors' => array(
				'numeric' => '%s must contain only numbers'
			)
		), array(
			'field' => 'shop_name',
			'label' => 'Shop Name',
			'rules' => 'trim|required|max_length[80]'
		), array(
			'field' => 'email',
			'label' => 'Email',
			'rules' => 'trim|required|valid_email|max_length[50]',
			'errors' => array(
                'is_unique' => 'This %s address already exists please enter another email'
			)
		), array(
			'field' => 'name',
			'label' => 'Name',
			'rules' => 'trim|required|max_length[80]'
		), array(
			'field' => 'mobile',
			'label' => 'Mobile No',
			'rules' => 'trim|required|exact_length[10]|numeric',
			'errors' => array(
				'exact_length' => '%s no must be exactly 15 digit'
			)
		), array(
			'field' => 'pincode',
			'label' => 'Pin Code',
			'rules' => 'trim|exact_length[6]|numeric',
			'errors' => array(
				'exact_length' => '%s no must be exactly 6 digit'
			)
		), array(
			'field' => 'age',
			'label' => 'Age',
			'rules' => 'trim|max_length[2]|numeric'
		)
	),
	'rs_category' => array(
		array(
			'field' => 'category_id',
			'label' => 'Category ID',
			'rules' => 'trim|numeric|max_length[5]',
			'errors' => array(
				'numeric' => '%s must contain only numbers'
			)
		)
	),
	'rs_vendor' => array(
		array(
			'field' => 'user_id',
			'label' => 'User ID',
			'rules' => 'trim|numeric|max_length[5]',
			'errors' => array(
				'numeric' => '%s must contain only numbers'
			)
		)
	),
	'rs_advertise' => array(
		array(
			'field' => 'user_id',
			'label' => 'User ID',
			'rules' => 'trim|numeric|max_length[5]',
			'errors' => array(
				'numeric' => '%s must contain only numbers'
			)
		)
	),
	'rs_add_member' => array(
		array(
			'field' => 'name',
			'label' => 'Name',
			'rules' => 'trim|required|max_length[80]'
		), array(
			'field' => 'email',
			'label' => 'Email',
			'rules' => 'trim|required|valid_email|max_length[50]',
			'errors' => array(
                'is_unique' => 'This %s address already exists please enter another email'
			)
		), array(
			'field' => 'mobile',
			'label' => 'Mobile No',
			'rules' => 'trim|required|exact_length[10]|numeric',
			'errors' => array(
				'exact_length' => '%s no must be exactly 15 digit'
			)
		), array(
			'field' => 'pincode',
			'label' => 'Pin Code',
			'rules' => 'trim|exact_length[6]|numeric',
			'errors' => array(
				'exact_length' => '%s no must be exactly 6 digit'
			)
		), array(
			'field' => 'age',
			'label' => 'Age',
			'rules' => 'trim|max_length[2]|numeric'
		)
	),
	'rs_cities' => array(
		array(
			'field' => 'state_id',
			'label' => 'State ID',
			'rules' => 'trim|numeric|max_length[2]',
			'errors' => array(
				'numeric' => '%s must contain only numbers'
			)
		)
	),
	// END REST API VALIDATION

);

$config['error_prefix'] = '';
$config['error_suffix'] = '';