<!-- from https://p5js.org/reference/#/p5/createShader -->

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

<script id="vertex-shader" type="x-shader/x-vertex">
attribute vec3 aPosition;
attribute vec2 aTexCoord;
varying vec2 vTexCoord;
void main () {
  vTexCoord = aTexCoord;
  vec4 posVec4 = vec4(aPosition, 1.0);
  gl_Position = posVec4;
}
</script>

<script id="fragment-shader" type="notjx-shader/x-fragments">
#ifdef GL_ES
precision mediump float;
#endif
varying vec2 vTexCoord;
uniform sampler2D depthImage;
uniform sampler2D originalImage;
uniform vec2 mouse; // 0-1
void main() {
  vec2 uv = vTexCoord;
  uv.y = 1.0 - uv.y;
  vec4 depth = texture2D(depthImage, uv);
  gl_FragColor = texture2D(originalImage, uv + mouse*vec2(1.0, 1.0) * depth.g); // LARGE
}
</script>
<script>

let imgs = [
  {img: "1C.jpg", depthMap: "1-obtained-map.png"},
  {img: "2C.jpg", depthMap: "2-obtained-map.png"},
  {img: "3C.jpg", depthMap: "3-obtained-map.png"},
  {img: "4C.jpg", depthMap: "4-obtained-map.png"},
];
let imgPath = "images/creature-details-displacement/";
let img, imgMap, imgTypography;
let myShader;
let sliders;
let text = "The Porto di Fano, with its nuances and connotations of 'land', 'infrastructure', and 'water', serves as a real template for the collective room69's digital intervention, which is accessible via Google Street View. The harbour’s locality was captured via drone footage. These recordings were constructed as a 360-degree world and thus became the virtual landscape for the digital artworks of the collective. To make this digital world accessible, the material was fed into Google Maps to make the artistic intervention part of the Streetview function.";

function preload () {
  let loaded = 0;
  let item = random(imgs);
  img = loadImage(imgPath + item.img);
  let item1 = random(imgs);
  imgMap = loadImage(imgPath + item1.depthMap);
}

function setup () {
  createCanvas(700, 700, WEBGL);
  noStroke();
  rectMode(CENTER);

  var vertexShaderSource = document.querySelector("#vertex-shader").text;
  var fragmentShaderSource = document.querySelector("#fragment-shader").text;
  myShader = createShader(vertexShaderSource, fragmentShaderSource);
  shader(myShader);
  myShader.setUniform("originalImage", img);
  myShader.setUniform("depthImage", imgMap);

  // write text on imgTypography
  imgTypography = createGraphics(width, height);
  ctx = imgTypography.drawingContext;
  imgTypography.fill(255);
  imgTypography.background(0);
  if (random() < 0.5) {
    imgTypography.textSize(height/25);
    imgTypography.textFont('Arial');
    imgTypography.text(text, width*0.3, height*0.2, width*0.4, height);
  } else {
    imgTypography.textSize(width/8);
    imgTypography.textFont('Arial');
    imgTypography.textStyle(BOLD);
    imgTypography.textAlign(CENTER, CENTER);
    // https://editor.p5js.org/Vamoss/sketches/x4Z8KBms
    imgTypography.canvas.style.letterSpacing = "-2em";
    ctx.font = '300px Arial';
    imgTypography.text("SUMMER", width/2, height*0.4);
    imgTypography.text("SCHOOL", width/2, height*0.6);
  }
}

function draw() {
  // myShader.setUniform("originalImage", imgTypography);
  // myShader.setUniform("mouse", [mouseX/width-0.5 + (noise(millis()*0.0001)-0.5) * 0.95, mouseY/height-0.5 + (noise(millis()*0.0001)-0.5) * 0.95]);

  myShader.setUniform("mouse", [0.5 + (noise(millis()*0.0001)-0.5) * 0.95, 0.5 + (noise(millis()*0.0001)-0.5) * 0.95]);

  quad(-1, -1, 1, -1, 1, 1, -1, 1);
}    

</script>
</body>
</html>
















