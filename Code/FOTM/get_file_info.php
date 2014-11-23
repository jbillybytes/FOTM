<?php
// Contains functions to get ad information from filename: name, date and page

// Get newspaper name from filename, e.g. for "CharlestonCourier_18220103_03_02.pdf", we will retrieve "Charleston Courier"
function getName($fileName) {

  $name = "";

  if (preg_match("/(([A-Z][a-z]+)+)_/", $fileName, $matches)) {
    $adNewsName = $matches[1]; // $adNewsName looks like a string without spaces "CharlestonCourier"
    
    // Split the previous string by capital letters into the array $strings
    $count = strlen($adNewsName);
    $i = 0;
    $ii = -1;

    while ($i < $count) {
      $char = $adNewsName{$i};
      if (preg_match("/[A-Z]/", $char)) {
        $ii++;
        $strings[$ii] = $char;
      }
      else {
        $strings[$ii] .= $char;
      }
      $i++;
    }

    // Get the newspaper name with spaces
    for ($j = 0; $j < count($strings); $j++) {
      if ($j == count($strings)-1) {
        $name .= $strings[$j];
      }
      else {
        $name .= $strings[$j] . " ";
      }
    }
  }

  return $name;
}

// Get newspaper date from filename, e.g. for "CharlestonCourier_18220103_03_02.pdf", we will retrieve "03/01/1822"
function getDistributionDate($fileName) {

  $date = "";

  if (preg_match("/(\d{8})/", $fileName, $matches)) {
    $year = substr($matches[1], 0, 4);
    $month = substr($matches[1], 4, 2);
    $day = substr($matches[1], 6, 2);
    $date = $month . "/" . $day . "/" . $year;
  }

  return $date;
}

// Get newspaper page number from filename, e.g. for "CharlestonCourier_18220103_03_02.pdf", we will retrieve "03"
function getPage($fileName) {

  $page = "";

  if (preg_match("/_(\d{2})_/", $fileName, $matches)) {
    $page = $matches[1];
  }

  return $page;
}
?>