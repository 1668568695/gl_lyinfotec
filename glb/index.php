<?php
header('content-type:application:json;charset=utf8');  
header('Access-Control-Allow-Origin:*');
header('Access-Control-Allow-Methods:POST,GET,PUT');
header('Access-Control-Allow-Headers:x-requested-with,content-type');

require_once './common/Initialization.php';
require_once './common/common.function.php';
require_once './index/function.php';

$txt = $_REQUEST['txt'];
$dir = 'models/' . date('Ymd', time());
$fileName = 'gl-' . date('YmdHis', time()) . '-' . mt_rand(1000, 9999);
$filePath = saveFile($dir, $fileName . '.gltf', $txt);

if(chdir($dir)) {
	$fromPath = getcwd() . '/' . basename($filePath);
	$toPath = str_replace('.gltf', '.glb', $fromPath);
	$shell = "/data_01/phpwind/gltf-import-export/node_modules/.bin/gltf-import-export {$fromPath} -o {$toPath}";
	$gls = dirname(__FILE__).'/models/' . $fileName . '.gls';
	file_put_contents($gls, "#!/bin/bash\n{$shell} &\nrm \-f {$gls} &");
	exec("chmod +x {$gls}", $result, $fail);
	// exec($shell, $result, $fail);
	$filePath = str_replace('.gltf', '.glb', $filePath);
	$i = 0;
	while(++$i < 30) {
		if(file_exists($toPath)) break;
		sleep(1);
	}
}

ajaxData( array(
	'url' => $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] . __ROOT__ . '/' . $filePath,
	'status'=> 1
) );



/*
exit;

$MakePic = new MakePic();





$dst = "gl-01.jpg";

$src = "logo-01.png";

$dest = 'composite.jpg';

$MakePic->compositeImg($dst, $src, $dest, 'center', 'top');

$MakePic->addText($dst, $dst, '阿斯顿发生打发斯蒂芬');

*/




















