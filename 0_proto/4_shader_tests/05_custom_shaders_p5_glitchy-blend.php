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

<script id="vertex-shaderOLDOK" type="x-shader/x-vertex">
precision highp float;
varying vec2 uv;
attribute vec3 aPosition;
void main () {
  uv = (gl_Position = vec4(aPosition,1.0)).xy;
}
</script>
<script id="fragment-shaderOLDOK" type="notjx-shader/x-fragments">
precision highp float;
varying vec2 uv;
uniform sampler2D u_image;
void main() {
  gl_FragColor = vec4(uv.x, uv.y, 0.0, 1.0);
}
</script>



<script id="vertex-shader" type="x-shader/x-vertex">
attribute vec3 aPosition;
attribute vec2 aTexCoord;
varying vec2 vTexCoord;

void main () {
  // copy the coordinates
  vTexCoord = aTexCoord;

  vec4 posVec4 = vec4(aPosition, 1.0);
  //posVec4.xy = posVec4.xy * 2.0 - 1.0;
  gl_Position = posVec4;
}
</script>


<script id="fragment-shader" type="notjx-shader/x-fragments">
#ifdef GL_ES
precision mediump float;
#endif

varying vec2 vTexCoord;
uniform sampler2D texture1;
uniform sampler2D texture2;
uniform float noise;
uniform float random;
uniform vec2 mouse; // 0-1

void main() {
  vec2 uv = vTexCoord;
  uv.y = 1.0 - uv.y;

  vec4 c = vec4(0.0, 0.0, 0.0, 1.0);

  float threshold = mouse.y * 2.0;
  float r = texture2D(texture1, uv).r + texture2D(texture2, uv).r;
  float g = texture2D(texture1, uv).g + texture2D(texture2, uv).g;
  float b = texture2D(texture1, uv).b + texture2D(texture2, uv).b;
  float check = (random < 0.33) ? r : (random < 0.66) ? g : b;
  c = (check > threshold)
    ? texture2D(texture1, uv)
    : texture2D(texture2, uv);

  gl_FragColor = c;
}
</script>

<script>

let imgPath = "Set via php on top of file";
let imgs = <?= json_encode($images) ?>;
let img1;
let img2;
let myShader;

function preload () {
  img1 = loadImage(imgs.splice(floor(random() * imgs.length), 1));
  img2 = loadImage(imgs.splice(floor(random() * imgs.length), 1));
}

function setup () {
  createCanvas(window.innerWidth, window.innerHeight, WEBGL);
  noStroke();

  // create and initialize the shader
  var vertexShaderSource = document.querySelector("#vertex-shader").text;
  var fragmentShaderSource = document.querySelector("#fragment-shader").text;
  myShader = createShader(vertexShaderSource, fragmentShaderSource);
  shader(myShader);
  myShader.setUniform("texture1", img1);
  myShader.setUniform("texture2", img2);
}

function draw() {
  myShader.setUniform("random", random());
  myShader.setUniform("noise", noise(frameCount/100));
  myShader.setUniform("mouse", [mouseX/width, mouseY/height]);
  quad(-1, -1, 1, -1, 1, 1, -1, 1);
}    

</script>
</body>
</html>