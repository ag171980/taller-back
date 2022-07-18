<?php

defined('BASEPATH') or exit('No direct script access allowed');
/*
 * Paneles de Navegación
 *
 * @author Luciano Menez <lucianomenez1212@gmail.com>
 */
class Notificaciones extends MX_Controller
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



  function get_notificaciones_por_id($id)
  {
    $query = ['idu' => intval($id)];
    $notificaciones = $this->Model_api->get('notificaciones', $query);
    echo json_encode($notificaciones);
  }
  function insert_notificacion()
  {
    $data['titulo'] = "titulo de la notificacion";
    $data['texto'] = "Este es el mensaje de la notificacion";
    $data['estado'] = "nueva";
    $data['idu'] = 5;
    $data['idu_envio'] = 1;
    $notificaciones = $this->Model_api->insert('notificaciones', $data);
    echo json_encode($notificaciones);
  }
}
