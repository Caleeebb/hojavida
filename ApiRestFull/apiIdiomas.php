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
            INSERT INTO idiomas(idioma, porcentaje, persona_id) VALUES(:idioma, :porcentaje, :persona_id)
            ";
            $statement = $dbConnection->prepare($sql);
            bindAllValues($statement, $inputs);
            $statement->execute();
            $postID = $dbConnection->lastInsertId();
            $inputs['id'] = $postID;
            $response = [
                'status' => 'ok',
                'message' => "La informacion ha sido almacenda",
                'idioma' => $inputs,
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
            $sql = "UPDATE idiomas set $fields where id = $postID";
            $statement = $dbConnection->prepare($sql);
            bindAllValues($statement, $inputs);
            $statement->execute();

            $response = [
                'status' => 'ok',
                'message' => "La información ha sido actualizada",
                'idioma' => $inputs
            ];
        } catch (PDOException $ex) {
            $dbConnection->rollBack();
            $response = [
                'status' => 'error',
                'message' => $sql . ' ' . $ex->getMessage(),
                'idioma' => $inputs
            ];
        }

        echo json_encode($response);
        break;
        case 'read':
            //Mostrar los registros de la tabla
            $sql = $dbConnection->prepare("SELECT * from idiomas");
            $sql->execute();
            $sql->setFetchMode(PDO::FETCH_ASSOC);
            $registros=$sql->fetchAll();
            $response=[
                'status'=>'ok',
                'message'=>($registros ? "ok" : "Sin registros"),
                'idiomas'=>$registros
            ];
            echo json_encode($response);
            break;
    case 'delete':
        try {
            $dbConnection->beginTransaction();
            $inputs = $_POST;
            $statement = $dbConnection->prepare("Delete from idiomas where id = :id");
            $statement->bindValue(":id", $inputs['id']);
            $statement->execute();
            $response = [
                'status' => 'ok',
                'message' => "La informacion ha sido eliminada",
                'idioma' => $inputs
            ];
            $dbConnection->commit();
        } catch (PDOException $ex) {
            $dbConnection->rollBack();
            $response = [
                'status' => 'error',
                'message' =>    $ex->getMessage(),
                'persona' =>  $inputs
            ];
        }
        echo json_encode($response);
        break;

    default:
        header('HTTP/1.1 404 Bad Request');
        echo json_encode('404: Bad Request');
        break;
}
