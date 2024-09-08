<?php
//Establecer una conexion a MySQL
function makeConnection($paramDB)
{
    try {
        $connection = new PDO("mysql:host={$paramDB['serverhost']};dbname={$paramDB['database']};charset=utf8", $paramDB['username'], $paramDB['password']);
        $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $connection;
    } catch (PDOException $exception) {
        exit($exception->getMessage());
    }
}

//Asociar parametros a un SQL Insert o Update 
function bindAllValues($statement, $params)
{
    foreach ($params as $param => $value) {
        $statement->bindValue(':' . $param, $value);
    }
return $statement;
}

//Obtener parametros para SQL Update
function getParams($inputs)
{
    $filterParams = [];
    foreach ($inputs as $input => $value) {
        $filterParams[] = "$input= :$input";
    }
    return implode(", ", $filterParams);
}
