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
uniform vec2 mouse; // 0-1

void main() {
  vec2 uv = vTexCoord;
  uv.y = 1.0 - uv.y;

  vec4 depth = texture2D(depthImage, uv);
  
  gl_FragColor = texture2D(originalImage, uv + mouse*vec2(1.0, 1.0) * depth.g); // LARGE
  
  // gl_FragColor = texture2D(originalImage, uv + mouse*vec2(0.1, 0.05) * depth.g); // SMALL

  // gl_FragColor = texture2D(originalImage, uv + mouse*vec2(5.0, 5.0) * depth.g); // MASSIVE
}
</script>

<script>

let imgs = [
  {img: "flows-dall-e1.png", depthMap: "flows-dall-e1.png"},
  {img: "flows-dall-e1.png", depthMap: "flows-dall-e2.png"},
  {img: "flows-dall-e1.png", depthMap: "flows-dall-e3.png"},
  {img: "flows-dall-e1.png", depthMap: "flows-dall-e4.png"},
  {img: "flows-dall-e1.png", depthMap: "flows-dall-e5.png"},
  {img: "flows-dall-e1.png", depthMap: "flows-dall-e6.png"},
  {img: "flows-dall-e1.png", depthMap: "flows-dall-e7.png"},
  {img: "flows-dall-e1.png", depthMap: "flows-dall-e8.png"},
  {img: "flows-dall-e1.png", depthMap: "flows-dall-e9.png"},
];
let imgPath = "images/fake3d/";
let img, imgMap1, imgMap2, imgTypography;
let blendedDepthMap;
let myShader;
let sliders;
let text = "The Porto di Fano, with its nuances and connotations of 'land', 'infrastructure', and 'water', serves as a real template for the collective room69's digital intervention, which is accessible via Google Street View. The harbour’s locality was captured via drone footage. These recordings were constructed as a 360-degree world and thus became the virtual landscape for the digital artworks of the collective. To make this digital world accessible, the material was fed into Google Maps to make the artistic intervention part of the Streetview function.";

function preload () {
  let loaded = 0;
  let item = random(imgs);
  img = loadImage(imgPath + item.img);
  let item1 = random(imgs);
  imgMap1 = loadImage(imgPath + item1.depthMap);
  let item2 = random(imgs);
  imgMap2 = loadImage(imgPath + item2.depthMap);
}

function setup () {
  createCanvas(window.innerWidth, window.innerHeight, WEBGL);
  noStroke();

  blendedDepthMap = createGraphics(width, height, P2D);
  rectMode(CENTER);
  sliders = createSliders({
    "blend": { x: 20, y: 20, w: 200, text: "map image blend", textColor: color(128), min: 0, max: 255, startVal: 127, step: 5},
  });

  var vertexShaderSource = document.querySelector("#vertex-shader").text;
  var fragmentShaderSource = document.querySelector("#fragment-shader").text;
  myShader = createShader(vertexShaderSource, fragmentShaderSource);
  shader(myShader);
  myShader.setUniform("originalImage", img);

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


  var transp = sliders.blend.value();
  blendedDepthMap.imageMode(CENTER);
  blendedDepthMap.background(128, 255, 0);
  blendedDepthMap.tint(255);
  blendedDepthMap.image(imgMap1, 0, 0, width*2, height*2);
  blendedDepthMap.tint(255, transp);
  blendedDepthMap.image(imgMap2, 0, 0, width*2, height*2);

  myShader.setUniform("originalImage", imgTypography);
  myShader.setUniform("depthImage", blendedDepthMap);
  myShader.setUniform("mouse", [mouseX/width-0.5 + (noise(millis()*0.0001)-0.5) * 0.95, mouseY/height-0.5 + (noise(millis()*0.0001)-0.5) * 0.95]);

  quad(-1, -1, 1, -1, 1, 1, -1, 1);
}    

function createSliders (slidersData) {
  var sliders = {};
  Object.keys(slidersData).forEach(key => {
    var sd = slidersData[key];
    var s = createSlider(sd.min, sd.max, sd.startVal, sd.step);
    s.position(sd.x, sd.y);
    s.size(sd.w);
    s.id(key);
    sliders[key] = s;
    let span = createSpan();
    span.id("slider-text-" + key);
    span.style("color", sd.textColor)
    span.addClass("slider-text");
    span.attribute("data-text", sd.text);
    span.attribute("data-id", key);
    span.position(sd.x + s.width + 20, sd.y);
    span.html(sd.text + " = " + sd.startVal);
    s.input((ev) => {
      var el = ev.target;
      var id = el.id;
      var s = select("#" + id);
      var span = select("#slider-text-" + id);
      span.html(span.attribute("data-text") + " = " + s.value());
    });
  });
  return sliders;
}

</script>
</body>
</html>