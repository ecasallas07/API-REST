<?php


function connection(){
    //TODO: Variable for config database access control 
    $host = 'localhost';
    $username = 'root';
    $password = '';
    $dbname = 'api';

    try{
        $con = new PDO('mysql:host='.$host.';dbname='.$dbname , $username,$password,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]);
        return $con;
    
    }catch(Exception $e){
         die($e->getMessage());
        // die('Conexion fallida algo salio mal');
    }
}

