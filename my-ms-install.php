<?php
// this file was hardcoded on purpose, it's to support wp-config update
// for multisite installation
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
else if ($myStep == 3)  {
  $myStatement = 'sed -i "s/\/\*REMOVE3//g" ' . $myFile;
  exec($myStatement);
  exec('sed -i "s/REMOVE3\*\///g" ' . $myFile);
  echo 'done'. $myStep;
}
?>
<br />
<a href="/wp-admin/my-ms-install.php?step=2" title="Step2">Step2</a>
<a href="/wp-login.php?loggedout=true" title="Logout">Login again</a>
