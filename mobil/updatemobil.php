<?php
include '../connection.php';

//if($_SERVER['REQUEST_METHOD'] !== 'PATCH'){
//    header('Content-Type: application/json');
//    http_response_code(400);
//    $reply['error'] = 'PATCH method required';
//    echo json_encode($reply);
//    exit();
//}
$formData = [];
parse_str(file_get_contents('php://input'), $formData);

$id_mobil = $formData['id_mobil']??0;
$merk = $formData['merk']??'';
$no_plat = $formData['no_plat']??0;
$warna = $formData['warna']??'';
$tahun_pembuatan = $formData['tahun_pembuatan']??0;
$kapasitas = $formData['kapasitas']??0;

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
    $reply['error'] = 'nomor plat kategori harus diisi';
    $isValidated = false;
}
if(empty($warna)){
    $reply['error'] = 'warna kategori harus diisi';
    $isValidated = false;
}
if(empty($tahun_pembuatan)){
    $reply['error'] = 'tahun kategori harus diisi';
    $isValidated = false;
}
if(empty($kapasitas)){
    $reply['error'] = 'kapasitas kategori harus diisi';
    $isValidated = false;
}
if(!$isValidated){
    echo json_encode($reply);
    http_response_code(400);
    exit(0);
}
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
    $fields=[];
    $query = "UPDATE mobil SET merk = :merk, no_plat = :no_plat, warna = :warna, tahun_pembuatan = :tahun_pembuatan, kapasitas = :kapasitas
            WHERE id_mobil = :id_mobil";
    $statement = $connection->prepare($query);

    $statement->bindValue(":id_mobil", $id_mobil);
    $statement->bindValue(":merk", $merk);
    $statement->bindValue(":no_plat", $no_plat);
    $statement->bindValue(":warna", $warna);
    $statement->bindValue(":tahun_pembuatan", $tahun_pembuatan);
    $statement->bindValue(":kapasitas", $kapasitas);

    $isOk = $statement->execute();
} catch (Exception $exception){
    $reply['error'] = $exception->getMessage();
    echo json_encode($reply);
    http_response_code(400);
    exit(0);
}
if(!$isOk){
    $reply['error'] = $statement->errorInfo();
    http_response_code(400);
}
$getResult = "SELECT * FROM mobil WHERE id_mobil = :id_mobil";
$stm = $connection->prepare($getResult);
$stm->bindValue(':id_mobil', $id_mobil);
$stm->execute();
$result = $stm->fetch(PDO::FETCH_ASSOC);

$dataFinal = [
    'id_mobil' => $result['id_mobil'],
    'merk' => $result['merk'],
    'no_plat' => $result['no_plat'],
    'warna' => $result['warna'],
    'tahun_pembuatan' => $result['tahun_pembuatan'],
    'kapasitas' => $result['kapasitas'],

];
$reply['data'] = $dataFinal;
$reply['status'] = $isOk;
echo json_encode($reply);
