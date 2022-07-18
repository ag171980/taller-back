<?php

defined('BASEPATH') or exit('No direct script access allowed');
/*
 * Paneles de Navegación
 *
 * @author Luciano Menez <lucianomenez1212@gmail.com>
 */
class Cursos extends MX_Controller
{

    function __construct()
    {
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
        header('Access-Control-Allow-Origin: *');
    }


    function insert_cursos()
    {
        $curso[0]['id'] = 1;
        $curso[0]['imagen'] = "https://global-uploads.webflow.com/5f5a53e153805db840dae2db/6073fbc8ece768495cc698ca_programador-senior.jpeg";
        $curso[0]['nombre'] = "Lenguaje Complentario";
        $curso[0]['profesores'] = ['Luciano Menez', 'Luis Amarilla'];
        $curso[0]['descripcion'] = "lorem50";
        $curso[0]['alumnos'] = [];
        $curso[0]['carrera'] = ['programación full stack', 'back-end developer'];
        $curso[0]['tipo dictado'] = "a distancia";
        $curso[0]['fecha inicio'] = "01/12/2021";
        $curso[0]['fecha fin'] = "01/03/2022";

        $curso[1]['id'] = 2;
        $curso[1]['imagen'] = "https://gitlab.com/lucianomenez/taller-front/-/raw/4fa8fc0662567d9c519945f41d38d5ac01b5fe95/src/assets/portadas/frontend.png";
        $curso[1]['nombre'] = "Frontend Developer";
        $curso[1]['descripcion'] = "lorem51";
        $curso[1]['profesores'] = ['Luciano Menez', 'Luis Amarilla'];
        $curso[1]['alumnos'] = [];
        $curso[1]['carrera'] = ['frontend', 'designer'];
        $curso[1]['tipo dictado'] = "a distancia";
        $curso[1]['fecha inicio'] = "01/03/2022";
        $curso[1]['fecha fin'] = "01/06/2022";

        $curso[2]['id'] = 3;
        $curso[2]['imagen'] = "https://gitlab.com/lucianomenez/taller-front/-/raw/4fa8fc0662567d9c519945f41d38d5ac01b5fe95/src/assets/portadas/uiux.png";
        $curso[2]['nombre'] = "Diseñador UI/UX";
        $curso[2]['descripcion'] = "lorem53";
        $curso[2]['profesores'] = ['Luciano Menez', 'Luis Amarilla'];
        $curso[2]['alumnos'] = [];
        $curso[2]['carrera'] = ['UI', 'UX', 'Designer'];
        $curso[2]['tipo dictado'] = "a distancia";
        $curso[2]['fecha inicio'] = "01/06/2022";
        $curso[2]['fecha fin'] = "01/09/2022";

        $curso[3]['imagen'] = "https://gitlab.com/lucianomenez/taller-front/-/raw/4fa8fc0662567d9c519945f41d38d5ac01b5fe95/src/assets/portadas/backend.png";
        $curso[3]['id'] = 4;
        $curso[3]['nombre'] = "Backend Developer";
        $curso[3]['descripcion'] = "lorem52";
        $curso[3]['profesores'] = ['Luciano Menez', 'Luis Amarilla'];
        $curso[3]['alumnos'] = [];
        $curso[3]['carrera'] = ['Backend', 'Developer'];
        $curso[3]['tipo dictado'] = "a distancia";
        $curso[3]['fecha inicio'] = "01/09/2022";
        $curso[3]['fecha fin'] = "01/12/2022";

        foreach ($curso as $curso_actual){
          $insert = $this->Model_api->insert('cursos', $curso_actual);
          echo json_encode($insert);
        }
    }

    function insert_cursos_post(){
      header('Access-Control-Allow-Origin: *');
      $rawData = file_get_contents("php://input");
      // this returns null if not valid json
      json_decode($rawData);
      exit;
    }


    function delete_cursos($data = null)
    {
        $query = ['id' => ['$exists' => true]];
        $cursos = $this->Model_api->delete('cursos', $query);
        echo json_encode($cursos);
    }

    function get_cursos()
    {
        $query = ['id' => ['$exists' => true]];
        $cursos = $this->Model_api->get('cursos', $query);
        echo json_encode($cursos);
    }

    function get_usuarios_por_id($id = 3452435)
    {
        $query = ['idu' => $id];
        $usuarios = $this->Model_api->get('users', $query);
        echo json_encode($usuarios);
    }


    function update_usuario($data = null, $id = 3452435)
    {

        $data = array(
            'name' => "Mario",
            'lastname' => "Mendez",
            'checkdate' => "aaaa"
        );




        $query = ['idu' => $id];
        $usuarios = $this->Model_api->update('users', $query, $data);
        echo json_encode($usuarios);
    }

    function multi_update_usuario()
    {
    }







    public function get_estadisticas()
    {

        echo "hola";

        // get the raw POST data
        $data = file_get_contents("php://input");
        // this returns null if not valid json
        return json_decode($data);
    }
}
