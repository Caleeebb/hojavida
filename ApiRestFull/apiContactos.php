<?php
include_once('../connectionsFiles/Parameters.php');
include_once('../connectionsFiles/Connection.php');

//crear un objeto conexion
$dbConnection = makeConnection($parametersDB);

//declarar variables, cadena vacia
$accion = '';
if (isset($_GET['action'])) {
    $accion = $_GET['action'];
}

//generar encabezados
header('Content-type: application/json');
header('HTTP/1.1 200 OK');

switch ($accion) {
    case 'create':
        try {
            $dbConnection->beginTransaction(); //objeto conexion
            $inputs = $_POST;
            $sql = "
        INSERT INTO contactos (tipo, descripcion, persona_id) VALUES (:tipo, :descripcion, :persona_id)  
        ";
            $statement = $dbConnection->prepare($sql);
            bindAllValues($statement, $inputs);
            $statement->execute(); //sentencia con punto y coma
            $postID = $dbConnection->lastInsertId(); //objeto conexion
            $inputs['id'] = $postID;
            $response = [ //armando un array asociativo
                'status' => 'ok',
                'message' => "El contacto ha sido almacenado",
                'contacto' => $inputs,
            ];
            $dbConnection->commit(); //si no ponemos un commit no se guardan cambios
        } catch (PDOException $ex) { //controlar el error
            $dbConnection->rollBack();
            $response = [
                'status' => 'error',
                'message' => $ex->getMessage(),
                'contacto' => $inputs
            ];
        }
        echo json_encode($response);
        break;

    case 'read':
        //mostrar los registros de la tabla
        $sql = $dbConnection->prepare("SELECT * from contactos where persona_id = " . $_POST['persona_id'] );
        $sql->execute();
        $sql->setFetchMode(PDO::FETCH_ASSOC);
        $registros = $sql->fetchAll();
        $response = [
            'status' => 'ok',
            'message' => ($registros ? "ok" : "Sin registros"),
            'contactos'  => $registros
        ];

        echo json_encode($response);
        break;

    case 'update':
        try {
            $dbConnection->beginTransaction();
            $inputs = $_POST;
            $postID = $inputs['id'];
            $fields = getParams($inputs);
            $sql = "UPDATE contactos SET $fields WHERE id = $postID";
            $statement = $dbConnection->prepare($sql);
            bindAllValues($statement, $inputs);
            $statement->execute();

            $response = [
                'status' => 'ok',
                'mesage' => "El contacto ha sido actualizado", 
                'contacto' => $inputs
            ];
            $dbConnection->commit();
        } catch (PDOException $ex) {
            $dbConnection->rollBack();
            $response = [
                'status' => 'error',
                'message' => $ex->getMessage(),
                'contacto'  => $inputs
            ];
        }

        echo json_encode($response);
        break;

    case 'delete':
        try {
            $dbConnection->beginTransaction();
            $inputs = $_POST;
            $statement = $dbConnection->prepare("DELETE FROM contactos WHERE id = :id");
            $statement->bindValue(":id", $inputs['id']);
            $statement->execute();
            $response = [
                'status' => 'ok',
                'message' => "El contacto ha sido eliminado",
                'contacto' => $inputs
            ];
            $dbConnection->commit();
        } catch (PDOException $ex) {
            $dbConnection->rollBack();
            $response = [
                'status' => 'error',
                'message' => $ex->getMessage(), 
                'contacto'  => $inputs
            ];
        }
        echo json_encode($response);
        break;

    default:
        header('HTTP/1.1 404 Bad Request');
        echo json_encode('404 Bad Request');
        break;
}   