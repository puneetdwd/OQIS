<?php

class User_model extends CI_Model {

    function __construct() {
        parent::__construct();

        require_once APPPATH .'libraries/pass_compat/password.php';
        $this->load->database();
    }

    function get_all_users() {
        $sql = "SELECT u.*, 
        GROUP_CONCAT(p.name ORDER BY p.name) as product_name 
        FROM users u
        LEFT JOIN products p
        ON FIND_IN_SET(p.id, u.product_id)";
        
        $pass_array = array();
        if($this->product_id) {
            $sql .= ' WHERE u.product_id = ?';
            $pass_array = array($this->product_id);
        }
        
        $sql .= " GROUP BY u.id";
        
        $users = $this->db->query($sql, $pass_array);
        return $users->result_array();
    }

    function get_user($username) {
        $sql = "SELECT u.*, 
        GROUP_CONCAT(p.name ORDER BY p.name) as product_name 
        FROM users u
        LEFT JOIN products p
        ON FIND_IN_SET(p.id, u.product_id)
        WHERE u.username = ?";
        
        $pass_array = array($username);
        if($this->product_id) {
            $sql .= ' AND u.product_id = ?';
            $pass_array[] = $this->product_id;
        }

        $sql .= " GROUP BY u.id";
        
        return $this->db->query($sql, $pass_array)->row_array();
    }
    
    function get_user_by_type($user_type) {
        $this->db->where('user_type', $user_type);
        
        if($this->product_id) {
            $this->db->where('product_id', $this->product_id);
        }
        
        return $this->db->get('users')->result_array();
    }

    function is_username_exists($username, $id = '') {
        if(!empty($id)) {
            $this->db->where('id !=', $id);
        }

        $this->db->where('username', $username);

        return $this->db->count_all_results('users');
    }

    function update_user($data, $user_id = '') {
        //filter unwanted fields while inserting in table.
        $needed_array = array('product_id', 'first_name', 'last_name', 'username', 'password', 'email', 'user_type', 'is_active', 'checklist_checked');
        $data = array_intersect_key($data, array_flip($needed_array));

        if(!empty($data['password'])) {
            $cost = $this->config->item('hash_cost');
            $data['password'] = password_hash(SALT .$data['password'], PASSWORD_BCRYPT, array('cost' => $cost));
        } else {
            unset($data['password']);
        }

        if(empty($user_id)) {
            $data['created'] = date("Y-m-d H:i:s");

            return (($this->db->insert('users', $data)) ? $this->db->insert_id() : False);
        } else {
            $this->db->where('id', $user_id);
            $data['modified'] = date("Y-m-d H:i:s");

            return (($this->db->update('users', $data)) ? $user_id : False);
        }
    }

    function login_check($username, $password, $only_check = false) {
        if (empty($username) || empty($password)) {
            return False;
        }

        $response['status'] = 'ERROR';
        $response['message'] = 'Invalid Credentials';

        $sql = "SELECT u.*, 
            GROUP_CONCAT(p.id ORDER BY p.name) as product_ids,
            GROUP_CONCAT(p.org_id ORDER BY p.name) as org_ids,
            GROUP_CONCAT(p.name ORDER BY p.name) as product_names
            FROM users u 
            LEFT JOIN products p 
            ON FIND_IN_SET(p.id, u.product_id)
            WHERE u.username = ?
            GROUP BY u.id";
        
        $query = $this->db->query($sql, array($username));

        if ($query->num_rows() == 1) {
            $user = $query->row_array();
            //echo "<pre>";print_r($user);exit;
            if (password_verify(SALT .$password, $user['password'])) {
                if(!$user['is_active']) {
                    $response['message'] = 'Your acount has been deactivated.';
                } else {

                    $response['status'] = 'SUCCESS';
                    
                    if(!$only_check) {
                        $this->create_session($user);
                    }
                    return $response;
                }
            }
        }

        return $response;
    }
    
    function create_session($user) {
        $data = array(
            'is_logged_in' => True,
            'id' => $user['id'],
            'first_name' => $user['first_name'],
            'last_name' => $user['last_name'],
            'name' => $user['first_name'].' '.$user['last_name'],
            'username' => $user['username'],
            'email' => $user['email'],
            'user_type' => $user['user_type'],
            'product_ids' => $user['product_ids'],
        );
        
        if($user['user_type'] == 'Admin' && empty($user['product_id'])) {
            $all_products = $this->db->get('products')->result_array();
            
            $products = array();
            $product_ids = array();
            foreach($all_products as $k => $pid) {
                if($k === 0) {
                    $data['product_id']     = $pid['id'];
                    $data['product_name']   = $pid['name'];
                    $data['org_id']         = $pid['org_id'];
                }
                
                $temp = array();
                $temp['id'] = $pid['id'];
                $temp['name'] = $pid['name'];
                $temp['org_id'] = $pid['org_id'];
                $product_ids[] = $pid['id'];
                $products[] = $temp;
            }
            
            $data['product_ids'] = implode(',', $product_ids);
            $data['is_super_admin'] = true;
        } else {
            $data['is_super_admin'] = false;
            $product_ids = explode(',', $user['product_ids']);
            $org_ids = explode(',', $user['org_ids']);
            $product_names = explode(',', $user['product_names']);
            $products = array();
            
            if(count($product_ids)) {
                foreach($product_ids as $k => $pid) {
                    if($k === 0) {
                        $data['product_id']     = $pid;
                        $data['product_name']   = $product_names[$k];
                        $data['org_id']   = $org_ids[$k];
                    }
                    
                    $temp = array();
                    $temp['id'] = $pid;
                    $temp['name'] = $product_names[$k];
                    $temp['org_id'] = $org_ids[$k];
                    
                    $products[] = $temp;
                }

            }
        }
        
        $data['products'] = $products;
        
        //echo "<pre>";print_r($data);exit;
        $this->session->set_userdata($data);
        
        return true;
    }

    function login_by_email($email) {
        $sql = "SELECT u.*, 
            GROUP_CONCAT(p.id ORDER BY p.name) as product_ids,
            GROUP_CONCAT(p.name ORDER BY p.name) as product_names
            FROM users u 
            LEFT JOIN products p 
            ON FIND_IN_SET(p.id, u.product_id)
            WHERE u.email = ?
            GROUP BY u.id";
        
        $query = $this->db->query($sql, array($email));
        if ($query->num_rows() == 1) {
            $user = $query->row_array();            
            $this->create_session($user);

            return true;
        }
        
        return false;
    }
    
    function change_password($id, $password) {
        if(!empty($password)) {
            $cost = $this->config->item('hash_cost');
            $password = password_hash(SALT .$password, PASSWORD_BCRYPT, array('cost' => $cost));

            $this->db->where('id', $id);
            $this->db->set('password', $password);

            $this->db->update('users');

            if($this->db->affected_rows() > 0) {
                return TRUE;
            }

        }

        return False;
    }

    function change_status($username, $status) {
        if(!empty($username) && !empty($status)) {
            $user_active = ($status == 'active') ? 1 : 0;
            
            $this->db->where('username', $username);
            if($this->product_id) {
                $this->db->where('product_id', $this->product_id);
            }
            $this->db->set('is_active', $user_active);
            $this->db->update('users');

            if($this->db->affected_rows() > 0) {
                return TRUE;
            }
        }

        return FALSE;
    }

    public function reset_token($user_id, $email) {
        $token = md5($email);
        
        $this->db->where('id', $user_id);        
        $this->db->set('reset_token', $token);
        $this->db->set('reset_request_time', date('Y-m-d H:i:s'));
        
        return (($this->db->update('users')) ? $token : False);
    }
    
    public function find_user_by_token($token){
        $sql = "SELECT * FROM users WHERE reset_token = ? AND reset_request_time >  DATE_SUB( NOW(), INTERVAL 24 HOUR)" ;
        $result = $this->db->query($sql, array($token));
        return $result->row_array();
    }
}