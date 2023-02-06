<?php
// $path  = 'images/midjourney';
// $images = glob("$path/*.{jpeg,jpg,gif,png}", GLOB_BRACE);
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
  <script src="../assets/lib/p5.min.js"></script>
</head>
<body>  
  <div id="p5-sketch"></div>


<script>

let font1, font2, font3, font4, font5;
let fs;
let micel;
// let letters = {};
// let sentence;
// let ms;
// let m1, m2;

var wordTitle, singleLetter;

function preload () {

  var f1 = "fonts/NewEdge6666-LightRounded.otf";
  var f4 = "fonts/SuisseIntlMono-Regular.otf";

  font1 = loadFont(f1, 
    () => {console.log("loadFont(): f1 loaded")},
    () => {console.log("loadFont(): f1 error")},
  );
  font4 = loadFont(f4, 
    () => {console.log("loadFont(): f4 loaded")},
    () => {console.log("loadFont(): f4 error")},
  );
}

function setup () {
  var canvas = createCanvas(windowWidth, windowHeight);
  canvas.parent("p5-sketch");
  rectMode(CENTER);
  textAlign(CENTER, CENTER);
  angleMode(DEGREES);
  background(0, 255, 147);

  fs = 380;
  sentence = new GrowingSentence("TMRI", font1, fs);
  // console.log("sentence", sentence);
  // ms = new MiceliumLine(200, 200, 90);
  // m1 = new MiceliumStrand(width/2, height/2, random(360));
  // m2 = new MiceliumStrand(width/2, height/2, random(360));
  micel = new Miceluim();
}

function draw () {

  // background(0, 255, 147, 15);

  micel.grow();
  micel.grow();
  micel.grow();
  micel.grow();
  micel.grow();

  sentence.show(createVector(mouseX, mouseY));

  // var amt = mouseX / width;
  // sentence.run(amt);
}

function mouseClicked () {
  micel.addStrand(mouseX, mouseY, random(360));
  micel.addStrand(mouseX, mouseY, random(360));
  micel.addStrand(mouseX, mouseY, random(360));
  micel.addStrand(mouseX, mouseY, random(360));
  micel.addStrand(mouseX, mouseY, random(360));
  micel.addStrand(mouseX, mouseY, random(360));
  micel.addStrand(mouseX, mouseY, random(360));

  // micel.addRandomStrand();
  // micel.addRandomStrand();
  // micel.addRandomStrand();
  // micel.addRandomStrand();
  // micel.addRandomStrand();
  // micel.addRandomStrand();
  // micel.addRandomStrand();
}

// ---------------------------------------------------------------------------
// Micelium class
// -> contains MiceliumStrand objects
//    -> contains MiceliumLine objects
// ---------------------------------------------------------------------------

class MiceliumLine {

  constructor (startX, startY, angle) {
    // ----------------
    this.angleVar = 0.3;
    this.speedBase = 3;
    this.speedVariation = 1;
    // ----------------
    this.pos = createVector(startX, startY);
    this.direction = p5.Vector.fromAngle(angle);
  }

  grow () {
    var deltaAngle = (random() - 0.5) * this.angleVar;
    this.direction.setHeading(this.direction.heading() + deltaAngle);
    this.direction.setMag(this.speedBase + (random() - 0.5) * this.speedVariation);
    var from = this.pos.copy();
    this.pos.add(this.direction);
    strokeWeight(0.7);
    line(from.x, from.y, this.pos.x, this.pos.y);
  }

  copy () {
    let clone = new MiceliumLine (this.pos.x, this.pos.y, this.direction.heading());
    return clone;
  }

}

class MiceliumStrand {

  constructor (startX, startY, angle) {
    this.seedPos = createVector(startX, startY);
    this.direction = p5.Vector.fromAngle(angle);
    this.mlines = [
      new MiceliumLine(this.seedPos.x, this.seedPos.y, this.direction.heading()),
    ];
  }

  grow () {
    for (var i = this.mlines.length-1; i >= 0; i--) {
      let mline = this.mlines[i];
      mline.grow();
      if (random() < 0.01) {
        var clone = mline.copy();
        console.log("cloned", clone)
        this.mlines.push(clone);
      }
      if (random() < 0.01) {
        this.mlines.splice(i, 1);
      }
    }
  }
}

class Miceluim {

  constructor () {
    this.mstrands = [];
  }

  grow () {
    for (var i = this.mstrands.length-1; i >= 0; i--) {
      var mstrand = this.mstrands[i];
      mstrand.grow();
    }    
  }

  addStrand (startX, startY, angle) {
    const s = new MiceliumStrand(startX, startY, angle);
    this.mstrands.push(s);
  }

  addRandomStrand () {
    const startX = random(width);
    const startY = random(height);
    const angle = random(360);
    this.addStrand (startX, startY, angle);
  }

}

// ---------------------------------------------------------------------------
// GrowingSentence class
// ---------------------------------------------------------------------------

class GrowingSentence {
  
  constructor (text, font, fontSize) {
    this.pos = createVector(width/2, height/2);
    this.textPoints = font.textToPoints(text, this.pos.x, this.pos.y, fontSize, {sampleFactor: 0.180});
    // this.seedPoints = new Array(100).fill("").map(e => { return {"x": random(width), "y": random(height)}});
    textSize(fontSize);
    textFont(font);
    this.width = textWidth(text);
    console.log(this.width)
  }
  
  show (textPos) {

    // stroke(0, 2);
    // for (var i = 0; i < 100; i++) {
    //   this.seedPoints.forEach(p => {
    //     var from = createVector(p.x, p.y);
    //     var toTextPoint = random(this.textPoints);
    //     var to = createVector(toTextPoint.x - this.pos.x + textPos.x, toTextPoint.y - this.pos.y + textPos.y);
    //     line(from.x, from.y, to.x, to.y);
    //   });
    // }

    push();
    translate(-this.width/2, 0);
    this.textPoints.forEach(p => {
      circle(p.x, p.y, 5, 5);
    });
    pop();

    // this.seedPoints.forEach(p => {
    //   circle(p.x, p.y, 5, 5);
    // });
  }

  update (amt) {

  }
  
  draw () {

  }
}

// ---------------------------------------------------------------------------
// Helper functions
// ---------------------------------------------------------------------------

function easeInOutCubic (x) {
  return x < 0.5 ? 4 * x * x * x : 1 - pow(-2 * x + 2, 3) / 2;
}

function easeInOutQuad (x) {
  return x < 0.5 ? 2 * x * x : 1 - pow(-2 * x + 2, 2) / 2;
}

function easeInOutSine(x) {
  return -(cos(PI * x) - 1) / 2;
}

// ---------------------------------------------------------------------------
// OLD CLASSES
// ---------------------------------------------------------------------------
// MorphLetter class
// ---------------------------------------------------------------------------

function MorphLetter (letterFrom, x, y, size = 200) {

  this.letterToString;
  this.letterFrom = JSON.parse(JSON.stringify(letters[letterFrom]));
  this.letterCurr = JSON.parse(JSON.stringify(letters[letterFrom]));
  this.letterTo = null;
  this.morphing = false;
  this.morphedPoints;
  this.x = x;
  this.y = y;
  this.size = size;
  this.xOriginal = x;
  this.yOriginal = y;
  this.sizeOriginal = size;
  this.xCurr = x;
  this.yCurr = y;
  this.completeness = 0.8;


  this.init = function () {
    this.display();
  }
  
  this.morphStart = function (letterTo) {
    this.letterTo = JSON.parse(JSON.stringify(letters[letterTo]));
    this.letterToString = letterTo;
    this.morphing = true;
    this.morphedPoints = [];
    this.isDone = false;
    
    // Only used by V2
    this.life = 0;
  }
  this.morphStop = function () {
    this.morphing = false;
  }

  this.update = function () {
    if (this.morphing) {
      for (let i = 0; i < this.letterCurr.length; i++) {
        
        // V1 - LINEAR
        // ---------------------------------------------------------------------
        
        var inc = 0.000045; // SENTENCE
        // var inc = 0.00008; // LETTER

        this.life += inc;
        if (this.life <= 1) {
          
          var finalCompleteness = (this.letterToString === "-") ? 1 : this.completeness;
          // var lifeEased = easeInOutQuad(this.life) * finalCompleteness;
          var lifeEased = easeInOutSine(this.life) * finalCompleteness;
          // var lifeEased = (this.life) * finalCompleteness;
          
          var v1 = p.createVector(this.letterFrom[i].x, this.letterFrom[i].y);
          var v2 = p.createVector(this.letterTo[i].x, this.letterTo[i].y);
          var v = p5.Vector.lerp(v1, v2, lifeEased);
          this.letterCurr[i].x = v.x;
          this.letterCurr[i].y = v.y;
        }
        if (this.life >= 1) {
          this.morphStop();
          this.letterFrom = JSON.parse(JSON.stringify(this.letterCurr));;
          this.isDone = true;
          this.completeness = (p.random() > 0.5) ? (p.random(0.7, 0.9)) : (p.random(1.1, 1.2));
          this.completeness = 1;
          // this.completeness = p.random(0.95, 1.05);
          // this.completeness *= map(mouseY, 0, height, 0,1);
        }
        // ---------------------------------------------------------------------
        
        // V2 - PROPORTIONAL
        // ---------------------------------------------------------------------
        // this.letterCurr[i].x += (this.letterTo[i].x - this.letterCurr[i].x) * 0.05;
        // this.letterCurr[i].y += (this.letterTo[i].y - this.letterCurr[i].y) * 0.05;

        // var tolerance = 0.5;
        // if (Math.abs(this.letterCurr[i].x - this.letterTo[i].x) < tolerance && Math.abs(this.letterCurr[i].x === this.letterTo[i].x) < tolerance) {
        //   if (this.morphedPoints.indexOf(i) === -1) {
        //     this.morphedPoints.push(i);
        //     if (this.morphedPoints.length >= this.letterCurr.length) {
        //       this.morphStop();
        //       console.log(this.morphedPoints.length);
        //       this.isDone = true;
        //     }
        //   }
        // }
        // ---------------------------------------------------------------------

      }
    }
  }

  this.display = function () {
    var scale = this.size/120;
    if (this.xCurr != this.x) { this.xCurr += (this.x - this.xCurr) * 0.1; }
    if (this.yCurr != this.y) { this.yCurr += (this.y - this.yCurr) * 0.1; }
    p.push();
    p.translate(this.xCurr, this.yCurr);
    p.beginShape();
    for (let i = 0; i < this.letterCurr.length; i++) {
      p.vertex(this.letterCurr[i].x * scale, this.letterCurr[i].y * scale);
    }
    p.endShape(p.CLOSE);
    p.pop();
  }

  this.setParameters = function (params) {
    if (params.x !== undefined) { this.x = params.x; }
    if (params.y !== undefined) { this.y = params.y; }
    if (params.size !== undefined) { this.size = params.size; }
  }

  this.resetParameters = function () {
    this.x = this.xOriginal;
    this.y = this.yOriginal;
    this.size = this.sizeOriginal;
  }

  // this.isDone = function() {
  //   return this.pos.y > this.maxY;
  // }
}


// ---------------------------------------------------------------------------
// MorphWord class
// ---------------------------------------------------------------------------

function MorphWord (setupLetters, words) {
  
  this.words = words;

  this.startedMorphing = false;
  this.doneMorphingCount = 0;
  this.items = [];
  this.currentWord = -1;

  this.setup = function () {
    var that = this;
    setupLetters.forEach(function (l) {
      that.items.push(new MorphLetter(l.letter, l.left, l.top, l.size));
    });
    this.items.forEach(function (item) {
      item.init();
    });
    
    if (this.words[0].length !== this.items.length) {
      throw new Error("Number of MorphLetters must be equal to length of words");
    }
  }

  this.update = function () {

    // p.erase(0.1, 0.1);
    // p.erase();
    p.fill(255, 10);
    p.rect(0, 0, p.width, p.height);
    // p.noErase();

    var that = this;
    this.items.forEach(function (item) {
      if (!item.isDone) {
        item.update();
        if (item.isDone) {
          that.doneMorphingCount++;
          if (that.doneMorphingCount >= that.items.length) {
            that.morphAll();
          }
        }
      }
    });
  }

  this.display = function () {
    this.items.forEach(function (item) {
      item.display();
    });
  }

  this.morphAll = function () {
    this.currentWord++;
    if (this.currentWord >= this.words.length) {
      this.currentWord = 0;
    }
  
    this.doneMorphingCount = 0;
    var word = this.words[this.currentWord];
    this.items.forEach(function (item, i) {
      item.morphStart(word[i]);
    });
  }
}


</script>

</body>
</html>