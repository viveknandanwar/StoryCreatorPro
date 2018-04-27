<?php
$mysqli = new mysqli("localhost", "root", "", "konva") or die ("Could not connect mysql server");
if(is_array($_FILES)) {
	if(is_uploaded_file($_FILES['files']['tmp_name'])) {
		$sourcePath = $_FILES['files']['tmp_name'];
		$targetPath = "images/".$_FILES['files']['name'];
		$filename = $_FILES['files']['name'];
		/*echo "insert into images ('id','name') values ('','$filename')";exit;	*/	
	$query = $mysqli->query("INSERT INTO `images`(`name`) VALUES ('$filename')");
	move_uploaded_file($sourcePath,$targetPath);
		/*if() {
		?>
		<img class="image-preview" src="<?php echo $targetPath; ?>" class="upload-preview" />
		<?php
		}*/
		?>
	
    <?php 
    $query = $mysqli->query("select * from images where 1");
    while($data=$query->fetch_array())
    {
    	$rows[] = $data;		
	}

	foreach($rows as $data)
	{
		?>		
		<span class="itemimage" id="alertID"><img class="img-responsive img-thumbnail" src="/images/<?php echo $data['name']; ?>" width="50px" height="50px"></span>
		<?php
	}
	}
	exit();
}
?>