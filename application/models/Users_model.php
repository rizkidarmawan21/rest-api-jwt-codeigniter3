<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Users_model extends CI_Model {
    public function getUsers(){
        return $this->db->get('tb_users')->result_array();
    }
}