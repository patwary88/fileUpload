<?php
require_once('../includes/common.php');
	extract($_REQUEST);
	extract($_FILES);
	if ($action=="uploadFile") {
	
	// delete file
		$files = glob('../file_upload/*'); 

		foreach($files as $file){
		  if(is_file($file))
		    unlink($file); 
		}
		// File Read...
		if ($_FILES['file']['name']!="") {
			
			$fileName = $_FILES['file']['name'];
			$expName  = explode(".",$fileName);
			$extension = strtolower(end($expName));

			$uploaddir = '../file_upload/';
			$tmp_name = $uploaddir . basename(strtolower($_FILES['file']['name']));

			$is_moved=move_uploaded_file($_FILES['file']['tmp_name'], $tmp_name);

			if ($is_moved) {
				echo $is_moved;
			}
		}
	}

	if ($action=="file_upload") {
		
		$category_id = $category_id;
		$file_type_id = $file_type_id;
		//echo $file_type_id."==".$category_id;

		if ($file_type_id==1) {
				
			$files = glob('../file_upload/*'); 
			
			foreach($files as $file){
			  	$tmpFile = explode(".",$file);
				$fileExtension = end($tmpFile);
				$txtFile = fopen($file,"r");

				while(! feof($txtFile))
				  {
				  	echo $lineOfText = fgets($txtFile),"<br/>";
				  	$output = preg_split( "/ (.||) /", $lineOfText );
				  	/*echo "<pre>";
				  	print_r($output);*/
				  	
				  }

			}

		}

	}
?>