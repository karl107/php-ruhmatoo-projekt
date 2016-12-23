<?php 
	
	require("../functions.php");
	include_once("../class/Upload.class.php");

	$mysqli = "";

	
	
	//kui ei ole kasutaja id'd
	if (!isset($_SESSION["userId"])){
		
		//suunan sisselogimise lehele
		header("Location: login2.php");
		exit();
	}
	
	
	//kui on ?logout aadressireal siis login välja
	if (isset($_GET["logout"])) {
		
		session_destroy();
		header("Location: login2.php");
		exit();
	}

	

//UPLOAD
	$url="";
	$caption = "fileToUpload";
	$captionError="";
	$target_dir = "../uploads/";
	$error="";
	$existsError="";
	$typeError="";
	$sizeError="";
	$success="";
	if(isset($_FILES["fileToUpload"]) && !empty($_FILES["fileToUpload"]["name"])){
		
		$url = $target_dir . basename($_FILES["fileToUpload"]["name"]);
		$uploadOk = 1;
		
		$imageFileType = pathinfo($url,PATHINFO_EXTENSION);
		
		//check if user wrote title
		if(isset($_POST["caption"])){			
			if(empty($_POST["caption"])){				
				$captionError="Please insert title";
				$uploadOk = 0;
			}else{				
				$caption=$_POST["caption"];
			}
		
		}
		// Check if file already exists
		if (file_exists($url)) {
			$existsError= "Sorry, file already exists.";
			$uploadOk = 0;
		}
		// Check file size
		if ($_FILES["fileToUpload"]["size"] > 50000000) {
			$sizeError= "Sorry, your file is too large.";
			$uploadOk = 0;
		}
		// Allow certain file formats
		if($imageFileType != "mp3" && $imageFileType != "wav") {
			$typeError= "Sorry, only MP3 and WAV files are allowed.";
			$uploadOk = 0;
		}
		// Check if $uploadOk is set to 0 by an error
		if ($uploadOk == 0) {
			//$error="Sorry, your file was not uploaded.";
		// if everything is ok, try to upload file
		} else {
			if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $url)) {
				$success= "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
				
				// save file name to DB here
				
				$userid=$_SESSION["userId"];
				$caption =($_POST["caption"]); //$caption = $Helper->cleanInput($_POST["caption"]);
				$Upload->uploadAudio($userid,$caption,$url);
				header("location: data.php");
				
				
				
			} else {
				$error;
			}
		}
	}else{
		
	}

?>




<head>
	<meta charset="utf-8">
	<title>Audify</title>
	<meta name="description" content="Audify">
	

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css">



</head>

<header>
	<nav class="navbar navbar-inverse navbar-static-top">
	  <div class="container">
		<!-- Brand and toggle get grouped for better mobile display -->
		<div class="navbar-header">
		  <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
			<span class="sr-only">Toggle navigation</span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
		  </button>
		  <a class="navbar-brand" href="data.php"><img src="../images/audify_600x220.png" height="22px"/></a>
		</div>

		<!-- Collect the nav links, forms, and other content for toggling -->
		<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
		  <ul class="nav navbar-nav">
			<li><a href="upload.php">Upload</a></li>
		  </ul>
		  <form class="navbar-form navbar-left">
			<div class="form-group">
			  <input type="text" class="form-control" placeholder="Search">
			</div>
		  </form>
		  <ul class="nav navbar-nav navbar-right">
			
			<li class="dropdown">
			  <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><?=$_SESSION["userEmail"];?><span class="caret"></span></a>
			  <ul class="dropdown-menu">
				<li><a href="user.php">User profile</a></li>
				<li><a href="settings.php">Settings</a></li>
				<li role="separator" class="divider"></li>
				<li><a href="?logout=1">Sign out</a></li>
			  </ul>
			</li>
		  </ul>
		</div><!-- /.navbar-collapse -->
	  </div><!-- /.container-fluid -->
	</nav>
	
	<script src="http://code.jquery.com/jquery-2.1.1.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</header>


<body>
		<!--<div class="jumbotron">-->
			<div class="container">
				
				<h1>Upload your track</h1>

					<form action="upload.php" method="post" enctype="multipart/form-data">
						Select audio to upload:
						<input type="file" name="fileToUpload" id="fileToUpload"><br><br>
						<label>Insert title</label>
						<input type="text" name="caption" id="caption" class="form-control" placeholder="Insert a title here"><br>
						<input type="submit" value="Upload Audio" name="submit">
						<p style="color:red;"><?=$captionError;?></p>
						<p style="color:red;"><?=$sizeError;?></p>
						<p style="color:red;"><?=$typeError;?></p>
						<p style="color:red;"><?=$existsError;?></p>
						<p style="color:red;"><?=$error;?></p>
						<p><?=$success;?></p>
					</form>
				
			</div>

</body>