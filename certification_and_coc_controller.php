<?php
/*********************************************************************
|	Purpose			:	Compliance Basic Information
|	Functionality	:	
|	JS Functions	:
|	Created by		:	Main Uddin Patwary
|	Creation date 	:	29.06.2019
|	Updated by 		: 		
|	Update date		:    
|	QC Performed BY	:		
|	QC Date			:	
|	Comments		:
**********************************************************************/

header('Content-type:text/html; charset=utf-8');
session_start();
if( $_SESSION['logic_erp']['user_id'] == "" ) header("location:login.php");
include('../../../includes/common.php');
$data=$_REQUEST['data'];
$action=$_REQUEST['action'];
$_SESSION['page_permission']=$permission;


if( $action=="file_uploader" )
{
	extract($_REQUEST);
	/*echo "<pre>";
	print_r($_REQUEST); die;*/
	echo load_html_head_contents("Legal License Info", "../../../", 1, 1, $unicode);
	?>
	<script type="text/javascript" src="../../../js/ajaxupload.js" ></script>
	<script type="text/javascript" >
		var del_file;
		function check_ext(ext,type)
		{
			if (type==1) // Image
			{
				if (! (ext && /^(jpg|png|jpeg|bmp)$/.test(ext)))  return false;
				else return true;
			}
			if (type==2) // pdf,txt,docs,xls
			{
				if (! (ext && /^(pdf|txt|doc|docx|xls|xlsx)$/.test(ext)))  return false;
				else return true;
			}
		}
		<?php
		//$document_details= json_encode($document_details); 
		//echo "var document_details = ".$document_details.";\n";
		?>
		$(function(){
			//alert("su..re"); return;

			var btnUpload	= $('#upload');
			var status		= $('#status');
			var company_id= '<? echo $cbo_company_id; ?>';
			var location_id= '<? echo $cbo_location_id; ?>';
			var cbo_audit_type= '<? echo $cbo_audit_type; ?>';
			var cbo_audit_status= '<? echo $cbo_audit_status; ?>';
			var file_type   = '<? echo $cbo_file_type ?>';
			var mst_id   = '<? echo $update_id ?>';
			var fname;
			//alert(cbo_audit_type); return;
			new AjaxUpload(btnUpload, {
				action: 'certification_and_coc_controller.php?action=php_upload_file&company_id='+company_id+'&location_id='+location_id+'&cbo_audit_type='+cbo_audit_type+'&cbo_audit_status='+cbo_audit_status+'&file_type='+file_type+'&mst_id='+mst_id,
				name: 'uploadfile',
				onSubmit: function(file, ext)
				{
					if (check_ext(ext,file_type)==false)
					{
						if (file_type==1)
						{ 
							status.text('Only JPG, PNG or bmp files are allowed');
							return false;
						}
						if (file_type==2)
						{ 
							status.text('Only Text, Pdf or Docs files are allowed');
							return false;
						}
					}
					status.html('<img src="../../../images/loading.gif" />');
				},
				onComplete: function(file, response)
				{
					status.text('');
					response=response.split("**");
					
					

					var del_file="<a href='##' onclick=\"remove_this_image('"+response[0]+"','"+response[1]+"','"+response[2]+"','"+response[3]+"','"+response[4]+"','"+response[5]+"','"+response[6]+"')\">Certification Audit</a>";	
					//alert(del_file); return;			
					if( file_type==1 )//image
					{
						$('<li></li>').appendTo('#files').html('<a href="##"><img src="../../../'+response[4]+'" height="120px" width="120px" /></a><br />'+del_file).addClass('success');
					}
					else //document
					{
						$('<li></li>').appendTo('#files').html('<a href="certification_and_coc_controller.php?filename=../../../'+response[4]+'&action=download_file"><img src="../../../file_upload/blank_file.png" height="120px" width="120px" /></a><br />'+del_file).addClass('success');
					}
				}
			});
		});
		 
		//removing here
		function remove_this_image(id,company_id,location_id,audit_type,file_location,filetype,audit_status)
		{
			 var conf=confirm("Do you really Want to Delete the Image");
			 if (conf==1)
			 {
				document.getElementById('files').innerHTML= $.ajax({
					url: 'certification_and_coc_controller.php?action=delete_uploaded_file&id='+id+'&company_id='+company_id+'&location_id='+location_id+'&audit_type='+audit_type+'&file_location='+file_location+'&filetype='+filetype+'&audit_status='+audit_status,
					async: false
				}).responseText
			}
		}

	</script>

	<style>
		#upload
		{
			padding:5px;
			font-weight:bold; font-size:1.3em;
			font-family:Arial, Helvetica, sans-serif;
			text-align:center;
			background:#999999;
			color:#ffffff;
			border:1px solid #999999;
			width:190px;
			cursor:pointer !important;
			-moz-border-radius:5px; -webkit-border-radius:5px;
		}
		 
		.darkbg{ background:#ddd !important; }
		#status{ font-family:Arial; padding:5px; }
		#files{ list-style:none; }
		#files li{ margin-top:7px; margin-left:7px; float:left }
		.success{ border:1px solid #CCCCCC;color:#660000; float:left }
		.error{ background:#FFFFFF; border:1px solid #CCCCCC; }
	</style>
</head>
<body>
	<div style="width:620px">
		<table width="100%" border="0" cellpadding="0" cellspacing="0">
			<tr>
				<td width="100%" height="250" align="center">
					<div id="files" style="width:100%; border:1px solid; height:100%;" align="center"> 
						<?php
				$nameArray=sql_select( "select b.ID,a.COMPANY_ID,a.LOCATION_ID, a.AUDIT_TYPE,b.FILE_LOCATION,b.FILE_TYPE,b.AUDIT_STATUS,b.PAGE_TYPE from CMP_CERTIFICATION_AUDIT a, CMP_FILE_LOCATION b where a.id=b.COC_MST_ID and a.COMPANY_ID=b.COMPANY_ID and a.LOCATION_ID=b.LOCATION_ID AND a.AUDIT_TYPE=b.AUDIT_TYPE AND a.AUDIT_STATUS=b.AUDIT_STATUS AND b.PAGE_TYPE=3 AND a.STATUS_ACTIVE=1 and a.IS_DELETED=0 AND b.COMPANY_ID=".$cbo_company_id." and b.LOCATION_ID=".$cbo_location_id." and a.AUDIT_TYPE = ".$cbo_audit_type." and b.AUDIT_STATUS= ".$cbo_audit_status." and b.FILE_TYPE= ".$cbo_file_type."" );
						
						if(count($nameArray)>0) 
						{
							foreach($nameArray as $inf)
							{
								$ext =strtolower( get_file_ext($inf[csf("file_location")]));
								$exp_file_location=explode('/',$inf[csf("file_location")]);
								$file_name=$exp_file_location[2];
								//$file_name=$document_details[$inf[csf("document_name")]];
								if($ext=="jpg" || $ext=="jpeg" || $ext=="png" || $ext=="bmp")
								{
									//alert(filetype); return;
									?>			
									<li><a href="##"><img src="<? echo '../../../'.$inf[csf("file_location")]; ?>" height="120" width="120" /></a><br />
									<a href='##'  onClick="remove_this_image('<? echo $inf[csf("id")]; ?>','<? echo $inf[csf("company_id")]; ?>','<? echo $inf[csf("location_id")]; ?>','<? echo $inf[csf("audit_type")]; ?>','<? echo $inf[csf("file_location")]; ?>','<? echo $inf[csf("file_type")]; ?>','<? echo $inf[csf("audit_status")]; ?>')"><? echo "Certification Audit"; ?></a>
									</li>
									<? 
								}
								else
								{
									?>			
									<li><a href="certification_and_coc_controller.php?filename=<? echo "../../../".$inf[csf("file_location")]; ?>&action=download_file" style="text-transform:none"><img  src="<? echo '../../../file_upload/blank_file.png'; ?>" height="120" width="120" /></a><br />
									<a href='##' onClick="remove_this_image('<? echo $inf[csf("id")]; ?>','<? echo $inf[csf("company_id")]; ?>','<? echo $inf[csf("location_id")]; ?>','<? echo $inf[csf("audit_type")]; ?>','<? echo $inf[csf("file_location")]; ?>','<? echo $inf[csf("file_type")]; ?>','<? echo $inf[csf("audit_status")]; ?>')"><? echo "Certification Audit"; ?></a>
								</li>
								<? 
								}
							}
						}
						else
						{ 
							?>
							<div id="files" style="float:left" align="center"></div>
							<?
						}
						?>
					</div>
				</td>
			</tr>
			<tr>
				<td width="100%" align="center">
					<div id="status" align="center"></div>
					<div style="padding-top:5px">
						<div id="upload"><span>Select File</span></div>
					</div>
					<div style="width:100px; padding-top:5px" align="center"></div>
				</td>
			</tr>
		</table>
	</div>
</body>
</html>

	<?	
}

extract($_REQUEST);
if ($action=="php_upload_file")
{
	$con = connect();
	if($db_type==0)
	{
		mysql_query("BEGIN");
	}
	
	$image =$_FILES["uploadfile"]["name"];
	$uploadedfile = $_FILES['uploadfile']['tmp_name'];
	$extension =strtolower( get_file_ext($_FILES["uploadfile"]["name"]));

	if($extension=="jpg" || $extension=="jpeg" || $extension=="png" || $extension=="bmp")
	{
		if ( $image ) 
		{
			if($extension=="jpg" || $extension=="jpeg" )
			{
				$uploadedfile = $_FILES['uploadfile']['tmp_name'];
				$src = imagecreatefromjpeg($uploadedfile);
			}
			else if($extension=="png")
			{
				$uploadedfile = $_FILES['uploadfile']['tmp_name'];
				$src = imagecreatefrompng($uploadedfile);
			}
			else 
			{
				$src = imagecreatefromgif($uploadedfile);
			}
			list($width,$height)=getimagesize($uploadedfile);
			$newwidth=300;
			if ($width<$newwidth)
			{
				$newwidth=$width;
			}
			$newheight=($height/$width)*$newwidth;
			$tmp=imagecreatetruecolor($newwidth,$newheight);
			
			imagecopyresampled($tmp,$src,0,0,0,0,$newwidth,$newheight,$width,$height); 
			
			$uploaddir = '../../../file_upload/certification_audit/';
			$tmp_name = $uploaddir . basename($_FILES['uploadfile']['name']);


			 if ($is_multi!=1)
			 {
				 $id=return_next_id( "id", "CMP_FILE_LOCATION", 1 ) ;
				 $fname=$id."_".$company_id."_".$location_id."_".$cbo_audit_type."_".$cbo_audit_status."_".$file_type.".".$extension;
			 }
			 else
			 {
				 $id=return_next_id( "id", "CMP_FILE_LOCATION", 1 ) ;
				   $fname=$id."_".$company_id."_".$location_id."_".$cbo_audit_type."_".$cbo_audit_status."_".$file_type.".".$extension;
			 }
			
			$file="$uploaddir$fname";
			$file_save="file_upload/certification_audit/"."$fname";
			//echo $licence_no."==".$file_save."==="; die;
			imagejpeg($tmp,$file,100);
			imagedestroy($src);
			imagedestroy($tmp);

			$is_moved=move_uploaded_file($_FILES['uploadfile']['tmp_name'], $file);
			if ($is_moved) 
			{
				if ($mst_id=="") {
				$mstID = return_next_id( "id", "CMP_CERTIFICATION_AUDIT", 1 ) ;
				}else{
					$mstID = $mst_id;
				}


				$field_array="ID,COMPANY_ID,LOCATION_ID,LICENCE_NO,FILE_LOCATION,FILE_TYPE,PAGE_TYPE,AUDIT_TYPE,AUDIT_STATUS,COC_MST_ID";
				$data_array[]="('".$id."','".$company_id."','".$location_id."','0','".$file_save."','".$file_type."','3','".$cbo_audit_type."','".$cbo_audit_status."','".$mstID."')";
				$rID=sql_insert("CMP_FILE_LOCATION",$field_array,$data_array,1);
				//echo "<pre>";
				//print_r($file_type); die;
				//echo "insert into CMP_FILE_LOCATION ($field_array) values $data_array[0]";die;
				if($db_type==0)
				{
					if($rID )
					{
						mysql_query("COMMIT"); 

						echo $id."**".$company_id."**".$location_id."**".$cbo_audit_type."**".$file_save."**".$file_type."**".$cbo_audit_status;
					}
					else
					{
						mysql_query("ROLLBACK"); 
						echo "10**".$file_save;
					}
				}
					 
				if($db_type==2 || $db_type==1 )
				{
					if($rID )
					{
						oci_commit($con);  
						echo $id."**".$company_id."**".$location_id."**".$cbo_audit_type."**".$file_save."**".$file_type."**".$cbo_audit_status;
					}
					else
					{
						oci_rollback($con);
						echo "10**".$file_save;
					}
				}
				disconnect($con);
			}
			//else echo  "not".$extension; die;
		}
	}
	else  //Not Image File
	{
		//echo "su..re"; die;
		$uploaddir = '../../../file_upload/certification_audit/';
		$tmp_name = $uploaddir . basename($_FILES['uploadfile']['name']);
		
		if ($is_multi!=1)
		{
			$id=return_next_id( "id", "CMP_FILE_LOCATION", 1 ) ;
			$fname=$id."_".$company_id."_".$location_id."_".$cbo_audit_type."_".$cbo_audit_status."_".$file_type.".".$extension;
		}
		else
		{
			$id=return_next_id( "id", "CMP_FILE_LOCATION", 1 ) ;
			$fname=$id."_".$company_id."_".$location_id."_".$cbo_audit_type."_".$cbo_audit_status."_".$file_type.".".$extension;
		}

		$file="$uploaddir$fname";
		$file_save="file_upload/certification_audit/"."$fname";
		$is_moved=move_uploaded_file($_FILES['uploadfile']['tmp_name'], $file);
		if ($is_moved) 
		{
			if ($mst_id=="") {
				$mstID = return_next_id( "id", "CMP_CERTIFICATION_AUDIT", 1 ) ;
			}else{
				$mstID = $mst_id;
			}

			$field_array="id,COMPANY_ID,LOCATION_ID,LICENCE_NO,FILE_LOCATION,FILE_TYPE,PAGE_TYPE,AUDIT_TYPE,AUDIT_STATUS,COC_MST_ID";
			$data_array[]="('".$id."','".$company_id."','".$location_id."','0','".$file_save."','".$file_type."','3','".$cbo_audit_type."','".$cbo_audit_status."','".$mstID."')";
			
			$rID=sql_insert("CMP_FILE_LOCATION",$field_array,$data_array,1);

			//echo "insert into hrm_emp_docs_location $field_array values ($data_array)";die;
			if($db_type==0)
			{
				if($rID )
				{
					mysql_query("COMMIT");  
					echo $id."**".$company_id."**".$location_id."**".$cbo_audit_type."**".$file_save."**".$file_type."**".$cbo_audit_status;
				}
				else
				{
					mysql_query("ROLLBACK"); 
					echo "10**".$id;
				}
			}

			if($db_type==2 || $db_type==1 )
			{
				if($rID )
				{
					oci_commit($con); 
					echo $id."**".$company_id."**".$location_id."**".$cbo_audit_type."**".$file_save."**".$file_type."**".$cbo_audit_status;
				}
				else
				{
					oci_rollback($con);
					echo "10**".$id;
				}
			}
			disconnect($con);
		}
	}
}

if ($action=="delete_uploaded_file")
{
	extract($_REQUEST);
	//echo "<pre>";
	//print_r($_REQUEST); die;
	$con = connect();
	if($db_type==0)
	{
		mysql_query("BEGIN");
	}
	//echo '../../../'.$file_location;
	$rID=execute_query( "delete from cmp_file_location where id='$id'", $commit );
	unlink('../../../'.$file_location);
	
	if($db_type==0)
	{
		if($rID )
		{
			mysql_query("COMMIT");  
			//echo $file_save."**".$id;
		}
		else
		{
			mysql_query("ROLLBACK"); 
			//echo "10**".$id;
		}
	}
	if($db_type==2 || $db_type==1)
	{
		if($rID )
		{
			oci_commit($con); 
			//echo $file_save."**".$id;
		}
		else
		{
			oci_rollback($con); 
			//echo "10**".$id;
		}
	}

	$new_img="";

	$nameArray=sql_select( "select b.ID,a.COMPANY_ID,a.LOCATION_ID, a.AUDIT_TYPE,b.FILE_LOCATION,b.FILE_TYPE,b.AUDIT_STATUS,b.PAGE_TYPE from CMP_CERTIFICATION_AUDIT a, CMP_FILE_LOCATION b where a.id=b.COC_MST_ID and a.COMPANY_ID=b.COMPANY_ID and a.LOCATION_ID=b.LOCATION_ID AND a.AUDIT_TYPE=b.AUDIT_TYPE AND a.AUDIT_STATUS=b.AUDIT_STATUS AND b.PAGE_TYPE=3 AND a.STATUS_ACTIVE=1 and a.IS_DELETED=0 AND b.COMPANY_ID=".$company_id." and b.LOCATION_ID=".$location_id." and a.AUDIT_TYPE = ".$audit_type." and b.AUDIT_STATUS= ".$audit_status." and b.FILE_TYPE= ".$filetype."" );

	if (count($nameArray)>0) 
	{
		foreach ($nameArray as $inf)
		{
			$ext =strtolower( get_file_ext($inf[csf("file_location")]));
			$exp_file_location=explode('/',$inf[csf("file_location")]);
			$file_name=$exp_file_location[2];

			if($ext=="jpg" || $ext=="jpeg" || $ext=="png" || $ext=="bmp")
			{
				$new_img .="<li><a href='##'><img src='../../../".$inf[csf("file_location")]."' height='120' width='120' /></a><br /><a href='##' onclick=\"remove_this_image('".$inf[csf("id")]."','".$inf[csf("company_id")]."','".$inf[csf("location_id")]."','".$inf[csf("audit_type")]."','".$inf[csf("file_location")]."','".$inf[csf("file_type")]."','".$inf[csf("audit_status")]."')\">Certification Audit</a></li>";
			}
			else
			{
				$new_img .="<li><a href='certification_and_coc_controller.php?filename=../../../".$inf[csf("file_location")]."&action=download_file' style='text-transform:none'><img src='../../../file_upload/blank_file.png' height='120' width='120' /></a><br /><a href='##' onclick=\"remove_this_image('".$inf[csf("id")]."','".$inf[csf("company_id")]."','".$inf[csf("location_id")]."','".$inf[csf("audit_type")]."','".$inf[csf("file_location")]."','".$inf[csf("file_type")]."','".$inf[csf("audit_status")]."')\">Certification Audit</a></li>";	
			}
		}
	}
	else
	{  
		$new_img .='<div id="files" style="float:left" align="center"></div>';
	}
	disconnect($con);
	echo $new_img;
	if($db_type==2 || $db_type==1 )
	{
		//echo $file_save."**".$id;
	}
}

if($action=="download_file")
{
	extract($_REQUEST);
	/*echo "<pre>";
	print_r($_REQUEST); die;*/
	set_time_limit(0);
	$file_path=$_REQUEST['filename'];
	download_start($file_path, ''.$_REQUEST['filename'].'', 'text/plain');
}

function download_start($file, $name, $mime_type='')
{
	if(file_exists($file))
	{
		echo "file found";
	}
	else
	{
		die('File not found');
	}

	//Check the file exist or not
	if(!is_readable($file)) die('File not found or inaccessible!');
	$size = filesize($file);
	$name = rawurldecode($name);
	/* MIME type check*/
	$known_mime_types=array(
	  "pdf" => "application/pdf",
	  "txt" => "text/plain",
	  "html" => "text/html",
	  "htm" => "text/html",
	  "exe" => "application/octet-stream",
	  "zip" => "application/zip",
	  "doc" => "application/msword",
	  "xls" => "application/vnd.ms-excel",
	  "ppt" => "application/vnd.ms-powerpoint",
	  "gif" => "image/gif",
	  "png" => "image/png",
	  "jpeg"=> "image/jpg",
	  "jpg" =>  "image/jpg",
	  "php" => "text/plain"
	);
	
	if($mime_type=='')
	{
		$file_extension = strtolower(substr(strrchr($file,"."),1));
		if(array_key_exists($file_extension, $known_mime_types))
		{
		$mime_type=$known_mime_types[$file_extension];
		} 
		else 
		{
			$mime_type="application/force-download";
		}
	}
	//turn off output buffering to decrease cpu usage
	@ob_end_clean(); 
	// required for IE Only
	if(ini_get('zlib.output_compression'))
	ini_set('zlib.output_compression', 'Off'); 
	header('Content-Type: ' . $mime_type);
	header('Content-Disposition: attachment; filename="'.$name.'"');
	header("Content-Transfer-Encoding: binary");
	header('Accept-Ranges: bytes'); 
	/*non-cacheable */
	header("Cache-control: private");
	header('Pragma: private');
	header("Expires: Tue, 15 May 1984 12:00:00 GMT");
	
	// multipart-download and download resuming support
	if(isset($_SERVER['HTTP_RANGE']))
	{
		list($a, $range) = explode("=",$_SERVER['HTTP_RANGE'],2);
		list($range) = explode(",",$range,2);
		list($range, $range_end) = explode("-", $range);
		$range=intval($range);
		if(!$range_end) {
		 $range_end=$size-1;
		} else {
		 $range_end=intval($range_end);
		}
		$new_length = $range_end-$range+1;
		header("HTTP/1.1 206 Partial Content");
		header("Content-Length: $new_length");
		header("Content-Range: bytes $range-$range_end/$size");
	} else {
		$new_length=$size;
		header("Content-Length: ".$size);
	}
	
	/* Will output the file itself */
	$chunksize = 1*(1024*1024); //you may want to change this
	$bytes_send = 0;
	if ($file = fopen($file, 'r')){
	if(isset($_SERVER['HTTP_RANGE']))
	fseek($file, $range);
	
	while(!feof($file) && (!connection_aborted()) && ($bytes_send<$new_length))
	{
		$buffer = fread($file, $chunksize);
		print($buffer); //echo($buffer); // can also possible
		flush();
		$bytes_send += strlen($buffer);
	}
	fclose($file);
	} else
	//If no permissiion
	die('Error - can not open file.');
	//die
	die();
}






if ($action=="certification_coc_list_new") {
	
	extract($_REQUEST);
	echo load_html_head_contents("Certification And COC Info", "../../../", 1, 1, $unicode);
	?>
	<script type="text/javascript">
    	
    	function js_set_value(emp_row)
		{
			$('#selected_prod_row').val(emp_row);
			parent.emailwindow.hide();
		}
    </script>
	</head>
    <body>
    	<form name="legalinfo_1"  id="legalinfo_1" autocomplete="off">
    		<table cellspacing="0" cellpadding="0" border="1" rules="all" class="rpt_table" width="1000" align="left">
    			<thead>
    				<tr>
    					<th width="150">Company</th>
    					<th width="150">Factorly Address</th>
    					<th width="100">Buyer</th>
    					<th width="100">Audit Type</th>
    					<th width="100">Issue Date</th>
    					<th width="100">Renewal Date</th>
    					<th width="100">Audit Status</th>
    					<th></th>
    				</tr>
    			</thead>
    			<tbody>
    				<tr class="general">
    					<input type="hidden" id="selected_prod_row">
    					<td id="buyer_td"><?php echo create_drop_down( 'cbo_company_id',120, return_array_from_array( $company_arr, 0, 'company_name'  ), '', 1, '--Select--', 0, $onchange_func ); ?></td>
    					<td><? echo create_drop_down( "cbo_location_id",120, get_location_array_for_listview(), " ", 1, '--Select--', 0, $onchange_func); ?></td>
    					<td><?php echo create_drop_down( 'cbo_applicable_buyer',120,$buyer_arr, '', 1, '--Select--', 0, $onchange_func ); ?></td>
    					<td><?php echo create_drop_down( 'cbo_audit_type',120,$audit_type_arr, '', 1, '--Select--', 0, $onchange_func ); ?></td>
    					<td><input name="txt_last_audit_date" id="txt_last_audit_date" class="datepicker" style="width:100px"></td>
    					<td><input name="txt_audit_expire_date" id="txt_audit_expire_date" class="datepicker" style="width:100px"></td> 
    					<td><?php echo create_drop_down( 'cbo_audit_status',120,$audit_status, '', 1, '--Select--', 0, $onchange_func ); ?></td>
    					<td align="center">
    						<input type="button" name="button" class="formbutton" value="Show" onClick="show_list_view ($('#cbo_company_id').val()+'_'+$('#cbo_location_id').val()+'_'+$('#cbo_applicable_buyer').val()+'_'+$('#cbo_audit_type').val()+'_'+$('#txt_last_audit_date').val()+'_'+$('#txt_audit_expire_date').val()+'_'+$('#cbo_audit_status').val(), 'create_certification_coc_list_view', 'search_div', 'certification_and_coc_controller', 'setFilterGrid(\'list_view\',-1)')" style="width:100px;" /></td>
    					</tr>
    				</tbody>
    			</table>
    		</form>
            <div id="search_div" align="center"></div>
        </div>
    </body>
    <script src="../../../includes/functions_bottom.js" type="text/javascript"></script>
    </html>
	<?
}

if ($action=="create_certification_coc_list_view") {

	$search_data=explode('_',$data);

	if ($search_data[0]==0) $company_id=""; else $company_id = "AND company_id='".$search_data[0]."'";
	if ($search_data[1]==0) $location_id=""; else $location_id = "AND location_id='".$search_data[1]."'";
	if ($search_data[2]==0) $buyer_id=""; else $buyer_id = "AND buyer_id='".$search_data[2]."'";
	if ($search_data[3]==0) $audit_type=""; else $audit_type = "AND audit_type='".$search_data[2]."'";
	if ($search_data[4]==NULL) $last_audit_date = ""; else $last_audit_date = "AND last_audit_date=".date("d-M-Y",strtotime($search_data[4]))."";
	if ($search_data[5]==NULL) $audit_expire_date = ""; else $audit_expire_date = "AND audit_expire_date=".date("d-M-Y",strtotime($search_data[5]))."";
	if ($search_data[6]==0) $audit_statusss=""; else $audit_statusss = "AND audit_status='".$search_data[6]."'";

	$sql ="SELECT ID,COMPANY_ID,LOCATION_ID,BUYER_ID,AUDIT_TYPE,LAST_AUDIT_DATE,AUDIT_EXPIRE_DATE,AUDITED_BY,AUDITOR_RATING,AUDIT_STATUS,REMARK FROM cmp_certification_audit WHERE STATUS_ACTIVE=1 ".$company_id.$location_id.$buyer_id.$audit_type.$last_audit_date.$audit_expire_date.$audit_statusss."";

	$result=sql_select($sql);
	if(empty($result))
    {
        echo "<h1 style=\"color:red;font-size:20px;\">No Record Found.</h1>";
        die;
    }
   	$arr=array (0=>get_company_array_for_listview(),1=>get_location_array_for_listview(),2=>$buyer_arr,3=>$audit_type_arr,4=>$audit_status);

   echo  create_list_view("list_view", "Company,Factory Address,Buyer,Audit Type,Audit Status,Last Audit,Audit Expire,Audited By,Auditor Rating,Remark", "100,100,100,100,80,80,80,80,80","1000","320",0, $sql , "js_set_value", "id,company_id,location_id,buyer_id,audit_type,audit_status,last_audit_date,audit_expire_date,audited_by,auditor_rating,remark", "",1, "COMPANY_ID,LOCATION_ID,BUYER_ID,AUDIT_TYPE,AUDIT_STATUS,0,0,0,0,0", $arr , "COMPANY_ID,LOCATION_ID,BUYER_ID,AUDIT_TYPE,AUDIT_STATUS,LAST_AUDIT_DATE,AUDIT_EXPIRE_DATE,AUDITED_BY,AUDITOR_RATING,REMARK", "",'setFilterGrid("list_view",-1);','','','','');

}
   if($action=="show_listview")
	
{
	$data_arr=sql_select("SELECT ID,COMPANY_ID,LOCATION_ID,BUYER_ID,AUDIT_TYPE,LAST_AUDIT_DATE,AUDIT_EXPIRE_DATE,AUDITED_BY,AUDITOR_RATING,REMARK,AUDIT_STATUS FROM CMP_CERTIFICATION_AUDIT WHERE id=".$data."");
	
		foreach($data_arr as $row) 
		{
			$last_audit_date      = date("d-m-Y",strtotime($row['LAST_AUDIT_DATE']));
			$audit_expire_date    = date("d-m-Y",strtotime($row['AUDIT_EXPIRE_DATE']));

			echo "document.getElementById('cbo_company_id').value = '".$row["COMPANY_ID"]."';\n";
			echo "document.getElementById('cbo_location_id').value      = '".$row["LOCATION_ID"]."';\n";
			echo "document.getElementById('cbo_applicable_buyer').value      = '".$row["BUYER_ID"]."';\n";
			echo "document.getElementById('cbo_audit_type').value      = '".$row["AUDIT_TYPE"]."';\n";
			echo "document.getElementById('txt_last_audit_date').value      = '".$last_audit_date."';\n";
			echo "document.getElementById('txt_audit_expire_date').value      = '".$audit_expire_date."';\n";
			echo "document.getElementById('txt_audited_by').value      = '".$row["AUDITED_BY"]."';\n";
			echo "document.getElementById('txt_auditor_rating').value      = '".$row["AUDITOR_RATING"]."';\n";
			echo "document.getElementById('txt_remark').value      = '".$row["REMARK"]."';\n";
			echo "document.getElementById('cbo_audit_status').value      = '".$row["AUDIT_STATUS"]."';\n";
			echo "document.getElementById('update_id').value      = '".$row["ID"]."';\n";
		}
		echo "set_button_status(1, '".$_SESSION['page_permission']."', 'fnc_certification_coc',1);\n";
}



if ($action=="save_update_delete")
{
	/*echo "<pre>";
	print_r($_POST);*/
	$process = array( &$_POST );
	extract(check_magic_quote_gpc( $process )); 
	if ($operation==0)  // Insert Here=======================================================
	{
		//echo "sdfsdfsd";
		$con = connect();
		$id=return_next_id( "id", "cmp_certification_audit", 1 ) ;

		$field_array="id,company_id,location_id,buyer_id,audit_type,last_audit_date,audit_expire_date,audited_by,auditor_rating,audit_status,remark,file_type,inserted_by,insert_date";

		$data_array[]="(".$id.",".$cbo_company_id.",".$cbo_location_id.",".$cbo_applicable_buyer.",".$cbo_audit_type.",".$txt_last_audit_date.",".$txt_audit_expire_date.",".$txt_audited_by.",".$txt_auditor_rating.",".$cbo_audit_status.",".$txt_remark.",".$cbo_file_type.",".$_SESSION['logic_erp']['user_id'].",".$insert_update_date_time.")";

		//echo $sql = "INSERT INTO cmp_certification_audit (".$field_array.") VALUES ".$data_array[0]."";
		//echo $data_array[0]; die;

		$rID=sql_insert("cmp_certification_audit",$field_array,$data_array,0);
		if($rID)
		{
			oci_commit($con);   
			echo "0**".str_replace("'",'',$id);
		}
		else
		{
			oci_rollback($con);
			echo "10**";
		}
		disconnect($con);
		die;
	}
	//update
	if($operation==1)
	{
		$con = connect();
		
		$field_array="company_id*location_id*buyer_id*audit_type*last_audit_date*audit_expire_date*audited_by*auditor_rating*remark*audit_status*file_type*updated_by*update_date";

		$data_array="".$cbo_company_id."*".$cbo_location_id."*".$cbo_applicable_buyer."*".$cbo_audit_type."*".$txt_last_audit_date."*".$txt_audit_expire_date."*".$txt_audited_by."*".$txt_auditor_rating."*".$txt_remark."*".$cbo_audit_status."*".$cbo_file_type."*".$_SESSION['logic_erp']['user_id']."*".$insert_update_date_time."";
		
		$rID=sql_update("cmp_certification_audit",$field_array,$data_array,"id","".$update_id."",1);

		if($rID)
		{
			oci_commit($con);   
			echo "1**".$update_id;
		}
		else
		{
			oci_rollback($con);
			echo "10**".$rID;
		}
	   disconnect($con);
	   die;
	}

	//delete
	if($operation==2)
	{
		$con = connect();

		$field_array="UPDATED_BY*UPDATE_DATE*STATUS_ACTIVE*IS_DELETED";
		$data_array="".$_SESSION['logic_erp']['user_id']."*".$insert_update_date_time."*'0'*'1'";
		//echo $update_id;
		$rID=sql_update("cmp_certification_audit",$field_array,$data_array,"id","".$update_id."",1);
		if($rID)
		{
			oci_commit($con);   
			echo "2**".$rID;
		}
		else
		{
			oci_rollback($con);
			echo "10**".$rID;
		}
		disconnect($con);
		die;
	}
}

?>