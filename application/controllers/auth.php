<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

	function __construct() {
		parent::__construct();

		$this->load->library('form_validation');
		$this->load->helper('url');
	}

	//redirect if needed, otherwise display the user list
	function index() {
		if (!$this->ion_auth->logged_in()) {
			//redirect them to the login page
			redirect('auth/login', 'refresh');
		} else {
			// get user detail
			$data['user'] = '';//$this->ion_auth->user()->row();
			$this->load->view('home/index', $data);
		}
	}

	//log the user in
	function login() {
		//validate form input
		$this->form_validation->set_rules('identity', 'Email', 'required');
		$this->form_validation->set_rules('password', 'Password', 'required');

		if ($this->form_validation->run() == true) { //check to see if the user is logging in

			if ($this->ion_auth->login($this->input->post('identity'), $this->input->post('password'))) { 
				//if the login is successful
				//redirect them back to the home page
				//$this->session->set_flashdata('message', $this->ion_auth->messages());
				redirect('auth', 'refresh');
			} else { 
				//if the login was un-successful
				//redirect them back to the login page
				//$this->session->set_flashdata('message', $this->ion_auth->errors());
				echo $this->ion_auth->errors();
				redirect('auth/login', 'refresh'); //use redirects instead of loading views for compatibility with MY_Controller libraries
			}
		} else {  
			//the user is not logging in so display the login page
			//set the flash data error message if there is one
			//$this->data['message'] = (validation_errors()) ? validation_errors('<p class="error">','</p>') : $this->session->flashdata('message');

			$this->data['identity'] = array('name' => 'identity',
				'id' => 'identity',
				'type' => 'email',
				'placeholder' => 'E-mail',
				'class' => 'form-control',
				'autofocus' => 'autofocus',
				'value' => $this->form_validation->set_value('identity'),
			);
			
			$this->data['password'] = array('name' => 'password',
				'id' => 'password',
				'class' => 'form-control',
				'placeholder' => 'Password',
				'type' => 'password',
			);

			$this->load->view('auth/login', $this->data);
		}
	}

	//log the user out
	function logout()
	{
		$this->data['title'] = "Logout";

		//log the user out
		$logout = $this->ion_auth->logout();
		
		
		//redirect them back to the page they came from
		redirect(base_url().'auth/login', 'refresh');
	}

	function _get_csrf_nonce()
	{
		$this->load->helper('string');
		$key = random_string('alnum', 8);
		$value = random_string('alnum', 20);
		$this->session->set_flashdata('csrfkey', $key);
		$this->session->set_flashdata('csrfvalue', $value);

		return array($key => $value);
	}

	function _valid_csrf_nonce()
	{
		if ($this->input->post($this->session->flashdata('csrfkey')) !== FALSE &&
				$this->input->post($this->session->flashdata('csrfkey')) == $this->session->flashdata('csrfvalue'))
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}

}
