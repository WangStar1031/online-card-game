<?php
	$arrCards = array();
	$category = "";
	function getImageCount($topic){
		$dir = './images/'.$topic.'/';
		$files1 = scandir($dir);
		return count($files1) - 2;
	}
	function fileRename($topic){
		$nFiles = getImageCount($topic);
		$dir = './images/'.$topic.'/';
		$files1 = scandir($dir);
		for( $i = 0, $fileFix = 0; $i < count($files1); $i++){
			$fName = $files1[$i];
			if(!( $fName == "." || $fName == "..")){
				$fileFix ++;
				$fNewName = './images/'.$topic.'/'.$topic.'-temp' . str_pad($fileFix, 2, "0", STR_PAD_LEFT) . '.png';
				$fOldName = "./images/".$topic.'/' . $fName;
				rename( $fOldName, $fNewName);
			}
		}
		$files1 = scandir($dir);
		for( $i = 0, $fileFix = 0; $i < count($files1); $i++){
			$fName = $files1[$i];
			if(!( $fName == "." || $fName == "..")){
				$fileFix ++;
				$fOldName = './images/'.$topic.'/'.$topic.'-temp' . str_pad($fileFix, 2, "0", STR_PAD_LEFT) . '.png';
				$fNewName = "./images/".$topic."/".$topic."-" . str_pad($fileFix, 2, "0", STR_PAD_LEFT) . '.png';
				rename( $fOldName, $fNewName);
			}
		}
	}
	if( isset($_POST['deleteImages'])){
		$topic = $_POST['topicName'];
		$deleteImgs = $_POST['deleteImages'];
		$arrIds = explode(",", $deleteImgs);
		for ( $i = 0; $i < count($arrIds); $i ++){
			$fName = './images/'.$topic.'/'.$topic.'-' . str_pad($arrIds[$i], 2, "0", STR_PAD_LEFT) . '.png';
			unlink($fName);
		}
		fileRename($topic);
		$nFiles = getImageCount($topic);
		echo $nFiles;
	}
	if( isset($_POST['getImgFileCount'])){
		$topic = $_POST['topicName'];
		echo getImageCount($topic);
	}
	if(isset($_POST['submit'])){
		if(count($_FILES['upload']['name']) > 0){
			$topic = $_POST['editFormTopic'];
			var_dump($topic);
			if( $topic != '.' && $topic != '..' && $topic != ''){
				for($i=0; $i<count($_FILES['upload']['name']); $i++) {
					$tmpFilePath = $_FILES['upload']['tmp_name'][$i];
					if($tmpFilePath != ""){
						$shortname = $_FILES['upload']['name'][$i];
						$filePath = "./images/".$topic."/" . date('d-m-Y-H-i-s').'-'.$_FILES['upload']['name'][$i];
						if(move_uploaded_file($tmpFilePath, $filePath)) {
							$files[] = $shortname;
						}
					}
				}
				fileRename($topic);
			}
		}
	}
	if(isset($_POST['topicChange'])){
		$topic = $_POST['topicChange'];
		$dir = './images/'.$topic.'/';
		$files1 = scandir($dir);
		echo count($files1) - 2;
	}
	if(isset($_POST['addTopic'])){
		$topicName = $_POST['addTopic'];
		mkdir('./images/'.$topicName);
		echo $topicName;
	}
	if(isset($_POST['delTopic'])){
		$topicName = $_POST['delTopic'];
		rmdir('./images/'.$topicName);
		echo $topicName;
	}
?>