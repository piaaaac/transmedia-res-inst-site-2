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
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous"></head>
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
    #p5-cont {
      position: fixed;
      top: 0; left: 0; right: 0; bottom: 0;
      background: black;
    }
    #contents {
      margin-top: 120vh;
      color: #eee;
      font-family: "s-i-m"; font-weight: 300; font-size: 2.8vw; line-height: 1.05em;
      background: rgb(0,0,0);
      background: linear-gradient(180deg, rgba(0,0,0,0) 0%, rgba(0,0,0,0.9) 100%);
      position: relative;
    }

  </style>
  <script src="../assets/lib/p5.min.js"></script>
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
let canvas;

let scrollAnimationVal = 0;

function preload () {
  spinner = createSpan("loading");
  spinner.style("color", "red");
  spinner.position(windowWidth/2, windowHeight/2);
  blob = new Organism();
  // partsNum = floor(random(1, 12));
  partsNum = 6;
  f = loadFont("fonts/SuisseIntlMono-Light.otf");
  for (let i= 0; i < partsNum; i++) {
    let img = loadImage(imgFiles.splice(floor(random() * imgFiles.length), 1));
    blob.addLimb(img, "random", {"state": 1});
  }
}

function setup () {
  spinner.remove();
  canvas = createCanvas(window.innerWidth, window.innerHeight, WEBGL);
  canvas.parent("p5-cont");
  imageMode(CENTER)
  angleMode(DEGREES)
  textFont(f)
  sliders = createSliders({
    "size": { x: 20, y: 45, w: 120, text: "size", min: 1, max: 5, startVal: 1.0, step: 0.001},
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

    // scroll controlled

    // translate(mouseY-height/2, mouseX-width/2, 0); // explode
    // scale(mouseY/height/2, mouseX/width/2, 0); // explode
    var ss = scrollAnimationVal;
    translate(ss/2, ss/3, 0); // explode
    // scale(mouseY/height/2, mouseX/width/2, 0); // explode

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
    s.position(sd.x, sd.y, "fixed");
    s.size(sd.w);
    s.id(key);
    sliders[key] = s;
    let span = createSpan();
    span.id("slider-text-" + key);
    span.style("color", color(200));
    span.addClass("slider-text");
    span.attribute("data-text", sd.text);
    span.attribute("data-id", key);
    span.position(sd.x + s.width + 20, sd.y, "fixed");
    span.html(sd.text + " = " + sd.startVal);
    s.input((ev) => {
      var el = ev.target;
      var id = el.id;
      var s = select("#" + id);
      var span = select("#slider-text-" + id);
      span.html(span.attribute("data-text") + " = " + s.value());
    });
    span.style("font-size", "15px");
    // span.style("font-family", "s-i-l, sans-serif");
  });
  return sliders;
}


// ---------------------------------
// Web page, dom etc
// ---------------------------------

window.scrollTo(0, 0);

document.addEventListener("scroll", (event) => {
  var v = window.scrollY / (window.innerHeight * 1.2);
  // console.log(v);

  document.getElementById("wiggle").value = v*6;
  document.getElementById("size").value = 1.2+v*1.8;
  
  canvas.style.opacity = 1 - v/1.2;
  scrollAnimationVal = v;
  console.log(scrollAnimationX, scrollAnimationY)
});

</script>

<div id="p5-cont"></div>

<section id="contents">
  <div class="container">
    <div class="row">
      <div class="col-12">
        <h1 style="font-size: 3.7em; line-height: 0.95em;">SUMMER SCHOOL<br />OF BITS AND ATOMS</h1>
      </div>
      <div class="col-12 spacer" style="height:50vh;"></div>
      <div class="col-12">
        <p>Cosmic ocean concept of the number one something incredible is waiting to be known as a patch of light decipherment network of wormholes. Take root and flourish hydrogen atoms permanence of the stars from which we spring a still more glorious dawn awaits ship of the imagination. Invent the universe hundreds of thousands take root and flourish invent the universe bits of moving fluff hundreds of thousands. Vastness is bearable only through love finite but unbounded courage of our questions courage of our questions a mote of dust suspended in a sunbeam how far away?</p>
      </div>
    </div>
  </div>
</section>

</body>
</html>