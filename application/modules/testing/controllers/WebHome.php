<?php

/**
 *
 * @author Leonardo Gómez <leonardo.gmz@gmail.com>
 * @date 18/11/2020
 *
 */


defined('BASEPATH') or exit('No direct script access allowed');

require_once APPPATH . 'modules/api/controllers/ApiBaseController.php';

class WebHome extends ApiBaseController
{
    /**
     * @var WebHomeModel
     */
    private $webHomeModel = null;

    public function __construct()
    {
        parent::__construct();
        $this->load->model('WebHomeModel');
        $this->webHomeModel = $this->WebHomeModel;
        $this->load->model('Model_api');
    }

    public function getHome()
    {
        //toDo

    }

    public function getCategoriasHome()
    {

        $result =  $this->webHomeModel->getCategoriasHome();

        $this->return($result);
    }

    public function getStateActions()
    {
        $data = $this->getJsonRequest();
        $result['data'] = [];
        $categoria = $this->webHomeModel->getCategoriasHome($data['value'])[0];
        if ($categoria) {
            $result['data']['title'] =  $categoria['text'];
            $result['data']['bajada'] =  $categoria['bajada'];
            $result['data']['color'] =  $categoria['color'];
            $result['data']['imageCard'] =  $categoria['image'];
        }

        $result['data']['stateActionCards'] =  $this->webHomeModel->getStateActions($data['value']);

        $result['data']['chartData'] =  $this->webHomeModel->getOrganismosDataByValue($data['value']);


        $result['data']['generalInfoCards'][0] = [
            'description' => "Acciones Estatales<br>2020", 'value' => count($result['data']['stateActionCards'])
        ];

        $result['data']['generalInfoCards'][1] = [
            'description' => "Ministerios y<br>Organismos", 'value' => count($result['data']['chartData'])
        ];

        $this->return($result);
    }

    public function getDotacion()
    {

        $result = [
            'total' => 191832,
            'varones' => 96137,
            'mujeres' => 95675
        ];
        $this->return($result);
    }

    public function getStatsCatalogoCovid()
    {
        $result['essentialServicesCards'] =  $this->webHomeModel->getStatsCatalogoCovid();
        $consultas = $this->Model_api->get_resultados_catalogo()['result'][0]['count'] + 600000;
        $result['essentialServicesCards']['cant_consultas']['number'] = $consultas;
        $result['essentialServicesCards']['cant_consultas']['number_format'] =  '+' . substr($consultas, 0, 3) . ' mil';

        $this->return($result);
    }

    public function getMasConsultasdosCatalogoCovid()
    {
        $servicesCards = $this->webHomeModel->getMasConsultasdosCatalogoCovid(10);
        if (is_array($servicesCards)) {
            foreach ($servicesCards as $key => &$servicio) {
                $servicio['organismos'] = $this->webHomeModel->getOrganismoBySlug($servicio['slug']);
            }
        }
        $this->return(  $servicesCards);
    }
}
