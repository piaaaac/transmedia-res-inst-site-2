<?php
$path  = 'images/midjourney';
$path  = 'images/abuse';
$path  = 'images/ai-cutouts';
$images = glob("$path/*.{jpeg,jpg,gif,png}", GLOB_BRACE);
?>

<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="description" content="">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Untitled</title>
  <style type="text/css">
    body {margin: 0; background: white;}
/*     * {cursor: none;} */
  </style>
  <script src="../assets/lib/p5.min.js"></script>
</head>
<body>  

<script>

let imgPath = "Set via php on top of file";
let imgFiles = <?= json_encode($images) ?>;
let parts = [];
let partsNum;

function preload () {
  partsNum = random(5,8);
  for (let i= 0; i < partsNum; i++) {
    img = loadImage(imgFiles.splice(floor(random() * imgFiles.length), 1));
    parts.push(img);
  }
}

function setup () {
  var canvas = createCanvas(window.innerWidth, window.innerHeight, WEBGL);
  imageMode(CENTER)
  console.log(parts)
}

function draw() {
  background(0)
  var dim = min(width, height) * 0.5;
  parts.forEach(function (part, i) {
    var size = dim + noise(millis()/10000 + i) * dim * 0.3;
    image(part, 0,0, size, size);
  });
}    

// function createSliders (slidersData) {
//   var sliders = {};
//   Object.keys(slidersData).forEach(key => {
//     var sd = slidersData[key];
//     var s = createSlider(sd.min, sd.max, sd.startVal, sd.step);
//     s.position(sd.x, sd.y);
//     s.size(sd.w);
//     s.id(key);
//     sliders[key] = s;
//     let span = createSpan();
//     span.id("slider-text-" + key);
//     span.addClass("slider-text");
//     span.attribute("data-text", sd.text);
//     span.attribute("data-id", key);
//     span.position(sd.x + s.width + 20, sd.y);
//     span.html(sd.text + " = " + sd.startVal);
//     s.input((ev) => {
//       var el = ev.target;
//       var id = el.id;
//       var s = select("#" + id);
//       var span = select("#slider-text-" + id);
//       span.html(span.attribute("data-text") + " = " + s.value());
//     });
//   });
//   return sliders;
// }

</script>
</body>
</html>