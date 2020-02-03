<?php
    require_once('includes/common.php');
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>File Upload</title>

<link rel="stylesheet" type="text/css" href="resources/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="resources/jquery.datetimepicker.min.css">

<!-- <script type="text/javascript" src="resources/bootstrap-datetimepicker.min.js"></script> -->

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
</style>
</head> 
<body>
	<div class="container">
	  <h2 style="text-align: center;">Question Bnak Entry</h2>
	  <h5 style="text-align: center;">Examination Information</h5>
	  <form>
			  <div class="form-row">
			    <div class="form-group col-sm-8">
			      <label for="exam_name">Exam Name</label>
			      <input type="text" class="form-control form-control-sm" id="exam_name" name="exam_name" placeholder="Enter Exam Name">
			    </div>
			    <div class="form-group col-sm-4">
			      <label for="cbo_exam_type">Exam Type</label>
			      <select class="form-control form-control-sm" id="cbo_exam_type">
				      <option>1</option>
				      <option>2</option>
				      <option>3</option>
				      <option>4</option>
				      <option>5</option>
			    	</select>
			    </div>
			  </div>
			  <div class="form-row">
			    <div class="form-group col-sm-6">
			      <label for="cbo_exam_taker">Exam Taker</label>
			       <select class="form-control form-control-sm" id="cbo_exam_taker">
				      <option>1</option>
				      <option>2</option>
				      <option>3</option>
				      <option>4</option>
				      <option>5</option>
			    	</select>
			    </div>
			    <div class="form-group col-sm-6">
			      <label for="exam_name">Exam Date</label>
			      <input class="form-control form-control-sm" id="txt_exam_date" name="txt_exam_date">
			    </div>
			  </div>
			   <h5 style="text-align: center;">Question Details</h5>
			   <div class="form-row">
			    <div class="form-group col-sm-6">
			      <label for="cbo_exam_taker">Exam Taker</label>
			       <select class="form-control form-control-sm" id="cbo_exam_taker">
				      <option>1</option>
				      <option>2</option>
				      <option>3</option>
				      <option>4</option>
				      <option>5</option>
			    	</select>
			    </div>
			    <div class="form-group col-sm-6">
			      <label for="exam_name">Exam Date</label>
			      <input class="form-control form-control-sm" id="txt_exam_date" name="txt_exam_date">
			    </div>
			  </div>
			   <div class="form-group">
			    <label for="inputAddress">Address</label>
			    <input type="text" class="form-control form-control-sm" id="inputAddress" placeholder="1234 Main St">
			  </div>
			  <h5 style="text-align: center;">Question Option</h5>
			  <div class="form-row">
			    <div class="form-group col-sm-4">
			      <label for="txt_question_number">Question Number</label>
			      <input type="number" class="form-control form-control-sm" id="txt_question_number" name="txt_question_number" placeholder="Enter Exam Name">
			    </div>
			    <div class="form-group col-sm-4">
			      <label for="txt_question_opt1">Question Option One</label>
			      <input type="text" class="form-control form-control-sm" id="txt_question_opt1" name="txt_question_opt1" placeholder="Enter Option One">
			    </div>
			    <div class="form-group col-sm-4">
			      <label for="txt_question_opt2">Question Option Two</label>
			      <input type="text" class="form-control form-control-sm" id="txt_question_opt2" name="txt_question_opt2" placeholder="Enter Option Two">
			    </div>
			  </div>
			  <div class="form-row">
			  	<div class="form-group col-sm-4">
			      <label for="txt_question_opt3">Question Option Three</label>
			      <input type="text" class="form-control form-control-sm" id="txt_question_opt3" name="txt_question_opt3" placeholder="Enter Option Two">
			    </div>
			    <div class="form-group col-sm-4">
			      <label for="txt_question_opt4">Question Option Four</label>
			      <input type="text" class="form-control form-control-sm" id="txt_question_opt4" name="txt_question_opt4" placeholder="Enter Option Two">
			    </div>
			    <div class="form-group col-sm-4">
			      <label for="txt_question_opt5">Question Option Five</label>
			      <input type="text" class="form-control form-control-sm" id="txt_question_opt5" name="txt_question_opt5" placeholder="Enter Option Two">
			    </div>
			  </div>
			  <h5 style="text-align: center;">Answer Option</h5>
			  <div class="form-row">
			    <div class="form-group col-sm-4">
			      <label for="txt_question_number">Answer Number</label>
			      <input type="number" class="form-control form-control-sm" id="txt_question_number" name="txt_question_number" placeholder="Enter Exam Name">
			    </div>
			    <div class="form-group col-sm-4">
			      <label for="txt_question_opt1">Answer Option One</label>
			      <input type="text" class="form-control form-control-sm" id="txt_question_opt1" name="txt_question_opt1" placeholder="Enter Option One">
			    </div>
			    <div class="form-group col-sm-4">
			      <label for="txt_question_opt2">Answer Option Two</label>
			      <input type="text" class="form-control form-control-sm" id="txt_question_opt2" name="txt_question_opt2" placeholder="Enter Option Two">
			    </div>
			  </div>
			  <div class="form-row">
			  	<div class="form-group col-sm-4">
			      <label for="txt_question_opt3">Answer Option Three</label>
			      <input type="text" class="form-control form-control-sm" id="txt_question_opt3" name="txt_question_opt3" placeholder="Enter Option Two">
			    </div>
			    <div class="form-group col-sm-4">
			      <label for="txt_question_opt4">Answer Option Four</label>
			      <input type="text" class="form-control form-control-sm" id="txt_question_opt4" name="txt_question_opt4" placeholder="Enter Option Two">
			    </div>
			    <div class="form-group col-sm-4">
			      <label for="txt_question_opt5">Answer Option Five</label>
			      <input type="text" class="form-control form-control-sm" id="txt_question_opt5" name="txt_question_opt5" placeholder="Enter Option Two">
			    </div>
			  </div>
			  <button type="submit" class="btn btn-primary">Sign in</button>
			</form>
	</div>
	<script type="text/javascript" src="resources/jquery.min.js"></script>
	<script type="text/javascript" src="resources/popper.min.js"></script>
	<script type="text/javascript" src="resources/bootstrap.min.js"></script>
	<script type="text/javascript" src="resources/jquery.datetimepicker.full.min.js"></script>
	<script type="text/javascript">
		$('#txt_exam_date').datetimepicker({
			timepicker:false,
			datepicker:true,
			format:'Y-m-d',
		})
	</script>
</body>
</html>