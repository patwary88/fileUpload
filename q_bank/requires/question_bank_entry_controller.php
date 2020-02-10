<?php
	require_once('../../includes/common.php');
	header('Content-Type: text/html; charset=utf-8');
	extract($_REQUEST);
	if ($action=="save_update") {
		$insertedBy = 1;
		$insertDate = date("Y-m-d h:i:s",time());
		$examID     = return_next_id("sr_pre_exam_mst","id",$where_cond="");
		$qID     	= return_next_id("sr_pre_exam_question_dtls","id",$where_cond="");
		$examMaxID  = return_max_id("sr_pre_exam_mst","id",$where_cond="");

		/*echo secure($txt_question_solution);
		die;*/

		if ($exam_name!="") {
			//echo $cbo_exam_taker;
			$exam_mst_tbl = "sr_pre_exam_mst";
			$exam_mst_colomn_name = "exam_name,designation_id,exam_taker_id,exam_type_id,exam_date,status_active,is_deleted,inserted_by,insert_date";
			$exam_mst_data_arr  ="'".secure($exam_name)."','".secure($cbo_designation_id)."','".secure($cbo_exam_taker_id)."','".secure($cbo_examtype_id)."','".secure($txt_exam_date)."','1','0','".$insertedBy."','".secure($insertDate)."'";

			$question_dtls_tbl = "sr_pre_exam_question_dtls";
			$question_dtls_colomn_name = "exam_id,question_description,question_type_id,question_number,status_active,is_deleted,inserted_by,insert_date";
			$question_dtls_data_arr  ="'".secure($examID)."','".secure($question_description)."','".$cbo_question_type_id."','".$txt_question_number."','1','0','".$insertedBy."','".secure($insertDate)."'";

			$opt_dtls_tbl = "sr_pre_exam_opt_dtls";
			$opt_dtls_colomn_name = "exam_id,question_id,que_option_one,que_option_two,que_option_three,que_option_four,que_option_five,status_active,is_deleted,inserted_by,insert_date";
			$opt_dtls_data_arr  ="'".$examID."','".$qID."','".secure($txt_question_opt1)."','".secure($txt_question_opt2)."','".secure($txt_question_opt3)."','".secure($txt_question_opt4)."','".secure($txt_question_opt5)."','1','0','".$insertedBy."','".secure($insertDate)."'";

			$ans_dtls_tbl = "sr_pre_exam_ans_dtls";
			$ans_dtls_colomn_name = "exam_id,question_id,ans_option_one,ans_option_two,ans_option_three,ans_option_four,ans_option_five,status_active,is_deleted,inserted_by,insert_date";
			$ans_dtls_data_arr  ="'".$examID."','".$qID."','".secure($cbo_answer_opt1)."','".secure($cbo_answer_opt2)."','".secure($cbo_answer_opt3)."','".secure($cbo_answer_opt4)."','".secure($cbo_answer_opt5)."','1','0','".$insertedBy."','".secure($insertDate)."'";

			$solution_dtls_tbl = "sr_pre_qus_solution_dtls";
			$solution_dtls_colomn_name = "exam_id,question_id,solution_dtls,status_active,is_deleted,inserted_by,insert_date";
			$solution_dtls_data_arr  ="'".$examID."','".$qID."','".secure($txt_question_solution)."','1','0','".$insertedBy."','".secure($insertDate)."'";

			$rID1 = sql_insert($exam_mst_tbl,$exam_mst_colomn_name,$exam_mst_data_arr);
			$rID2 = sql_insert($question_dtls_tbl,$question_dtls_colomn_name,$question_dtls_data_arr);
			$rID3 = sql_insert($opt_dtls_tbl,$opt_dtls_colomn_name,$opt_dtls_data_arr);
			$rID4 = sql_insert($ans_dtls_tbl,$ans_dtls_colomn_name,$ans_dtls_data_arr);
			$rID5 = sql_insert($solution_dtls_tbl,$solution_dtls_colomn_name,$solution_dtls_data_arr);

			$qusnum  = return_max_id("sr_pre_exam_question_dtls","question_number",$where_cond="");
		    if (empty($qusnum)) {
		    	$qusNumber = 1;
		    }else{
		    	$qusNumber = $qusnum+1;
		    }

			if ($rID1 && $rID2 && $rID3 && $rID4 && $rID5) {
				echo "1**".$qusNumber;
			}else{
				echo "2**".$qusNumber;
			}
		
		}else{

			$question_dtls_tbl = "sr_pre_exam_question_dtls";
			$question_dtls_colomn_name = "exam_id,question_description,question_type_id,question_number,status_active,is_deleted,inserted_by,insert_date";
			$question_dtls_data_arr  ="'".secure($examMaxID)."','".secure($question_description)."','".$cbo_question_type_id."','".$txt_question_number."','1','0','".$insertedBy."','".secure($insertDate)."'";

			$opt_dtls_tbl = "sr_pre_exam_opt_dtls";
			$opt_dtls_colomn_name = "exam_id,question_id,que_option_one,que_option_two,que_option_three,que_option_four,que_option_five,status_active,is_deleted,inserted_by,insert_date";
			$opt_dtls_data_arr  ="'".$examMaxID."','".$qID."','".secure($txt_question_opt1)."','".secure($txt_question_opt2)."','".secure($txt_question_opt3)."','".secure($txt_question_opt4)."','".secure($txt_question_opt5)."','1','0','".$insertedBy."','".secure($insertDate)."'";

			$ans_dtls_tbl = "sr_pre_exam_ans_dtls";
			$ans_dtls_colomn_name = "exam_id,question_id,ans_option_one,ans_option_two,ans_option_three,ans_option_four,ans_option_five,status_active,is_deleted,inserted_by,insert_date";
			$ans_dtls_data_arr  ="'".$examMaxID."','".$qID."','".secure($cbo_answer_opt1)."','".secure($cbo_answer_opt2)."','".secure($cbo_answer_opt3)."','".secure($cbo_answer_opt4)."','".secure($cbo_answer_opt5)."','1','0','".$insertedBy."','".secure($insertDate)."'";

			$solution_dtls_tbl = "sr_pre_qus_solution_dtls";
			$solution_dtls_colomn_name = "exam_id,question_id,solution_dtls,status_active,is_deleted,inserted_by,insert_date";
			$solution_dtls_data_arr  ="'".$examMaxID."','".$qID."','".secure($txt_question_solution)."','1','0','".$insertedBy."','".secure($insertDate)."'";

			$rID2 = sql_insert($question_dtls_tbl,$question_dtls_colomn_name,$question_dtls_data_arr);
			$rID3 = sql_insert($opt_dtls_tbl,$opt_dtls_colomn_name,$opt_dtls_data_arr);
			$rID4 = sql_insert($ans_dtls_tbl,$ans_dtls_colomn_name,$ans_dtls_data_arr);
			$rID5 = sql_insert($solution_dtls_tbl,$solution_dtls_colomn_name,$solution_dtls_data_arr);
			//echo $rID2."==".$rID3."==".$rID4."==".$rID5;
			if ($rID2==1 && $rID3==1 && $rID4==1 && $rID5==1) {
				echo "1";
			}else{
				echo "2";
			}
		}
		
		
	}

/*Array
(
    [txt_question_solution] => 
)*/

?>