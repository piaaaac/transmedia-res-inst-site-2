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
    body {
      margin: 0; background: white; font-size: 0;
    }

/*     * {cursor: none;} */
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
let f;

function preload () {
  spinner = createSpan("loading");
  spinner.style("color", "red");
  spinner.position(windowWidth/2, windowHeight/2);
  blob = new Organism();
  partsNum = floor(random(1, 12));
  f = loadFont("fonts/NeueMontreal-Regular.otf");
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
}

function draw() {
  background(0)

  blob.run();

  if (frameCount % 100 === 0) {
    console.log("Limbs:", blob.limbs.length);
    console.log("States:", blob.limbs.map(e => e.state).join(", "))
  }
  let fps = frameRate();
  textSize(15);
  fill(255);
  stroke(0);
  text(fps.toFixed(2), -width/2 + 30, -height/2+50);

}

function mouseReleased () {
  blob.evolve();
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
    this.size = min(window.innerWidth, window.innerHeight) * 0.8;
  }

  addLimb (img, position, limbOptions) {
    var l = new Limb(img, this.size, limbOptions);
    let i = position === "random"
      ? floor(random() * this.limbs.length)
      : position;
    this.limbs.splice(i, 0, l);
    return this.limbs[i];
  }

  evolve () {
    if (imgFiles.length === 0) {
      imgFiles = imgFilesAll.map(el => el);
    }
    
    let img = loadImage(imgFiles.splice(floor(random() * imgFiles.length), 1));
    this.addLimb(img);
    // let i = floor(random() * this.limbs.length);
    let i = floor(random(this.limbs.length-2, this.limbs.length-4));
    this.limbs[i].kill();

    // Optimized to not overlap dying and growing
    // let that = this;
    // let indexNew = floor(random() * this.limbs.length);
    // let img = loadImage(imgFiles.splice(floor(random() * imgFiles.length), 1));
    // let l = this.addLimb(img, indexNew, {
    //   "onGrowEnd": function () {
    //     let indexKill = indexNew;
    //     while (indexKill === indexNew) {
    //       indexKill = floor(random(that.limbs.length-2, that.limbs.length-4));
    //     }
    //     that.limbs[indexKill].kill();
    //   }
    // });
  }

  run () {
    for (let i = this.limbs.length - 1; i >= 0; i--) { // remove dead limbs
      if (this.limbs[i].isDead()) {
        this.limbs.splice(i, 1);
      }      
    }
    this.limbs.forEach(l => {
      l.update();
      l.display();
    });
  }
}

// Limb class

class Limb {
  constructor (img, size, limbOptions) {
    var defaults = { "state": 0, "onGrowEnd": null };
    var options = Object.assign({}, defaults, limbOptions);


    this.img = img;
    this.size = size;
    this.state = options.state; // [0=growing - 1=normal - 2=dying]
    this.onGrowEnd = options.onGrowEnd;

    this.life = 0;
    this.lifeMax = Infinity;
    this.lifeInc = 0.001;
    this.noisePosition = floor(random(3))/20;
    this.noiseInc = 0.001;
    this.rotation = random(365);

    this.transitionAmt = 0.15;
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
    this.noisePosition += this.noiseInc;

    if (this.state === 0 && this.life > this.transitionAmt) { // grow end
      this.state = 1;
      if (this.onGrowEnd !== null) {
        this.onGrowEnd();
      }
    }
    // state = 2 is set from the top - evolve() 
  }

  display () {
    let sizeDisplay = this.size + noise(this.noisePosition) * this.size * 0.3;
    
    push();
    rotate(this.rotation);
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


/**
 * Class for each piece
 * a random one gets in
 * and a random one gets out
 */

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

</script>
</body>
</html>