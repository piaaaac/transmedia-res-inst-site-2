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
"use strict";

var p5Sketch = new p5(function (p) {

  let font1, font2, font3, font4, font5;
  let fontSize;
  let letters = {};
  let w, ww;

  var wordTitle, singleLetter;

  p.preload = function () {

    var f1 = "fonts/NewEdge6666-LightRounded.otf";
    var f4 = "fonts/SuisseIntlMono-Regular.otf";

    font1 = p.loadFont(f1, 
      () => {console.log("loadFont(): f1 loaded")},
      () => {console.log("loadFont(): f1 error")},
    );
    font4 = p.loadFont(f4, 
      () => {console.log("loadFont(): f4 loaded")},
      () => {console.log("loadFont(): f4 error")},
    );
  }

  p.setup = function () {
    var canvas = p.createCanvas(p.windowWidth, p.windowHeight);
    canvas.parent("p5-sketch");

    p.background(0, 255, 147);

    // p.textFont(font1);
    // p.textSize(fontSize);
    // stroke(255);
    // p.noFill();
    // p.stroke(0);
    // p.fill(0,0,0);
    // p.strokeWeight(0.1);
    p.noFill();
    p.stroke(0, 255, 147);
    p.strokeWeight(0.5);


    // --- NewEdge6666-LightRounded.otf ------------------------------------------
    fontSize = 200;
    letters["D1"] = font1.textToPoints("D", 0, 0, fontSize, {sampleFactor: 0.180});
    letters["E1"] = font1.textToPoints("E", 0, 0, fontSize, {sampleFactor: 0.199});
    letters["G1"] = font1.textToPoints("G", 0, 0, fontSize, {sampleFactor: 0.166});
    letters["I1"] = font1.textToPoints("I", 0, 0, fontSize, {sampleFactor: 0.492});
    letters["N1"] = font1.textToPoints("N", 0, 0, fontSize, {sampleFactor: 0.185});
    letters["O1"] = font1.textToPoints("O", 0, 0, fontSize, {sampleFactor: 0.185});
    letters["R1"] = font1.textToPoints("R", 0, 0, fontSize, {sampleFactor: 0.181});
    letters["S1"] = font1.textToPoints("S", 0, 0, fontSize, {sampleFactor: 0.189});
    letters["W1"] = font1.textToPoints("W", 0, 0, fontSize, {sampleFactor: 0.135});
    // ---------------------------------------------------------------------------

    // --- SuisseIntlMono-Regular.otf --------------------------------------------
    fontSize = 200;
    letters["D4"] = font4.textToPoints("D", 0, 0, fontSize, {sampleFactor: 0.203});
    letters["E4"] = font4.textToPoints("E", 0, 0, fontSize, {sampleFactor: 0.206});
    letters["G4"] = font4.textToPoints("G", 0, 0, fontSize, {sampleFactor: 0.182});
    letters["I4"] = font4.textToPoints("I", 0, 0, fontSize, {sampleFactor: 0.241});
    letters["N4"] = font4.textToPoints("N", 0, 0, fontSize, {sampleFactor: 0.167});
    letters["O4"] = font4.textToPoints("O", 0, 0, fontSize, {sampleFactor: 0.208});
    letters["R4"] = font4.textToPoints("R", 0, 0, fontSize, {sampleFactor: 0.196});
    letters["S4"] = font4.textToPoints("S", 0, 0, fontSize, {sampleFactor: 0.203});
    letters["W4"] = font4.textToPoints("W", 0, 0, fontSize, {sampleFactor: 0.159});
    // ---------------------------------------------------------------------------


    Object.keys(letters).forEach(k => {
      console.log(k, letters[k].length);
    });

    // font 1
    wordTitle = {
      setupLetters: [
        {"letter": "W1", "left": p.width/6*0, "top": p.height/2+p.width/6/2, "size": p.width/6},
        {"letter": "E1", "left": p.width/6*1, "top": p.height/2+p.width/6/2, "size": p.width/6},
        {"letter": "I1", "left": p.width/6*2, "top": p.height/2+p.width/6/2, "size": p.width/6},
        {"letter": "R1", "left": p.width/6*3, "top": p.height/2+p.width/6/2, "size": p.width/6},
        {"letter": "D1", "left": p.width/6*4, "top": p.height/2+p.width/6/2, "size": p.width/6},
        {"letter": "D1", "left": p.width/6*4, "top": p.height/2+p.width/6/2, "size": p.width/6},
      ],
      words: [
        ["G1","N1","O1","S1","I1","S1"],
        ["W1","E1","I1","R1","D1","D1"],
      ],
    };

    // font 4
    // wordTitle = {
    //   setupLetters: [
    //     {"letter": "W4", "left": 100+p.width/7*0, "top": p.height/2+p.width/7/2, "size": p.width/7},
    //     {"letter": "E4", "left": 100+p.width/7*1, "top": p.height/2+p.width/7/2, "size": p.width/7},
    //     {"letter": "I4", "left": 100+p.width/7*2, "top": p.height/2+p.width/7/2, "size": p.width/7},
    //     {"letter": "R4", "left": 100+p.width/7*3, "top": p.height/2+p.width/7/2, "size": p.width/7},
    //     {"letter": "D4", "left": 100+p.width/7*4, "top": p.height/2+p.width/7/2, "size": p.width/7},
    //     {"letter": "D4", "left": 100+p.width/7*5, "top": p.height/2+p.width/7/2, "size": p.width/7},
    //   ],
    //   words: [
    //     ["G4","N4","O4","S4","I4","S4"],
    //     ["W4","W4","E4","I4","R4","D4"],
    //     ["G4","N4","O4","S4","I4","S4"],
    //     ["W4","E4","E4","I4","R4","D4"],
    //     ["G4","N4","O4","S4","I4","S4"],
    //     ["W4","E4","I4","I4","R4","D4"],
    //     ["G4","N4","O4","S4","I4","S4"],
    //     ["W4","E4","I4","R4","R4","D4"],
    //   ],
    // };

    // ------------------------
    // ww = singleLetter;
    ww = wordTitle;
    // ------------------------

    w = new MorphWord(ww.setupLetters, ww.words);
    w.setup();
    w.morphAll(); 
  }

  p.draw = function () {
    // background(200);
    // clear();
    w.update();
    w.display();
  }

  // function randomLetterIndex () {
  //   return Object.keys(letters)[Math.floor(Math.random() * Object.keys(letters).length)];
  // }

  // p.getWord = function (name) {
  //   var words = {
  //     wordHome: {
  //       setupLetters: [
  //                                                   // p.height-60
  //         {"letter": "E", "left": p.width/10*0, "top": p.height/2+p.width/11/2, "size": p.width/11},
  //         {"letter": "M", "left": p.width/10*1, "top": p.height/2+p.width/11/2, "size": p.width/11},
  //         {"letter": "B", "left": p.width/10*2, "top": p.height/2+p.width/11/2, "size": p.width/11},
  //         {"letter": "R", "left": p.width/10*3, "top": p.height/2+p.width/11/2, "size": p.width/11},
  //         {"letter": "A", "left": p.width/10*4, "top": p.height/2+p.width/11/2, "size": p.width/11},
  //         {"letter": "C", "left": p.width/10*5, "top": p.height/2+p.width/11/2, "size": p.width/11},
  //         {"letter": "E", "left": p.width/10*6, "top": p.height/2+p.width/11/2, "size": p.width/11},
  //         {"letter": "-", "left": p.width/10*7, "top": p.height/2+p.width/11/2, "size": p.width/11},
  //         {"letter": "-", "left": p.width/10*8, "top": p.height/2+p.width/11/2, "size": p.width/11},
  //         {"letter": "-", "left": p.width/10*9, "top": p.height/2+p.width/11/2, "size": p.width/11},
  //       ],
  //       words: [
  //         ["C","O","M","P","L","E","X","I","T","Y"],
  //         ["-","-","E","M","B","R","A","C","E","-"],
  //         ["C","O","M","P","L","E","X","I","T","Y"],
  //         ["-","-","-","E","M","B","R","A","C","E"],
  //         ["C","O","M","P","L","E","X","I","T","Y"],
  //         ["E","M","B","R","A","C","E","-","-","-"],
  //       ],
  //     },
  //     singleLetter: {
  //       setupLetters: [
  //         {"letter": "E", "left": p.width*0.66 - p.height*0.68/2, "top": p.height*0.44 + p.height*0.68/2, "size": p.height*0.508},
  //       ],
  //       words: [
  //         // ["-"],["Q"],["E"],["6"],["T"],["9"],["C"],["P"],["5"],["O"],["S"],["8"],["0"],["2"],["U"],["3"],["4"],["R"],["7"],["F"],["G"],["I"],["1"],["J"],["K"],["D"],["L"],["M"],["A"],["N"],["V"],["W"],["X"],["Y"],["H"],["Z"],["B"],
  //         // ["B"],["R"],["E"],["W"],["I"],["N"],["G"],["F"],["U"],["N"],["K"],["I"],["N"],["M"],["Y"],["S"],["O"],["U"],["L"],["K"],["I"],["T"],["C"],["H"],["E"],["N"],
  //         ["M"], ["B"], ["R"], ["A"], ["C"], ["E"], ["C"], ["O"], ["M"], ["P"], ["L"], ["E"], ["X"], ["I"], ["T"], ["Y"], ["E"], 
  //       ],
  //     },
  //   };
  //   return words[name];
  // }

  p.morphiesSmall = function () {
    p.clear();
    w.items.forEach(function (item) {
      // item.setParameters({y: p.height + p.width/22 - 35});
      item.setParameters({y: p.height + p.width/16 - 35});
    });
  }
  p.morphiesLarge = function () {
    p.clear();
    w.items.forEach(function (item) {
      item.resetParameters();
    });
  }

  // function mousePressed () { 
  //   if (!w.startedMorphing) {
  //     w.startedMorphing = true;
  //     w.morphAll(); 
  //   }
  // }

  // p.windowResized = function () {
  //   p.resizeCanvas(p.windowWidth, p.windowHeight);
  //   p.clear();
  //   w = null;
  //   ww = (window.state.responsiveVersion === "desktop") ? p.getWord("wordHome") : p.getWord("singleLetter");
  //   w = new MorphWord(ww.setupLetters, ww.words);
  //   w.setup();
  //   w.morphAll(); 
  // }



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



  function easeInOutCubic (x) {
    return x < 0.5 ? 4 * x * x * x : 1 - p.pow(-2 * x + 2, 3) / 2;
  }

  function easeInOutQuad (x) {
    return x < 0.5 ? 2 * x * x : 1 - p.pow(-2 * x + 2, 2) / 2;
  }

  function easeInOutSine(x) {
    return -(p.cos(p.PI * x) - 1) / 2;
  }
});

</script>

</body>
</html>