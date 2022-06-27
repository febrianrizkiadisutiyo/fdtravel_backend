<?php
include '../connection.php';

$formData = [];
parse_str(file_get_contents('php://input'), $formData);

$no_jadwal = $formData['no_jadwal']??0;
$id_mobil = $formData['id_mobil']??0;
$id_supir = $formData['id_supir']??0;
$tempat_berangkat = $formData['tempat_berangkat']??'';
$tanggal = $formData['tanggal']??'';
$pukul = $formData['pukul']??'';
$tujuan = $formData['tujuan']??'';

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
    $queryCheck ="SELECT * FROM jadwal_berangkat where no_jadwal = :no_jadwal";
    /** @var TYPE_NAME $connection */
    $statement = $connection->prepare($queryCheck);
    $statement->bindValue(':no_jadwal', $no_jadwal);
    $statement->execute();
    $row = $statement->rowCount();

    if($row === 0){
        $reply['error'] = 'Data tidak ditemukan  '.$no_jadwal;
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
    $query = "UPDATE jadwal_berangkat SET id_mobil = :id_mobil, id_supir = :id_supir, tempat_berangkat = :tempat_berangkat, tanggal = :tanggal, pukul = :pukul, tujuan = :tujuan
            WHERE no_jadwal = :no_jadwal";
    $statement = $connection->prepare($query);


    $statement->bindValue(":no_jadwal", $no_jadwal);
    $statement->bindValue(":id_mobil", $id_mobil);
    $statement->bindValue(":id_supir", $id_supir);
    $statement->bindValue(":tempat_berangkat", $tempat_berangkat);
    $statement->bindValue(":tanggal", $tanggal);
    $statement->bindValue(":pukul", $pukul);
    $statement->bindValue(":tujuan", $tujuan);

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
$getResult = "SELECT * FROM jadwal_berangkat WHERE no_jadwal = :no_jadwal";
$stm = $connection->prepare($getResult);
$stm->bindValue(':no_jadwal', $no_jadwal);
$stm->execute();
$result = $stm->fetch(PDO::FETCH_ASSOC);

$dataFinal = [
    'no_jadwal'=>$result['no_jadwal'],
    'id_mobil' => $result['id_mobil'],
    'id_supir' => $result['id_supir'],
    'tempat_berangkat' => $result['tempat_berangkat'],
    'tanggal' => $result['tanggal'],
    'pukul' => $result['pukul'],
    'tujuan' => $result['tujuan'],

];
$reply['data'] = $dataFinal;
$reply['status'] = $isOk;
echo json_encode($reply);















//if($_SERVER['REQUEST_METHOD'] !== 'PATCH'){
//    header('Content-Type: application/json');
//    http_response_code(400);
//    $reply['error'] = 'PATCH method required';
//    echo json_encode($reply);
//    exit();
//}
//$formData = [];
//parse_str(file_get_contents('php://input'), $formData);
//
//if($_POST){
//    $no_jadwal = $_POST['no_jadwal'];
//    $id_mobil = $_POST['id_mobil'];
//    $id_supir = $_POST['id_supir'];
//    $tempat_berangkat = $_POST['tempat_berangkat'];
//    $tanggal = $_POST['tanggal'];
//    $pukul = $_POST['pukul'];
//    $tujuan = $_POST['tujuan'];
//
//    /** @var TYPE_NAME $connection */
//    $statement = $connection->prepare("UPDATE `jadwal_berangkat` set id_mobil='$id_mobil', id_supir='$id_supir',tempat_berangkat='$tempat_berangkat',tanggal='$tanggal',pukul='$pukul',tujuan='$tujuan'
//                                          WHERE no_jadwal='$no_jadwal'");
//    $statement->execute();
//
//    $response['message'] = "berhasil";
//    $response['data']=[
//        'no_jadwal'=>$no_jadwal,
//        'id_mobil'=>$id_mobil,
//        'id_supir'=>$id_supir,
//        'tempat_berangkat'=>$tempat_berangkat,
//        'tanggal'=>$tanggal,
//        'pukul'=>$pukul,
//        'tujuan'=>$tujuan
//    ];
//
//    //Jadikan data dalam bentuk JSON
//    $json = json_encode($response, JSON_PRETTY_PRINT);
//
//    //print json
//    echo $json;
//} else {
//    $response['message'] = "Update Gagal";
//    //Jadikan data dalam bentuk JSON
//    $json = json_encode($response, JSON_PRETTY_PRINT);
//
//    //print json
//    echo $json;
//}




//$isValidated = true;
//if(empty($no_jadwal)){
//    $reply['error'] = 'ID Berangkat harus diisi';
//    $isValidated = false;
//}
//if(empty($id_mobil)){
//    $reply['error'] = 'ID Mobil harus diisi';
//    $isValidated = false;
//}
//if(empty($id_supir)){
//    $reply['error'] = 'ID Supir harus diisi';
//    $isValidated = false;
//}
//if(empty($tempat_berangkat)){
//    $reply['error'] = 'Tempat berangkat harus diisi';
//    $isValidated = false;
//}
//if(empty($tanggal)){
//    $reply['error'] = 'Tanggal harus diisi';
//    $isValidated = false;
//}
//if(empty($pukul)){
//    $reply['error'] = 'Jam harus diisi';
//    $isValidated = false;
//}
//if(empty($tujuan)){
//    $reply['error'] = 'Tujuan harus diisi';
//    $isValidated = false;
//}
//if(!$isValidated){
//    echo json_encode($reply);
//    http_response_code(400);
//    exit(0);
//}
//try{
//    $queryCheck ="SELECT * FROM jadwal_berangkat where no_jadwal = :no_jadwal";
//    /** @var TYPE_NAME $connection */
//    $statement = $connection->prepare($queryCheck);
//    $statement->bindValue(':no_jadwal', $no_jadwal);
//    $statement->execute();
//    $row = $statement->rowCount();
//
//    if($row === 0){
//        $reply['error'] = 'Data tidak ditemukan  '.$no_jadwal;
//        echo json_encode($reply);
//        http_response_code(400);
//        exit(0);
//    }
//}catch (Exception $exception){
//    $reply['error'] = $exception->getMessage();
//    echo json_encode($reply);
//    http_response_code(400);
//    exit(0);
//}
//try{
//    $fields=[];
//    $query = "UPDATE jadwal_berangkat SET id_mobil = :id_mobil, id_supir = :id_supir, tempat_berangkat = :tempat_berangkat,tanggal = :tanggal,pukul = :pukul, tujuan = :tujuan,
//            WHERE no_jadwal = :no_jadwal";
//    $statement = $connection->prepare($query);
//
//    $statement->bindValue(":no_jadwal", $no_jadwal);
//    $statement->bindValue(":id_mobil", $id_mobil);
//    $statement->bindValue(":id_supir", $id_supir);
//    $statement->bindValue(":tempat_berangkat", $tempat_berangkat);
//    $statement->bindValue(":tanggal", $tanggal);
//    $statement->bindValue(":pukul", $pukul);
//    $statement->bindValue(":tujuan", $tujuan);
//
//    $isOk = $statement->execute();
//} catch (Exception $exception){
//    $reply['error'] = $exception->getMessage();
//    echo json_encode($reply);
//    http_response_code(400);
//    exit(0);
//}
//if(!$isOk){
//    $reply['error'] = $statement->errorInfo();
//    http_response_code(400);
//}
//$getResult = "SELECT * FROM jadwal_berangkat WHERE no_jadwal = :no_jadwal";
//$stm = $connection->prepare($getResult);
//$stm->bindValue(':no_jadwal', $no_jadwal);
//$stm->execute();
//$result = $stm->fetch(PDO::FETCH_ASSOC);
//
//$dataFinal = [
//    'no_jadwal' => $result['no_jadwal'],
//    'id_mobil' => $result['id_mobil'],
//    'id_supir' => $result['id_supir'],
//    'tempat_berangkat' => $result['tempat_berangkat'],
//    'tanggal' => $result['tanggal'],
//    'pukul' => $result['pukul'],
//    'tujuan' => $result['tujuan'],
//];
//$reply['data'] = $dataFinal;
//$reply['status'] = $isOk;
//echo json_encode($reply);
