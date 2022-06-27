<?php
include '../connection.php';

//if($_SERVER['REQUEST_METHOD'] !== 'DELETE'){
//    http_response_code(400);
//    $reply['error'] = 'DELETE method required';
//    echo json_encode($reply);
//    exit();
//}

$data = file_get_contents('php://input');
$res = [];
parse_str($data, $res);
$id_mobil = $res['id_mobil'] ?? 0;

try{
    $queryCheck ="SELECT * FROM mobil where id_mobil = :id_mobil";
    /** @var TYPE_NAME $connection */
    $statement = $connection->prepare($queryCheck);
    $statement->bindValue(':id_mobil', $id_mobil);
    $statement->execute();
    $row = $statement->rowCount();

    if($row === 0){
        $reply['error'] = 'Data tidak ditemukan  '.$id_mobil;
        echo json_encode($reply);
        http_response_code(400);
        exit(0);
    }
}catch (Exception $exception){
    $reply['error'] = $exception->getMessage();
    echo json_encode($reply);
    http_response_code(400);
    exit(0);
}
try{
    $queryCheck = "DELETE FROM mobil where id_mobil = :id_mobil";
    $statement = $connection->prepare($queryCheck);
    $statement->bindValue(':id_mobil', $id_mobil);
    $statement->execute();
}catch (Exception $exception){
    $reply['error'] = $exception->getMessage();
    echo json_encode($reply);
    http_response_code(400);
    exit(0);
}
$reply['status'] = true;
echo json_encode($reply);
