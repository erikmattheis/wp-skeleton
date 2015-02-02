<?php
$myStep = $_GET["step"];
$myFile = dirname( __FILE__ ) . '/../wp-config.php';
$myStatement = 'sed -i "s/\/\*REMOVE1//g" ' . $myFile;
if ($myStep == 1) {
  exec($myStatement);
  exec('sed -i "s/REMOVE1\*\///g" ' . $myFile);
  //echo $myStatement;
  echo 'done'. $myStep;
}
else if ($myStep == 2)  {
  $myStatement = 'sed -i "s/\/\*REMOVE2//g" ' . $myFile;
  exec($myStatement);
  exec('sed -i "s/REMOVE2\*\///g" ' . $myFile);
  echo 'done'. $myStep;
}
?>
