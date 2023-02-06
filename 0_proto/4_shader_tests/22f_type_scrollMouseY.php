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
    #p5-sketch { position: fixed; top: 0; left: 0; }
  </style>
  <script src="../assets/lib/p5.min.js"></script>
</head>
<body>  
  <div id="p5-sketch"></div>

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
uniform sampler2D depthImage;
uniform sampler2D typeImage;
uniform vec2 displace; // 0-1
void main() {
  vec2 uv = vTexCoord;
  uv.y = 1.0 - uv.y;
  vec4 pxDepth = texture2D(depthImage, uv);
  vec4 pxTypeMoved = texture2D(typeImage, uv + displace*vec2(0.1, 0.3) * pxDepth.g);
  vec4 pxOriginal = texture2D(originalImage, uv);
  gl_FragColor = (pxOriginal + pxTypeMoved) * 0.8;
}
</script>
<script>
let text = `Dal          18 al 24 luglioGiulia              Tomasello Coded Biophilia - Hacking Marea Daaaaal 20000 al 23 LuglioooEmi››››dio Battipaglia    A**dversarial ImagesSSSabato 23 Luglio)(£&é* Room69: A Manual for Virtual Squatting                                     + Student          exhibitiondddDal 26 al 29 LuglioDal          18 al 24 luglioGiulia              Tomasello Coded Biophilia - Hacking Marea Daaaaal 20000 al 23 LuglioooEmidio Battipaglia    A**dversarial ImagesSSSabato 23 Luglio)(£&é* Room69:         + Stu     18 al 24idio Battipaglia                      exhibitioargherita  ⁄Severe ore 21:15  DopplereSevere Economie Ins liveDal          1       Tomasel9: A Manual for Vi         + Stu     18 al 24Battipaglia                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                              exhibitiorita  ⁄Severe1:15  DopplerA Manual for Virtual Squatting                                     + Student          e9 LuglioL  L  L  L  L        uuuuuuuuuca Pagan: Body Architectures for Kinesthetic Memory      Sabato 30 Luglio daldaldaldaldalle 18:30  MEconomie Instabili  + Luca Pagan Performance      Sabato 30 + dom                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                              enica 31 Luglio  Marco  ⁄D        ooooooooonnarumma AI Ethics & Prosca 31 Luglio ore 21:15  Dopplereffekt live a/v + Gabor Lazar live  Luca Pagan: Body Architectures for Kinesthetic Memory      Sabato 30 M        aaaaaaaaargherita                                                                                                                                                                                                                                                                                                                                                                                                                                                     Severe Economie Instabili  + Luca Pagan Performance      Sabato 30 + domenica 31 Luglio  Marco Donnarummthetics      Domenica 31 Luglio ore 21:15  Dopplereffekt live a/v + Gabor Lazar live Dal          18 al 24 luglioGiulia              Tomasello Ccking Marea Daaaaal 20000 al 23 LuglioooEmi››››dio Battipaglia    A**dversarial ImagesSSSabato 23 Luglio)(£&é* Room69: A Manuang                             dent          exhibitiondddDal 26 al 29 LuglioDal      luglioGiulia              Tomasello Coded Biophilia - Hacking Marea D3 LuglioooEm A**dversarial ImagesSSSabato 23 Luglio)(£&é* Room69: A Manual for Virtual Squatting                       + Student     nD  L  L  L  L        uuuuuuuuuca Pagan: Body Architectures for Kinesthetic Memory      Sabato 30 Luglio daldaldald                                                                                                                                                                                                                          aldalle 18:30  MEc Luca Pagan Performance      Sabato 30 + domenica 31 Luglio  Marco  ⁄D        ooooooooonnarumma AI Ethics & Prosthetics       ffekt live a/v + Gabor Lazar live  Luca Pagan: Body Architectures for Kinesthetic Memory      Sabato 30 Luglio dalle 18:30  M        tabili  + Luca Pagan Performance      Sabato 30 + domenica 31 Luglio  Marco Donnarumma AI Ethics & Prosthetics      Domenica  Dopplereffekt live a/v + Gabor Lazar8 al 24 luglioGiulia       lo Coded Biophilia - Hacking Marea Daaaaal 20000 al 23 Lugipaglia    A**dversarial ImagesSSSabato 23 Luglio)(£&é* Room6rtual Squatting                            dent          exhibitiondddDal 26 al 29 LuglioDal      luglioGiulia              Tomasello Coded Biophilia - Hacking Marea Daaaaal 20000 al 23 LuglioooEmidio  A**dversarial ImagesSSSabato 23 Luglio)(£&é* Room69: A Manual for Virtual Squatting                        + Student      nDal 26 al 29 LuglioL  L  L  L  L        uuuuuuuuuca Pagan: Body Architectures for Kinesthetic Memory      Sabato 30 Luglio daldaldaldaldalle 18:30  Marghe Economie Instabili  + Luca Pagan Performance      Sabato 30 + domenica 31 Luglio  Marco  ⁄D        ooooooooonnarumma AI Ethics & Prosthetics      Domenica 31 Luglio ore 2effekt live a/v + Gabor Lazar live  Luca Pagan: Body Architectures for Kinesthetic Memory      Sabato 30 Luglio dalle 18:30  M        aaaaaaaaargherita Severe Economie Instabili  + Luca Pagan Performance      Sabato 30 + domenica 31 Luglio  Marco Donnarumma AI Ethics & Prosthetics      Domenica 31 Luglio ore 21:15  Dopplereffekt live a/v + Gabor Lazar livexhibitionDal 26 al 2xhibitionDal 26 al 2`;

let imgs = [
  {img: "1C.jpg", depthMap: "1-obtained-map.png"},
  {img: "2C.jpg", depthMap: "2-obtained-map.png"},
  {img: "3C.jpg", depthMap: "3-obtained-map.png"},
  {img: "4C.jpg", depthMap: "4-obtained-map.png"},
];
let imgPath = "images/creature-details-displacement/";
let img, imgMap, imgTypography, imgTypographySection;
let myShader;
let sliders;

function preload () {
  let loaded = 0;
  let item = random(imgs);
  img = loadImage(imgPath + item.img);
  // let item1 = random(imgs);
  imgMap = loadImage(imgPath + item.depthMap);
}

function setup () {
  var canvas = createCanvas(700, 400, WEBGL);
  canvas.parent("p5-sketch");

  noStroke();
  rectMode(CENTER);

  var vertexShaderSource = document.querySelector("#vertex-shader").text;
  var fragmentShaderSource = document.querySelector("#fragment-shader").text;
  myShader = createShader(vertexShaderSource, fragmentShaderSource);
  shader(myShader);
  myShader.setUniform("originalImage", img);
  myShader.setUniform("depthImage", imgMap);

  // write text on imgTypography
  imgTypography = createGraphics(width, height * 5);
  ctx = imgTypography.drawingContext;
  imgTypographySection = createGraphics(width, height);
  imgTypography.fill(255);
  imgTypography.background(0);
  if (random() < 0.9) {
    imgTypography.textSize(height/25);
    imgTypography.textFont('Arial');
    imgTypography.text(text, width*0.2, height*0.5, width*0.6, height*4.8);
  }
}

function draw() {

  var scrollAmt = mouseY/width;
  var sy = map(scrollAmt, 0, 1, 0, height * 4);
  imgTypographySection.copy(imgTypography, 0, sy, width, height, 0, 0, width, height);
  myShader.setUniform("typeImage", imgTypographySection);
  
  // displace
  /* noise */ // myShader.setUniform("displace", [0.5 + (noise(millis()*0.0001)-0.5) * 0.95, 0.5 + (noise(millis()*0.0001)-0.5) * 0.95]);
  /* fixed */ myShader.setUniform("displace", [1, 1]);

  quad(-1, -1, 1, -1, 1, 1, -1, 1);
}    

</script>


</body>
</html>
















