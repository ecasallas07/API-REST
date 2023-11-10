<?php 

require_once('../core/dbConection.php');


connection();

//De la siguiente forma devolvemos la data en JSON
header('Content-Type: application/json');

//De esta forma estamos viendo que tipo de peticion recibe el server
$method = $_SERVER['REQUEST_METHOD'];
print_r($method);

switch ($method) 
{
    case 'GET':
        echo' Consulta de registros';
        break;
    case 'POST':
        echo ' Iserccion de registros';
        break;    
    case 'PUT':
        echo ' Modificar registros';
        break;    
    case 'DELETE':
        echo ' elimina registro';
        break;
    default:
        echo ' no se registro metodo';
}