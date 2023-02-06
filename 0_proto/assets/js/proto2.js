var img;
var sorted;

function preload(){
  img = loadImage('assets/images/DALL-E-2022-12-01-11.12.06.png');
}

function setup() {
  createCanvas(windowWidth, windowHeight);

  // sorted = img.get();
  // sorted.loadPixels();
  // for (var i = 0; i < 10000; i++) {
  //   var record = -1;
  //   var selectedPixel = i;
  //   for (var j = i; j < 10000; j++) {
  //     var pix = sorted.pixels[j];
  //     // Sort by hue
  //     var b = hue(pix);
  //     if (b > record) {
  //       selectedPixel = j;
  //       record = b;
  //       console.log(b)
  //     }
  //   }

  //   // Swap selectedPixel with i
  //   var temp = sorted.pixels[i];
  //   sorted.pixels[i] = sorted.pixels[selectedPixel];
  //   sorted.pixels[selectedPixel] = temp;
  // }
  
  // sorted.updatePixels();


  for (var y = 0; y < img.height; y++) {
    var row = img.get(y, 0, y, img.width);
    console.log(row);
  }
}

function draw() {
  
  background(220);
  //image(img,0,0,500,400);
  image(sorted,0, 0);
  
}
  

 


