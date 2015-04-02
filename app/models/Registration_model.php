<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 *  Модель регистрации
 */
class Registration_model extends CI_Model
{
	/**
	 *  Регистрация пользователя
	 */	
	public function register($email, $pass, $ip, $date, $cookies, $social_id = 0)
    {
		$this->db->set('EMAIL',       $email);
		$this->db->set('PASSWORD',   $pass);
		$this->db->set('IP',  $ip);
		$this->db->set('DATE', $date);
		$this->db->set('COOKIES',   $cookies);
		$this->db->set('SOCIAL_ID',   $social_id);

		$result = $this->db->insert('users');
		
		return $result;
    }
	
	/**
	 *  Выборка данных пользователя через мэйл
	 */
	public function get_by_email($email)
    {
        $this->db->select('*');
        $this->db->from('users');
        $this->db->where('EMAIL', $email); 
        $query = $this->db->get();
        
        return $query->result_array();
    }
	
	/**
	 *  Выборка данных пользователя через social_id
	 */
	public function get_by_social($social_id)
    {
        $this->db->select('*');
        $this->db->from('users');
        $this->db->where('SOCIAL_ID', $social_id); 
        $query = $this->db->get();
        
        return $query->result_array();
    }
	
}