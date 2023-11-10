<?php 
//Llamada a la funcion de la base de datos
require_once('../core/dbConection.php');


//De la siguiente forma devolvemos la data en JSON
header('Content-Type: application/json');

//De esta forma estamos viendo que tipo de peticion recibe el server
$method = $_SERVER['REQUEST_METHOD'];
$conection = connection();
// print_r($method);

switch ($method) 
{
    case 'GET':
        methodGet($conection);
        break;
    case 'POST':
        insertPost($conection);
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
       

function methodGet($conection)
{

    $query = $conection->query("SELECT * FROM usuarios");
 
    if($query){
        $result = $query->fetch_assoc(); 
        // TODO: Las primeras formas comentadas, no funcionan porque envie el indice, duplicando el registro
        // $result = iterator_to_array($query, PDO::FETCH_OBJ);
        // $result = $query->fetchAll();
        $result = [];
        while($p = $query->fetch(PDO::FETCH_ASSOC))
        {
            $result[] = $p;
        }
        echo json_encode($result);
    }
 }

 function insertPost($conection)
 {
    $data = json_decode(file_get_contents('php://input'), true);
    $name = $data['name'];
    $surname =$data['surname'];

    $query = $conection->prepare('INSERT INTO usuarios (nombre, apellido) VALUES (:nombre,:apellido)');
    $query->bindParam(':nombre', $name, PDO::PARAM_STR);
    $query->bindParam(':apellido',$surname,PDO::PARAM_STR);
    $query->execute();

    if($query->rowCount() > 0){
        $data['id'] = $conection->lastInsertId();
        echo json_encode($data);
    }else{
        echo json_encode(['error'=> 'The user was not created correctly']);
    }

    // echo json_encode($query);


 }