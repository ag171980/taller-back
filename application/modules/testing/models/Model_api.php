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

    public function get_page($id)
    {
        $queryArray =
            [
            [
                '$facet' => [
                    'home' => [
                        [
                            '$match' => [
                                'id' => 'home',
                            ],
                        ],
                    ],
                    'productos' => [
                        [
                            '$lookup' => [
                                'from' => 'container.productos',
                                'pipeline' => [
                                    [

                                        '$match' => [
                                            'borrado' => ['$ne' => 1],
                                        ],
                                    ],
                                ],
                                'as' => 'productos',
                            ],
                        ],
                        [
                            '$project' => [
                              'productos' => 1
                            ],
                        ]
                    ],
                    'extras' => [
                      [
                          '$lookup' => [
                              'from' => 'container.extras',
                              'pipeline' => [
                                  [

                                      '$match' => [
                                          'borrado' => ['$ne' => 1],
                                      ],
                                  ],
                              ],
                              'as' => 'extras',
                          ],
                      ],
                      [
                          '$project' => [
                            'extras' => 1
                          ],
                      ]
                    ],
                ],
            ],

        ];
        $rs = $this->mongowrapper->db->selectCollection('container.home')->aggregateCursor($queryArray);
        $result = iterator_to_array($rs, false);

        return $result[0];
    }

    public function get_home($id)
    {
        $result = $this->db->get('container.home')->result_array();
        return $result;
    }

    public function get_productos($query = null)
    {
        if ($query) {
            $this->db->where($query);
        }

        $result = $this->db->get('container.productos')->result_array();
        return $result;
    }

    public function get_extras($query = null)
    {
        if ($query) {
            $this->db->where($query);
        }

        $result = $this->db->get('container.extras')->result_array();
        return $result;
    }

    public function get_categorias($query = null)
    {
        if ($query) {
            $this->db->where($query);
        }

        $result = $this->db->get('container.categorias')->result_array();
        return $result;
    }

    public function save($container, $data)
    {
        $query = array('id' => $data['id']);
        $this->db->where($query);
        $this->db->update($container, $data);
        return $result;
    }

    public function insert_producto($container, $data)
    {
        if (!$data['id'] || trim($data['id']) == '' || $data['id'] == 'nuevo_producto') {
            $data['id'] = $this->genid($container, 'P');
        }
        $query = array('id' => $data['id']);
        $this->db->where($query);
        $result['id'] = $data['id'];
        unset($data['id']);
        $this->db->update($container, $data);
        return $result['id'];
    }

    private function genid($container, $char)
    {
        $data = array();
        $rs = 0;
        $campos = array('id_num' => 'id_num');
        $this->db->select($campos);
        $this->db->order_by(array('id_num' => "desc"));
        $rs = $this->db->get($container, 1)->result_array()[0]['id_num']/*[0]*/;

        if ($rs) {
            $rs = $rs + 1;
        } else {
            $rs = 1;
        }

        $data['id_num'] = $rs;
        $data['id'] = str_pad($rs, 5, "0", STR_PAD_LEFT);
        $data['id'] = $char . $data['id'];
        $this->db->insert($container, $data);
        return $data['id'];

    }

    public function insert($container, $data)
    {
        return $this->db->insert($container, $data);
    }
}
