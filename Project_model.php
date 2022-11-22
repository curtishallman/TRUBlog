<?php
 //login to database and set session to $conn
 $conn = mysqli_connect('localhost', 'challman', 'challman136', 'C354_challman');
 
    
	//START PAGE FUNCTIONS AND TEST PROCEDURES*************
	
	//SignUp Feature - Signs up a new user into the database. 
	function signUpNewUser($username, $password, $email)
	{
		global $conn;
		$current_date = date("Ymd");
		$sql = "insert into Users values (NULL, '$username', '$password', '$email', $current_date)";  
		$result = mysqli_query($conn, $sql);
		return $result;
		
	}
	
	//doesUserExist - Test Procedure for SignUp. - Checks if the user is already in the DB. 
	function doesUserExist($username)
	{
		global $conn;
		$sql = "select * from Users where Username = '$username'";
		$result = mysqli_query($conn, $sql);
		if (mysqli_num_rows($result) > 0)
			return true;
		else
			return false;
		
	}
	
	//getUserId - Test Procedure for SignUp - Verifies User has signedup by returning the userId. 
	function getUserId($u) {
		
		global $conn;
    
		$sql = "select * from Users where Username = '$u'";  // where Username is 
		$result = mysqli_query($conn, $sql);

		if (mysqli_num_rows($result) <= 0)
			return -1;
		else {
        $row = mysqli_fetch_assoc($result);
        return $row['ID'];
		}
	}
	
	//IsValid - Test Procedure for SignIn - Checks if the entered information matches the DB.  
	function is_valid($username, $password) 
	{
		global $conn;
		$sql = "select * from Users where Username = '$username' AND Password = '$password' ";
		$result = mysqli_query($conn, $sql);
		
		if (mysqli_num_rows($result) > 0)
        return true;
		else
        return false;
		
	}
	
	
	//MAIN PAGE FUNCTIONS AND TEST PROCEDURES****************

	//Search for a user
	function searchUser($term){
		global $conn;
		
	$sql = "select * from Users where Username like '%$term%'"; //Similar usernames
	$result = mysqli_query($conn,$sql);
	
	$data = []; 
	while ($row = mysqli_fetch_assoc($result))
		$data[] = $row;
	
	return $data;
		
		
		
	}
	
	//delete a question
	function deleteQuestion($qid){
		global $conn;
		
		$sql = "delete from Questions where Id = $qid";
		$result = mysqli_query($conn,$sql);
		
		if(mysqli_affected_rows($conn) > 0){
			return true;
		}else{
			return false;
		}
	}
	//Create Blog Post 
	function postBlog($blog, $uid, $username){
		global $conn;
		$current_date = date("Ymd");
		$likes = 0;
		$comments = 0;
		$repubs = 0;
		
		
		$sql = "insert into Blogs values(NULL,'$blog','$username',$uid, $current_date, $likes, $comments, $repubs)";
		$result = mysqli_query($conn,$sql);
		
		
		
		if(mysqli_affected_rows($conn) > 0){
			return true;
		}else{
		return false;
		
	}
	}
	//Retrieve Blog Posts
	function getBlogs(){
		global $conn;
		
		$sql = "select * from Blogs";
		$result = mysqli_query($conn, $sql);

		$data = [];
		while ($row = mysqli_fetch_assoc($result))
        $data[] = $row;

		return $data;
		
		
	}
	//Follow the User
	function followUser($uid, $fid)
	{
		global $conn;
		
		$sql = "insert INTO Followers values(NULL, $uid, $fid)";
		$result = mysqli_query($conn, $sql);
		
		if(mysqli_affected_rows($conn) > 0)
		{
			return true;
		}
		else
		{
			return false;
		}	
	}
	//Unfollow User
	function unfollowUser($uid, $fid){
		
		global $conn;
		
		$sql = "delete from Followers where UID = $uid and FID = $fid";
		$result = mysqli_query($conn,$sql);
		
		if(mysqli_affected_rows($conn) > 0){
			return true;
		}else{
			return false;
		}
		
		
	}
	//Retrieve Unique Followers to get follower count.
	function getFollowers($uid){
		global $conn;
		
		$sql ="select DISTINCT FID from Followers where UID = $uid";
		$result = mysqli_query($conn, $sql);
		
		$data = [];
		while ($row = mysqli_fetch_assoc($result))
        $data[] = $row;

		return $data;	
	}
	
	//Gets the following id, used in a different operation.
	function getFollowing($uid,$pid){
		global $conn;
		
		$sql ="select DISTINCT UID,FID from Followers where UID = $uid";
		$result = mysqli_query($conn, $sql);
		
		$data = [];
		while ($row = mysqli_fetch_assoc($result))
        $data[] = $row;
	
	
		return $data;
		
	}
	//Like the post.
	function likePost($pid){
		global $conn;
		$like_count = getLikes($pid);
		$count = $like_count[0];
		
		$incrementer = 1;
		$count += $incrementer;
		
	
		
		$sql = "UPDATE Blogs SET Likes = $count WHERE Id = $pid";
		$result = mysqli_query($conn, $sql);
		if(mysqli_affected_rows($conn) > 0)
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	//Retireve Likes for a post.
	function getLikes($pid){
		global $conn;
		
		$sql = "select Likes from Blogs where Id = $pid";
		$result = mysqli_query($conn, $sql);
		
		
		$row = mysqli_fetch_assoc($result);
        $data = $row['Likes'];
		return $data;
		
	}
	//get number of republishes
	function getRepublishes($pid){
		global $conn;
		
		$sql = "select Repubilshes from Blogs where Id = $pid";
		$result = mysqli_query($conn, $sql);
		
		$row = mysqli_fetch_assoc($result);
		$data = $row['Repubilshes'];
		
		
		return $data;
		
		
		
		
		
	}
	//incremenet repubs by one
	function incrementRepub($pid){
		global $conn;
		
		$repub_count = getRepublishes($pid);
		$count = $repub_count[0];
		
		$incrementer = 1;
		$count += $incrementer;
		
		$sql2 = "UPDATE Blogs SET Repubilshes = $count WHERE Id = $pid";
		$result = mysqli_query($conn, $sql2);
		
		if(mysqli_affected_rows($conn) > 0)
		{
			return true;
		}
		else
		{
			return false;
		}
		
	}
	//Republish the post to your profile.
	function republishPost($pid,$uid,$username){
		global $conn;
		
		$sql = "insert INTO Republishes values(NULL, $pid, $uid, '$username')";
		$result = mysqli_query($conn, $sql);
		if(mysqli_affected_rows($conn) > 0)
		{
			return true;
		}
		else
		{
			return false;
		}	
	}
	//Get repubs count
	function getRepubs($username){
		global $conn;
		
		$sql = "select PID from Republishes where username = '$username' ";
		$result = mysqli_query($conn, $sql);

		
		//$row = mysqli_fetch_assoc($result);
		
		//if($row != null){
        //$data[] = $row['PID'];
		//}else{
		 // $data[] = "";	
		//}
		
		$data = [];
		while ($row = mysqli_fetch_assoc($result))
        $data[] = $row['PID'];

		return $data;
		
		
	}
	//Write a comment!
	function commentPost($pid,$uid,$username,$comment){
		global $conn;
		$sql = "insert INTO Comm values(NULL, $pid, $uid, '$username', '$comment')";
		$result = mysqli_query($conn, $sql);
		if(mysqli_affected_rows($conn) > 0)
		{
			return true;
		}
		else
		{
			return false;
		}	
		
		
	}
	
	//incremenet comments by one
	function incrementComment($pid){
		global $conn;
		
		$comment_count = getComments($pid);
		$count = (int)$comment_count[0];
		
		$incrementer = 1;
		$count += $incrementer;
		
		$sql2 = "UPDATE Blogs SET Comments = $count WHERE Id = $pid";
		$result = mysqli_query($conn, $sql2);
		
		if(mysqli_affected_rows($conn) > 0)
		{
			return true;
		}
		else
		{
			return false;
		}
		
	}
	//decrement Comment
	function decrementComment($pid){
		global $conn;
		
		$comment_count = getComments($pid); //Blogs where Id = $blogId (returns array)
		$count = (int)$comment_count[0];
		
		$decrementer = 1;
		$count -= $decrementer;
		
		$sql = "UPDATE Blogs SET Comments = $count WHERE Id = $pid";
		$result = mysqli_query($conn,$sql);
		
		if(mysqli_affected_rows($conn) > 0) {
			
			return true;
		}
		else
		{
			return false;
		}

	}
	//delete comment
	function deleteComment($cid)
	{
		global $conn;
		$sql = "DELETE FROM Comm where CID = $cid";
		$result = mysqli_query($conn, $sql);
		if(mysqli_affected_rows($conn) > 0)
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	//Retrieve the number of comments a certain post has.
	function getComments($blogId){
		
		global $conn;
		
		$sql = "select Comments from Blogs where Id = $blogId"; //PID in Json string is blogId
		$result = mysqli_query($conn, $sql);
		
		$row = mysqli_fetch_assoc($result);
		$data = $row['Comments'];
		
		
		return $data;

		
		
	}
	//Retrieve comment number from post
	function getComm($pid){

	global $conn;
		
		$sql = "select * from Comm where pid = '$pid' ";
		$result = mysqli_query($conn, $sql);

		
		//$row = mysqli_fetch_assoc($result);
		
		//if($row != null){
        //$data[] = $row['PID'];
		//}else{
		 // $data[] = "";	
		//}
		
		$data = [];
		while ($row = mysqli_fetch_assoc($result))
        $data[] = $row;

		return $data;


	}

		//Unsubscribe :( 
		function unsubscribe($uid){
		global $conn;
		
		$sql = "delete from Blogs where Uid = $uid";
		$result = mysqli_query($conn,$sql);
		$sql2 = "delete from Followers where FID = $uid"; 
		$result2 = mysqli_query($conn,$sql2);
		$sql3 = "delete from Users where ID = $uid";
		$result3 = mysqli_query($conn,$sql3);
		$sql4 = "delete from Republishes where UID = $uid";
		$result4 = mysqli_query($conn,$sql4);
		$sql5 = "delete from Comm where UID = $uid";
		$result5 = mysqli_query($conn,$sql5);
		
		return true;
		
		
		
		
			
			
		}
		//Delete a post.
		function deletePost($qid){
			global $conn;
			
			$sql = "delete from Blogs where Id = $qid";
			$result = mysqli_query($conn,$sql);
			
			$sql2 = "delete from Comm where PID = $qid";
			$result2 = mysqli_query($conn,$sql2);
			
			$sql3 = "delete from Republishes where PID = $qid";
			$result3 = mysqli_query($conn,$sql3);
			
			
		
		if(mysqli_affected_rows($conn) > 0){
			return true;
		}else{
			return false;
		}
		}
		//Retrieve the content of the blog post for editing.
		function getBlogContent($qid){
		global $conn;

		$sql = "select * from Blogs where Id = $qid";
		$result = mysqli_query($conn,$sql);
		
		$data = [];
		while ($row = mysqli_fetch_assoc($result))
        $data[] = $row;

		return $data;
	
		}
		//Set the new blog post content from the edit.
		function setBlogContent($qid, $content){
			global $conn;
			$sql = "UPDATE Blogs SET blog = '$content' WHERE Id = $qid";
			
			$result = mysqli_query($conn,$sql);
			
			if(mysqli_affected_rows($conn) > 0) {
			
			return true;
			}
			else
			{
				return false;
			}	
		}
	
?>