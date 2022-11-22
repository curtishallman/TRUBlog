<!DOCTYPE html>

<html>
<head>

	<!-- Login Verification -->
	<script>
	
		window.addEventListener('load', function() 
		{
		<?php
			if ($display_modal_window == 'signin')
			{
				echo 'show_signin();'; // echo JavaScript code
		
				if($error_msg_username != '* Wrong username, or')
				{
					echo 'hide_error_user();';
				}
				else
				{
					echo 'show_error_user();';
				}
		
				if($error_msg_password != '* Wrong password')
				{
					echo 'hide_error_pass();';
				}
				else
				{
					echo 'show_error_pass();';
				}
		
			}
			else if ($display_modal_window == 'signup')
			{
				echo 'show_signup();'; // echo JavaScript code
			
				if($error_msg_signup = '* The username exists')
				{
					echo 'show_error_signup();';
				}
				else
				{
					echo 'hide_error_signup();';
				}
			}
			else
			{
			}           
		?>
	
		});
	</script>
	<title>TRU Blog</title>
	<style>
        #layout-main 
		{
            position:relative; top:0; left:0;
            width:100vw; height:calc(100vh - 50px); 
			background-color:SkyBlue; 
        }
        
		#layout-main-top 
		{
            position:absolute; top:0; left:0;
            width:50%; height:50%; 
            background-color:SkyBlue; 
			text-align:center;
        }
        
		#content-left 
		{
            position:absolute;  left:50%;
            width:100%; height:100%; 
            background-color:White;
			text-align: center;
			border-style: solid;
        }
        
        #layout-bottom 
		{
            position:relative;
			background-color: Silver;
			border-style: ridge;
        }
        
        #blanket 
		{
            background-color:Grey;
            display:none;
            width:100%; height:100%;
            position:fixed;
            top:0; left:0;
            opacity:0.5;
            z-index:998;
        }

        .modal-window 
		{
            width:400px; height:200px;
            border:1px solid black;
            
            display:none;
            background-color:White;
            position:fixed;
            top:150px; left:calc(50% - 201px);
            padding:20px;
            z-index:999;
        }
        
        label.modal-startpage 
		{ 
		display:inline-block; width:80px;
		}  /* to give width */
	</style>
</head>

	<body style='margin:0'>
    
	<!-- Layout Main -->
    <div id='layout-main'>
	
		<!-- Modal Windows -->
		<div id='blanket'></div>
		
		<!-- Modal Signin -->
		<div id='modal-signin' class='modal-window' style='display: relative'>
			<h2 style='text-align:center'>Sign in to TRU Blog!</h2>
			<br>
			<form method='POST' action='Project_controller.php'>
					<input type='hidden' name='page' value='StartPage'>
					<input type='hidden' name='command' value='SignIn'>
				<label class='modal-startpage' for='signin-username'>Username: </label>
					<input id='signin-username' type='text' name='username' required> 
					<span id='error-msg-signin-username'><?php if (!empty($error_msg_username)) echo $error_msg_username; // Display error message if there is ?></span>
					<br><br>
				<label class='modal-startpage' for='signin-password'>Password: </label>
					<input id='signin-password' type='password' name='password' required>
					<span id='error-msg-signin-password'><?php if (!empty($error_msg_password)) echo $error_msg_password; ?></span>
					<br>
				<input id='signin-cancel' type='button' value='Cancel' style='margin:10px; position:absolute; left:0; bottom:0'>
				<input id='signin-submit' type='submit' value='Submit' style='margin:10px; position:absolute; right:0; bottom:0'>
			</form>
		</div>
		
		<!-- Modal Signup -->
		<div id='modal-signup' class='modal-window'>
			<h2 style='text-align:center'>Create your account</h2>
			<br>
			<form method='POST' action='Project_controller.php'>
				<input type='hidden' name='page' value='StartPage'>
				<input type='hidden' name='command' value='SignUp'>
				<label class='modal-startpage' for='signup-username'>Username: </label>
					<input id='username' type='text' name='username' required> 
					<span id='error-msg-signup-username'><?php if (!empty($error_msg_username)) echo $error_msg_username; ?></span>
					<br>
				<label class='modal-startpage' for='signup-password'>Password: </label>
					<input id='signup-password' type='password' name='password' required><br>
				<label class='modal-startpage' for='signup-password'>Email: </label>
					<input id='signup-email' type='email' name='email' required><br>
					<input id='signup-cancel' type='button' value='Cancel' style='margin:10px; position:absolute; left:0; bottom:0'>
					<input id='signup-submit' type='submit' value='Submit' style='margin:10px; position:absolute; right:0; bottom:0'>
			</form>
		</div>
		<!-- Layout Main Top -->
			<div id='layout-main-top'>
			<!-- Content Left -->
				<div id='content-left' style='position:absolute'>
					<br><br><img src='One TRU Logo.png' width='200px' height='50px'>
					<h2>Join TRU Blog!</h2>
					<br><br><br>
					<button id='signup-button' style=' display:inling-block; width:200px; height:40px;'><b>Sign up</b></button>
					<br><br>
					<button id='signin-button' style=' display:inling-block; width:200px; height:40px;'><b>Sign in</b></button>
				</div>
				<!-- Use JS to center content -->
				<script>
					pos = (document.getElementById("layout-main").offsetHeight - document.getElementById("content-left").offsetHeight) / 2;
					document.getElementById("content-left").style.top = pos + "px";
				</script>
			</div>
    </div>

    <div id='layout-bottom'>
        <br>
        &nbsp;&nbsp;&nbsp;&nbsp
        <a href=''>About</a> &nbsp;&nbsp;&nbsp;
        <a href=''>Help center</a> &nbsp;&nbsp;&nbsp;
        <a href=''>Terms of service</a> &nbsp;&nbsp;&nbsp;
        ...
    </div>
    
	<script>
	//On SignIn Click show SignIn Modal Window
    function show_signin() {
        document.getElementById("blanket").style.display = "block";
        document.getElementById("modal-signin").style.display = "block";
    }
    //On Signup Click shows Signup Modal Window
    function show_signup() {
        document.getElementById("blanket").style.display = "block";
        document.getElementById("modal-signup").style.display = "block";
    }
    //When modals are closed, set error messages to blank.
    function delete_error_messages() {
        document.getElementById("error-msg-signin-username").innerHTML = "";
        document.getElementById("error-msg-signin-password").innerHTML = "";
        document.getElementById("error-msg-signup-username").innerHTML = "";
    }
    //BLANKET (When clicked hide modals)
    document.getElementById("blanket").addEventListener("click", function() {
        document.getElementById("blanket").style.display = "none";
        document.getElementById("modal-signin").style.display = "none";
        document.getElementById("modal-signup").style.display = "none";
        delete_error_messages();
    });
	// SIGN IN BUTTON
    document.getElementById("signin-button").addEventListener("click", function() {
        document.getElementById("blanket").style.display = "block";
        document.getElementById("modal-signin").style.display = "block";
    });
    // SIGNUP BUTTON
    document.getElementById("signup-button").addEventListener("click", function() {
        document.getElementById("blanket").style.display = "block";
        document.getElementById("modal-signup").style.display = "block";
    });
    //Signin Cancel Button
    document.getElementById("signin-cancel").addEventListener("click", function() {
        document.getElementById("blanket").style.display = "none";
        document.getElementById("modal-signin").style.display = "none";
        document.getElementById("modal-signup").style.display = "none";
        delete_error_messages();
    });
	//SignUp Cancel Button
    document.getElementById("signup-cancel").addEventListener("click", function() {
        document.getElementById("blanket").style.display = "none";
        document.getElementById("modal-signin").style.display = "none";
        document.getElementById("modal-signup").style.display = "none";
        delete_error_messages();
    });	
</script>
</body>
</html>

