
<?php
$images0Shape = glob("assets/images/0-shape/*.{jpeg,jpg,gif,png}", GLOB_BRACE);
$images1Language = glob("assets/images/1-language/*.{jpeg,jpg,gif,png}", GLOB_BRACE);
// print_r($images0Shape);
// print_r($images1Language);
?>

<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="description" content="">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>TMRI</title>
  <link rel="stylesheet" type="text/css" href="style.css">
  <script src="../../assets/lib/p5.min.js"></script>
</head>
<body>

<div id="tiles">
  <?php foreach ($images0Shape as $img): 
    $hx = rand(0, 1000)/1000 * 0.7; // 0 to 0.7
    $hy = rand(0, 1000)/1000 * 0.7; // 0 to 0.7
    $hw = rand(0, 1000)/1000 * (1 - $hx);
    $hh = rand(0, 1000)/1000 * (1 - $hy);
    ?>
    <div
    class="tile" 
    data-highlight-x="<?= $hx ?>"
    data-highlight-y="<?= $hy ?>"
    data-highlight-w="<?= $hw ?>"
    data-highlight-h="<?= $hh ?>"
    style="background-image: url('<?= $img ?>');"></div>
  <?php endforeach?>
</div>

<script>


// var aiscreens = document.querySelectorAll('.aiscreen');

// document.addEventListener('mousemove', (e) => {
//   mouseX = e.clientX / window.innerWidth;
//   mouseY = e.clientY / window.innerHeight;
//   console.log(mouseX, mouseY);
//   var activeScreens = document.querySelectorAll('.aiscreen.active');
//   activeScreens.forEach(el => {
//     console.log(el.style.backgroundImage);
//     var z = el.dataset.z;
//     var zamp = 0.2 + znorm * 2;
//     var transformX = "translateX(" + mouseX*200*zamp + "px)";
//     var transformY = "translateY(" + mouseY*280*zamp + "px)";
//     el.style.transform = [transformX, transformY, transformZ].join(" ");
//   });
  
// });

</script>

</body>
</html>
