
<?php
$thumbsFolder = "../proto-thumbs";
$thumbs = glob("$thumbsFolder/*.{jpeg,jpg,gif,png}", GLOB_BRACE);
?>

<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="description" content="">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>TMRI / Scrolling window</title>
  <link rel="stylesheet" type="text/css" href="style.css">
  <script src="../../assets/lib/p5.min.js"></script>
</head>
<body>

<div id="background-creature">
  <video autoplay muted loop>
    <source src="../videos/cave-bg-1.mp4" type="video/mp4">
    <source src="../videos/cave-bg-1.webm" type="video/ogg">
  </video>
</div>

<div id="aiscreens">
  <?php foreach ($thumbs as $img): 
    $maxDist = 300;
    $w_vw = rand() / mt_getrandmax() * 20 + 10;
    $h_vh = rand() / mt_getrandmax() * 20 + 10;
    $x_vw = rand() / mt_getrandmax() * 70;
    $y_vh = rand() / mt_getrandmax() * 70;
    $z_px = rand() / mt_getrandmax() * $maxDist; // max distance on Z axis
    $blur = $z_px / $maxDist * 30;
    $show = rand(0, 10) < 3;
    ?>
    <div
    class="aiscreen<?= $show ? " active" : "" ?>" 
    data-w="<?= $w_vw ?>"
    data-h="<?= $h_vh ?>"
    data-x="<?= $x_vw ?>"
    data-y="<?= $y_vh ?>"
    data-z="<?= -$z_px ?>"
    style="
      width: <?= $w_vw ?>vw; 
      height: <?= $h_vh ?>vh; 
      left: <?= $x_vw ?>vw; 
      top: <?= $y_vh ?>vh; 
      background-image: url('<?= $img ?>');
      filter: blur(<?= $blur ?>px);
      opacity: <?= $show ? 1 : 0 ?>;
    "></div>
  <?php endforeach?>
</div>

<script>

// document.querySelector('video').playbackRate = 0.15;

var aiscreens = document.querySelectorAll('.aiscreen');

document.addEventListener('mousemove', (e) => {
  mouseX = e.clientX / window.innerWidth;
  mouseY = e.clientY / window.innerHeight;
  console.log(mouseX, mouseY);
  var activeScreens = document.querySelectorAll('.aiscreen.active');
  activeScreens.forEach(el => {
    console.log(el.style.backgroundImage);
    var z = el.dataset.z;
    var znorm = z/<?= $maxDist ?>;
    var zamp = 0.2 + znorm * 2;
    var transformX = "translateX(" + mouseX*200*zamp + "px)";
    var transformY = "translateY(" + mouseY*280*zamp + "px)";
    var transformZ = "translateZ(" + "<?= $z_px ?>" + "px)";
    el.style.transform = [transformX, transformY, transformZ].join(" ");
  });
  
});

</script>

</body>
</html>
