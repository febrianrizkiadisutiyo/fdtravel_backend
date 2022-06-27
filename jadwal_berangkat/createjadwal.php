<?php
include '../connection.php';

if($_SERVER['REQUEST_METHOD'] !== 'POST'){
    http_response_code(400);
    $reply['error'] = 'POST method required';
    echo json_encode($reply);
    exit();
}
$no_jadwal = $_POST['no_jadwal']??'';
$id_mobil = $_POST['id_mobil']??'';
$id_supir = $_POST['id_supir']??'';
$tempat_berangkat = $_POST['tempat_berangkat']??'';
$tanggal = $_POST['tanggal']??'';
$pukul = $_POST['pukul']??'';
$tujuan = $_POST['tujuan']??'';

$isValidated = true;
if(empty($no_jadwal)){
    $reply['error'] = 'no jadwal harus diisi';
    $isValidated = false;
}
if(empty($id_mobil)){
    $reply['error'] = 'id mobil harus diisi';
    $isValidated = false;
}
if(empty($id_supir)){
    $reply['error'] = 'id supir harus diisi';
    $isValidated = false;
}
if(empty($tempat_berangkat)){
    $reply['error'] = 'tempat berangkat harus diisi';
    $isValidated = false;
}
if(empty($tanggal)){
    $reply['error'] = 'tanggal harus diisi';
    $isValidated = false;
}
if(empty($pukul)){
    $reply['error'] = 'pukul harus diisi';
    $isValidated = false;
}
if(empty($tujuan)){
    $reply['error'] = 'tujuan harus diisi';
    $isValidated = false;
}
if(!$isValidated){
    echo json_encode($reply);
    http_response_code(400);
    exit(0);
}
try{
    $query ="INSERT INTO jadwal_berangkat (no_jadwal,id_mobil,id_supir,tempat_berangkat,tanggal,pukul,tujuan)
            VALUES (:no_jadwal,:id_mobil,:id_supir,:tempat_berangkat,:tanggal,:pukul,:tujuan)";
    /** @var TYPE_NAME $connection */
    $statement = $connection->prepare($query);

    $statement->bindValue(":no_jadwal", $no_jadwal);
    $statement->bindValue(":id_mobil", $id_mobil);
    $statement->bindValue(":id_supir", $id_supir);
    $statement->bindValue(":tempat_berangkat", $tempat_berangkat);
    $statement->bindValue(":tanggal", $tanggal);
    $statement->bindValue(":pukul", $pukul);
    $statement->bindValue(":tujuan", $tujuan);

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
$getResult = "SELECT * FROM jadwal_berangkat WHERE no_jadwal = :no_jadwal";
$stm = $connection->prepare($getResult);
$stm->bindValue(':no_jadwal', $lastId);
$stm->execute();
$result = $stm->fetch(PDO::FETCH_ASSOC);


$reply['status'] = $isOk;
echo json_encode($reply);









//if($_SERVER['REQUEST_METHOD'] !== 'POST'){
//    http_response_code(400);
//    $reply['error'] = 'POST method required';
//    echo json_encode($reply);
//    exit();
//}
//if($_POST){
//    $no_jadwal = $_POST['no_jadwal'];
//    $id_mobil = $_POST['id_mobil'];
//    $id_supir = $_POST['id_supir'];
//    $tempat_berangkat = $_POST['tempat_berangkat'];
//    $tanggal = $_POST['tanggal'];
//    $pukul = $_POST['pukul'];
//    $tujuan = $_POST['tujuan'];
//
//
//    /** @var TYPE_NAME $connection */
//    $statement = $connection->prepare("INSERT INTO jadwal_berangkat (no_jadwal, id_mobil, id_supir, tempat_berangkat, tanggal, pukul, tujuan)
//                            VALUES  ('$no_jadwal','$id_mobil','$id_supir','$tempat_berangkat','$tanggal','$pukul','$tujuan')");
//    $statement->execute();
//
//    $response['message'] = "berhasil";
//    $response['data'] = [
//        'no_jadwal'=>$no_jadwal,
//        'id_mobil'=>$id_mobil,
//        'id_supir'=>$id_supir,
//        'tempat_berangkat'=>$tempat_berangkat,
//        'tanggal'=>$tanggal,
//        'pukul'=>$pukul,
//        'tujuan'=>$tujuan
//    ];
//    $json = json_encode($response, JSON_PRETTY_PRINT);
//
//    //print json
//    echo $json;
//}else {
//    $response['message'] = "Insert Gagal";
//    //Jadikan data dalam bentuk JSON
//    $json = json_encode($response, JSON_PRETTY_PRINT);
//
//    //print json
//    echo $json;
//}






//$response=[];
//$query ="INSERT INTO jadwal_berangkat(no_jadwal, id_mobil, id_supir, tempat_kejadian, tanggal, pukul, tujuan)
//        VALUES ('$no_jadwal','$id_mobil','$id_supir','$tempat_berangkat','$tanggal','$pukul','$tujuan')";
///** @var TYPE_NAME $connection */
//$statement = $connection->prepare($query);
//if($statement){
//    $response["status"]="berhasil";
//    $response["data"] = [
//        'no_jadwal'=>$no_jadwal,
//        'id_mobil'=>$id_mobil,
//        'id_supir'=>$id_supir,
//        'tempat_berangkat'=>$tempat_berangkat,
//        'tanggal'=>$tanggal,
//        'pukul'=>$pukul,
//        'tujuan'=>$tujuan
//    ];
//    $json =json_encode($response, JSON_PRETTY_PRINT);
//    echo$json;
//}

//$isValidated = true;
//if(empty($no_jadwal)){
//    $reply['error'] = 'NAMA kategori harus diisi';
//    $isValidated = false;
//}
//if(empty($id_mobil)){
//    $reply['error'] = 'NAMA kategori harus diisi';
//    $isValidated = false;
//}
//if(empty($id_supir)){
//    $reply['error'] = 'NAMA kategori harus diisi';
//    $isValidated = false;
//}
//if(empty($tempat_berangkat)){
//    $reply['error'] = 'NAMA kategori harus diisi';
//    $isValidated = false;
//}
//if(empty($tanggal)){
//    $reply['error'] = 'NAMA kategori harus diisi';
//    $isValidated = false;
//}
//if(empty($pukul)){
//    $reply['error'] = 'NAMA kategori harus diisi';
//    $isValidated = false;
//}
//if(empty($tujuan)){
//    $reply['error'] = 'NAMA kategori harus diisi';
//    $isValidated = false;
//}
//if(!$isValidated){
//    echo json_encode($reply);
//    http_response_code(400);
//    exit(0);
//}
//try{
//    $query = "INSERT INTO jadwal_berangkat(no_jadwal, id_mobil, id_supir,tempat_berangkat,tanggal,pukul,tujuan)
//            VALUES (:no_jadwal,:id_mobil,:id_supir,:tempat_berangkat,:tanggal,:pukul,:tujuan)";
//    /** @var TYPE_NAME $connection */
//    $statement = $connection->prepare($query);
//
//    $statement->bindValue(":no_jadwal", $no_jadwal);
//    $statement->bindValue(":id_mobil", $id_mobil);
//    $statement->bindValue(":id_supir", $id_supir);
//    $statement->bindValue(":tempat_kejadian", $tempat_berangkat);
//    $statement->bindValue(":tanggal", $tanggal);
//    $statement->bindValue(":pukul", $pukul);
//    $statement->bindValue(":tujuan", $tujuan);
//
//    $isOk = $statement->execute();
//}catch (Exception $exception) {
//    $reply['error'] = $exception->getMessage();
//    echo json_encode($reply);
//    http_response_code(400);
//    exit(0);
//}
//if(!$isOk){
//    $reply['error'] = $statement->errorInfo();
//    http_response_code(400);
//}
//$lastId = $connection->lastInsertId();
//$getResult = "SELECT * FROM jadwal_berangkat WHERE no_jadwal = :no_jadwal";
//$stm = $connection->prepare($getResult);
//$stm->bindValue(':no_jadwal', $lastId);
//$stm->execute();
//$result = $stm->fetch(PDO::FETCH_ASSOC);
//
//
//$reply['status'] = $isOk;
//echo json_encode($reply);
