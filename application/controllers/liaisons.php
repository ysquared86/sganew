<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Liaisons extends MY_Controller {
    public function __construct() {
        parent::__construct();
        if(!$this->is_sga_liaison() || !$this->logged_in() ) { redirect( site_url() ); }
    }
    
    public function index() {
        $this->edit_orgs();
    }
    
    public function edit_orgs( $org_id = null ) {
        $this->load->model('Organizations');
        if( !isset($org_id) )
        {
            // liaisons/edit_orgs/
            $data['my_orgs'] = $this->Organizations->get_orgs_by_liaison( $this->session->userdata('user')->id );
            $data['title'] = 'BU Law SGA | SGA Liaisons | Edit My Organizations';
            $data['heading'] = 'Edit My Organizations';
            $this->load->view('header', $data);
            $this->load->view('edit_my_orgs', $data);
        }
        else
        {
            // liaisons/edit_orgs/org_id
            $this->Organizations->check_is_liaison_for( $org_id, $this->session->userdata('user')->id );
            $data['org'] = $this->Organizations->get_org( $org_id );
            $data['officers'] = $this->Organizations->get_officers( $org_id );
        }
        $this->load->view('footer', $data);
    }
}

?>