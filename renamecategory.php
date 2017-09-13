<!doctype html>
<html>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Rename Category</title>
<!-- Stylesheets -->
<link rel="stylesheet" href="reset.css">
<link rel="stylesheet" href="simplegrid.css">
<link rel="stylesheet" href="styles.css">
</head>

<body>

<?php
if($cmd = filter_input(INPUT_POST, 'cmd')){
	if($cmd == 'rename_category') {
		$cid = filter_input(INPUT_POST, 'cid', FILTER_VALIDATE_INT) 
			or die('Missing/illegal cid parameter');
		$catname = filter_input(INPUT_POST, 'catname') 
			or die('Missing/illegal catname parameter');
		
		require_once('db_con.php');
		$sql = 'UPDATE category SET name=? WHERE category_id=?';
		$stmt = $con->prepare($sql);
		$stmt->bind_param('si', $catname, $cid);
		$stmt->execute();
		
		if($stmt->affected_rows > 0){
			echo 'Category name was successfully changed to '.$catname;
		}
		else {
			echo 'Could not change the name of the category. Try again.';
		}	
	}
}
?>




<?php
	if (empty($cid)){
		$cid = filter_input(INPUT_GET, 'cid', FILTER_VALIDATE_INT) 
			or die('Missing/illegal cid parameter');	
	}
	require_once('db_con.php');
	$sql = 'SELECT name FROM category WHERE category_id=?';
	$stmt = $con->prepare($sql);
	$stmt->bind_param('i', $cid);
	$stmt->execute();
	$stmt->bind_result($catname);
	while($stmt->fetch()){} 
?>

<a href="categories.php">View all Categories</a>

<div class="rename_category">
	<form action="<?= $_SERVER['PHP_SELF'] ?>" method="post">
		<fieldset>
			<legend>Rename Category</legend>
			<input name="cid" type="hidden" value="<?=$cid?>" />
			<input name="catname" type="text" value="<?=$catname?>" /><br>
			<button name="cmd" type="submit" value="rename_category">Save new name</button>
		</fieldset>
	</form>
</div>
	

</body>
</html>