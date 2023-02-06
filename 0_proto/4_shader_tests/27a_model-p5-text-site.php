<?php

/**
 * A) Html elements are glitched (text is randomly fuckupped)
 * B) Text content is fed to the creature while scrolling
 */

$path1  = 'images/midjourney';
$path2  = 'images/abuse';
$path3  = 'images/ai-cutouts';
$path4  = 'images/fleshy';
$path5  = 'images/ai-other';
$path6  = 'images/textures-davide';
// $path6  = 'images/tmp';
// Single path
$images = glob("$path6/*.{jpeg,jpg,gif,png}", GLOB_BRACE);

// Multiple paths
// if (rand(0, 100) < 50) {
//   $images = glob("{"."$path1/*.,$path2/*.}{jpeg,jpg,gif,png}", GLOB_BRACE);
// } else {
//   $images = glob("{"."$path3/*.,$path4/*.}{jpeg,jpg,gif,png}", GLOB_BRACE);
// }

$h = 300;
?>

<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="description" content="">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Untitled</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous"></head>
  <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
  <style type="text/css">
    @font-face {
      font-family: "NewEdge6666-Rounded";
      src: url("fonts/NewEdge6666-LightRounded.otf") format("opentype"),
           url("fonts/NewEdge6666-LightRounded.woff") format("woff");
    }
    body {
      margin: 0; 
      background: rgba(170, 170, 170, 1); 
      min-height: 100vh;
      font-family: NewEdge6666-Rounded;
/*     * {cursor: none;} */
    }
    #p5-cont {
      z-index: 2;
      position: fixed;
/*       background-color: rgba(255, 0, 0, 0.2); */
      top: 0; left: 0;
    }
    #contents {
      color: white;
/* 
      margin-top: 500px;
      position: relative;
      opacity: 1;
 */
    }
    a { color: #ccc; }
    a:hover { text-decoration: none; }

  </style>
  <script src="../assets/lib/p5.min.js"></script>
</head>
<body>  

<script>
/*
var initialTextCreature = `
function setup () {   var canvas = createCanvas(window.innerWidth, window.innerHeight, WEBGL);   imageMode(CENTER)   angleMode(DEGREES)   noStroke()    dim = min(width, height) * 0.5;    // set up offscreen canvas imgTypography   imgTypography = createGraphics(width, height);   imgTypography.textFont(f)   ctx = imgTypography.drawingContext;   writeTex ts();   parts.push(imgTypography);    textureImg = createGraphics(width*2, width*2)   parts.forEach(function (part, i) {     var size = textureImg.width * 0.7 + noise(millis()/10000 + i) * textureImg.width * 0.3;     textureImg.image(part, 0,0, size, size);   });    camera = createCamera();   // ortho(-width / 2, width / 2, height / 2, -height / 2, -dim*3, dim*3);   var n = 3000   perspective(PI / 3.0 * n, width / height, 0.1, 500);  }  function draw() {   stroke(0);   strokeWeight(0);   background(150);   texture(textureImg);    // orbitControl(5)   // rotateZ(PI)    var scale = min(width, height) * 0.1;   var targetX = sin(frameCount / 60) * scale + (mouseX/width - 0.5) * scale   var targetY = (mouseY/height - 0.5) * scale   var z = sin(millis()/100) * scale/2   currX -= (currX - targetX) * 0.05   currY -= (currY - targetY) * 0.05`;
*/

// Are updated while navigating
var textCreature__ = "";
var colorCreature__ = [0,0,0,0];
var textSizeCreature__;


let imgPath = "Set via php on top of file";
let imgFiles = <?= json_encode($images) ?>;
let parts = [];
let partsNum;
let textureImg;
let imgTypography;
let dim;
let camera;
let myModel;
let currX = 0; 
let currY = 0;
let f;
let glContext;

function preload () {
  console.log("preload()")
  var options = [
    // 0,
    // 2,
    3,
    // 3, 4, 5, 6,
    // 9,
  ];
  partsNum = random(options);
  for (let i= 0; i < partsNum; i++) {
    img = loadImage(imgFiles.splice(floor(random() * imgFiles.length), 1));
    parts.push(img);
  }
  // myModel = loadModel('models/postojna-cave-postojnska-jama-simplier-0.05.obj', true);
  // myModel = loadModel('models/cave-malachite.obj', true);
  // myModel = loadModel('models/mario.obj', true);

  // myModel = loadModel('models/cave-malachite-decimate0.2.obj', true);
  myModel = loadModel('models/cave-malachite-decimate0.05.obj', true);
  // myModel = loadModel('models/d-untitled.obj', true);
  // myModel = loadModel('models/joints_DECIMATED_3.obj', true);

  // f = loadFont("fonts/SuisseIntlMono-Light.otf");
  f = loadFont("fonts/NewEdge6666-LightRounded.otf");
}

function setup () {
  console.log("setup()")
  var canvas = createCanvas(window.innerWidth, <?= $h ?>, WEBGL);
  canvas.parent("p5-cont");
  glContext = canvas.GL;
  imageMode(CENTER)
  angleMode(DEGREES)
  noStroke()

  dim = min(width, height) * 0.5;
  textureImg = createGraphics(width*2, width*2)
  imgTypography = createGraphics(1000, 1000);
  imgTypography.textFont(f)
  textSizeCreature__ = width/100;

  camera = createCamera();
  
  ortho(-width / 2, width / 2, height / 2, -height / 2, -dim*3, dim*3);
  // ortho(-width, width, height, -height, -dim*3, dim*3);
  
  // var n = 1000
  // perspective(PI / 3.0 * n, 2.2, 0.1, 500);

}

function draw() {
  clear();
  background(170);
  glContext.clear(glContext.DEPTH_BUFFER_BIT);

  imgTypography.clear();
  writeP5Texts();
  parts.pop();
  parts.push(imgTypography);

  textureImg.clear();
  if (colorCreature__[3] > 0) {
    // textureImg.background(colorCreature__[0], colorCreature__[1], colorCreature__[2]);
    textureImg.stroke(colorCreature__[0], colorCreature__[1], colorCreature__[2]);
    textureImg.strokeWeight(20);
    for (var k = 0; k - 100; k++) { textureImg.rect(random*width, random*height, 20, 20);}
  }
  parts.forEach(function (part, i) {
    var size = textureImg.width * 0.7 + noise(millis()/10000 + i) * textureImg.width * 0.1;
    textureImg.image(part, 0,0, size, size);
  });

  stroke(0);
  strokeWeight(0);
  texture(textureImg);

  // orbitControl(5)
  // rotateZ(PI)

  var scalee = min(width, height) * 0.1;
  var targetX = sin(frameCount / 60) * scalee + (mouseX/width - 0.5) * scalee
  
  var targetY = (mouseY/height - 0.5) * scalee
  var targetY = map(mouseY, 0, windowHeight, -2, 5) +3;
  
  var z = sin(millis()/100) * scalee/2
  currX -= (currX - targetX) * 0.05
  currY -= (currY - targetY) * 0.05
  camera.lookAt(0, 0, 0);
  camera.setPosition(currX, currY, z);
  
  // translate(0, 600, 0);

  push()
  scale(10);
  model(myModel);
  pop()


  stroke(255, 0, 0)
  strokeWeight(20)
  rect(-200, -200, 0, 200, 200, 0)

}    

function writeP5Texts () {
  var t1 = textCreature__ + textCreature__ + textCreature__;
  var length = 16;
  var index = frameCount % (t1.length - length);
  var t2 = t1.substr(index) + t1.substr(index, length);
  imgTypography.textSize(textSizeCreature__);
  imgTypography.fill(255);
  if (colorCreature__[3] > 0) {
    imgTypography.fill(colorCreature__[0], colorCreature__[1], colorCreature__[2]);
  }
  imgTypography.text(t2, width*0.3, height*0.2, width*0.4, height);
}

</script>

<div id="p5-cont"></div>

<section id="contents">
  <div class="container">
    <div class="row">
      <div class="col-12 spacer" style="height:50vh;"></div>
      <div class="col-12">
        <h1 style="font-size: 4.1em; line-height: 0.95em;" data-glitch="yes" data-food='yes'>
          COSMIC OCEAN CONCEPT OF THE NUMBER ONE SOMETHING. INCREDIBLE IS WAITING TO BE KNOWN AS A PATCH OF LIGHT DECIPHERMENT NETWORK OF WORMHOLES.
        </h1>
      </div>
      <div class="col-12 mt-5">
        <p style="font-size: 40px; line-height: 1.1em;" data-glitch="yes" data-food='yes' class="pl-5 ml-5">
          Cosmic ocean concept of the number one something incredible is waiting to be known as a patch of light decipherment network of wormholes. Take root and flourish hydrogen atoms permanence of the stars from which we spring a still more glorious dawn awaits ship of the imagination. Invent the universe hundreds of thousands take root and flourish invent the universe bits of moving fluff hundreds of thousands. Vastness is bearable only through love finite but unbounded courage of our questions courage of our questions a mote of dust suspended in a sunbeam how far away?
        </p>
        <p style="font-size: 4.1em; line-height: 1.1em;" data-glitch="yes" data-food='yes'>
          <br />Dal 18 al 24 luglio
          <br />Giulia Tomasello <a href="">Coded Biophilia - Hacking Marea</a>
        </p>
        <p style="font-size: 4.1em; line-height: 1.1em;" data-glitch="yes" data-food='yes'>
          <br />Dal 20 al 23 Luglio
          <br />Emidio Battipaglia <a href="">Adversarial Images</a>
        </p>
        <p style="font-size: 4.1em; line-height: 1.1em;" data-glitch="no" data-food='yes'>
          <br />Sabato 23 Luglio
          <br />Room69: <a href="">A Manual for Virtual Squatting</a>
          <br />+ Student exhibition
        </p>
        <p style="font-size: 4.1em; line-height: 1.1em;" data-glitch="yes" data-food='yes'>
          <br />Dal 26 al 29 Luglio
          <br />Luca Pagan: <a href="">Body Architectures for Kinesthetic Memory</a>
        </p>
        <p style="font-size: 4.1em; line-height: 1.1em;" data-glitch="no" data-food='yes'>
          <br />Sabato 30 Luglio dalle 18:30
          <br />Margherita Severe <a href="">Economie Instabili</a>
          <br />+ Luca Pagan Performance
        </p>
        <p style="font-size: 4.1em; line-height: 1.1em;" data-glitch="no" data-food='yes'>
          <br />Sabato 30 + domenica 31 Luglio
          <br />Marco Donnarumma <a href="">AI Ethics & Prosthetics</a>
        </p>
        <p style="font-size: 4.1em; line-height: 1.1em;" data-glitch="no" data-food='yes'>
          <br />Domenica 31 Luglio ore 21:15
          <br /><a href="">Dopplereffekt live a/v + Gabor Lazar live</a></p>        
      </div>
      <div class="col-12 spacer" style="height:50vh;"></div>
    </div>
  </div>
</section>

<script>

// ---------------------
// Site texts fuckup
// ---------------------

var els = {};

// glitch();

function glitch () {
  fkpSiteTexts();
  setTimeout(resetSiteTexts, Math.random() * 400);
  setTimeout(glitch, Math.random() * 1000);
}

function fkpSiteTexts () {
  $("[data-glitch='yes']").each((i, el) => {
    if (Math.random() > 0.3) {
      return;
    }
    var id;
    if (!el.dataset.fkpid) {
      id = "fkp-item-"+ Math.random().toString(16).slice(2);
      el.dataset.fkpid = id;
      els[id] = { "originalText": $(el).text() }
    } else {
      id = el.dataset.fkpid;
    }
    var fkpText = fuckupString(els[el.dataset.fkpid].originalText);
    $(el).html(fkpText);
  });
}

function resetSiteTexts () {
  $("[data-glitch='yes']").each((i, el) => {
    if (el.dataset.fkpid) {
      var id = el.dataset.fkpid;
      $(el).html(els[el.dataset.fkpid].originalText);
    }
  });
}

function fuckupString (str, strength = 0.2) {
  var iterations = strength * 30;
  for (var i = 0; i < iterations; i++) {
    var material = "qwertyuiopasdfghjklzxcvbnm!£$%&/()=";
    var length = Math.random() * 3;
    var fuckup = "";
    for (var j = 0; j < length; j++) {
      fuckup += material.substr(Math.floor(Math.random() * material.length), 1);
    }
    var pos = Math.floor(Math.random() * str.length);
    str = str.substr(0, pos) + fuckup + str.substr(pos + fuckup.length);
    var n = Math.floor(Math.random() * 8);
    
    var spaces = new Array(n).fill("&nbsp;").join("");

    pos = Math.random() * str.length;
    str = str.substr(0, pos) + spaces + str.substr(pos);
  }
  return str;
}

// ---------------------
// Feed the creature
// ---------------------

function updateTextCreature (addition, maxLength = 1000) {
  textCreature__ += addition;
  if (textCreature__.length > maxLength) {
    var i = textCreature__.length - maxLength;
    textCreature__ = textCreature__.substr(i);
  }
  console.log(textCreature__);
}

var normalLinkColor = "#ccc";


$("a").mouseenter(function () {
  var rgb = randomRGBArray();
  this.style.color = "rgba("+ rgb.join(", ") +", 1)";;
  colorCreature__ = rgb.concat([1]);
  textCreature__ = new Array(200).fill(this.textContent).join(" ");
  textSizeCreature__ *= 2;
});

$("a").mouseleave(function () {
  this.style.color = normalLinkColor;
  colorCreature__ = [0,0,0,0];
  textSizeCreature__ /= 2;
});

// ---------------------
// Intersection Observer
// via https://developer.mozilla.org/en-US/docs/Web/API/Intersection_Observer_API
// ---------------------

let observerOptions = {
  // root: // default is window viewport
  rootMargin: '-<?= $h ?>px 0px 0px 0px', // top 200
  threshold: [0, 0.25, 0.5, 0.75, 1],
}

let observer = new IntersectionObserver(observerCallback, observerOptions);


// A
// let observerTarget = document.querySelector("[data-food='yes']"); // 1 item
// observer.observe(observerTarget);
// B
document.querySelectorAll("[data-food='yes']").forEach((i) => {
  if (i) { observer.observe(i) }
});


// the callback we setup for the observer will be executed now for the first time
// it waits until we assign a target to our observer (even if the target is currently not visible)

function observerCallback (entries, observer) {
  entries.forEach((entry) => {

    // Each entry describes an intersection change for one observed
    // target element:
    //   entry.boundingClientRect
    //   entry.intersectionRatio
    //   entry.intersectionRect
    //   entry.isIntersecting
    //   entry.rootBounds
    //   entry.target
    //   entry.time

    console.log(entry.intersectionRatio);
    console.log(entry.target);
    var el = entry.target;
    var text = el.textContent;
    var ratio = entry.intersectionRatio;
    var index = Math.floor(text.length * ratio);
    var addLength = Math.floor(text.length * 0.2);
    var addition = text.substr(index - addLength/2, index + addLength/2);
    updateTextCreature(addition);
  });
};

// ---------------------
// JS Utils
// ---------------------

function randomRGBArray () {
  var r = Math.floor(Math.random() * 256);
  var g = Math.floor(Math.random() * 256);
  var b = Math.floor(Math.random() * 256);
  return [r, g, b];
}


</script>

</body>
</html>