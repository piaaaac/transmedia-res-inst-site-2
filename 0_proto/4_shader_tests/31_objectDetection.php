<?php
$path  = 'images/cats-test';
$path  = 'images/stable-d-dav-shape';
$path  = 'images/stable-d-ap-shape';
$images = glob("$path/*.{jpeg,jpg,gif,png}", GLOB_BRACE);
?>

<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="description" content="">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>growing type</title>
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
  <script src="libs/p5.min.js"></script>
  <script src="libs/ml5.min.js"></script>
</head>
<body>  
  <div id="p5-sketch"></div>


<script>

let font1;
let imgFiles = <?= json_encode($images) ?>;

let objectDetector;
let imgOri, imgTarget;
let objects = [];
let status;

function preload () {
  imgOri = loadImage(random(imgFiles));
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

</script>

</body>
</html>




