<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('model_user');
		$this->load->library(array('encrypt', 'form_validation', 'session', 'admin/functions'));
		$this->load->helper(array('functions'));
		session_start();
		if (isset($_GET["profiler"])):
			$this->output->enable_profiler(TRUE);
		endif;
	}

	public function index()
	{
		if (!$this->session->userdata('logged_in')):

			$this->form_validation->set_rules('username', 'Username', 'trim|required|xss_clean');
			$this->form_validation->set_rules('password', 'Password', 'trim|required|xss_clean|callback_check_database');

			if ($this->form_validation->run() == FALSE):
				$data['title'] = 'Connexion';
				$this->load->view('admin/view_form_login', $data);

			else:
				if ($this->model_user->get_songs_by_user($this->functions->get_user_id())->num_rows() == 0):
					$this->session->set_flashdata('success', 'Bienvenue sur votre dashboard Meloman, pour ajouter une musique, cliquez <a href="' . base_url('admin/content/edit') . '">ici</a>');
					redirect(base_url('admin/content'));
				else:	
					$this->session->set_flashdata('success', 'Bienvenue sur votre dashboard Meloman.');
					redirect(base_url('admin/content'));
				endif;

			endif;

		elseif ($this->session->userdata('logged_in')):
			redirect(base_url('admin/content'));

		endif;
	}

	function check_database($password)
	{
		$login = $this->input->post('username');
		$query = $this->model_user->login($login, $password);

		if ($query->num_rows() == 1):
			$sess_array = array();
			foreach ($query->result() as $row):
				$sess_array = array(
					'id'    => $row->id_user,
					'login' => $row->pseudo_user,
					'level' => $row->level_user
				);
				$this->session->set_userdata('logged_in', $sess_array);
			endforeach;
			return TRUE;

		else:
			$this->form_validation->set_message('check_database', 'Login ou mot de passe incorrect');
			return FALSE;

		endif;
	}

	public function logout()
	{
		$this->session->unset_userdata('logged_in');
		$this->session->set_flashdata('success', 'Vous êtes désormais déconnecté(e).');
		session_destroy();
		redirect(base_url('admin'), 'refresh');
	}

	public function reset_password()
	{
		$data['title'] = 'Réinitialisation du mot de passe';
		$this->form_validation->set_rules('u_email', 'Email', 'trim|required|xss_clean|valid_email');
		$u_email = $this->input->post('u_email');

		if ($this->form_validation->run() !== FALSE):

			if ($this->model_user->check_email($u_email)->num_rows() == 1):
				$new_pass = random_string('alnum', 10);
				$this->model_user->reset_password($u_email, $new_pass);
				echo $new_pass;
				die();
				@mail($u_email, 'Reset de votre mot de passe', 'Votre nouveau mot de passe est ' . $new_pass);
				$this->session->set_flashdata('success', 'Vous allez recevoir un email avec votre nouveau mot de passe.');
				redirect(base_url('admin'));

			else:
				$this->session->set_flashdata('alert', 'Cet email n\'existe pas.');
				redirect(current_url());
			endif;

		endif;

		$this->load->view('admin/view_form_reset', $data);
	}

}


/* End of file admin.php */
/* Location: ./application/controllers/admin/admin.php */