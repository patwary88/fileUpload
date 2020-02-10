<?php

	$DB_HOST = "localhost";
	$DB_USER = "root";
	$DB_PASS = "";
	$DB_NAME = "study_room";
	$conn = mysqli_connect($DB_HOST,$DB_USER,$DB_PASS,$DB_NAME);
	mysqli_query($conn,'SET CHARACTER SET utf8'); 
	mysqli_query($conn,"SET SESSION collation_connection ='utf8_general_ci'");


	function sql_select($query){
		global $conn;
		$result = mysqli_query($conn,$query);
		$rows = array();
		if (!empty($result)) {
		    while($row = mysqli_fetch_assoc($result)) {
		   	 $rows[] = $row; 
		    }
		} else {
			return 0;
		}
		return $rows;
		mysqli_close($conn);
	}

	function sql_insert($table_name,$colomn_name,$data_arr){
		global $conn;
		$sqlInsert = "INSERT INTO ".$table_name." (".$colomn_name." ) VALUES ( ".$data_arr." )";
		$result = mysqli_query($conn,$sqlInsert);

		return $result;
	}

	function return_next_id($table_name,$id_colomn,$where_cond=""){
		global $conn;
		if ($where_cond=="") {
			$whereCond="";
		}else{
			$whereCond=" WHERE ".$where_cond."";
		}

		$sql = sql_select("select max(".$id_colomn.") AS ".$id_colomn."  FROM ".$table_name.$whereCond."");
		

		if ($sql[0][$id_colomn]==0) {
			$idValue =1;
		}else{
			$idValue=$sql[0][$id_colomn]+1;
		}
		return $idValue;
	}


	function return_max_id($table_name,$id_colomn,$where_cond=""){
		global $conn;
		if ($where_cond=="") {
			$whereCond="";
		}else{
			$whereCond=" WHERE ".$where_cond."";
		}
		$sql = sql_select("select max(".$id_colomn.") AS ".$id_colomn."  FROM ".$table_name.$whereCond."");
		return $sql[0][$id_colomn];
	}
?>