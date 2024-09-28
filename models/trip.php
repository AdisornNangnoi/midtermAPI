<?php

class Trip
{
    //ตัวแปรที่ใช้เก็บการติดต่อฐานข้อมูล
    private $connDB;

    //ตัวแปรที่ทำงานคู่กับคอลัมน์(ฟิวล์)ในตาราง
    public $trip_id;
    public $user_id;
    public $start_date;
    public $end_date;
    public $location_name;
    public $latitude;
    public $longitude;
    public $cost;
    public $created_at;

    //ตัวแปรสารพัดประโยชน์
    public $message;

    //constructor
    public function __construct($connDB)
    {
        $this->connDB = $connDB;
    }
    //----------------------------------------------
    //ฟังก์ชันการทำงานที่ล้อกับส่วนของ APIs

    //ฟังก์ชันเพิ่มข้อมูลผู้ใช้ใหม่
    public function newTrip()
    {
        //ตัวแปรเก็บคำสั่ง SQL
        $strSQL = "INSERT INTO trip_tb (user_id, start_date, end_date, location_name, latitude, longitude, cost, created_at) VALUES (:user_id, :start_date, :end_date, :location_name, :latitude, :longitude, :cost, :created_at)";
        //ตรวจสอบค่าที่ถูกส่งจาก Client/User ก่อนที่จะกำหนดให้กับ parameters (:????)
        $this->user_id = intval(htmlspecialchars(strip_tags($this->user_id)));
        $this->start_date = htmlspecialchars(strip_tags($this->start_date));
        $this->end_date = htmlspecialchars(strip_tags($this->end_date));
        $this->location_name = htmlspecialchars(strip_tags($this->location_name));
        $this->latitude = htmlspecialchars(strip_tags($this->latitude));
        $this->longitude = htmlspecialchars(strip_tags($this->longitude));
        $this->cost = htmlspecialchars(strip_tags($this->cost));
        $this->created_at = htmlspecialchars(strip_tags($this->created_at));

        //สร้างตัวแปรที่ใช้ทำงานกับคำสั่ง SQL
        $stmt = $this->connDB->prepare($strSQL);

        //เอาที่ผ่านการตรวจแล้วไปกำหนดให้กับ parameters 
        $stmt->bindParam(":user_id", $this->user_id);
        $stmt->bindParam(":start_date", $this->start_date);
        $stmt->bindParam(":end_date", $this->end_date);
        $stmt->bindParam(":location_name", $this->location_name);
        $stmt->bindParam(":latitude", $this->latitude);
        $stmt->bindParam(":longitude", $this->longitude);
        $stmt->bindParam(":cost", $this->cost);
        $stmt->bindParam(":created_at", $this->created_at);

        //สั่งให้ SQL ทำงาน และส่งผลลัพธ์ว่าเพิ่มข้อมูลสําเร็จหรือไม่
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    //ฟังก์ชันดึงข้อมูลของคนนั้นๆ
    public function getAllTripByUserId()
    {
        //ตัวแปรเก็บคำสั่ง SQL
        $strSQL = "SELECT * FROM trip_tb WHERE user_id = :user_id";

        $this->memId = intval(htmlspecialchars(strip_tags($this->user_id)));

        //สร้างตัวแปรที่ใช้ทำงานกับคำสั่ง SQL
        $stmt = $this->connDB->prepare($strSQL);
        $stmt->bindParam(":user_id", $this->user_id);
        //สั่งให้ SQL ทำงาน
        $stmt->execute();

        //ส่งค่าผลการทำงานกลับไปยังจุดเรียกใช้ฟังก์ชันนี้
        return $stmt;
    }


    //ฟังก์ชันดึงข้อมูลของคนนั้นๆ
    public function getAllTripByUserIdAndDate()
    {
        // ตัวแปรเก็บคำสั่ง SQL พร้อมเงื่อนไขวันที่เริ่มและวันที่สิ้นสุด
        $strSQL = "SELECT * FROM trip_tb WHERE user_id = :user_id AND start_date >= :start_date AND end_date <= :end_date";

        // กำหนดค่าให้ตัวแปร user_id
        $this->memId = intval(htmlspecialchars(strip_tags($this->user_id)));

        // สร้างตัวแปรที่ใช้ทำงานกับคำสั่ง SQL
        $stmt = $this->connDB->prepare($strSQL);

        // ทำการ bind ตัวแปร user_id, start_date และ end_date
        $stmt->bindParam(":user_id", $this->user_id);
        $stmt->bindParam(":start_date", $this->start_date);
        $stmt->bindParam(":end_date", $this->end_date);

        // สั่งให้ SQL ทำงาน
        $stmt->execute();

        // ส่งค่าผลการทำงานกลับไปยังจุดเรียกใช้ฟังก์ชันนี้
        return $stmt;
    }

    //ดึงข้อมูลจากสถานที่
    public function getAllTripByUserIdAndLocation()
    {
        // ตัวแปรเก็บคำสั่ง SQL พร้อมเงื่อนไขชื่อสถานที่ที่มีความคล้ายคลึงกัน
        $strSQL = "SELECT * FROM trip_tb WHERE user_id = :user_id AND location_name LIKE :location_name";

        // กำหนดค่าให้ตัวแปร user_id และ location_name
        $this->user_id = intval(htmlspecialchars(strip_tags($this->user_id)));
        $location = "%" . htmlspecialchars(strip_tags($this->location_name)) . "%"; // การค้นหาที่ตรงกันบางส่วน

        // สร้างตัวแปรที่ใช้ทำงานกับคำสั่ง SQL
        $stmt = $this->connDB->prepare($strSQL);

        // ทำการ bind ตัวแปร user_id และ location_name
        $stmt->bindParam(":user_id", $this->user_id);
        $stmt->bindParam(":location_name", $location); // ใช้ LIKE กับ location_name

        // สั่งให้ SQL ทำงาน
        $stmt->execute();

        // ส่งค่าผลการทำงานกลับไปยังจุดเรียกใช้ฟังก์ชันนี้
        return $stmt;
    }


    // ฟังก์ชันดึงข้อมูลตาม user_id และช่วงค่าใช้จ่าย
    public function getAllTripByUserIdAndCostRange($min_cost, $max_cost)
    {
        // คำสั่ง SQL สำหรับค้นหาทริปตามช่วงค่าใช้จ่าย
        $strSQL = "SELECT * FROM trip_tb WHERE user_id = :user_id AND cost BETWEEN :min_cost AND :max_cost";

        // สร้างตัวแปรที่ใช้ทำงานกับคำสั่ง SQL
        $stmt = $this->connDB->prepare($strSQL);

        // Bind parameters
        $stmt->bindParam(":user_id", $this->user_id);
        $stmt->bindParam(":min_cost", $min_cost);
        $stmt->bindParam(":max_cost", $max_cost);

        // สั่งให้ SQL ทำงาน
        $stmt->execute();

        // ส่งค่าผลการทำงานกลับไปยังจุดเรียกใช้ฟังก์ชันนี้
        return $stmt;
    }


    // updateTrip() ฟังก์ชัน
public function updateTrip()
{
    // ตรวจสอบว่า trip_id และ user_id ไม่ว่าง
    if (empty($this->trip_id) || empty($this->user_id) || empty($this->start_date) || empty($this->end_date) || empty($this->location_name) || empty($this->latitude) || empty($this->longitude) || empty($this->cost)) {
        return false; // ส่ง false หากค่าที่จำเป็นขาดหายไป
    }

    // ตรวจสอบว่ามี trip_id นี้อยู่ในฐานข้อมูลหรือไม่
    $checkSQL = "SELECT * FROM trip_tb WHERE trip_id = :trip_id";
    $checkStmt = $this->connDB->prepare($checkSQL);
    $checkStmt->bindParam(":trip_id", $this->trip_id);
    $checkStmt->execute();

    if ($checkStmt->rowCount() > 0) {
        // trip_id มีอยู่ในฐานข้อมูล
        $strSQL = "UPDATE trip_tb SET user_id = :user_id, start_date = :start_date, end_date = :end_date, location_name = :location_name, latitude = :latitude, longitude = :longitude, cost = :cost WHERE trip_id = :trip_id";

        $stmt = $this->connDB->prepare($strSQL);
        
        // กำหนดค่าที่ใช้ในคำสั่ง SQL
        $stmt->bindParam(":trip_id", $this->trip_id);
        $stmt->bindParam(":user_id", $this->user_id);
        $stmt->bindParam(":start_date", $this->start_date);
        $stmt->bindParam(":end_date", $this->end_date);
        $stmt->bindParam(":location_name", $this->location_name);
        $stmt->bindParam(":latitude", $this->latitude);
        $stmt->bindParam(":longitude", $this->longitude);
        $stmt->bindParam(":cost", $this->cost);

        // สั่งให้ SQL ทำงานและส่งผลลัพธ์
        return $stmt->execute();
    } else {
        // trip_id ไม่พบในฐานข้อมูล
        return false; // หรือส่งค่าที่เหมาะสมกลับ
    }
}



    //ฟังก์ชันลบข้อมูล
    public function deleteTrip() {
        // ตรวจสอบว่ามี trip_id นี้อยู่ในฐานข้อมูลหรือไม่
        $checkSQL = "SELECT * FROM trip_tb WHERE trip_id = :trip_id";
        $stmtCheck = $this->connDB->prepare($checkSQL);
        $stmtCheck->bindParam(":trip_id", $this->trip_id);
        $stmtCheck->execute();
    
        // ถ้าไม่พบข้อมูล
        if ($stmtCheck->rowCount() === 0) {
            return false; // ข้อมูลไม่พบ ไม่สามารถลบได้
        }
    
        // ถ้ามีข้อมูลก็ทำการลบ
        $strSQL = "DELETE FROM trip_tb WHERE trip_id = :trip_id";
        $stmt = $this->connDB->prepare($strSQL);
        $stmt->bindParam(":trip_id", $this->trip_id);
    
        return $stmt->execute(); // ส่งค่าผลลัพธ์การลบ
    }
    

}
