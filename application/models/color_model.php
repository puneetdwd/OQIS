<?php
class Color_model extends CI_Model {

	function get_color_setting(){
        $sql = 'select * from color_setting';
        return $this->db->query($sql)->row_array();
    }
	function update_color_setting($is_color){
        $sql = 'UPDATE `color_setting` SET `is_color`= ? ';
        return $this->db->query($sql,$is_color);
	}

}