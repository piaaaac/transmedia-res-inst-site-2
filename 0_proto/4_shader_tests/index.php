<?php

$path    = './';
$files = scandir($path);
$files = array_diff(scandir($path), array(".", "..", ".DS_Store", "index.php"));

$thumbsFolder = "proto-thumbs";
$thumbs = glob("$thumbsFolder/*.{jpeg,jpg,gif,png}", GLOB_BRACE);
?>


<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
  <title>Prototypes</title>
  <style>
    @font-face {
      font-family: "Authentic Sans";
      src: url("fonts/authentic-sans/otf/AUTHENTICSans-90.otf") format("opentype"),
           url("fonts/authentic-sans/woff/AUTHENTICSans-90.woff") format("woff"),
           url("fonts/authentic-sans/woff/AUTHENTICSans-90.woff2") format("woff2");
    }
    body {
      font-size: 17px;
      margin: 0;
      padding: 20px;
      background: #ccc; 
      min-height: 100vh;
      font-family: "Authentic Sans", sans-serif;
      column-count: 3;
    }
    img#thumb {
      position: fixed;
      pointer-events: none;
      width: 30vw;
      min-height: 100px;
      max-width: 500px;
    }
    p { margin: 0.3em 0; }
    a { text-decoration: none; color: white; }
    a:hover { text-decoration: underline; }
    a:visited { color: #66f; }
  </style>
</head>
<body>

<?php
foreach ($files as $file): 
  // if (is_dir($file)) { continue; }
  ?>
  <p><a href="<?= $file ?>"><?= $file ?></a></p>
<?php endforeach ?>
<img id="thumb" />

<script>
var thumbs = <?= json_encode($thumbs) ?>;
var mouseX = 200;
var mouseY = 200;
var imgThumb = document.getElementById('thumb');
var imgThumbW = imgThumb.style.width;

$("a").mouseenter(function () {
  var t = this.textContent;
  var searchedImage = "<?= $thumbsFolder ?>/"+ t +".png";
  if (thumbs.includes(searchedImage)) {
    imgThumb.src = searchedImage;
    imgThumb.style.width = imgThumbW;
  }
});
$("a").mouseleave(function () {
  imgThumb.src = "";
  imgThumb.style.width = "0";
});

document.addEventListener('mousemove', (e) => {
  mouseX = e.clientX;
  mouseY = e.clientY;
  imgThumb.style.left = mouseX +"px" 
  imgThumb.style.top = mouseY +"px" 
});


</script>
</body>
</html>