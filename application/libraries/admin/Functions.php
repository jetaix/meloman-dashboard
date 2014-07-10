<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Functions {

	function get_loged()
	{
		$CI =& get_instance();
		if ($CI->session->userdata('logged_in')):
			return true;
		else:
			$data = current_url();
			$this->get_redirect($data);
			return $data;
		endif;
	}

	function get_redirect($medias)
	{
		$CI =& get_instance();
		$data = $CI->session->set_flashdata('alert', 'Cette page est protégée par un mot de passe.' . $medias).redirect(base_url('admin'));

		return $data;
	}

	function get_user_data()
	{
		$data = array(
			'id_user' => $this->get_user_id(),
			'login'   => $this->get_user_login(),
			'level'	  => $this->get_user_level()
		);

		return $data;
	}

	function get_user_id()
	{
		$CI =& get_instance();
		$data = $CI->session->userdata('logged_in');

		return $data['id'];
	}

	function get_user_login()
	{
		$CI =& get_instance();
		$data = $CI->session->userdata('logged_in');

		return $data['login'];
	}

	function get_user_level()
	{
		$CI =& get_instance();
		$data = $CI->session->userdata('logged_in');

		return $data['level'];
	}

	function get_all_content()
	{
		$CI =& get_instance();
		$data = $CI->model_content->get_contents('', '');

		return $data;
	}

	function get_all_user()
	{
		$CI =& get_instance();
		$data = $CI->model_user->get_users();

		return $data;
	}

	function get_all_tags()
	{
		$CI =& get_instance();
		$data = $CI->model_tag->get_tags();

		return $data;
	}

	function get_all_categories()
	{
		$CI =& get_instance();
		$data = $CI->model_category->get_categories();

		return $data;
	}

}


/* End of file admin/Functions.php */
/* Location: ./application/librairies/admin/Functions.php */