<?php
  $filename = "gpu/" . $_GET["hostname"];
  $diff = floor((time()-filemtime($filename))/60);
  if ($diff > 10) {
    echo("Minutes since last update: " . $diff . "\nLast known status: ");
    $status=file_get_contents($filename);
    if ($status == "1")
      echo("in use");
    elseif ($status == "0")
      echo("free");
    else
      echo($status);
  }
  else {
    echo(file_get_contents($filename));
  }
?>
