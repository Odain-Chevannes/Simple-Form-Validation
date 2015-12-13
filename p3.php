<?php
/**
*@Author Odain Chevannes
*@Date 11-26-2015
*
Simple html form validation
*
**/
?>
 <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

 <head>
 	<title>MP creation form</title>
 	<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
 	<link rel="stylesheet" type="text/css" href="p2a.css"/>
	<!--using the css from part1 for the table-->
 	<link rel="stylesheet" type="text/css" href="p1a.css"/>
 </head>
 <style type="text/css">
 	.error{
 		color: #ff0000;
 	}
 </style>
 <?php
	//basic Cross Site Scripting prevention
	function test_input($data) {
	   $data = trim($data);
	   $data = stripslashes($data);
	   $data = htmlspecialchars($data);
	   return $data;
	}
$fnameErr = $lnameErr = $constErr = $emailErr = $yearsErr = $pass1Err = $passErr ="";
$fname = $lname = $con = $years =  $email = $pass1 = $pass2 = $hash = $options = $salt = "";
if($_SERVER["REQUEST_METHOD"] == "POST"){

	if(!isset($_POST["fname"]) || !ctype_alpha($_POST["fname"])){
		//gimme [A-Za-z],spaces or hyphens plz
		$fnameErr = "Please enter a valid name.";
	}
	else{
		$fname = test_input($_POST["fname"]);
	}
	if(!isset($_POST["lname"]) || !ctype_alpha($_POST["lname"])){
		//gimme [A-Za-z],spaces or hyphens plz
		$lnameErr = "Please enter a valid name.";
	}
	else{
		$lname = test_input($_POST["lname"]);
	}
	/*
	validate email
	*/
	if(empty($_POST["email"])){
		$emailErr = "Required field.";
	}
	else{
		$email = test_input($_POST["email"]);
		if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
	  		$emailErr = "Invalid email format"; 
		}
		else{
			$email = test_input($_POST["email"]);
		}
	}

	if(empty($_POST["const"])){
		$constErr = "Required field.";
	}
	else{
		if(!ctype_alpha($_POST["const"])){
		//gimme [A-Za-z],spaces or hyphens plz
			$constErr = "Please enter a valid constituecy neame.";
		}
		else{
			$con = test_input($_POST["const"]);
		}
	}
	if(empty($_POST["years"])){
		$yearsErr = "Required Field";
	}
	else{
		if(!ctype_digit($_POST["years"])){
			$yearsErr = "Please enter a valid number";
		}
		else{
			$years = test_input($_POST["years"]);
		}
	}

	if(empty($_POST["pass1"]) || empty($_POST["pass2"])){
		$passErr = "Required Field";
	}
	else{
		if(strcmp($_POST["pass1"],$_POST["pass2"])){
			$passErr = "The passwords do not match";
		}
		else{
			$pass1 = test_input($_POST["pass2"]);
			$pass2 = test_input($_POST["pass1"]);
			$options = array("cost" => 10, "salt" => uniqid());
			$hash = password_hash($password, PASSWORD_BCRYPT, $options);
			$salt = $option["salt"];
		}
	}
}

?>
 <body>
 	<form id = "form" action = "<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method = "post">
 		First Name:<br></br>
 		<input type="text" name="fname" value="<?php echo $fname; ?>" id = "fname"/><br></br>
 		<span class ="error "><?php echo htmlspecialchars($fnameErr);?></span><br></br>
 		
 		Last Name:<br></br>
 		<input type="text" name="lname" value="<?php echo $lname; ?>" id = "lname"/><br></br>
 		<span class ="error "><?php echo htmlspecialchars($lnameErr);?></span><br></br>
 	
 		Constituency:<br></br>
 		<input type="text" name="const" value="<?php echo $con; ?>" id = "const"/><br></br>
 		<span class ="error "><?php echo htmlspecialchars($constErr);?></span><br></br>
 		
 		Email:<br></br>
 		<input type = "text" name = "email" value="<?php echo $email; ?>" id = "email"/><br></br>
 		<span class ="error "><?php echo htmlspecialchars($emailErr);?></span><br></br>
 		
 		Years of Service:<br></br>
 		<input type = "text" name = "years" value="<?php echo $years; ?>" id = "years"/><br></br>
 		<span class ="error "><?php echo htmlspecialchars($yearsErr);?></span><br></br>
 		
 		Password:<br></br>
 		<input type = "text" name ="pass1" value="<?php echo $pass1;?>" id = "passwordFirst"/><br></br>
 		<span class ="error "><?php echo htmlspecialchars($passErr);?></span><br></br>
 		
 		Confirm Password:<br></br>
 		<input type = "text" name = "pass2" value="<?php echo $pass2;?>" id = "passwordSecond"/><br></br>
 		<span class ="error "><?php echo htmlspecialchars($passErr);?></span><br></br>
 		
 		<input type="hidden" value = "6eb6ac241942dc7226aeb"/>
 		<button id="button" value = "Submit">Submit</button>
 	</form>
 	<?php
		echo "<p>$fname,$lname,$con,$email,$years</p>";
		$servername = "localhost";
		$username = "comp2190SA";
		$password = "2015Sem1";
		$dbname = "MPMgmtDB";
		
		// Create connection
		$conn = new mysqli($servername, $username, $password, $dbname);
		echo "connected to the database";
		// Check connection
		if ($conn->connect_error) {
		    die("Connection failed: " . $conn->connect_error);
		} 

		$sql = "INSERT INTO Representatives"."(`first_name`, `last_name`, `constituency`, `email`, `yrs_service`,`password_digest`, `salt`)"."VALUES"."('$fname','$lname','$con','$email','$years','$hash','$salt')";

		if ($conn->query($sql) === TRUE) {
		    echo 'New record created successfully';
		}
			// Selecting specified fields to display in table 
		$sql = "SELECT first_name, last_name,constituency,email,yrs_service,password_digest FROM Representatives";
		$result = $conn->query($sql);
		// specifing table colours 
		$col="#eeeff4";
		$text = "#ffffff";
		$header="#687290";
	
		if ($result->num_rows > 0) {
			// defining table header
		    echo "<table><tr bgcolor='$header'><th align='left'><font color = '$text'>First Name</font></th><th align='left'><font color = '$text'>Last Name</font></th><th align='left'><font color = '$text'>Constituency</font></th><th align='center'><font color = '$text'>Email</font></th><th align='center'><font color = '$text'>Hash</font></th><th align='center'><font color = '$text'>Years of Service</font></th></tr>";
		    // output data of each row
		    while($row = $result->fetch_assoc()) {
				//setting values to table 
			echo "<tr bgcolor='$col'><td align='left'>".$row["first_name"]."</td><td align='left'>".$row["last_name"]."</td><td align='left'>".$row["constituency"]."</td><td align='center'>".$row["email"]."</td><td align='center'>".$row["password_digest"]."</td><td align='center'>".$row["yrs_service"]."</td></tr>";
		    }
		    echo "</table>";
		} else {
		    echo "0 results";
		} 
		// close mysql connections
		$conn->close();
		
	?>
 	</body>
 </html>


