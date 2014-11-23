<?php
header('Content-Type: image/jpeg');
if (isset($_GET['file'])) {
  $file = $_GET['file'];
  $dest = "$file.hd.jpg";
  if (file_exists($dest)) {
    $im = new imagick();
    $im->readImage($dest);
    echo $im;
    exit;
  }
  else {
    $im = new imagick();
    $im->setOption('pdf:use-cropbox', 'true');
    $im->setResolution(300,300);
    $im->readimage($file . '[0]');
    $im->setImageFormat('jpeg');
    $im->writeImage($dest);
    echo $im;
    exit;
  }
}
?>