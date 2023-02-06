<?php
$path1  = 'images/midjourney';
$path2  = 'images/abuse';
$path3  = 'images/ai-cutouts';
$path4  = 'images/fleshy';
$path5  = 'images/ai-other';
$path5  = 'images/textures-davide';
// Single path
$images = glob("$path5/*.{jpeg,jpg,gif,png}", GLOB_BRACE);

// Multiple paths
// if (rand(0, 100) < 50) {
//   $images = glob("{"."$path1/*.,$path2/*.}{jpeg,jpg,gif,png}", GLOB_BRACE);
// } else {
//   $images = glob("{"."$path3/*.,$path4/*.}{jpeg,jpg,gif,png}", GLOB_BRACE);
// }
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
let imgTypography;
let dim;
let camera;
let myModel;
let currX = 0; 
let currY = 0;
let f;

function preload () {
  partsNum = random(3, 6);
  partsNum = 1;
  for (let i= 0; i < partsNum; i++) {
    img = loadImage(imgFiles.splice(floor(random() * imgFiles.length), 1));
    parts.push(img);
  }
  // myModel = loadModel('models/postojna-cave-postojnska-jama-simplier-0.05.obj', true);
  // myModel = loadModel('models/cave-malachite.obj', true);
  myModel = loadModel('models/cave-malachite-decimate0.2.obj', true);
  // myModel = loadModel('models/cave-malachite-decimate0.05.obj', true);
  // myModel = loadModel('models/mario.obj', true);
  // f = loadFont("fonts/SuisseIntlMono-Light.otf");
  f = loadFont("fonts/SuisseIntlMono-Regular.otf");
}

function setup () {
  var canvas = createCanvas(window.innerWidth, window.innerHeight, WEBGL);
  imageMode(CENTER)
  angleMode(DEGREES)
  noStroke()

  dim = min(width, height) * 0.5;

  // set up offscreen canvas imgTypography
  imgTypography = createGraphics(width, height);
  imgTypography.textFont(f)
  ctx = imgTypography.drawingContext;
  writeTexts();
  parts.push(imgTypography);

  textureImg = createGraphics(width*2, width*2)
  parts.forEach(function (part, i) {
    var size = textureImg.width * 0.7 + noise(millis()/10000 + i) * textureImg.width * 0.3;
    textureImg.image(part, 0,0, size, size);
  });

  camera = createCamera();
  // ortho(-width / 2, width / 2, height / 2, -height / 2, -dim*3, dim*3);
  var n = 3000
  perspective(PI / 3.0 * n, width / height, 0.1, 500); // Original
  perspective(PI / 3.0 * n, width / height, 0.5, 200); // Zoomed

}

function draw() {
  stroke(0);
  strokeWeight(0);
  background(150);
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

function writeTexts () {
  imgTypography.fill(255);
  if (random() < 0.5) {
    imgTypography.textSize(width/15);
    // imgTypography.textFont('Arial');
    imgTypography.textStyle(BOLD);
    imgTypography.textAlign(CENTER, CENTER);
    // https://editor.p5js.org/Vamoss/sketches/x4Z8KBms
    // imgTypography.canvas.style.letterSpacing = "-1em";
    // ctx.font = '250px Arial';
    imgTypography.text("TRANSMEDIA", width/2, height*0.3);
    imgTypography.text("RESEARCH", width/2, height*0.5);
    imgTypography.text("INSTITUTE", width/2, height*0.7);
  } else {
    imgTypography.textSize(height/120);
    // imgTypography.textFont('Arial');
    imgTypography.text(text3 + text3 + text3, width*0.3, height*0.2, width*0.4, height);
  }
}

var text1 = `TRANSMEDIA RESEARCH INSTITUTE TRANSMEDIA RESEARCH INSTITUTE TRANSMEDIA RESEARCH INSTITUTE TRANSMEDIA RESEARCH INSTITUTE TRANSMEDIA RESEARCH INSTITUTE TRANSMEDIA RESEARCH INSTITUTE TRANSMEDIA RESEARCH INSTITUTE TRANSMEDIA       RESEARCH INSTITUTE                          TRANSMEDIA RESEARCH INSTITUTE                          TRANSMEDIA RESEARCH INSTITUTE TRANSMEDIA RESEARCH INSTITUTE TRANSMEDIA RESEARCH INSTITUTE TRANSMEDIA RESEARCH INSTITUTE TRANSMEDIA RESEARCH INSTITUTE TRANSMEDIA RESEARCH INSTITUTE TRANSMEDIA RESEARCH INSTITUTE TRANSMEDIA RESEARCH INSTITUTE TRANSMEDIA RESEARCH INSTITUTE TRANSMEDIA RESEARCH INSTITUTE TRANSMEDIA RESEARCH INSTITUTE TRANSMEDIA RESEARCH INSTITUTE TRANSMEDIA RESEARCH INSTITUTE TRANSMEDIA RESEARCH INSTITUTE TRANSMEDIA RESEARCH                          INSTITUTE TRANSMEDIA RESEARCH                          INSTITUTE TRANSMEDIA RESEARCH INSTITUTE TRANSMEDIA RESEARCH INSTITUTE TRANSMEDIA RESEARCH INSTITUTE TRANSMEDIA RESEARCH INSTITUTE TRANSMEDIA RESEARCH                          INSTITUTE TRANSMEDIA RESEARCH INSTITUTE TRANSMEDIA RESEARCH INSTITUTE                          TRANSMEDIA RESEARCH INSTITUTE TRANSMEDIA RESEARCH INSTITUTE TRANSMEDIA RESEARCH INSTITUTE TRANSMEDIA RESEARCH INSTITUTE TRANSMEDIA RESEARCH       INSTITUTE TRANSMEDIA RESEARCH       INSTITUTE TRANSMEDIA RESEARCH INSTITUTE TRANSMEDIA RESEARCH INSTITUTE TRANSMEDIA RESEARCH INSTITUTE                          TRANSMEDIA RESEARCH INSTITUTE TRANSMEDIA RESEARCH INSTITUTE TRANSMEDIA RESEARCH INSTITUTE TRANSMEDIA RESEARCH INSTITUTE TRANSMEDIA RESEARCH INSTITUTE TRANSMEDIA RESEARCH INSTITUTE TRANSMEDIA RESEARCH INSTITUTE TRANSMEDIA RESEARCH INSTITUTE TRANSMEDIA RESEARCH INSTITUTE       TRANSMEDIA RESEARCH INSTITUTE TRANSMEDIA RESEARCH INSTITUTE TRANSMEDIA RESEARCH INSTITUTE TRANSMEDIA RESEARCH INSTITUTE `;

var text2 = `
function setup () {   var canvas = createCanvas(window.innerWidth, window.innerHeight, WEBGL);   imageMode(CENTER)   angleMode(DEGREES)   noStroke()    dim = min(width, height) * 0.5;    // set up offscreen canvas imgTypography   imgTypography = createGraphics(width, height);   imgTypography.textFont(f)   ctx = imgTypography.drawingContext;   writeTexts();   parts.push(imgTypography);    textureImg = createGraphics(width*2, width*2)   parts.forEach(function (part, i) {     var size = textureImg.width * 0.7 + noise(millis()/10000 + i) * textureImg.width * 0.3;     textureImg.image(part, 0,0, size, size);   });    camera = createCamera();   // ortho(-width / 2, width / 2, height / 2, -height / 2, -dim*3, dim*3);   var n = 3000   perspective(PI / 3.0 * n, width / height, 0.1, 500);  }  function draw() {   stroke(0);   strokeWeight(0);   background(150);   texture(textureImg);    // orbitControl(5)   // rotateZ(PI)    var scale = min(width, height) * 0.1;   var targetX = sin(frameCount / 60) * scale + (mouseX/width - 0.5) * scale   var targetY = (mouseY/height - 0.5) * scale   var z = sin(millis()/100) * scale/2   currX -= (currX - targetX) * 0.05   currY -= (currY - targetY) * 0.05`;

var text3 = `
function setup () {   var INSTITUTE TRA TRANSMEDITUTE TRANSSMEDIA RESWEBGL);   creen canvpography.ddth*2)   preImg.widt, width / t, 0.1, 50A RESEARCHNSMEDIA RESEARCH INSTITUTE TRANSMEDIA RESEARCH INSTITUTE TRANSMEDIA RESEARCH INSTITUTE TRANSMEDIA RESEARCH INSTITUTE TRANSMEDIA RESEARCH INSTITUTE TRANSMEDIA RESEARCH INSTITUTE TRANSMEDIA RESEARCH                          INSTITUTE TRANSMEDIA RESEARCH                          INSTITUTE TRANSMEDIA RESEARCH INSTITUTE TRANSMEDIA RESEARCH INSTITUTEA RESEARCH INSTITUTE TRANSMEDIA RESEARCH INSTITUTE TRANSMEDIA RESEARCH                          INSTIMEDIA RESEARCH INSTITUTE TRANSMEDIA RESEARCH INSTITUTE                          TRANEARCH                                     canvas = createCanvas(window.innerWidth, window.innerHeight, imageMode(CENTER)   angleMode(DEGREES)   noStroke()    dim = min(width, height) * 0.5;    // set up offsas imgTypography   imgTypography = createGraphics(width, height);   imgTypography.textFont(f)   ctx = imgTyrawingContext;   writeTexts();   parts.push(imgTypography);    textureImg = createGraphics(width*2, wiarts.forEach(function (part, i) {     var size = textureImg.width * 0.7 + noise(millis()/10000 + i) * textuh * 0.3;     textureImg.image(part, 0,0, size, size);   });    camera = createCamera();   // ortho(-width / 22, height / 2, -height / 2, -dim*3, dim*3);   var n = 3000   perspective(PI / 3.0 * n, width / heigh0);  }  function draw() INSTITUTE TRANSMEDIA RESEARCH INSTITUTE TRANSMEDIA RESEARCH INSTITUTE TRANSMEDI INSTITUTE TRANSMEDIA RESEARCH INSTITUTE TRANSMEDIA RESEARCH INSTITUTE TRANSMEDIA RESEARCH INSTITUTE TRANSMEDIA                                    RESEARCH                          INSTITUTE TRANSMEDIA                                    RESEARCH                          INSTITUTE TRANSMEDIA                                    RESEARCH INSTITUTE TRANSMEDIA RESEARCH INSTITUTE TRANSMEDIA RESEARCH INSTITUTE TRANSMEDIA RESEARCH INSTITUTE TRANSMEDIA RESEARCH                                                             INSTITUTE TRANSMEDIA RESEARCH INSTITUTE TRANSMEDIA RESEARCH INSTITUTE                          TRANSMEDIA RESEARCH {   stroke(0);   strokeWeight(0);   background(150);   texture(textureImg);    // orbitControl                                   (5)   // rotateZ(PI)    var scale = min(width, height) * 0.1;   var targetX = sin(frameCount / 60) * scale + (mouseX/width - 0.5) * scale   var targetY = (mouseY/height - 0.5) * scale   var z = sin(millis()/100) * scale/2   currX -= (currX - targetX) * 0.05   currY -= (currY - targetY) * 0.05`;

</script>
</body>
</html>