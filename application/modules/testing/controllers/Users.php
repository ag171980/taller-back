<?php
/**
 *
 * @author Leonardo Gómez <leonardo.gmz@gmail.com>
 * @date 18/11/2020
 *
 */


use chriskacerguis\RestServer\RestController;
use GuzzleHttp\Client;

defined('BASEPATH') or exit('No direct script access allowed');

require_once APPPATH . 'modules/api/controllers/ApiBaseWithUser.php';

class Users extends ApiBaseWithUser
{
    /**
     * @var UsersModel
     */
    private $model = null;


    /**
     * @var Minio
     */
    private $miniod = null;

    /**
     * @var Profile
     */
    private $userProfile = null;

    /**
     * @var Client
     */
    private $client = null;

    /**
     * @var Model_api
     */
    private $model_api = null;

    public function __construct()
    {
        parent::__construct();

        $this->model = $this->usersModel;

        $this->load->model('Model_api');
        $this->model_api = $this->Model_api;

        $this->load->module('api/Minio');
        $this->miniod = $this->minio;

        $this->load->module('user/Profile');
        $this->userProfile = $this->profile;

    }

    public function get_usuarios_creados_por_mi(){
        $data = $this->getJsonRequest();

        $organismos = $this->user['organismos'];

        $query = [
            'organismos' => ['$in' =>  $organismos],
            'group' => [ '$in' => [5007]],
        ];
        $fields = ['name', 'lastname', 'idu', 'idnumber' , 'email', 'sector', 'visualizacion'];
        $result = $this->model_api->get_collection_by_query('users', $query, $fields);
        foreach ($result as &$usuario) {
            if (is_array($usuario)){

                if (!array_key_exists('visualizacion', $usuario)) {
                    $usuario['visualizacion'] = 'Ver todo';
                }

                $usuario['estado'] = "Activo";
                $usuario['nombre'] = $usuario['name']." ".$usuario['lastname'];
                $usuario['rol'] = $this->model->getDisplayNameFromPerfil($this->model->getPerfilReferente());
            }
        }
        $query2 = [
            'dbobj.organismos' => ['$in' =>  $organismos],
            'dbobj.group' => [ '$in' => [5007]],
        ];
        $fields2 = ['dbobj.name', 'dbobj.lastname', 'dbobj.idnumber' ,'dbobj.rol' , 'dbobj.email', 'dbobj.sector', 'dbobj.visualizacion'];
        $result2 = $this->model_api->get_collection_by_query('users_token', $query2, $fields2);
        foreach ($result2 as &$usuario_pendiente) {
            if (is_array($usuario_pendiente)){
                if (!array_key_exists('visualizacion', $usuario_pendiente['dbobj'])) {
                    $usuario_pendiente['dbobj']['visualizacion'] = 'Ver todo';
                }

                $usuario_pendiente['dbobj']['estado'] = "Pendiente";
                $usuario_pendiente['dbobj']['nombre'] = $usuario_pendiente['dbobj']['name']." ".$usuario_pendiente['dbobj']['lastname'];
                $result[]= $usuario_pendiente['dbobj'];
            }
        }
        
        $this->return($result);
    }


    public function get_logged_user() {

        $this->return($this->user);

    }

    public function uploadAvatar($file) {

        ob_start();
        $this->userProfile->upload_file($file);
        $output = ob_get_clean();

        $this->return(json_decode($output));
    }

    public function changePassword() {
        $updatable_fields = ['newPassword', 'newPasswordRepeat'];

        $data = $this->getJsonRequest();

        $validations = [
            'newPassword' => function($field) {

                $passwordCheckPattern = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#$%^&*._?¿¡+:])(?=.{8,})/';

                $match = preg_match($passwordCheckPattern, $field);

                if ($match == 0) {
                    $this->return('La contraseña debe contener 8 dígitos que debe incluir números, símbolos y una combinación de letras mayúsculas y minúsculas.',"wrong");
                }

                return true;
            },
            'newPasswordRepeat' => function($field, $fields) {
                if (strcmp($field, $fields['newPassword']) !== 0) {
                    $this->return('Las contraseñas deben coincidir.', "wrong");
                }
                return true;
            }
        ];

        $fields = $this->validateFields($updatable_fields, $data, true, $validations);

        $newPassword = $fields['newPassword'];

        $passUpdated = $this->model->changePassword($this->user['idu'], $newPassword);

        if ($passUpdated) {
            $this->return(["user" => $this->user['name']." ".$this->user['lastname'], "message" => "La contraseña se ha cambiado con éxito."]);
        } else {
            $this->return("La contraseña no se pudo modificar.", "wrong");
        }

    }

    public function get_by_idu($idu){

        $usuario = $this->model->getUserByIdu((int)$idu);
        if (!$usuario) {
            $this->returnError("El usuario no existe.");
        }
        $usuario['organismo'] = $usuario['organismos_data'][0]['name'];
        $usuario['accesos'] = array();
        foreach ($usuario['groups_data'] as $grupo){
           if (isset($grupo['accesos'])){
             foreach ($grupo['accesos'] as $key => $acceso) {
               switch ($acceso['type']) {
                   case "aplicacion":
                       $usuario['accesos']['aplicaciones'][] =  $acceso;
                       break;
                   case "acceso":
                       $usuario['accesos']['detallados'][] =  $acceso;
                       break;
                   case "label":
                       $usuario['accesos']['simples'][] =  $acceso;
                       break;
               }
               $usuario['accesos'][] =  $acceso;
             }
         } 
        }
        if( is_array($usuario['accesos']['aplicaciones'])){
          $usuario['accesos']['aplicaciones'] = array_unique($usuario['accesos']['aplicaciones'], SORT_REGULAR);
        }
        if (is_array(  $usuario['accesos']['detallados'])){
          $usuario['accesos']['detallados'] = array_unique($usuario['accesos']['detallados'], SORT_REGULAR);
        }
        if(is_array($usuario['accesos']['simples'])){
          $usuario['accesos']['simples'] = array_unique($usuario['accesos']['simples'], SORT_REGULAR);
        }

        $this->return($usuario);
    }

    public function update(){

        $updatable_fields = [
            'idu',
            'name',
            'edited_by',
            'visualizacion',
            'lastname',
            'sector',
            'email',
            'idnumber',
            'shortcuts',
            'darkMode',
            'tema',
            'color_tema',
            'group',
            'organismos'
        ];

        $data = $this->getJsonRequest();

        $update_fields = $this->validateFields($updatable_fields, $data);

        $idu = $update_fields['idu'];

        $usuario = $this->model->getUserByIdu((int)$idu);
        if (!$usuario) {
            $this->returnError("El usuario no existe.");
        }

        $updated = $this->model->updateUserByIdu((int)$idu, $update_fields);

        if (!$updated || $updated['ok'] == 0) {
            $this->returnError("El usuario no se puede actualizar.");
        }

        $usuario = $this->model->getUserByIdu((int)$idu, false);

        $this->return($usuario);
    }


    public function update_by_idu($idu){

        $updatable_fields = ['name', 'lastname', 'email', 'shortcuts', 'darkMode', 'tema', 'color_tema'];

        $data = $this->getJsonRequest();

        $update_fields = $this->validateFields($updatable_fields, $data);

        $usuario = $this->model->getUserByIdu((int)$idu);
        if (!$usuario) {
            $this->returnError("El usuario no existe.");
        }

        $updated = $this->model->updateUserByIdu((int)$idu, $update_fields);

        if (!$updated || $updated['ok'] == 0) {
            $this->returnError("El usuario no se puede actualizar.");
        }

        $usuario = $this->model->getUserByIdu((int)$idu, false);

        $this->return($usuario);
    }

    public function get_stats_panel($organismo){
        $año_prioritario = '2021';
        $bloqueDeData = [
            "funcionario" => [
                
            ],
            "regular" => [
                
            ],
        ];
        /* base de funcionario */

        $bloqueDeData['funcionario']['base'][1]['elemento'] = "Acciones Estatales $año_prioritario";
        $bloqueDeData['funcionario']['base'][2]['elemento'] = "Indicadores $año_prioritario";
        $bloqueDeData['funcionario']['base'][3]['elemento'] = "Consultas al catálogo $año_prioritario";

        $bloqueDeData['funcionario']['base'][2]['valor'] =
            $this->Model_api->get_collection_by_query_fast('mapa_indicadores',
            ['_id' => ['$exists' => true], 'idwf' => "accion_estatal_2021"])['result'][0]['count'];
        $bloqueDeData['funcionario']['base'][3]['valor'] =
            $this->Model_api->get_resultados_catalogo("container.registros", [],  'dna3', "catalogo-covid19","2021")['result'][0]['count'];
        


        $bloqueDeData['funcionario']['alter'] = $this->get_alter();
        
        /* base de comun */
        $bloqueDeData['regular']['base'][1]['elemento'] = 'Acciones Estatales ingresadas';
        $bloqueDeData['regular']['base'][2]['elemento'] = 'Servicios Esenciales a la ciudadanía';
        $bloqueDeData['regular']['base'][3]['elemento'] = 'Usuarios creados';

        $idwf = "accion_estatal_2021";

        if (($organismo == "0") or ($organismo == "NaN")) {
            $bloqueDeData['funcionario']['base'][1]['valor'] =
                $this->Model_api->get_collection_by_query_fast('case',
                ['status' => 'open' , 'idwf' => "accion_estatal_2021"])['result'][0]['count'];

            $bloqueDeData['regular']['base'][1]['valor'] =
                $this->Model_api->get_collection_by_query_fast('case',
                ['status' => 'open' , 'idwf' => $idwf])['result'][0]['count'];
            $bloqueDeData['regular']['base'][2]['valor'] =
                $this->Model_api->get_collection_by_query_fast('container.pages',
                ['post_type' => 'items_catalogo','post_status' => 'published'], [] , 'mae_cms')['result'][0]['count'];
            $bloqueDeData['regular']['base'][3]['valor'] =
                $this->Model_api->get_collection_by_query_fast('users',
                ['idu' => ['$exists' => true]])['result'][0]['count'];
        }
        else {
            $bloqueDeData['funcionario']['base'][1]['valor'] =
                $this->Model_api->get_collection_by_query_fast('case',
                ['status' => 'open' , 'idwf' => "accion_estatal_2021", 'organismo_id' => $organismo])['result'][0]['count'];
            $bloqueDeData['regular']['base'][1]['valor'] =
                $this->Model_api->get_collection_by_query_fast('case',
                ['status' => 'open' , 'idwf' => $idwf, 'organismo_id' => $organismo])['result'][0]['count'];

            if($bloqueDeData['regular']['base'][1]['valor'] == null){
                $bloqueDeData['regular']['base'][1]['valor'] = 0;
            }
            $bloqueDeData['regular']['base'][2]['valor'] =
                $this->Model_api->get_collection_by_query_fast('container.pages',
                ['post_type' => 'items_catalogo', 'organismos.value' => $organismo,
                'post_status' => 'published'], [] , 'mae_cms')['result'][0]['count'];
            $bloqueDeData['regular']['base'][3]['valor'] =
                $this->Model_api->get_collection_by_query_fast('users',
                [
                    'idu' => ['$exists' => true],
                    'organismos' => ['$in' =>  [$organismo]],
                ])['result'][0]['count'];
        }

        $bloqueDeData['dotacion'] = $this->get_dotacion();

        $this->return($bloqueDeData);
    }

    public function usersForEdition(){

        $data = $this->getJsonRequest();

        $result = $this->model->usersForEdition($this->user);

        $this->return($result);
    }

    public function createNewUser()
    {
        $data = $this->getJsonRequest();

        $updatable_fields = ['name', 'lastname', 'email', 'sector', 'permiso', 'visualizacion', 'estado'];

        $fields = $this->validateFields($updatable_fields, $data);

        $this->model->insertUser($fields);
    }

    public function getReferentes() {

        $users = $this->model->getUsersByPerfilesOrganismos([$this->model->getPerfilReferente()], $this->user['organismos']);

        $this->return($users);

    }


    public function check_user(){
        $data = $this->getJsonRequest();
        $result = $this->Model_api->get_collection_by_query_fast('users', $data)['result'][0]['count'];
        if ($result){
          $flag = true;
        }else{
          $flag = false;
        }
        $this->return($flag);
    }


    function get_dotacion(){
      $query = ['_id' => ['$exists' => true]];
      $result = $this->Model_api->get_collection_by_query_fast('biep', $query)['result'][0]['count'];
      $query = ['_id' => ['$exists' => true], "SEXO" => "F"];
      $mujeres = $this->Model_api->get_collection_by_query_fast('biep', $query)['result'][0]['count'];
      $query = ['_id' => ['$exists' => true], "SEXO" => "M"];
      $hombres = $this->Model_api->get_collection_by_query_fast('biep', $query)['result'][0]['count'];
      /*$otrxs = $result - $mujeres - $hombres;*/
      $rtnArray['dotacion'] = number_format($mujeres + $hombres, 0,",", ".");
      $rtnArray['dotacionMasculina'] = number_format(($hombres * 100) / $result, 2,",", ".");
      $rtnArray['dotacionFemenina'] = number_format(($mujeres * 100) / $result,2,",", ".");

      $result = [
        'total' => 191832,
        'varones' => 96137,
        'mujeres' => 95675
      ];
      
      $result['dotacion'] = number_format( $result['total'], 0,",", ".");
      $result['dotacionMasculina'] = number_format(($result['varones']* 100) / $result['total'], 2,",", ".");
      $result['dotacionFemenina'] = number_format(($result['mujeres'] * 100) / $result['total'],2,",", ".");
      return $result;
    }


    function get_alter(){
      $rtnArray = array();

      $rtnArray[0]['elemento'] = "Acciones Estatales Totales";
      $rtnArray[0]['valor'] =
        $this->Model_api->get_collection_by_query_fast('case',
        ['status' => 'open' , 'idwf' => "accion_estatal"])['result'][0]['count'] + $this->Model_api->get_collection_by_query_fast('case',
        ['status' => 'open' , 'idwf' => "accion_estatal_2021"])['result'][0]['count'];
      $rtnArray[1]['elemento'] = "Indicadores Totales";
        
      $rtnArray[1]['valor'] =
        $this->Model_api->get_collection_by_query_fast('mapa_indicadores',
        ['_id' => ['$exists' => true]])['result'][0]['count'];

      $rtnArray[2]['elemento'] = "Servicios Esenciales a la Ciudadanía";
      $rtnArray[2]['valor'] =
        $this->Model_api->get_collection_by_query_fast('container.pages',
        ['post_type' => 'items_catalogo', 'post_status' => 'published'], [] , 'mae_cms')['result'][0]['count'];

      $rtnArray[3]['elemento'] = "Consultas al catálogo de Servicios Esenciales a la ciudadanía";
      $rtnArray[3]['valor'] =
        $this->Model_api->get_resultados_catalogo()['result'][0]['count'] + 600000;

      $rtnArray[4]['elemento'] = "Ministerios y organismos";
      $rtnArray[4]['valor'] = "25";

      $rtnArray[5]['elemento'] = "Usuarios creados";
      $rtnArray[5]['valor'] =
        $this->Model_api->get_collection_by_query_fast('users',
        ['idu' => ['$exists' => true]])['result'][0]['count'];

      return $rtnArray;
    }




}
