<!doctype html>
<html>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Upload</title>
<!-- Stylesheets -->
<link rel="stylesheet" href="reset.css">
<link rel="stylesheet" href="simplegrid.css">
<link rel="stylesheet" href="styles.css">
</head>
<body>

	<h1>Upload image</h1>
	
	<h2><a href="categories.php">View Categories</a></h2>

<?php
	require_once("db_con.php");	
	
	$cmd = filter_input(INPUT_POST, 'upload');
	
	
	
/////////////////////////
///// Image upload /////
////////////////////////
	
	// variable to check if there were upload problems/errors!
	$uploadOk = 0;
	
	
	
	if($cmd){
		
		$imagetitle = filter_input(INPUT_POST, 'imagetitle')
				or die('Missing/Illegal Image title parameter');
		
		
		$catnumber = filter_input(INPUT_POST, 'DropDownList')
				or die('Missing/Illegal Category number parameter');
		
		// storing the path to your image directory
		$target_dir = "images/";
 		$target_file = $target_dir . basename($_FILES['fileToUpload']['name']); //specifies the path of the file to be uploaded (fx: images/bulletin-board-background.jpg)
		
		 // Check if file is an image
		 $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
		 if($check !== false) {
		 echo "Image type: " . $check["mime"] . ". <br> ";
		 $uploadOk = 1;
		 } else {
		 echo "File is not an image. ";
		 $uploadOk = 0;
		 }
		
		
		// Check if file already exists
		 if (file_exists($target_file)) {
		 echo "This file already exists. Try another filename.<br>";
		 $uploadOk = 0; 
		 }
		
		// Check if $uploadOk is set to 0 by an error
		 if ($uploadOk == 0) {
		 echo "Sorry, your file was not uploaded. ";
		 // if everything is ok, try to upload file
		 } else {
		 if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {

		// the query inserting target path into database!

			 
			 
			 
		$stmt = $con->prepare("INSERT INTO image (image_url, title, category_number) VALUES (?, ?, ?)");
			$stmt->bind_param("ssi", $target_file, $imagetitle, $catnumber);
			$stmt->execute();
			// close statement
			$stmt->close();
			 
		
			 
		 echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
		 } else {
		 echo "Sorry, there was an error uploading your file. Please try again!";
		 	}
		 }
		
	// end of cmd:
	}



?>
<!-- Enctype multipart MUST be used in connection with a file/image upload -->
<form class="form_upload" action="<?=$_SERVER['PHP_SELF']?>" method="post" enctype="multipart/form-data">
<p><input class="choose_file" type="file" name="fileToUpload" required></p>
<p><input type="text" name="imagetitle" placeholder="Image title" required></p>




<!-- Kategori navnene bliver slected fra databasen og smidt ind i select formen -->

<select name="DropDownList">
<option selected disabled>Choose category</option>

<?php
	require_once('db_con.php');
	
	$sql = 'SELECT category_id, category.name FROM category order by category.name';
	$stmt = $con->query($sql);
	  
	 if($stmt->num_rows > 0){
		 while($row = $stmt->fetch_assoc()){
			 echo "<option name='category_id' value='" . $row['category_id']."'>" . $row['name']."</option>"; 
		 }}
	  
	  	else {
			echo 'Could not find category' .$cid;
		}
	 	
?>	

</select>


<p><input class="btn_upload" type="submit" name="upload" value="Upload"></p>
</form>

<hr>

<div class="last_image_uploads_box">
<h3 class="text_last_uploaded_image">Last uploaded image</h3>
<?php

	
	$stmt = $con->prepare("SELECT image.image_url, image.title, image.last_update, category.category_id, category.name 
FROM image, category 
WHERE image.category_number = category.category_id
order by image.last_update 
desc limit 1");
	$stmt->execute();
	$stmt->bind_result($url, $imgtitle, $lastupdate, $catid, $catname);
	
	while($stmt->fetch()){
		
		echo '<h3 class="image_title"><a href="images.php?categoryid='.$catid.'">' . $imgtitle . '</h3></a><p class="image_title_upload">Uploaded: ' . date('g:i a F j, Y', strtotime($lastupdate)) . '</p>';
		echo '<a href="images.php?categoryid='.$catid.'"><img src="' . $url . '" width="320px"></a>';
		echo '<br>';
		echo '<a class="image_link_category" href="images.php?categoryid='.$catid.'">'.$catname.'</a>';
		
		
		

		
	}	

	$stmt->close();
	$con->close();
	
?>
</div>

</body>
</html>