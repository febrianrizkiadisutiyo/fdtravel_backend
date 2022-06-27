<?php
include '../connection.php';

if($_SERVER['REQUEST_METHOD'] !== 'POST'){
    http_response_code(400);
    $reply['error'] = 'POST method required';
    echo json_encode($reply);
    exit();
}

$id_mobil = $_POST['id_mobil']??'';
$merk = $_POST['merk']??'';
$no_plat = $_POST['no_plat']??'';
$warna = $_POST['warna']??'';
$tahun_pembuatan = $_POST['tahun_pembuatan']??'';
$kapasitas = $_POST['kapasitas']??'';

$isValidated = true;
if(empty($id_mobil)){
    $reply['error'] = 'id mobil harus diisi';
    $isValidated = false;
}
if(empty($merk)){
    $reply['error'] = 'merk harus diisi';
    $isValidated = false;
}
if(empty($no_plat)){
    $reply['error'] = 'no plat harus diisi';
    $isValidated = false;
}
if(empty($warna)){
    $reply['error'] = 'warna harus diisi';
    $isValidated = false;
}
if(empty($tahun_pembuatan)){
    $reply['error'] = 'tahun pembuatan harus diisi';
    $isValidated = false;
}
if(empty($kapasitas)){
    $reply['error'] = 'kapasitas harus diisi';
    $isValidated = false;
}
if(!$isValidated){
    echo json_encode($reply);
    http_response_code(400);
    exit(0);
}
try{
    $query ="INSERT INTO mobil (id_mobil,merk,no_plat,warna,tahun_pembuatan,kapasitas)
            VALUES (:id_mobil,:merk,:no_plat,:warna,:tahun_pembuatan,:kapasitas)";
    /** @var TYPE_NAME $connection */
    $statement = $connection->prepare($query);

    $statement->bindValue(":id_mobil", $id_mobil);
    $statement->bindValue(":merk", $merk);
    $statement->bindValue(":no_plat", $no_plat);
    $statement->bindValue(":warna", $warna);
    $statement->bindValue(":tahun_pembuatan", $tahun_pembuatan);
    $statement->bindValue(":kapasitas", $kapasitas);

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
$getResult = "SELECT * FROM mobil WHERE id_mobil = :id_mobil";
$stm = $connection->prepare($getResult);
$stm->bindValue(':id_mobil', $lastId);
$stm->execute();
$result = $stm->fetch(PDO::FETCH_ASSOC);


$reply['status'] = $isOk;
echo json_encode($reply);
