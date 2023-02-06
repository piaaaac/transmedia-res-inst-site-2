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
uniform sampler2D colorImage;
uniform sampler2D depthImage;
uniform sampler2D originalImage;
uniform vec2 mouse; // 0-1

void main() {
  vec2 uv = vTexCoord;
  uv.y = 1.0 - uv.y;

  vec4 depth = texture2D(depthImage, uv);
  
  // gl_FragColor = texture2D(originalImage, uv + mouse*vec2(0.1, 0.05) * depth.g); // SMALL
  // gl_FragColor = texture2D(originalImage, uv + mouse*vec2(5.0, 5.0) * depth.g); // MASSIVE
  // gl_FragColor = vec4(1.0, 1.0, 1.0, 1.0) - (texture2D(depthImage, uv) + texture2D(originalImage, uv + mouse*vec2(1.0, 1.0) * depth.g)); // LARGE

  gl_FragColor = vec4(1.0, 1.0, 1.0, 1.0) - (texture2D(colorImage, uv) + texture2D(originalImage, uv + mouse*vec2(1.0, 1.0) * depth.g)); // LARGE

}
</script>

<script>

/**
 * 
 * image effect here: https://editor.p5js.org/piaaaac/sketches/LFR8gG4nP
 * 
 * */

let imgs = [
  // "ai-other/davide1.jpg",
  // "ai-other/davide2.jpg",
  // "fleshy/fleshy-1.jpg", "fleshy/fleshy-2.jpg", "fleshy/fleshy-3.jpg", "fleshy/fleshy-4.jpg", "fleshy/fleshy-5.jpg", "fleshy/fleshy-6.jpg", "fleshy/fleshy-7.jpg", "fleshy/", "fleshy/fleshy-9.jpg", "fleshy/fleshy-10.jpg", "fleshy/fleshy-11.jpg", "fleshy/fleshy-12.jpg", "fleshy/fleshy-13.jpg", "fleshy/fleshy-14.jpg", "fleshy/fleshy-16.jpg", "fleshy/fleshy-17.jpg", "fleshy/fleshy-18.jpg", "fleshy/fleshy-19.jpg", "fleshy/fleshy-20.jpg", "fleshy/fleshy-21.jpg", "fleshy/fleshy-23.jpg", "fleshy/fleshy-24.jpg", "fleshy/fleshy-25.jpg", "fleshy/fleshy-26.jpg", "fleshy/fleshy-27.jpg", "fleshy/fleshy-28.jpg", "fleshy/fleshy-30.jpg", "fleshy/fleshy-31.jpg", "fleshy/fleshy-32.jpg", "fleshy/fleshy-33.jpg", "fleshy/fleshy-34.jpg", "fleshy/fleshy-35.jpg",
  // "fleshy/big-alien-metallic-flesh.jpg", 
  "fleshy/big-fleshy-combo.jpg", 
  "monk-nyc.jpg", 
  ];
let imgPath = "images/";
let imgMap, imgTypography, imgColor;
let blendedDepthMap;
let myShader;
let sliders;
let ctx;
let text = `·······················T
························RA
······················NS
··················MEDI
················A·RES
···············EARCH
·············INSTITU
···········TE·TRA
········NSMED
······IA·RES
·····EARC
····H·INS
····TI`;

function preload () {
  let loaded = 0;
  let item = random(imgs);
  imgMap = loadImage(imgPath + item);
  imgColor = loadImage(imgPath + item);
}

function setup () {
  var canvas = createCanvas(window.innerWidth, window.innerHeight, WEBGL);
  noStroke();

  // blendedDepthMap = createGraphics(width, height, P2D);
  rectMode(CENTER);
  // sliders = createSliders({
  //   "kerning": { x: 20, y: 20, w: 200, text: "kerning", min: 0, max: 255, startVal: 17, step: 5},
  // });

  // set up offscreen canvas imgTypography
  imgTypography = createGraphics(width, height);
  ctx = imgTypography.drawingContext;
  imgTypography.fill(0);
  imgTypography.background(255)
  writeTexts();
  
  // imgColor = createGraphics(imgMap.width, imgMap.height);
  // imgColor.copy(imgMap, 0, 0, imgMap.width, imgMap.height, 0, 0, imgMap.width, imgMap.height);

  var vertexShaderSource = document.querySelector("#vertex-shader").text;
  var fragmentShaderSource = document.querySelector("#fragment-shader").text;
  myShader = createShader(vertexShaderSource, fragmentShaderSource);
  shader(myShader);
  myShader.setUniform("originalImage", imgTypography);
  myShader.setUniform("depthImage", imgMap);
  myShader.setUniform("colorImage", imgColor);

}

function draw() {
  editImage(imgMap);

  if (mouseIsPressed) {
    myShader.setUniform("colorImage", imgColor);
  } else {
    myShader.setUniform("originalImage", get());
  }

  // myShader.setUniform("mouse", [mouseX/width-0.5, mouseY/height-0.5]);
  // myShader.setUniform("mouse", [1,1]);
  myShader.setUniform("mouse", [mouseX/width-0.5 + (noise(millis()*0.0001)-0.5) * 0.95, mouseY/height-0.5 + (noise(millis()*0.0001)-0.5) * 0.95]);

  shader(myShader);
  quad(-1, -1, 1, -1, 1, 1, -1, 1);

  // var normMX = (mouseX/width - 0.5)*0.8
  // var normMY = (mouseY/height - 0.5)*0.8
  // quad(-1+normMX, -1-normMY, 1+normMX, -1-normMY, 1+normMX, 1-normMY, -1+normMX, 1-normMY);

}    

function writeTexts () {
  var r = random();
  if (r < 0) {
    // imgTypography.textSize(height/25);
    // imgTypography.textFont('Arial');
    // imgTypography.text(text, width*0.3, height*0.2, width*0.4, height);
  } else {
    imgTypography.textSize(width/8);
    // imgTypography.textFont('Arial');
    imgTypography.textFont('Arial');
    imgTypography.textStyle(BOLD);
    imgTypography.textAlign(CENTER, CENTER);
    // https://editor.p5js.org/Vamoss/sketches/x4Z8KBms
    imgTypography.canvas.style.letterSpacing = "-1em";
    // ctx.font = '250px Arial';
    ctx.font = '250px Martian Mono';
    imgTypography.text("TRANSMEDIA", width/2, height*0.3);
    imgTypography.text("RESEARCH", width/2, height*0.5);
    imgTypography.text("INSTITUTE", width/2, height*0.7);
  }  
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

function editImage (img) {
  img.loadPixels();
  for (var y = 0; y < img.height; y++) {
    for (var x = 0; x < img.width; x++) {
      var index = (x + y * img.width)*4;
      var r = img.pixels[index+0];
      var g = img.pixels[index+1];
      var b = img.pixels[index+2];
      var a = img.pixels[index+3];     

      // v1
      // img.pixels[index+0] = r;
      // img.pixels[index+1] = g + 3;
      // img.pixels[index+2] = b;

      // v2
      // img.pixels[index+0] = r + 1;
      // img.pixels[index+1] = g + 4;
      // img.pixels[index+2] = b - 2;

      // v3
      img.pixels[index+0] = r + 0.8;
      img.pixels[index+1] = g + 0.8;
      img.pixels[index+2] = b - 0.8;

      if (img.pixels[index+0] >= 255) img.pixels[index+0] = 0;
      if (img.pixels[index+1] >= 255) img.pixels[index+1] = 0;
      if (img.pixels[index+2] >= 255) img.pixels[index+2] = 0;
      if (img.pixels[index+0] < 0) img.pixels[index+0] = 255;
      if (img.pixels[index+1] < 0) img.pixels[index+1] = 255;
      if (img.pixels[index+2] < 0) img.pixels[index+2] = 255;
      //println(img.pixels[index+0], img.pixels[index+1], img.pixels[index+2])
    }
  }
  img.updatePixels();
}

</script>
</body>
</html>