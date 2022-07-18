<?php

defined('BASEPATH') or exit('No direct script access allowed');
/*
 * Paneles de Navegación
 *
 * @author Luciano Menez <lucianomenez1212@gmail.com>
 */
class Usuarios extends MX_Controller
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
    header('Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS');
    header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token');
  }

  function get_usuarios_by_filter()
  {
    $query = ['comisiones' => ['$in' => [2]]];
    $usuarios = $this->Model_api->get('users', $query);
    echo json_encode($usuarios);
  }

  function get_usuarios_por_id($id = 34554676654)
  {
    $query = ['idu' => intval($id)];
    $usuarios = $this->Model_api->get('users', $query);
    echo json_encode($usuarios);
  }
  function get_usuarios()
  {
    $query = ['idu' => ['$exists' => true]];
    $usuarios = $this->Model_api->get('users', $query);
    echo json_encode($usuarios);
  }
  function update_usuario($data = null, $id = 3452435)
  {
    $data = array(
      'id' => "27",
      'nombre' => "Conrado",
      'apellido' => "Flamini Rodriguez"
    );
    $query = ['idu' => $id];
    $usuarios = $this->Model_api->update('users', $query, $data);
    echo json_encode($usuarios);
  }

  function multi_update_usuario()
  {
  }

  function insert_usuario()
  {
    $usuario[0]['idu'] = 3;
    $usuario[0]['apellido'] = "Conrado";
    $usuario[0]['nombre'] = "Flamini";
    $usuario[0]['email'] = "conradoflamini@gmail.com";
    $usuario[0]['dni'] = "34982750";
    $usuario[0]['direccion'] = "Formosa 112";
    $usuario[0]['ciudad'] = "caba";
    $usuario[0]['provincia'] = "buenos aires";
    $usuario[0]['codigo_postal'] = "1425";
    $usuario[0]['descripcion'] = "lorem50";

    $usuario[1]['idu'] = 4;
    $usuario[1]['apellido'] = "Gutierrez";
    $usuario[1]['nombre'] = "Alexis";
    $usuario[1]['email'] = "ag171980@gmail.com";
    $usuario[1]['dni'] = "11222333";
    $usuario[1]['direccion'] = "Calle falsa 123";
    $usuario[1]['ciudad'] = "CABA";
    $usuario[1]['provincia'] = "Buenos Aires";
    $usuario[1]['codigo_postal'] = "1212";
    $usuario[1]['descripcion'] = "soy yo";

    $usuario[2]['idu'] = 5;
    $usuario[2]['apellido'] = "Durán";
    $usuario[2]['nombre'] = "Nicolas";
    $usuario[2]['email'] = "nico@gmail.com";
    $usuario[2]['dni'] = "22333111";
    $usuario[2]['direccion'] = "Av Rivadavia 3290";
    $usuario[2]['ciudad'] = "CABA";
    $usuario[2]['provincia'] = "Buenos Aires";
    $usuario[2]['codigo_postal'] = "1201";
    $usuario[2]['descripcion'] = "tambien soy yo";
    foreach ($usuario as $user) {
      $insert = $this->Model_api->insert('users', $user);
      echo json_encode($insert);
    }
  }

  // LLave magica para borrar todos los usuarios de una, NO USAR.
  // function delete_all_users()
  // {
  //   $query = ['idu' => ['$exists' => true]];
  //   $cursos = $this->Model_api->delete('users', $query);
  //   echo json_encode($cursos);
  // }
  function delete_usuario()
  {
    $usuario['nombre'] = "Conrado";
    $usuario['apellido'] = "Flamini";
    $usuario['email'] = "conradoflamini@gmail.com";
    $usuario['dni'] = "34982750";
    $usuario['direccion'] = "Formosa 112";
    $usuario['ciudad'] = "caba";
    $usuario['provincia'] = "buenos aires";
    $usuario['codigo_postal'] = "1425";
    $usuario['descripcion'] = "lorem50";
    $delete = $this->Model_api->delete('users', $usuario);
    echo json_encode($delete);
  }
  function get_user()
  {
    $query = ['idu' => ['$exists' => true]];
    $usuarios = $this->Model_api->get('usuarios', $query);
    echo json_encode($usuarios);
  }

  function send_usuario()
  {
    $data = json_decode(file_get_contents("php://input"));
    $query = ['idu' => ['$exists' => true]];
    $usuarios = $this->Model_api->get('usuarios', $query);
    foreach ($usuarios as $users) {
      if ($users['email'] == $data->email) {
        echo json_encode("Este correo ya se encuentra registrado, intente con otro.");
        exit;
      }
    }
    $usuario['idu'] = $data->id;
    $usuario['nombre'] = $data->fullname;
    $usuario['email'] = $data->email;
    $usuario['pass'] = password_hash($data->pass, PASSWORD_DEFAULT);
    $insert = $this->Model_api->insert('usuarios', $usuario);
    echo json_encode("Cuenta registrada correctamente");
  }

  function valid_usuario()
  {
    $data = json_decode(file_get_contents("php://input"));
    $query = ['idu' => ['$exists' => true]];
    $usuarios = $this->Model_api->get('usuarios', $query);
    foreach ($usuarios as $users) {
      if (password_verify($data->pass, $users['pass'])) {
        $usuario['id'] = $users['idu'];
        $usuario['fullname'] = $users['nombre'];
        $usuario['email'] = $users['email'];
        echo json_encode($usuario);
      }
    }
  }
  function delete_users()
  {
    $query = ['idu' => ['$exists' => true]];
    $cursos = $this->Model_api->delete('usuarios', $query);
    echo json_encode($cursos);
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
