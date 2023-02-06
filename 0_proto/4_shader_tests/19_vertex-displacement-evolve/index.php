<!-- VIA https://itp-xstory.github.io/p5js-shaders/#/./docs/examples/shaders_to_shapes -->

<?php
$path  = '../images/midjourney';
$path  = '../images/dall-e';
$images = glob("$path/*.{jpeg,jpg,gif,png}", GLOB_BRACE);
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8" />
  <meta name="viewport" width="device-width," initial-scale="1.0," maximum-scale="1.0," user-scalable="0" />
  <style>
    body {margin: 0; background: grey;}
    .info {
      position: fixed; left: 10px; bottom: 10px; color: rgba(255, 255, 255, 0.3); font-family: monospace; font-size: 9;
    }
  </style>
  <script src="../../assets/lib/p5.min.js"></script>
  <title>6-3 Vertex Displacement using a texture</title>
</head>
<body>
<script>

let imgPath = "Set via php on top of file";
let imgs = <?= json_encode($images) ?>;
let myShader;
let img1, img2, mapImg;
let forceTransp;

function preload() {
  myShader = loadShader("shader.vert", "shader.frag");

  // load 2 images
  img1Path = imgs.splice(floor(random() * imgs.length), 1)[0];
  img2Path = imgs.splice(floor(random() * imgs.length), 1)[0];
  img1 = loadImage(img1Path);
  img2 = loadImage(img2Path);
}

function setup() {
  createCanvas(windowWidth, windowHeight, WEBGL);
  noStroke();

  mapImg = createGraphics(width, height, P2D);
}

function draw() {
  // background(0);
  clear()

  var transp = map(mouseY, 0,height, 0,255);
  if (forceTransp !== undefined) {
    transp = forceTransp;
  }
  mapImg.background(128, 0, 100);
  mapImg.tint(255);
  mapImg.image(img1, 0, 0, width, height);
  mapImg.tint(255, transp);
  mapImg.image(img2, 0, 0, width, height);
  // mapImg = loadImage("mapImg.png");

  // push();
  // // translate(0,0);
  // texture(mapImg);
  // plane(mapImg.width, mapImg.height);
  // pop();



  // Send the frameCount to the shader
  shader(myShader);
  myShader.setUniform("uFrameCount", frameCount);
  myShader.setUniform("uNoiseTexture", mapImg);

  // Rotate our geometry on the X and Y axes
  rotateX(frameCount * 0.005);
  rotateY(frameCount * 0.007);

  // Draw some geometry to the screen
  // We're going to tessellate the sphere a bit so we have some more geometry to work with
  sphere(width / 8, 200, 200);
}

function windowResized() {
  resizeCanvas(windowWidth, windowHeight);
}

function keyPressed () {
  console.log(keyCode)
  if (keyCode === LEFT_ARROW) {
    forceTransp = 255;
  } else if (keyCode === RIGHT_ARROW) {
    forceTransp = 0;
  }
}
function keyReleased () {
  forceTransp = undefined;
}

  
</script>

<div class="info">
  inputs: mouse y / &larr; &rarr; arrows
</div>

</body>
</html>
