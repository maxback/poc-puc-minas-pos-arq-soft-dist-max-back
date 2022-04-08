<?php
$pdo = null;
try {
    $pdo = new PDO('mysql:host=mysql;dbname=database', 'root', '12345');
    echo "tudo certo por aqui: ";
} catch (PDOException $e) {
    print $e->getMessage();
    die();
}
var_dump($pdo);