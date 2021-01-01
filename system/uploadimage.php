<?php
$result = null;

$fileName = $_FILES['fileUpload']['name'];
$fileAccept = ['jpeg','jpg','png','gif'];
$fileExtension = strtolower(end(explode('.',$fileName)));

try
{
	if(in_array($fileExtension,$fileAccept)){
		$fileId = uniqid() . "-" . $fileName;
		move_uploaded_file($_FILES['fileUpload']['tmp_name'], __DIR__ . "/".$_POST['local']."/" . $fileId); 
		$result = array('status' => 'ok', 'fileId' => $fileId);
	}else{
		throw new Exception;
	}
}
catch (Exception $ex)
{
  $result = array('status' => 'error', 'fileId' => null); 
}

header('Content-Type: application/json');
echo json_encode($result);
?>