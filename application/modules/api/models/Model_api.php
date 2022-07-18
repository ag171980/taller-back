<?php

/**
 * Funciones para el manejo de datos
 * @author Luciano Menez <lucianomenez1212@gmail.com>
 * @date 18/05/2020
 *
 */

class Model_api extends CI_Model
{

    public function __construct()
    {
        // Call the Model constructor
        parent::__construct();
        $this->load->library('cimongo/cimongo', '', 'db');
        $this->load->config('cimongo');
    }


    public function get($collection, $query = null)
    {
        if ($query) {
            $this->db->where($query);
        }
        $result = $this->db->get($collection)->result_array();
        return $result;
    }


    public function insert($container, $data)
    {
        return $this->db->insert($container, $data);
    }

    public function update($collection, $query, $data)
    {
        $this->db->where($query);
        $result = $this->db->update($collection, $data);
        return $result;
    }
    public function delete($collection, $data)
    {
        $result = $this->db->delete($collection, $data);
        return $result;
    }
}
