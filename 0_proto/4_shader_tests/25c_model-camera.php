<?php
$path1  = 'images/midjourney';
$path2  = 'images/abuse';
$path3  = 'images/ai-cutouts';
$path4  = 'images/fleshy';
// Single path
// $images = glob("$path/*.{jpeg,jpg,gif,png}", GLOB_BRACE);

// Multiple paths
if (rand(0, 100) < 50) {
  $images = glob("{"."$path1/*.,$path2/*.}{jpeg,jpg,gif,png}", GLOB_BRACE);
} else {
  $images = glob("{"."$path3/*.,$path4/*.}{jpeg,jpg,gif,png}", GLOB_BRACE);
}
?>

<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="description" content="">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Untitled</title>
  <style type="text/css">
    body {margin: 0; background: white; font-size: 0;}
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
let textureImg;
let dim;
let camera;
let myModel;
let currX = 0; 
let currY = 0;

function preload () {
  partsNum = random(3, 10);
  for (let i= 0; i < partsNum; i++) {
    img = loadImage(imgFiles.splice(floor(random() * imgFiles.length), 1));
    parts.push(img);
  }
  // myModel = loadModel('models/postojna-cave-postojnska-jama-simplier-0.05.obj', true);
  // myModel = loadModel('models/cave-malachite.obj', true);
  myModel = loadModel('models/cave-malachite-decimate0.2.obj', true);
  // myModel = loadModel('models/cave-malachite-decimate0.05.obj', true);
  // myModel = loadModel('models/mario.obj', true);
}

function setup () {
  var canvas = createCanvas(window.innerWidth, window.innerHeight, WEBGL);
  imageMode(CENTER)
  angleMode(DEGREES)
  noStroke()

  dim = min(width, height) * 0.5;
  textureImg = createGraphics(width*2, width*2)
  parts.forEach(function (part, i) {
    var size = textureImg.width * 0.7 + noise(millis()/10000 + i) * textureImg.width * 0.3;
    textureImg.image(part, 0,0, size, size);
  });

  camera = createCamera();
  // ortho(-width / 2, width / 2, height / 2, -height / 2, -dim*3, dim*3);
  var n = 3000
  perspective(PI / 3.0 * n, width / height, 0.1, 500);


}

function draw() {
  stroke(0);
  strokeWeight(0);
  background(100);
  texture(textureImg);

  // orbitControl(5)
  // rotateZ(PI)

  var scale = min(width, height) * 0.1;
  var targetX = sin(frameCount / 60) * scale + (mouseX/width - 0.5) * scale
  var targetY = (mouseY/height - 0.5) * scale
  var z = sin(millis()/100) * scale/2
  currX -= (currX - targetX) * 0.05
  currY -= (currY - targetY) * 0.05
  camera.lookAt(0, 0, 0);
  camera.setPosition(currX, currY, z);

  model(myModel);

  // push()
  // translate(0, 0, dim*1.6)
  // rotateY(frameCount / 1 % 360)
  // pop()

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