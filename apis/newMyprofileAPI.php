<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST"); //POST, PUT, DELETE
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json; charset=UTF-8");

require_once "./../connectdb.php";
require_once "./../models/myprofile.php";

//สร้าง Instance (Object/ตัวแทน)
$connDB = new ConnectDB();
$myprofile = new Myprofile($connDB->getConnectionDB());

//รับค่าจาก Client/User ซึ่งเป็น JSON มา Decode เก็บในตัวแปร
$data = json_decode(file_get_contents("php://input"));

//เอาค่าในตัวแปรกำหนดให้กับ ตัวแปรของ Model ที่สร้างไว้
$myprofile->username = $data->username;
$myprofile->email = $data->email;
$myprofile->password = $data->password;

//เรียกใช้ฟังก์ชันตรวจสอบชื่อผู้ใช้ รหัสผ่าน
$result = $myprofile->newMyprofile();

//ตรวจสอบข้อมูลจากการเรัยกใช้ฟังก์ชันตรวจสอบชื่อผู้ใช้ รหัสผ่าน
if ($result == true) {
    //insert-update-delete สำเร็จ
    $resultArray = array(
        "message" => "เพิ่มผู้ใช้ใหม่เรียบร้อย!!"
    );
    echo json_encode($resultArray, JSON_UNESCAPED_UNICODE);
} else {
    //insert-update-delete ไม่สำเร็จ
    $resultArray = array(
        "message" => "เพิ่มผู้ใช้ใหม่ไม่สำเร็จ!!"
    );
    echo json_encode($resultArray, JSON_UNESCAPED_UNICODE);
}







?>