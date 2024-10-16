<?php //get_all_diaryfood_by_member_api.php ดึงข้อมูลเฉพาะข้อมูลการกินของสมาชิกคนนั้นๆเท่านั้น
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET"); //POST, PUT, DELETE
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json; charset=UTF-8");

require_once "./../connectdb.php";
require_once "./../models/trip.php";

//สร้าง Instance (Object/ตัวแทน)
$connDB = new ConnectDB();
$trip = new Trip($connDB->getConnectionDB());

$data = json_decode(file_get_contents("php://input"));

$trip->user_id = $data->user_id;
//เรียกใช้ฟังก์ชันดึงข้อมูลทั้งหมดจากตาราง diaryfood_tb
$result = $trip->getAllTripByUserId();

//ตรวจสอบข้อมูลจากการเรัยกใช้ฟังก์ชันตรวจสอบชื่อผู้ใช้ รหัสผ่าน
if ($result->rowCount() > 0) {
    //มี
    $resultInfo = array();

    //Extract ข้อมูลที่ได้มาจากคำสั่ง SQL เก็บในตัวแปร
    while ($resultData = $result->fetch(PDO::FETCH_ASSOC)) {
        extract($resultData);
        //สร้างตัวแปรอาร์เรย์เก็บข้อมูล
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


    echo json_encode($resultInfo, JSON_UNESCAPED_UNICODE);
} else {
    $resultInfo = array();
    $resultArray = array(
        "message" => "0"
    );
    array_push($resultInfo, $resultArray);
    echo json_encode(array("message" => "ไม่พบข้อมูลการเดินทาง"), JSON_UNESCAPED_UNICODE);
}
