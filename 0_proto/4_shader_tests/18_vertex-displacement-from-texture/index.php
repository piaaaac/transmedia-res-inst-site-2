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
  </style>
  <script src="../../assets/lib/p5.min.js"></script>
  <title>6-3 Vertex Displacement using a texture</title>
</head>
<body>
<script>

let imgPath = "Set via php on top of file";
let imgs = <?= json_encode($images) ?>;


// This line is used for auto completion in VSCode
/// <reference path="../../node_modules/@types/p5/global.d.ts" />
//this variable will hold our shader object

let myShader;
let noise;

function preload() {
  // a shader is composed of two parts, a vertex shader, and a fragment shader
  // the vertex shader prepares the vertices and geometry to be drawn
  // the fragment shader renders the actual pixel colors
  // loadShader() is asynchronous so it needs to be in preload
  // loadShader() first takes the filename of a vertex shader, and then a frag shader
  // these file types are usually .vert and .frag, but you can actually use anything. .glsl is another common one
  myShader = loadShader("shader.vert", "shader.frag");

  noise = loadImage(imgs.splice(floor(random() * imgs.length), 1));
  // noise = loadImage("noise.png");
}

function setup() {
  // shaders require WEBGL mode to work
  createCanvas(windowWidth, windowHeight, WEBGL);
  noStroke();
}

function draw() {
  background(0);
  // shader() sets the active shader with our shader
  shader(myShader);

  // Send the frameCount to the shader
  myShader.setUniform("uFrameCount", frameCount);
  myShader.setUniform("uNoiseTexture", noise);

  // Rotate our geometry on the X and Y axes
  rotateX(frameCount * 0.005);
  rotateY(frameCount * 0.002);

  // Draw some geometry to the screen
  // We're going to tessellate the sphere a bit so we have some more geometry to work with
  sphere(width / 10, 200, 200);
}

function windowResized() {
  resizeCanvas(windowWidth, windowHeight);
}
    
</script>

</body>
</html>
