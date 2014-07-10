<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Model_content extends CI_Model {

	function get_contents($last_content, $state)
	{
		$this->db->select('id_song, title_song, artist_song, image_song, state_song, cdate_song, udate_song, pdate_song, id_user, pseudo_user, title_category');
		$this->db->from('m_song');
		$this->db->join('m_category', 'm_category.id_category = m_song.fk_id_category');
		$this->db->join('m_user', 'm_user.id_user = m_song.fk_id_user');
		if (!empty($last_content)):
		$this->db->where('cdate_song <=', unix_to_human(now(), TRUE, 'eu') );
		endif;	
		if (!empty($state)):
		$this->db->where('state_song', 1);
		endif;
		$this->db->order_by('id_song', 'DESC');

		$query = $this->db->get();
		return $query;
	}

	function get_contents_by_category($category)
	{
		$this->db->select('id_song, title_song, artist_song, image_song, state_song, cdate_song, udate_song, pdate_song, title_category')
				 ->join('m_category', 'm_category.id_category = m_song.fk_id_category')
				 ->from('m_song')
				 ->like('title_category', $category)
				 ->order_by('id_song', 'DESC');

		$query = $this->db->get();
		return $query;
	}

	function get_content_by_bg($id_bg)
	{
		$this->db->select('id_song, title_song')
				 ->from('m_bg')
				 ->join('m_song', 'm_song.fk_id_bg = m_bg.id_bg')
				 ->where('fk_id_bg', $id_bg);

		$query = $this->db->get();
		return $query; 
	}


	function get_tags($id_song){
		$this->db->select('id_tag, name_tag')
				 ->from('m_tag')
				 ->join('m_songtags', 'm_songtags.fk_id_tag = m_tag.id_tag')
				 ->join('m_song', 'm_song.id_song = m_songtags.fk_id_song')
				 ->where('m_song.id_song', $id_song);

		$query = $this->db->get();
		return $query; 
	}

	function get_tags_by_tag($id_song, $id_tag){
		$this->db->select('id_tag, name_tag')
				 ->from('m_tag')
				 ->join('m_songtags', 'm_songtags.fk_id_tag = m_tag.id_tag')
				 ->join('m_song', 'm_song.id_song = m_songtags.fk_id_song')
				 ->where('m_song.id_song', $id_song)
				 ->where('id_tag', $id_tag);
		$query = $this->db->get();
		return $query; 
	}


	function get_contents_by_tag($tag)
	{
		$this->db->select('id_song, title_song, artist_song, image_song, state_song, cdate_song, udate_song, title_category')
				 ->from('m_tag')
				 ->join('m_songtags', 'm_songtags.fk_id_tag = m_tag.id_tag')
				 ->join('m_song', 'm_song.id_song = m_songtags.fk_id_song')
				 ->join('m_category', 'm_category.id_category = m_song.fk_id_category')
				 ->like('name_tag', $tag);

		$query = $this->db->get();
		return $query;
	}

	function get_contents_by_author($author)
	{
		$this->db->select('id_song, title_song, artist_song, image_song, state_song, cdate_song, udate_song, id_user, pseudo_user, title_category')
				 ->from('m_song')
				 ->join('m_category', 'm_category.id_category = m_song.fk_id_category')
				 ->join('m_user', 'm_user.id_user = m_song.fk_id_user')
				 ->where('artist_song', $author)
				 ->order_by('id_song', 'DESC');

		$query = $this->db->get();
		return $query;
	}


	function get_content($id_song, $title_song)
	{
		$this->db->select('id_song, title_song, artist_song, punchline_song, image_song, vendor_song, state_song, cdate_song, udate_song, pdate_song, url_soundcloud, pseudo_user, fk_id_user, fk_id_category, fk_id_bg');
		$this->db->from('m_song');
		$this->db->join('m_user', 'm_user.id_user = m_song.fk_id_user');
		if (empty($c_title)):
		$this->db->where('id_song', $id_song);
		else:
		$this->db->where('title_song', $title_song);
		endif;
		$this->db->limit(1);

		$query = $this->db->get();
		return $query;
	}

	function get_authors()
	{
		$this->db->distinct('')
				 ->select('artist_song')
				 ->from('m_song')
				 ->order_by('artist_song', 'ASC');

		$query = $this->db->get();
		return $query;
	}

/*	function check_title($id_song, $title_song)
	{
		$this->db->select('title_song')
				 ->from('m_song')
				 ->where('id_song <>', $id_song)
				 ->where('title_song', $title_song);

		$query = $this->db->get();
		return $query;
	}*/

	function check_id_soundcloud($id_song, $id_soundcloud)
	{
		$this->db->select('id_soundcloud')
				 ->from('m_song')
				 ->where('id_song <>', $id_song)
				 ->where('id_soundcloud', $id_soundcloud);

		$query = $this->db->get();
		return $query;
	}

	function check_url_soundcloud($id_song, $url_soundcloud)
	{
		$this->db->select('url_soundcloud')
				 ->from('m_song')
				 ->where('id_song <>', $id_song)
				 ->where('url_soundcloud', $url_soundcloud);

		$query = $this->db->get();
		return $query;
	}

	function create_content($id_user, $title_song, $artist_song, $punchline_song, $image_song, $vendor_song, $state_song, $pdate_song, $id_soundcloud, $url_soundcloud, $duration_soundcloud, $id_category, $id_bg)
	{
		$data = array(
			'fk_id_user'		  => $id_user,
			'title_song'		  => $title_song,
			'artist_song'		  => $artist_song,
			'punchline_song'	  => $punchline_song,
			'image_song'		  => $image_song,
			'vendor_song'		  => $vendor_song,
			'state_song'		  => $state_song,
			'cdate_song'		  => unix_to_human(now(), TRUE, 'eu'),
			'udate_song'		  => unix_to_human(now(), TRUE, 'eu'),
			'pdate_song'		  => $pdate_song,
			'id_soundcloud'		  => $id_soundcloud,
			'url_soundcloud'	  => $url_soundcloud,
			'duration_soundcloud' => $duration_soundcloud,
			'fk_id_category' 	  => $id_category,
			'fk_id_bg'			  => $id_bg
		);

		$this->db->insert('m_song', $data);
	}

	function create_songtags($id_song, $id_tag)
	{
		$data = array(
			'id_song' => $id_song,
			'id_tag'  => $id_tag,
		);

		$this->db->insert('m_songtags', $data);
	}
	
	function update_content($title_song, $artist_song, $punchline_song, $image_song, $vendor_song, $state_song, $udate_song, $pdate_song, $id_soundcloud, $url_soundcloud, $duration_soundcloud, $id_category, $id_bg, $id_song)
	{
		if ($udate_song === TRUE):
			$data = array(
				'title_song'		  => $title_song,
				'artist_song'	 	  => $artist_song,
				'punchline_song' 	  => $punchline_song,
				'image_song'	 	  => $image_song,
				'vendor_song'	 	  => $vendor_song,
				'state_song'	 	  => $state_song,
				'udate_song'	 	  => unix_to_human(now(), TRUE, 'eu'),
				'pdate_song'	 	  => $pdate_song,
				'id_soundcloud'	 	  => $id_soundcloud,
				'url_soundcloud'	  => $url_soundcloud,
				'duration_soundcloud' => $duration_soundcloud,
				'id_soundcloud'	 	  => $id_soundcloud,
				'fk_id_category'	  => $id_category,
				'fk_id_bg'		 	  => $id_bg
			);
		else:
			$data = array(
				'title_song'		  => $title_song,
				'artist_song'		  => $artist_song,
				'image_song'		  => $image_song,
				'punchline_song'	  => $punchline_song,
				'vendor_song'		  => $vendor_song,
				'pdate_song'	 	  => $pdate_song,
				'state_song'		  => $state_song,
				'id_soundcloud'		  => $id_soundcloud,
				'url_soundcloud'	  => $url_soundcloud,
				'duration_soundcloud' => $duration_soundcloud,
				'fk_id_category'	  => $id_category,
				'fk_id_bg'			  => $id_bg
			);
		endif;

		$this->db->where('id_song', $id_song);
		$this->db->update('m_song', $data);
	}

	function delete_content($id_song)
	{
		$this->db->where('id_song', $id_song)
				 ->delete('m_song'); 
	}

	function delete_content_songtags($id_song)
	{
		$this->db->where('fk_id_song', $id_song)
				 ->delete('m_songtags');
	}

	function get_content_by_category($id_category)
	{
		$this->db->select('id_song, title_song')
				 ->from('m_song')
				 ->join('m_category', 'm_song.fk_id_category = m_category.id_category')
				 ->where('m_category.id_category', $id_category);

		$query = $this->db->get();
		return $query;
	}

	function get_content_by_tag($id_tag)
	{
		$this->db->select('id_song, title_song')
				 ->from('m_song')
				 ->join('m_songtags', 'm_songtags.fk_id_song = m_song.id_song')
				 ->join('m_tag', 'm_tag.id_tag = m_songtags.fk_id_tag')
				 ->where('m_tag.id_tag', $id_tag);

		$query = $this->db->get();
		return $query;
	}

	function get_content_by_user($id_user, $limit)
	{
		$this->db->select('id_song, title_song, artist_song, state_song, cdate_song, udate_song, title_category');
		$this->db->from('m_song');
		$this->db->join('m_category', 'm_song.fk_id_category = m_category.id_category');
		$this->db->join('m_user', 'm_user.id_user = m_song.fk_id_user');
		$this->db->where('m_user.id_user', $id_user);
		$this->db->order_by('id_song', 'DESC');

		if (!empty($limit)):
			$this->db->limit($limit);
		endif;

		$query = $this->db->get();
		return $query;
	}

	function get_all_bg_image()
	{
		$this->db->select('id_bg, image_bg')
				 ->from('m_bg')
				 ->order_by('id_bg', 'DESC');

		$query = $this->db->get();
		return $query;
	}

}


/* End of file model_content.php */
/* Location: ./application/models/admin/model_content.php */