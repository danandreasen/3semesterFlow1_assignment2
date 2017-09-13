<!doctype html>
<html>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Categories</title>
<!-- Stylesheets -->
<link rel="stylesheet" href="reset.css">
<link rel="stylesheet" href="simplegrid.css">
<link rel="stylesheet" href="styles.css">

</head>
<body>



<?php
if($cmd = filter_input(INPUT_POST, 'cmd')){
	if($cmd == 'add_category') {
		$catname = filter_input(INPUT_POST, 'catname') 
			or die('Missing/illegal catname parameter');
		
		require_once('db_con.php');
		$sql = 'INSERT INTO category (name) VALUES (?)';
		$stmt = $con->prepare($sql);
		$stmt->bind_param('s', $catname);
		$stmt->execute();
		
		if($stmt->affected_rows > 0){
			echo 'Category: '.$catname.' - has been created!';
		}
		else {
			echo 'Could not create a new category. Please try again.';
		}	
	}
	elseif($cmd == 'del_category') {
		$cid = filter_input(INPUT_POST, 'cid', FILTER_VALIDATE_INT) 
			or die('Missing/illegal cid parameter');
		
		require_once('db_con.php');
		$sql = 'DELETE FROM category WHERE category_id=?';
		$stmt = $con->prepare($sql);
		$stmt->bind_param('i', $cid);
		$stmt->execute();
		
		if($stmt->affected_rows > 0){
			echo 'Category has been deleted';
		}
		else {
			echo 'Could not delete this category.<br>There are images inside this category.';
		}
	}
	else {
		die('Unknown cmd: '.$cmd);
	}
	

}
?>

<h1>Categories</h1>
<h2><a href="upload.php">Upload more images</a></h2>

<?php
// include all contents of db_con.php in this file - once = loads through 1 time.
require_once('db_con.php');	
	
	// prepared statement to read data from the database
	$sql = 'SELECT category_id, name FROM category';
	$stmt = $con->prepare($sql);
	// $stmt->bind_param(); not needed - no placeholders.....
	
	// execute the statement
	$stmt->execute();
	// preperations for data gathering: prepared statement
	$stmt->bind_result($cid, $nam);
	
	// while statement
	while($stmt->fetch()){ 

	// switching to html			  
?>
<div class="categories">
	<a class="col-1-3 categories_links" href="images.php?categoryid=<?=$cid?>"><?=$nam?></a>
	<a class="col-1-3 categories_rename" href="renamecategory.php?cid=<?=$cid?>">Rename Category</a>
		<form action="<?=$_SERVER['PHP_SELF']?>" method="post">
			<input type="hidden" name="cid" value="<?=$cid?>" />
			<button class="col-1-3 btn_delete" name="cmd" type="submit" value="del_category">Delete Category</button>
		</form>
</div>
<?php
	// end the while statement
	}	
?>

<hr>

<form class="col-1-1 form_new_category" action="<?= $_SERVER['PHP_SELF'] ?>" method="post">
	<fieldset>
    	<legend>Create new category</legend>
    	<input name="catname" type="text" placeholder="Category name" required />
    	<br>
		<button class="btn_create" name="cmd" type="submit" value="add_category">Create</button>
	</fieldset>
</form>

<hr>




	
<div class="col-1-1 categories_images">
<h1>Latest images</h1>
<?php
	$stmt = $con->prepare("SELECT image.image_url, image.title, image.last_update, category.category_id, category.name 
FROM image, category 
WHERE image.category_number = category.category_id
order by image.last_update 
desc");
	$stmt->execute();
	$stmt->bind_result($url, $imgtitle, $lastupdate, $catid, $catname);
	  

	
	while($stmt->fetch()){
		
		echo '<div class="col-1-3">';
		echo '<h3 class="image_title"><a href="images.php?categoryid='.$catid.'">' . $imgtitle . '</h3></a><p class="image_title_upload">Uploaded: ' . date('g:i a F j, Y', strtotime($lastupdate)) . '</p>';
		echo '<p class="image_box"><a href="images.php?categoryid='.$catid.'"><img class="images" src="' . $url . '"width="100%"></a></p>';
		echo '</div>';
	}	

	$stmt->close();
	$con->close();
?>
</div>



</body>
</html>