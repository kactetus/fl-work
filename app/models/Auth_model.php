<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 *  Модель авторизации
 */
class Auth_model extends CI_Model 
{
    /**
     *  Аутентификация пользователя
     */
    function auth_user($email, $password)
    {
        $this->db->select('*');
        $this->db->from('users');
        $this->db->where('EMAIL', $email); 
        $this->db->where('PASSWORD', $password);
		$this->db->where('SOCIAL_ID', '0');
        $query = $this->db->get();
        
        return $query->result_array();
    }
      
	
    /**
     *  Напоминание пароля
     */
	function remind_pass($email, $data)
	{
	    $this->db->where('EMAIL', $email);
		$this->db->where('SOCIAL_ID', '0');
        $this->db->update('users', $data);

		return true;
	}
	
	/**
	 *  Добавление куки
	 */
	public function add_cookie($data, $email)
	{
	    $this->db->where('EMAIL', $email);
		$this->db->where('SOCIAL_ID', '0');
        $this->db->update('users', $data);

		return true;
	}
	
	/**
	 *  Проверка на существование куки
	 */
	public function check_cookie($cookie)
	{
        $this->db->select('EMAIL');
        $this->db->from('users');
        $this->db->where('COOKIES', $cookie); 
		$this->db->where('SOCIAL_ID', '0');
        $query = $this->db->get();
        
        return $query->result_array();
	}
	
	/**
	 *  Удаление куки
	 */
	public function delete_cookie($cookie, $data)
	{
	    $this->db->where('COOKIES', $cookie);
        $this->db->update('users', $data);
		
		return true;
	}
	
	
	/**
	 *  Получение данных пользователя через его мэйл (с отсутсвием social_id)
	 */
	public function get_by_email_social_id($email)
    {
        $this->db->select('*');
        $this->db->from('users');
        $this->db->where('EMAIL', $email); 
		$this->db->where('SOCIAL_ID', '0');
        $query = $this->db->get();
        
        return $query->result_array();
    }
}