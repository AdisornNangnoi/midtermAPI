<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST"); //POST, PUT, DELETE
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
$trip->user_id = $data->user_id;
$trip->start_date = $data->start_date;
$trip->end_date = $data->end_date;
$trip->location_name = $data->location_name;
$trip->latitude = $data->latitude;
$trip->longitude = $data->longitude;
$trip->cost = $data->cost;
$trip->created_at = $data->created_at;

//เรียกใช้ฟังก์ชันตรวจสอบชื
$result = $trip->newTrip();

//ตรวจสอบข้อมูลจากการเรัยกใช้ฟังก์ชัน
if ($result == true) {
    //insert-update-delete สำเร็จ
    $resultArray = array(
        "message" => "เพิ่มข้อมูลใหม่แล้ว!!"
    );
    echo json_encode($resultArray, JSON_UNESCAPED_UNICODE);
} else {
    //insert-update-delete ไม่สำเร็จ
    $resultArray = array(
        "message" => "เพิ่มข้อมูลไม่สำเร็จ!!"
    );
    echo json_encode($resultArray, JSON_UNESCAPED_UNICODE);
}







?>