<!DOCTYPE html>
<html>
<title>RALI GPU availability watch</title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Raleway">
<style>
body,h1,h2,h3,h4,h5 {font-family: "Raleway", sans-serif}
.machine {font-weight: bold}
</style>
<body class="w3-light-grey">

<!-- w3-content defines a container for fixed size centered content, 
and is wrapped around the whole page content, except for the footer in this example -->
<div class="w3-content" style="max-width:1400px">

<!-- Header -->
<header class="w3-container w3-center w3-padding-32"> 
  <h1><b>RALI GPU availability watch</b></h1>
  <p>Find where to run your <span class="w3-tag">heavy</span> experiments.</p>
</header>

<!-- Grid -->
<div class="w3-row">

<!-- Blog entries -->
<div class="w3-col l12 s12">
  <!-- Blog entry -->
  <div class="w3-card-4 w3-margin w3-white">
    <div class="w3-container">
      <h3><b>Processing status</b></h3>
      <!--
      <h5>Last update: <span class="w3-opacity">
      <?php echo date("Y/m/d H:i", filemtime("gpu")); ?>
      </span></h5>
      -->
    </div>

    <div class="w3-container">
      <table>
        <?php
          $files = scandir('gpu');
          foreach ($files as $filename){
            if ($filename[0] != '.'){
              echo "<tr><td class=\"machine\">" . $filename . "</td>";
              echo "<td>:</td>";
              echo "<td id=\"m_" . $filename . "\">querying...</td></tr>";
            }
          }
        ?>
      </table>
    </div>
  </div>
  <hr>

<!-- END BLOG ENTRIES -->
</div>

<!-- END GRID -->
</div><br>

<!-- END w3-content -->
</div>

</body>
<script>
function refreshStatus(str) { // sélectionner tous les éléments machine et faire une requête pour chacun
  Array.from(document.getElementsByClassName("machine")).forEach((el) => {
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
        var target = window.document.getElementById("m_" + el.innerHTML);
        if (this.responseText == "0"){
          target.title = "No process is using the GPU.";
          target.className = "w3-green";
          target.innerHTML = "free";
        } else if (this.responseText == "1"){
          target.title = "A process is running.";
          target.className = "w3-red";
          target.innerHTML = "in use";
        } else {
          target.title = this.responseText;
          target.className = "w3-light-grey";
          target.innerHTML = "unknown";
        }
      }
    };
    xmlhttp.open("GET", "gpu-refresh.php?hostname=" + el.innerHTML, true);
    xmlhttp.send();
  });
}
setInterval(function(){refreshStatus();}, 5000);
</script>
</html>
