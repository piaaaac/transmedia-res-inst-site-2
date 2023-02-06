
<!-- from https://p5js.org/reference/#/p5/createShader -->

<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="description" content="">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Untitled</title>
  <style type="text/css">
    body {margin: 0; background: black;}
    * {cursor: none;}
  </style>
  <script src="../assets/lib/p5.min.js"></script>
</head>
<body>  


<script id="fragment-shader---------------BKP" type="notjx-shader/x-fragments">
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
uniform sampler2D depthImage;
uniform sampler2D originalImage;
uniform float random;
uniform vec2 mouse; // 0-1

void main() {
  vec2 uv = vTexCoord;
  uv.y = 1.0 - uv.y;

  vec4 depth = texture2D(depthImage, uv);

  float depthMovement = (random < 0.33) ? depth.r : (random < 0.66) ? depth.g : depth.b;

  gl_FragColor = texture2D(originalImage, uv + mouse*vec2(1.0, 1.0) * depthMovement);     // LARGE
  //gl_FragColor = texture2D(originalImage, uv + mouse*vec2(0.1, 0.05) * depthMovement);     // SMALL

}
</script>

<script>

let imgs = [
  {img: "dall-e-purple-ball.png", depthMap: "dall-e-purple-ball-cmap.png"},
  


  {img: "mid-organic_solarpunk.png", depthMap: "dall-e-flows.png"},
  {img: "dall-e-purple-ball.png", depthMap: "dall-e-flows.png"},
  {img: "dall-e-flows.png", depthMap: "dall-e-flows.png"},
];
let imgPath = "images/fake3d/";
let img, imgDepthMap;
let myShader;

function preload () {
  let item = imgs[floor(random() * imgs.length)];
  img = loadImage(imgPath + item.img);
  imgDepthMap = loadImage(imgPath + item.depthMap);
}

function setup () {
  createCanvas(window.innerWidth, window.innerHeight, WEBGL);
  noStroke();

  // create and initialize the shader
  var vertexShaderSource = document.querySelector("#vertex-shader").text;
  var fragmentShaderSource = document.querySelector("#fragment-shader").text;
  myShader = createShader(vertexShaderSource, fragmentShaderSource);
  shader(myShader);
  myShader.setUniform("originalImage", img);
  myShader.setUniform("depthImage", imgDepthMap);
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