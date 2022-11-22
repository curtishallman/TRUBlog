<script>
		
		var c;
		
		//When the window loads
		window.addEventListener('load', function() 
		{
			var url = 'https://cs.tru.ca/~challman/Project/Project_controller.php';  // test program for 'ListQuestions' from TRUQA
			var query7 = {page: 'MainPage', command: 'getBlog'};
			// jQuery post to retrieve blogs
			$.post(url, query7, function(data) 
			{
				//alert("Load Blogs Data: " + data);  //SHOWS JSON FORMAT OF ALL BLOG POSTS
				var rows = JSON.parse(data);  // convert blogs JSON string to an object           
				var t = "";
				//For each blog post returned, create the blog post and fill in the information from the server. 
				for (var i = 0; i < rows.length; i++) 
				{  
					//alert("Current Post ID: " + rows[i]['Id']); //THE CURRENT BLOG POST ID 
					t += "<div class='blog_post'> <div class = 'blog_top'> <img id = 'profile_pic'src='https://cs.tru.ca/~challman/Project/uploads/" + rows[i]['username'] + "/profile_pic.png' alt='Profile Picture' width='100' height='100'><div id = 'blogpost_username'><h4> " + rows[i]['username'] + "</h4> "  + rows[i]['Id'] + " </div></div> <div id = blog_text><p>" + rows[i]['blog'];
					t += " </p></div> <button class='like_button' data-q-id='" + rows[i]['Id'] + "'>Like</button> " + rows[i]['Likes'] + "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button class='comment_button' data-q-id='" + rows[i]['Id'] + "' >Comments</button>" + rows[i]['Comments'] + "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button class='republish_button'  data-q-id='" + rows[i]['Id'] + "'>Republish</button>" +rows[i]['Repubilshes'] + " </div>";
				}
				
				//When Delete Post Button Is Clicked. 
				$(document).on('click','.delete-post-button', function()
				{
					//Send Query to Delete the Post from the database. 
					var url = 'https://cs.tru.ca/~challman/Project/Project_controller.php';                       
					var query2 = {page: 'MainPage', command: 'delete_post', CID: $(this).attr('data-c-id'), qid: $(this).attr('data-q-id')};
					//Current Post ID!!! 
					//alert("Post ID: " + $(this).attr('data-c-id'));
					$.post(url,query2, function(data)
					{
						//alert("Delete Post Data: " + data);
					});
					//Remove the post visually from the user.
					$(this).parent().parent().parent().remove();
			
				});
				
				//********When You Click On the Blog Post Comment Button
				$(document).on('click','.comment_button', function()
				{
					//Show the Comment Modal Window
					$('#comment-box').css("display","block");
					$('#blanket').css("display","block");
					
					//Current Post ID showing Comments 
					 c = $(this).attr('data-q-id'); //Gets correct value
					//alert("Initial: Current Posts  ID: " + c); // Correct*
					
					//Send Query to retrive comments from DB where PID = this posts ID. 
					var url = 'https://cs.tru.ca/~challman/Project/Project_controller.php';
					var query90 = {page: 'MainPage', command: 'getComm', qid: $(this).attr('data-q-id')};
					$.post(url, query90, function(data) 
					{
						$('#user-comment-result').html(""); //set the built comment section into the user comment area
						//alert("Comments: " + data);  // Show JSON Format of Comments
						var rows_comments = JSON.parse(data);  // convert a JSON string to an object 
                           
						var t = ""; //string to build comment area
						for (var i = 0; i < rows_comments.length; i++) 
						{  // for each row
					
							t += "<div class='comm_post'> Username:" + rows_comments[i]['username'] + " Comment:" + rows_comments[i]['comment']; 
							t += "<button class='delete-comment-button' data-c-id='" + rows_comments[i]['CID'] + "' data-post-id ='" + rows_comments[i]['PID'] + "'>Delete</button>"  +" </div>";
							
						}
						var PostId = rows_comments[0]['Id'];
						//alert($('.comment_button').attr('data-q-id'));
						$('#user-comment-result').html(t); //set the built comment section into the user comment area
						
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
					});	
				});
				//***********************
				$('#content-right').html(t);  // display the table into <div> of id='tr2-result-pane'
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
			});
			
		});
		</script>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	
		<div id='layout'>
		
		<div id='layout-top2'>
			<h1>TRU Blog</h1>
			<h2>User: <?php echo $_POST['username']; ?></h2>
			<br>
		</div>

		<div id='layout-main'>
			<div id='blanket'></div>

			<div id='blog-box' class='modal-window'>
				<h2 style='text-align:center'>Create a blog post!</h2>	
				<form method ="POST" action="Project_controller.php" id="blog_post">
					<input type='hidden' name='page' value='MainPage'>
					<input type='hidden' name='command' value='blog'>
					<textarea id="blog" name="blog" cols="40" rows="5" maxlength='400' style='resize: none' required></textarea><br>
					<input type='button' value="Cancel" id="cancel_btn1">
					<input type="button" value="Submit" id="submit_btn1">
				</form>
			</div>
	
			<div id='search-box' class='modal-window'>
				<h2 style='text-align:center'>Search Users!</h2>
				<input type='hidden' name='page' value='MainPage'>
				<input type='hidden' name='command' value='search'>
				<input type="text" id="term" name="term" maxlength='50' required><br>
				<div id='user-search-result'>
					User Results Shown Here
				</div>
				<div id="user_form"></div>
				<input type="button" value="Cancel" id="cancel_btn2">
				<input type="button" value="Submit" id="submit_btn2">
			</div>
		
			<div id='comment-box' class='modal-window'>
				<h2 style='text-align:center'>Comments!</h2>
				<input type='hidden' name='page' value='MainPage'>
				<input type='hidden' name='command' value='comment'>
				<input type="text" id="comment" name="comment" maxlength='50' required><br>
				<div id='comment-section'>
					<div id='user-comment-result'>
						User Comments Shown Here
					</div>
					<div id="user_form"></div>
				</div>
				<input type="button" value="Cancel" id="cancel_btn2">
				<input type="button" value="Submit" class="submit_comment_button">
			</div>


			<div id='layout-main-left2'>
	
				<div id='content-left2'>
					<div id='content-left-house'>
						<br><br><br><br>
						<div id = 'posted'></div>
						<button id='post-blog-btn'>Post Blog</button><br>
						<button id='search-user-btn'>Search User</button><br>
		
						<form method ="POST" action="Project_controller.php" id="my_profile">
							<input type='hidden' name='page' value='MainPage'>
							<input type='hidden' name='command' value='profile'>
							<input type="submit" value="My Profile" id='profile_btn'><br>		
						</form>
		
						<form method ="POST" action="Project_controller.php" id="signout_form">
							<input type='hidden' name='page' value='MainPage'>
							<input type='hidden' name='command' value='sign_out'> 
							<button type="submit" id='signout-btn'>Sign Out</button>
						</form>
					</div>
				</div>
			</div>
			<div id='layout-main-right2'>
				<h2> Post Feed </h2>
				<div id='content-right'>
					Results will be shown here
				</div>
				<div id='like_div'></div>
			</div>
		</div>
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
	
	
//Modal Window and Button Listeners

	
	$(document).on('click','#post-blog-btn', function()
	{
		$('#blog-box').css("display","block");
		$('#blanket').css("display","block");
	});
	
	
	$(document).on('click','#search-user-btn', function()
	{
		$('#search-box').css("display","block");
		$('#blanket').css("display","block");
	});
	
	$(document).on('click', '#cancel_btn1', function() 
	{
		$('#blog-box').css("display","none");
		$('#blanket').css("display","none");
	});
	
	
	$(document).on('click', '#cancel_btn2', function() 
	{
		$('#search-box').css("display","none");
		$('#blanket').css("display","none");
		$('#comment-box').css("display","none");
	});
		
	$(document).on('click', '.submit_comment_button', function() 
	{
		var url = 'https://cs.tru.ca/~challman/Project/Project_controller.php';                       
		var query2 = {page: 'MainPage', command: 'comment', pid: c, content:$('#comment').val()  }; //pid needs to be the ID of the blog post. Content needs to be what is in the comment box. 
		//alert(c);
		//alert($('#comment').val());		
		$.post(url,query2, function(data)
		{
			//alert("Submit Comment Data: " + data);
		});
		//$('#blanket').css("display","none");
		//$('#comment-box').css("display","none");
		window.location.reload();
	});
		//**********************************************************************************
		
	$('#submit_btn1').click(function() 
	{
		$('#blog-box').css("display","none");
		$('#blanket').css("display","none");
        var xhttp = new XMLHttpRequest();  // create an AJAX object
        xhttp.onreadystatechange = function() 
		{
			// register an event handler for the onreadystatechange event
            if (this.readyState == 4 && this.status == 200) 
			{  // check readyState and status
                $('#posted').html(this.responseText);  // display the text response to the above <div> using jQuery
				window.location.reload();
            }
        };	
		
        var controller = "https://cs.tru.ca/~challman/Project/Project_controller.php";
        var query="page=MainPage&command=blog" + "&" + "blog=" + $("#blog").val().trim();
        xhttp.open("post", controller);  // open the channel to the controller using the post method
        //Send the proper header information along with the request
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");  // setRequestHeader()
        // send the query
        xhttp.send(query);
    });

	$('#submit_btn2').click(function() 
	{  // when the above button is clicked, call the next function
		var url = 'https://cs.tru.ca/~challman/Project/Project_controller.php';  // test program for 'ListQuestions' from TRUQA
		var query = {page: 'MainPage', command: 'search', term: $("#term").val().trim()};
		// jQuery post
		$.post(url, query, function(data) 
		{
			//alert(data);  // just to see the format of the data
			var rows = JSON.parse(data);  // convert a JSON string to an object 
			//   the object will be a linear array of associative arrays
			var t = '<table id="search_table">';
			t += '<tr>';
			t += '<th>';
			t += "Username&nbsp";
			t +='</th>';
			t += '</tr>';
		
            for (var i = 0; i < rows.length; i++) 
			{  // for each row
                t += '<tr>';
				t += '<td>' + (rows[i])['Username'] + '</td>';
				t += '<td>';
				t += "<button type='button' data-q-id='" + rows[i]['Username'] + "'>Visit Profile</button>";  // data-q-id: a custom data attribute for question Id
                t += "</td>";
                t += '</tr>';
            }
			t += '</table>';
        
			$('#user-search-result').html(t);  // display the table into <div> of id='tr2-result-pane'
			$('#user-search-result table button').click(function() 
			{  // 'click' event registration for the above 'Delete' buttons, using the attribute selection
                                    // It should not be outside $.post().
				//alert($(this).attr('data-q-id'));
				var query2 = {page: 'MainPage', command: 'user_profile', Username: $(this).attr('data-q-id')};
				$.post(url,query2);
				var str = '<form method ="POST" action="Project_controller.php" id="user_profile_form">';
				str += '<input type="hidden" name="page" value="MainPage"><input type="hidden" name="command" value="user_profile">';
				str += '<input type="hidden" name="Username" value=' + $(this).attr('data-q-id') + '></form>';
				$('#user_form').html(str);
				$('#user_profile_form').submit();
			});
		});
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
        height:100%; 
		top:20%;
		background-color:skyblue;
		border-style:groove;
	}

/*  This houses the buttons on the leftside of the page.  */ 
	#layout-main-left2 
	{
        position:absolute;
        background-color:silver;
        width:25%;
        height:calc(100% - 100px);
		left:0;
		text-align:center;
    }
    #layout-main-right2 
	{
        position:absolute;
		background-color:silver;
		width:75%;
        height:calc(100% - 100px);
        top:(100% - 100px); 
		left:25%; /* to put on right side */
    }
	 #layout-top2 
	 {
        height:175px;
        width:100%;
        position:relative;
        left:0;
        top:0;
		background-color:skyblue;
		border-style:solid;
    }
	
	#content-left-house
	{
		background-color:skyblue;
		width:75%;
		height:75%;
		position:absolute;
		top:100px;
		left:10%;
		border-style:solid;
		
	}
	
/*  content-right houses the blog post feed */
	#content-right
	{
			position:absolute; 
			display:inline-block;
            top:100px; left:200px;
            width:75%; height:75%; 
            background-color:skyblue;
            border-style:solid;			
            padding:10px;
			overflow-y:scroll;		
	}
	
	
/* Heading Styles */ 
	
	
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
		background-color:white;
	}
	
	
/* Buttons Styles */
	
	button
	{
		margin:10px;
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
	
	.submit_comment_button
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
	
	#post-blog-btn
	{
		width: 60%;
		height:10%;
		
	}
	
	#profile_btn
	{
		margin:10px;
		width: 60%;
		height:10%;
	}
	
	
	#search-user-btn
	{
		width:60%;
		height:10%;
	}
	
	#signout-btn
	{
		width:60%;
		height:10%;
	}
	
	#cancel_btn2
	{
		position: absolute;
		left:    0;
		bottom:   0;
	}
	
/* Modal Styles */ 
	
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
	
	
	#blog-box 
	{
        display:none;  /* initially hidden */
        width: 50%; 
		border: 1px solid; 
		background-color:White;
        position: absolute; 
		top: 0; 
		left:25%; 
		bottom:calc(75% - 100px);
        z-index: 9001;  /* over all elements */
		text-align:center;
		
	}
		
	#search-box 
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
		
	#comment
	{
		width:50%;			
	}
		
	
	#question
	{
		width:80%;
	}
	#term
	{
		width:50%;
	}
	
	#user-comment-result
	{
		text-align:center;
		background-color:white;
		left:5%;
		right:5%;
		margin-left:5%;
		margin-right:5%;
		display:block;
	}
	
	
	#user-search-result
	{
		position:absolute;
		overflow-y:scroll;
		height:50%;
		width:50%;
		left:25%;
		background-color:silver;
		text-align:center;
	}
	#search_table
	{
		background-color:silver;
		display:block;
		width:100%;
		left:25%;
		
		
	}
	
	/* Styles for the Blog Post */
	
	.blog_post
	{
		background-color:white;
		border-style:solid;
	}
	.comm_post
	{
		background-color:white;
		border-style:solid;
		width:100%;
	}
	#blog_text
	{
		background-color:white;
		margin:20px;
		overflow-y:scroll;
		overflow-wrap:word-break;
	}
	
	.blog_top
	{
		background-color:silver;
	}
	
	#profile_pic
	{
		margin: 10px;
	}
	#blogpost_username
	{
		background-color:white;
		right:0;
		top: 0;
		display:inline-block;
		width:25%;
		float:right;
		text-align:center;
	}
</style>