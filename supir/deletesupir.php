<?php
include '../connection.php';

$data = file_get_contents('php://input');
$res = [];
parse_str($data, $res);
$id_supir = $res['id_supir'] ?? 0;

try{
    $queryCheck ="SELECT * FROM supir where id_supir = :id_supir";
    /** @var TYPE_NAME $connection */
    $statement = $connection->prepare($queryCheck);
    $statement->bindValue(':id_supir', $id_supir);
    $statement->execute();
    $row = $statement->rowCount();

    if($row === 0){
        $reply['error'] = 'Data tidak ditemukan  '.$id_supir;
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
    $queryCheck = "DELETE FROM supir where id_supir = :id_supir";
    $statement = $connection->prepare($queryCheck);
    $statement->bindValue(':id_supir', $id_supir);
    $statement->execute();
}catch (Exception $exception){
    $reply['error'] = $exception->getMessage();
    echo json_encode($reply);
    http_response_code(400);
    exit(0);
}
$reply['status'] = true;
echo json_encode($reply);

