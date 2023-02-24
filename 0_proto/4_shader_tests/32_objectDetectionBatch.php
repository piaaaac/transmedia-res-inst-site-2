<?php
$path  = 'images/cats-test';
$path  = 'images/stable-d-dav-shape';
$path  = 'images/stable-d-ap-shape';
$path  = 'images/language';
$images = glob("$path/*.{jpeg,jpg,gif,png}", GLOB_BRACE);
?>

<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="description" content="">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Batch object detector</title>
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
    }
  </style>
  <script src="libs/p5.min.js"></script>
  <script src="libs/ml5.min.js"></script>
</head>
<body>  
<script>

let font1;
let imgUrls = <?= json_encode($images) ?>;
let imgOri, imgTarget;
let objectDetector;
let log = [];
let modelReady = false;
let imgReadyForDrawing = false;
let modelName, modelOptions;
let currentFilename, currentResults;
// ------------------------
let autoAdvance = true;
let autoAdvanceDelay = 100;
// ------------------------

function mouseReleased () {
  if (!autoAdvance) {
    advance();
  }
}

function advance () {
  // clean
  currentFilename = null;
  currentResults = null;

  // if images finished
  if (imgUrls.length <= 0) {
    console.log("------------------------------");
    console.log("DONE");
    console.log(log);
    console.log("------------------------------");
    noLoop();
    return;
  }

  // load next image
  var url = imgUrls.splice(0, 1)[0];
  var urlPieces = url.split("/");
  currentFilename = urlPieces[urlPieces.length-1];
  console.log(currentFilename);
  loadImage(url, img => {

    // copy new image on an offscreen canvas with canvas width and height
    imgTarget.copy(img, 0, 0, img.width, img.height, 0, 0, width, height);
    
    // detection
    console.log('Detecting') 
    objectDetector.detect(imgTarget, function (err, results) {
      if (err) {
        console.log("Error (2389745298):", err);
      } else {

        currentResults = results;
        results.forEach(res => {
          var date = new Date();
          addDetection(modelName, modelOptions, currentFilename, date.toGMTString(), res.label, res.confidence, res.normalized.x, res.normalized.y, res.normalized.width, res.normalized.height);
          // console.log(Date.now());
          // console.log(date.toLocaleDateString());
          // console.log(date.toDateString());
          // console.log(date.toGMTString());
        });

        console.log(log);
        imgReadyForDrawing = true;
        if (autoAdvance) {
          setTimeout(advance, autoAdvanceDelay)
        }
      }
    });
  });
}

function addDetection (modelName, modelOptions, imageFilename, timestamp, label, confidence, normX, normY, normW, normH) {
  var myDetection = {
    "modelName": modelName,
    "modelOptions": modelOptions,
    "imageFilename": imageFilename,
    "timestamp": timestamp,
    "label": label,
    "confidence": confidence,
    "normX": normX,
    "normY": normY,
    "normW": normW,
    "normH": normH,
  };
  log.push(myDetection);
}

function setup() {
  createCanvas(800, 800);
  imgTarget = createGraphics(width, height);
  
  // v.A
  modelName = 'yolo';
  modelOptions = { filterBoxesThreshold: 0.1, IOUThreshold: 0.8, classProbThreshold: 0.4 };
  objectDetector = ml5.objectDetector(modelName, modelOptions, start);
  
  // v.B
  // modelName = 'cocossd';
  // modelOptions = {}; // don't find if there are options for model cocossd
  // objectDetector = ml5.objectDetector(modelName, modelOptions, start);
  
}

// Change the status when the model loads.
function start () {
  console.log("model Ready!")
  modelReady = true;
  advance();
}


function draw() {
  fill(0, 5); 
  rect(0, 0, width, height);
  image(imgTarget, width*0.9, height*0.9, width*0.1, height*0.1); // miniature
  
  if (imgReadyForDrawing) {
    background(0)
    noStroke();
    noFill();
    image(imgTarget, 0, 0, width, height);
    imgReadyForDrawing = false;
  }

  if (modelReady && currentResults !== null) {
    currentResults.forEach(res => {
      var normX = res.normalized.x;
      var normY = res.normalized.y;
      var normW = res.normalized.width;
      var normH = res.normalized.height;
      var x = normX * width;
      var y = normY * height;
      var w = normW * width;
      var h = normH * height;

      noStroke();
      fill(244, 255, 0);
      text(res.label + " " + nfc(res.confidence * 100.0, 2) + "%", x + 5, y + 15);
      text(nf(normX, 1, 2) +", "+ nf(normY, 1, 2) +": "+ nf(normW, 1, 2) +" x "+ nf(normH, 1, 2), x + 5, y + 35);
      noFill();
      strokeWeight(1);
      stroke(244, 255, 0);
      rect(x, y, w, h);
    });
  }
}







/*
let font1;
let imgUrls = <?= json_encode($images) ?>;

let objectDetector;
let imgOri, imgTarget;
let objects = [];
let status;

function preload () {
  imgOri = loadImage(random(imgUrls));
  font1 = loadFont("fonts/NewEdge6666-LightRounded.otf", 
    () => {console.log("loadFont(): f1 loaded")},
    () => {console.log("loadFont(): f1 error")},
  );
}

function setup() {
  createCanvas(500, 500);
  imgTarget = createGraphics(width, height);
  imgTarget.copy(imgOri, 0, 0, imgOri.width, imgOri.height, 0, 0, width, height);
  objectDetector = ml5.objectDetector('yolo', modelReady);
}

// Change the status when the model loads.
function modelReady() {
  console.log("model Ready!")
  status = true;
  console.log('Detecting') 
  objectDetector.detect(imgTarget, gotResult);
}

function gotResult (err, results) {
  if (err) {
    console.log(err);
  }
  console.log(results)
  objects = results;
}


function draw() {
  // unless the model is loaded, do not draw anything to canvas
  if (status != undefined) {
    image(imgTarget, 0, 0)

    for (let i = 0; i < objects.length; i++) {
      noStroke();
      fill(244, 255, 0);
      text(objects[i].label + " " + nfc(objects[i].confidence * 100.0, 2) + "%", objects[i].x + 5, objects[i].y + 15);
      noFill();
      strokeWeight(1);
      stroke(244, 255, 0);
      rect(objects[i].x, objects[i].y, objects[i].width, objects[i].height);
    }
  }
}
*/
</script>

</body>
</html>




