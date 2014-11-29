<?php
$folder_to_monitor = array('C:\FOTM\Charleston Courier FOTM','C:\FOTM\Mobile Register PDFs','C:\FOTM\Richmond Enquirer PDFs');

for($j=0; $j< count($folder_to_monitor); $j++) {
	$dir = $folder_to_monitor[$j];
	$files = scandir($dir, 0);
	for($i = 2; $i < count($files); $i++) {
		$ftype = substr($files[$i], strrpos($files[$i], '.') + 1);
		if ($ftype == "pdf") {
			$file = $folder_to_monitor[$j] . "\\" . $files[$i];
			$dest = $file . ".jpg";
			if (!file_exists($dest)) {
				// Create thumbnail image for the list
				$im = new imagick();
				$im->setOption('pdf:use-cropbox', 'true');
				$im->readimage($file . '[0]');
				$im->setImageFormat('jpeg');
				$im->writeImage($dest);
			}

			$destHD = $file . ".hd.jpg";
			if (!file_exists($destHD)) {
				// Create better quality image for transcriber
				$im = new imagick();
				$im->setOption('pdf:use-cropbox', 'true');
				$im->setResolution(300,300);
				$im->readimage($file . '[0]');
				$im->setImageFormat('jpeg');
				$im->writeImage($destHD);
			}
		}
	}
}
?>