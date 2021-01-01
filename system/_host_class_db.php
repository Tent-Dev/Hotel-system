<?php 
$BASE_domain = "https://tenthotel.000webhostapp.com";

define('VERSION_NUMBER', 31);

class Main_db{
	var $db_host;
	var $db_user;
	var $db_pass;
	var $db_name;
	var $db_connection;

	public function Main_db(){
		$this->db_host = "localhost";
		$this->db_user = "id10784191_itsofun01";
		$this->db_pass = "TaTent591220306";
		$this->db_name = "id10784191_myport";
	}

	public function Connect_db(){
		$this->db_connection = mysqli_connect($this->db_host,$this->db_user,$this->db_pass,$this->db_name);
		mysqli_set_charset($this->db_connection,"utf8"); //set utf8
	}

	public function SetCharacter(){
		$set_char1 = "SET character_set_results = utf8";
		$set_char2 = "SET character_set_client = utf8";
		$set_char3 = "SET character_set_connection = utf8";
		mysqli_query($this->db_connection,$set_char1);
		mysqli_query($this->db_connection,$set_char2);
		mysqli_query($this->db_connection,$set_char3);
	}

	public function Select_db($sql){
		$result = mysqli_query($this->db_connection,$sql);
		$array = array();

		while ($row = mysqli_fetch_array($result)) { //คืนค่ามา
			array_push($array, $row); //เพิ่ม value เข้าไปใน array ตำแหน่งท้ายสุด
		}
		mysqli_free_result($result);
		return $array;
	}

	public function Select_db_one($sql){
		$result = mysqli_query($this->db_connection,$sql);
		$query = mysqli_fetch_array($result);
		return $query;
	}

	public function numRows($query) {
        $result  = mysqli_query($this->db_connection, $query);
        $rowcount = mysqli_num_rows($result);
        return $rowcount;
    }

	public function Insert_db($arr,$tableName){
		$str = "INSERT INTO ".$tableName."(".implode(",", array_keys($arr)).")"; //implode แทรก string
		$str2 = " VALUES('".implode("','",$arr)."')";
		$sql = $str.$str2;
		//var_dump($sql);
		//echo $sql;
		$result = mysqli_query($this->db_connection,$sql);
		return $result;
	}

	public function Update_db($arr,$key,$tableName){
		$sql = "UPDATE ".$tableName." SET ";
		$last_key = end(array_keys($arr)); //end เลื่อน pointer ไปตำแหน่งท้ายสุดของ array, array_key ใช้คืนค่า key ทั้งหมดแบบ array โดย array ที่ได้จะมี key ตั้งแต่ 0 ขั้นไปตามลำดับ
		$last_arr = end($key);
		$where = " WHERE ";
		foreach ($arr as $k => $value) { //นำข้อมูลออกมาจากตัวแปลที่เป็นประเภท array โดยสามารถเรียกค่าได้ทั้ง $key และ $value ของ array
			$value = $this->quote($value);
			
			$sql.= $k." = '".$value."' ";

			if ($k != $last_key){ //เช็คว่า array ยังไม่ใช่ตัวสุดท้าย ให้ใส่เครื่องหมาย , ต่อท้าย เช่น 'field1','field2' จบ
				$sql.=",";
			}
			if (in_array($k, $key)){ //in_array ใช้ตรวจสอบว่ามีข้อมูลที่กำหนดใน array หรือไม่ ถ้ามีก็ใช้ AND ต่อ
				$where.= $k." = '".$value."' ";

				if ($k != $last_arr){
					$where.= " AND ";

				}

			}
		}

		$result = mysqli_query($this->db_connection,$sql.$where);

		return $result;
	}

	//login with Prepared statement
	public function login($username){
		$sql = "SELECT h_user_id, h_user_username, h_user_password, h_userrank_permission FROM h_user
				JOIN h_userrank ON h_userrank.h_userrank_id = h_user.h_user_rank
			 	WHERE h_user_username = ?";
		$protect = mysqli_prepare($this->db_connection,$sql);
		mysqli_stmt_bind_param($protect, "s", $username);

		if(mysqli_stmt_execute($protect)){
			mysqli_stmt_bind_result($protect,$user_id, $user_username,$user_password,$user_permission);
			$result = mysqli_stmt_fetch($protect);
			$arr = array(
					'h_user_username' => $user_username,
					'h_user_id' => $user_id,
					'h_user_password' =>$user_password,
					'h_userrank_permission' => $user_permission
			 		);
		}
		else{
		echo mysqli_stmt_error;
		}
		return $arr;
	}

	//ป้องกันตัวอักษร
	public function quote($str){
    	return $this->db_connection->real_escape_string($str);
    }
	public function Delete_db($sql){
		$result = mysqli_query($this->db_connection,$sql);
		return $result;
	}

	public function Close_db(){
		$result = mysqli_close($this->db_connection);
		return $result;
	}

	// Does string contain letters?
	public function Check_letters($string){
	    return preg_match( '/[a-zA-Z]/', $string );
	}
	// Does string contain numbers?
	public function Check_numbers($string){
	    return preg_match( '/\d/', $string );
	}
	// Does string contain special characters?
	public function Check_special_chars($string){
	    return preg_match('/[^a-zA-Z\d]/', $string);
	}

	public function Check_same($tablename,$field,$value){
		$sql = "SELECT * FROM ".$tablename." WHERE ".$field." = '".$value."'";
		$count = $this->numRows($sql);
		if($count >0){
			$result = true;
		}else{
			$result = false;
		}
		return $result;
	}

	//////////hotel function///////////

	//แปลงวันที่ให้คำนวณได้
	function dateDifference($date_1 , $date_2 , $differenceFormat = '%a' ){
	    $datetime1 = date_create($date_1);
	    $datetime2 = date_create($date_2);
	    $interval = date_diff($datetime1, $datetime2);
    	return $interval->format($differenceFormat);
	}

	//สุ่มชุดตัวอักษร ทำ Session
	function ran(){
		$length = 15;    
		$ran = substr(str_shuffle('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ'),1,$length);
		return $ran;
	}

	//ติดต่อ API
	function curl(array $postAPI,$urlpost){

		$url = curl_init();
			curl_setopt($url, CURLOPT_URL,"$urlpost");
			curl_setopt($url, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($url, CURLOPT_POSTFIELDS, $postAPI);
			curl_setopt($url, CURLOPT_VERBOSE, 1);

			// execute!
			$response = json_decode(curl_exec($url),true);

			// close the connection, release resources used
			curl_close($url);

			return $response;
	}

	function findRoomOld($typeid,$rooms,$session){
		$sqlsearch = "SELECT DISTINCT h_room_name, h_room_id, h_type_id, h_type_price, h_room_status, 
									  h_type_desc, h_type_capacity, h_type_name, h_type_bed_name, h_type_bed, h_type_bed_image, h_statustouser_id 
					  FROM h_room r, h_type t, h_type_bed b, h_status s, h_statustouser u
					  WHERE h_type_id = '$typeid' and h_type_id = h_room_type and h_type_bed = h_type_bed_id and h_status_id = h_room_status and h_statustouser_id = h_status_statustouser and h_room_id IN
					    	(SELECT h_transaction.h_trans_roomid
					     	 FROM h_customer
					         INNER JOIN h_transaction on h_customer.h_customer_id = h_transaction.h_trans_customerid
					         WHERE h_trans_codesession = '$session') AND h_statustouser_id = 1
					  ORDER BY h_room_name
					  LIMIT $rooms";
		$result = mysqli_query($this->db_connection,$sqlsearch);
		return $result;
	}

	function findRoom($typeid,$checkin,$checkout,$rooms){
		$sqlsearch = "SELECT DISTINCT h_room_name, h_room_id, h_type_id, h_type_price, h_room_status, 
		              h_type_desc, h_type_capacity, h_type_name, h_type_bed_name, h_type_bed, h_type_bed_image, h_statustouser_id
      				  FROM h_room r, h_type t, h_type_bed b, h_status s, h_statustouser u
                      WHERE h_type_id = '$typeid' and h_type_id = h_room_type and h_type_bed = h_type_bed_id and h_status_id = h_room_status and h_statustouser_id = h_status_statustouser and h_room_id NOT IN
                            (SELECT h_transaction.h_trans_roomid
                             FROM h_customer
                             INNER JOIN h_transaction on h_customer.h_customer_id = h_transaction.h_trans_customerid
                             WHERE(h_trans_checkindate BETWEEN '$checkin' AND '$checkout' AND h_trans_bill_status NOT IN (2,3,4))
                             OR(h_trans_checkoutdate BETWEEN '$checkin' AND '$checkout' AND h_trans_bill_status NOT IN (2,3,4))
                             OR('$checkin' BETWEEN h_trans_checkindate AND h_trans_checkoutdate AND h_trans_bill_status NOT IN (2,3,4))
                             OR('$checkout' BETWEEN h_trans_checkindate AND h_trans_checkoutdate AND h_trans_bill_status NOT IN (2,3,4))
                            ) AND h_statustouser_id = 1
                      ORDER BY h_room_name
                      LIMIT $rooms";

        $result = mysqli_query($this->db_connection,$sqlsearch);
		return $result;
	}

	function SearchRoom($checkin,$checkout,$guests,$rooms){
		$sqlsearch = "SELECT h_type.h_type_id, h_type.h_type_name, h_type.h_type_desc, h_type.h_type_bed, h_type.h_type_bedtotal, h_room.h_room_id, h_type.h_type_capacity, h_type.h_type_price, h_type_bed.h_type_bed_name, h_type_bed.h_type_bed_image, h_statustouser.h_statustouser_id,
        COUNT(h_room.h_room_type) AS sumOfRoomByType , /*หาจำนวนห้องที่จองได้ของแต่ละชนิด*/
        COUNT(h_room.h_room_type)*h_type.h_type_capacity AS TotalAmount /*หาจำนวนคนที่รองรับได้ทั้งหมด จาก จำนวนห้องที่ว่าง * จำนวนคนที่จุได้ (แยกเป็นแต่ละชนิด)*/
        FROM h_type
        JOIN h_room
        ON h_type.h_type_id = h_room.h_room_type
        JOIN h_type_bed
        ON h_type.h_type_bed = h_type_bed.h_type_bed_id
        JOIN h_status
        ON h_status_id = h_room_status
        JOIN h_statustouser
        ON h_statustouser_id = h_status_statustouser
        WHERE h_room.h_room_id NOT IN (SELECT h_transaction.h_trans_roomid
                                        FROM h_customer
                                        INNER JOIN h_transaction on h_customer.h_customer_id = h_transaction.h_trans_customerid
                                        WHERE(h_trans_checkindate BETWEEN '$checkin' AND '$checkout'AND h_trans_bill_status NOT IN (2,3,4))
                                        OR(h_trans_checkoutdate BETWEEN '$checkin' AND '$checkout' AND h_trans_bill_status NOT IN (2,3,4))
                                        OR('$checkin' BETWEEN h_trans_checkindate AND h_trans_checkoutdate AND h_trans_bill_status NOT IN (2,3,4))
                                        OR('$checkout' BETWEEN h_trans_checkindate AND h_trans_checkoutdate AND h_trans_bill_status NOT IN (2,3,4))
                                       ) AND h_type_statustouser = 1 AND h_statustouser.h_statustouser_id = 1
        GROUP BY h_type.h_type_id
        HAVING TotalAmount >= $guests and h_type.h_type_capacity*$rooms >= $guests
        ORDER BY h_type.h_type_price";

        //$result = mysqli_query($this->db_connection,$sqlsearch);
		return $sqlsearch;
	}

	function UIDProfile($UID){
		$sql = "SELECT * FROM h_user WHERE h_user_id = '$UID'";
		$result = $this->Select_db_one($sql);
		return $result;
	}

	function delete_Image($image_field,$form_table,$where_field,$id){

		$sql_delete_image = "SELECT ".$image_field." FROM ".$form_table." WHERE  ".$where_field." = '".$id."'";
		$result = $this->Select_db($sql_delete_image);
		foreach($result as $read){
			if($read[$image_field] != "../system/upload_icon/404.png"
				&& $read[$image_field] != "../system/upload_profile/404.png"){
				unlink($read[$image_field]);
			}
			
		}
	}

	function pagination_setpage($perpage,$current_page){
		if (isset($current_page)) {
			$page = $current_page;
		} else {
			$page = 1;
		}
		$start = ($page - 1) * $perpage;
		return $start;
	}

}
?>