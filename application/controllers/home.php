<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	/**
	 * @author Pisyek Kumar
	 * @email pisyek@gmail.com
	 * @link http://www.pisyek.com
	 */

class Home extends CI_Controller {

	function __construct() {
		parent::__construct();
        $this->load->helper('url');
		//$this->load->model('blog_model');
	}

	public function index() {
		$this->load->view('rememberme/index');
	}

	public function login() {
		$this->load->view('rememberme/login');
	}
}

/* End of file blog.php */
/* Location: ./application/controllers/blog.php */