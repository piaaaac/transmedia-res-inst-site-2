<?php
$path  = 'images/midjourney';
$path  = 'images/abuse';
$path  = 'images/dall-e';
$images = glob("$path/*.{jpeg,jpg,gif,png}", GLOB_BRACE);
?>

<!-- from https://p5js.org/reference/#/p5/createShader -->

<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="description" content="">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Untitled</title>
  <style type="text/css">body {margin: 0;}</style>
  <script src="../assets/lib/p5.min.js"></script>
</head>
<body>  

<script>

let imgPath = "Set via php on top of file";
let imgs = <?= json_encode($images) ?>;
let img;

function preload () {
  img = loadImage(imgs.splice(floor(random() * imgs.length), 1));
}

function setup () {
  createCanvas(window.innerWidth, window.innerHeight);
  noStroke();
}

function draw() {
  image(img, 0,0, width,height);

}    

</script>
</body>
</html>