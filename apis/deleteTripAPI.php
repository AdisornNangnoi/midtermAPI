<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: DELETE"); //POST, PUT, DELETE
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json; charset=UTF-8");

require_once "./../connectdb.php";
require_once "./../models/trip.php";

//สร้าง Instance (Object/ตัวแทน)
$connDB = new ConnectDB();
$trip = new Trip($connDB->getConnectionDB());

//รับค่าจาก Client/User ซึ่งเป็น JSON มา Decode เก็บในตัวแปร
$data = json_decode(file_get_contents("php://input"));

//เอาค่าในตัวแปรกำหนดให้กับ ตัวแปรของ Model ที่สร้างไว้
$trip->trip_id = $data->trip_id;

//เรียกใช้ฟังก์ชันลบข้อมูล
$result = $trip->deleteTrip();

//ตรวจสอบข้อมูลจากการเรัยกใช้ฟังก์ชันลบ
if ($result === true) {
    //ลบสำเร็จ
    $resultArray = array(
        "message" => "ลบข้อมูลสำเร็จ!!"
    );
    echo json_encode($resultArray, JSON_UNESCAPED_UNICODE);
} else {
    //ลบไม่สำเร็จ
    $resultArray = array(
        "message" => "ลบข้อมูลไม่สำเร็จ!! หรือข้อมูลไม่พบ"
    );
    echo json_encode($resultArray, JSON_UNESCAPED_UNICODE);
}
?>
