<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
	var c;
	//LOADING BLOG POSTS ON LOAD
	<?php $username = $_SESSION['username']; ?>
	window.addEventListener('load', function()
	{	
		var url = 'https://cs.tru.ca/~challman/Project/Project_controller.php';  // test program for 'ListQuestions' from TRUQA
		var query = {page: 'MainPage', command: 'getBlog'};
		// JQUERY Load Blog Posts
		$.post(url, query, function(data) 
		{
			var popup ="";
			popup += "<h2 style='text-align:center'>Edit Post</h2>";
			popup += "<textarea id='post_edit' name='post_edit' cols='80' rows='8' maxlength='400' style='resize: none' required></textarea><br>";
			popup += "<input type='button' value='Cancel' class='cancel_btn2'> <button id='commit_changes' data-2='" + data2 + "' blog_c='" + blog_content + "'>Submit</button>";
			$('#edit-post-box').html(popup);
			//alert(data);  
			var rows = JSON.parse(data);                      
			var myvar='<?php echo $username;?>';
			var t = "";
			var data2;
			var blog_content;
		
			for (var i = 0; i < rows.length; i++)
			{			// for each row
				if(rows[i]['username'] == myvar) //show only user posts
				{
					t += "<div id='blog_post'> <div id = 'header'><h4>" + rows[i]['username'] +  "</h4><button class ='edit_post' data-c-id='" + rows[i]['Id'] + "'>Edit Post</button> <button class='delete-post-button' data-c-id='" + rows[i]['Id'] + "'>Delete</button></div> " + "<div id = 'blog_text'><p id='blog_text_text'>" + rows[i]['blog'];
					t += " </p></div> <button class='like_button' data-q-id='" + rows[i]['Id'] + "'>Like</button> " + rows[i]['Likes'] + "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button class='comment_button' data-q-id='" + rows[i]['Id'] + "' >Comments</button>" + rows[i]['Comments'] +  "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button class='republish_button' data-q-id='" + rows[i]['Id'] + "'>Republish</button>" +rows[i]['Repubilshes'] + " </div>";
					blog_content = rows[i]['blog'];
				}	
			}	
//****************EDIT POST BUTTON**************************************************
			$(document).on('click','.edit_post', function()
			{
				$('#blanket').css("display","block");
				$('#edit-post-box').css("display","block");	
				var url = 'https://cs.tru.ca/~challman/Project/Project_controller.php';
				var query2 = {page: 'ProfilePage', command: 'edit_post', CID: $(this).attr('data-c-id'), qid: $(this).attr('data-q-id')};
				var str = "";
					
				$.post(url,query2, function(data)
				{
					//alert(data);
					var rows = JSON.parse(data);
					data2 = rows[0]['Id'];
					str += "" + rows[0]['blog'];
					//alert(rows[0]['Id'] + str);
					$('#post_edit').val(str);
						
					$(document).on('click', '#commit_changes', function() 
					{
						$('#edit-post-box').css("display","none");
						$('#blanket').css("display","none");
						var url = 'https://cs.tru.ca/~challman/Project/Project_controller.php';
						var query23 = {page: 'ProfilePage', command: 'submit_postedit', something: data2, content:$('#post_edit').val()  };
						$.post(url,query23, function(data23)
						{
							//alert(data23);
							var rows = JSON.parse(data23);
						});	
						window.location.reload();
					});
				});
//Edit Post Cancel Button.
				$(document).on('click', '.cancel_btn2', function() 
				{
					$('#edit-post-box').css("display","none");
					$('#blanket').css("display","none");
						
				});		
			});	
//******************************DELETE POST BUTTON**********************************************
			$(document).on('click','.delete-post-button', function()
			{
				//$('#blanket').css("display","none");
				//$('#comment-box').css("display","none");
			
				$(this).parent().parent().remove();
				var url = 'https://cs.tru.ca/~challman/Project/Project_controller.php';                       // It should not be outside $.post().
				var query2 = {page: 'MainPage', command: 'delete_post', CID: $(this).attr('data-c-id'), qid: $(this).attr('data-q-id')};
				//alert($(this).attr('data-c-id'));
				$.post(url,query2, function(data)
				{
					//alert(data);
				});
				window.location.reload();
			
			});
//***************************************************************************************
				
			var query3 = {page: 'MainPage', command: 'getRepub'};
			$.post(url,query3, function(data2)
			{
				var myvar2='<?php echo $username; ?>';
				//alert(data2); // to see the format
				var rows2 = JSON.parse(data2);
				//alert(rows2);
				var j = "";
				for (var i = 0; i < rows.length; i++) 
				{
					for(var x = 0; x < rows2.length; x++)
					{
						// for each row
						//alert(rows[i]['Id'] + "" + rows2[x]);
						if(rows2[x] == rows[i]['Id']) //*TODO* ADD ANONTHER IF where if republishes matches username, set up a new query where command is getRepubs. 
						{
							j += "<div id='blog_post'> <div id = 'header'><h4>"  + rows[i]['username'] + "<h4 id = 'repub_post'> Republished Post </h4></div>"  + "<div id = blog_text><p>" + rows[i]['blog'];
							j += " </p></div> <button class='like_button' data-q-id='" + rows[i]['Id'] + "'>Like</button> " + rows[i]['Likes'] + "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button class='comment_button' data-q-id='" + rows[i]['Id'] + "' >Comments</button>" + rows[i]['Comments'] +  "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button class='republish_button' data-q-id='" + rows[i]['Id'] + "'>Republish</button>" +rows[i]['Repubilshes'] + " </div>";
						}
					}
				}
				t += j;
				$('#content-right').html(t);
				//***********************************************************************************************************************************
				$('.like_button').click(function() 
				{  // 'click' event registration for the above 'Delete' buttons, using the attribute selection
					//alert($(this).attr('data-q-id'));
					var query9 = {page: 'MainPage', command: 'like', qid: $(this).attr('data-q-id')};
					$.post(url,query9);
					window.location.reload();
				});
				$('.republish_button').click(function() 
				{  // 'click' event registration for the above 'Delete' buttons, using the attribute selection
                
				//alert($(this).attr('data-q-id'));
					var query90 = {page: 'MainPage', command: 'repub', qid: $(this).attr('data-q-id')};
					$.post(url,query90);
					window.location.reload();
				});
				
				//get Comments on button click
		
				$(document).on('click','.comment_button', function()
				{
					$('#comment-box').css("display","block");
					$('#blanket').css("display","block");
					c = $(this).attr('data-q-id'); //Gets correct value
					//alert("Initial: Current Posts  ID: " + c); // Correct*
					var url = 'https://cs.tru.ca/~challman/Project/Project_controller.php';
					var query90 = {page: 'MainPage', command: 'getComm', qid: $(this).attr('data-q-id')};
					$.post(url, query90, function(data) 
					{
						//alert("Comments: " + data); // just to see the format of the data
						var rows_comments = JSON.parse(data);  // convert a JSON string to an object 
                           //   the object will be a linear array of associative arrays
						var t = "";
						for (var i = 0; i < rows_comments.length; i++) 
						{  // for each row
								//removed information * + "CID: " + rows_comments[i]['CID'] *
								//+ " PID:" + rows_comments[i]['PID'] +
								//" UID:" + rows_comments[i]['UID'] +
								t += "<div class='comm_post'>"  +  " Username:" + rows_comments[i]['username'] + " Comment:" + rows_comments[i]['comment'];
								t += "<button class='delete-comment-button' data-c-id='" + rows_comments[i]['CID'] + "' data-post-id ='" + rows_comments[i]['PID'] + "'>Delete</button>"  +" </div>";
						}
//SUBMIT THE COMMENT ********************************************************
						$(document).on('click','.submit_comment', function()
						{
								 //THIS IS CORRECT, SHOWS COMMENT ID
								//alert("Current Posts ID: " + c);
								//alert($('.comment_button').attr('data-q-id'));
								//alert("Comment Value: " + $('#comment').val());
								//Send query to server to post comment 
								var url = 'https://cs.tru.ca/~challman/Project/Project_controller.php';                       
								var query97 = {page: 'MainPage', command: 'comment',  pid: c, content: $('#comment').val() }; //WHAT AM I TRYING TO SEND WITH QID
								
								$.post(url,query97, function(data)
								{
									//alert("Submit Comment Data: " + data);
									
								});
								//$(this).parent().remove(); // remove the comment visually.
								$('#comment-box').css("display","none");
								$('#blanket').css("display","none");
								window.location.reload();
						});	
						
						//DELETING A COMMENT 
						$(document).on('click','.delete-comment-button', function()
						{
								//alert("Data CID: " + $(this).attr('data-c-id')); //THIS IS CORRECT, SHOWS COMMENT ID
								//alert("Data PID: " + $(this).attr('data-post-id')); //Returns the Correct Blog Post Id the comment belongs too.
								//Send query to server to delete comment 
								var url = 'https://cs.tru.ca/~challman/Project/Project_controller.php';                       // It should not be outside $.post().
								var query2 = {page: 'MainPage', command: 'delete_comment', CID: $(this).attr('data-c-id'), PID: $(this).attr('data-post-id')}; //WHAT AM I TRYING TO SEND WITH QID
								
								$.post(url,query2, function(data)
								{
									//alert(data);
			
								});
								$(this).parent().remove(); // remove the comment visually.
								$('#comment-box').css("display","none");
								$('#blanket').css("display","none");
								window.location.reload();
								
							});	
				
						//alert($('.comment_button').attr('data-q-id'));
						$('#user-comment-result').html(t);	
												
					});
					$(document).on('click', '.cancel_btn2', function() 
					{
						$('#profile-box').css("display","none");
						$('#comment-box').css("display","none");
						$('#edit-post-box').css("display","none");
						$('#blanket').css("display","none");
						$('#edit-post-box').css("display","none");
					});
				});	
			});  
			$('#content-right table button').click(function() 
			{   // 'click' event registration for the above 'Delete' buttons, using the attribute selection
				//alert($(this).attr('data-q-id'));
				var query2 = {page: 'MainPage', command: 'delete', qid: $(this).attr('data-q-id')};
				$.post(url,query2);
				$(this).parent().parent().remove();
			});	
		});
		//Load Followers*******************
		var queryFollow = {page: 'ProfilePage', command: 'getFollowers'};
		$.post(url,queryFollow, function(data5)
		{
			//alert(data5);  
			var rows = JSON.parse(data5);                      
			var myvar='<?php echo $username;?>';
			var follows = "";
			$('#followers').html(rows.length);
		});
				
		$(document).on('click','#open-profile-box', function()
		{
			//alert("Click!");
			$('#profile-box').css("display","block");
			$('#blanket').css("display","block");
			$(document).on('click', '.cancel_btn2', function() 
			{
				$('#profile-box').css("display","none");
				$('#blanket').css("display","none");
						
			});
		});
		$('#button-submit').click(function() 
		{
			//alert("submit");
			$.ajax(
			{
				url: "Project_controller.php",
				type: "POST",
				data7:  new FormData(document.getElementById("form-upload")),
				contentType: false,
				cache: false,
				processData:false,
				success: function(data7) 
				{
					result7 = JSON.parse(data7);
					//alert(result7);
					$('#change_pic_result').html('Profile Picture Changed!');
				},
				error: function(e) 
				{
					alert(e)
				}          
			});	
			document.getElementById('form-upload').submit();
		});
				
		document.getElementById("profile_pic").src = 'https://cs.tru.ca/~challman/Project/uploads/<?php echo $username ?>/profile_pic.png';
		//alert(document.getElementById("profile_pic").src);
		
	});


</script>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>


<div id='layout-top2'>
	<h1>My Profile</h1>
</div>

<div id='layout-main'>
	
	<div id='blanket'></div>
	
	<div id='edit-post-box' class='modal-window'>
			
	</div>
	
	<div id='comment-box' class='modal-window'>
			<h2 style='text-align:center'>Comments!</h2>
			<h3 style='text-align:center'>Write your comment:</h3>
			
				<input type='hidden' name='page' value='MainPage'>
				<input type='hidden' name='command' value='comment'>
				<input type="text" id="comment" name="comment" maxlength="50" required><br>
			
			<div id='comment-section'>
			<div id='user-comment-result'>
				User Comments Shown Here
			</div>
			<div id="user_form"></div>
			</div>
			
			<input type="button" value="Cancel" class="cancel_btn2">
			<input type="button" value="Submit" class="submit_comment">
		</div>
	
	<div id='profile-box' class='modal-window'>
			<h2 style='text-align:center'>Change Profile</h2>
			
			<form id="form-upload" action="Project_controller.php" method="POST" enctype="multipart/form-data">
			<fieldset>
				<legend>File Upload</legend>
				<input type='hidden' name='page' value='ProfilePage'>
				<input type='hidden' name='command' value='change_profile'>
            
				<label for="fileselect">Files to upload:</label>
				<input type="file" id="fileselect" name="fileToUpload[]" multiple>
				<br>
            
				<div id="filedropzone">or drag files and drop them here</div>
            
				
			</fieldset>
			</form>
			
			<div id='result-pane'>Results will be displayed here.</div>
			
			<input type="button" value="Cancel" class="cancel_btn2">
			<button id='button-submit' type="button">Upload Files</button>
			
	</div>


	<div id='layout-main-left2'>
	
		<div id='content-left2'>
		<div id ='change_pic_result'></div>
			<div id='profile_picture'>
			<br>
				<img id= 'profile_pic' alt="Profile Picture" width="150" height="150">
			</div>
			<h2>User: <?php echo $_SESSION['username']; ?></h2>
			
			<label id='followers_label'>Followers: </label><h4 style='display:inline;' id='followers'></h4>
			<form method ="POST" action="Project_controller.php" id="return_form">
				<input type='hidden' name='page' value='ProfilePage'>
				<input type='hidden' name='command' value='return'>
				<input type="submit" value="Return to Main Page" id='return_btn'><br>		
			</form>	
		</div>	
	</div>
	<div id='layout-main-right2'>
		<h2> My Posts </h2>
		<div id='content-right'>
			Results will be shown here
		</div>
	</div>
</div>
<div id='layout-bottom'>
     <!-- Unsubscribe Form -->
	<form method ="POST" action="Project_controller.php" id="unsubscribe_form" style="display:inline;">
		<input type='hidden' name='page' value='ProfilePage'>
		<input type='hidden' name='command' value='unsubscribe'>
		<input type='hidden' name='unsub_username' value='<?php echo $_SESSION['username']?>'>
		<button id='unsubscribe-btn'>Unsubscribe</button>
	</form>

	<button id ='open-profile-box'>Change Profile</button>

	<form method ="POST" action="Project_controller.php" id="signout_form" style="display:inline;">
		<input type='hidden' name='page' value='ProfilePage'>
		<input type='hidden' name='command' value='sign_out'> 
		<button type="submit" id='signout-btn'>Sign Out</button>
	</form>
</div>


<script>
	//Timeout functions
	var timer = setTimeout(timeout, 1000 * 1000);  // 10 seconds
	window.addEventListener('mousemove', event_listener_mousemove); // Register an event handler for 'mousemove'
    
	function event_listener_mousemove() {
        clearTimeout(timer);  // Clear timeout
        timer = setTimeout(timeout, 1000 * 1000);  // Reregister ...
    }
    function timeout() {
        window.removeEventListener('mousemove', event_listener_mousemove);  // Remove the event listener
        alert('Signed out due to inactivity.');
		document.getElementById('signout_form').submit();
    }

	//Change Profile		
	$(document).on('click','#open-profile-box', function(){
		//alert("Click!");
		$('#profile-box').css("display","block");
		$('#blanket').css("display","block");
		});
	
//Modal Window and Button Listeners



	
	$('#unsubscribe-btn').click(function() {
		var url = 'https://cs.tru.ca/~challman/Project/Project_controller.php';
		var query90 = {page: 'ProfilePage', command: 'unsubscribe', qid: <?php echo $_SESSION['username']?>};
			$.post(url, query90, function(data) 
			{
				//alert(data);  // just to see the format of the data
				
			});
		
	});
	
	

	
	
	
		
	//Edit Post
	$(document).on('click','#edit_post', function(){
		$('#edit-post-box').css("display","block");
		$('#blanket').css("display","block");
		});
	
	
	
	
	$(document).on('click', '.cancel_btn2', function() {
		$('#profile-box').css("display","none");
		$('#comment-box').css("display","none");
		$('#edit-post-box').css("display","none");
		$('#blanket').css("display","none");
		$('#edit-post-box').css("display","none");
		});
		
		
	

	window.addEventListener('load', function() {
            //if (window.File && window.FileList && window.FileReader) {  // if FILE API is supported
                var filedropzone = document.getElementById("filedropzone");  // The div element as a drop zone
                filedropzone.addEventListener("dragover", FileDragHover, false);
                filedropzone.addEventListener("dragleave", FileDragHover, false);
                filedropzone.addEventListener("drop", FileDrop, false);
            //}
        });

        // File drag hover - for the dragover and dragleave events
        function FileDragHover(e) {
            e.stopPropagation();
            e.preventDefault();
            e.target.className = (e.type == "dragover" ? "hover" : "");  // For CSS class
        }

        // File drop
        function FileDrop(e) {
            // Cancel event and hover styling
            FileDragHover(e);

            // Assign the files to the file type input element so that they can be submitted.
            document.getElementById("fileselect").files = e.dataTransfer.files;  
        }
		
	
//PHP Code
 <?php
 if (empty($_SESSION['signedin'])) {
    $display_modal_window = 'none';
    include('Project_startpage.php');
    exit;
}
 ?>


</script>

<style>
	/* Div Styles */ 
	
	#layout-main 
	{
        width:100%; /* full width */
        position:relative;
        height:90%; 		
	}
    
	#layout-main-left2
	{
        position:absolute;
        background-color:silver;
        width:25%;
        height:100%;
		left:0;  
		text-align:center;
    }
	.comm_post{
		background-color:white;
		border-style:solid;
		width:100%;
		
		
	}
    
	#layout-main-right2 
	{
        position:absolute;
		background-color:LightGrey;
		width:75%;
        height:100%;
        top:(100% - 100px); 
		left:25%; /* to put on right side */
    }
	 #layout-top2 
	 {
        height:15%;
        width:100%;
        position:relative;
        left:0;
        top:0;
        bottom:calc(100% - 100px);
		background-color:skyblue;
		border-style:solid;
    }
	
	#layout-bottom
	{
		background-color:skyblue;
		width:100%;
		position:relative;
		
		
	}
	
	/*  Header Styles */
	
	
	h1
	{
	
		font-size:3em;
		text-align:center;

	}
	
	h2
	{
		font-size:2em;
		text-align:center;
	
	}
	
	h4
	{
	display:inline-block;
		margin:10px;
		
	}
	#repub_post{
		display:inline-block;
		float:right;
		
	}
	#followers_label
	{
			font-size:1.5em;
		
	}
	#followers{
		font-size:1.5em;
	}
	/* Button Styles */ 
	
	button
	{
		margin:10px;
		
	}
	
	#return_btn
	{
		margin:10px;
		width: 50%;
		
	}
	#signout-btn
	{
		right:0;
		
	}
	
	.submit_comment
	{
		position: absolute;
		right:    0;
		bottom:   0;
	}
	#comment
	{
		width:50%;			
	}
	
	#user-comment-result
	{
		text-align:center;
		overflow-y:scroll;
		left:5%;
		right:5%;
		margin-left:5%;
		margin-right:5%;
		display:block;
		bottom:5%;
		height:80%;
	}
	
	
	#submit_btn1
	{
		position: absolute;
		right:    0;
		bottom:   0;

	}
	#submit_btn2
	{
		position: absolute;
		right:    0;
		bottom:   0;
	}
	#cancel_btn1
	{
		position: absolute;
		left:    0;
		bottom:   0;
	}
	.cancel_btn2
	{
		position: absolute;
		left:    0;
		bottom:   0;
	}
	#unsubscribe-btn
	{
		height:
	}
	
	#commit_changes{
		position:absolute;
		bottom:0;
		right:0;
		margin:0;
	}
	
	/* Modal Styles  */
	
	#comment-box 
	{
        display:none;  /* initially hidden */
        width: 50%; 
		border: 1px solid; 
		background-color:White;
        position: absolute; 
		top:0; 
		left:25%; 
		bottom: calc(50% - 100px);
        z-index: 9001;  /* over all elements */
		text-align:center;
		overflow:hidden;
		
	}
		
	#edit-post-box
	{
        display:none;  /* initially hidden */
        width: 50%; 
		border: 1px solid; 
		background-color:White;
        position: absolute; 
		top:0; 
		left:25%; 
		bottom: calc(75% - 100px);
        z-index: 9001;  /* over all elements */
		text-align:center;
		}
		
	#profile-box
	{
        display:none;  /* initially hidden */
        width: 50%; 
		border: 1px solid; 
		background-color:White;
        position: absolute; 
		top: 0; 
		left:25%; 
		bottom:25%;
        z-index: 9001;  /* over all elements */
		text-align:center;
		
	}
		
	#blanket 
	{
        display:none;
        width:100%;
        height:100%;
        z-index:9000;
        opacity:0.5;
        position:absolute;
        background-color:Grey;
    }
	#question
	{
		width:80%;
	
	}
	#term{
		width:80%;
	
	}
	
	
	#content-right{
		position:absolute; 
            top:100px; left:200px;
            width:75%; height:75%; 
            background-color:skyblue; 
            padding:10px;
			overflow-y:scroll;
			
	}
	#blog_post{
		background-color:skyblue;
		border-style:solid;
		
		
	}
	#blog_text{
		background-color:white;
		margin:20px;
		overflow-wrap:word-break;
		width:80%;
		height:40%;
		margin:10px;
	}
	
	#blog_text_text{
		background-color:white;
		width:100%;
		word-wrap: break-word;
	}
	#filedropzone
        {
            /* display: none; */
            font-weight: bold;
            text-align: center;
            padding: 1em 0;
            margin: 1em 0;
            color: #555;
            border: 2px dashed #555;
            border-radius: 7px;
            cursor: default;
        }
		fieldset{
			display:inline-block;
		}

        #filedropzone.hover
        {
            color: #f00;
            border-color: #f00;
            border-style: solid;
            box-shadow: inset 0 3px 4px #888;
        }
		#profile_picture{
		text-align:center;
			
			
			
		}
		#header{
			background-color:silver;
			height:10%;
		}
		.edit_post{
		display:inline-block;
		float:right;
		right:0;
		top:0;
				
			
		}
		.delete-post-button{
		display:inline-block;
		float:right;
		right:0;
		top:0;
				
			
		}
		#post_edit{
		
			
			
		}
		#followers_label{
			display:inline-block;
			
			
		}
		#profile_pic{
			border-style:solid;
			
		}
		#button-submit{
			display:inline-block;
			right:0;
			bottom:0;
			
		}
		
		#comment-section{
			bottom:5%;
			
			display:inline-block;
			height:80%;
			width:80%;
			left:25%;
			text-align:center;
			
			
		}
	
	
	
	</style>