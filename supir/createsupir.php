<?php
include '../connection.php';

if($_SERVER['REQUEST_METHOD'] !== 'POST'){
    http_response_code(400);
    $reply['error'] = 'POST method required';
    echo json_encode($reply);
    exit();
}
$id_supir = $_POST['id_supir']??0;
$nama = $_POST['nama']??'';
$ttl = $_POST['ttl']??'';
$alamat = $_POST['alamat']??'';
$no_hp = $_POST['no_hp']??'';
$jenis_kelamin = $_POST['jenis_kelamin']??'';

$isValidated = true;
if(empty($id_supir)){
    $reply['error'] = 'id supir harus diisi';
    $isValidated = false;
}
if(empty($nama)){
    $reply['error'] = 'nama harus diisi';
    $isValidated = false;
}
if(empty($ttl)){
    $reply['error'] = 'ttl harus diisi';
    $isValidated = false;
}
if(empty($alamat)){
    $reply['error'] = 'alamat harus diisi';
    $isValidated = false;
}
if(empty($no_hp)){
    $reply['error'] = 'no hp harus diisi';
    $isValidated = false;
}
if(empty($jenis_kelamin)){
    $reply['error'] = 'jenis kelamin harus diisi';
    $isValidated = false;
}
if(!$isValidated){
    echo json_encode($reply);
    http_response_code(400);
    exit(0);
}
try{
    $query ="INSERT INTO supir (id_supir,nama,ttl,alamat,no_hp,jenis_kelamin)
            VALUES (:id_supir,:nama,:ttl,:alamat,:no_hp,:jenis_kelamin)";
    /** @var TYPE_NAME $connection */
    $statement = $connection->prepare($query);

    $statement->bindValue(":id_supir", $id_supir);
    $statement->bindValue(":nama", $nama);
    $statement->bindValue(":ttl", $ttl);
    $statement->bindValue(":alamat", $alamat);
    $statement->bindValue(":no_hp", $no_hp);
    $statement->bindValue(":jenis_kelamin", $jenis_kelamin);

    $isOk = $statement->execute();
}catch (Exception $exception) {
    $reply['error'] = $exception->getMessage();
    echo json_encode($reply);
    http_response_code(400);
    exit(0);
}
if(!$isOk){
    $reply['error'] = $statement->errorInfo();
    http_response_code(400);
}
$lastId = $connection->lastInsertId();
$getResult = "SELECT * FROM supir WHERE id_supir = :id_supir";
$stm = $connection->prepare($getResult);
$stm->bindValue(':id_supir', $lastId);
$stm->execute();
$result = $stm->fetch(PDO::FETCH_ASSOC);


$reply['status'] = $isOk;
echo json_encode($reply);
