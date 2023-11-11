<?php 
//Llamada a la funcion de la base de datos
require_once('../core/dbConection.php');


//De la siguiente forma devolvemos la data en JSON
header('Content-Type: application/json');

//De esta forma estamos viendo que tipo de peticion recibe el server
$method = $_SERVER['REQUEST_METHOD'];
$conection = connection();

//Obtener parametro o id para eliminar
//Server-> PATH_INFO contieneinformacion de la url
$path = isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : '/' ;
$buscar_id = explode('/',$path);
$id = ($path !== '/') ? end($buscar_id):null;

switch ($method) 
{
    case 'GET':
        methodGet($conection,$id);
        break;
    case 'POST':
        insertPost($conection);
        break;    
    case 'PUT':
        update($conection,$id);
        break;    
    case 'DELETE':
        delete($conection,$id);
        break;
    default:
            echo ' no se registro metodo';
}
       

function methodGet($conection,$id=null)
{
    $query = ($id===null) ? $conection->query("SELECT * FROM usuarios"): $conection->query("SELECT * FROM usuarios WHERE id = $id");
   
 
    if($query){
        // $result = $query->fetch_assoc(); 
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

 function delete($conection,$id)
 {
    $query = $conection->prepare('DELETE FROM usuarios WHERE id =:id');
    $query->bindParam(':id',$id);
    $query->execute();

    if($query)
    {
        echo json_encode(['success'=>'The user was successfully deleted']);
    }else{
        echo json_encode(['error'=>'The user was not found']);
    }
 }

 function update($conection,$id)
 {
    $data = json_decode(file_get_contents('php://input'),true);
    $name = $data['name'];
    $surname =$data['surname'];
    $query = $conection->prepare('UPDATE usuarios SET nombre = :name, apellido = :surname WHERE id =:id');
    $query->execute(
        [
            'name'=>$name,
            'surname'=>$surname,
            'id'=>$id
        
        ]
    );
    if($query)
    {
        echo json_encode(['success'=>'The user was successfully updated']);
    }else{
        echo json_encode(['error'=>'The user was not updated']);
    }
 }