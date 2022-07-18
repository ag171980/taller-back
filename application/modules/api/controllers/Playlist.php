<?php

defined('BASEPATH') or exit('No direct script access allowed');
/*
 * Paneles de Navegación
 *
 * @author Luciano Menez <lucianomenez1212@gmail.com>
 */
class Playlist extends MX_Controller
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

  function get_playlist_for_id($id)
  {
    $query = ['id' => intval($id)];
    $playlist = $this->Model_api->get('playlist', $query);
    echo json_encode($playlist);
  }
  function get_playlists()
  {
    $query = ['id' => ['$exists' => true]];
    $playlist = $this->Model_api->get('playlist', $query);
    echo json_encode($playlist);
  }
  function get_your_playlists($id)
  {
    $query = ['idUser' => intval($id)];
    $playlist = $this->Model_api->get('playlist', $query);
    echo json_encode($playlist);
  }
  function insert_playlist()
  {
    $data = json_decode(file_get_contents("php://input"));
    $playlist['id'] = rand(1, 1300);
    $playlist['userCreator']['nameCreator'] = $data->userCreator->fullname;
    $playlist['userCreator']['idCreator'] = $data->userCreator->id;
    $playlist['idUser'] = $data->idUser;
    $playlist['dataImg']['name'] = $data->dataImg->name;
    $playlist['dataImg']['dataBase64'] = $data->dataImg->dataBase64;
    $playlist['name'] = $data->name;
    $playlist['description'] = $data->description;
    $playlist['songs'] = [];
    $playlists = $this->Model_api->insert('playlist', $playlist);
    echo json_encode($playlists);
  }
  function delete_all_playlist()
  {
    $query = ['idu' => ['$exists' => true]];
    $playlists = $this->Model_api->delete('playlist', $query);
    echo json_encode($playlists);
  }

  //Songs

  function add_song_in_playlist()
  {
    $data = json_decode(file_get_contents("php://input"));
    $id_playlist = $data->idPlaylist;
    $query = ['id' => intval($id_playlist)];
    $playlist = $this->Model_api->get('playlist', $query);
    array_push($playlist[0]['songs'], $data->song);
    $this->db->where($query);
    $updatedPlaylist = $this->Model_api->update('playlist', $query, $playlist[0]);

    echo json_encode($updatedPlaylist);
  }
}
