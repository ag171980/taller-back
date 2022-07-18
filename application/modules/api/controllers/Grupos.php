<?php

defined('BASEPATH') or exit('No direct script access allowed');
/*
 * Paneles de Navegación
 *
 * @author Luciano Menez <lucianomenez1212@gmail.com>
 */
class Grupos extends MX_Controller {

    function __construct() {
        parent::__construct();
          $this->base_url = base_url();
          $this->module_url = base_url() . $this->router->fetch_module() . '/';
          //$this->user->authorize();
          $this->load->helper('url');
          $this->load->model('app');
          $this->load->model('user/user');
          $this->load->model('Model_api');
          $this->load->config('config');
          $this->idu = $this->user->idu;
        }

    function get_usuarios_by_filter($query){

    //  $query = ['idnumber' => '34737436'];

    }

    function get_grupos_por_id(){
      $grupos = $this->Model_api->get('groups', $query);
      echo json_encode($grupos);
    }


    function update_usuario(){



    }


    function delete_usuario(){



    }


    function insert_usuario(){




    }




    public function get_estadisticas(){

      echo "hola";

      // get the raw POST data
      $data = file_get_contents("php://input");
      // this returns null if not valid json
      return json_decode($data);

    }






}
