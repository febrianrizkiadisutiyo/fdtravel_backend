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
$no_jadwal = $res['no_jadwal'] ?? 0;

try {
    $queryCheck = "SELECT * FROM jadwal_berangkat where no_jadwal = :no_jadwal";
    /** @var TYPE_NAME $connection */
    $statement = $connection->prepare($queryCheck);
    $statement->bindValue(':no_jadwal', $no_jadwal);
    $statement->execute();
    $row = $statement->rowCount();

    if ($row === 0) {
        $reply['error'] = 'Data tidak ditemukan  ' . $no_jadwal;
        echo json_encode($reply);
        http_response_code(400);
        exit(0);
    }
} catch (Exception $exception) {
    $reply['error'] = $exception->getMessage();
    echo json_encode($reply);
    http_response_code(400);
    exit(0);
}
try {
    $queryCheck = "DELETE FROM jadwal_berangkat where no_jadwal = :no_jadwal";
    $statement = $connection->prepare($queryCheck);
    $statement->bindValue(':no_jadwal', $no_jadwal);
    $statement->execute();
} catch (Exception $exception) {
    $reply['error'] = $exception->getMessage();
    echo json_encode($reply);
    http_response_code(400);
    exit(0);
}
$reply['status'] = true;
echo json_encode($reply);

