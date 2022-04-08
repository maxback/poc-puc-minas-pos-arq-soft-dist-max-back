<?php
namespace Src;

class RelatorioEntrega extends ControllerBase {


/*

CREATE TABLE IF NOT EXISTS `relatorio_entrega` (
  `id` int NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) NOT NULL,
  `id_entrega` int NOT NULL,
  `status` int NOT NULL DEFAULT 0,
  `latitude` int NOT NULL DEFAULT 0,
  `longitude` int NOT NULL DEFAULT 0,
  `descricao` varchar(255) NOT NULL,
  `data_criacao` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `data` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `data_realizacao_entrega` datetime NULL,

  id_entrega,status,latitude,longitude

*/

  protected function hookMountSelectAllItemsSQL() {
    
    return "
      SELECT
      id, uuid, id_entrega, status, latitude, longitude, descricao, data_criacao, data
      FROM
        relatorio_entrega
    ";
  }

  protected function hookMountSelectAllIChieldrenItemsSQL() {
    return $this->hookMountSelectAllItemsSQL() . " WHERE id_entrega = :ownerId";
  }


  protected function hookMountCreateItemSQL() {
    return "
      INSERT INTO relatorio_entrega
        (uuid, id_entrega, status, descricao, latitude, longitude, data)
      VALUES
        (:uuid, :id_entrega, :status, :descricao, :latitude, :longitude, :data);
    ";
  }

  protected function hookMountCreateItemFieldsMapValues($input) {
    return array(
        'uuid' => $input['uuid'],
        'id_entrega'  => $input['id_entrega'],
        'status' => $input['status'],
        'descricao' => $input['descricao'],
        'latitude' => $input['latitude'],
        'longitude' => $input['longitude'],
        'data'=> $this->datetimeFromStr($input['data'])
      );
  }



  protected function hookMountUpdateItemSQL() {
    return "
      UPDATE relatorio_entrega
      SET
        status = :status,
        latitude  = :latitude,
        longitude = :longitude,
        descricao = :descricao,
        data = :data
      WHERE id = :id;
    ";
  }


  protected function hookMountUpdateItemFieldsMapValues($id, $input) {
    return array(
        'id' => (int) $id,
        'status' => $input['status'],
        'latitude'  => $input['latitude'],
        'longitude' => $input['longitude'],
        'descricao' => $input['descricao'],
        'data' => $this->datetimeFromStr($input['data'])
      );
  }



  protected function hookMountDeleteItemSQL() {
    return "
      DELETE FROM relatorio_entrega
      WHERE id = :id;
    ";
  }

  protected function hookMountFindItemSQL() {
    return "
      SELECT
        id, uuid, id_entrega, status, descricao, latitude, longitude, data_criacao, data
      FROM
      relatorio_entrega
      WHERE id = :id
    ";
  }  

  protected function hookMountFindChieldItemSQL() {
    return $this->hookMountFindItemSQL() . " AND id_entrega = :ownerId";
  }  

  protected function validateRegister($input)
  {
    if (! isset($input['status'])) {
      return false;
    }
    if (! isset($input['latitude'])) {
      return false;
    }

    if (! isset($input['longitude'])) {
      return false;
    }

    return true;
  }
}