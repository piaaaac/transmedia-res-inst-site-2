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
let partsNum;
let parts = [];
let dim;

function preload () {
  partsNum = floor(random(10,15));
  for (let i= 0; i < partsNum; i++) {
    img = loadImage(imgFiles.splice(floor(random() * imgFiles.length), 1));
    parts.push(img);
  }
}

function setup () {
  var canvas = createCanvas(window.innerWidth, window.innerHeight, WEBGL);
  imageMode(CENTER)
  console.log(parts)
  dim = min(width, height) * 0.5;
}

function draw() {
  background(0)
  parts.forEach(function (part, partIndex) {
    var size = dim + noise(millis()/10000 + partIndex) * dim * 0.3;
    if (partIndex !== partsNum-1) {
      image(part, 0,0, size, size);
    } else {
      var partPixels = part.get(0,0, part.width, part.height);
      partPixels.loadPixels();
      for (var i = 0; i < partPixels.pixels.length; i+=4) {
        var pr = partPixels.pixels[i + 0];
        var pg = partPixels.pixels[i + 1];
        var pb = partPixels.pixels[i + 2];
        var my = (map(mouseY, 0,height, 0,1) - 0.5) * 2; // -1 > 1
        var threshold = 765 - abs(my * 765);
        if (pr + pg + pb > threshold) {
          partPixels.pixels[i + 3] = 0;
        }
      }
      partPixels.updatePixels();
      image(partPixels, 0,0, size, size);
    }

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