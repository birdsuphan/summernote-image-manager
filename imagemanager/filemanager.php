<?php

include('../config.php');
include('Tool.class.php');

$tool = new Tool();

$action = 'list';

if(!empty($_GET['action'])){
	$action = $_GET['action'];
}

if($action=='list'){
	$folderList = $tool->summernote_ListFolder();
	$imagesList = $tool->imageList();
	//print_r($imagesList);
	include('filemanager.htm');
}
if($action=='filemanagerlistfolder'){
	$folderList = $tool->summernote_ListFolder();
	$imagesList = $tool->imageList();
	
	include('filemanager_folder.htm');
}

if($action=='uploadimage'){
	$result = $tool->uploaImage();
	if($result){
		echo '<script>window.top.imageFolder2("'.$_POST['folders_path'].'");</script>';
	}else{
		echo '<script>window.top.alert("Error upload,Please try again.");</script>';
		echo '<script>window.top.imageFolder2("'.$_POST['folders_path'].'");</script>';
	}
}
if($action=='deleteimage'){
	$tool->deleteImage();
}

if($action=='createfolder'){
	$result = $tool->createFolder();
	if($result){
		echo '<script>window.top.imageFolder2("'.$_POST['folders_path'].'");</script>';
	}else{
		echo "F";
	}
}
if($action=='deletefolder'){
	$tool->deleteFolder();
}

?>