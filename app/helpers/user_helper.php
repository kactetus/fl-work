<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
// ------------------------------------------------------------------------

/**
 * OraTestEngine User Helpers
 *
 * @package		OraTestEngione
 * @subpackage	Helpers
 * @category	Helpers
 * @author		i.okhonko
 */

// ------------------------------------------------------------------------

/**
 * Check if user is logged in.
 *
 * @access  public
 * @return  boolean
 */
if ( ! function_exists('is_user_logged'))
{
	function is_user_logged()
	{
	    $CI =& get_instance();
		return (bool) $CI->session->userdata('is_user_logged');
	}
}


/**
 * Check if user is new.
 *
 * @access  public
 * @return  boolean
 */
if ( ! function_exists('is_user_new'))
{
	function is_user_new()
	{
	    $CI =& get_instance();
		return (bool) $CI->session->userdata('is_user_new');
	}
}


/**
 * Check if user has admin privilegies
 *
 * @access  public
 * @return  boolean
 */
if ( ! function_exists('is_user_admin'))
{
	function is_user_admin()
	{
	    $CI =& get_instance();
		return (bool) $CI->session->userdata('is_user_admin');
	}
}


/**
 * Check if admin is logged-in
 *
 * @access  public
 * @return  boolean
 */
if ( ! function_exists('is_admin_logged'))
{
    function is_admin_logged()
    {
        $CI =& get_instance();
        return (bool) $CI->session->userdata('is_admin_logged');
    }
}
/* End of file user_helper.php */
/* Location: ./app/helpers/user_helper.php */