<script>
	var c;
	//LOADING BLOG POSTS ON LOAD
	
	window.addEventListener('load', function() 
	{
		<?php $username = $_POST['Username']; ?>	
		var url = 'https://cs.tru.ca/~challman/Project/Project_controller.php';  // test program for 'ListQuestions' from TRUQA
		var query = {page: 'MainPage', command: 'getBlog'};
		// jQuery post
		$.post(url, query, function(data) 
		{
			//alert(data);  // just to see the format of the data
			var rows = JSON.parse(data);              
			var myvar='<?php echo $username;?>';
			//alert("My var: " + myvar); //myvar is correct! Shows the searched users username. 
			var t = "";
			for (var i = 0; i < rows.length; i++) 
			{  // for each row
				if(rows[i]['username'] == myvar) //if the post has the same username as the profile retrieve those posts   
				{
					t += "<div id='blog_post'> <h4>"  + rows[i]['username'] + "<div id = blog_text><p>" + rows[i]['blog'];
					t += " </p></div> <button class='like_button' data-q-id='" + rows[i]['Id'] + "'>Like</button> " + rows[i]['Likes'] + "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button class='comment_button' data-q-id='" + rows[i]['Id'] + "' >Comments</button>" + rows[i]['Comments'] +  "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button class='republish_button' data-q-id='" + rows[i]['Id'] + "'>Republish</button>" +rows[i]['Repubilshes'] + " </div>";
				}
			}								
//******************************************************************************************************
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
							j += "<div id='blog_post'> <h4>"  + rows[i]['username'] + "<h4 id = 'repub_post'> Republished Post </h4><div id = blog_text><p>" + rows[i]['blog'];
							j += " </p></div> <button class='like_button' data-q-id='" + rows[i]['Id'] + "'>Like</button> " + rows[i]['Likes'] + "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button class='comment_button' data-q-id='" + rows[i]['Id'] + "' >Comments</button>" + rows[i]['Comments'] + "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button class='republish_button' data-q-id='" + rows[i]['Id'] + "'>Republish</button>" +rows[i]['Repubilshes'] + " </div>";
						}
					}
				}
				t += j;
				$('#content-right').html(t);  // display the table into <div> of id='tr2-result-pane'		
//***********************************************************************************************************************************
				$('.like_button').click(function() 
				{  // 'click' event registration for the above 'Delete' buttons, using the attribute selection                
					//alert($(this).attr('data-q-id'));
					var query9 = {page: 'MainPage', command: 'like', qid: $(this).attr('data-q-id')};
					$.post(url,query9);
					window.location.reload();
				});
//**********************************************************************************************
				$('.republish_button').click(function() 
				{  // 'click' event registration for the above 'Delete' buttons, using the attribute selection
					//alert($(this).attr('data-q-id'));
					var query90 = {page: 'MainPage', command: 'repub', qid: $(this).attr('data-q-id')};
					$.post(url,query90);
					window.location.reload();			
				});
//*****************************
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
							t += "<div id='comm_post'>Username:" + rows_comments[i]['username'] + " Comments:" + rows_comments[i]['comment'];
							t += "<button class='delete-comment-button' data-c-id='" + rows_comments[i]['CID'] + "' data-post-id ='" + rows_comments[i]['PID'] + "'>Delete</button>"  +" </div>";
					}
				
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
//Load Followers*******************
			var queryFollow = {page: 'ProfilePage', command: 'getFollowers2', user:'<?php echo $username; ?>'};
				
			$.post(url,queryFollow, function(data5)
			{
				//alert(data5);  
				var rows = JSON.parse(data5);                      
				var follows = 0;
				for (var i = 0; i < rows.length; i++) 
				{  // for each row
					follows = follows + 1;
				}
				$('#followers').html(follows);
			});
			$('#content-right table button').click(function() 
			{  // 'click' event registration for the above 'Delete' buttons, using the attribute selection
                          
				//alert($(this).attr('data-q-id'));
				var query2 = {page: 'MainPage', command: 'delete', qid: $(this).attr('data-q-id')};
				$.post(url,query2);
				$(this).parent().parent().remove();
			});
		});
		document.getElementById("profile_pic").src = 'https://cs.tru.ca/~challman/Project/uploads/<?php echo $username ?>/profile_pic.png';
		
		
		//******************If the user if following this user, change the html inside the follow button to following. (When page loads)
		var queryFollowing = {page: 'userProfile', command: 'following', fid:'<?php echo $username; ?>'};
		$.post(url,queryFollowing, function(d)
		{
			//alert("Data: " + d);  
			var rows = JSON.parse(d);
			var queryFollowing = {page: 'userProfile', command: 'getid', fid:'<?php echo $username; ?>'};
			$.post(url,queryFollowing, function(dataid)
			{
				//***** GET THE CURRENT USERS USER ID*****************
				var rows2 = JSON.parse(dataid);
				//alert("Current User ID = " + rows2);
				
				//**** IF THE CURRENT USER ID IS EQUAL TO ONE OF THE FOLLOWER IDS
				for (var i = 0; i < rows.length; i++) 
				{  
					//alert("Followers ID: " + rows[i]['FID']);
					//alert("Current User ID: " + rows2);
					if(rows[i]['FID'] == rows2)
					{
						//alert("match");
						$('#follow-btn').html("Following");
					}
				}			    
				
			});		
		});
	});
</script>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<html>
	<!-- Layout Top -->
	<div id='layout-top2'>
		<h1><?php echo $_POST['Username']; ?>'s Profile</h1>
	</div>
	
	<!-- Layout Main -->
	<div id='layout-main'>
		
		<!-- Comment Modal Window -->
		<div id='comment-box' class='modal-window'>
			
			<h2 style='text-align:center'>Comments!</h2>
			<input type="text" id="comment" name="comment" required><br>
			<div id='comment-section'>
				<div id='user-comment-result'>
					User Comments Shown Here
				</div>
			<div id="user_form"></div>
			</div>
			<input type="button" value="Cancel" class="cancel_btn2">
			<input type="button" value="Submit" class="submit_comment">
		</div>
		
		<!-- Layout Main Left -->
		<div id='layout-main-left2'>
			<div id='content-left2'>
				<!-- Profile Picture -->
				<div id='profile_picture'>
					<img id = 'profile_pic' alt="Profile Picture" width="150" height="150">
				</div>
				<!-- Username -->
				<h2>User: <?php echo $_POST['Username']; ?></h2>
				<label id = 'followers_label'>Followers: </label><h4 style='display:inline;' id='followers'></h4>
				
				<!-- Return to Main Page-->
				<form method ="POST" action="Project_controller.php" id="return_form">
					<input type='hidden' name='page' value='ProfilePage'>
					<input type='hidden' name='command' value='return'>
					<input type="submit" value="Return to Main Page" id='return_btn'><br>
				</form>
			</div>
		</div>
		<!-- Layout Main Right -->
		<div id='layout-main-right2'>
			<h2> <?php echo $_POST['Username']; ?>'s Posts </h2>
			<div id='content-right'>
				Results will be shown here
			</div>
		</div>
	</div>
	
	<!-- Footer  -->
	<div id='layout-bottom'>
		
		<!-- Follow Button -->
		<button id='follow-btn'>Follow</button>
		<!-- Sign Out Button -->
		<form method ="POST" action="Project_controller.php" id="signout_form" style="display:inline;">
			<input type='hidden' name='page' value='userProfile'>
			<input type='hidden' name='command' value='sign_out'> 
			<button type="submit" id='signout-btn'>Sign Out</button>
		</form>
	
	</div>
</html>

<script>
//Timeout functions
	
	var timer = setTimeout(timeout, 1000 * 1000);  // 10 minutes
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
	
//Buttons
	//FOLLOW BUTTON
	$(document).on('click', '#follow-btn', function() 
	{
		//alert($('#follow-btn').html());
		if($('#follow-btn').html() == "Follow")
		{
			var xhttp = new XMLHttpRequest();  // create an AJAX object
			xhttp.onreadystatechange = function() 
			{  // register an event handler for the onreadystatechange event
				if (this.readyState == 4 && this.status == 200) 
				{  // check readyState and status
					$('#follow-btn').html("Following");
					//alert(this.responseText);
					window.location.reload();					
				}	
			};	
			var followvar='<?php echo $username;?>';
			//alert(followvar); //followvar shows the username of the current searched users page 
			var controller = "https://cs.tru.ca/~challman/Project/Project_controller.php";
			var query4="page=userProfile&command=follow" + "&" + "fid=" + followvar;
			xhttp.open("post", controller);  // open the channel to the controller using the post method
			//Send the proper header information along with the request
			xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");  // setRequestHeader()
			// send the query
			xhttp.send(query4);
		}
		else	
		{
			var xhttp = new XMLHttpRequest();  // create an AJAX object
			xhttp.onreadystatechange = function() 	
			{  // register an event handler for the onreadystatechange event
				if (this.readyState == 4 && this.status == 200)
				{  // check readyState and status
					$('#follow-btn').html("Follow");
					//alert(this.responseText);
					window.location.reload();					
				}
			};	
		
			var followvar2='<?php echo $username;?>';
			//alert(followvar2); //followvar shows the username of the current searched users page 
		
			var controller2 = "https://cs.tru.ca/~challman/Project/Project_controller.php";
			var query42="page=userProfile&command=unfollow" + "&" + "fid=" + followvar2;
			xhttp.open("post", controller2);  // open the channel to the controller using the post method
			//Send the proper header information along with the request
			xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");  // setRequestHeader()
			// send the query
			xhttp.send(query42);
		}
	});
	
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
	#content-right
	{
		position:absolute; 
        top:100px; left:200px;
        width:75%; height:75%; 
        background-color:skyblue; 
        padding:10px;
		overflow:scroll;
	}
	
/* Header Styles */ 
	
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
	#followers_label
	{
		font-size:1.5em;
		
	}
	#followers
	{
		font-size:1.5em;
	}
	
/* Button Styles */ 
	
	button
	{
		margin:10px;
	}
	
	#repub_post
	{
		display:inline-block;
		float:right;
	}
	
	#return_btn
	{
		margin:10px;
		width:50%;
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
	.cancel_btn2
	{
		position: absolute;
		left:    0;
		bottom:   0;
	}
/* Input Styles */ 
	
	#question
	{
		width:80%;
	}
	
	#term
	{
		width:80%;
	}
	
	#blog_post
	{
		background-color:silver;
		border-style:solid;
	}
	
	#blog_text
	{
		background-color:white;
		margin:20px;
	}
	#profile_picture
	{
		text-align:center;
		margin:10px;		
	}
		
	#profile_pic
	{
		border-style:solid;
	}
	
	#comment
	{
		width:80%;			
	}
	.comm_post
	{
		background-color:white;
		border-style:solid;
		width:100%;
	}
		
/*  Modal Styles */
		
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
	#comment-section{
			bottom:5%;
			
			display:inline-block;
			height:80%;
			width:80%;
			left:25%;
			text-align:center;
			
			
		}
	

	</style>