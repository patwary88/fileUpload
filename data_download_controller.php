<?php 
header( 'Content-type:text/html; charset=utf-8' );
session_start();
if( $_SESSION['logic_erp']['user_id']=='' ) header( 'location:login.php' );
include('../../includes/common.php');
//include('../../includes/excel_reader.php');
set_include_path(get_include_path().PATH_SEPARATOR.'Classes/');
include 'PHPExcel/IOFactory.php';
$data	= $_REQUEST['data'];
$action	= $_REQUEST['action'];

if( $action=="file_uploader" )
{	
	//echo $file_type;
	//echo $txt_emp_code;die;
	echo load_html_head_contents("File Uploader","../../", '', '', '');
	?>
    <script type="text/javascript" src="../../js/ajaxupload.js" ></script>  
	<script type="text/javascript" >
		var del_file;
		function check_ext(ext,type)
		{
			//alert(type);
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
		
		$(function(){
			//alert("su..re"); return;
			var btnUpload	= $('#upload');
			var status		= $('#status');
			var cbo_reader_model= '<? echo $cbo_reader_model; ?>';
			//alert(txt_emp_code);
			//var file_type	= '<? //echo $file_type ?>';
			var file_type=2;
			//alert(file_type)
			
			var fname;

			new AjaxUpload(btnUpload, {
			//alert(master_tble_id);
				action: 'data_download_controller.php?action=php_upload_file&cbo_reader_model='+cbo_reader_model+'&file_type='+file_type,
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
					status.html('<img src="../../images/loading.gif" />');
				},
				onComplete: function(file, response)
				{
					//On completion clear the status
					status.text('');
					response=response.split("**");
					fname=response[3].split("/"); 
					//alert(fname[1]);
					//var del_file="<a href='##' onclick=\"remove_this_image('"+response[0]+"','"+response[1]+"','"+response[2]+"','"+response[3]+"')\">"+fname[2]+"</a>";				
					if( file_type==1 )//image
					{
						//$('<li></li>').appendTo('#files').html('<a href="##"><img src="../../'+response[3]+'" height="97px" width="89px" /></a><br />'+del_file).addClass('success');
					}
					else //document
					{
						$('<li></li>').appendTo('#files').html('<a href="employee_document_info_controller.php?filename=../../'+response[3]+'&action=download_file"><img src="../../file_upload/blank_file.png" height="97px" width="89px" /></a><br />'+fname[1]).addClass('success');
					}
				}
			});
		});
		 
		//removing here
		function remove_this_image(id,txt_emp_code,file_type,file_location)
		{
			 //alert(file_location)
			 var conf=confirm("Do you really Want to Delete the Image");
			 if (conf==1)
			 {
				document.getElementById('files').innerHTML= $.ajax({
					url: 'employee_document_info_controller.php?action=delete_uploaded_file&id='+id+'&txt_emp_code='+txt_emp_code+'&file_type='+file_type+'&file_location='+file_location,
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
    <div style="width:392px" align="center">
    	<table width="100%" border="0" cellpadding="0" cellspacing="0" align="center">
        	<tr>
            	<td width="100%" height="200" align="center">
                    <div id="files"  style="width:100%; border:1px solid;  height:100%; background-color:" align="center"></div> 
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
             <tr>
            	<td width="100%" align="center"></td>
            </tr>
             <tr>
            	<td width="100%" align="center">
                    <input type="button" id="closs_btn" name="closs_btn" class="formbutton" style="width:50px" value="Close" onClick="parent.emailwindow.hide();"/>
                </td>
            </tr>
        </table>
    </div>
</body>
</html>
<?
exit();
}

if ($action=="php_upload_file")
{
	extract($_REQUEST);
	$con = connect();
	if($db_type==0)
	{
		mysql_query("BEGIN");
	}
	
	$image =$_FILES["uploadfile"]["name"];
	$uploadedfile = $_FILES['uploadfile']['tmp_name'];
	$extension =strtolower( get_file_ext($_FILES["uploadfile"]["name"]));
 	
	if($extension=="xls" || $extension=="xlsx" || strtoupper($extension)=="TXT" || $extension=="bmp")
	{
		$uploaddir = 'download/';
		$tmp_name = $uploaddir . basename($_FILES['uploadfile']['name']);

		$fname="employee_attendance_file".".".$extension;
		
		$file="$uploaddir$fname";
		$file_save="download/"."$fname";
		$is_moved=move_uploaded_file($_FILES['uploadfile']['tmp_name'], $file);
		if ($is_moved) 
		{
			$rID=1;
			if($db_type==0)
			{
				if($rID )
				{
					mysql_query("COMMIT");  
					echo $id."**".$cbo_reader_model."**".$file_type."**".$file_save;
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
					echo $id."**".$cbo_reader_model."**".$file_type."**".$file_save;
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
	else
	{
		$msg .= 'Select txt or xls File';
	} 
	exit();
}

if($action=="download_file")
{
	extract($_REQUEST);
	set_time_limit(0);
	$file_path=$_REQUEST['filename'];
	download_start($file_path, ''.$_REQUEST['filename'].'', 'text/plain');
	exit();
}

if($action=='save_update_delete')
{
	$process=array( &$_POST );
	extract(check_magic_quote_gpc( $process ));
	
	if($operation==0)
	{
		$con = connect();
		$cbo_reader_model_explode=explode("_",str_replace("'","",$cbo_reader_model));
		$reader_model = str_replace("'",'',$cbo_reader_model_explode[0]);

		$from_date=add_date(date("Y-m-d",time()),-30);
		$to_date  =add_date(date("Y-m-d",time()),5);
		$from_date=change_date_format(str_replace("'",'',add_date(date("Y-m-d",time()),-30)),'','',1);
		$to_date  =change_date_format(str_replace("'",'',add_date(date("Y-m-d",time()),5)),'','',1);

		/*
		|--------------------------------------------------------------------------
		| HRM_DUTY_ROSTER_PROCESS
		|--------------------------------------------------------------------------
		|
		*/
		$sqldata=sql_select("SELECT EMP_CODE, CURRENT_SHIFT, SHIFT_DATE, OVERTIME_POLICY FROM HRM_DUTY_ROSTER_PROCESS WHERE SHIFT_DATE BETWEEN '".$from_date."' AND '".$to_date."'");
		foreach($sqldata as $row)
		{
			$shift_date=date("Y-m-d",strtotime($row['SHIFT_DATE']));
			$roster_emp_shift[$row['EMP_CODE']][$shift_date]['shift_policy_id']=$row['CURRENT_SHIFT'];
			$roster_emp_shift[$row['EMP_CODE']][$shift_date]['overtime_policy_id']=$row['OVERTIME_POLICY'];
		}
		unset($sqldata);
		//print_r($roster_emp_shift); die;
		
		/*
		|--------------------------------------------------------------------------
		| LIB_POLICY_SHIFT
		|--------------------------------------------------------------------------
		|
		*/
		$sqldata=sql_select("SELECT ID, SHIFT_NAME, SHIFT_TYPE, CROSS_DATE, TO_CHAR(SHIFT_START,'HH24:MI') AS SHIFT_START, TO_CHAR(SHIFT_END,'HH24:MI') AS SHIFT_END, GRACE_MINUTES, EXIT_BUFFER_MINUTES, EARLY_OUT_START, ENTRY_RESTRICTION_START, FIRST_BREAK_START, FIRST_BREAK_END, SECOND_BREAK_START, SECOND_BREAK_END, LUNCH_TIME_START, LUNCH_TIME_END, LAST_PUNCH_OT, IS_ACCESS_CONTROL FROM LIB_POLICY_SHIFT");	
		foreach($sqldata as $row)
		{
			$shift_policy[$row['ID']]['id']=$row['ID'];
			$shift_policy[$row['ID']]['shift_type']=$row['SHIFT_TYPE'];
			$shift_policy[$row['ID']]['cross_date']=$row['CROSS_DATE'];
			$shift_policy[$row['ID']]['shift_start']=$row['SHIFT_START'];	
			$shift_policy[$row['ID']]['shift_end']=$row['SHIFT_END'];	
			$shift_policy[$row['ID']]['grace_minutes']=$row['GRACE_MINUTES'];
			$shift_policy[$row['ID']]['exit_buffer_minutes']=$row['EXIT_BUFFER_MINUTES'];
			$shift_policy[$row['ID']]['entry_restriction_start']=$row['ENTRY_RESTRICTION_START'];
		}
		unset($sqldata);
		//print_r($shift_policy); die;
		 
		/*
		|--------------------------------------------------------------------------
		| HRM_EMPLOYEE
		|--------------------------------------------------------------------------
		|
		*/
		$sqldata=sql_select("SELECT EMP_CODE, DUTY_ROSTER_POLICY, COMPANY_ID, LOCATION_ID, SHIFT_POLICY, OVERTIME_POLICY, PUNCH_CARD_NO FROM HRM_EMPLOYEE WHERE STATUS_ACTIVE=1 AND IS_DELETED=0 AND SHIFT_POLICY!=0");	
		foreach($sqldata as $emp_dtls)
		{
			$employee_data[$emp_dtls['PUNCH_CARD_NO']]['emp_code']=$emp_dtls['EMP_CODE'];
			$employee_data[$emp_dtls['PUNCH_CARD_NO']]['duty_roster_policy']=$emp_dtls['DUTY_ROSTER_POLICY'];
			$employee_data[$emp_dtls['PUNCH_CARD_NO']]['company_id']=$emp_dtls['COMPANY_ID'];
			$employee_data[$emp_dtls['PUNCH_CARD_NO']]['location_id']=$emp_dtls['LOCATION_ID'];
			$employee_data[$emp_dtls['PUNCH_CARD_NO']]['shift_policy']=$emp_dtls['SHIFT_POLICY'];
			$employee_data[$emp_dtls['PUNCH_CARD_NO']]['overtime_policy']=$emp_dtls['OVERTIME_POLICY'];
			$employee_data[$emp_dtls['PUNCH_CARD_NO']]['punch_card_no']=$emp_dtls['PUNCH_CARD_NO'];
		}
		unset($sqldata);
		//print_r($employee_data); die;	

		/*
		|--------------------------------------------------------------------------
		| READER_CONFIGURATION
		|--------------------------------------------------------------------------
		|
		*/
		$sqldata5=sql_select("SELECT ID, COMPANY_NAME, USER_NAME_LIST, TOTAL_READER, READER_MODEL, DATABASE_TYPE, SERVER_NAME, DATABASE_NAME, TABLE_NAME, DB_USER_ID, DB_PASS_WD, RF_CODE_FLD, DATE_FLD, TIME_FLD, READER_NO_FLD, NETWORK_NO_FLD, STATUS_FLD, ID_FIELD, DATE_FORMAT, XCESS_CONTROLL, STATUS_ACTIVE, IS_DELETED, IS_LOCKED FROM READER_CONFIGURATION WHERE STATUS_ACTIVE=1 AND IS_DELETED=0 AND ID=".$reader_model." AND USER_NAME_LIST=".$_SESSION['logic_erp']['user_id']."");
		if(count($sqldata5)>0)
		{
			foreach($sqldata5 as $emp_dtls)
			{
				$database_type=$emp_dtls['DATABASE_TYPE'];
				$server_name=$emp_dtls['SERVER_NAME'];
				$database_name=$emp_dtls['DATABASE_NAME'];
				$table_name=$emp_dtls['TABLE_NAME'];
				$db_user_id=$emp_dtls['DB_USER_ID'];
				$db_pass_wd=$emp_dtls['DB_PASS_WD'];
				$company_name=$emp_dtls['COMPANY_NAME'];
				$rf_code_fld=$emp_dtls['RF_CODE_FLD'];
				$date_fld=$emp_dtls['DATE_FLD'];
				$time_fld=$emp_dtls['TIME_FLD'];
				$reader_no_fld=$emp_dtls['READER_NO_FLD'];
				$network_no_fld=$emp_dtls['NETWORK_NO_FLD'];
				$status_fld=$emp_dtls['STATUS_FLD'];
				$id_fld=$emp_dtls['ID_FIELD'];
				$date_format=$emp_dtls['DATE_FORMAT'];
				$xcess_controll=$emp_dtls['XCESS_CONTROLL'];
			}
		}
		else
		{
			echo "9";
			exit();
		}
		
		$id=return_next_id( "ID", "HRM_RAW_DATA_ATTND", 1 );
		$rid=return_next_id( "ID", "HRM_RAW_PUNCH_WRONG", 1 );
		$id=$id-1;
		$rid=$rid-1;
		 
		if ($database_type==4)
		{
			if( $server_name=="text")
			{
				$fh=fopen("download/employee_attendance_file.txt","r");
				$k=0;
				while(!feof($fh)) 
				{
					$k++;
					$line_of_text = fgets($fh);
					$raw_text=explode("**",get_formatted_data_text( $line_of_text ));
					
					$emp=$employee_data[$raw_text[0]]['emp_code'];
					$raw_text[2]="to_date('".$raw_text[1]." ".$raw_text[2]."','DD MONTH YYYY HH24:MI:SS')";
					//echo $raw_text[0]."==".$raw_text[1]."==".$raw_text[2]."==".$raw_text[3]; die;
					
					if( ($employee_data[$raw_text[0]]['duty_roster_policy']*1)!=0 )
					{
						$shift=$roster_emp_shift[$emp][date("Y-m-d",strtotime($raw_text[1]))]['shift_policy_id'];
						$ot_policy=$roster_emp_shift[$emp][date("Y-m-d",strtotime($raw_text[1]))]['overtime_policy_id'];
					}
					else
					{
						$shift=$employee_data[$raw_text[0]]['shift_policy'];
						$ot_policy=$employee_data[$raw_text[0]]['overtime_policy'];
					}
					
					$shift_start="to_date('".date("d-M-Y H:i:s",strtotime($raw_text[1]." ".$shift_policy[$shift]['shift_start']))."','DD MONTH YYYY HH24:MI:SS')";
					$shift_end="to_date('".date("d-M-Y H:i:s",strtotime($raw_text[1]." ".$shift_policy[$shift]['shift_end']))."','DD MONTH YYYY HH24:MI:SS')";
					if($shift_policy[$shift]['cross_date']==1)
					{
						$date_zs=add_date(date("Y-m-d",strtotime($raw_text[1])),1);
						$shift_end="to_date('".date("d-M-Y H:i:s",strtotime($date_zs." ".$shift_policy[$shift]['shift_end']))."','DD MONTH YYYY HH24:MI:SS')";
					}
					
					$company_id=$employee_data[$raw_text[0]]['company_id'];
					$location_id=$employee_data[$raw_text[0]]['location_id'];
					$grace_minutes=$shift_policy[$shift]['grace_minutes'];
					$exit_buffer_minutes=$shift_policy[$shift]['exit_buffer_minutes'];
					$entry_restriction_start=$shift_policy[$shift]['entry_restriction_start'];

					if( $shift*1!=0 && $raw_text[1]!="1970-01-01")
					{
						if($raw_text[0]!='0000000000')
						{
							$id++;
							$sql_part[]="( $id,$emp,'".$raw_text[1]."',".$raw_text[2].",'".$shift."','".$shift_policy[$shift]['shift_type']."','".$shift_policy[$shift]['cross_date']."',".$shift_start.",".$shift_end.",'".$ot_policy."','".$raw_text[3]."','".$raw_text[0]."','".$company_id."','".$location_id."','".$grace_minutes."','".$exit_buffer_minutes."','".$entry_restriction_start."')";
						}
					}
					else
					{
						if($raw_text[0]!='0000000000')
						{
							if( $raw_text[1]!='1970-01-01' && $raw_text[1]!='01-Jan-1970')
							{
								if(strlen($raw_text[0])>9)
								{
									$rid++;
									$data_array_wrong[]="($rid,'".$raw_text[0]."','".$raw_text[1]."',".$raw_text[2].",'".$raw_text[3]."')";
								}
							}
						}
					}
				}
			}
			else if($server_name=="exp")
			{
				$k=0;
				$fh = fopen( "download/employee_attendance_file.txt","r");
				while (!feof($fh) ) 
				{
					$k++;
					$line_of_text = fgets($fh);
					
					$line_of_text = preg_replace('/\s+/', ' ', $line_of_text);
					
					$line_of_text=str_replace(" ","__",$line_of_text);
					$raw_text=explode("**",get_formatted_data_exp( $line_of_text ));
					//echo $database_name;
					/*echo "<pre>";
					print_r($raw_text);*/
					$emp=$employee_data[$raw_text[0]]['emp_code'];
					$raw_text[2]="to_date('".$raw_text[1]." ".$raw_text[2]."','DD MONTH YYYY HH24:MI:SS')";
					//echo $raw_text[0]."==".$raw_text[1]."==".$raw_text[2]."==".$raw_text[3]; die;
					
					if( ($employee_data[$raw_text[0]]['duty_roster_policy']*1)!=0 )
					{
						$shift=$roster_emp_shift[$emp][date("Y-m-d",strtotime($raw_text[1]))]['shift_policy_id'];
						$ot_policy=$roster_emp_shift[$emp][date("Y-m-d",strtotime($raw_text[1]))]['overtime_policy_id'];
					}
					else
					{
						$shift=$employee_data[$raw_text[0]]['shift_policy'];
						$ot_policy=$employee_data[$raw_text[0]]['overtime_policy'];
					}
					
					$shift_start="to_date('".date("d-M-Y H:i:s",strtotime($raw_text[1]." ".$shift_policy[$shift]['shift_start']))."','DD MONTH YYYY HH24:MI:SS')";
					$shift_end="to_date('".date("d-M-Y H:i:s",strtotime($raw_text[1]." ".$shift_policy[$shift]['shift_end']))."','DD MONTH YYYY HH24:MI:SS')";
					if($shift_policy[$shift]['cross_date']==1)
					{
						$date_zs=add_date(date("Y-m-d",strtotime($raw_text[1])),1);
						$shift_end="to_date('".date("d-M-Y H:i:s",strtotime($date_zs." ".$shift_policy[$shift]['shift_end']))."','DD MONTH YYYY HH24:MI:SS')";
					}
					
					$company_id=$employee_data[$raw_text[0]]['company_id'];
					$location_id=$employee_data[$raw_text[0]]['location_id'];
					$grace_minutes=$shift_policy[$shift]['grace_minutes'];
					$exit_buffer_minutes=$shift_policy[$shift]['exit_buffer_minutes'];
					$entry_restriction_start=$shift_policy[$shift]['entry_restriction_start'];

					if( $shift*1!=0 && $raw_text[1]!="1970-01-01")
					{
						if($raw_text[0]!='0000000000')
						{
							$id++;
							$sql_part[]="( $id,$emp,'".$raw_text[1]."',".$raw_text[2].",'".$shift."','".$shift_policy[$shift]['shift_type']."','".$shift_policy[$shift]['cross_date']."',".$shift_start.",".$shift_end.",'".$ot_policy."','".$raw_text[3]."','".$raw_text[0]."','".$company_id."','".$location_id."','".$grace_minutes."','".$exit_buffer_minutes."','".$entry_restriction_start."')";
						}
					}
					else
					{
						if($raw_text[0]!='0000000000')
						{
							if( $raw_text[1]!='1970-01-01' && $raw_text[1]!='01-Jan-1970')
							{
								if(strlen($raw_text[0])>9)
								{
									$rid++;
									$data_array_wrong[]="($rid,'".$raw_text[0]."','".$raw_text[1]."',".$raw_text[2].",'".$raw_text[3]."')";
								}
							}
						}
					}
				}
				/*echo "<pre>";
				print_r($sql_part);*/
			}
			else if( $server_name=="xls")
			{
				if($database_name=="bpkw")
				{
					//echo $rf_code_fld."=="; die;
					$n_rf_code_fld=explode(",",$rf_code_fld);
					$n_date_fld=explode(",",$date_fld);
					$n_time_fld=explode(",",$time_fld);

					$rf_code_fld=0;
					$date_fld=1;
					$time_fld=2;
					
					$files = glob('download/*'); // get all file names
					foreach($files as $file)// iterate files
					{
						/* -----------  Instruction for Reader Configuration --------------------
							for Date Field: 1,5,10 (Column INdex, starting point, length )
							for Date Field: 1,11,6 (Column INdex, starting point, length ) for single field date and time data
						*/
						$data = new Spreadsheet_Excel_Reader($file);
						for ($i = 2; $i <= $data->sheets[0]['numRows']; $i++) 
						{
							$card_colum=$data->sheets[0]['cells'][$i][$n_rf_code_fld[0]];
							$date_fld2=$data->sheets[0]['cells'][$i][$date_fld];
							$in_out_time=$data->sheets[0]['cells'][$i][$n_time_fld[0]].",".$data->sheets[0]['cells'][$i][$n_time_fld[1]];
							
							$in_out_time_arr=explode(",",$in_out_time);
							foreach($in_out_time_arr as $time)
							{
								if($time!="00:00" && $time!="0:00")
								{
									$line_of_text=$card_colum."__".$date_fld2."__".$time;
									$raw_text=explode("**",get_formatted_data_exp( $line_of_text ));
									$emp=$employee_data[$raw_text[0]]['emp_code'];
									$raw_text[2]="to_date('".$raw_text[1]." ".$raw_text[2]."','DD MONTH YYYY HH24:MI:SS')";
									//echo $raw_text[0]."==".$raw_text[1]."==".$raw_text[2]."==".$raw_text[3]; die;
									
									if( ($employee_data[$raw_text[0]]['duty_roster_policy']*1)!=0 )
									{
										$shift=$roster_emp_shift[$emp][date("Y-m-d",strtotime($raw_text[1]))]['shift_policy_id'];
										$ot_policy=$roster_emp_shift[$emp][date("Y-m-d",strtotime($raw_text[1]))]['overtime_policy_id'];
									}
									else
									{
										$shift=$employee_data[$raw_text[0]]['shift_policy'];
										$ot_policy=$employee_data[$raw_text[0]]['overtime_policy'];
									}
									
									$shift_start="to_date('".date("d-M-Y H:i:s",strtotime($raw_text[1]." ".$shift_policy[$shift]['shift_start']))."','DD MONTH YYYY HH24:MI:SS')";
									$shift_end="to_date('".date("d-M-Y H:i:s",strtotime($raw_text[1]." ".$shift_policy[$shift]['shift_end']))."','DD MONTH YYYY HH24:MI:SS')";
									if($shift_policy[$shift]['cross_date']==1)
									{
										$date_zs=add_date(date("Y-m-d",strtotime($raw_text[1])),1);
										$shift_end="to_date('".date("d-M-Y H:i:s",strtotime($date_zs." ".$shift_policy[$shift]['shift_end']))."','DD MONTH YYYY HH24:MI:SS')";
									}
									
									$company_id=$employee_data[$raw_text[0]]['company_id'];
									$location_id=$employee_data[$raw_text[0]]['location_id'];
									$grace_minutes=$shift_policy[$shift]['grace_minutes'];
									$exit_buffer_minutes=$shift_policy[$shift]['exit_buffer_minutes'];
									$entry_restriction_start=$shift_policy[$shift]['entry_restriction_start'];
				
									if( $shift*1!=0 && $raw_text[1]!="1970-01-01")
									{
										if($raw_text[0]!='0000000000')
										{
											$id++;
											$sql_part[]="( $id,$emp,'".$raw_text[1]."',".$raw_text[2].",'".$shift."','".$shift_policy[$shift]['shift_type']."','".$shift_policy[$shift]['cross_date']."',".$shift_start.",".$shift_end.",'".$ot_policy."','".$raw_text[3]."','".$raw_text[0]."','".$company_id."','".$location_id."','".$grace_minutes."','".$exit_buffer_minutes."','".$entry_restriction_start."')";
										}
									}
									else
									{
										if($raw_text[0]!='0000000000')
										{
											if( $raw_text[1]!='1970-01-01' && $raw_text[1]!='01-Jan-1970')
											{
												if(strlen($raw_text[0])>9)
												{
													$rid++;
													$data_array_wrong[]="($rid,'".$raw_text[0]."','".$raw_text[1]."',".$raw_text[2].",'".$raw_text[3]."')";
												}
											}
										}
									}
								}
							}
						} // End For Loop for ROw Iteration
					}
				}
				else if($database_name=="taj")
				{
					$n_rf_code_fld=explode(",",$rf_code_fld);
					$n_date_fld=explode(",",$date_fld);
					$n_time_fld=explode(",",$time_fld);
					
					$rf_code_fld=0;
					$date_fld=1;
					$time_fld=2;
					
					$files = glob('download/*'); // get all file names
					foreach($files as $file)// iterate files
					{
						/* -----------  Instruction for Reader Configuration --------------------
							for Date Field: 1,5,10 (Column INdex, starting point, length )
							for Date Field: 1,11,6 (Column INdex, starting point, length ) for single field date and time data
						*/
						$exp_file=explode(".",$file);
						$inputFileName = $file; 
						try {
							$objPHPExcel = PHPExcel_IOFactory::load($inputFileName);
						} catch(Exception $e) {
							die('Error loading file "'.pathinfo($inputFileName,PATHINFO_BASENAME).'": '.$e->getMessage());
						}
						$sheetData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
						$row_count = count($sheetData);
						for ($i=2; $i<=$row_count; $i++) 
						{
							$PUNCH_CARD_NO 	= trim($sheetData[$i][A]);
							$ATTND_DATE     = trim($sheetData[$i][D]);
							$PUNCH_TIME     = trim($sheetData[$i][E]);
							if ($PUNCH_TIME!="") {
								$PUNCH_TIME 	= preg_replace('/\s+/', ' ', $PUNCH_TIME);
								$punch_time 	= explode(" ", $PUNCH_TIME);
								$punch_count = count($punch_time);
								
								for ($j=0; $j <$punch_count ; $j++) { 
									
									//echo $PUNCH_CARD_NO."==".$ATTND_DATE."==".$punch_time[$j]."<br/>";
									
									$line_of_text=$PUNCH_CARD_NO."__".$ATTND_DATE."__".$punch_time[$j];
									$raw_text=explode("**",get_formatted_data_exp( $line_of_text ));
									$raw_text[2]="to_date('".$raw_text[1]." ".$raw_text[2]."','DD MONTH YYYY HH24:MI:SS')";

									if( ($employee_data[$raw_text[0]]['duty_roster_policy']*1)!=0 )
									{
										$shift=$roster_emp_shift[$emp][date("Y-m-d",strtotime($raw_text[1]))]['shift_policy_id'];
										$ot_policy=$roster_emp_shift[$emp][date("Y-m-d",strtotime($raw_text[1]))]['overtime_policy_id'];
									}
									else
									{
										$shift=$employee_data[$raw_text[0]]['shift_policy'];
										$ot_policy=$employee_data[$raw_text[0]]['overtime_policy'];
									}

									$shift_start="to_date('".date("d-M-Y H:i:s",strtotime($raw_text[1]." ".$shift_policy[$shift]['shift_start']))."','DD MONTH YYYY HH24:MI:SS')";
									$shift_end="to_date('".date("d-M-Y H:i:s",strtotime($raw_text[1]." ".$shift_policy[$shift]['shift_end']))."','DD MONTH YYYY HH24:MI:SS')";
									if($shift_policy[$shift]['cross_date']==1)
									{
										$date_zs=add_date(date("Y-m-d",$raw_text[1]),1);
										$shift_end="to_date('".date("d-M-Y H:i:s",strtotime($date_zs." ".$shift_policy[$shift]['shift_end']))."','DD MONTH YYYY HH24:MI:SS')";
									}
									
									$company_id=$employee_data[$raw_text[0]]['company_id'];
									$location_id=$employee_data[$raw_text[0]]['location_id'];
									$grace_minutes=$shift_policy[$shift]['grace_minutes'];
									$exit_buffer_minutes=$shift_policy[$shift]['exit_buffer_minutes'];
									$entry_restriction_start=$shift_policy[$shift]['entry_restriction_start'];

									if( $shift*1!=0 && $raw_text[1]!="1970-01-01")
									{
										if($raw_text[0]!='0000000000')
										{
											$id++;
											$sql_part[]="( $id,$emp,'".$raw_text[1]."',".$raw_text[2].",'".$shift."','".$shift_policy[$shift]['shift_type']."','".$shift_policy[$shift]['cross_date']."',".$shift_start.",".$shift_end.",'".$ot_policy."','".$raw_text[3]."','".$raw_text[0]."','".$company_id."','".$location_id."','".$grace_minutes."','".$exit_buffer_minutes."','".$entry_restriction_start."')";
										}
									}
									else
									{
										if($raw_text[0]!='0000000000')
										{
											if( $raw_text[1]!='1970-01-01' && $raw_text[1]!='01-Jan-1970')
											{
												if(strlen($raw_text[0])>9)
												{
													$rid++;
													$data_array_wrong[]="($rid,'".$raw_text[0]."','".$raw_text[1]."',".$raw_text[2].",'".$raw_text[3]."')";
												}
											}
										}
									}
								}
							}
							
						}
						
					}
				}
				else
				{
					$n_rf_code_fld=explode(",",$rf_code_fld);
					$n_date_fld=explode(",",$date_fld);
					$n_time_fld=explode(",",$time_fld);
					
					$rf_code_fld=0;
					$date_fld=1;
					$time_fld=2;
					
					$files = glob('download/*'); // get all file names
					foreach($files as $file)// iterate files
					{
						/* -----------  Instruction for Reader Configuration --------------------
							for Date Field: 1,5,10 (Column INdex, starting point, length )
							for Date Field: 1,11,6 (Column INdex, starting point, length ) for single field date and time data
						*/
						$data = new Spreadsheet_Excel_Reader($file);
						for ($i = 1; $i <= $data->sheets[0]['numRows']; $i++) 
						{
							$k++;
							$card_colum=$data->sheets[0]['cells'][$i][$n_rf_code_fld[0]];
							$date_fld2=$data->sheets[0]['cells'][$i][$n_date_fld[0]];
							$in_out_time=$data->sheets[0]['cells'][$i][$n_time_fld[0]];
							
							if( $n_date_fld[1]!='' )
							{
								$date_fld2=substr($date_fld2,$n_date_fld[1],$n_date_fld[2]);
							}
							if( $n_time_fld[1]!='' )
							{
								$in_out_time=substr($in_out_time,$n_time_fld[1],$n_time_fld[2]);
							}
							
							//mm-dd-yyyy
							// echo $date_fld2."=".$date_format."=".get_new_date_format( $date_fld2,2);
							// die;
							$line_of_text=$card_colum."__".$date_fld2."__".$in_out_time;
							$raw_text=explode("**",get_formatted_data_exp( $line_of_text ));
							$emp=$employee_data[$raw_text[0]]['emp_code'];
							$raw_text[2]="to_date('".$raw_text[1]." ".$raw_text[2]."','DD MONTH YYYY HH24:MI:SS')";
							//echo $raw_text[0]."==".$raw_text[1]."==".$raw_text[2]."==".$raw_text[3]; die;
							
							if( ($employee_data[$raw_text[0]]['duty_roster_policy']*1)!=0 )
							{
								$shift=$roster_emp_shift[$emp][date("Y-m-d",strtotime($raw_text[1]))]['shift_policy_id'];
								$ot_policy=$roster_emp_shift[$emp][date("Y-m-d",strtotime($raw_text[1]))]['overtime_policy_id'];
							}
							else
							{
								$shift=$employee_data[$raw_text[0]]['shift_policy'];
								$ot_policy=$employee_data[$raw_text[0]]['overtime_policy'];
							}
							
							$shift_start="to_date('".date("d-M-Y H:i:s",strtotime($raw_text[1]." ".$shift_policy[$shift]['shift_start']))."','DD MONTH YYYY HH24:MI:SS')";
							$shift_end="to_date('".date("d-M-Y H:i:s",strtotime($raw_text[1]." ".$shift_policy[$shift]['shift_end']))."','DD MONTH YYYY HH24:MI:SS')";
							if($shift_policy[$shift]['cross_date']==1)
							{
								$date_zs=add_date(date("Y-m-d",$raw_text[1]),1);
								$shift_end="to_date('".date("d-M-Y H:i:s",strtotime($date_zs." ".$shift_policy[$shift]['shift_end']))."','DD MONTH YYYY HH24:MI:SS')";
							}
							
							$company_id=$employee_data[$raw_text[0]]['company_id'];
							$location_id=$employee_data[$raw_text[0]]['location_id'];
							$grace_minutes=$shift_policy[$shift]['grace_minutes'];
							$exit_buffer_minutes=$shift_policy[$shift]['exit_buffer_minutes'];
							$entry_restriction_start=$shift_policy[$shift]['entry_restriction_start'];
		
							if( $shift*1!=0 && $raw_text[1]!="1970-01-01")
							{
								if($raw_text[0]!='0000000000')
								{
									$id++;
									$sql_part[]="( $id,$emp,'".$raw_text[1]."',".$raw_text[2].",'".$shift."','".$shift_policy[$shift]['shift_type']."','".$shift_policy[$shift]['cross_date']."',".$shift_start.",".$shift_end.",'".$ot_policy."','".$raw_text[3]."','".$raw_text[0]."','".$company_id."','".$location_id."','".$grace_minutes."','".$exit_buffer_minutes."','".$entry_restriction_start."')";
								}
							}
							else
							{
								if($raw_text[0]!='0000000000')
								{
									if( $raw_text[1]!='1970-01-01' && $raw_text[1]!='01-Jan-1970')
									{
										if(strlen($raw_text[0])>9)
										{
											$rid++;
											$data_array_wrong[]="($rid,'".$raw_text[0]."','".$raw_text[1]."',".$raw_text[2].",'".$raw_text[3]."')";
										}
									}
								}
							}
						} // End For Loop for ROw Iteration
					}
				}
				/*print_r($data_array_wrong);
				print_r($sql_part); die;
				echo "su..re"; die;*/
			}
		}
		//print_r($sql_part); die;
		$field_list="ID,EMP_CODE,DTIME,CTIME,SHIFT_POLICY_ID,SHIFT_TYPE,IS_NEXT_DAY,SHIFT_START,SHIFT_END,OVERTIME_POLICY_ID,PUNCH_TYPE,CID,COMPANY_ID,LOCATION_ID,GRACE_MINUTES,EXIT_BUFFER_MINUTES,ENTRY_RESTRICTION_START";
		$field_list_wrong="ID,CID,DTIME,CTIME,PUNCH_TYPE";
		
		if(count($data_array_wrong)!=0)
		{
			 $rID_wrong=sql_insert("hrm_raw_punch_wrong",$field_list_wrong,$data_array_wrong,1);
		}
		else 
		{
			$rID_wrong=1;
		}
		
		if(count($sql_part)!=0)
		{
			$rID=sql_insert("hrm_raw_data_attnd",$field_list,$sql_part,1);
		}
		else
		{ 
			$rID=1;
		}
		if($db_type==0)
		{
			if($rID && $rID_wrong){
				mysql_query("COMMIT");  
				echo "0**".$rID."**".$rID1;
			}
			else{
				mysql_query("ROLLBACK"); 
				echo "10**".$rID;
			}
		}
		if($db_type==2 || $db_type==1 )
		{
			if($rID && $rID_wrong)
			{
				oci_commit($con);   
				echo "0**".$rID."**".$rID_wrong;
			}
			else
			{
				oci_rollback($con);
				echo "10**".$rID;
			}
		}
		disconnect($con);
		die;	
	}
}

function get_formatted_data_text( $trow )
{
	global $rf_code_fld;
	global $date_fld;
	global $time_fld;
	global $date_format;
	global $xcess_controll;
	global $db_type;
	
	$rf_code_fld_exp=explode(",",$rf_code_fld);
	$date_fld_exp=explode(",",$date_fld);
	$time_fld_exp=explode(",",$time_fld);

	$xcess_controll_exp=explode(",",$xcess_controll);
	
	$rf_code=str_pad( trim(substr($trow,$rf_code_fld_exp[0],$rf_code_fld_exp[1])),10,"0",STR_PAD_LEFT );
	
	if($db_type==2)
		$tdate=change_date_format( get_new_date_format( substr($trow,$date_fld_exp[0],$date_fld_exp[1]),$date_format),'','',1);
	else
		$tdate=change_date_format(get_new_date_format( substr($trow,$date_fld_exp[0],$date_fld_exp[1]),$date_format),"yyyy-mm-dd", "-");
	//echo $tdate."==";
	//return  $rf_code."**".substr($trow,$date_fld_exp[0],$date_fld_exp[1])."**".substr($trow,$time_fld_exp[0],$time_fld_exp[1]);
	$abc = trim(substr($trow,$time_fld_exp[0],$time_fld_exp[1]));

	$time_val= explode(" ",trim(substr($trow,$time_fld_exp[0],$time_fld_exp[1])));
	//print_r($time_val);
	if( $time_val[0]=="00:00:00" || $time_val[0]=="00:00" ) $time_val[0]="00:00:05";
	
	if( trim($time_val[1])=="AM" || trim($time_val[1])=="PM")
	{
		$new_time=date("H:i:s", strtotime( $time_val[0]." ".trim($time_val[1])."" ));
	}
	else
	{
		$new_time=date("H:i:s", strtotime($time_val[0]));
	}

	$xcess_controlled=explode(" ",substr($trow, $xcess_controll_exp[0], $xcess_controll_exp[1]));
	if(trim($xcess_controlled[0])=="Entrance" || trim($xcess_controlled[0])==1) $punch_type=1; 
	else if(trim($xcess_controlled[0])=="Exit" || trim($xcess_controlled[0])==2) $punch_type=2; 
	else $punch_type=0;
	//echo $rf_code."**".$tdate."**".$new_time."**".$punch_type;
	return  $rf_code."**".$tdate."**".$new_time."**".$punch_type;
}

function get_formatted_data_exp( $trow )
{
	global $rf_code_fld;
	global $date_fld;
	global $time_fld;
	global $date_format;
	global $xcess_controll;
	//global $database_name;
	global $db_type;
	
	$trow=explode("__",$trow);

	$rf_code_fld_exp=trim($trow[$rf_code_fld]);
	$date_fld_exp=$trow[$date_fld];
	$time_fld_exp=$trow[$time_fld];
	$xcess_controll_exp=$trow[$xcess_controll];
	
	$rf_code=str_pad($rf_code_fld_exp,10,"0",STR_PAD_LEFT);
	
	if($db_type==2) $tdate=change_date_format( get_new_date_format( $date_fld_exp,$date_format),'','',1);
	else $tdate=change_date_format(get_new_date_format( $date_fld_exp,$date_format),"yyyy-mm-dd", "-");
	
	$time_val= explode(" ",$time_fld_exp);
	if( $time_val[0]=="00:00:00" || $time_val[0]=="00:00" ) $time_val[0]="00:00:05";
	
	if( trim($time_val[1])=="AM" || trim($time_val[1])=="PM")
	{	
		$new_time=date("H:i:s", strtotime( $time_val[0]." ".trim($time_val[1])."" ));
	}
	else
	{	
		$new_time=date("H:i:s", strtotime($time_val[0]));
		 
	}
	$xcess_controlled=explode(" ",$xcess_controll_exp);
	if(trim($xcess_controlled[0])=="Entrance" || trim($xcess_controlled[0])==1) $punch_type=1; 
	else if(trim($xcess_controlled[0])=="Exit" || trim($xcess_controlled[0])==2) $punch_type=2; 
	else $punch_type=0;
	
	return  $rf_code."**".$tdate."**".$new_time."**".$punch_type;
}

function get_new_date_format( $date, $date_format )
{
	if( $date_format==2 ) ///dd/mm/yyyy
	{
		$dpart=split("/",$date);
		//return $dpart[2]."-".str_pad( $dpart[0], 2 , "0", STR_PAD_LEFT )."-".str_pad( $dpart[1], 2 , "0", STR_PAD_LEFT );
		return date("Y-m-d", strtotime( $dpart[2]."-".str_pad( $dpart[1], 2 , "0", STR_PAD_LEFT )."-".str_pad( $dpart[0], 2 , "0", STR_PAD_LEFT ) ));
	}
	else if ($date_format==6)  //ddmmyy
	{
		return date("Y-m-d", strtotime( str_pad( substr($date,4,2), 4 , "20", STR_PAD_LEFT )."-".str_pad( substr($date,2,2), 2 , "0", STR_PAD_LEFT )."-".str_pad( substr($date,0,2), 2 , "0", STR_PAD_LEFT )) );
	}
	else if ($date_format==7) ///ddmmyyyy
	{
		return date("Y-m-d", strtotime(substr($date,4,4)."-".str_pad( substr($date,2,2), 2 , "0", STR_PAD_LEFT )."-".str_pad( substr($date,0,2), 2 , "0", STR_PAD_LEFT ) ));
	}
	else if( $date_format==8 ) //mm-dd-yyyy
	{
		$dpart=split("-",$date);
		return date("Y-m-d", strtotime( $dpart[2]."-".str_pad( $dpart[0], 2 , "0", STR_PAD_LEFT )."-".str_pad( $dpart[1], 2 , "0", STR_PAD_LEFT )) );
	}
	else if ($date_format==9)  //yymmdd
	{
		return date("Y-m-d", strtotime(str_pad( substr($date,0,2), 4 , "20", STR_PAD_LEFT )."-".str_pad( substr($date,2,2), 2 , "0", STR_PAD_LEFT )."-".str_pad( substr($date,4,2), 2 , "0", STR_PAD_LEFT ) ));
	}
	else if( $date_format==10 ) ///mm/dd/yyyy  18/04/2016
	{
		$dpart=split("/",$date);
		return date("Y-m-d", strtotime($dpart[2]."-".str_pad( $dpart[1], 2 , "0", STR_PAD_LEFT )."-".str_pad( $dpart[0], 2 , "0", STR_PAD_LEFT ) ));
	}
	
	else
	{
		return date("Y-m-d",strtotime($date));
	}
}	
?>