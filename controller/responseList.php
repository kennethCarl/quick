<?php

	session_start();

	/*$connected = mysqli_connect('localhost', 'root','', 'mydb');*/
	$connected = mysqli_connect('mysql5.000webhost.com', 'a3623774_quick','carl143', 'a3623774_quick');
	$data = file_get_contents('php://input');
	$userData = json_decode($data, true);
	$key = 'hgfdjkbkdbg';
	$request = $userData['data']['request'];

	switch($request)
	{
		case "getCookieValues":  getCookieValues();
						break;
		case "checkIflogged": checkIflogged();
						break;
		case "login": login();
						break;
		case "getUserInfo": getUserInfo();
						break;
		case "getProjects": getProjects();
						break;
		case "getTasks": getTasks();
						break;
		case "getProjectTask": getProjectTask();
						break;
		case "getPriorityTypes": getPriorityTypes();
						break;
		case "getStatusTypes": getStatusTypes();
						break;
		case "getStatusTypesAdd": getStatusTypesAdd();
						break;
		case "save": saveData();
						break;
		case "signUp": saveUser();
						break;
		case "updateProject": updateProject();
						break;
		case "updateTask": updateTask();
						break;
		case "deleteProject": deleteProject();
						break;
		case "deleteTask" : deleteTask();
						break;
		case "logout" : logout();
						break;
	}
	function check_input($value)
	{
		$string = str_split($value, 1);
		$holder = "";
		
		for($i = 0; $i < count($string); $i++)
		{
			if($string[$i] != "'")
				$holder = $holder.$string[$i];
			else
				$holder = $holder."/";
		}
		return $holder;
	}
	function convertValue($value)
	{
		$string = str_split($value, 1);
		$holder = "";

		for($i = 0; $i < count($string); $i++)
		{
			if($string[$i] != "/")
				$holder = $holder.$string[$i];
			else
				$holder = $holder."'";
		}
		return $holder;
	}
/*//To encrypt
$encryptedMessage = openssl_encrypt($textToEncrypt, $encryptionMethod, $secretHash);

//To Decrypt
$decryptedMessage = openssl_decrypt($encryptedMessage, $encryptionMethod, $secretHash);*/
	function getCookieValues()
	{

		if(isset($_COOKIE['username']) && isset($_COOKIE['password']))
		{
			$data = array('username' => $_COOKIE['username'], 'password' => $_COOKIE['password']);
			$respond['status'] = true;
			$respond['data'] = $data;
		}
		else
		{
			$respond['status'] = false;
			/*$expire = time() - 60 * 60;
			setcookie('username', "", $expire);
			setcookie('password', "", $expire);*/
		}
		header('Content-Type: application/json');
		echo json_encode($respond);
	}
	function checkIflogged()
	{
		if(isset($_SESSION['login']) && $_SESSION['login'] == true)
			$respond['status'] = true;
		else
			$respond['status'] = false;
		header('Content-Type: application/json');
		echo json_encode($respond);
	}
	function saveUser()
	{
		global $data, $userData, $connected, $key;

		$firstname 	= $userData['data']['firstname'];
		$lastname 	= $userData['data']['lastname'];
		$middlename = $userData['data']['middlename'];
		$username 	= $userData['data']['username'];
		$password 	= $userData['data']['password'];
		$status 	= 1;
		$encryptedPassword = base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5($key), $password, MCRYPT_MODE_CBC, md5(md5($key))));

		$sql = "SELECT * FROM user WHERE username = '$username'";
		$result = $connected->query($sql);

		if($result->num_rows > 0)
			$respond['status'] = false;
		else
		{
			$saveUser = $connected->query("INSERT into user(username, password, status) VALUES('$username', '$encryptedPassword', '$status')");

			$getUser = $connected->query("SELECT * FROM user WHERE username = '$username'");

			while($row = $getUser->fetch_assoc())
			{
				$userId = $row['userId'];
				$saveInfo = $connected->query("INSERT into userinformation(userId, firstname, lastname, middlename) VALUES('$userId', '$firstname', '$lastname', '$middlename')");
				$respond['status'] = true;
			}
		}
		header('Content-Type: application/json');
		echo json_encode($respond);
	}
	function login()
	{
		global $data, $userData, $connected, $key;

		if(isset($_SESSION['login']) && $_SESSION['login'] == true)
		{
			$respond['status'] = true;
			echo json_encode($respond);
		}	
		else
		{
			$username = $userData['data']['username'];
			$password = $userData['data']['password'];
			$remember = $userData['data']['remember'];

			$respond = array();

			$result = $connected->query("SELECT * FROM user WHERE username = '$username'");
			if($result->num_rows == 0)
				$respond['status'] = false;
			else
			{
				$encryptedPassword = base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5($key), $password, MCRYPT_MODE_CBC, md5(md5($key))));
				while($row = $result->fetch_assoc())
				{
					if($encryptedPassword == $row['password'] || $password == $row['password'])
					{
						$_SESSION['userId'] = $row['userId'];
						$respond['status'] = true;
						$_SESSION['login'] = true;

						if($remember)
						{
							if((!(isset($_COOKIE['username']) && isset($_COOKIE['password']))) || (isset($_COOKIE['username']) && $_COOKIE['username'] != $username))
							{

								$expire = time() + 60 * 60 * 24 * 30;
								setcookie('username', $username, $expire);
								setcookie('password', $encryptedPassword, $expire);
							}
						}
						else
						{
							if(isset($_COOKIE['username']) && isset($_COOKIE['password']))
							{
								$expire = time() - 60 * 60;
								setcookie('username', "", $expire);
								setcookie('password', "", $expire);
							}
						}
					}
					else
						$respond['status'] = false;
				}
			}
			header('Content-Type: application/json');
			echo json_encode($respond);
		}
	}
	function getUserInfo()
	{
		global $data, $userData, $connected;

		$respond = array();
		$respond['status'] = false;
		$userinformation = array();
		
		if(isset($_SESSION['login']) && $_SESSION['login'] == true)
		{
			$userId = $_SESSION['userId'];

			/*$sql = "SELECT * from userinformation where userId = ?";
			$rs = $connected->prepare($sql);
			$rs->bind_param('s', $userId);
			$rs->execute();
			$result = $rs->get_result();*/

			$result = $connected->query("SELECT * FROM userinformation WHERE userId = '$userId'");
			if($result->num_rows != 0)
			{
				while($row = $result->fetch_assoc())
					$userinformation[] = $row;

				$respond['status'] = true;
				$respond['data'] = $userinformation;
				header('Content-Type: application/json');
				echo json_encode($respond); 
			}

		}
		else
		{
			header('Content-Type: application/json');
			echo json_encode($respond);
		}
	}
	function getProjects()
	{
		global $data, $userData, $connected;

		$respond = array();
		$respond['status'] = false;
		$userProjects = array();
		
		if(isset($_SESSION['login']) && $_SESSION['login'] == true)
		{
			$userId = $_SESSION['userId'];
			$stat = 1;
		
			/*$sql = "SELECT * from project where userId = ? and projectStatus = ? order by priority desc";
			$rs = $connected->prepare($sql);
			$rs->bind_param('ss', $userId, $stat);
			$rs->execute();
			$result = $rs->get_result();*/
			$result = $connected->query("SELECT * FROM project WHERE userId = '$userId' and projectStatus = '$stat' ORDER BY priority DESC");

			if($result->num_rows == 0)
			{
				$respond['status'] = false;
				$respond['message'] = "No Project.";
				header('Content-Type: application/json');
				echo json_encode($respond);
			}
			else
			{
				while($row = $result->fetch_assoc())
				{
					$row['name'] = convertValue($row['name']);
					$row['description'] = convertValue($row['description']);
					$userProjects[] = $row;
				}

				$respond['message'] = "";
				$respond['status'] = true;
				$respond['data'] = $userProjects;
				header('Content-Type: application/json');
				echo json_encode($respond);
			}
		}
		else
		{
			header('Content-Type: application/json');
			echo json_encode($respond);
		}
	}
	function getProjectTask()
	{
		global $data, $userData, $connected;

		$respond = array();
		$respond['status'] = false;
		$userTasks = array();
		$stat = 1;
		$parentId = 0;
		
		if(isset($_SESSION['login']) && $_SESSION['login'] == true)
		{
			$userId = $_SESSION['userId'];

			/*$sql = "SELECT * from tasks where userId = ? and taskStatus = ? and projectId != ? order by priority desc";
			$rs = $connected->prepare($sql);
			$rs->bind_param('sss', $userId, $stat, $parentId);
			$rs->execute();
			$result = $rs->get_result();*/
			$result = $connected->query("SELECT * FROM tasks WHERE userId = '$userId' and taskStatus = '$stat' and projectId != '$parentId' ORDER BY priority DESC");

			if($result->num_rows == 0)
			{
				$respond['status'] = false;
				$respond['message'] = "No Task.";
				header('Content-Type: application/json');
				echo json_encode($respond);
			}
			else
			{
				while($row =$result->fetch_assoc())
				{
					$row['name'] = convertValue($row['name']);
					$row['description'] = convertValue($row['description']);
					$userTasks[] = $row;
				}

				$respond['message'] = "";
				$respond['status'] = true;
				$respond['data'] = $userTasks;
				header('Content-Type: application/json');
				echo json_encode($respond); 
			}
		}
		else
		{
			header('Content-Type: application/json');
			echo json_encode($respond);
		}
	}
	function getTasks()
	{
		global $data, $userData, $connected;

		$respond = array();
		$respond['status'] = false;
		$userTasks = array();
		$stat = 1;
		$parentId = 0;
		
		if(isset($_SESSION['login']) && $_SESSION['login'] == true)
		{
			$userId = $_SESSION['userId'];

			/*$sql = "SELECT * from tasks where userId = ? and taskStatus = ? and projectId = ? order by priority desc";
			$rs = $connected->prepare($sql);
			$rs->bind_param('sss', $userId, $stat, $parentId);
			$rs->execute();
			$result = $rs->get_result();*/

			$result = $connected->query("SELECT * FROM tasks WHERE userId = '$userId' and taskStatus = '$stat' and projectId = 'parentId' ORDER BY priority DESC");

			if($result->num_rows == 0)
			{
				$respond['status'] = false;
				$respond['message'] = "No Task.";
				header('Content-Type: application/json');
				echo json_encode($respond);
			}
			else
			{
				while($row = $result->fetch_assoc())
				{
					$row['name'] = convertValue($row['name']);
					$row['description'] = convertValue($row['description']);
					$userTasks[] = $row;
				}

				$respond['message'] = "";
				$respond['status'] = true;
				$respond['data'] = $userTasks;
				header('Content-Type: application/json');
				echo json_encode($respond); 
			}
		}
		else
		{
			header('Content-Type: application/json');
			echo json_encode($respond);
		}
	}
	function getPriorityTypes()
	{
		global $data, $userData, $connected;

		$respond = array();
		$respond['status'] = false;
		$priorities = array();
		
		if(isset($_SESSION['login']) && $_SESSION['login'] == true)
		{
			/*$userId = $_SESSION['userId'];
			$sql = "SELECT * from priority";
			$rs = $connected->prepare($sql);
			$rs->execute();
			$result = $rs->get_result();*/

			$result = $connected->query("SELECT * FROM priority");

			if($result->num_rows == 0)
			{
				$respond['status'] = false;
				header('Content-Type: application/json');
				echo json_encode($respond); 
			}
			else
			{
				while($row = $result->fetch_assoc())
					$priorities[] = $row;

				$respond['message'] = "";
				$respond['status'] = true;
				$respond['data'] = $priorities;
				header('Content-Type: application/json');
				echo json_encode($respond); 
			}
		}
		else
		{
			header('Content-Type: application/json');
			echo json_encode($respond);
		}
	}
	function getStatusTypes()
	{
		global $data, $userData, $connected;

		$respond = array();
		$respond['status'] = false;
		$status = array();
		
		if(isset($_SESSION['login']) && $_SESSION['login'] == true)
		{
			/*$userId = $_SESSION['userId'];
			$sql = "Select * from status";
			$rs = $connected->prepare($sql);
			$rs->execute();
			$result = $rs->get_result();*/

			$result = $connected->query("SELECT * FROM status");

			if($result->num_rows == 0)
			{
				$respond['status'] = false;
				header('Content-Type: application/json');
				echo json_encode($respond);
			}
			else
			{
				while($row = $result->fetch_assoc())
					$status[] = $row;

				$respond['message'] = "";
				$respond['status'] = true;
				$respond['data'] = $status;
				header('Content-Type: application/json');
				echo json_encode($respond); 
			}
		}
		else
			echo json_encode($respond);
	}
	function getStatusTypesAdd()
	{
		global $data, $userData, $connected;

		$respond = array();
		$respond['status'] = false;
		$status = array();
		
		if(isset($_SESSION['login']) && $_SESSION['login'] == true)
		{
			$userId = $_SESSION['userId'];
			$holder = 3;
			/*$sql = "SELECT * from status where id < ?";
			$rs = $connected->prepare($sql);
			$rs->bind_param('s', $holder);
			$rs->execute();
			$result = $rs->get_result();*/

			$result = $connected->query("SELECT * FROM status WHERE id < '$holder'");
			if($result->num_rows == 0)
			{
				$respond['status'] = false;
				header('Content-Type: application/json');
				echo json_encode($respond); 
			}
			else
			{
				while($row = $result->fetch_assoc())
					$status[] = $row;

				$respond['message'] = "";
				$respond['status'] = true;
				$respond['data'] = $status;
				header('Content-Type: application/json');
				echo json_encode($respond); 
			}
		}
		else
		{
			header('Content-Type: application/json');
			echo json_encode($respond);
		}
	}
	function saveData()
	{
		global $data, $userData, $connected;
		
		$respond = array();
		$respond['status'] = false;
		
		if(isset($_SESSION['login']) && $_SESSION['login'] == true)
		{
			$userId = $_SESSION['userId'];
			$name = check_input($userData['data']['name']);
			$desc = check_input($userData['data']['description']);
			$priority = $userData['data']['priority'];
			$status = $userData['data']['status'];
			$projectId = (int)$userData['data']['projectId'];
			$type = strtolower($userData['data']['type']);
			$stat = 1;
			$date = date("Y/m/d h:i:s");

			if($type == "project")
			{
				/*$sql =  "INSERT INTO project(userId, name, priority, status, description, projectStatus)
											VALUES(?, ?, ?, ?, ?, ?)";
				$rs = $connected->prepare($sql);
				$rs->bind_param('ssssss', $userId, $name, $priority, $status, $desc, $stat);
				$rs->execute();*/
				$result = $connected->query("INSERT INTO project(userId, name, priority, status, description, projectStatus, dateCreated)
											VALUES('$userId', '$name', '$priority', '$status', '$desc', '$stat', '$date')");
				$respond['status'] = true;
			}
			else
			{
				/*$sql = "INSERT INTO tasks(userId, name, priority, status, description, projectId,taskStatus)
											VALUES(?, ?, ?, ?, ?, ?, ?)";
				$rs = $connected->prepare($sql);
				$rs->bind_param('sssssss', $userId, $name, $priority, $status, $desc, $projectId, $stat);
				$rs->execute();*/
				$result = $connected->query("INSERT INTO tasks(userId, name, priority, status, description, projectId,taskStatus, dateCreated)
											VALUES('$userId', '$name', '$priority', '$status', '$desc', '$projectId', '$stat', '$date')");
				$respond['status'] = true;
			}
			header('Content-Type: application/json');
			echo json_encode($respond);
		}
		else
		{
			header('Content-Type: application/json');
			echo json_encode($respond);
		}
	}
	function updateProject()
	{
		global $data, $userData, $connected;
		
		$respond = array();
		$respond['status'] = false;
		
		if(isset($_SESSION['login']) && $_SESSION['login'] == true)
		{
			$name = check_input($userData['data']['name']);
			$desc = check_input($userData['data']['description']);
			$priority = $userData['data']['priority'];
			$status = $userData['data']['status'];
			$id = (int)$userData['data']['id'];

			/*$sql = 	"UPDATE project 
					SET name = ?, description = ?, priority = ?, status = ?
					WHERE id = ?";
			$rs = $connected->prepare($sql);
			$rs->bind_param('sssss', $name, $desc, $priority, $status, $id);
			$rs->execute();*/
			$result = $connected->query("UPDATE project 
										SET name = '$name', description = '$desc', priority = '$priority', status = '$status'
										WHERE id = '$id'");
			$respond['status'] = true;
			header('Content-Type: application/json');
			echo json_encode($respond);
		}
		else
		{	
			header('Content-Type: application/json');
			echo json_encode($respond);
		}
	}
	function updateTask()
	{

		global $data, $userData, $connected;
		
		$respond = array();
		$respond['status'] = false;
		
		if(isset($_SESSION['login']) && $_SESSION['login'] == true)
		{
			$name = check_input($userData['data']['name']);
			$desc = check_input($userData['data']['description']);
			$priority = $userData['data']['priority'];
			$status = $userData['data']['status'];
			$id = (int)$userData['data']['id'];

			/*$sql = 	"UPDATE tasks 
					SET name = ?, description = ?, priority = ?, status = ?
					WHERE id = ?";
			$rs = $connected->prepare($sql);
			$rs->bind_param('sssss', $name, $desc, $priority, $status, $id);
			$rs->execute();*/
			$result = $connected->query("UPDATE tasks 
										SET name = '$name', description = '$desc', priority = '$priority', status = '$status'
										WHERE id = '$id'");
			$respond['status'] = true;
			header('Content-Type: application/json');
			echo json_encode($respond);
		}
		else
		{
			header('Content-Type: application/json');
			echo json_encode($respond);
		}
	}
	function deleteProject()
	{
		global $data, $userData, $connected;
		
		$respond = array();
		$respond['status'] = false;
		
		if(isset($_SESSION['login']) && $_SESSION['login'] == true)
		{
			$id = (int)$userData['data']['id'];
			$stat = 0;

			/*$sql = "UPDATE project 
					SET projectStatus = ?
					WHERE id = ?";
			$rs = $connected->prepare($sql);
			$rs->bind_param('ss', $stat, $id);
			$rs->execute();*/
			$result = $connected->query("UPDATE project 
										SET projectStatus = '$stat'
										WHERE id = '$id'");
			$respond['status'] = true;
			header('Content-Type: application/json');
			echo json_encode($respond);
		}
		else
		{
			header('Content-Type: application/json');
			echo json_encode($respond);
		}
	}
	function deleteTask()
	{
		global $data, $userData, $connected;
		
		$respond = array();
		$respond['status'] = false;
		
		if(isset($_SESSION['login']) && $_SESSION['login'] == true)
		{
			$id = (int)$userData['data']['id'];
			$stat = 0;

			/*$sql = "UPDATE tasks 
					SET taskStatus = ?
					WHERE id = ?";
			$rs = $connected->prepare($sql);
			$rs->bind_param('ss', $stat, $id);
			$rs->execute();*/
			$result = $connected->query("UPDATE tasks
										SET taskStatus = '$stat'
										WHERE id = '$id'");
			$respond['status'] = true;
			header('Content-Type: application/json');
			echo json_encode($respond);
		}
		else
		{
			header('Content-Type: application/json');
			echo json_encode($respond);
		}
	}
	function logout()
	{
		session_destroy();
		$respond['status'] = true;
		header('Content-Type: application/json');
		echo json_encode($respond);
	}
	die;