<!doctype html>
<html>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Images</title>
<!-- Stylesheets -->
<link rel="stylesheet" href="reset.css">
<link rel="stylesheet" href="simplegrid.css">
<link rel="stylesheet" href="styles.css">
</head>

<body>


	
	
<?php
if($cmd = filter_input(INPUT_POST, 'cmd')){
	if($cmd == 'del_image') {
			$image_id = filter_input(INPUT_POST, 'image_id', FILTER_VALIDATE_INT)
					or die('Missing/Illegal image id parameter');
			require_once('db_con.php');
			$sql = 'DELETE FROM image WHERE image_id=?';
			$stmt = $con->prepare($sql);
			$stmt->bind_param('i', $image_id);
			$stmt->execute();

			if($stmt->affected_rows > 0){
				echo 'The image has been deleted<br>';
			}
			else {
				echo 'Could not delete the image<br>';
			}
		}
		else {
			die('Unknown cmd: '.$cmd);
		}
}
?>
	
	
	
	<a href="categories.php">Back to categories</a><br>
	<br>
	<a href="upload.php">Upload new Image</a>
	
	<div class="col-1-1 box_with_images">
<?php
$cid = filter_input(INPUT_GET, 'categoryid', FILTER_VALIDATE_INT)
				or die('Missing/Illegal category id parameter');
		
	require_once('db_con.php');
	$sql = 'SELECT image.image_id, image.image_url, image.title, image.category_number, image.last_update, category.category_id, category.name
FROM image, category 
WHERE image.category_number = category.category_id 
and image.category_number = ?
order by image_id desc';
	$stmt = $con->prepare($sql);
	$stmt->bind_param('i', $cid);
	$stmt->execute();
	$stmt->bind_result($image_id, $url, $imgtitle, $cat_number, $lastupdate, $cat_id, $catname);
	while ($stmt->fetch()){
		
		
		
		echo '<h3 class="image_title"><a href="images.php?categoryid='.$cat_id.'">' . $imgtitle . '</h3></a><p class="image_title_upload">Uploaded: ' . date('g:i a F j, Y', strtotime($lastupdate)) . '</p>';
		
		echo '<img src="' . $url . '" width="100%">';
	
		
		echo '<form action="'.$_SERVER['PHP_SELF'].'" method="post">';
		echo '<input type="hidden" name="image_id" value="'.$image_id.'" />';
		echo '<button class="btn_del_image" name="cmd" type="submit" value="del_image">Delete Image</button><button class="btn_download_image"><a href="' . $url . '" download="' . $imgtitle . '">Download Image</a></button>';
		echo '</form>'.PHP_EOL;
	}

	$stmt->close();
	$con->close();
?>
	</div>



	
	

	
</body>
</html>