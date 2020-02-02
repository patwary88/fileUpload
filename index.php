
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
<script type="text/javascript" src="resources/jquery.min.js"></script>
<script type="text/javascript" src="resources/popper.min.js"></script>
<script type="text/javascript" src="resources/bootstrap.min.js"></script>
<script type="text/javascript">
	
	
	function file_upload(){

		var message_return = $('#file_return').val();
		var category_id = $('#category_id').val();
		var file_type_id = $('#file_type_id').val();
		if (message_return==0) {
			alert('Please Select Upload File'); return;
		}else
		{
		
		$("#uploadFile_id").submit(function(e) {
		    e.preventDefault(); 

		    var data="action=file_upload&category_id="+category_id+"&file_type_id="+file_type_id;
		    var url = "requires/file_upload_controller.php";
		    $.ajax({
		           type: "POST",
		           url: url,
		           data: data, 
		           success: function(data)
		           {
		               //alert(data);

		               $( '#file_return' ).val('0');
		           }
		    });


		});



		}
		
		
	}

</script>
</head>
	<body>
		<div class="container">
		  <h2>File Upload</h2>
		  <form method="post" id="uploadFile_id" enctype="multipart/form-data" action="javascript:file_upload();">
		    <div class="form-group">
			    <label for="category_id">Category</label>
			    <select class="form-control" id="category_id">
			      <?php
			      foreach ($category_arr as $key => $value) {
			      ?>
			      <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
			      <?php
			  		}
			      ?>
			    </select>
			</div>
		    <div class="form-group">
			    <label for="file_type_id">File Type</label>
			    <select class="form-control" id="file_type_id">
			      <?php
			      foreach ($file_type_arr as $key => $value) {
			      ?>
			      <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
			      <?php
			  		}
			      ?>
			    </select>
			    <input type="hidden" name="file_return" id="file_return" value="0">
			</div>
		    <div class="form-group">
		      <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal">
  					Browse File
			</button>
		    </div>
	  		<button type="submit" class="btn btn-primary">Submit</button>
		  </form>
		   <!-- The Modal -->
	  		<div class="modal" id="myModal">
	  			<div class="modal-dialog">
	  				<div class="modal-content">
	  					<!-- Modal Header -->
				        <div class="modal-header">
				          <h4 class="modal-title">Modal Heading</h4>
				          <button type="button" class="close" data-dismiss="modal">&times;</button>
				        </div>
				        <!-- Modal body -->
				        <div class="modal-body">
				        	<div class="alert alert-primary" role="alert" id="message-box"></div>
				          <form method='post' action='' enctype="multipart/form-data" id="modal_form">

				          	<div class="form-group">
					              <label for="usrname">Username</label>
					              <input type="text" class="form-control" id="usrname" placeholder="Enter email">
					            </div>

							  <div class="form-group">
							    <label for="uploadFile">File Upload</label>
							    <input type="file" class="form-control" id="uploadFile">
							  </div>
							</form>
				        </div>
				        
				        <!-- Modal footer -->
				        <div class="modal-footer">
				          <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
				        </div>
				        
	  				</div>
	  			</div>
	  		</div>
		</div>
	</body>
</html>

<script type="text/javascript">
	
	$(document).ready(function(){

		$("#myModal").click(function(){
			
			$('#uploadFile').change(function(){
				var poperty = this.files[0];
		        var fileName = this.files[0].name;
		        var fileSize = this.files[0].size;
		        var fileType = this.files[0].type;
		        if (fileName) {
		        	alert('You Select '+fileName);
		        }

		        var form_data = new FormData();
		        form_data.append("file",poperty);
		        //console.log(form_data);

		        $.ajax({
		            url: 'requires/file_upload_controller.php?action=uploadFile',
		            type: 'POST',
		            data: form_data,
		            contentType: false,       
		            cache: false,             
		            processData:false, 
		            success:function(data) {
		                   
		            	if (data==1) {
		            		$('#message-box').html('Successfully Uploaded'); 
		            		$('#file_return').val(data);
		            		/*$( '#modal_form' ).each(function(){
					    		this.reset();
							});*/
		            	}
		                                  
			        
			        }
			    });
	   	 	});
		});
	});

</script>