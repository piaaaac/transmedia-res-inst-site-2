<!-- ref https://p5js.org/reference/#/p5/createShader -->
<!-- ref https://p5js.org/examples/3d-shader-as-a-texture.html -->

<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="description" content="">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Untitled</title>
  <style type="text/css">body {margin: 0;}</style>
  <script src="../assets/lib/p5.min.js"></script>
</head>
<body>  


<script id="vertex-shader" type="x-shader/x-vertex">
attribute vec3 aPosition;
attribute vec2 aTexCoord;
varying vec2 vTexCoord;

void main () {
  // copy the coordinates
  vTexCoord = aTexCoord;

  vec4 posVec4 = vec4(aPosition, 1.0);
  //posVec4.xy = posVec4.xy * 2.0 - 1.0;
  gl_Position = posVec4;
}
</script>


<script id="fragment-shader" type="notjx-shader/x-fragments">
#ifdef GL_ES
precision mediump float;
#endif

varying vec2 vTexCoord;
uniform sampler2D texture1;
uniform sampler2D texture2;
uniform float noise;
uniform float random;
uniform vec2 mouse; // 0-1

void main() {
  vec2 uv = vTexCoord;
  uv.y = 1.0 - uv.y;

  vec4 c = vec4(0.0, 0.0, 0.0, 1.0);

  float threshold = mouse.y * 2.0;
  float r = texture2D(texture1, uv).r + texture2D(texture2, uv).r;
  float g = texture2D(texture1, uv).g + texture2D(texture2, uv).g;
  float b = texture2D(texture1, uv).b + texture2D(texture2, uv).b;
  float check = (random < 0.33) ? r : (random < 0.66) ? g : b;
  c = (check > threshold)
    ? texture2D(texture1, uv)
    // : vec4(1.0,1.0,1.0,0.0);
    : texture2D(texture2, uv);

  gl_FragColor = c;
}
</script>

<script>

let myShader;
let vid1;
let vid2;
let lastFrame;
let stillLoading = 2;

function setup () {
  createCanvas(window.innerWidth, window.innerHeight, WEBGL);
  imageMode(CENTER);
  noStroke();
  vid1 = createVideo(["videos/ducks.mp4", "videos/ducks.ogv"], () => vidLoad(vid1));
  vid2 = createVideo(["videos/IMG_2679-low.mp4", "videos/IMG_2679-low.ogv"], () => vidLoad(vid2));
  // vid2 = createVideo(["videos/IMG_5293.mp4", "videos/IMG_5293.ogv"], () => vidLoad(vid2));
  lastFrame = createGraphics(window.innerWidth, window.innerHeight);
  lastFrame.noStroke();

  // load the shader
  let vertexShaderSource = document.querySelector("#vertex-shader").text;
  let fragmentShaderSource = document.querySelector("#fragment-shader").text;
  myShader = createShader(vertexShaderSource, fragmentShaderSource);
}

function vidLoad (vid) {
  vid.loop();
  vid.hide();
  vid.volume(0);
  stillLoading--;
}

function draw() {
  background(255,0,255);
  if (stillLoading > 0) { return; }
  var size = 0.7;
  // size *= (1 + n*0.01);

  // feedback
  if (lastFrame) {
    var n = noise(frameCount/100);
    var n1ish = 1; // 1 + (n - 0.5) * 0.6;
    var grow = 1.005 + n*0.01;
    push();
    rotate((n-0.5)*0.015);
    image(lastFrame, 0, 0, (width*grow), (height*grow));
    pop();
    // debugger
  }

  push();
  shader(myShader);
  rotate((n-0.5)*0.015);
  // vid
  myShader.setUniform("texture1", vid1);
  myShader.setUniform("texture2", vid2);
  myShader.setUniform("random", random());
  myShader.setUniform("noise", noise(frameCount/100));
  myShader.setUniform("mouse", [mouseX/width, mouseY/height]);
  quad(-size*n1ish, -size*n1ish, size*n1ish, -size*n1ish, size*n1ish, size*n1ish, -size*n1ish, size*n1ish);
  resetShader();
  pop();

  lastFrame = get();
  // lastFrame.loadPixels();
  // console.log(lastFrame.pixels);
  // lastFrame.updatePixels();
}

</script>
</body>
</html>