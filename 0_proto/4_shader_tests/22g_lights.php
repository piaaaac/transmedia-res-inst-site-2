<!-- from https://p5js.org/reference/#/p5/createShader -->

<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="description" content="">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>TMRI / Scrolling window</title>
  <style type="text/css">
    @font-face {
      font-family: "NewEdge6666-Rounded";
      src: url("fonts/NewEdge6666-LightRounded.otf") format("opentype"),
           url("fonts/NewEdge6666-LightRounded.woff") format("woff");
    }
    body {
      margin: 0; background: white;
      font-family: NewEdge6666-Rounded;
    }
    #p5-sketch { 
      position: fixed; top: 20px; left: 50px;
      box-shadow: 4px 3px 45px 21px rgba(0,0,0,0.35);
      font-size: 0;
      overflow: hidden;
    }
    canvas { margin: 0; }
    #bg-text {
      font-size: 50px;
      color: #ccc;
    }
  </style>
  <script src="../assets/lib/p5.min.js"></script>
</head>
<body>  
  <div id="p5-sketch"></div>
  <div id="bg-text" style=""></div>

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
uniform sampler2D originalImage;
uniform sampler2D depthImageX;
uniform sampler2D depthImageY;
uniform vec2 mouse;
void main() {

  vec2 uv = vTexCoord;
  uv.y = 1.0 - uv.y;
  vec4 pxOriginal = texture2D(originalImage, uv);
  vec4 pxDepthX = texture2D(depthImageX, uv);
  vec4 pxDepthY = texture2D(depthImageY, uv);
  gl_FragColor = (pxOriginal + pxDepthX*mouse.x + pxDepthY*mouse.y) * (1.0 - mouse.x + mouse.y);

}
</script>
<script>

let imgPath = "images/creature-details-displacement/";
let imgs = [
  {img: "2C.jpg", depthMapX: "2O-lights.jpg", depthMapY: "2V-lights.jpg"},
  {img: "4C.jpg", depthMapX: "4O-lights.jpg", depthMapY: "4V-lights.jpg"},
];
let img, imgMapX, imgMapY;
let myShader;

function preload () {
  let loaded = 0;
  let item = random(imgs);
  img = loadImage(imgPath + item.img);
  // let item1 = random(imgs);
  imgMapX = loadImage(imgPath + item.depthMapX);
  imgMapY = loadImage(imgPath + item.depthMapY);
  
  // var f1 = "fonts/NewEdge666TRIAL-RegularRounded.otf";
  var f1 = "fonts/NewEdge6666-LightRounded.otf";
  font1 = loadFont(f1, 
    () => {console.log("loadFont(): f1 loaded")},
    () => {console.log("loadFont(): f1 error")},
  );
}

function setup () {
  var cont = select("#p5-sketch");
  var canvas = createCanvas(windowWidth, windowHeight, WEBGL);
  canvas.parent(cont);
  cont.position(0, 0, "fixed");

  noStroke();
  rectMode(CENTER);

  var vertexShaderSource = document.querySelector("#vertex-shader").text;
  var fragmentShaderSource = document.querySelector("#fragment-shader").text;
  myShader = createShader(vertexShaderSource, fragmentShaderSource);
  shader(myShader);
  myShader.setUniform("originalImage", img);
  myShader.setUniform("depthImageX", imgMapX);
  myShader.setUniform("depthImageY", imgMapY);
}

function draw() {
  myShader.setUniform("mouse", [mouseX/width-0.5, mouseY/height-0.5]);
  quad(-1, -1, 1, -1, 1, 1, -1, 1);
}    


</script>
</body>
</html>
