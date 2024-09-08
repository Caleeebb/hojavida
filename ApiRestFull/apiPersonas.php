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
            INSERT INTO personas(identidad, fechanac, nombres, apellidos, sexo, estadocivil, casadocon, nacionalidad, resumen) VALUES(:identidad, :fechanac, :nombres, :apellidos, :sexo,  :estadocivil, :casadocon, :nacionalidad, :resumen)
            ";
            $statement = $dbConnection->prepare($sql);
            bindAllValues($statement, $inputs);
            $statement->execute();
            $postID = $dbConnection->lastInsertId();
            $inputs['id'] = $postID;
            $response = [
                'status' => 'ok',
                'message' => "La informacion de " . $inputs['nombres'] . "" . $inputs['apellidos'] . " ha sido almacenda",
                'persona' => $inputs,
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
            $sql = "UPDATE personas set $fields where id = $postID";
            $statement = $dbConnection->prepare($sql);
            bindAllValues($statement, $inputs);
            $statement->execute();
            $response = [
                'status' => 'ok',
                'message' => "La información de " .  $inputs['nombres'] . " " . $inputs['apellidos'] .  " ha sido actualizada",
                'persona' => $inputs
            ];
        } catch (PDOException $ex) {
            $dbConnection->rollBack();
            $response = [
                'status' => 'error',
                'message' => $sql . ' ' . $ex->getMessage(),
                'persona' => $inputs
            ];
        }

        echo json_encode($response);
        break;

        case 'read':
            //Mostrar los registros de la tabla
            $sql = $dbConnection->prepare("SELECT * from personas");
            $sql->execute();
            $sql->setFetchMode(PDO::FETCH_ASSOC);
            $registros=$sql->fetchAll();
            $response=[
                'status'=>'ok',
                'message'=>($registros ? "ok" : "Sin registros"),
                'personas'=>$registros
            ];
            echo json_encode($response);
            break;
            
            
            case 'delete':
                
                
                try {
                    $dbConnection->beginTransaction();
                    $inputs = $_POST;
                    $statement = $dbConnection->prepare("Delete from personas where id = :id");
                    $statement->bindValue(":id", $inputs['id']);
                    $statement->execute();
                    $response = [
                        'status' => 'ok',
                'message' => "La informacion de " . $inputs['nombres'] . " " .
                $inputs['apellidos'] . " ha sido eliminada",
                'persona' => $inputs
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

        case 'getpersona': //se usara cuando se va a cargar info de un perfil
            $response = [
                'status'  => 'ok',
                'persona' => getPersona($_POST['id'], $dbConnection)
            ];
            echo json_encode($response);
            break;
    
            
            case 'photo': //se usara cuando se cargue o cambie una foto de perfil
                $result = getPersona($_POST['id'], $dbConnection);
                $message = empty($result['foto']) ? uploadPhoto(null) : uploadPhoto($result['foto']);
                echo json_encode ($message);
                if($message['status'] === 'ok'){
                    try {
                        $dbConnection->beginTransaction();
                        $sql = "UPDATE personas SET foto = '". $message['foto'] ."' where id=" . $_POST['id'];
                        $statement = $dbConnection->prepare($sql);
                        $statement->execute();
                        $dbConnection->commit();
                        $response = $message;
                    } catch (\PDOException $ex) {
                        $dbConnection->rollBack();
                        $response = [
                            'status' => 'error',
                            'message' => $ex->getMessage(),
                        ];
                    }
                    echo json_encode($response);
                }
        break;
        header('HTTP/1.1 404 Bad Request');
        echo json_encode('404: Bad Request');
        break;

        default:
}

function getPersona($id, $dbConnection){
    $sql = "Select * from personas where id = $id";
    $result = $dbConnection->prepare($sql);
    $result->execute();
    return $result->fetch(PDO::FETCH_ASSOC);
}

function uploadPhoto($image2Delete){
    $imgFile = $_FILES['foto']['name'];
    $tmp_dir = $_FILES['foto']['tmp_name'];
    $imgSize = $_FILES['foto']['size'];
    $upload_dir = $_SERVER['DOCUMENT_ROOT'] .'/hojavidaphp/perfiles/';
    $imgExt = strtolower(pathinfo($imgFile, PATHINFO_EXTENSION));
    $valid_extension = array('jpeg', 'jpg', 'png', 'gif');
    $photoname = time().'.'.$imgExt;
    if(in_array($imgExt, $valid_extension) and is_uploaded_file($tmp_dir)){
        if($imgSize < 1000000){
            deletePhoto($image2Delete);
            move_uploaded_file($tmp_dir, $upload_dir . $photoname);
            $response = [
                'status'  =>'ok',
                'message' => "El archivo se ha subido correctamente, actualizando la foto de perfil",
                'foto'    => $photoname,
            ];
            return $response;
        }else{
            $response = [
                'status'  =>'error',
                'message' => "El archivo es demasiado grande, Suba archivos de al menos (300Kb) de peso, el archivo que envia pesa " . $imgSize . "KB",
                'foto'    => '',
            ];
            return $response;
        }
    }else{
        $response = [
            'status'  =>'error',
            'message' => "La extencion del archivo subido no es la correcta para la foto de perfil, el archivo no se ha podido subir ",
            'foto'    => '',
        ];
        return $response;
    }
}

function deletePhoto($image2Delete){
    $photo_dir = $_SERVER['DOCUMENT_ROOT'] .'/hojavidaphp/perfiles/' .$image2Delete;
    if(file_exists($photo_dir) and $image2Delete){
        unlink($photo_dir);
    }
}