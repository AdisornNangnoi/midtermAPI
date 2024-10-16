<?php
// get_all_trip_by_member_api.php ดึงข้อมูลการเดินทางของสมาชิกคนนั้นๆ
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET"); // POST, PUT, DELETE
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json; charset=UTF-8");

require_once "./../connectdb.php";
require_once "./../models/trip.php";

//สร้าง Instance (Object/ตัวแทน)
$connDB = new ConnectDB();
$trip = new Trip($connDB->getConnectionDB());

// รับข้อมูล JSON
$data = json_decode(file_get_contents("php://input"));

// ตรวจสอบว่าข้อมูลที่รับเข้ามาครบหรือไม่
if (!empty($data->user_id) && !empty($data->location_name)) {

    $trip->user_id = $data->user_id;
    $trip->location_name = $data->location_name;

    // เรียกใช้ฟังก์ชันดึงข้อมูลการเดินทางทั้งหมดของสมาชิกตามเงื่อนไข
    $result = $trip->getAllTripByUserIdAndLocation();

    // ตรวจสอบข้อมูลจากการเรียกใช้ฟังก์ชัน
    if ($result->rowCount() > 0) {
        // มีข้อมูล
        $resultInfo = array();

        // Extract ข้อมูลที่ได้มาจากคำสั่ง SQL เก็บในตัวแปร
        while ($resultData = $result->fetch(PDO::FETCH_ASSOC)) {
            extract($resultData);
            // สร้างตัวแปรอาร์เรย์เก็บข้อมูล
            $resultArray = array(
                "trip_id" => $trip_id,
                "user_id" => $user_id,
                "start_date" => $start_date,
                "end_date" => $end_date,
                "location_name" => $location_name,
                "latitude" => $latitude,
                "longitude" => $longitude,
                "cost" => $cost
            );
            array_push($resultInfo, $resultArray);
        }

        // ส่งข้อมูลในรูปแบบ JSON พร้อมรองรับภาษาไทย
        echo json_encode($resultInfo, JSON_UNESCAPED_UNICODE);
    } else {
        // ไม่พบข้อมูล
        echo json_encode(array("message" => "ไม่พบข้อมูลการเดินทาง"), JSON_UNESCAPED_UNICODE);
    }
} else {
    // กรณีข้อมูลที่ส่งมาไม่ครบ
    echo json_encode(array("message" => "ข้อมูลไม่ครบถ้วน"), JSON_UNESCAPED_UNICODE);
}
