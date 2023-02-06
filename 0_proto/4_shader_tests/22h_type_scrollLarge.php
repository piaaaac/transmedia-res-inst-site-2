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
      border-radius: 5px;
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
uniform sampler2D depthImage;
uniform sampler2D typeImage;
uniform vec2 displace; // 0-1
void main() {

  // v2 Better
  // vec2 uv = vTexCoord;
  // uv.y = 1.0 - uv.y;
  // vec4 pxOriginal = texture2D(originalImage, uv);
  // vec4 pxDepth = texture2D(depthImage, uv);
  // vec2 offset = vec2(displace.x * 0.15 * pxDepth.g, displace.y * 0.15 * pxDepth.r);
  // vec4 pxTypeMoved = texture2D(typeImage, uv + offset);
  // pxTypeMoved = vec4(pxTypeMoved.r, pxTypeMoved.g, pxTypeMoved.g, 0.2);
  // // gl_FragColor = vec4(pxOriginal.r, pxTypeMoved.g, pxTypeMoved.b, 1.0);
  // gl_FragColor = (pxOriginal + pxTypeMoved) * 1.0;

  // v1 OK
  vec2 uv = vTexCoord;
  uv.y = 1.0 - uv.y;
  vec4 pxOriginal = texture2D(originalImage, uv);
  vec4 pxDepth = texture2D(depthImage, uv);

  vec2 offset = vec2(displace.x * 0.15 * pxDepth.g, displace.y * 0.15 * pxDepth.r);
  vec4 pxTypeMoved = texture2D(typeImage, uv + offset);
  if (pxDepth.b > 0.0) { 
    pxTypeMoved = vec4(0.0, 0.0, 0.0, 0.0);
  }
  gl_FragColor = (pxOriginal + pxTypeMoved) * 1.0;

}
</script>
<script>
let text = `Dal   ouse moved to 22% x 230y ~ mous ~ 242.123.132.32 ~ new connection from 
Pescara ~ e moved to 22% x 230y ~ mouse moved to 23% x 230y ~ mouse moved to 22% x 230y ~ mouse moved to 21% x 230y ~ mouse moved to 20% x 230y ~ mouse m       18 al 24 luglioGiulia              Tomasello Coded Biophilia - Hacking Marea Daaaaal 20000 al 23 LuglioooEmi››››dio Battipaglia    A**dversarial ImagesSSSabato 23 Lu ~ 242.123.132.32 ~ new connection from 
Pescara ~ glio)(£&é* Room69: A Manual for Virtual Squatting                                     + Student          exhibitiondddDal 26 al 29 LuglioDal          18 al 24 luglioGiulia              Tomasel ~ 242.123.132.32 ~ new connection from 
Pescara ~ lo Coded Biophilia - Hacking Marea Daaaaal 20000 al 23 LuglioooEmidio Battipaglia    A**dversarial ImagesSSSabato 23 Luglio)(£&é* Room69:         + Stu     18 al 24idio Battipaglia                      exhibitioargherita  ⁄Severe ore 21:15  DopplereSevere Economie Ins liveDal          1       Tomasel9: A Manual for Vi         + Stu     18 al 24Battipaglia                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                   ouse moved to 22% x 230y ~ mouse moved to 22% x 230y ~ mouse moved to 23% x 230y ~ mouse moved to 22% x 230y ~ mouse moved to 21% x 230y ~ mouse moved to 20% x 230y ~ mouse m                                                                                           exhibitiorita  ⁄Severe1:15  DopplerA Manual for Virtual Squatting                                     + Student          e9 Lugouse moved to 22% x 230y ~ mouse moved to 22% x 230y ~ mouse moved to 23% x 230y ~ mouse moved to 22% x 230y ~ mouse moved to 21% x 230y ~ mouse moved to 20% x 230y ~ mouse mlioL  L  L  L  L        uuuuuuuuuca Pagan: Body Architectures for Kinesthetic Memory      Sabato 30 Luglio daldaldaldaldalle 18:30  MEconomie Instabili  + Luca Pagan Performance      Sabato 30 + dom                                                                                                                                                                                                                                                                                                         ~ 242.123.132.32 ~ new connection from 
Pescara ~                                                                                                                                         ~ 242.123.132.32 ~ new connection from 
Pescara ~                                                                                                                                                                                                                                enica 31 Luglio  Marco  ⁄D        ooooooooonouse moved to 22% x 230y ~ mouse moved to 22% x 230y ~ mouse moved to 23% x 230y ~ mouse moved to 22% x 230y ~ mouse moved to 21% x 230y ~ mouse moved to 20% x 230y ~ mouse mnarumma AI Ethics & Prosca 31 Luglio ore 21:15  Dopplereffekt live a/v + Gabor Lazar live  Luca Paga ~ 242.123.132.32 ~ new connection from 
Pescara ~ n: Body Architecture ~ 242.123.132.32 ~ new connection from 
Pescara ~ s for Kinesthetic Memory      Sabato 30 M        aaaaaaaaargherita                                                                                                                                                                                                                                                                                                                                                                                                                                                     Severe Economie ~ 242.123.132.32 ~ new connection from 
Pescara ~  Instabili  + Luca Pagan Performance      Sabato 30 + domenica 31 Luglio  Marco Donnarummthetics      Dome ~ 242.123.132.32 ~ new connection from 
Pescara ~  ~ 242.123.132.32 ~ new connection from 
Pescara ~ nica 31 Luglio ore 21:15   ~ 242.123.132.32 ~ new connection from 
Pescara ~ Dopplereffekt live a/v + Gabor Lazar live Dal          18 al 24 luglioGiulia              Tomasello Ccking Marea Daaaaal 20000 al 23 LuglioooEmi››››dio Battipaglia    A**dversarial ImagesSSSabato 23 Luglio ~ 242.123.132.32 ~ new connection from 
Pescara ~ )(£&é* Room69: A Manuang                             dent          exhibitiondddDal 26 al 29 LuglioDal      luglioGiulia              Tomasello Coded Biophilia - Hacking Marea D3 LuglioooEm A**dversarial ImagesSSS ~ 242.123.132.32 ~ new connection from 
Pescara ~ abato 23 Luglio)(£&é* Room69: A Manual for Virtual Squatting                       + Student     nD  L  L  L  L        uuuuuuuuuca Pagan: Body Architectures for Kinesthetic Memory      Sabato 30 Luglio daldaldald                                                                                                                                                  ~ 242.123.132.32 ~ new connection from 
Pescara ~                                                                          aldalle 18:30  MEc Luca Pagan Performance      Sabato 30 + domenica 31 Luglio  Marco  ⁄D        ooooooooonnarumma AI Ethics & Prosthetics       ffekt live a/v + Gabor Lazar live  Luca Pagan: Body Architectures for Kinesthetic Memory      Sabato 30 Luglio dalle 18:30  M        tabili  + Luca P ~ 242.123.132.32 ~ new connection from 
Pescara ~ agan Performance      Sabato 30 + domenica 31 Luglio  Marco Donnarumma AI Ethics & Prosthetics      Domenica  Dopplereffekt live a/v + Gabor Lazar8 al 24 luglioGiulia       lo Coded Biophilia - Hacking Marea Daaaaal 20000 al 23 Lugipaglia    A**dversarial ImagesSSSabato 23 Luglio)(£&é* Room6rtual Squatting                            dent          exhibitiondddDal 26 al 29 LuglioDal      luglioGiulia              Tomasello Coded Biophilia - Hacking Marea Daaaaal 20000 al 23 LuglioooEmidio  A**dversarial ImagesSSSabato 23 Luglio)(£&é* Room69: A Manual for Virtual Squatting                        + Student      nDal 26 al 29 LuglioL  L  L  L  L        uuuuuuuuuca Pagan: Body Architectures for Kinesthetic Memory      Sabato 30 Luglio daldaldaldaldalle 18:30  Marghe Economi ~ 242.123.132.32 ~ new connection from 
Pescara ~ e Instabili  + Luca Pagan Performance      Sabato 30 + domenica 31 Luglio  Marco  ⁄D        ooooooooonnarumma AI Ethics & Prosthetics      Domenica 31 Luglio ore 2effekt  ~ 242.123.132.32 ~ new connection from  ~ 242.123.132.32 ~ new connection from 
Pescara ~ 
Pescara ~ live a/v + Gabor Lazar live  Luca Pagan: Body Architectures for Kinesthetic Memory      Sabato 30 Luglio ouse moved to 22% x 230y ~ mouse moved to 22% x 230y ~ mouse moved to 23% x 230y ~ mouse moved to 22% x 230y ~ mouse moved to 21% x 230y ~ mouse moved to 20% x 230y ~ mouse mdalle 18:30  M        aaaaaaaaargherita Severe Economie Instabili  + Luca Pagan Performance      Sabato 30 + domenica 31 Luglio  Marco Donnarumma AI Ethics & Prosthetics      Domenica 31 Luglio ore 21:15  Dopplereffekt live a/v + Gabor Lazar livexhibitionDal 26 al 2xhibitionDal 26 al 2`;

let imgs = [
  {img: "1C.jpg", depthMap: "1-obtained-map.png"},
  {img: "2C.jpg", depthMap: "2-obtained-map-b.png"},
  {img: "3C.jpg", depthMap: "3-obtained-map.png"},
  {img: "4C.jpg", depthMap: "4-obtained-map-b.png"},
];
let imgPath = "images/creature-details-displacement/";
let img, imgMap, imgTypography, imgTypographySection;
let myShader;
let font1;
let ynorm = 0;

function preload () {
  let loaded = 0;
  let item = random(imgs);
  img = loadImage(imgPath + item.img);
  // let item1 = random(imgs);
  imgMap = loadImage(imgPath + item.depthMap);
  
  // var f1 = "fonts/NewEdge666TRIAL-RegularRounded.otf";
  var f1 = "fonts/NewEdge6666-LightRounded.otf";
  font1 = loadFont(f1, 
    () => {console.log("loadFont(): f1 loaded")},
    () => {console.log("loadFont(): f1 error")},
  );
}

function setup () {
  var w = min(windowWidth - 200, 1600);
  var h = min(windowHeight - 200, 1600);
  var bottomSafeSpace = windowWidth * 0.2;
  var cont = select("#p5-sketch");
  var canvas = createCanvas(w, h + bottomSafeSpace, WEBGL);
  canvas.parent(cont);
  cont.position((windowWidth - w)/2, (windowHeight - h)/2, "fixed");
  cont.style("height", h + "px");
  // cont.elt.position();

  // _renderer.drawingContext.enable(gl.BLEND);
  // _renderer.drawingContext.enable(gl.ALP);
  // glAlphaFunc(GL_GREATER, 0.5); 

  // _renderer.drawingContext.glBlendFunc(GL_SRC_ALPHA, GL_ONE_MINUS_SRC_ALPHA);


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
  imgTypography.textFont(font1);
  imgTypography.textSize(height/25);
  imgTypography.textAlign(CENTER);
  imgTypography.text(text + text + text + text, width*0.2, height*1.2, width*0.7, height*3.0);
  imgTypography.textAlign(LEFT);
  imgTypography.textSize(height/155);
  imgTypography.text(text + text, width*0.7, height*1.7, width*0.2, height*3.0);
}

function draw() {

  // var scrollAmt = mouseY/width;
  var scrollAmt = ynorm;
  var sy = map(scrollAmt, 0, 1, 0, height * 4);
  imgTypographySection.copy(imgTypography, 0, sy, width, height, 0, 0, width, height);
  myShader.setUniform("typeImage", imgTypographySection);
  
  // displace
  /* noise */ // myShader.setUniform("displace", [0.5 + (noise(millis()*0.0001)-0.5) * 0.95, 0.5 + (noise(millis()*0.0001)-0.5) * 0.95]);
  /* fixed */ myShader.setUniform("displace", [0.8, 0.8]);

  quad(-1, -1, 1, -1, 1, 1, -1, 1);
}    


// non p5 -----------------------------------------
document.getElementById("bg-text").textContent = text;

var body = document.body, html = document.documentElement;
var wh = window.innerHeight;
var dh = document.body.clientHeight;

let lastKnownScrollPosition = 0;
let ticking = false;
document.addEventListener("scroll", (event) => {
  lastKnownScrollPosition = window.scrollY;
  if (!ticking) {
    window.requestAnimationFrame(() => {
      doSomething(lastKnownScrollPosition);
      ticking = false;
    });
    ticking = true;
  }
});


function doSomething (y) {
  ynorm = y / (dh - wh);
  console.log(wh, dh, ynorm);
}

</script>
</body>
</html>
