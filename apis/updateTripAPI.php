<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST"); // POST, PUT, DELETE
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json; charset=UTF-8");

require_once "./../connectdb.php";
require_once "./../models/trip.php";

// สร้าง Instance (Object/ตัวแทน)
$connDB = new ConnectDB();
$trip = new Trip($connDB->getConnectionDB());

// รับค่าจาก Client/User ซึ่งเป็น JSON มา Decode เก็บในตัวแปร
$data = json_decode(file_get_contents("php://input"));

// ตรวจสอบค่าที่ได้รับจาก Client/User
if (empty($data->trip_id) || empty($data->user_id) || empty($data->start_date) || empty($data->end_date) || empty($data->location_name) || empty($data->latitude) || empty($data->longitude) || empty($data->cost)) {
    $resultArray = array(
        "message" => "กรุณากรอกข้อมูลให้ครบถ้วน"
    );
    echo json_encode($resultArray, JSON_UNESCAPED_UNICODE);
    exit; // หยุดการทำงานของสคริปต์
}


// เอาค่าในตัวแปรกำหนดให้กับตัวแปรของ Model ที่สร้างไว้
$trip->trip_id = $data->trip_id;
$trip->user_id = $data->user_id; // รับ user_id
$trip->start_date = $data->start_date;
$trip->end_date = $data->end_date;
$trip->location_name = $data->location_name;
$trip->latitude = $data->latitude;
$trip->longitude = $data->longitude;
$trip->cost = $data->cost;

// เรียกใช้ฟังก์ชัน updateTrip
$result = $trip->updateTrip();

// ตรวจสอบผลลัพธ์จากการเรียกใช้ฟังก์ชัน updateTrip
if ($result) {
    // update สำเร็จ
    $resultArray = array(
        "message" => "อัพเดทข้อมูลสำเร็จ!!"
    );
    echo json_encode($resultArray, JSON_UNESCAPED_UNICODE);
} else {
    // update ไม่สำเร็จ
    $resultArray = array(
        "message" => "อัพเดทข้อมูลไม่สำเร็จ!!"
    );
    echo json_encode($resultArray, JSON_UNESCAPED_UNICODE);
}
?>
