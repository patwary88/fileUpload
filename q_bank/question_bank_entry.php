<?php
    require_once('../includes/common.php');

    $qusnum  = return_max_id("sr_pre_exam_question_dtls","question_number",$where_cond="");
    if (empty($qusnum)) {
    	$qusNumber = 1;
    }else{
    	$qusNumber = $qusnum+1;
    }
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>File Upload</title>

<link rel="stylesheet" type="text/css" href="../resources/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="../resources/jquery.datetimepicker.min.css">
<style type="text/css">
	.row_height {
		margin-bottom: 5px;
	}
	label {
    	height: 10px; 
	}
	input {
    	height: 10px;
	}

	 h3, h4, h5, h6{
  		line-height: 10px; 
	}
</style>
</head> 
<body>
	<div class="container">
		<h6 style="text-align: center;color: green;" id="response_msg"></h6>
	  <h2 style="text-align: center;">Question Bnak Entry</h2>
	  <h5 style="text-align: center;">Examination Information</h5>
	  <form method="POST" id="question_bank" name="question_bank" action="#" accept-charset="utf-8">
			<div class="form-row">
			    <div class="form-group col-sm-3">
			      <label for="exam_name">Exam Name</label>
			      <input type="text" class="form-control form-control-sm" id="exam_name" name="exam_name" placeholder="Enter Exam Name">
			    </div>
			    <div class="form-group col-sm-3">
			      <label for="cbo_designation_id">Designation Type</label>
			      <select class="form-control form-control-sm" id="cbo_designation_id" name="cbo_designation_id">
				      <?php
				      foreach ($designation_type as $key => $value) {
				      ?>
				      <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
				      <?php
				  		}
				      ?>
			    	</select>
			    </div>
			    <div class="form-group col-sm-2">
			      <label for="cbo_exam_taker_id">Exam Taker</label>
			       <select class="form-control form-control-sm" id="cbo_exam_taker_id" name="cbo_exam_taker_id">
				      <?php
				      foreach ($exam_taker_arr as $key => $value) {
				      ?>
				      <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
				      <?php
				  		}
				      ?>
			    	</select>
			    </div>
			    <div class="form-group col-sm-2">
			      <label for="cbo_examtype_id">Exam Type</label>
			       <select class="form-control form-control-sm" id="cbo_examtype_id" name="cbo_examtype_id">
				      <?php
				      foreach ($exam_type_arr as $key => $value) {
				      ?>
				      <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
				      <?php
				  		}
				      ?>
			    	</select>
			    </div>
			    <div class="form-group col-sm-2">
			      <label for="exam_name">Exam Date</label>
			      <input class="form-control form-control-sm" id="txt_exam_date" name="txt_exam_date">
			    </div>
			</div>
			<h5 style="text-align: center;">Question Details</h5>
			<div class="form-row">
				<div class="form-group col-sm-10">
				    <label for="question_description">Question Description</label>
				    <input type="text" class="form-control form-control-sm" id="question_description" name="question_description" placeholder="Question Description">
			    </div>
				<div class="form-group col-sm-1">
			       <label for="cbo_question_type_id">Q. Type</label>
			       <select class="form-control form-control-sm" id="cbo_question_type_id" name="cbo_question_type_id">
				      <?php
				      foreach ($question_type_arr as $key => $value) {
				      ?>
				      <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
				      <?php
				  		}
				      ?>
			    	</select>
				</div>
				<div class="form-group col-sm-1">
			      <label for="txt_question_number">Q. Number</label>
			       <input type="number" class="form-control form-control-sm" id="txt_question_number" name="txt_question_number" placeholder="Enter Exam Name" min="1" max="200" value="<?php echo $qusNumber; ?>">
				</div>
		    </div>
			<h5 style="text-align: center;">Question Option</h5>
			<div class="form-row">
			    <div class="form-group col-sm-3">
			      <label for="txt_question_opt1">Option One</label>
			      <input type="text" class="form-control form-control-sm" id="txt_question_opt1" name="txt_question_opt1" placeholder="Enter Option One">
			    </div>
			    <div class="form-group col-sm-3">
			      <label for="txt_question_opt2">Option Two</label>
			      <input type="text" class="form-control form-control-sm" id="txt_question_opt2" name="txt_question_opt2" placeholder="Enter Option Two">
			    </div>
			    <div class="form-group col-sm-2">
			      <label for="txt_question_opt3">Option Three</label>
			      <input type="text" class="form-control form-control-sm" id="txt_question_opt3" name="txt_question_opt3" placeholder="Enter Option Two">
			    </div>
			    <div class="form-group col-sm-2">
			      <label for="txt_question_opt4">Option Four</label>
			      <input type="text" class="form-control form-control-sm" id="txt_question_opt4" name="txt_question_opt4" placeholder="Enter Option Two">
			    </div>
			    <div class="form-group col-sm-2">
			      <label for="txt_question_opt5">Option Five</label>
			      <input type="text" class="form-control form-control-sm" id="txt_question_opt5" name="txt_question_opt5" placeholder="Enter Option Two">
			    </div>
			</div>
			<h5 style="text-align: center;">Answer Option</h5>
			<div class="form-row">
			    <div class="form-group col-sm-3">
			      <label for="cbo_answer_opt1">Option One</label>
			      <select class="form-control form-control-sm" id="cbo_answer_opt1" name="cbo_answer_opt1">
					    <?php
					      foreach ($yes_no as $key => $value) {
					     
					     if($key==2) { $select = 'selected="selected"';}else{ $select="";}
					      	?>
					      	 <option value="<?php echo $key; ?>" <?php echo $select; ?> ><?php echo $value; ?></option>
					      	<?php
					      	}
					    ?>
				    </select>
			    </div>
			    <div class="form-group col-sm-3">
			      <label for="cbo_answer_opt2">Option Two</label>
			      <select class="form-control form-control-sm" id="cbo_answer_opt2" name="cbo_answer_opt2">
					    <?php
					      foreach ($yes_no as $key => $value) {
					     
					     if($key==2) { $select = 'selected="selected"';}else{ $select="";}
					      	?>
					      	 <option value="<?php echo $key; ?>" <?php echo $select; ?> ><?php echo $value; ?></option>
					      	<?php
					      	}
					    ?>
				    </select>
			    </div>
			    <div class="form-group col-sm-2">
			      <label for="cbo_answer_opt3">Option Three</label>
			      <select class="form-control form-control-sm" id="cbo_answer_opt3" name="cbo_answer_opt3">
					    <?php
					      foreach ($yes_no as $key => $value) {
					     
					     if($key==2) { $select = 'selected="selected"';}else{ $select="";}
					      	?>
					      	 <option value="<?php echo $key; ?>" <?php echo $select; ?> ><?php echo $value; ?></option>
					      	<?php
					      	}
					    ?>
				    </select>
			    </div>
			    <div class="form-group col-sm-2">
			      <label for="cbo_answer_opt4">Option Four</label>
			      <select class="form-control form-control-sm" id="cbo_answer_opt4" name="cbo_answer_opt4">
					    <?php
					      foreach ($yes_no as $key => $value) {
					     
					     if($key==2) { $select = 'selected="selected"';}else{ $select="";}
					      	?>
					      	 <option value="<?php echo $key; ?>" <?php echo $select; ?> ><?php echo $value; ?></option>
					      	<?php
					      	}
					    ?>
				    </select>
			    </div>
			    <div class="form-group col-sm-2">
			      <label for="cbo_answer_opt5">Option Five</label>
			      <select class="form-control form-control-sm" id="cbo_answer_opt5" name="cbo_answer_opt5">
					    <?php
					      foreach ($yes_no as $key => $value) {
					     
					     if($key==2) { $select = 'selected="selected"';}else{ $select="";}
					      	?>
					      	 <option value="<?php echo $key; ?>" <?php echo $select; ?> ><?php echo $value; ?></option>
					      	<?php
					      	}
					    ?>
				    </select>
			    </div>
			</div>
			<h5 style="text-align: center;">Solution</h5>

			<div class="form-group">
			    <textarea class="form-control" id="txt_question_solution" name="txt_question_solution" rows="2"></textarea>
			</div>

			<button type="submit" class="btn btn-primary btn-sm">Save</button>
		</form>
	</div>
	<script type="text/javascript" src="../resources/jquery.min.js"></script>
	<script type="text/javascript" src="../resources/popper.min.js"></script>
	<script type="text/javascript" src="../resources/bootstrap.min.js"></script>
	<script type="text/javascript" src="../resources/jquery.datetimepicker.full.min.js"></script>

	<script type="text/javascript">
	
	$("#question_bank").submit( function () {   
		var data = $(this).serialize();
		var url  = "requires/question_bank_entry_controller.php?action=save_update";
	    $.ajax({   
	        type: "POST",
	        data : data,
	        cache: false,  
	        url: url,   
	        success: function(data){  
	        	var call_back=	data.split('**');    
	            if (call_back[0]==1) {
					$("#response_msg").html("Data Success Fully Saved.");
					$("#response_msg").fadeOut(1000);
	            }else if (call_back[0]==2) {
	            	$("#response_msg").html("Data Not Inserted.");
					$("#response_msg").fadeOut(1000);
	            }

	            $('#exam_name').val('');
	            $('#question_description').val('');
	            $('#txt_question_opt1').val('');
	            $('#txt_question_opt2').val('');
	            $('#txt_question_opt3').val('');
	            $('#txt_question_opt4').val('');
	            $('#txt_question_opt5').val('');
	            $('#txt_question_solution').val('');
	            $('#txt_question_number').val(call_back[1]);

	        }   
	    });   
	    return false;   
	});

</script>
	<script type="text/javascript">
		$('#txt_exam_date').datetimepicker({
			timepicker:false,
			datepicker:true,
			format:'Y-m-d',
		})
	</script>
</body>
</html>