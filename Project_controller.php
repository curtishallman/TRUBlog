<?php
	// Case 1: When no page is sent from the client
	if (empty($_POST['page'])) 
	{ 
                                                      
		$display_modal_window = 'no-modal-window';
		$error_msg_username = '';
		$error_msg_password = '';	
                   
		include ('Project_startpage.php');
		exit();
	}

	require('Project_model.php');  // This file includes some routines to use DB.

	// Case 2: When commands come from StartPage
    if ($_POST['page'] == 'StartPage') 
	{  
		$command = $_POST['command'];
        switch($command) 
		{
			//User is Signing In
			case 'SignIn':
            //echo 'Username = ' . $_POST['username'] . ', Password = ' . $_POST['password'] . '<br>';
            if (is_valid($_POST['username'], $_POST['password']))
			{
				$error_msg_username = 'no-message';
				$error_msg_password = 'no-message';
				$username = $_POST['username'];
				
				//start session 
				session_start();
				$_SESSION['signedin'] = 'YES';
				$_SESSION['username'] = $username;
				include ('Project_mainpage.php');
				
			}
			//Login invalid
            else
			{
                //echo 'Invalid username and password<br>';
				$display_modal_window =  'signin';
				$error_msg_username = '* Wrong username, or';
                $error_msg_password = '* Wrong password';
				include('Project_startpage.php');
 
			}
				exit();
				break;				
            //User signs up for an account
			case 'SignUp':
                
				// doesUserExist checks if a user is already in the database
				if (doesUserExist($_POST['username']) == false)
				{  
					//sign up new user 
					$result = signUpNewUser($_POST['username'],$_POST['password'],$_POST['email']);
					$error_msg_username = '';
					$error_msg_password = '';
					$display_modal_window =  'signin';
					include('Project_startpage.php');
				}
                else
				{
					//echo 'Invalid username and password<br>';
					$display_modal_window =  'signup';
					$error_msg_signup = '* The username already exists';
					include('Project_startpage.php');
					
				}
					exit();
				
			default:
					echo 'Unknown command<br>';
					exit();
        }
    }
	// Case 3: When commands come from 'MainPage' (User Logged In)
	else if ($_POST['page'] == 'MainPage')
	{
		session_start();
		if (!isset($_SESSION['signedin'])) 
		{
			$display_modal_window = 'none';
			include ('Project_startpage.php');
			exit;
		}
		
		$username = $_SESSION['username'];  //Shows user who is logged in. 
		$command = $_POST['command']; //command sent from the mainpage
		
		switch($command) 
		{
		//on page load get all the blogs and display them	
		case 'getBlog':
				
				$data = getBlogs();
				echo json_encode($data);
				exit();
				
		case 'getComm':
			    
				$pid = $_POST['qid'];
				$data = getComm($pid);
				echo json_encode($data);
				exit();
						
		case 'sign_out':
				
				//echo 'Signed out<br>';
				session_unset();
				session_destroy();  // It does not unset session variables. session_unset() is needed.
                $display_modal_window = 'no-modal-window';
				include ('Project_startpage.php');
				exit();
				
		case 'question':
		
				$uid = getUserId($username);
				if(postQuestion($_POST['question'], $uid) == true)
				{
					echo "Ok";
				}
				else
				{
					echo "Error";
				}
				exit();
			
		case 'search':
				
				$term = $_POST['term'];
				$data = searchUser($term);
				echo json_encode($data);
				exit();
				
		case 'like':
				
				$qid = $_POST['qid'];

				if(likePost($qid) == true) 
				{
					echo "Ok";
				}
				else
				{
					echo "Error";
				}
				exit();
				
		case 'repub':
				
				$qid = $_POST['qid'];
				$pid = getUserId($username);
				incrementRepub($qid);
		
				if(republishPost($qid,$pid,$username) == true)
				{
					echo "Ok";
				}
				else
				{
					echo "Error";
				}
				exit();
			
		//POST A COMMENT 	
		case 'comment':
				
				$USERNAME = $_SESSION['username'];
				$PID = $_POST['pid']; //pid is the id of the blogpost!
				$UID = getUserId($USERNAME); //user whos logged in, their user ID. 
				$COMMENT = $_POST['content']; //content of the comment written. 
				echo $PID;//POST ID
				echo $UID;//USER ID
				echo $USERNAME;//USERNAME
				echo $COMMENT;//COMMENT
				incrementComment($PID);
			
				if(commentPost($PID,$UID,$USERNAME,$COMMENT) == true)
				{
					echo "Ok";
				}
				else
				{
					echo "Error";
				}
				exit();
				
		//DELETE A COMMENT 	
		case 'delete_comment':
				$PID = $_POST['PID'];
				$CID = $_POST['CID'];
				decrementComment($PID); //Need to pass Id from Blogs.
				if(deleteComment($CID) == true) 
				{
					echo "Ok";
				}
				else
				{
					echo "Error";
				}
				exit();
			 
		case 'delete_post':
				$cid = $_POST['CID'];
				if(deletePost($cid) == true) 
				{
					echo "Ok";
				}
				else
				{
					echo "Error";
				}
				exit();
				
		case 'getRepub':
				
				$data = getRepubs($username);
				echo json_encode($data);
				exit();

		case 'blog':
				
				$uid = getUserId($username);
				if(postBlog($_POST['blog'],$uid,$username) == true)
				{
					echo "Ok";
				}
				else
				{
					echo "Error";
				}
				exit();
				
		case 'profile':
				
				include('Project_profilepage.php');
				exit();
				
				
		case 'delete':
				
				$qid = $_POST['qid'];
				if(deleteQuestion($qid) == true) 
				{
						echo "Ok";
				}
				else
				{
					echo "NOk";
				}
				exit();
				
		case 'user_profile':
				
				$username = $_POST['Username'];
				include('Project_userprofile.php');
				exit();
			
			
				
		default:
                
				echo 'Unknown command<br>';
                exit();
        }
	}
	else if ($_POST['page'] == 'ProfilePage')
	{
		session_start();
		$username = $_SESSION['username'];
		$_POST['username'] = $username;
		$command = $_POST['command'];
	
		switch($command) 
		{
		
		case 'return':
					
				include('Project_mainpage.php');
				exit();
				
		case 'change_profile':
		
				$target_dir = "uploads/$username/";
				if (!is_dir($target_dir)) 
				{
					mkdir($target_dir, 0777, true);
				}                                 // 'fileToUpload[]' is the name of file type input in the client code.
				$data2 = [];
		
				for ($i = 0; $i < count($_FILES['fileToUpload']['name']); $i++) 
				{
					$target_file = $target_dir . "profile_pic.png";  // basename() - just file name
					if (!file_exists($target_file))
					{
					 	
						if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"][$i], $target_file))
						{
							chmod($target_file, 0777);
							$result =  "The file " . basename( $_FILES["fileToUpload"]["name"][$i]) . " has been uploaded.<br>";
							$data2[] = basename($_FILES["fileToUpload"]["name"][$i]);
						}
						else
						{
							$result = "Sorry, there was an error uploading your file.<br>";
						}
					}
					else
					{
						unlink($target_file);
						
						if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"][$i], $target_file))
						{
							chmod($target_file, 0777);
							$result =  "The file " . basename( $_FILES["fileToUpload"]["name"][$i]) . " has been replaced.<br>";
							$data2[] = basename($_FILES["fileToUpload"]["name"][$i]);
						}
						else
						{
							$result =  "Sorry, there was an error uploading your file.<br>";
						}
					}
				
				}
				
				include('Project_profilepage.php');
				exit();
						
		case 'getFollowers':
				
				$uid = getUserId($username);
				$data = getFollowers($uid);
				echo json_encode($data);
				exit();
				
		case 'getFollowers2':
				$user = getUserId($_POST['user']);
				$data = getFollowers($user);		
				echo json_encode($data);
				exit();
		
		case 'unsubscribe':
				$userID = getUserId($_POST['unsub_username']);
				$data = unsubscribe($userID);
				include('Project_startpage.php');
				exit();
				
		case 'sign_out':
				//echo 'Signed out<br>';
				session_unset();
				session_destroy();  // It does not unset session variables. session_unset() is needed.
                $display_modal_window = 'no-modal-window';
				include ('Project_startpage.php');
				exit();
				
		case 'edit_post':
				$qid = $_POST['CID'];
				
				$data = getBlogContent($qid);
				echo  json_encode($data);
				exit();
				
		case 'submit_postedit':
				$qid = $_POST['something'];
				echo $qid;
				$content = $_POST['content'];
				$data = setBlogContent($qid,$content); 
				echo $data;
				exit();
				
	
		default:
                echo 'Unknown command<br>';
                exit();
	
	
	
		}
	}	
	else if ($_POST['page'] == 'userProfile')
	{
		session_start();
		$followedBy = $_SESSION['username'];
		$followedByID = getUserId($followedBy);
		$command1 = $_POST['command'];
	
		switch($command1) 
		{

		case 'follow':
				$userToFollow = $_POST['fid'];
				$userToFollowID = getUserId($userToFollow);

				if(followUser($userToFollowID,$followedByID) == true)
				{
					echo "Ok";
				}
				else
				{
					echo followUser($userToFollowID,$followedByID);
				}
				exit();
				
				
		case 'following':
				
				$userIsFollowing = $_POST['fid'];
				$userIsFollowingID = getUserId($userIsFollowing);
				//echo $userIsFollowingID;
				$followers = getFollowing($userIsFollowingID, $followedByID);
				echo json_encode($followers);
			
				exit();
			
			
		case 'unfollow':	
				$userIsFollowing = $_POST['fid'];
				$uid = getUserId($userIsFollowing);
		
				if(unfollowUser($uid,$followedByID) == true) 
				{
					echo "Ok";
				}
				else
				{
					echo "Error";
				}
				
				exit();
		
	
		case 'getid':
			
				$userId = getUserId($followedBy);
				echo json_encode($userId);
				exit();
				
		case 'sign_out':
				
				//echo 'Signed out<br>';
				session_unset();
				session_destroy();  // It does not unset session variables. session_unset() is needed.
                $display_modal_window = 'no-modal-window';
				include ('Project_startpage.php');
				exit();
				
		default:
                echo 'Unknown command<br>';
                exit();		
	}	
}
?> 