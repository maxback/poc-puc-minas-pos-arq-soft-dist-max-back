<?php
namespace Src;

class Entrega extends ControllerBase {


/*

CREATE TABLE IF NOT EXISTS `entrega`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cte` varchar(44) NOT NULL,
  `valor` decimal(15,2) NOT NULL DEFAULT 0,
  `destinatario` varchar(255),
  `endereco` text NOT NULL,
  `endereco_latitude` int NOT NULL DEFAULT 0,
  `endereco_longitude` int NOT NULL DEFAULT 0,
  `descricao` varchar(255) NOT NULL,
  `status` int NOT NULL DEFAULT 0,
  `observacoes` text NOT NULL,
  `data_criacao` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `data_limite_entrega` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `data_realizacao_entrega` datetime NULL,
  PRIMARY KEY (`id`)
);


id, cte, valor, destinatario, endereco, endereco_latitude, endereco_longitude, descricao, status, observacoes, data_criacao, data_limite_entrega, data_realizacao_entrega
*/


  protected function hookMountSelectAllItemsSQL() {
        
    return "
      SELECT
      id, cte, valor, destinatario, endereco, endereco_latitude, endereco_longitude, descricao, status, observacoes, data_criacao, data_limite_entrega, data_realizacao_entrega
      FROM
      entrega;
    ";
  }


  protected function hookMountCreateItemSQL() {
    return "
      INSERT INTO entrega
        (cte, valor, destinatario, endereco, endereco_latitude, endereco_longitude, descricao, status, observacoes, data_limite_entrega, data_realizacao_entrega)
      VALUES
        (:cte, :valor, :destinatario, :endereco, :endereco_latitude, :endereco_longitude, :descricao, :status, :observacoes, :data_limite_entrega, :data_realizacao_entrega);
    ";
  }

  protected function hookMountCreateItemFieldsMapValues($input) {
    return array(
      'cte' => $input['cte'], 
      'valor' => $input['valor'], 
      'destinatario' => $input['destinatario'], 
      'endereco' => $input['endereco'], 
      'endereco_latitude' => $input['endereco_latitude'], 
      'endereco_longitude' => $input['endereco_longitude'], 
      'descricao' => $input['descricao'], 
      'status' => $input['status'], 
      'observacoes' => $input['observacoes'], 
      'data_limite_entrega' => $this->datetimeFromStr($input['data_limite_entrega']), 
      'data_realizacao_entrega' => $this->datetimeFromStr($input['data_realizacao_entrega'])
      );
  }



  protected function hookMountUpdateItemSQL() {
    return "
      UPDATE entrega
      SET
        cte = :cte,
        valor = :valor,
        destinatario = :destinatario,
        endereco = :endereco,
        endereco_latitude = :endereco_latitude,
        endereco_longitude = :endereco_longitude,
        descricao = :descricao,
        status = :status,
        observacoes = :observacoes,
        data_limite_entrega = :data_limite_entrega,
        data_realizacao_entrega = :data_realizacao_entrega
      WHERE id = :id;
    ";
  }


  protected function hookMountUpdateItemFieldsMapValues($id, $input) {
    return array(
        'id' => (int) $id,
        'cte' => $input['cte'],
        'valor' => $input['valor'],
        'destinatario' => $input['destinatario'],
        'endereco' => $input['endereco'],
        'endereco_latitude' => $input['endereco_latitude'],
        'endereco_longitude' => $input['endereco_longitude'],
        'descricao' => $input['descricao'],
        'status' => $input['status'],
        'observacoes' => $input['observacoes'],
        'data_limite_entrega' => $this->datetimeFromStr($input['data_limite_entrega']),
        'data_realizacao_entrega' => $this->datetimeFromStr($input['data_realizacao_entrega'])
      );
  }



  protected function hookMountDeleteItemSQL() {
    return "
      DELETE FROM entrega
      WHERE id = :id;
    ";
  }

  protected function hookMountFindItemSQL() {
    return "
      SELECT
      id, cte, valor, destinatario, endereco, endereco_latitude, endereco_longitude, descricao, status, observacoes, data_criacao, data_limite_entrega, data_realizacao_entrega
      FROM
        entrega
      WHERE id = :id;
    ";
  }  


  protected function validateRegister($input)
  {

    if (! isset($input['cte'])) {
      return false;
    }

    if (! isset($input['status'])) {
      return false;
    }

    return true;
  }
}