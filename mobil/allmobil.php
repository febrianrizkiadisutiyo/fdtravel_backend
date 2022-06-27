<?php
include '../connection.php';

try{
    /** @var TYPE_NAME $connection */
    $statement = $connection->prepare("select * from mobil limit 50");
    $isOk = $statement->execute();
    $results = $statement->fetchAll(PDO::FETCH_ASSOC);
    $reply['data'] = $results;
}catch (Exception $exception){
    $reply['error'] = $exception->getMessage();
    echo json_encode($reply);
    http_response_code(400);
    exit(0);
}
if(!$isOk){
    $reply['error'] = $statement->errorInfo();
    http_response_code(400);
}
$reply['status'] = true;
echo json_encode($reply);
