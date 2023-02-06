<?php
$path  = 'images/midjourney';
$path  = 'images/abuse';
$path  = 'images/ai-cutouts';
$images = glob("$path/*.{jpeg,jpg,gif,png}", GLOB_BRACE);
?>

<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="description" content="">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Untitled</title>
  <style type="text/css">
    @font-face {
      font-family: 's-i-m';
      src: url(fonts/SuisseIntlMono-Light.otf) format('opentype');
      font-weight: 300;
    }
    body {
      margin: 0; background: white; font-size: 0; 
      font-family: "s-i-m"; font-weight: 300;
    }

  </style>
  <script src="../assets/lib/p5.min.js"></script>
</head>
<body>  

<script>

let imgPath = "Set via php on top of file";
let imgFilesAll = <?= json_encode($images) ?>;
let imgFiles = <?= json_encode($images) ?>;
let partsNum;
let spinner;
let blob;
let sliders;
let f;
let sliderMemory = "";

function preload () {
  spinner = createSpan("loading");
  spinner.style("color", "red");
  spinner.position(windowWidth/2, windowHeight/2);
  blob = new Organism();
  // partsNum = floor(random(1, 12));
  partsNum = 0;
  f = loadFont("fonts/SuisseIntlMono-Light.otf");
  for (let i= 0; i < partsNum; i++) {
    let img = loadImage(imgFiles.splice(floor(random() * imgFiles.length), 1));
    blob.addLimb(img, "random", {"state": 1});
  }
}

function setup () {
  spinner.remove();
  var canvas = createCanvas(window.innerWidth, window.innerHeight, WEBGL);
  imageMode(CENTER)
  angleMode(DEGREES)
  textFont(f)
  sliders = createSliders({
    "size": { x: 20, y: 45, w: 120, text: "size", min: 1, max: 5, startVal: 1.3, step: 0.001},
    "nervous": { x: 20, y: 65, w: 120, text: "nervous", min: 1, max: 5, startVal: 1, step: 0.001},
    "wiggle": { x: 20, y: 85, w: 120, text: "wiggle", min: 0, max: 15, startVal: 0, step: 0.001},
    "mouse": { x: 20, y: 105, w: 120, text: "impersonate", min: 0, max: 1, startVal: 0, step: 1},
  });
  blob.addRandomLimb();
  setTimeout(() => {blob.addRandomLimb();}, 1000);
  setTimeout(() => {blob.addRandomLimb();}, 2500);
}

function draw() {
  background(0)

  blob.run();

  // Sliders
  let values = Object.keys(sliders).map(key => (key +"="+ sliders[key].value())).join("---");
  if (values !== sliderMemory) {
    blob.sizeMultiplier = sliders.size.value();
    blob.evolutionSpeed = map(sliders.nervous.value(), 1, 5, 1, 2);
    blob.setLimbsOptions({
      "breathAmplitude":  map(sliders.nervous.value(), 1, 5, 0.3, 0.6),
      "breathSpeed":      map(sliders.nervous.value(), 1, 5, 0.001, 0.003),
    });
    sliderMemory = values;
  }

  // Log
  if (frameCount % 100 === 0) {
    console.log("Limbs:", blob.limbs.length);
    console.log("States:", blob.limbs.map(e => e.state).join(", "))
  }
  let fps = frameRate();
  textSize(15);
  fill(255);
  stroke(0);
  text(fps.toFixed(0 /*2*/) + "fps ("+ blob.limbs.length +")", -width/2 + 25, -height/2+30);

}

function mouseReleased () {
  if (mouseX > width * 0.8) {
    blob.evolve();
  }
}

// ---------------------------------
// 3d texts
// ---------------------------------

// class CodeCloud {
//   constructor () {
//     this.snippets = [];
//   }

//   addSnippet (text) {
//     let pos = createVector()
//     let s = new Snippet(text, pos);
//     this.snippets.push(s);
//   }
// }

// // Snippet class

// class Snippet {
//   constructor (text) {
//     this.text = text;
//     this.position = createVector()
//   }
// }


// ---------------------------------
// Organism 
// ---------------------------------

class Organism {
  constructor () {
    this.limbs = [];
    this.limbsTargetNum = 12;
    this.size = min(window.innerWidth, window.innerHeight) * 0.6;

    this.sizeMultiplier = 1;
    this.evolutionSpeed = 1;
    this.breathAmplitude = 0.3;
    this.breathSpeed = 0.001;
  }

  addLimb (img, position = "last", limbOptions) {
    var limbOptEdit = Object.assign({}, limbOptions, {
      "breathAmplitude": this.breathAmplitude,
      "breathSpeed": this.breathSpeed,
    });

    var l = new Limb(img, this.size, limbOptEdit);
    if (position === "last") {
      this.limbs.push(l);
    } else {
      let i = position === "random"
        ? floor(random() * this.limbs.length)
        : position;
      this.limbs.splice(i, 0, l);
    }
  }

  setLimbsOptions (limbOptions) {
    this.limbs.forEach((l, i) => {
      Object.keys(limbOptions).forEach(key => {
        l[key] = limbOptions[key];
      });
    });
  }

  addRandomLimb () {
    if (imgFiles.length === 0) {
      imgFiles = imgFilesAll.map(el => el);
    }
    let img = loadImage(imgFiles.splice(floor(random() * imgFiles.length), 1));
    this.addLimb(img);
  }

  evolve () {
    this.addRandomLimb();
    // let i = floor(random(this.limbs.length-2, this.limbs.length-4));
    // this.limbs[i].kill();
    this.limbs[0].kill();
  }

  run () {
    if (random() < (0.008 * this.evolutionSpeed) && this.limbs.length < this.limbsTargetNum) {
      this.addRandomLimb();
    }
    if (random() < (0.008 * this.evolutionSpeed) && this.limbs.length > this.limbsTargetNum - 3) {
      this.limbs[0].kill();
    }

    // housekeeping
    for (let i = this.limbs.length - 1; i >= 0; i--) { // remove dead limbs
      if (this.limbs[i].isDead()) {
        this.limbs.splice(i, 1);
      }      
    }
    // display
    this.limbs.forEach(l => {
      l.sizeMultiplier = this.sizeMultiplier;
      l.update();
      l.display();
    });
  }
}

// Limb class

class Limb {
  constructor (img, size, limbOptions) {
    var defaults = { 
      "state": 0,
      "onGrowEnd": null, 
      "breathAmplitude": 0.3,
      "breathSpeed": 0.001,
    };
    var options = Object.assign({}, defaults, limbOptions);

    this.img = img;
    this.size = size;
    this.sizeMultiplier = 1;
    this.state = options.state; // [0=growing - 1=normal - 2=dying]
    this.onGrowEnd = options.onGrowEnd;

    this.life = 0;
    this.lifeMax = Infinity;
    this.lifeInc = 0.001;
    this.noisePosition = floor(random(5));
    this.rotation = random(365);
    this.transitionAmt = 0.12;  // speed of transition in/out

    this.breathAmplitude = 0.3;
    this.breathSpeed = 0.001;
  }

  kill () {
    this.state = 2;
    this.lifeMax = this.life + this.transitionAmt;
  }

  isDead () {
    return this.life > this.lifeMax;
  }

  update () {
    this.life += this.lifeInc;
    this.noisePosition += this.breathSpeed;

    if (this.state === 0 && this.life > this.transitionAmt) { // grow end
      this.state = 1;
      if (this.onGrowEnd !== null) {
        this.onGrowEnd();
      }
    }
    // state = 2 is set from the top - evolve() 
  }

  display () {
    let sizeDisplay = (this.size + ((noise(this.noisePosition)-0.5)*2) * this.size * this.breathAmplitude) * this.sizeMultiplier;
    
    push();
    rotate(this.rotation + noise(this.noisePosition) * 8);

    // Slider otions scale / translate
    let n1 = noise(this.noisePosition) * sliders.wiggle.value();
    let n2 = noise(this.noisePosition + 3) * sliders.wiggle.value();
    translate(n1 * this.size/10 * this.sizeMultiplier, n2 * this.size/10 * this.sizeMultiplier, 0); // wiggle
    if (sliders.mouse.value()) {
      translate(mouseY-height/2, mouseX-width/2, 0); // explode
      scale(mouseY/height/2, mouseX/width/2, 0); // explode
    }

    if (this.state === 1) {
      image(this.img, 0,0, sizeDisplay, sizeDisplay);
    } else {
      var threshold = (this.state === 0)
        ? map(this.life, 0, this.transitionAmt, 0, 765) // 0 - growing
        : map(this.life, this.lifeMax - this.transitionAmt, this.lifeMax, 765, 0) // 2 - dying
      var partPixels = this.img.get(0,0, this.img.width, this.img.height);
      
      // partPixels.resize(400, 400);
      var rnd = random(10, 400);
      partPixels.resize(rnd, rnd);
      
      partPixels.loadPixels();
      for (var i = 0; i < partPixels.pixels.length; i+=4) {
        var pr = partPixels.pixels[i + 0];
        var pg = partPixels.pixels[i + 1];
        var pb = partPixels.pixels[i + 2];
        if (pr + pg + pb > threshold) {
          partPixels.pixels[i + 3] = 0;
        }
      }
      partPixels.updatePixels();
      image(partPixels, 0,0, sizeDisplay, sizeDisplay);
    }
    pop();

  }
}

// ---------------------------------
// Utilities
// ---------------------------------

// use:
// val = sliders.blend.value();
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
    span.style("color", color(200));
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
    span.style("font-size", "15px");
    // span.style("font-family", "Arial, sans-serif");
  });
  return sliders;
}

</script>
</body>
</html>