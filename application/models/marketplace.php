<?php
class Marketplace extends CI_Model {
	public function __construct() {
        parent::__construct();
    }
	
	public function fetch_all_listings( $limit = null, $start = null )
	{
		$this->db->select('m.*, c.category, mt.author, mt.isbn, mt.edition, mt.year');
		$this->db->from('market m');
		$this->db->join('market_categories c', 'm.mkt_cat_id = c.id', 'left');
		$this->db->join('market_books_meta mt', 'm.id = mt.mkt_id', 'left');
		$this->db->order_by('m.created', 'desc');
		if(isset($limit) && isset($start)) {
			$this->db->limit($limit, $start);
		}
		
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            return $query->result();
        }
        return false;
	}
	
	public function fetch_my_listings( $user_id, $limit = null, $start = null )
	{
		$this->db->select('m.*, c.category, mt.author, mt.isbn, mt.edition, mt.year');
		$this->db->from('market m');
		$this->db->join('market_categories c', 'm.mkt_cat_id = c.id', 'left');
		$this->db->join('market_books_meta mt', 'm.id = mt.mkt_id', 'left');
		$this->db->where('m.user_id', $user_id);
		$this->db->order_by('m.created', 'desc');
		if(isset($limit) && isset($start)) {
			$this->db->limit($limit, $start);
		}
		
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            return $query->result();
        }
        return false;
	}
	
	public function search_listings( $post, $limit = null, $start = null )
	{
		$this->db->select('m.*, c.category, mt.author, mt.isbn, mt.edition, mt.year');
		$this->db->from('market m');
		$this->db->join('market_categories c', 'm.mkt_cat_id = c.id', 'left');
		$this->db->join('market_books_meta mt', 'm.id = mt.mkt_id', 'left');
		
		// build search query
		if( $post['search'] != '' )
		{
			$this->db->where('(m.title LIKE "%'.$post['search'].'%" OR mt.author LIKE "%'.$post['search'].'%" OR mt.isbn LIKE "%'.$post['search'].'%")');
		}
		if( $post['cat_id'] != '' )
		{
			$this->db->where('m.mkt_cat_id', $post['cat_id']);
		}
		if( $post['price_min'] != '' )
		{
			$this->db->where('m.price >= ', $post['price_min']);
		}
		if( $post['price_max'] != '' )
		{
			$this->db->where('m.price <= ', $post['price_max']);
		}
		
		// build order by query
		switch( $post['sort_by'] ) {
			case 'created':
				$this->db->order_by('m.created', 'desc');
			break;
			
			case 'title':
				$this->db->order_by('m.title', 'asc');
			break;
		
			case 'price_lth':
				$this->db->order_by('m.price', 'asc');
			break;
			
			case 'price_htl':
				$this->db->order_by('m.price', 'desc');
			break;
			
			default:
				$this->db->order_by('m.created', 'desc');
			break;
		}
		
		if(isset($limit) && isset($start)) {
			$this->db->limit($limit, $start);
		}		
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            return $query->result();
        }
        return false;
	}
	
	public function get_listing( $id )
	{
		$this->db->select('m.*, c.category, mt.author, mt.isbn, mt.edition, mt.year, u.firstname, u.lastname, u.email_pref, ul.path');
		$this->db->from('market m');
		$this->db->join('market_categories c', 'm.mkt_cat_id = c.id', 'left');
		$this->db->join('market_books_meta mt', 'm.id = mt.mkt_id', 'left');
		$this->db->join('market_uploads ul', 'm.id = ul.mkt_id', 'left');
		$this->db->join('users u', 'm.user_id = u.id', 'left');
		$this->db->where('m.id', $id);
		$result = $this->db->get()->result();
		return $result[0];
	}
	
	public function get_attachments( $id )
	{
		return $this->db->select('*')->from('market_uploads')->where('mkt_id', $id)->get()->result();
	}
	
	public function insert_listing( $post )
	{	
		$listing = array(
			'mkt_cat_id' => $post['mkt_cat_id'],
			'user_id' => $post['user_id'],
			'title' => $post['title'],
			'description' => $post['description'],
			'condition' => $post['condition'],
			'price' => number_format($post['price'], 2),
			'created' => time()
		);
		$this->db->insert('market', $listing);
		$listing_id = $this->db->insert_id();
		
		if($post['mkt_cat_id'] == 1)
		{
			// if book, insert metadata
			$meta = array(
				'mkt_id' => $listing_id,
				'author' => $post['author'],
				'isbn' => $post['isbn'],
				'edition' => $post['edition'],
				'year' => $post['year']
			);
			$this->db->insert('market_books_meta', $meta);
		}
		
		return $listing_id;
	}
	
	public function insert_upload( $listing_id, $filename )
	{
		$upload = array(
			'mkt_id' => $listing_id,
			'path' => $filename,
			'created' => time()
		);
		$this->db->insert('market_uploads', $upload);
	}
	
	public function update_listing( $post )
	{
		$listing = array(
			'mkt_cat_id' => $post['mkt_cat_id'],
			'user_id' => $post['user_id'],
			'title' => $post['title'],
			'description' => $post['description'],
			'condition' => $post['condition'],
			'price' => number_format($post['price'], 2)
		);
		$this->db->where( array('id' => $post['id'], 'user_id' => $this->session->userdata('user')->id) );
		$this->db->update('market', $listing);
		
		if(!empty($post['delete_img']))
		{
			foreach($post['delete_img'] as $img_id)
			{
				$this->delete_upload( $img_id );
			}
		}
	}
	
	public function delete_upload( $img_id )
	{
		$result = $this->db->get_where('market_uploads', array('id' => $img_id))->result();
		unlink('./uploads/marketplace/'.$result[0]->path);
		$this->db->delete('market_uploads', array('id' => $img_id));
	}
	
	public function delete_listing( $listing_id )
	{
		$uploads = $this->db->select('*')->from('market_uploads')->where('mkt_id', $listing_id)->get()->result();
		foreach($uploads as $upload)
		{
			unlink('./uploads/marketplace/'.$upload->path);
		}
		$this->db->delete('market_uploads', array('mkt_id' => $listing_id));
		$this->db->delete('market_books_meta', array('mkt_id' => $listing_id));
		$this->db->delete('market', array('id' => $listing_id));
	}
	
	public function check_is_listing_author( $user_id, $listing_id )
	{
		$this->db->from('market');
		$this->db->where( array('id' => $listing_id, 'user_id' => $user_id) );
		return ($this->db->count_all_results() > 0);
	}
}
?>