<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/*
 * Paneles de Navegación
 *
 * @author Luciano Menez <lucianomenez1212@gmail.com>
 */
class Api extends MX_Controller {

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

        function index(){

          echo "Home APi";
          exit;

        }



        function get_page($id=null,$array = false){
          $data = $this->Model_api->get_page($query);
          if ($array){
             return $data;
          }else{
            $this->output->set_content_type('json','utf-8');
            $this->output->set_output(json_encode($data));
          }

        }


       function get_home($id=null,$array = false){
         $data = $this->Model_api->get_home($query);
         if ($array){
            return $data;
         }else{
           $this->output->set_content_type('json','utf-8');
           $this->output->set_output(json_encode($data));
         }

       }

       function get_productos($id=null,$array = false){
        $query['borrado']=array('$ne' => 1);
        if($id){
          $query['id']=$id;
        }

        $data = $this->Model_api->get_productos($query);
        if ($array){
           return $data;
        }else{
          $this->output->set_content_type('json','utf-8');
          $this->output->set_output(json_encode($data));
        }
      }
      function get_extras($id=null,$array = false){
        $query['borrado']=array('$ne' => 1);
        if($id){
          $query['id']=$id;
        }

        $data = $this->Model_api->get_extras($query);
        if ($array){
           return $data;
        }else{
          $this->output->set_content_type('json','utf-8');
          $this->output->set_output(json_encode($data));
        }
      }

      function get_categorias($array = false){
        $data = $this->Model_api->get_categorias($query);
        if ($array){
           return $data;
        }else{
          $this->output->set_content_type('json','utf-8');
          $this->output->set_output(json_encode($data));
        }

      }

      function change_producto(){
        $this->user->authorize();
        $data = (array)json_decode(file_get_contents("php://input"), TRUE);
        $data_save[$data['prop']]=$data['value'] ;
        $data_save['id']=$data['id'] ;
        $data = $this->Model_api->save('container.productos',$data_save);
      }

      function save_producto(){
        $this->user->authorize();
        $data = (array)json_decode(file_get_contents("php://input"), TRUE);
        unset($data['dialog_borrar']);
        unset($data['dialog']);
        unset($data['_id']);
        $data = $this->Model_api->save('container.productos',$data);
      }

      function insert_producto(){
        $this->user->authorize();
        $data = (array)json_decode(file_get_contents("php://input"), TRUE);
        unset($data['dialog_borrar']);
        unset($data['dialog']);
        unset($data['_id']);
        $id = $this->Model_api->insert_producto('container.productos',$data);
       $response= $this->get_productos($id,true)[0];
        $this->output->set_content_type('json','utf-8');
        $this->output->set_output(json_encode($response));
      }


      function save_home(){
        $this->user->authorize();
        $data_save = (array)json_decode(file_get_contents("php://input"), TRUE);
        unset($data_save['_id']);
        $data = $this->Model_api->save('container.home',$data_save);
      }
}
