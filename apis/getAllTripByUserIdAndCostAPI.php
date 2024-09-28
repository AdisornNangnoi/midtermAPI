<?php
// get_all_trip_by_cost_range_api.php ดึงข้อมูลการเดินทางตามช่วงค่าใช้จ่าย
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET"); // POST, PUT, DELETE
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json; charset=UTF-8");

require_once "./../connectdb.php";
require_once "./../models/trip.php";

// สร้าง Instance (Object/ตัวแทน)
$connDB = new ConnectDB();
$trip = new Trip($connDB->getConnectionDB());

// รับข้อมูล JSON
$data = json_decode(file_get_contents("php://input"));

// ตรวจสอบว่าข้อมูลที่รับเข้ามาครบหรือไม่
if (!empty($data->user_id) && isset($data->min_cost) && isset($data->max_cost)) {

    // กำหนดค่า user_id และช่วงค่าใช้จ่ายให้กับฟังก์ชัน
    $trip->user_id = $data->user_id;
    $min_cost = $data->min_cost;
    $max_cost = $data->max_cost;

    // เรียกใช้ฟังก์ชันดึงข้อมูลการเดินทางตามช่วงค่าใช้จ่าย
    $result = $trip->getAllTripByUserIdAndCostRange($min_cost, $max_cost);

    // ตรวจสอบข้อมูลจากการเรียกใช้ฟังก์ชัน
    if ($result->rowCount() > 0) {
        // มีข้อมูล
        $resultInfo = array();

        // Extract ข้อมูลที่ได้มาจากคำสั่ง SQL เก็บในตัวแปร
        while ($resultData = $result->fetch(PDO::FETCH_ASSOC)) {
            extract($resultData);
            // สร้างตัวแปรอาร์เรย์เก็บข้อมูล
            $resultArray = array(
                "รหัสการเดินทาง" => $trip_id,
                "รหัสผู้ใช้" => $user_id,
                "วันที่เริ่ม" => $start_date,
                "วันที่สิ้นสุด" => $end_date,
                "ชื่อสถานที่" => $location_name,
                "ละติจูด" => $latitude,
                "ลองติจูด" => $longitude,
                "ค่าใช้จ่าย" => $cost
            );
            array_push($resultInfo, $resultArray);
        }

        // ส่งข้อมูลในรูปแบบ JSON พร้อมรองรับภาษาไทย
        echo json_encode($resultInfo, JSON_UNESCAPED_UNICODE);
    } else {
        // ไม่พบข้อมูล
        echo json_encode(array("message" => "ไม่พบข้อมูลการเดินทางในช่วงค่าใช้จ่ายนี้"), JSON_UNESCAPED_UNICODE);
    }
} else {
    // กรณีข้อมูลที่ส่งมาไม่ครบ
    echo json_encode(array("message" => "ข้อมูลไม่ครบถ้วน"), JSON_UNESCAPED_UNICODE);
}
