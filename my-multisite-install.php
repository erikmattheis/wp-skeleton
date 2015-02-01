<?php
$myStep = $_GET["step"];
if ($myStep == 1) {
  shell_exec('sed -i "/\/*REMOVE1//g" wp-config.php\nsed -i "/REMOVE1*\///g" wp-config.php');
  echo 'done' + $myStep;
}
else if ($myStep == 2)  {
  shell_exec('sed -i "/\/*REMOVE2//g" wp-config.php\nsed -i "/REMOVE2*\///g" wp-config.php');
  echo 'done' + $myStep;
}
?>
