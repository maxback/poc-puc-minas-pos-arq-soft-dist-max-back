<?php
require "../start.php";
use Src\Post;
use Src\Entrega;
use Src\RelatorioEntrega;

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: OPTIONS,GET,POST,PUT,DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = explode( '/', $uri );

$requestMethod = $_SERVER["REQUEST_METHOD"];



// the post id is, of course, optional and must be a number
$postId = null;
if (isset($uri[2])) {
    $postId = (int) $uri[2];
}

$ownerId = null;
$controller = null;

if ($uri[1] === "entrega" || $uri[1] === "entregas") {

  $controller = new Entrega($dbConnection, $requestMethod, $postId, $ownerId);

  if (isset($uri[3])) {
    if ($uri[3] === "relatorio") {
      $ownerId = $postId;
      $postId = null;
      if (isset($uri[4])) {
        $postId = (int) $uri[4];
      }
      $controller = new RelatorioEntrega($dbConnection, $requestMethod, $postId, $ownerId);
    }
  }

} else if ($uri[1] === "relatorio_entrega") {
  $controller = new RelatorioEntrega($dbConnection, $requestMethod, $postId, $ownerId);
} else if ($uri[1] === "post") {
  $controller = new Post($dbConnection, $requestMethod, $postId, $ownerId);
} else {
  header("HTTP/1.1 404 Not Found");
  exit();
}


$controller->processRequest();

?>