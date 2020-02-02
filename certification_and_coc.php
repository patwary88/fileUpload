<?php
/****************************************************************
|   Purpose         :   This form will create Legal License
|   Functionality   :   
|   JS Functions    :
|   Created by      :   Main Uddin Patwary
|   Creation date   :   01.07.2019
|   Updated by      :       
|   Update date     :    
|   QC Performed BY :       
|   QC Date         :   
|   Comments        :
******************************************************************/

    session_start();
    if( $_SESSION['logic_erp']['user_id'] == "" ) header("location:login.php");
    require_once('../../includes/common.php');
    extract($_REQUEST);
    $_SESSION['page_permission']=$permission; 
    echo load_html_head_contents("Company Details", "../../", 1, 1,$unicode,'','');
    ?>
    <script type="text/javascript">
        var permission='<?php echo $permission; ?>';

        function certification_coc_list_view(){
           
            var page_link="requires/certification_and_coc_controller.php?action=certification_coc_list_new";
            emailwindow=dhtmlmodal.open('EmailBox', 'iframe', page_link, 'License Info List View', 'width=1000px,height=400px,center=1,resize=1,scrolling=0','../');
           
            emailwindow.onclose=function()
            {
                var theemail=this.contentDoc.getElementById("selected_prod_row").value;
                var ref_data = theemail.split('_');
                get_php_form_data(ref_data[0], "show_listview", "requires/certification_and_coc_controller" );
            }
        }

        function fnc_certification_coc(operation){

            if (form_validation('cbo_company_id','Company')==false)
                {
                    return;
                }
                else
                {
                    //alert (operation); return;
                    var data="action=save_update_delete&operation="+operation+get_submitted_data_string('cbo_company_id*cbo_location_id*cbo_applicable_buyer*cbo_audit_type*txt_last_audit_date*txt_audit_expire_date*txt_audited_by*txt_auditor_rating*cbo_audit_status*txt_remark*cbo_file_type*update_id',"../../");
                    //alert(data); return;
                    freeze_window(operation);
                    http.open("POST","requires/certification_and_coc_controller.php",true);
                    http.setRequestHeader("Content-type","application/x-www-form-urlencoded");
                    http.send(data);
                    http.onreadystatechange = fnc_on_submit_reponse;
                }
        }

        //fnc_on_submit_reponse
        function fnc_on_submit_reponse()
        {
            if(http.readyState == 4) 
            {
                var reponse=http.responseText.split('**');
                show_msg(trim(reponse[0]));
                release_freezing();
                reset_form('certificationcoc_1','','');
               set_button_status(0, permission, 'fnc_certification_coc',1);
            }
        }

        function fnc_file_uploader ()
        {
            //alert("su..re"); return;
            if (form_validation('cbo_company_id*cbo_audit_status*cbo_file_type','Company*Audit Status*File Type')==false)
            {
                return;
            }
            //alert($('#update_id').val()); return;
            emailwindow=dhtmlmodal.open('EmailBox', 'iframe', 'requires/certification_and_coc_controller.php?action=file_uploader'+'&cbo_company_id='+$('#cbo_company_id').val()+'&cbo_location_id='+$('#cbo_location_id').val()+'&cbo_audit_type='+$('#cbo_audit_type').val()+'&cbo_audit_status='+$('#cbo_audit_status').val()+'&cbo_file_type='+$('#cbo_file_type').val()+'&update_id='+$('#update_id').val(), 'File Uploader', 'width=640px,height=330px,center=1,resize=0,scrolling=0', '../')
            emailwindow.onclose=function()
            {
            }
        }

    </script>
    </head>
    <body onLoad="set_hotkey()">
        <div align="center"> 
            <?php echo load_freeze_divs ("../../",$permission); ?>
            <fieldset style="width:900px"><legend>Legal License Info <input type="text" onDblClick="certification_coc_list_view()" class="text_boxes" placeholder="Browse" title="Write To search Employee Code" style="width:140px;" /></legend>
                <form id="certificationcoc_1" name="certificationcoc_1" autocomplete="off">
                    <fieldset>
                        <table cellpadding="0" cellspacing="1" width="100%" align="center">
                            <tr>
                                <td width="200" align="right" class="must_entry_caption">Company</td>
                                <td width="170"><?php echo create_drop_down( 'cbo_company_id',170, return_array_from_array( $company_arr, 0, 'company_name'  ), '', 1, '--Select--', 0, $onchange_func ); ?></td>
                                <td width="200" align="right">Factory Address</td>
                                <td width="170"><? echo create_drop_down( "cbo_location_id",170, get_location_array_for_listview(), " ", 1, '--Select--', 0, $onchange_func); ?></td>
                                <td width="200" align="right">Buyer</td>
                                <td width="170"><?php echo create_drop_down( 'cbo_applicable_buyer',170,$buyer_arr, '', 1, '--Select--', 0, $onchange_func ); ?></td>
                            </tr>
                            <tr>
                                <td width="200" align="right">Audit Type</td>
                                <td width="170"><?php echo create_drop_down( 'cbo_audit_type',170,$audit_type_arr, '', 1, '--Select--', 0, $onchange_func ); ?></td>
                                <td width="200" align="right">Last Audit Date</td>
                                <td width="120"><input type="text" class="datepicker" name="txt_last_audit_date" id="txt_last_audit_date" style="width:160px;"/></td>
                                <td width="200" align="right">Audit Expire Date</td>
                                <td width="120"><input type="text" class="datepicker" name="txt_audit_expire_date" id="txt_audit_expire_date" style="width:160px;"/></td>
                            </tr>
                            <tr>
                                <td width="200" align="right">Audited By</td>
                                <td width="120"><input type="text" class="text_boxes" name="txt_audited_by" id="txt_audited_by" style="width:160px;"/></td>
                                <td width="200" align="right">Auditor Rating</td>
                                <td width="120"><input type="text" class="text_boxes" name="txt_auditor_rating" id="txt_auditor_rating" style="width:160px;"/></td>
                                 <td width="200" align="right" class="must_entry_caption">Audit Status</td>
                                <td width="120"><?php echo create_drop_down( 'cbo_audit_status',170,$audit_status, '', 1, '--Select--', 0, $onchange_func ); ?></td>
                            </tr>
                            <tr>
                                 <td width="200" align="right">Remark</td>
                                <td width="120"><input type="text" class="text_boxes" name="txt_remark" id="txt_remark" style="width:160px;"/></td>
                                <td width="200" align="right"> File Type</td>
                                <td width="120"><?php echo create_drop_down("cbo_file_type",170,$file_type,"", 0, '', 0, $onchange_func,0); ?></td>
                                <td width="200" align="right"></td>
                                <td width="120" valign="middle" align="center" class="image_uploader" onClick="fnc_file_uploader()"><strong>Upload File</strong></td>
                                <input type="hidden" id="update_id" name="update_id">
                            </tr>
                            <tr>
                                <td colspan="6" align="center" style="padding-top:10px;" class="button_container">
                                    <?php 
                                        echo load_submit_buttons( $permission, "fnc_certification_coc", 0,0 ,"reset_form('certificationcoc_1','','',1)",1);
                                    ?>                      
                                </td>
                            </tr>
                        </table>
                    </fieldset>
                </form>
            </fieldset>
        </div>
    </body>
    <script src="../../includes/functions_bottom.js" type="text/javascript"></script>
</html>