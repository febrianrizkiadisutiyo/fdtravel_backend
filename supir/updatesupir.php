<?php
include '../connection.php';

$formData = [];
parse_str(file_get_contents('php://input'), $formData);

$id_supir = $formData['id_supir']??'';
$nama = $formData['nama']??'';
$ttl = $formData['ttl']??'';
$alamat = $formData['alamat']??'';
$no_hp = $formData['no_hp']??'';
$jenis_kelamin = $formData['jenis_kelamin']??'';

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
    $fields=[];
    $query = "UPDATE supir SET nama = :nama, ttl = :ttl, alamat = :alamat, no_hp = :no_hp, jenis_kelamin = :jenis_kelamin
            WHERE id_supir = :id_supir";
    $statement = $connection->prepare($query);

    $statement->bindValue(":id_supir", $id_supir);
    $statement->bindValue(":nama", $nama);
    $statement->bindValue(":ttl", $ttl);
    $statement->bindValue(":alamat", $alamat);
    $statement->bindValue(":no_hp", $no_hp);
    $statement->bindValue(":jenis_kelamin", $jenis_kelamin);

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
$getResult = "SELECT * FROM supir WHERE id_supir = :id_supir";
$stm = $connection->prepare($getResult);
$stm->bindValue(':id_supir', $id_supir);
$stm->execute();
$result = $stm->fetch(PDO::FETCH_ASSOC);

$dataFinal = [
    'id_supir' => $result['id_supir'],
    'nama' => $result['nama'],
    'ttl' => $result['ttl'],
    'alamat' => $result['alamat'],
    'no_hp' => $result['no_hp'],
    'jenis_kelamin' => $result['jenis_kelamin'],

];
$reply['data'] = $dataFinal;
$reply['status'] = $isOk;
echo json_encode($reply);