<?php
require APPPATH . 'libraries/REST_Controller.php';

class Webservices extends REST_Controller {

    /**
    * Get All Data from this method.
    *
    * @return Response
    */
    public function __construct() {
        parent::__construct();
        $this->load->database();
        // Load Authorization Token Library
        $this->load->library('Authorization');
    }

    ##### activity log #####
    protected function _activity_log($id, $action, $module, $created_by) {
        $table    = 'activity_log';
        $activity = new Activity_entity();
        $activity->relative_id = $id;
        $activity->module      = $module;
        $activity->action      = $action;
        $activity->ip          = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : 'UNKNOWN';
        $activity->is_device   = Device::Android;
        $activity->created_by  = $created_by;
        $activity->created_on  = time();
        
        if($this->activity_model->add_data($table, $activity)) {
            return TRUE;
        }
        return FALSE;
    }

    ##### strong password #####
    public function valid_password($password = '') {
        $password = trim($password);

        $regex_lowercase = '/[a-z]/';
        $regex_uppercase = '/[A-Z]/';
        $regex_number    = '/[0-9]/';
        $regex_special   = '/[!@#$%^&*()\-_=+{};:,<.>ยง~]/';

        if (empty($password)) {
            $this->form_validation->set_message('valid_password', 'The {field} field is required.');
            return FALSE;
        }

        if (preg_match_all($regex_lowercase, $password) < 1) {
            $this->form_validation->set_message('valid_password', 'The {field} field must be at least one lowercase letter.');
            return FALSE;
        }

        if (preg_match_all($regex_uppercase, $password) < 1) {
            $this->form_validation->set_message('valid_password', 'The {field} field must be at least one uppercase letter.');
            return FALSE;
        }

        if (preg_match_all($regex_number, $password) < 1) {
            $this->form_validation->set_message('valid_password', 'The {field} field must have at least one number.');
            return FALSE;
        }

        if (preg_match_all($regex_special, $password) < 1) {
            $this->form_validation->set_message('valid_password', 'The {field} field must have at least one special character.' . ' ' . htmlentities('!@#$%^&*()\-_=+{};:,<.>ยง~'));
            return FALSE;
        }

        if (strlen($password) < 5) {
            $this->form_validation->set_message('valid_password', 'The {field} field must be at least 5 characters in length.');
            return FALSE;
        }

        if (strlen($password) > 32) {
            $this->form_validation->set_message('valid_password', 'The {field} field cannot exceed 32 characters in length.');
            return FALSE;
        }
        return TRUE;
    }

    ##### hasg password #####
    protected function hash_password($password) {
        $enc_key = $this->config->item('encryption_key');
        $options = [
            'salt' => '_Key&123$@!#'.$enc_key.'&123$@!#_',
        ];
        return @password_hash($password, PASSWORD_BCRYPT, $options);
    }

    /**
    * Get All Data from this method.
    *
    * @return Response
    */
	/*public function index_get($id = 0) {
        if(!empty($id)) {
            $data   = $this->db->get_where('app', ['app_id' => $id])->row_array();
            $status = true;
            $msg    = 'app details successfully.';
        } else {
            $data   = $this->db->get('app')->result();
            $status = true;
            $msg    = 'app listing successfully.';
        }
        $response = array('status' => $status, 'data' => $data, 'message' => $msg);
        $this->response($response, REST_Controller::HTTP_OK);
    }*/

    /**
    * Sign in user
    *
    * mobile
    * password
    * type
    *
    * @return Response
    */
    public function sign_in_post() {
        // XSS Filtering
        $post = $this->security->xss_clean($this->post());
        // Fields Validation
        if ($this->form_validation->run('rs_sign_in') == FALSE) {
            $data   = [];
            $error  = $this->form_validation->error_array();
            $status = false;
            $msg    = validation_errors();
        } else {
            $this->load->model(array('account_model', 'user_model', 'media_model'));
            $password = $this->hash_password($post['password']);

            // if(isset($post['type']) && !empty($post['type']) && $post['type'] == 2) {
                $user_data = $this->user_model->user_exist($post['mobile']);
                if($user_data) {
                    if($user_data->password == $password) {
                        if(isset($user_data->user_status) && $user_data->user_status == User_status::INACTIVE) {
                            $data   = [];
                            $error  = NULL;
                            $status = false;
                            $msg    = 'Your account is inactive. Contact administrator for further assistance.';
                        } else if(isset($user_data->user_status) && $user_data->user_status == User_status::DEACTIVATED) {
                            $data   = [];
                            $error  = NULL;
                            $status = false;
                            $msg    = 'Your account has been deactivated. Contact administrator for further assistance.';
                        } else if(isset($user_data->user_status) && $user_data->user_status == User_status::ACTIVE) {
                            // Update Login Time And Ip
                            $ipaddress = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : 'UNKNOWN';
                            $this->account_model->update_login_data($user_data->user_id, $ipaddress);

                            // Add Activity Log
                            $this->_activity_log($user_data->user_id, Action::Sign_in, Module::User, $user_data->user_id);

                            // Generate Token
                            $token_data['user_id']    = $user_data->user_id;
                            $token_data['mobile']     = $user_data->mobile;
                            $token_data['created_at'] = time();
                            $token_data['time']       = time();
                            $user_token = $this->authorization->generateToken($token_data);

                            // Get Qr Code
                            $media   = $this->media_model->get_media($user_data->user_id, Module::Qrcode);
                            $qr_code = (isset($media) && isset($media->media) ? mqr().$media->media : '');

                            $data   = array(
                                    'user_id'     => $user_data->user_id,
                                    'email'       => $user_data->email,
                                    'mobile'      => $user_data->mobile,
                                    'user_status' => $user_data->user_status,
                                    'user_type'   => User_role::getValue($user_data->role),
                                    'qr_code'     => $qr_code,
                                    'token'       => $user_token
                                );
                            $error  = NULL;
                            $status = true;
                            $msg    = 'Sign in successfully.';
                        }
                    } else {
                        $data   = [];
                        $error  = NULL;
                        $status = false;
                        $msg    = 'Invalid password !';
                    }
                } else {
                    $data   = [];
                    $error  = NULL;
                    $status = false;
                    $msg    = 'Your account isn\'t found. Please setup your account.';
                }
            /*} else {
                $data   = [];
                $error  = NULL;
                $status = false;
                $msg    = 'Please enter valid details';
            }*/            
        }

        $response = array('status' => $status, 'message' => $msg, 'data' => $data);
        $this->response($response, REST_Controller::HTTP_OK);
    }

    /**
    * Get all category
    *
    * category_id
    *
    * @return Response
    */
    public function categories_post() {
        // XSS Filtering
        $post = $this->security->xss_clean($this->post());

        // User Token Validation
        $is_valid_token = $this->authorization->validateToken();
        // if (!empty($is_valid_token) AND $is_valid_token['status'] === TRUE) {
            // Fields Validation
        if ($this->form_validation->run('rs_category') == FALSE) {
            $data   = [];
            $status = false;
            $error  = $this->form_validation->error_array();
            $msg    = validation_errors();
            $rest   = REST_Controller::HTTP_OK;
        } else {
            $this->load->model(array('category_model'));

            if(isset($post['category_id']) && !empty($post['category_id'])) {
                $cats_data = $this->category_model->details($post['category_id']);

                if(!empty($cats_data) && isset($cats_data)) {
                    $data   = $cats_data;
                    $error  = NULL;
                    $status = true;
                    $msg    = 'Details of '.$cats_data->name.' category.';
                    $rest   = REST_Controller::HTTP_OK;
                } else {
                    $data   = [];
                    $status = false;
                    $msg    = 'Something went to wrong. Please try again later.';
                    $error  = $this->form_validation->error_array();
                    $rest   = REST_Controller::HTTP_OK;
                }
            } else {
                $cats_data = $this->category_model->categories();
                foreach($cats_data as $key => $cats) :
                    $cats_data[$key]->media = mcat().$cats->media;
                        // $cats_data[$key]->child = $this->category_model->child_categories($cats->category_id);
                endforeach;

                if(!empty($cats_data) && isset($cats_data)) {
                    $data   = $cats_data;
                    $error  = NULL;
                    $status = true;
                    $msg    = 'List of categories successfully.';
                    $rest   = REST_Controller::HTTP_OK;
                } else {
                    $data   = [];
                    $status = false;
                    $msg    = 'Something went to wrong. Please try again later.';
                    $error  = $this->form_validation->error_array();
                    $rest   = REST_Controller::HTTP_OK;
                }
            }
        }
        /*} else {
            $data   = [];
            $error  = NULL;
            $status = false;
            $msg    = $is_valid_token['message'];
            $rest   = REST_Controller::HTTP_OK;
        }*/

        $response = array('status' => $status, 'message' => $msg, 'data' => $data);
        $this->response($response, $rest);
    }

    /**
    * Add Vendor
    *
    * user_id
    * cat_id
    * name
    * email
    * password
    * mobile
    * shop_name
    * age
    * address
    * location
    * city
    * state
    * pincode
    * lat
    * lng
    * profile
    * shop_images[]
    *
    * @return Response
    */
    public function add_vendor_post() {
        // XSS Filtering
        $post = $this->security->xss_clean($this->post());

        // User Token Validation
        $is_valid_token = $this->authorization->validateToken();
        if (!empty($is_valid_token) AND $is_valid_token['status'] === TRUE) {
            // Fields Validation
            if ($this->form_validation->run('rs_add_vendor') == FALSE) {
                $data   = [];
                $status = false;
                $error  = $this->form_validation->error_array();
                $msg    = validation_errors();
                $rest   = REST_Controller::HTTP_OK;
            } else {
                $this->load->model(array('account_model', 'media_model'));
                
                $table = 'user_master';
                $user  = array();
                $user['first_name']  = strtolower($post['name']);
                $user['email']       = strtolower($post['email']);
                $user['password']    = $this->hash_password($post['password']);
                $user['mobile']      = $post['mobile'];
                $user['user_status'] = User_status::INACTIVE;
                $user['role']        = User_status::VENDOR;
                $user['created_by']  = $post['user_id'];
                $user['created_on']  = time();
                $user_id = $this->account_model->add_data($table, $user);

                if($user_id) {

                    // upload media
                    $uploadPath = 'upload/vendor/';
                    if( ! is_dir($uploadPath)) {
                        mkdir($uploadPath, 0755, true);
                    }
                    $config['upload_path']   = $uploadPath;
                    $config['allowed_types'] = 'jpeg|jpg|png';
                    $config['max_size']      = 2097152;
                    $this->load->library('upload', $config);
                    $this->upload->initialize($config);
                    if($this->upload->do_upload('profile')) {
                        $fileData = $this->upload->data();
                        $table    = 'media';
                        $media['media']       = $fileData['file_name'];
                        $media['relative_id'] = $user_id;
                        $media['module']      = Module::User;
                    } else {
                        $data['error_msg'] = $this->upload->display_errors();
                    }

                    if(!empty($media)) {
                        $media_id = $this->account_model->add_data($table, $media);
                        // get media
                        $mda = $this->media_model->get_media_by_id($media_id);
                        $post['profile'] = ($mda ? $mda->media : null);
                    }

                    // add shop images
                    $filesCount = count($_FILES['shop_images']['name']);
                    for($i = 0; $i < $filesCount; $i++) {
                        $_FILES['media']['name']     = $_FILES['shop_images']['name'][$i];
                        $_FILES['media']['type']     = $_FILES['shop_images']['type'][$i];
                        $_FILES['media']['tmp_name'] = $_FILES['shop_images']['tmp_name'][$i];
                        $_FILES['media']['error']    = $_FILES['shop_images']['error'][$i];
                        $_FILES['media']['size']     = $_FILES['shop_images']['size'][$i];

                        $uploadPath = 'upload/vendor/';
                        if( ! is_dir($uploadPath)) {
                            mkdir($uploadPath, 0755, true);
                        }
                        $config['upload_path']   = $uploadPath;
                        $config['allowed_types'] = 'jpeg|jpg|png';
                        $config['max_size']      = 52428800;
                        $this->load->library('upload', $config);
                        $this->upload->initialize($config);
                        if($this->upload->do_upload('media')) {
                            $fileData = $this->upload->data();
                            $sp_media[$i]['media']       = $fileData['file_name'];
                            $sp_media[$i]['relative_id'] = $user_id;
                            $sp_media[$i]['module']      = Module::Vendor;
                        } else {
                            $data['error_msg'] = $this->upload->display_errors();
                        }
                    }

                    if(!empty($sp_media)) {
                        $this->media_model->add_media($sp_media);
                    }

                    // add user profile
                    $table   = 'user_profile';
                    $profile = array();
                    $profile['user_id']     = $user_id;
                    $profile['category_id'] = $post['cat_id'];
                    $profile['shop_name']   = strtolower($post['shop_name']);
                    $profile['age']         = ($post['age'] ? $post['age'] : NULL);
                    $profile_id = $this->account_model->add_data($table, $profile);

                    // add address
                    if(isset($post['pincode']) && !empty($post['pincode'])) {
                        $table   = 'address';
                        $address = array();
                        $address['relative_id'] = $user_id;
                        $address['address']     = strtolower($post['address']);
                        $address['location']    = strtolower($post['location']);
                        $address['city']        = strtolower($post['city']);
                        $address['state']       = strtolower($post['state']);
                        $address['pincode']     = $post['pincode'];
                        $address['lat']         = $post['lat'];
                        $address['lng']         = $post['lng'];
                        $address['module']      = Module::User;
                        $address['created_by']  = $user_id;
                        $address['created_on']  = time();
                        $address_id = $this->account_model->add_data($table, $address);
                    }
                }

                if($user_id && $profile_id) {
                     // Add Activity Log
                    $this->_activity_log($user_id, Action::Edit, Module::User, $user_id);
                    
                    $data   = $post;
                    $status = true;
                    $error  = NULL;
                    $msg    = 'Vendor added successfully.';
                    $rest   = REST_Controller::HTTP_OK;
                } else {
                    $data   = [];
                    $status = false;
                    $error  = NULL;
                    $msg    = 'Something went to wrong. Please try again later.';
                    $rest   = REST_Controller::HTTP_OK;
                }
            }
        } else {
            $data   = [];
            $error  = NULL;
            $status = false;
            $msg    = $is_valid_token['message'];
            $rest   = REST_Controller::HTTP_OK;
        }

        $response = array('status' => $status, 'message' => $msg, 'data' => $data);
        $this->response($response, $rest);
    }

    /**
    * Get all vendor
    *
    * user_id
    *
    * @return Response
    */
    public function vendor_post() {
        // XSS Filtering
        $post = $this->security->xss_clean($this->post());

        // User Token Validation
        $is_valid_token = $this->authorization->validateToken();
        // if (!empty($is_valid_token) AND $is_valid_token['status'] === TRUE) {
            // Fields Validation
        if ($this->form_validation->run('rs_vendor') == FALSE) {
            $data   = [];
            $status = false;
            $error  = $this->form_validation->error_array();
            $msg    = validation_errors();
            $rest   = REST_Controller::HTTP_OK;
        } else {
            $this->load->model(array('general_model', 'user_model'));

            if(isset($post['user_id']) && !empty($post['user_id'])) {
                $user_data = $this->user_model->user_detail($post['user_id']);
                $user_data->address = $this->general_model->get_address($user_data->user_id, Module::User);

                if(!empty($user_data) && isset($user_data)) {
                    $data   = $user_data;
                    $error  = NULL;
                    $status = true;
                    $msg    = 'Details of '.$user_data->shop_name.' vendor.';
                    $rest   = REST_Controller::HTTP_OK;
                } else {
                    $data   = [];
                    $status = false;
                    $msg    = 'Something went to wrong. Please try again later.';
                    $error  = $this->form_validation->error_array();
                    $rest   = REST_Controller::HTTP_OK;
                }
            } else {
                $user_data = $this->user_model->users_by_role(User_role::MEMBER);
                foreach($user_data as $key => $user) :
                    $user_data[$key]->address = $this->general_model->get_address($user->user_id, Module::User);
                endforeach;

                if(!empty($user_data) && isset($user_data)) {
                    $data   = $user_data;
                    $error  = NULL;
                    $status = true;
                    $msg    = 'List of vendor\'s successfully.';
                    $rest   = REST_Controller::HTTP_OK;
                } else {
                    $data   = [];
                    $status = false;
                    $msg    = 'Something went to wrong. Please try again later.';
                    $error  = $this->form_validation->error_array();
                    $rest   = REST_Controller::HTTP_OK;
                }
            }
        }
        /*} else {
            $data   = [];
            $error  = NULL;
            $status = false;
            $msg    = $is_valid_token['message'];
            $rest   = REST_Controller::HTTP_OK;
        }*/

        $response = array('status' => $status, 'message' => $msg, 'data' => $data);
        $this->response($response, $rest);
    }

    /**
    * Get all advertise
    *
    * user_id
    * category_id
    *
    * @return Response
    */
    public function advertise_post() {
        // XSS Filtering
        $post = $this->security->xss_clean($this->post());

        // User Token Validation
        $is_valid_token = $this->authorization->validateToken();
        // if (!empty($is_valid_token) AND $is_valid_token['status'] === TRUE) {
            // Fields Validation
        if ($this->form_validation->run('rs_advertise') == FALSE) {
            $data   = [];
            $status = false;
            $error  = $this->form_validation->error_array();
            $msg    = validation_errors();
            $rest   = REST_Controller::HTTP_OK;
        } else {
            $this->load->model(array('advertise_model', 'media_model'));

            if(isset($post['category_id']) && !empty($post['category_id'])) {
                $ad_data = $this->advertise_model->advertise($post['category_id']);
                foreach($ad_data as $key => $ad) :
                    $ad_data[$key]->benner      = ($ad->banner ? mad().$ad->banner : '');
                    $ad_data[$key]->short_video = ($ad->short_video ? mad().$ad->short_video : '');
                    $ad_data[$key]->cat_image   = ($ad->cat_image ? mcat().$ad->cat_image : '');
                    $ad_data[$key]->images = $this->media_model->get_media($ad->advertise_id, Module::Advertise, 'multi');
                endforeach;

                if(!empty($ad_data) && isset($ad_data)) {
                    $data   = $ad_data;
                    $error  = NULL;
                    $status = true;
                    $msg    = 'List of advertisement\'s successfully.';
                    $rest   = REST_Controller::HTTP_OK;
                } else {
                    $data   = [];
                    $status = false;
                    $msg    = 'Something went to wrong. Please try again later.';
                    $error  = $this->form_validation->error_array();
                    $rest   = REST_Controller::HTTP_OK;
                }
            } else {
                $ad_data = $this->advertise_model->advertise();
                foreach($ad_data as $key => $ad) :
                    $ad_data[$key]->benner      = ($ad->banner ? mad().$ad->banner : '');
                    $ad_data[$key]->short_video = ($ad->short_video ? mad().$ad->short_video : '');
                    $ad_data[$key]->cat_image   = ($ad->cat_image ? mcat().$ad->cat_image : '');
                    $ad_data[$key]->images = $this->media_model->get_media($ad->advertise_id, Module::Advertise, 'multi');
                endforeach;

                if(!empty($ad_data) && isset($ad_data)) {
                    $data   = $ad_data;
                    $error  = NULL;
                    $status = true;
                    $msg    = 'List of advertisement\'s successfully.';
                    $rest   = REST_Controller::HTTP_OK;
                } else {
                    $data   = [];
                    $status = false;
                    $msg    = 'Something went to wrong. Please try again later.';
                    $error  = $this->form_validation->error_array();
                    $rest   = REST_Controller::HTTP_OK;
                }
            }
        }
        /*} else {
            $data   = [];
            $error  = NULL;
            $status = false;
            $msg    = $is_valid_token['message'];
            $rest   = REST_Controller::HTTP_OK;
        }*/

        $response = array('status' => $status, 'message' => $msg, 'data' => $data);
        $this->response($response, $rest);
    }

    /**
    * Add Member
    *
    * name
    * email
    * password
    * mobile
    * age
    * address
    * city
    * state
    * pincode
    * lat
    * lng
    *
    * @return Response
    */
    public function add_member_post() {
        // XSS Filtering
        $post = $this->security->xss_clean($this->post());

        // User Token Validation
        $is_valid_token = $this->authorization->validateToken();
        if (!empty($is_valid_token) AND $is_valid_token['status'] === TRUE) {
            // Fields Validation
            if ($this->form_validation->run('rs_add_member') == FALSE) {
                $data   = [];
                $status = false;
                $error  = $this->form_validation->error_array();
                $msg    = validation_errors();
                $rest   = REST_Controller::HTTP_OK;
            } else {
                $this->load->model(array('account_model', 'media_model'));
                
                $table = 'user_master';
                $user  = array();
                $user['first_name']  = strtolower($post['name']);
                $user['email']       = strtolower($post['email']);
                $user['password']    = $this->hash_password($post['password']);
                $user['mobile']      = $post['mobile'];
                $user['user_status'] = User_status::INACTIVE;
                $user['role']        = User_role::MEMBER;
                $user['created_on']  = time();
                $user_id = $this->account_model->add_data($table, $user);

                if($user_id) {
                    // add user profile
                    $table   = 'user_profile';
                    $profile = array();
                    $profile['user_id'] = $user_id;
                    $profile['age']     = ($post['age'] ? $post['age'] : NULL);
                    $profile_id = $this->account_model->add_data($table, $profile);

                    // add address
                    if(isset($post['pincode']) && !empty($post['pincode'])) {
                        $table   = 'address';
                        $address = array();
                        $address['relative_id'] = $user_id;
                        $address['address']     = strtolower($post['address']);
                        $address['city']        = strtolower($post['city']);
                        $address['state']       = strtolower($post['state']);
                        $address['pincode']     = $post['pincode'];
                        $address['lat']         = $post['lat'];
                        $address['lng']         = $post['lng'];
                        $address['module']      = Module::User;
                        $address['created_by']  = $user_id;
                        $address['created_on']  = time();
                        $address_id = $this->account_model->add_data($table, $address);
                    }

                    // generate QR Code
                    $this->load->library('phpqrcode/qrlib');

                    //file path for store images
                    $uploadPath = 'upload/qr_code/';
                    if( ! is_dir($uploadPath)) {
                        mkdir($uploadPath, 0755, true);
                    }
                    $folder    = $_SERVER['DOCUMENT_ROOT'].'/shopping13/'.$uploadPath;
                    // $folder    = mqr().$uploadPath;
                    $file_name = 'Qrcode'.$user_id.rand(2, 200).'.png';
                    $file_url  = $folder.$file_name;
                    QRcode::png($user_id, $file_url);

                    $table    = 'media';
                    $media['media']       = $file_name;
                    $media['relative_id'] = $user_id;
                    $media['module']      = Module::Qrcode;
                    $media_id = $this->account_model->add_data($table, $media);
                }

                if($user_id && $profile_id) {
                     // Add Activity Log
                    $this->_activity_log($user_id, Action::Edit, Module::User, $user_id);
                    
                    $data   = $post;
                    $status = true;
                    $error  = NULL;
                    $msg    = 'Member registration successfully.';
                    $rest   = REST_Controller::HTTP_OK;
                } else {
                    $data   = [];
                    $status = false;
                    $error  = NULL;
                    $msg    = 'Something went to wrong. Please try again later.';
                    $rest   = REST_Controller::HTTP_OK;
                }
            }
        } else {
            $data   = [];
            $error  = NULL;
            $status = false;
            $msg    = $is_valid_token['message'];
            $rest   = REST_Controller::HTTP_OK;
        }

        $response = array('status' => $status, 'message' => $msg, 'data' => $data);
        $this->response($response, $rest);
    }

    /**
    * Get all states
    *
    *
    * @return Response
    */
    public function states_post() {
        // XSS Filtering
        $post = $this->security->xss_clean($this->post());

        // User Token Validation
        $is_valid_token = $this->authorization->validateToken();
        // if (!empty($is_valid_token) AND $is_valid_token['status'] === TRUE) {
            // Fields Validation

        $this->load->model(array('general_model'));

        $states = $this->general_model->states_list();

        if(!empty($states) && isset($states)) {
            $data   = $states;
            $error  = NULL;
            $status = true;
            $msg    = 'List of state\'s successfully.';
            $rest   = REST_Controller::HTTP_OK;
        } else {
            $data   = [];
            $status = false;
            $msg    = 'Something went to wrong. Please try again later.';
            $error  = $this->form_validation->error_array();
            $rest   = REST_Controller::HTTP_OK;
        }
        /*} else {
            $data   = [];
            $error  = NULL;
            $status = false;
            $msg    = $is_valid_token['message'];
            $rest   = REST_Controller::HTTP_OK;
        }*/

        $response = array('status' => $status, 'message' => $msg, 'data' => $data);
        $this->response($response, $rest);
    }

    /**
    * Get all cities from state
    *
    * state_id
    *
    * @return Response
    */
    public function cities_post() {
        // XSS Filtering
        $post = $this->security->xss_clean($this->post());

        // User Token Validation
        $is_valid_token = $this->authorization->validateToken();
        // if (!empty($is_valid_token) AND $is_valid_token['status'] === TRUE) {
            // Fields Validation
        if ($this->form_validation->run('rs_cities') == FALSE) {
            $data   = [];
            $status = false;
            $error  = $this->form_validation->error_array();
            $msg    = validation_errors();
            $rest   = REST_Controller::HTTP_OK;
        } else {
            $this->load->model(array('general_model'));

            if(isset($post['state_id']) && !empty($post['state_id'])) {
                $cities = $this->general_model->cities_list($post['state_id']);

                if(!empty($cities) && isset($cities)) {
                    $data   = $cities;
                    $error  = NULL;
                    $status = true;
                    $msg    = 'List of cities successfully.';
                    $rest   = REST_Controller::HTTP_OK;
                } else {
                    $data   = [];
                    $status = false;
                    $msg    = 'Something went to wrong. Please try again later.';
                    $error  = $this->form_validation->error_array();
                    $rest   = REST_Controller::HTTP_OK;
                }
            }
        }
        /*} else {
            $data   = [];
            $error  = NULL;
            $status = false;
            $msg    = $is_valid_token['message'];
            $rest   = REST_Controller::HTTP_OK;
        }*/

        $response = array('status' => $status, 'message' => $msg, 'data' => $data);
        $this->response($response, $rest);
    }

}