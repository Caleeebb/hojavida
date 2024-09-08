<?php
include_once('../connectionsFiles/Parameters.php');
include_once('../connectionsFiles/Connection.php');
//Crear un objeto conexion
$dbConnection = makeConnection($parametersDB);

$accion = '';
if (isset($_GET['action'])) {
    $accion = $_GET['action'];
}
header('Content-type: application/json');
header('http/1.1 200 OK');
switch ($accion) {
    case 'create':
        try {
            $dbConnection->beginTransaction();
            $inputs = $_POST;
            $sql = "
            INSERT INTO pasatiempos(descripcion, persona_id) VALUES(:descripcion, :persona_id)
            ";
            $statement = $dbConnection->prepare($sql);
            bindAllValues($statement, $inputs);
            $statement->execute();
            $postID = $dbConnection->lastInsertId();
            $inputs['id'] = $postID;
            $response = [
                'status' => 'ok',
                'message' => "La informacion ha sido almacenda",
                'pasatiempos'=> $inputs,
            ];
            $dbConnection->commit();
        } catch (PDOException $ex) {
            $dbConnection->rollBack();
            $response = [
                'status' => 'error',
                'message' => $ex->getMessage(),
            ];
        }
        echo json_encode($response);
        break;

    case 'update':
        try {
            $dbConnection->beginTransaction();
            $inputs = $_POST;
            $postID = $inputs['id'];
            $fields = getParams($inputs);
            $sql = "UPDATE pasatiempos set $fields where id = $postID";
            $statement = $dbConnection->prepare($sql);
            bindAllValues($statement, $inputs);
            $statement->execute();

            $response = [
                'status' => 'ok',
                'message' => "La información ha sido actualizada",
                'pasatiempo' => $inputs
            ];
        } catch (PDOException $ex) {
            $dbConnection->rollBack();
            $response = [
                'status' => 'error',
                'message' => $sql . ' ' . $ex->getMessage(),
                'pasatiempo' => $inputs
            ];
        }

        echo json_encode($response);
        break;
        case 'read':
            //Mostrar los registros de la tabla
            $sql = $dbConnection->prepare("SELECT * from pasatiempos");
            $sql->execute();
            $sql->setFetchMode(PDO::FETCH_ASSOC);
            $registros=$sql->fetchAll();
            $response=[
                'status'=>'ok',
                'message'=>($registros ? "ok" : "Sin registros"),
                'pasatiempo'=>$registros
            ];
            echo json_encode($response);
            break;
    case 'delete':
        try {
            $dbConnection->beginTransaction();
            $inputs = $_POST;
            $statement = $dbConnection->prepare("Delete from pasatiempos where id = :id");
            $statement->bindValue(":id", $inputs['id']);
            $statement->execute();
            $response = [
                'status' => 'ok',
                'message' => "La informacion de ha sido eliminada",
                'pasatiempo' => $inputs
            ];
            $dbConnection->commit();
        } catch (PDOException $ex) {
            $dbConnection->rollBack();
            $response = [
                'status' => 'error',
                'message' =>    $ex->getMessage(),
                'pasatiempo' =>  $inputs
            ];
        }
        echo json_encode($response);
        break;

    default:
        header('HTTP/1.1 404 Bad Request');
        echo json_encode('404: Bad Request');
        break;
}
