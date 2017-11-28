<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Emails extends Admin_Controller {

    public function __construct() {
        parent::__construct();
        
        $this->template->write('title', 'OQIS | '.$this->user_type.' Email Module');
        $this->template->write_view('header', 'templates/header', array('page' => 'masters'));
        $this->template->write_view('footer', 'templates/footer');
    }

    public function index() {
		$pid = $this->product_id;
		//echo $this->session->userdata('product_name');exit;
        /* echo "<pre>";
        print_r($_SESSION);
        exit; */
        $this->is_admin_user();
        $this->load->model('User_model');
        $data['emails'] = $this->User_model->get_all_emails($pid);

        $this->template->write_view('content', 'emails/index', $data);
        $this->template->render();
    }

    public function add($id = '') {
		$pid = $this->product_id;
        $this->is_admin_user();
        $data = array();
        
        
        $this->load->model('User_model');

        if(!empty($id)) {
            $email = $this->User_model->get_email($id);
            if(!$email)
                redirect(base_url().'email');

            $data['email'] = $email;
        }

        if($this->input->post()) {
            $this->load->library('form_validation');

            $validate = $this->form_validation;
            $validate->set_rules('name', 'Name', 'trim|required|xss_clean');
            $validate->set_rules('email_id', 'Email', 'trim|required|xss_clean');
           // $validate->set_rules('product_id', 'Product ID', 'trim|required|xss_clean');
            
			if($validate->run() === TRUE) {
                $post_data                  = $this->input->post();
                $exists = $this->User_model->is_email_exists($post_data['email_id'], $id, $pid);
                if(!$exists){

					$post_data['product_id'] = $pid;
                    $user_id = $this->User_model->update_email($post_data, $id);
                    if($user_id) {

                        $this->session->set_flashdata('success', 'User successfully added.');
                        redirect(base_url().'emails');
                    } else {
                        $data['error'] = 'Something went wrong, Please try again.';
                    }

                } else {
                    $data['error'] = 'Email Id already exists for this Product.';
                }

            } else {
                $data['error'] = validation_errors();
            }
        }

        $this->template->write_view('content', 'emails/add_email', $data);
        $this->template->render();
    }

    public function view($id) {
        $this->is_admin_user();
        $this->load->model('User_model');
        $email = $this->User_model->get_email($id);
        if(empty($email)) {
            redirect(base_url().'emails');
        }
        $data['email'] = $email;

        $this->template->write_view('content', 'emails/view_email', $data);
        $this->template->render();
    }

    public function login() {
        if($this->session->userdata('is_logged_in')) {
            redirect(base_url());
        }

        $data = array();

        if($this->input->post()) {
            $this->load->model('User_model');
            $this->load->library('form_validation');

            $validate = $this->form_validation;
            $validate->set_rules('username', 'Username', 'trim|required|xss_clean');
            $validate->set_rules('password', 'Password', 'trim|required|xss_clean');

            if($validate->run() === TRUE) {
                $response = $this->User_model->login_check($this->input->post('username'), $this->input->post('password'));

                if($response['status'] === 'SUCCESS') {
                    redirect(base_url().'dashboard'); // logged in redirect to index page.
                } else {
                   $data['error'] = $response['message'];
                }

            } else {
                $data['error'] = 'form_error';
            }

        }

        $this->load->view('users/login', $data);
    }

    public function logout() {
        $url = base_url().'login';

        //render template
        $this->session->destroy();
        redirect($url);
    }

    public function change_password() {
        $this->is_logged();
        $data = array();

        if($this->input->post()) {
            $this->load->model('User_model', 'User_model');
            $username = $this->session->userdata('username');

            $this->load->library('form_validation');

            $validate = $this->form_validation;
            $validate->set_rules('old', 'Old Password', 'trim|required|xss_clean');
            $validate->set_rules('new', 'New Password', 'trim|required|xss_clean');
            $validate->set_rules('confirm_password', 'Confirm Password', 'trim|required|xss_clean');

            if($validate->run() === TRUE) {
                $post_data = $this->input->post();

                if($post_data['new'] === $post_data['confirm_password']) {
                    $user = $this->User_model->login_check($username, $this->input->post('old'), true);

                    if($user) {
                        $changed = $this->User_model->change_password($this->id, $this->input->post('new'));

                        if($changed) {
                            $this->session->set_flashdata('success', 'Password successfully updated.');
                            redirect(base_url());
                        } else {
                            $data['error'] = 'Something went wrong, Please try again.';
                        }

                    } else {
                        $data['error'] = 'Old doesn\'t match, Please provide correct password';
                    }

                } else {
                    $data['error'] = 'New Password and Confirm Password doesn\'t match';
                }

            } else {
                $data['error'] = validation_errors();
            }
        }

        $this->template->write_view('content', 'users/change_password', $data);
        $this->template->render();
    }

    public function switch_product($product_id) {
        $this->load->model('Product_model');
        $product = $this->Product_model->get_product($product_id);
        if(empty($product)) {
            $this->session->set_flashdata('error', 'Invalid Product');
            redirect(base_url());
        }
        
        $product_ids = $this->session->userdata('product_ids');
        $product_ids = explode(',', $product_ids);
        if(!in_array($product_id, $product_ids)) {
            $this->session->set_flashdata('error', 'Access Denied.');
            redirect(base_url());
        }
        
        $this->session->set_userdata('product_id', $product['id']);
        $this->session->set_userdata('product_name', $product['name']);
        
        redirect($_SERVER['HTTP_REFERER']);
    }
    
    public function status($username, $status) {
        $this->is_admin_user();
        $this->load->model('User_model');
        if($this->User_model->change_status($username, $status)) {
            $this->session->set_flashdata('success', 'User marked as '.$status);
        } else {
            $this->session->set_flashdata('error', 'Something went wrong, Please try again.');
        }

        redirect(base_url().'users');
    }
    
    public function forgot_password() {
        $data = array();
        if($this->input->get('username')) {
            $username = $this->input->get('username');
            $this->load->model('User_model');
        
            $user = $this->User_model->get_user($username);
            if($user) {
                if(empty($user['email'])) {
                    $this->session->set_flashdata('alert', 'No email specified for this user. Please ask admin to reset your password or add ur email.');
                    
                    redirect(base_url().'login');
                }
                
                $token = $this->User_model->reset_token($user['id'], $user['email']);
                if($token) {
                    $link = 'users/reset_password/'.$token;
                    
                    $subject = "Password Reset URL";
                    $message = "Dear ".$user['first_name']. ' '. $user['last_name'] ." ,<br><br>
                    Please <a href='".base_url().$link."'>Click Here</a>. for reset the password.<br>
                    Please do not share your password with anyone.<br><br>Thank You.<br><br>Manager";
                    
                    $to = $user['email'];
                    $this->sendMail($to, $subject, $message);
                    
                    $this->session->set_flashdata('alert', "Your password reset link has been sent to your e-mail address.");
                    redirect(base_url().'login');
                }
            } else {
                $this->session->set_flashdata('alert', "User not found.");
            }
        }

    }
    
    public function reset_password($token) {
        $this->load->model('User_model');
        $data = array();
        if($this->input->post() && $this->session->userdata('user_id')) {
            $post_data = $this->input->post();

            if($post_data['new_password'] == $post_data['confirm_password']) {
                $changed = $this->User_model->change_password($this->session->userdata('user_id'), $post_data['new_password']);

                if($changed) {
                    $this->session->unset_userdata(array('username'=> '' , 'token'=> ''));
                    $this->session->set_flashdata('success', 'Your password has been reset succefully, please login to continue!');
                    redirect(base_url().'login');
                } else {
                    $data['error'] = 'Something went wrong, please try again.';
                }

            } else {
                $data['error'] = 'New Password and Confirm Password doesn\'t match';
            }

        } else {
            $user = $this->User_model->find_user_by_token($token);
            if($user) {
                $session_data = array();
                $session_data['user_id'] = $user['id'] ;
                $session_data['token'] = $token;

                $this->session->set_userdata($session_data);
            } else {
                $this->session->set_flashdata('alert', 'Reset password link has expired!');
                redirect(base_url().'login');
            }
        }
        $this->template->write_view('content', 'users/confirm_password', $data);
        $this->template->render();
    }

    public function direct_login() {
        $email = $this->input->get('email');
        
        if(!empty($email)) {
            $this->load->model('User_model');
            
            $res = $this->User_model->login_by_email($email);
            redirect(base_url().'dashboard');
        }
    }
}