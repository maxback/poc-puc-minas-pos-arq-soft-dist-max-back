<?php
namespace Src;

class ControllerBase {
  private $db;
  private $requestMethod;
  private $postId;
  private $ownerId;

  public function __construct($db, $requestMethod, $postId, $ownerId)
  {
    $this->db = $db;
    $this->requestMethod = $requestMethod;
    $this->postId = $postId;
    $this->ownerId = $ownerId;
  }

  public function processRequest()
  {
    switch ($this->requestMethod) {
      case 'GET':
        if ($this->postId) {
          $response = $this->getItem($this->postId);
        } else {
          $response = $this->getAllItems();
        };
        break;
      case 'POST':
        $response = $this->createItem();
        break;
      case 'PUT':
        $response = $this->updateItem($this->postId);
        break;
      case 'DELETE':
        $response = $this->deleteItem($this->postId);
        break;
      default:
        $response = $this->notFoundResponse();
        break;
    }
    header($response['status_code_header']);
    if ($response['body']) {
      echo $response['body'];
    }
  }

  //override  on extends this class
  protected function hookCallSubControlers() {
    return "";
  }

  protected function hookReturnSubControlers() {
    return "";
  }

  
  //override  on extends this class
  protected function hookMountSelectAllItemsSQL() {
    return "";
  }

  //override  on extends this class
  protected function hookMountSelectAllIChieldrenItemsSQL() {
    return "";
  }

  //override  on extends this class
  protected function hookMountSelectAllIChieldrenItemsMapValues() {
    return array('ownerId' => $this->ownerId);
  }


  //override  on extends this class
  protected function hookMountCreateItemSQL() {
    return "";
  }

  //override  on extends this class
  protected function hookMountUpdateItemSQL() {
    return "";
  }

  //override  on extends this class
  protected function hookMountDeleteItemSQL() {
    return "";
  }

  //override  on extends this class
  protected function hookMountDeleteItemFieldsMapValues($id) {
    return array('id' => $id);
  }

  //override  on extends this class
  protected function hookMountFindItemSQL() {
    return "";
  }  

  //override  on extends this class
  protected function hookMountFindItemFieldsMapValues($id) {
    return array('id' => $id);
  }

  //override  on extends this class
  protected function validateRegister($input)
  {
    return false;
  }

  //override  on extends this class
  protected function hookMountFindChieldItemSQL() {
    return "";
  }

  //override  on extends this class
  protected function hookMountFindChieldItemFieldsMapValues($id) {
    return array('id' => $id, 'ownerId' => $this->ownerId);
  }



  protected function datetimeFromStr($string) {

    //$date = DateTime::createFromFormat('Y-m-dTH:i:s.vZ', $string);
    //return $date->format('Y-m-d H:i:s');

    $timestamp = strtotime($string);
    return date("Y-m-d H:i:s", $timestamp);
  }

  private function getAllItems()
  {
    $query = "";
    if($this->ownerId) {
      $query = $this->hookMountSelectAllIChieldrenItemsSQL();

    } else {
      $query = $this->hookMountSelectAllItemsSQL();
    }
    try {

      $statement = $this->db->query($query);

      $result = null;
      if($this->ownerId) {
        $statement = $this->db->prepare($query);
        $statement->execute($this->hookMountSelectAllIChieldrenItemsMapValues());
        $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
  
      } else {
        $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
      }
  
      
    } catch (\PDOException $e) {
      exit($e->getMessage());
    }

    $response['status_code_header'] = 'HTTP/1.1 200 OK';
    $response['body'] = json_encode($result);
    return $response;
  }

  private function getItem($id)
  {
    $result = $this->find($id);
    if (! $result) {
      return $this->notFoundResponse();
    }
    $response['status_code_header'] = 'HTTP/1.1 200 OK';
    $response['body'] = json_encode($result);
    return $response;
  }

  private function createItem()
  {
    $input = (array) json_decode(file_get_contents('php://input'), TRUE);
    if (! $this->validateRegister($input)) {
      return $this->unprocessableEntityResponse();
    }

    $query = $this->hookMountCreateItemSQL();
    $debug = "query = " . $query;

    try {
      $statement = $this->db->prepare($query);

      $param = $this->hookMountCreateItemFieldsMapValues($input);

      $debug .= " - param: [" . implode(" | ", $param) . "]\n\n";

      if(!$statement->execute($param )) {
        throw new \Error('Erro executando insert: ' . implode(", ",
          $statement->errorInfo()));
      }

      //$debug .= "execute() statement => " . $statement; 

      $rowCount = $statement->rowCount();
    } catch (\PDOException $e) {
      exit($e->getMessage());
    }

    $response['status_code_header'] = 'HTTP/1.1 201 Created';
    $response['body'] = json_encode(array('message' => 'Created'
      ,'row_count' => $rowCount
      , 'debug' => $debug
  ));
    return $response;
  }

  
   
  private function updateItem($id)
  {
    $result = $this->find($id);

    if (! $result) {
      return $this->notFoundResponse();
    }

    $input = (array) json_decode(file_get_contents('php://input'), TRUE);

    if (! $this->validateRegister($input)) {
      return $this->unprocessableEntityResponse();
    }

    $statement = $this->hookMountUpdateItemSQL();
    //$debug = "id => " . $id . "\n" . $statement;
    try {
      $statement = $this->db->prepare($statement);
      $param = $this->hookMountUpdateItemFieldsMapValues($id, $input);

      //$debug .= " - param: id => " . $param['id'];
      //$debug .= " - param: title => " . $param['title'];
      
      $statement->execute($param);
      $rowCount = $statement->rowCount();
    } catch (\PDOException $e) {
      exit($e->getMessage());
    }
    $response['status_code_header'] = 'HTTP/1.1 200 OK';
    $response['body'] = json_encode(array('message' => 'Updated!'
      //,'row_count' => $rowCount
      //, 'debug' => $debug
    ));
    return $response;
  }

  private function deleteItem($id)
  {
    $result = $this->find($id);

    if (! $result) {
      return $this->notFoundResponse();
    }

    $query = $this->hookMountDeleteItemSQL();

    try {
      $statement = $this->db->prepare($query);
      $statement->execute($this->hookMountDeleteItemFieldsMapValues($id));
      $statement->rowCount();
    } catch (\PDOException $e) {
      exit($e->getMessage());
    }
    $response['status_code_header'] = 'HTTP/1.1 200 OK';
    $response['body'] = json_encode(array('message' => 'Deleted!'));
    return $response;
  }


  public function find($id)
  {
    $query = "";
    $param = null;
    if($this->ownerId) {
      $query = $this->hookMountFindChieldItemSQL();
      $param = $this->hookMountFindChieldItemFieldsMapValues($id);
    } else {
      $query = $this->hookMountFindItemSQL();
      $param = $this->hookMountFindItemFieldsMapValues($id);
    }

    try {
      $statement = $this->db->prepare($query);
      $result = $statement->execute($param);
      if(!$result) {
        return false;
      }
      $result = $statement->fetch(\PDO::FETCH_ASSOC);
      return $result;
    } catch (\PDOException $e) {
      exit($e->getMessage());
    }
  }

 
  private function unprocessableEntityResponse()
  {
    $response['status_code_header'] = 'HTTP/1.1 422 Unprocessable Entity';
    $response['body'] = json_encode([
      'error' => 'Invalid input'
    ]);
    return $response;
  }

  private function notFoundResponse()
  {
    $response['status_code_header'] = 'HTTP/1.1 404 Not Found';
    $response['body'] = null;
    return $response;
  }
}