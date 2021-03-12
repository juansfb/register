<?php
ini_set('display_errors', 'Off');

function Error($Error)
{
	echo "<br /><br /><br /><br /><br /><br /><center><b><span style='color:#CD0000;'> " . $Error . "</span></b></center>";
}

function register()
{
		include('configs.php');

		//if(isset($_POST['flags'])) {
		//if($_POST['flags'] == "0") {
		//$flags = "0";
		//}elseif($_POST['flags'] == "8") {
		//$flags = "1";
		//}
		
		//PDO Connection
		//try {
		//	$conn = new PDO("mysql:host=$mysql_host;dbname=$mysql_db", $mysql_user, $mysql_pass);
		//	// set the PDO error mode to exception
		//	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		//	echo "Connected successfully";
		//}
		//catch(PDOException $e){
		//	echo "Connection failed: " . $e->getMessage();
		//}
		
		//mysqli oos connection
		$link = new mysqli($mysql_host,$mysql_user,$mysql_pass,$mysql_db);

		// Check connection
		if ($link -> connect_errno) {
			echo "Failed to connect to MySQL: " . $link -> connect_error;
			exit();
		}
		
        if ((empty($_POST["user"]))||(empty($_POST["password"])) ) {
				echo '<script type="text/javascript">window.location = "index.php?error=You did not enter all the required information.";</script>';
        } else {
                $username = strtoupper($_POST["user"]);
                $password = strtoupper($_POST["password"]);
				
				//comprobaciones minimas del input
				$user_chars = "#[^a-zA-Z0-9_\-]#";
                if (strlen($username) < 3) {
						echo '<script type="text/javascript">window.location = "index.php?error=Username is too short.";</script>';
						exit();
                };
                if (strlen($username) > 30) {
						echo '<script type="text/javascript">window.location = "index.php?error=Username is too long.";</script>';
						exit();
                };
                if (strlen($password) < 3) {
						echo '<script type="text/javascript">window.location = "index.php?error=Password is too short.";</script>';
						exit();
                };
                if (strlen($password) > 30) {
						echo '<script type="text/javascript">window.location = "index.php?error=Password is too long.";</script>';
						exit();
                };
				if (preg_match($user_chars,$username)) {
						echo '<script type="text/javascript">window.location = "index.php?error=Please only use A-Z and 0-9.";</script>';
						exit();
                };
                if (preg_match($user_chars,$password)) {
						echo '<script type="text/javascript">window.location = "index.php?error=Please only use A-Z and 0-9.";</script>';
						exit();
                };
              
                $username = $link->real_escape_string($username);
                $password = $link->real_escape_string($password);
				
				//COMPROBACION DE USERNAME UNICO
                $qry = mysqli_query($link,"SELECT * FROM account WHERE username = '".$username."'");
				
				$check = mysqli_num_rows($qry);
				
				if($check) {
					echo '<script type="text/javascript">window.location = "index.php?error=User already registered.";</script>';
					exit();
				}
					
				unset($qry);
				
				//pass->hash y query
				$sha_pass_hash = sha1($username . ":" . $password);
                
				$register_sql = "INSERT INTO account (username, sha_pass_hash) VALUES ('".$username."', '".$sha_pass_hash."')";
                $qry = mysqli_query($link,$register_sql);
				if (!$qry) {
					echo '<script type="text/javascript">window.location = "index.php?error=Error creating account.";</script>';
					exit();
				};
				echo '<br /><br /><br /><br /><br /><br /><center><span style="color:#41d600;">Your Account was successfully created!<br /></span></center>';
				
				//COMPROBACION final
                //$qry = mysqli_query($link,"SELECT * FROM account WHERE username = '" . $username . "'");
				//
				//$check = mysqli_num_rows($qry);
				//
				//if(!$check) {
				//	echo '<script type="text/javascript">window.location = "index.php?error=Error creating account.";</script>';
				//	exit();
				//}
				//else echo '<br /><br /><br /><br /><br /><br /><center><span style="color:#41d600;">Your Account was successfully created!<br /></span></center>';
				
				$link -> close();
        };
	

}
?>