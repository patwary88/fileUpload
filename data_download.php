<?php
/******************************************************************
|	Purpose			:	This form will create data download
|	Functionality	:	
|	JS Functions	:
|	Created by		:	Md. Nuruzzaman
|	Creation date 	:	11.05.2018
|	Updated by 		:
|	Update date		:
|	QC Performed BY	:		
|	QC Date			:	
|	Comments		:
*******************************************************************/

		session_start();
		if( $_SESSION['logic_erp']['user_id'] == "" ) header("location:login.php");
		require_once('../includes/common.php');
		extract($_REQUEST);
		$_SESSION['page_permission']=$permission; 
		echo load_html_head_contents("Data Download / Syncronization", "../", 1, 1,$unicode,'1','');
		$userid=$_SESSION['logic_erp']['user_id'];	
		?>
		<script type="text/javascript" charset="utf-8">
			if( $('#index_page', window.parent.document).val()!=1) window.location.href = "../logout.php"; 
			var permission='<?php echo $permission; ?>';
			
			function fn_file_browse(db_id)
			{
				//alert(db_id); return;
				var split_db_id = db_id.split('_');		
				if(split_db_id[1]==4)
				{  
					$('#tr_file_upload').removeAttr('style');
				}
				else
				{
					$('#tr_file_upload').attr('style','display:none');
				}
			}
			
			function fnc_data_download( operation )
			{
				if(form_validation('cbo_reader_model','reader model')==false)
				{
					return;
				}
											
				if(document.getElementById('all_data').checked==true)
				{
					var all_data=1;
				}
				else
				{
					var all_data=0;
				}	
				
				var data="action=save_update_delete&operation="+operation+"&all_data="+all_data+get_submitted_data_string('cbo_reader_model',"../");
				freeze_window(operation);
				http.open("POST","requires/data_download_controller.php",true);
				http.setRequestHeader("Content-type","application/x-www-form-urlencoded");
				http.send(data);
				http.onreadystatechange =fnc_on_submit_reponse;
			}
			
			//fnc_on_submit_reponse
			function fnc_on_submit_reponse()
			{
				if(http.readyState == 4) 
				{
					//alert(http.responseText); return;
					var reponse=http.responseText.split('**');
					show_msg(trim(reponse[0]));
					release_freezing();
				}
			}		
	
			function fnc_file_uploader ()
			{
				//alert("su..re"); return;
				if (form_validation('cbo_reader_model','reader model')==false)
				{
					return;
				}
				//alert($('#txt_emp_code').val());
				var file_type=2;
				emailwindow=dhtmlmodal.open('EmailBox', 'iframe', 'requires/data_download_controller.php?action=file_uploader'+'&cbo_reader_model='+$('#cbo_reader_model').val()+'&file_type='+file_type, 'File Uploader', 'width=400px,height=280px,center=1,resize=0,scrolling=0', '')
				emailwindow.onclose=function()
				{
				}
			}	
		</script>
	</head>
	<body onLoad="set_hotkey()">
        <div align="center" style="width:100%;">  
            <?php echo load_freeze_divs ("../",$permission); ?> 
            <form  id="datadownloadform_1"  name="datadownloadform_1" autocomplete="off" enctype="multipart/form-data" >
                <fieldset style="width:500px;">
                    <legend>Data Download</legend>
                    <table width="250" border="0" cellpadding="0" cellspacing="2">
                        <tr>
                            <td colspan="2"><input type="checkbox" name="all_data" id="all_data" />&nbsp;Include old Data in this Download</td>
                        </tr>
                        <tr>
                            <td width="65" align="right">Reader Model</td>
                            <td width="185" align="left"><?php echo create_drop_down( "cbo_reader_model",185, "SELECT ID, USER_NAME_LIST, TOTAL_READER, READER_MODEL, DATABASE_TYPE, SERVER_NAME,(ID || '_' || DATABASE_TYPE) AS IDS FROM READER_CONFIGURATION WHERE IS_DELETED=0 AND STATUS_ACTIVE=1 AND USER_NAME_LIST=".$userid." ORDER BY READER_MODEL", "iDS,READER_MODEL", 1, '--Select--', 0,"fn_file_browse(this.value);"); ?></td>
                        </tr>
                        <tr id="tr_file_upload" style="display:none">
                            <td align="right">File</td>
                            <td height="20" align="left" class="image_uploader" onClick="fnc_file_uploader()"><strong>Upload File</strong></td>
                        </tr>
                        <tr style="display:none">
                            <td>Downlaod Date From</td>
                            <td><input type="text" name="txt_date_from" id="txt_date_from" class="datepicker" value="" style="width:100px;" /></td>
                            <td>Downlaod Date To</td>
                            <td><input type="text" name="txt_date_to" id="txt_date_to" class="datepicker" value="" style="width:100px;" /></td>
                        </tr>
                        <tr>
                            <td colspan="2" height="60" valign="middle" align="center"><input type="button" name="save_d" id="save_d" value="Start Download/Syncronize" class="formbutton" style="width:250px; height:45px"  onClick="fnc_data_download(0);"/>
                            </td>
                        </tr>
                    </table>
                </fieldset>
            </form>
        </div>
    </body>
	<script src="../includes/functions_bottom.js" type="text/javascript"></script>
</html>