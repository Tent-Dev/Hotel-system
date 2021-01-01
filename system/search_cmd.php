<?php

session_start();
include("class_db.php");
$mysql = new Main_db;
$mysql->Connect_db(); //เชื่อมต่อdb
$mysql->SetCharacter();

$cmd = isset($_POST["command"]) ? $_POST["command"] : "";
//search roomlist & Typelist
if ($cmd != ""){
	if($cmd == "search_roomlist_2"){
		$perpage = 10;
		$start = $mysql->pagination_setpage($perpage,$_POST['page']);
		$sqltype = "SELECT * FROM h_type";
		$resulttype = $mysql->Select_db($sqltype);

		$sqlstatus = "SELECT * FROM h_status";
		$resultstatus = $mysql->Select_db($sqlstatus);

		$sql = "SELECT * FROM h_room
				JOIN h_status
				ON h_room.h_room_status = h_status.h_status_id
				JOIN h_type
				ON  h_room.h_room_type = h_type.h_type_id
				WHERE h_room_type = h_type_id ";

		$sql_count = "SELECT h_room_id FROM h_room
				JOIN h_status
				ON h_room.h_room_status = h_status.h_status_id
				JOIN h_type
				ON  h_room.h_room_type = h_type.h_type_id
				WHERE h_room_type = h_type_id ";

		if(isset($_POST["capacity"])) {
			$capacityFilterData = implode("','", $_POST["capacity"]);
			$sql .= " AND h_type.h_type_capacity IN('".$capacityFilterData."')";
			$sql_count .= " AND h_type.h_type_capacity IN('".$capacityFilterData."')";
		}
		if(isset($_POST["price"])){
			$priceFilterData = implode("','", $_POST["price"]);
			$sql .= " AND h_type.h_type_price BETWEEN '".($priceFilterData-3000)."' AND '".($priceFilterData)."'" ;
			$sql_count .= " AND h_type.h_type_price BETWEEN '".($priceFilterData-3000)."' AND '".($priceFilterData)."'" ;
		}
		if(isset($_POST["type"])){
			$typeFilterData = implode("','", $_POST["type"]);
			$sql .= " AND h_type.h_type_id IN('".($typeFilterData)."')" ;
			$sql_count .= " AND h_type.h_type_id IN('".($typeFilterData)."')" ;
		}
		if(isset($_POST["status"])){
			$statusFilterData = implode("','", $_POST["status"]);
			$sql .= " AND h_status.h_status_id IN('".($statusFilterData)."')" ;
			$sql_count .= " AND h_status.h_status_id IN('".($statusFilterData)."')" ;
		}
		if(isset($_POST["checkinDb"]) && isset($_POST["checkoutDb"])){
			$checkinFilterData = $_POST["checkinDb"];
			$checkoutFilterData = $_POST["checkoutDb"];
			$sql .= "
			AND h_room.h_room_id NOT IN (SELECT h_transaction.h_trans_roomid
                                        FROM h_customer
                                        INNER JOIN h_transaction on h_customer.h_customer_id = h_transaction.h_trans_customerid
                                        WHERE(h_trans_checkindate BETWEEN '".$checkinFilterData."' AND '".$checkoutFilterData."' AND h_trans_bill_status NOT IN (2,3,4))
                                        OR(h_trans_checkoutdate BETWEEN '".$checkinFilterData."' AND '".$checkoutFilterData."' AND h_trans_bill_status NOT IN (2,3,4))
                                        OR('".$checkinFilterData."' BETWEEN h_trans_checkindate AND h_trans_checkoutdate AND h_trans_bill_status NOT IN (2,3,4))
                                        OR('".$checkoutFilterData."' BETWEEN h_trans_checkindate AND h_trans_checkoutdate AND h_trans_bill_status NOT IN (2,3,4))
                                       )";

            $sql_count .= "
			AND h_room.h_room_id NOT IN (SELECT h_transaction.h_trans_roomid
                                        FROM h_customer
                                        INNER JOIN h_transaction on h_customer.h_customer_id = h_transaction.h_trans_customerid
                                        WHERE(h_trans_checkindate BETWEEN '".$checkinFilterData."' AND '".$checkoutFilterData."' AND h_trans_bill_status NOT IN (2,3,4))
                                        OR(h_trans_checkoutdate BETWEEN '".$checkinFilterData."' AND '".$checkoutFilterData."' AND h_trans_bill_status NOT IN (2,3,4))
                                        OR('".$checkinFilterData."' BETWEEN h_trans_checkindate AND h_trans_checkoutdate AND h_trans_bill_status NOT IN (2,3,4))
                                        OR('".$checkoutFilterData."' BETWEEN h_trans_checkindate AND h_trans_checkoutdate AND h_trans_bill_status NOT IN (2,3,4))
                                       )";
		}
		$sql .= " ORDER By h_type.h_type_price limit {$start} , {$perpage}";
		$sql_count .= " ORDER By h_type.h_type_price";

		$result = $mysql->select_db($sql);
        $rowcount = $mysql->numRows($sql);

        $result_count = $mysql->select_db($sql_count);
        $rowcount_count = $mysql->numRows($sql_count);
        $total_pages = ceil($rowcount_count / $perpage);

		if($rowcount > 0) {
			foreach ($result as $read) {
				$arr[] = array(
						'h_room_id' => $read['h_room_id'],
						'h_room_name' => $read['h_room_name'],
						'h_type_name' => $read['h_type_name'],
						'h_type_price' => $read['h_type_price'],
						'h_type_capacity' => $read['h_type_capacity'],
						'h_status_id' => $read['h_status_id'],
						'h_status_color' => $read['h_status_color'],
						'h_status_name' => $read['h_status_name']
						);
			}
			$arr_count = array(
						'result' => $arr,
						'count' => $rowcount_count,
						'total_page' => $total_pages,
						'perpage' => $perpage
						);

			echo json_encode($arr_count);
			exit();
		
		}else {
			$arr = array('html' => "");
			echo json_encode($arr);
			exit();
		}
	}
	
	if($cmd == "search_room_Info"){
		$sql = "SELECT * FROM h_room 
				JOIN h_type
				ON h_type.h_type_id = h_room.h_room_type
				WHERE h_room.h_room_id = '".$_POST['id']."'";
		$result = $mysql->select_db($sql);
		foreach ($result as $read) {
			$arr[] = array(
					'h_room_id' => $read['h_room_id'],
					'h_room_name' => $read['h_room_name'],
					'h_room_status' => $read['h_room_status'],
					'h_room_type' => $read['h_room_type'],
					'h_type_price'=> $read['h_type_price'],
					'h_type_capacity'=> $read['h_type_capacity'],
					'h_type_name'=> $read['h_type_name']
					);
		}
		echo json_encode($arr);
		exit();
	}

	if($cmd == "search_booklist"){
		$perpage = 4;
		$start = $mysql->pagination_setpage($perpage,$_POST['page']);

		if(isset($_POST['search'])){
			$number = $_POST['search'];
		}

		$sql = "SELECT * FROM h_bill
				JOIN h_customer
				ON h_bill.h_bill_customerid = h_customer.h_customer_id
			    JOIN h_transaction
			    ON h_bill.h_bill_customerid = h_transaction.h_trans_customerid
			    JOIN h_paymenttype
			    ON h_bill.h_bill_paymenttypeid = h_paymenttype.h_paymenttype_id
			    JOIN h_rate
			    ON h_bill.h_bill_rateid = h_rate.h_rate_id
			    JOIN h_bill_status
                ON h_bill.h_bill_status = h_bill_status.h_bill_status_id
                JOIN h_room
                ON h_room.h_room_id = h_transaction.h_trans_roomid
			    GROUP BY h_bill.h_bill_customerid";

		$from_dateFilterData = $_POST["from_date"];
		$to_dateFilterData = $_POST["to_date"];
		$day_select = $_POST["day_select"];

		$LIKE_Name = "(h_bill_customerid LIKE '%$number%' OR h_customer_firstname LIKE '%$number%' 
					  OR h_trans_customerid IN(SELECT h_trans_customerid FROM h_transaction
                      JOIN h_room ON h_room.h_room_id = h_transaction.h_trans_roomid WHERE h_room_name = '$number'))";
        $withoutName = "(h_bill.h_bill_checkin BETWEEN '".$from_dateFilterData."' AND '".$to_dateFilterData."')
						OR (h_bill.h_bill_checkout BETWEEN '".$from_dateFilterData."' AND '".$to_dateFilterData."')
						OR ('".$from_dateFilterData."' BETWEEN h_bill.h_bill_checkin AND h_bill.h_bill_checkout)
						OR ('".$to_dateFilterData."' BETWEEN h_bill.h_bill_checkin AND h_bill.h_bill_checkout)";
		$withoutName_currentdate = "(h_bill.h_bill_checkin BETWEEN (CURRENT_DATE() - $day_select)AND CURRENT_DATE())
								  OR (h_bill.h_bill_checkout BETWEEN (CURRENT_DATE() - $day_select) AND CURRENT_DATE())
								  OR ((CURRENT_DATE() - $day_select) BETWEEN h_bill.h_bill_checkin AND h_bill.h_bill_checkout)
								  OR ((CURRENT_DATE() - $day_select) BETWEEN h_bill.h_bill_checkin AND h_bill.h_bill_checkout)";
		$LIKE_Name_with_date = "(h_trans_customerid IN (SELECT h_trans_customerid FROM h_transaction
                           		JOIN h_room ON h_room.h_room_id = h_transaction.h_trans_roomid
                           		WHERE h_room_name = '$number'
                           		AND ((h_transaction.h_trans_checkindate BETWEEN '".$from_dateFilterData."'
	                                AND'".$to_dateFilterData."')
	                                OR (h_transaction.h_trans_checkoutdate BETWEEN '".$from_dateFilterData."'
	                                AND '".$to_dateFilterData."')
	                                OR ('".$from_dateFilterData."' BETWEEN h_transaction.h_trans_checkindate
	                                AND h_transaction.h_trans_checkoutdate)
	                                OR ('".$to_dateFilterData."' BETWEEN h_transaction.h_trans_checkindate
	                                AND h_transaction.h_trans_checkoutdate))))
                           		OR ((h_bill_customerid LIKE '%$number%' OR h_customer_firstname LIKE '%$number%')
                           	 		AND (((h_bill.h_bill_checkin BETWEEN '".$from_dateFilterData."'
	                           	 	AND '".$to_dateFilterData."')
									OR (h_bill.h_bill_checkout BETWEEN '".$from_dateFilterData."'
									AND '".$to_dateFilterData."')
									OR ('".$from_dateFilterData."' BETWEEN h_bill.h_bill_checkin
									AND h_bill.h_bill_checkout) OR ('".$to_dateFilterData."' BETWEEN h_bill.h_bill_checkin
									AND h_bill.h_bill_checkout))))";
		$LIKE_Name_with_currentdate = "h_trans_customerid IN (SELECT h_trans_customerid FROM h_transaction
                           			   JOIN h_room ON h_room.h_room_id = h_transaction.h_trans_roomid
                           			   WHERE h_room_name = '$number'
                           			   AND ((h_transaction.h_trans_checkindate BETWEEN (CURRENT_DATE() - $day_select)
	                                		AND CURRENT_DATE())
	                                    	OR (h_transaction.h_trans_checkoutdate BETWEEN (CURRENT_DATE() - $day_select)
	                                    	AND CURRENT_DATE())
	                                    	OR ((CURRENT_DATE() - $day_select) BETWEEN h_transaction.h_trans_checkindate
	                                        AND h_transaction.h_trans_checkoutdate)
	                                    	OR ((CURRENT_DATE() - $day_select) BETWEEN h_transaction.h_trans_checkindate
	                                        AND h_transaction.h_trans_checkoutdate)))
	                           		   OR ((h_bill_customerid LIKE '%$number%' OR h_customer_firstname LIKE '%$number%')
	                           	 			AND (((h_bill.h_bill_checkin BETWEEN (CURRENT_DATE() - $day_select)
		                                		 AND CURRENT_DATE())
												 OR (h_bill.h_bill_checkout BETWEEN (CURRENT_DATE() - $day_select)
		                                		 AND CURRENT_DATE())
												 OR ((CURRENT_DATE() - $day_select) BETWEEN h_bill.h_bill_checkin
												 AND h_bill.h_bill_checkout)
												 OR ((CURRENT_DATE() - $day_select) BETWEEN h_bill.h_bill_checkin
												 AND h_bill.h_bill_checkout))))";

		if($number!="" || isset($_POST['approve'])){
			//Option -> ทั่วไป
			if(isset($_POST['approve']) && ($_POST['approve']  >= 1 && $_POST['approve']  <= 4)){
				$sql.= " HAVING h_bill.h_bill_status = '".$_POST['approve']."'";

				if($number!="" && ($_POST["from_date"] == "" && $_POST["to_date"] == "")){
					$sql.= " AND ".$LIKE_Name;
				}
				if($_POST["from_date"] != "" && $_POST["to_date"] != "" && $number == ""){
					$sql.= " AND (".$withoutName.")";
				}
				if($_POST["from_date"] != "" && $_POST["to_date"] != "" && $number != ""){
					$sql.= " AND (".$LIKE_Name_with_date.")";
				}
				if($number != "" && ($_POST["day_select"] !="" && $_POST["day_select"] != "all")){
					$sql.= " AND (".$LIKE_Name_with_currentdate.")";
				}
				if($number == "" && ($_POST["day_select"] !="" && $_POST["day_select"] != "all")){
					$sql.= " AND (".$withoutName_currentdate.")";
				}
			}

			//Option -> ทั้งหมด
			if(isset($_POST['approve']) && $_POST['approve'] == 5){

				if($number!="" && ($_POST["from_date"] == "" && $_POST["to_date"] == "" && $_POST["day_select"] == "")){
					$sql.= " HAVING ".$LIKE_Name;
				}
				if($_POST["from_date"] != "" && $_POST["to_date"] != "" && $number == ""){
					$sql.= " HAVING ".$withoutName;
				}
				if($_POST["from_date"] != "" && $_POST["to_date"] != "" && $number != ""){
					$sql.= " HAVING ".$LIKE_Name_with_date;
				}
				if($number != "" && ($_POST["day_select"] !="" && $_POST["day_select"] != "all")){
					$sql.= " HAVING ".$LIKE_Name_with_currentdate;
				}
				if($number == "" && ($_POST["day_select"] !="" && $_POST["day_select"] != "all")){
					$sql.= " HAVING ".$withoutName_currentdate;
				}
			}

			//Option -> ที่ต้องดำเนินการวันนี้
			if(isset($_POST['approve']) && $_POST['approve'] == 6){
				$sql .= " HAVING h_trans_customerid IN(SELECT h_transaction.h_trans_customerid
	                      FROM h_customer INNER JOIN h_transaction
	                      ON h_customer.h_customer_id = h_transaction.h_trans_customerid
	                      WHERE (h_trans_checkoutdate = CURRENT_DATE() AND h_trans_bill_status = 2 )
	                      OR (h_trans_checkindate = CURRENT_DATE() AND h_trans_bill_status = 1 ))";
	            if($number!=""){
					$sql.= " AND ".$LIKE_Name;
				}
			}
		}
		$sql .= " ORDER By h_bill_id DESC limit {$start} , {$perpage}";
		$resultsql = $mysql->select_db($sql);
		$rowcount = $mysql->numRows($sql);

		$sql_count = "SELECT * FROM h_bill
				JOIN h_customer
				ON h_bill.h_bill_customerid = h_customer.h_customer_id
			    JOIN h_transaction
			    ON h_bill.h_bill_customerid = h_transaction.h_trans_customerid
			    JOIN h_paymenttype
			    ON h_bill.h_bill_paymenttypeid = h_paymenttype.h_paymenttype_id
			    JOIN h_rate
			    ON h_bill.h_bill_rateid = h_rate.h_rate_id
			    JOIN h_bill_status
                ON h_bill.h_bill_status = h_bill_status.h_bill_status_id
                JOIN h_room
                ON h_room.h_room_id = h_transaction.h_trans_roomid
			    GROUP BY h_bill.h_bill_customerid";

		if($number!="" || isset($_POST['approve'])){
			//Option -> ทั่วไป
			if(isset($_POST['approve']) && ($_POST['approve']  >= 1 && $_POST['approve']  <= 4)){
				$sql_count.= " HAVING h_bill.h_bill_status = '".$_POST['approve']."'";

				if($number!="" && ($_POST["from_date"] == "" && $_POST["to_date"] == "")){
					$sql_count.= " AND ".$LIKE_Name;
				}
				if($_POST["from_date"] != "" && $_POST["to_date"] != "" && $number == ""){
					$sql_count.= " AND (".$withoutName.")";
				}
				if($_POST["from_date"] != "" && $_POST["to_date"] != "" && $number != ""){
					$sql_count.= " AND (".$LIKE_Name_with_date.")";
				}
				if($number != "" && ($_POST["day_select"] !="" && $_POST["day_select"] != "all")){
					$sql_count.= " AND (".$LIKE_Name_with_currentdate.")";
				}
				if($number == "" && ($_POST["day_select"] !="" && $_POST["day_select"] != "all")){
					$sql_count.= " AND (".$withoutName_currentdate.")";
				}
			}

			//Option -> ทั้งหมด
			if(isset($_POST['approve']) && $_POST['approve'] == 5){

				if($number!="" && ($_POST["from_date"] == "" && $_POST["to_date"] == "" && $_POST["day_select"] == "")){
					$sql_count.= " HAVING ".$LIKE_Name;
				}
				if($_POST["from_date"] != "" && $_POST["to_date"] != "" && $number == ""){
					$sql_count.= " HAVING ".$withoutName;
				}
				if($_POST["from_date"] != "" && $_POST["to_date"] != "" && $number != ""){
					$sql_count.= " HAVING ".$LIKE_Name_with_date;
				}
				if($number != "" && ($_POST["day_select"] !="" && $_POST["day_select"] != "all")){
					$sql_count.= " HAVING ".$LIKE_Name_with_currentdate;
				}
				if($number == "" && ($_POST["day_select"] !="" && $_POST["day_select"] != "all")){
					$sql_count.= " HAVING ".$withoutName_currentdate;
				}
			}

			//Option -> ที่ต้องดำเนินการวันนี้
			if(isset($_POST['approve']) && $_POST['approve'] == 6){
				$sql_count .= " HAVING h_trans_customerid IN(SELECT h_transaction.h_trans_customerid
	                      FROM h_customer INNER JOIN h_transaction
	                      ON h_customer.h_customer_id = h_transaction.h_trans_customerid
	                      WHERE (h_trans_checkoutdate = CURRENT_DATE() AND h_trans_bill_status = 2 )
	                      OR (h_trans_checkindate = CURRENT_DATE() AND h_trans_bill_status = 1 ))";
	            if($number!=""){
					$sql_count.= " AND ".$LIKE_Name;
				}
			}
		}

		$resultsql_count = $mysql->select_db($sql_count);
		$rowcount_count = $mysql->numRows($sql_count);
		$total_pages = ceil($rowcount_count / $perpage);
		if($rowcount >=1){
			foreach ($resultsql as $readsql){

				$sqltype = "SELECT h_bill.h_bill_customerid, h_type.h_type_name,h_room.h_room_name
							FROM h_transaction
							JOIN h_room
							ON h_transaction.h_trans_roomid = h_room.h_room_id
							JOIN h_type
							ON h_room.h_room_type = h_type.h_type_id
							JOIN h_bill
							ON h_bill.h_bill_customerid = h_transaction.h_trans_customerid
							WHERE h_bill.h_bill_customerid = '".$readsql['h_bill_customerid']."'"; 
				$resultsqltype = $mysql->select_db($sqltype);
				foreach ($resultsqltype as $readtype){
						$arrtype[] = $readtype['h_type_name'];
						$arrroom[] = $readtype['h_room_name'];
				};

				$arrtype = array_count_values($arrtype);

				$arr[] = array(
					'h_bill_customerid' => $readsql['h_bill_customerid'],
					'h_bill_session' => $readsql['h_bill_session'],
					'h_bill_rooms' => $readsql['h_bill_rooms'],
					'h_bill_guests' => $readsql['h_bill_guests'],
					'h_bill_special' => $readsql['h_bill_special'],
					'h_bill_price' => $readsql['h_bill_price'],
					'h_bill_status' => $readsql['h_bill_status'],
					'h_bill_status_name' => $readsql['h_bill_status_name'],
					'h_bill_checkin' => $readsql['h_bill_checkin'],
					'h_bill_checkout' => $readsql['h_bill_checkout'],
					'h_bill_status' => $readsql['h_bill_status'],
					'h_rate_name' => $readsql['h_rate_name'],
					'h_rate_id' => $readsql['h_rate_id'],
					'h_paymenttype_type' => $readsql['h_paymenttype_type'],
					'h_customer_firstname' => $readsql['h_customer_firstname'],
					'h_customer_lastname' => $readsql['h_customer_lastname'],
					'h_customer_email' => $readsql['h_customer_email'],
					'h_customer_phone' => $readsql['h_customer_phone'],
					'h_customer_region' => $readsql['h_customer_region'],
					'h_customer_address' => $readsql['h_customer_address'],
					'h_customer_regioncode' => $readsql['h_customer_regioncode'],
					'h_customer_postal' => $readsql['h_customer_postal'],
					'h_customer_city' => $readsql['h_customer_city'],
					'type' => $arrtype,
					'room' => $arrroom
					);
					//reset array to empty
					unset($arrtype);
					unset($arrroom);
	  		}
	  		$arr_count = array(
						'result' => $arr,
						'count' => $rowcount_count,
						'total_page' => $total_pages,
						'perpage' => $perpage
						);

			echo json_encode($arr_count);
			exit();
		}else{
			$arr = array('html' => "", );
			echo json_encode($arr);
			exit();
		}
	}

	if($cmd == "search_booklist_Type"){

		$sqltype = "SELECT h_bill.h_bill_customerid,h_bill.h_bill_rooms, h_type.h_type_name,h_room.h_room_name,h_type.h_type_price
					FROM h_transaction
					JOIN h_room
					ON h_transaction.h_trans_roomid = h_room.h_room_id
					JOIN h_type
					ON h_room.h_room_type = h_type.h_type_id
					JOIN h_bill
					ON h_bill.h_bill_customerid = h_transaction.h_trans_customerid
					WHERE h_bill.h_bill_customerid = '".$_POST['cusId']."'"; 
		$resultsqltype = $mysql->select_db($sqltype);

		$sqlrate = "SELECT * FROM h_rate
					WHERE h_rate_id = '".$_POST['rateid']."'"; 
		$resultsqlrate = $mysql->select_db($sqlrate);
		foreach ($resultsqlrate as $readrate);

		foreach ($resultsqltype as $readtype){
			$arrtype[] = array(	
				'Type' => $readtype['h_type_name'],
				'Price' => $readtype['h_type_price'],
				'h_rate_name' => $readrate['h_rate_name'],
				'h_rate_discountset' => $readrate['h_rate_discountset'],
				'h_rate_discount' => $readrate['h_rate_discount'],
				'h_bill_rooms' => $readtype['h_bill_rooms']
			);
		};

		//$arrtype = array_count_values($arrtype);

		$arr[] = array(	'type' => $arrtype );
		//reset array to empty
		//unset($arrtype);
	  	echo json_encode($arrtype);
		exit();
	}

	if($cmd == "search_booklist_Info"){
		$sql = "SELECT * FROM h_customer WHERE h_customer_id = '".$_POST['cusid']."'";
		$result = $mysql->Select_db($sql);
		foreach ($result as $read);
		$arr[] = array(
				'h_customer_codesession' => $read['h_customer_codesession'],
				'h_customer_firstname' => $read['h_customer_firstname'],
				'h_customer_lastname' => $read['h_customer_lastname'],
				'h_customer_email' => $read['h_customer_email'],
				'h_customer_regioncode' => $read['h_customer_regioncode'],
				'h_customer_phone' => $read['h_customer_phone'],
				'h_customer_region' => $read['h_customer_region'],
				'h_customer_address' => $read['h_customer_address'],
				'h_customer_city' => $read['h_customer_city'],
				'h_customer_postal' => $read['h_customer_postal']
				);

		echo json_encode($arr);
		exit();
	}

	if($cmd == "search_booklist_today"){

		$sql = "SELECT h_type_id, h_type_name, COUNT(h_type_name) AS summary FROM h_type
				JOIN h_room
				ON h_room.h_room_type = h_type.h_type_id
				GROUP BY h_type_id";

		$result = $mysql->Select_db($sql);

		foreach ($result as $read) {

			$sql_total_empty = "SELECT h_type_name, COUNT(h_type_name) AS total FROM h_type
								JOIN h_room
								ON h_room.h_room_type = h_type.h_type_id
								JOIN h_status
						        ON h_status_id = h_room_status
						        JOIN h_statustouser
						        ON h_statustouser.h_statustouser_id = h_status.h_status_statustouser
				                WHERE h_room.h_room_id NOT IN
				                		( SELECT h_transaction.h_trans_roomid
				                          FROM h_customer
				                          INNER JOIN h_transaction
				                          ON h_customer.h_customer_id = h_transaction.h_trans_customerid
				                          WHERE(h_trans_checkindate BETWEEN CURRENT_DATE()
				                          		AND CURRENT_DATE()+1
				                          		AND h_trans_bill_status NOT IN (3,4)
				                          	   )
				                               OR(h_trans_checkoutdate BETWEEN CURRENT_DATE()
				                               	  AND CURRENT_DATE()+1
				                               	  AND h_trans_bill_status NOT IN (3,4)
				                               )
				                               OR(CURRENT_DATE() BETWEEN h_trans_checkindate
				                               	  AND h_trans_checkoutdate
				                               	  AND h_trans_bill_status NOT IN (3,4)
				                               )
				                               OR(CURRENT_DATE()+1 BETWEEN h_trans_checkindate
				                                  AND h_trans_checkoutdate
				                                  AND h_trans_bill_status NOT IN (3,4)
				                               )
				                        )
				                AND h_type.h_type_id = '".$read['h_type_id']."'
				                AND h_statustouser.h_statustouser_id = 1
				                GROUP BY h_type_id";

       		$result_total_empty = $mysql->Select_db($sql_total_empty);
			foreach ($result_total_empty as $read_total_empty);

			$sql_total_session = "SELECT h_type_name, COUNT(h_type_name) AS total
								  FROM h_transaction
								  JOIN h_room
								  ON h_room.h_room_id = h_transaction.h_trans_roomid
								  JOIN h_type
								  ON h_type.h_type_id = h_room.h_room_type
								  WHERE h_room.h_room_id IN ( SELECT h_transaction.h_trans_roomid
					                                          FROM h_customer
					                                          INNER JOIN h_transaction
					                                          ON h_customer.h_customer_id = h_transaction.h_trans_customerid
					                                          WHERE h_trans_checkindate = CURRENT_DATE()
					                                          		AND CHAR_LENGTH(h_transaction.h_trans_codesession) > 0
					                                               	AND h_trans_bill_status = 1
					                                         )
					               AND h_trans_bill_status = 1
					               AND h_trans_checkindate = CURRENT_DATE()
					               AND h_type.h_type_id = '".$read['h_type_id']."'";
			$result_session = $mysql->Select_db($sql_total_session);
			foreach ($result_session as $read_session);

			$sql_checkout_today = "SELECT h_type_name, COUNT(h_type_name) AS checkout FROM h_type
								   JOIN h_room
								   ON h_room.h_room_type = h_type.h_type_id
				                   WHERE h_room.h_room_id IN ( SELECT h_transaction.h_trans_roomid
				                                               FROM h_customer
				                                               INNER JOIN h_transaction
				                                               ON h_customer.h_customer_id = h_transaction.h_trans_customerid
				                                               WHERE h_trans_checkoutdate = CURRENT_DATE()
				                                               		 AND h_trans_bill_status = 2
				                                               		 AND h_type.h_type_id = '".$read['h_type_id']."'
				                                             )    
				                   GROUP BY h_type_id";
			$result_checkout_today = $mysql->Select_db($sql_checkout_today);
			foreach ($result_checkout_today as $read_checkout_today);

			// $sql_summary = "SELECT h_type_id, h_type_name, COUNT(h_type_name) AS summary FROM h_type
			// 	JOIN h_room
			// 	ON h_room.h_room_type = h_type.h_type_id
			// 	WHERE h_room.h_room_status = 1
			// 	GROUP BY h_type_id";
			// $result_summary = $mysql->Select_db($sql_summary);
			// foreach ($result_summary as $read_summary);

			if($read_checkout_today['checkout'] == null){
				$read_checkout_today['checkout'] = "ไม่มี";
			}
			if($read_total_empty['h_type_name'] != $read['h_type_name']){
				$read_total_empty['total'] = 0;
			}
			if($read_checkout_today['h_type_name'] != $read['h_type_name']){
				$read_checkout_today['checkout'] = "ไม่มี";
			}
			// if($read_summary['h_type_name'] != $read['h_type_name']){
			// 	$read_summary['total'] = 0;
			// }

			$arr[] = array(
					'h_type_name' => $read['h_type_name'],
					'summary_of_type' => $read_summary['summary'],
					'summary_of_empty' => $read_total_empty['total'],
					'summary_of_checkout' => $read_checkout_today['checkout'],
					'session' => $read_session['total'],
					);
		}
		echo json_encode($arr);
		exit();
	}

	if($cmd == "receipt_history"){
		$sql = "SELECT h_bill_copy FROM h_bill WHERE h_bill_customerid = '".$_POST['id']."'";
		$result = $mysql->select_db($sql);
		foreach ($result as $read) {
			echo $read['h_bill_copy'];
			exit();
		}
	}

	if($cmd == "search_typelist"){
		$perpage = 2;
		$start = $mysql->pagination_setpage($perpage,$_POST['page']);

		$sql = "SELECT * FROM h_type
				JOIN h_type_bed
				ON h_type_bed.h_type_bed_id = h_type_bed
				WHERE h_type.h_type_id != 0";

		if(isset($_POST["capacity"])) {
			$capacityFilterData = implode("','", $_POST["capacity"]);
			$sql .= "
			AND h_type_capacity IN('".$capacityFilterData."')";
		}
		if(isset($_POST["bed"])){
			$bedFilterData = implode("','", $_POST["bed"]);
			$sql .= "
			AND h_type_bed IN('".$bedFilterData."')";
		}
		if(isset($_POST["statustouser"])) {
			if($_POST["statustouser"] == 0){
				$sql .= "";
			}
			else if($_POST["statustouser"] == 1){
				$sql .= " AND h_type_statustouser = 1";
			}
			else if($_POST["statustouser"] == 2){
				$sql .= " AND h_type_statustouser = 2";
			}
		}

		$sql .= " ORDER By h_type_price limit {$start} , {$perpage}";
		$result = $mysql->select_db($sql);
        $rowcount = $mysql->numRows($sql);

        $sql_count = "SELECT h_type_id FROM h_type
				JOIN h_type_bed
				ON h_type_bed.h_type_bed_id = h_type_bed
				WHERE h_type.h_type_id != 0";

		if(isset($_POST["capacity"])) {
			$capacityFilterData = implode("','", $_POST["capacity"]);
			$sql_count .= "
			AND h_type_capacity IN('".$capacityFilterData."')";
		}
		if(isset($_POST["bed"])){
			$bedFilterData = implode("','", $_POST["bed"]);
			$sql_count .= "
			AND h_type_bed IN('".$bedFilterData."')";
		}
		if(isset($_POST["statustouser"])) {
			if($_POST["statustouser"] == 0){
				$sql_count .= "";
			}
			else if($_POST["statustouser"] == 1){
				$sql_count .= " AND h_type_statustouser = 1";
			}
			else if($_POST["statustouser"] == 2){
				$sql_count .= " AND h_type_statustouser = 2";
			}
		}

		$sql_count .= " ORDER By h_type_price";
		$result_count = $mysql->select_db($sql_count);
        $rowcount_count = $mysql->numRows($sql_count);
        $total_pages = ceil($rowcount_count / $perpage);

		if($rowcount > 0) {
			foreach ($result as $read) {
				$sqlimage = "SELECT * FROM h_gallery
						  	 WHERE h_gallery_roomtypeid = '".$read['h_type_id']."'"; 
				$resultsqlimage = $mysql->select_db($sqlimage);
				foreach ($resultsqlimage as $readsqlimage){
					if($readsqlimage['h_gallery_cover'] == 0){
						$arrimage[] = array('image' => $readsqlimage['h_gallery_filename']);
					}
					if($readsqlimage['h_gallery_cover'] == 1){
						$arrcover[] = array('image' => $readsqlimage['h_gallery_filename']);
					}
				};

				$arr[] = array(
						'h_type_id' => $read['h_type_id'],
						'h_type_name' => $read['h_type_name'],
						'h_type_desc' => $read['h_type_desc'],
						'h_type_price' => $read['h_type_price'],
						'h_type_bed_id' => $read['h_type_bed'],
						'h_type_bed' => $read['h_type_bed_name'],
						'h_type_bedtotal' => $read['h_type_bedtotal'],
						'h_type_capacity' => $read['h_type_capacity'],
						'h_type_statustouser' => $read['h_type_statustouser'],
						'imagegroup' => $arrimage,
						'imagecover' => $arrcover
						);
				//reset array to empty
				unset($arrimage);
				unset($arrcover);
				
				
			}
			$arr_count = array(
						'result' => $arr,
						'count' => $rowcount_count,
						'total_page' => $total_pages,
						'perpage' => $perpage
						);

			echo json_encode($arr_count);
			exit();
		
		}else {
			$arr = array('html' => "<h5>No room found</h5>");
			echo json_encode($arr);
			exit();
		}
	}

	if($cmd == "search_ratelist"){
		$perpage = 2;
		$start = $mysql->pagination_setpage($perpage,$_POST['page']);
		$sql = "SELECT * FROM h_rate
				WHERE h_rate_id != 0";

		if(isset($_POST["discount"])) {
			if($_POST["discount"] == 0){
				$sql .= " AND h_rate_discount = 0";
			}
			else if($_POST["discount"] == 1){
				$sql .= " AND h_rate_discount > 0";
			}
			else if($_POST["discount"] == 2){
				$sql .= "";
			}
		}
		if(isset($_POST["deposit"])) {
			if($_POST["deposit"] == 0){
				$sql .= " AND h_rate_deposit = 0";
			}
			else if($_POST["deposit"] == 1){
				$sql .= " AND h_rate_deposit = 1";
			}
			else if($_POST["deposit"] == 2){
				$sql .= "";
			}
		}
		if(isset($_POST["statustouser"])) {
			if($_POST["statustouser"] == 0){
				$sql .= "";
			}
			else if($_POST["statustouser"] == 1){
				$sql .= " AND h_rate_statustouser = 1";
			}
			else if($_POST["statustouser"] == 2){
				$sql .= " AND h_rate_statustouser = 2";
			}
		}

		$sql .= " ORDER By h_rate_id limit {$start} , {$perpage}";
		$result = $mysql->select_db($sql);
        $rowcount = $mysql->numRows($sql);

        $sql_count = "SELECT h_rate_id FROM h_rate
				WHERE h_rate_id != 0";

		if(isset($_POST["discount"])) {
			if($_POST["discount"] == 0){
				$sql_count .= " AND h_rate_discount = 0";
			}
			else if($_POST["discount"] == 1){
				$sql_count .= " AND h_rate_discount > 0";
			}
			else if($_POST["discount"] == 2){
				$sql_count .= "";
			}
		}
		if(isset($_POST["deposit"])) {
			if($_POST["deposit"] == 0){
				$sql_count .= " AND h_rate_deposit = 0";
			}
			else if($_POST["deposit"] == 1){
				$sql_count .= " AND h_rate_deposit = 1";
			}
			else if($_POST["deposit"] == 2){
				$sql_count .= "";
			}
		}
		if(isset($_POST["statustouser"])) {
			if($_POST["statustouser"] == 0){
				$sql_count .= "";
			}
			else if($_POST["statustouser"] == 1){
				$sql_count .= " AND h_rate_statustouser = 1";
			}
			else if($_POST["statustouser"] == 2){
				$sql_count .= " AND h_rate_statustouser = 2";
			}
		}

		$sql_count .= " ORDER By h_rate_id";
		$result_count = $mysql->select_db($sql_count);
        $rowcount_count = $mysql->numRows($sql_count);
        $total_pages = ceil($rowcount_count / $perpage);

		if($rowcount > 0) {
			foreach ($result as $read) {

				$sqltype = "SELECT DISTINCT h_type_name FROM h_mix_ratetype
							JOIN h_type
							ON h_type.h_type_id = h_mix_ratetype.h_mix_ratetype_typeid
							WHERE h_mix_ratetype_rateid = '".$read['h_rate_id']."'";
				$resulttype = $mysql->select_db($sqltype);
				foreach ($resulttype as $readtype) {
					$arrtype[] = array('type' => $readtype['h_type_name']);
				}

				$arr[] = array(
						'h_rate_id' => $read['h_rate_id'],
						'h_rate_name' => $read['h_rate_name'],
						'h_rate_desc' => $read['h_rate_desc'],
						'h_rate_discountset' => $read['h_rate_discountset'],
						'h_rate_discount' => $read['h_rate_discount'],
						'h_rate_statustouser' => $read['h_rate_statustouser'],
						'h_rate_deposit' => $read['h_rate_deposit'],
						'h_rate_dateset' => $read['h_rate_dateset'],
						'h_rate_datestart' => date('d/M/Y', strtotime($read['h_rate_datestart'])),
						'h_rate_dateend' =>date('d/M/Y', strtotime($read['h_rate_dateend'])),
						'type' => $arrtype
					);

				unset($arrtype);
			} 

			$arr_count = array(
						'result' => $arr,
						'count' => $rowcount_count,
						'total_page' => $total_pages,
						'perpage' => $perpage
						);

			echo json_encode($arr_count);
			exit();
		
		}else {
			$arr = array('html' => "<h5>No room found</h5>");
			echo json_encode($arr);
			exit();
		}
	}

	if ($cmd == "search_account") {
		$perpage = 10;
		$start = $mysql->pagination_setpage($perpage,$_POST['page']);
		$sql = "SELECT * FROM h_user
				JOIN h_userrank
				ON h_userrank.h_userrank_id = h_user.h_user_rank WHERE h_user.h_user_id > 0";

		if($_POST['name'] != ""){
			$name = $_POST['name'];
			$sql.= " AND (h_user.h_user_firstname LIKE '%$name%' OR h_user.h_user_lastname LIKE '%$name%'
				   OR h_user.h_user_username LIKE '%$name%')";
		}
		if(isset($_POST['status'])){
			$account_rank = implode("','", $_POST["status"]);
			$sql.= " AND h_user.h_user_rank IN ('".$account_rank."')";
		}
		$sql .= " ORDER By h_user_id limit {$start} , {$perpage}";
		$result = $mysql->Select_db($sql);
		$rowcount = $mysql->numRows($sql);

		$sql_count = "SELECT h_user_id FROM h_user
				JOIN h_userrank
				ON h_userrank.h_userrank_id = h_user.h_user_rank WHERE h_user.h_user_id > 0";

		if($_POST['name'] != ""){
			$name = $_POST['name'];
			$sql_count.= " AND (h_user.h_user_firstname LIKE '%$name%' OR h_user.h_user_lastname LIKE '%$name%'
				   OR h_user.h_user_username LIKE '%$name%')";
		}
		if(isset($_POST['status'])){
			$account_rank = implode("','", $_POST["status"]);
			$sql_count.= " AND h_user.h_user_rank IN ('".$account_rank."')";
		}
		$result_count = $mysql->Select_db($sql_count);
		$rowcount_count = $mysql->numRows($sql_count);
		$total_pages = ceil($rowcount_count / $perpage);

		if($rowcount >0){
			foreach ($result as $read) {
				$arr[] = array(
						'h_user_id' => $read['h_user_id'],
						'h_user_image' => $read['h_user_image'],
						'h_user_username' => $read['h_user_username'],
						'h_user_firstname' => $read['h_user_firstname'],
						'h_user_lastname' => $read['h_user_lastname'],
						'h_user_rank' => $read['h_userrank_name'],
						'h_user_rankid' => $read['h_userrank_id']
						);
			}
			$arr_count = array(
						'result' => $arr,
						'count' => $rowcount_count,
						'total_page' => $total_pages,
						'perpage' => $perpage
						);

			echo json_encode($arr_count);
			exit();
		
		}else {
			$arr = array('html' => "");
			echo json_encode($arr);
			exit();
		}
	}

	if ($cmd == "search_account_Info") {
		$sql = "SELECT * FROM h_user
				JOIN h_userrank
				ON h_userrank.h_userrank_id = h_user.h_user_rank
				WHERE h_user.h_user_id = '".$_POST['id']."'";
		$result = $mysql->Select_db($sql);
		foreach ($result as $read) {
			$arr[] = array(
					'h_user_id' => $read['h_user_id'],
					'h_user_username' => $read['h_user_username'],
					'h_user_firstname' => $read['h_user_firstname'],
					'h_user_lastname' => $read['h_user_lastname'],
					'h_user_rank' => $read['h_userrank_name'],
					'h_user_rankid' => $read['h_userrank_id']
					);
		}
		echo json_encode($arr);
		exit();
	}

	if ($cmd == "search_rank") {
		$perpage = 10;
		$start = $mysql->pagination_setpage($perpage,$_POST['page']);
		$sql = "SELECT * FROM h_userrank WHERE h_userrank_id > 0";

		if($_POST['name'] != ""){
			$name = $_POST['name'];
			$sql.= " AND h_userrank_name LIKE '%$name%' ";
		}
		if(isset($_POST['permission']) && $_POST['permission'] != 3){
				$sql.= " AND h_userrank_permission = '".$_POST['permission']."'";
		}
		$sql .= " ORDER By h_userrank_id limit {$start} , {$perpage}";
		$result = $mysql->Select_db($sql);
		$rowcount = $mysql->numRows($sql);

		$sql_count = "SELECT h_userrank_id FROM h_userrank WHERE h_userrank_id > 0";

		if($_POST['name'] != ""){
			$name = $_POST['name'];
			$sql_count.= " AND h_userrank_name LIKE '%$name%' ";
		}
		if(isset($_POST['permission']) && $_POST['permission'] != 3){
				$sql_count.= " AND h_userrank_permission = '".$_POST['permission']."'";
		}
		$result_count = $mysql->Select_db($sql_count);
		$rowcount_count = $mysql->numRows($sql_count);
		$total_pages = ceil($rowcount_count / $perpage);

		if($rowcount >=1){
			foreach ($result as $read) {
				$arr[] = array(
						'h_userrank_id' => $read['h_userrank_id'],
						'h_userrank_name' => $read['h_userrank_name'],
						'h_userrank_permission' => $read['h_userrank_permission']
						);
			}
			$arr_count = array(
							'result' => $arr,
							'count' => $rowcount_count,
							'total_page' => $total_pages,
							'perpage' => $perpage
							);
			echo json_encode($arr_count);
        }
        else{
        	echo json_encode($arr = array('html' => ''));
        }
		exit();
	}

	if ($cmd == "search_rank_Info") {
		$sql = "SELECT * FROM h_userrank WHERE h_userrank_id = '".$_POST['id']."'";
		$result = $mysql->Select_db($sql);
		foreach ($result as $read) {
			$arr[] = array(
					'h_userrank_id' => $read['h_userrank_id'],
					'h_userrank_name' => $read['h_userrank_name'],
					'h_userrank_permission' => $read['h_userrank_permission']
					);
		}
		echo json_encode($arr);
		exit();
	}

	if($cmd == "search_bed"){
		$perpage = 10;
		$start = $mysql->pagination_setpage($perpage,$_POST['page']);
		$sql = "SELECT * FROM h_type_bed WHERE h_type_bed_id > 0";

		if($_POST['name'] != ""){
			$name = $_POST['name'];
			$sql.= " AND (h_type_bed_name LIKE '%$name%')";
		}
		$sql .= " ORDER By h_type_bed_id limit {$start} , {$perpage}";
		$result = $mysql->Select_db($sql);
		$rowcount = $mysql->numRows($sql);

		$sql_count = "SELECT h_type_bed_id FROM h_type_bed WHERE h_type_bed_id > 0";

		if($_POST['name'] != ""){
			$name = $_POST['name'];
			$sql_count.= " AND (h_type_bed_name LIKE '%$name%')";
		}

		$result_count = $mysql->Select_db($sql_count);
		$rowcount_count = $mysql->numRows($sql_count);
        $total_pages = ceil($rowcount_count / $perpage);

        if($rowcount >0){
        	foreach ($result as $read) {
				$arr[] = array(
							'h_type_bed_id' => $read['h_type_bed_id'],
							'h_type_bed_name' => $read['h_type_bed_name'],
							'h_type_bed_desc' => $read['h_type_bed_desc'],
							'h_type_bed_image' => $read['h_type_bed_image']
							);
				}
			$arr_count = array(
							'result' => $arr,
							'count' => $rowcount_count,
							'total_page' => $total_pages,
							'perpage' => $perpage
							);
			echo json_encode($arr_count);
        }
        else{
        	echo json_encode($arr = array('html' => ''));
        }
		
		exit();
	}

	if ($cmd == "search_bed_Info") {
		$sql = "SELECT * FROM h_type_bed WHERE h_type_bed_id = '".$_POST['id']."'";
		$result = $mysql->Select_db($sql);
		foreach ($result as $read) {
			$arr[] = array(
					'h_type_bed_id' => $read['h_type_bed_id'],
					'h_type_bed_name' => $read['h_type_bed_name'],
					'h_type_bed_desc' => $read['h_type_bed_desc'],
					'h_type_bed_image' => $read['h_type_bed_image']
					);
		}
		echo json_encode($arr);
		exit();
	}

	if($cmd == "search_status"){
		$perpage = 10;
		$start = $mysql->pagination_setpage($perpage,$_POST['page']);
		$sql = "SELECT * FROM h_status JOIN h_statustouser ON h_statustouser_id = h_status_statustouser WHERE h_status_id > 0";

		if($_POST['name'] != ""){
			$name = $_POST['name'];
			$sql.= " AND (h_status_name LIKE '%$name%')";
		}
		if($_POST['status_touser'] != "" && $_POST['status_touser'] != 3){
			$sql.= " AND h_status_statustouser = '	".$_POST['status_touser']."'";
		}
		$sql .= " ORDER By h_status_id limit {$start} , {$perpage}";
		$result = $mysql->Select_db($sql);
		$rowcount = $mysql->numRows($sql);

		$sql_count = "SELECT h_status_id FROM h_status JOIN h_statustouser ON h_statustouser_id = h_status_statustouser WHERE h_status_id > 0";

		if($_POST['name'] != ""){
			$name = $_POST['name'];
			$sql_count.= " AND (h_status_name LIKE '%$name%')";
		}
		if($_POST['status_touser'] != "" && $_POST['status_touser'] != 3){
			$sql_count.= " AND h_status_statustouser = '	".$_POST['status_touser']."'";
		}
		$result_count = $mysql->Select_db($sql_count);
		$rowcount_count = $mysql->numRows($sql_count);
		$total_pages = ceil($rowcount_count / $perpage);

		if($rowcount >0){
			foreach ($result as $read) {
				$arr[] = array(
						'h_status_id' => $read['h_status_id'],
						'h_status_color' => $read['h_status_color'],
						'h_status_name' => $read['h_status_name'],
						'h_status_statustouser' => $read['h_status_statustouser'],
						'h_statustouser_name' => $read['h_statustouser_name']
						);
			}
			$arr_count = array(
							'result' => $arr,
							'count' => $rowcount_count,
							'total_page' => $total_pages,
							'perpage' => $perpage
							);
			echo json_encode($arr_count);
        }
        else{
        	echo json_encode($arr = array('html' => ''));
        }
		exit();
	}

	if ($cmd == "search_status_Info") {
		$sql = "SELECT * FROM h_status WHERE h_status_id = '".$_POST['id']."'";
		$result = $mysql->Select_db($sql);
		foreach ($result as $read) {
			$arr[] = array(
					'h_status_id' => $read['h_status_id'],
					'h_status_color' => $read['h_status_color'],
					'h_status_name' => $read['h_status_name'],
					'h_status_statustouser' => $read['h_status_statustouser']
					);
		}
		echo json_encode($arr);
		exit();
	}

	if ($cmd == "total_of_month_chart"){
		$month = date('m');
		$year = date('Y');
		$year_select = $year;
		$count = 0;
		if(isset($_POST['year_select'])){
			$year_select = $_POST['year_select'];
		}

		for($i = 1; $i <= $month; $i++){
			//Get all
			$sql = "SELECT COUNT(DISTINCT h_bill_id) AS Total_Of_Month FROM h_bill
					JOIN h_transaction ON h_bill_customerid = h_trans_customerid  
					WHERE MONTH(h_trans_session) = ".$i."
					AND YEAR(h_trans_session) = '".$year_select."' ";
			$result = $mysql->Select_db_one($sql);
			$count = $result['Total_Of_Month'] + $count;

			//Get cancel
			$sql_cancel = "SELECT COUNT(DISTINCT h_bill_id) AS Total_Of_cancel FROM h_bill
					JOIN h_transaction ON h_bill_customerid = h_trans_customerid  
					WHERE h_trans_bill_status = 4 AND MONTH(h_trans_session) = ".$i."
					AND YEAR(h_trans_session) = '".$year_select."' ";
			$result_cancel = $mysql->Select_db_one($sql_cancel);
			$count_cancel = $result_cancel['Total_Of_cancel'] + $count_cancel;

			$arr[] = $result['Total_Of_Month'];
			$arr_cancel[] = $result_cancel['Total_Of_cancel'];
		}

		$j_arr = array(
					'total_booking' => $arr,
					'total_cancel' => $arr_cancel,
					'count_all' =>  $count,
					'count_cancel' =>  $count_cancel
				);
		echo json_encode($j_arr);
		exit();
	}

	if ($cmd == "total_of_Typeroom_chart"){
		$year = date('Y');
		$year_select = $year;
		if(isset($_POST['year_select'])){
			$year_select = $_POST['year_select'];
		}

		//Get All Type name
		$sql = "SELECT h_type_name FROM h_transaction
				JOIN h_room ON h_trans_roomid = h_room_id
				JOIN h_type ON h_room_type = h_type_id
				WHERE YEAR(h_trans_session) = '".$year_select."'
				GROUP BY h_type_id";
		$result = $mysql->Select_db($sql);
		foreach ($result as $read) {
			$arr[] = $read['h_type_name'];
		}

		//Get Total book of Type name
		$sql = "SELECT COUNT(h_room_id) AS Total_of_type FROM h_transaction
				JOIN h_room ON h_trans_roomid = h_room_id
				JOIN h_type ON h_room_type = h_type_id
				WHERE YEAR(h_trans_session) = '".$year_select."'
				GROUP BY h_type_id";
		$result = $mysql->Select_db($sql);
		foreach ($result as $read) {
			$arrTotal[] = $read['Total_of_type'];
		}


		$j_arr = array('type_name' => $arr, 'total_of_type' =>  $arrTotal);
		echo json_encode($j_arr);
		exit();
	}

	if($cmd == "timerate_chart"){
		$year = date('Y');
		$year_select = $year;
		if(isset($_POST['year_select'])){
			$year_select = $_POST['year_select'];
		}
		//Get Hours different (average)
		for($i=0;$i<=23;$i++){
			$sql = "SELECT DISTINCT h_trans_customerid, h_trans_session
					FROM h_transaction WHERE HOUR(h_trans_session) = ".$i."
					AND YEAR(h_trans_session) = '".$year_select."'
					ORDER BY HOUR(h_trans_session)";
			$result = $mysql->Select_db($sql);

			foreach ($result as $read) {

				$dt = new DateTime($read['h_trans_session']);
				$arr_preTotal[] = $dt->format('H:i:s');

			}
			if($arr_preTotal !== null){
				$arrTotal[] = date('H:i', array_sum(array_map('strtotime', $arr_preTotal)) / count($arr_preTotal));
			}
			unset($arr_preTotal);
		}

		//Get total 0f booking in Hours
		$sql_total_in_range = "SELECT COUNT( DISTINCT h_trans_customerid) AS Total_Of_range
								FROM h_transaction
								GROUP BY HOUR(h_trans_session)
								ORDER BY HOUR(h_trans_session)";
		$result_in_range = $mysql->Select_db($sql_total_in_range);

		foreach ($result_in_range as $read_in_range) {
			$arrSum[] = $read_in_range['Total_Of_range'];
		}

		$j_arr = array('time_all' => $arrTotal, 'total_range' => $arrSum);
		echo json_encode($j_arr);
		exit();
	}

	if($cmd == "income_chart"){
		$month = date('m');
		$year = date('Y');
		$year_select = $year;
		$source = "all";
		$count = 0;
		if(isset($_POST['year_select'])){
			$year_select = $_POST['year_select'];
		}
		if(isset($_POST['source'])){
			$source = $_POST['source'];
		}

		if($source == "all"){
			$select = "h_bill_price";
		}
		elseif($source == "other"){
			$select = "h_bill_otherprice";
		}

		for($i = 1; $i <= $month; $i++){
			//Get all
			$sql = "SELECT SUM(".$select.") AS Total_Of_price FROM h_bill
					WHERE MONTH(h_bill_timestamp) = ".$i."
					AND YEAR(h_bill_timestamp) = '".$year_select."'
					AND h_bill_status = 3";
			$result = $mysql->Select_db_one($sql);
			$count = $count+$result['Total_Of_price'];
			$arr[] = $result['Total_Of_price'];
		}

		$j_arr = array(
					'total_income' => $arr,
					'count_income' => number_format((float)$count, 2, '.', '')
				);
		echo json_encode($j_arr);
		exit();
	}

	if($cmd == "paymenttype_chart"){
		$year = date('Y');
		$year_select = $year;
		if(isset($_POST['year_select'])){
			$year_select = $_POST['year_select'];
		}
		$sql = "SELECT COUNT(h_bill_paymenttypeid) AS Total_Of_paymenttype
				FROM h_bill WHERE YEAR(h_bill_timestamp) = '".$year_select."'
				GROUP BY h_bill_paymenttypeid";
		$result = $mysql->Select_db($sql);
		foreach ($result as $read) {
			$arrTotal[] = $read['Total_Of_paymenttype'];
		}

		$sql = "SELECT DISTINCT h_paymenttype_type FROM h_paymenttype
				JOIN h_bill ON h_bill_paymenttypeid = h_paymenttype_id
				WHERE YEAR(h_bill_timestamp) = '".$year_select."'";
		$result_type = $mysql->Select_db($sql);
		foreach ($result_type as $read_type) {
			$arrType[] = $read_type['h_paymenttype_type'];
		}

		$j_arr = array(
					'total_of_type' => $arrTotal,
					'type_name' => $arrType
				);
		echo json_encode($j_arr);
		exit();
	}

	if($cmd == "rate_chart"){
		$month = date('m');
		$year = date('Y');
		$count = 0;
		$year_select = $year;
		if(isset($_POST['year_select'])){
			$year_select = $_POST['year_select'];
		}

		$sql = "SELECT DISTINCT h_rate_name FROM h_bill
					JOIN h_rate ON h_bill_rateid = h_rate_id
					WHERE YEAR(h_bill_timestamp) = '".$year_select."'
					ORDER BY h_rate_id";
		$result = $mysql->Select_db($sql);
		foreach ($result as $read) {

			for($i = 1; $i <= $month; $i++){
				//Count
				$sql_count = "SELECT COUNT(h_rate_id) AS Total_Of_rate FROM h_bill
							  JOIN h_rate ON h_bill_rateid = h_rate_id
							  WHERE YEAR(h_bill_timestamp) = '".$year_select."'
							  AND MONTH(h_bill_timestamp) = ".$i."
							  AND h_rate_name = '".$read['h_rate_name']."'
							  GROUP BY h_rate_id ORDER BY h_rate_id";

				$result_count = $mysql->Select_db_one($sql_count);
				$checknull = $mysql->numRows($sql_count);
				if($checknull == 0){
					$arrTotal[] = 0;
				}
				else{
					$arrTotal[] = $result_count['Total_Of_rate'];
				}
				

			}
			$arrRate[] = array('label' => $read['h_rate_name'], 'data' =>  $arrTotal);
			unset($arrTotal);
		}



		$j_arr[] = $arrRate;
		echo json_encode($arrRate);
		exit();
	}

	if($cmd == "search_last_login"){
		$sql = "SELECT * FROM h_user ORDER BY h_user_login DESC LIMIT 5";
		$result = $mysql->Select_db($sql);

		foreach ($result as $read) {
			$arr[] = array(
						'h_user_username' => $read['h_user_username'],
						'h_user_firstname' => $read['h_user_firstname'],
						'h_user_login' => $read['h_user_login']
					);
		}
		echo json_encode($arr);
		exit();
	}
}?>

<script type="text/javascript">
	$(document).ready(function(){
		//ดึงจำนวนห้องที่หาได้มาเก็บไว้แสดงผล
		var sum = $('.sumfromcmd').data('sum');
		$('.showcheck').attr("hidden",true);
		$('.sumresult').html('&nbsp;'+sum);
		console.log('Total data: '+sum);
	});
</script>