<?php
	$arrCards = array();
	$topic = "";
	function getImageCount($subTopic){
		$dir = './images/' . $subTopic . '/';
		$files1 = scandir($dir);
		$retVal = 0;
		for( $i = 0; $i < count($files1); $i++){
			$fName = $files1[$i];
			if( strpos($fName, '.png') !== false){
				$retVal ++;
			}
		}
		return $retVal;
	}
	if( isset($_POST['topic'])){
		$topic = $_POST['topic'];
	}
	while ( count($arrCards) < 18) {
		$randomNumber = rand(1, getImageCount($topic));
		if( !in_array($randomNumber, $arrCards)){
			array_push($arrCards, str_pad($randomNumber, 2, "0", STR_PAD_LEFT));
			array_push($arrCards, str_pad($randomNumber, 2, "0", STR_PAD_LEFT));
		}
	}
	for( $i = 0; $i < 18; $i++){
		$randomNumber = rand(0, 17);
		$buff = $arrCards[$i];
		$arrCards[$i] = $arrCards[$randomNumber];
		$arrCards[$randomNumber] = $buff;
	}
	$myJSON = json_encode($arrCards);
	echo $myJSON;
?>