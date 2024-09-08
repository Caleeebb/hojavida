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
            INSERT INTO formacion(escuela,title,periodo,persona_id) VALUES(:escuela,:,:periodo,:persona_id)
            ";
            $statement = $dbConnection->prepare($sql);
            bindAllValues($statement, $inputs);
            $statement->execute();
            $postID = $dbConnection->lastInsertId();
            $inputs['id'] = $postID;
            $response = [
                'status' => 'ok',
                'message' => "La informacion ha sido almacenda",
                'formacion' => $inputs,
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
            $sql = "UPDATE formacion set $fields where id = $postID";
            $statement = $dbConnection->prepare($sql);
            bindAllValues($statement, $inputs);
            $statement->execute();

            $response = [
                'status' => 'ok',
                'message' => "La información ha sido actualizada",
                'formacion' => $inputs
            ];
        } catch (PDOException $ex) {
            $dbConnection->rollBack();
            $response = [
                'status' => 'error',
                'message' => $sql . ' ' . $ex->getMessage(),
                'formacion' => $inputs
            ];
        }

        echo json_encode($response);
        break;
        case 'read':
            //Mostrar los registros de la tabla
            $sql = $dbConnection->prepare("SELECT * from formacion");
            $sql->execute();
            $sql->setFetchMode(PDO::FETCH_ASSOC);
            $registros=$sql->fetchAll();
            $response=[
                'status'=>'ok',
                'message'=>($registros ? "ok" : "Sin registros"),
                'formacion'=>$registros
            ];
            echo json_encode($response);
            break;
    case 'delete':
        try {
            $dbConnection->beginTransaction();
            $inputs = $_POST;
            $statement = $dbConnection->prepare("Delete from formacion where id = :id");
            $statement->bindValue(":id", $inputs['id']);
            $statement->execute();
            $response = [
                'status' => 'ok',
                'message' => "La informacion ha sido eliminada",
                'formacion' => $inputs
            ];
            $dbConnection->commit();
        } catch (PDOException $ex) {
            $dbConnection->rollBack();
            $response = [
                'status' => 'error',
                'message' =>    $ex->getMessage(),
                'formacion' =>  $inputs
            ];
        }
        echo json_encode($response);
        break;

    default:
        header('HTTP/1.1 404 Bad Request');
        echo json_encode('404: Bad Request');
        break;
}