<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

// $usuario = 'root';
// $senha = 'senai@118';
// $database = 'login';
// $host = 'localhost';

// $mysqli = new mysqli($host, $usuario, $senha, $database);

// if ($mysqli->connect_error) {
//     die("Falha na conexão: " . $mysqli->connect_error);
// } else {
//     echo "Conectado com sucesso!";
// }


$usuario = 'root';
$senha = 'Senai@118';
$database = 'jeca';
$host = '10.106.12.113';


$mysqli = new mysqli($host, $usuario, $senha, $database);

if ($mysqli->connect_error) {
    die("Erro na conexão: " . $mysqli->connect_error);
}

?>

<!-- gryg -->