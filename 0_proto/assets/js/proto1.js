// starting point: https://www.youtube.com/watch?v=OAcXnzRNiCY

// --------------------------------------------------------------
// Vars
// --------------------------------------------------------------

var mode = 1;
var attractor;
var particle;
var ps;
var img, pgMap, pgMapPixels;

function preload () {
  img = loadImage('assets/images/DALL-E-2022-12-01-11.12.06.png');
}

function setup () {
  createCanvas(windowWidth, windowHeight);
  background(51);
  textSize(10);
  // frameRate(120)
  attractor = createVector(width/2, height/2);
  ps = new ParticleSystem();
  ps.addParticle(200, 100);
  pgMap = createGraphics(width, height);
  img.filter(GRAY);
  img.filter(POSTERIZE, 2);
  pgMap.tint(255, 1);
  pgMap.image(img, 0, 0);
  pgMap.loadPixels();
  pgMapPixels = pgMap.pixels;
  pgMap.updatePixels();
}

function draw () {
  background(51, 120);
  image(pgMap, 0, 0);

  stroke(255);
  strokeWeight(4);
  point(attractor.x, attractor.y);
  
  ps.run();
  displayFrameRate();
  displayParticleNum();

  if (mode === 3) {
    attractor.x = mouseX;
    attractor.y = mouseY;
  }

}

function displayFrameRate () {
  textAlign(LEFT, BOTTOM);
  var notRed = map(frameRate(), 30, 55, 0, 255);
  notRed = constrain(notRed, 0, 255);
  fill(255, notRed, notRed, 200);
  noStroke();
  text(floor(frameRate()), 5, 17);
}

function displayParticleNum () {
  textAlign(LEFT, BOTTOM);
  fill(255, 200);
  noStroke();
  text(ps.particles.length, 5, 32);
}


// --------------------------------------------------------------
// Events
// --------------------------------------------------------------

function keyPressed () {
  var multiplier = 20;

  console.log(keyCode);
  if (keyCode === 187 /* + */) { 
    for (var i = 0; i < multiplier; i++) {
      ps.addParticle(); 
    }
  }
  if (keyCode === 189 /* - */) { 
    for (var i = 0; i < multiplier; i++) {
      ps.removeParticle(); 
    }
  }
  if (keyCode === 49  /* 1 */) { mode = 1; }
  if (keyCode === 50  /* 2 */) { mode = 2; }
  if (keyCode === 51  /* 3 */) { mode = 3; }
}

// --------------------------------------------------------------
// Class :: Particle
// --------------------------------------------------------------

function Particle (x, y) {
  if (x & y) {
    this.pos = createVector(x, y);
  } else {
    this.pos = createVector(random(width), random(height));
  }
  this.vel = p5.Vector.random2D();
  this.acc = createVector(0, 0);
  this.life = 0;
  this.baseColor = color(255, 255);
  this.color = color(this.baseColor);

  this.attracted = function (target) {
    var force = p5.Vector.sub(target, this.pos);
    var distanceSquared = force.magSq();
    distanceSquared = constrain(distanceSquared, 25, 500);
    var G = 50;
    var strength = G / distanceSquared;
    force.setMag(strength);
    // this.acc.add(force);
    this.acc = force;
  }

  this.towards = function (target) {
    var dir = p5.Vector.sub(target, this.pos);
    dir.setMag(2);
    this.pos.add(dir);
  }

  this.wander = function () {
    let degrees = map(noise(this.life, frameCount/100), 0, 1, 0, 360);
    this.noiseDisturbance = p5.Vector.fromAngle(radians(degrees), 0.8);
    this.pos.add(this.noiseDisturbance);
  }

  this.interactWithPgMap = function () {
    // var size = 3;
    // var blacks = 0;
    // for (var y = floor(this.pos.y)-size/2; y < floor(this.pos.y)+size/2; y++) {
    //   for (var x = floor(this.pos.x)-size/2; x < floor(this.pos.x)+size/2; x++) {
    //     var index = (x + y * pgMap.width)*4;
        
    //     // pixel threshold
    //     // pixels[index+0] = R; [index+1] = G; [index+2] = B; [index+3] = A;

    //     if (pgMapPixels[index+0] < 128) { blacks++; }
    //   }
    // }
    // if(frameCount<100)console.log("pgMapPixels", pgMapPixels);
    // console.log("blacks", blacks);
    // var max = size * size;
    // var speedNorm = map(blacks, 0, max, 1, 10);
    // this.vel.setMag(speedNorm);

    var x = floor(this.pos.x);
    var y = floor(this.pos.y);
    var index = (x + y * pgMap.width) * 4 * pixelDensity();
    var c = pgMapPixels[index];
    console.log(c);
    // this.color = 


    // var areaScore;
  }

  this.update = function () {
    this.life += 0.01;
    this.pos.add(this.vel);
    this.vel.add(this.acc);
  }

  this.show = function () {
    stroke(this.color);
    strokeWeight(2);
    point(this.pos.x, this.pos.y);
  }

}

// --------------------------------------------------------------
// Class :: ParticleSystem
// --------------------------------------------------------------

function ParticleSystem () {
  this.particles = [];

  this.run = function () {
    this.particles.forEach (p => {
    
      // p.attracted(attractor);
      
      if (mode === 1 || mode === 3) {
        p.towards(attractor);
        p.wander();
      }

      if (mode === 2) {
        // img.loadPixels();
        // var px = img.pixels;
        // img.updatePixels();
        // p.interactWithImg(px);
        p.interactWithPgMap();
        // p.wander();
      }

      p.update();
      p.show();
    });
  }

  this.addParticle = function (x, y) {
    var p;
    if (x & y) {
      p = new Particle(x, y);
    } else {
      p = new Particle();
    }
    this.particles.push(p);
  }

  this.removeParticle = function () {
    var index = floor(random(this.particles.length));
    this.particles.splice(index, 1);
  }
}









