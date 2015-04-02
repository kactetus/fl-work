<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * FL-WORK Core Controllers
 *
 * @package     FL-WORK
 * @subpackage  Core
 * @category    Core
 * @author      Sviridenko D.
 */

// ------------------------------------------------------------------------

/**
 * Базовый контроллер приложения.
 */
class MY_Controller extends CI_Controller
{
    protected $AJAX_METHOD_PREFIX = "_ajax_";

    function __construct()
    {
        parent::__construct();

		//если есть сессия, редирект на user/projects
		
        if ( $this->input->is_ajax_request() ) {
            return;    
        }
		
        // установить шаблон страницы по-умолчанию
        $this->template->set_layout('default');        
        // подключить вспомогательные функции
        $this->load->helper('general');        
    }

    // ------------------------------------------------------------------------

    /**
     * Получить данные $_POST и $_GET выполняемого запроса.
     *
     * @return  array  массив данных запроса.
     */
    public function _post()
    {
        $post = $this->input->post(NULL, TRUE);
        $post = empty($post) ? $this->input->get_post(NULL, TRUE) : $post;
        return $post;
    }
    // ------------------------------------------------------------------------

    /**
     * Переопределить вызов функций для AJAX-запросов.
     *
     * @param  string $method
     * @param  array  $params
     * @return mixed
     */
    public function _remap($method, $params = array())
    {
        $method = $this->input->is_ajax_request() ? "_ajax_".$method : $method;
        if (method_exists($this, $method))
        {
            return call_user_func_array(array($this, $method), $params);
        }
        show_404();
    }
	
    /**
     * Для поддержки кросс-доменных AJAX запросов
     */
	public function _output($output)
	{
		echo $output;
		$this->output->set_header("Access-Control-Allow-Origin: *");
	}
}


/**
 * Контроллер для пользовательской части приложения.
 */
class Frontend_Controller extends MY_Controller
{
    function __construct()
    {
        parent::__construct();
		
        if (! is_user_logged())
        {
            redirect('home');
        }
        // установить шаблон по умолчанию для страницы пользователей
        $this->template->set_layout('user');
    }
}

/**
 * Контроллер для админской части приложения.
 */
class Backend_Controller extends MY_Controller
{
    function __construct()
    {
        parent::__construct();

        if ( ! is_admin_logged())
        {
            redirect('admin');
        }

        // установить шаблон по умолчанию для страницы администаротора
        $this->template->set_layout('admin');
    }
}

/* End of file MY_Controller.php */
/* Location: ./app/core/MY_Controller.php */