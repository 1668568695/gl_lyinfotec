<?php

function saveFile($dir='./', $fileName, $txt) {
	
	if(!$txt) return;
	
	if(!$fileName) $fileName = 'gl-' . date('YmdHis') . rand(1000, 9999);

	if(!is_dir($dir)) mkdir($dir, 0777, true);

	$filePath = $dir . '/' . $fileName;

	file_put_contents($filePath, $txt);
	
	return $filePath;
			
}








