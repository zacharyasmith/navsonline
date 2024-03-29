<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Song_model extends MY_Model {

    public $organization_id;

    public function __construct() {
        // Call the CI_Model constructor
        parent::__construct();

        $this->organization_id = $this->session->userdata('organization_id');
    }

    /**
     * Must have title in post data
     */
    public function create() {
        $song_title = $this->input->post('song_title', TRUE);
        $tags = $this->input->post('tags', TRUE);
        $tags_array = explode(',', $tags);

        $user_id = config_item('auth_user_id');

        //setting up insert
        $data = array(
            'title' => $song_title,
            'tags' => json_encode($tags_array),
            'organizations' => json_encode(array($this->organization_id)),
            'date_created' => time(),
            'created_by' => $user_id
        );

        $this->db->insert('song', $data);

        return $this->db->insert_id();
    }

    public function get($id = '') {
        $query = $this->db->query(""
                . "SELECT * "
                . "FROM song "
                . ($id == '' ? '' : "WHERE id='$id'"));

        if ($query->num_rows() == 1) {
            //return the first
            foreach ($query->result_array() as $result)
                return $result;
        } else {
            return $query->result_array();
        }
    }

    /**
     * Returns next dates. Change limit with param
     */
    public function generate_upcoming($user_id = '', $limit = 4) {
        //Ensure organization is set
        $this->organization_id = $this->session->userdata('organization_id');
        //build upcoming query
        $query = $this->db->query(""
                . "SELECT * "
                . "FROM event "
                . "WHERE organization='$this->organization_id' "
                . ($user_id == '' ? '' : "AND  users_matrix LIKE '%$user_id%' ")
                . "AND date > " . time() . " "
                . "ORDER BY date ASC "
                . ($limit > 0 ? "LIMIT $limit" : ''));

        //return the array
        return $query->result_array();
    }

    /**
     * Removes song
     * @param int $sid
     * @return array Response
     */
    public function delete($sid, $organization = '') {
        //get session organization if unset
        if ($organization == '')
            $organization = $this->session->userdata('organization_id');
        //verify organization first
        $query = $this->db->query(""
                . "SELECT organizations FROM song "
                . "WHERE id='$sid' ");
        //grab the first/only one in $result
        foreach ($query->result_array() as $result)
            break;
        $organization_db = $result['organizations'];
        if (strpos($organization_db, '"' . $organization . '"') === FALSE)
            return array('success' => FALSE, 'reason' => 'Unauthorized. User has no control of this organization.');
        //passed organization verification
        //delete the row
        $response = $this->db->delete('song', array('id' => $sid));
        if (!$response)
            return array('success' => FALSE, 'reason' => 'Database query error.');
        else
            return array('success' => TRUE);
    }

    /**
     * @param string $q
     * @param int/string $organization
     * @return type array Results
     */
    public function search($q, $organization) {
        //generate query
        $query = $this->db->query(""
                . "SELECT * FROM song "
                . "WHERE organizations LIKE '%\"$organization\"%' "
                . "AND title LIKE '%$q%'");

        return $query->result_array();
    }

}
