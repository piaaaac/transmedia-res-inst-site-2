<?php
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
  <style type="text/css">
    body {margin: 0; background: orange;}
    * {cursor: none;}
  </style>
  <script src="../assets/lib/p5.min.js"></script>
</head>
<body>  


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
uniform sampler2D textureTypography;
uniform float random;
uniform vec2 mouse; // 0-1

void main() {
  vec2 uv = vTexCoord;
  uv.y = 1.0 - uv.y;

  vec4 c = vec4(0.0, 0.0, 0.0, 1.0);

  float typeSpace = 0.6;
  float threshold = mouse.y * 2.0 + typeSpace;

  float r = texture2D(texture1, uv).r + texture2D(texture2, uv).r;
  float g = texture2D(texture1, uv).g + texture2D(texture2, uv).g;
  float b = texture2D(texture1, uv).b + texture2D(texture2, uv).b;
  float check = (random < 0.33) ? r : (random < 0.66) ? g : b;

  c = (check < (threshold - typeSpace))
    ? texture2D(texture1, uv)
    : (check > (threshold + typeSpace))
      ? texture2D(texture2, uv)
      : texture2D(textureTypography, uv);

  gl_FragColor = c;


}
</script>

<script>

let imgPath = "Set via php on top of file";
let imgs = <?= json_encode($images) ?>;
let img1;
let img2;
let imgTypography;
let myShader;
let myFont;

function preload () {
  img1 = loadImage(imgs.splice(floor(random() * imgs.length), 1));
  img2 = loadImage(imgs.splice(floor(random() * imgs.length), 1));
  myFont = loadFont('../assets/fonts/NeueMontreal-Regular.otf');
}

function setup () {
  createCanvas(window.innerWidth, window.innerHeight, WEBGL);
  noStroke();

  // write text on imgTypography
  imgTypography = createGraphics(width, height);
  // imgTypography.background(155);
  imgTypography.fill(255);
  imgTypography.textSize(120);
  imgTypography.textFont(myFont);
  imgTypography.textAlign(CENTER, CENTER);
  imgTypography.text("TRANSMEDIA", width/2, height/2 - 110);
  imgTypography.text("RESEARCH", width/2, height/2);
  imgTypography.text("INSTITUTE", width/2, height/2 + 110);

  // create and initialize the shader
  var vertexShaderSource = document.querySelector("#vertex-shader").text;
  var fragmentShaderSource = document.querySelector("#fragment-shader").text;
  myShader = createShader(vertexShaderSource, fragmentShaderSource);
  shader(myShader);
  myShader.setUniform("texture1", img1);
  myShader.setUniform("texture2", img2);
  myShader.setUniform("textureTypography", imgTypography);
}

function draw() {
  myShader.setUniform("random", random());
  // myShader.setUniform("noise", noise(frameCount/100));
  myShader.setUniform("mouse", [mouseX/width-0.5, mouseY/height-0.5]);
  quad(-1, -1, 1, -1, 1, 1, -1, 1);
}    

</script>
</body>
</html>