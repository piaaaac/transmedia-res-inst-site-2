<?php
$path1  = 'images/midjourney';
$path2  = 'images/abuse';
$path3  = 'images/ai-cutouts';
$path4  = 'images/fleshy';
$path5  = 'images/ai-other';
$path6  = 'images/textures-davide';
// Single path
$images = glob("$path3/*.{jpeg,jpg,gif,png}", GLOB_BRACE);

// Multiple paths
// if (rand(0, 100) < 50) {
//   $images = glob("{"."$path1/*.,$path2/*.}{jpeg,jpg,gif,png}", GLOB_BRACE);
// } else {
//   $images = glob("{"."$path3/*.,$path4/*.}{jpeg,jpg,gif,png}", GLOB_BRACE);
// }
?>

<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="description" content="">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Untitled</title>
  <style type="text/css">
    body {margin: 0; background: white; font-size: 0;}
     * {cursor: none;} 
  </style>
  <script src="../assets/lib/p5.min.js"></script>
</head>
<body>  

<script>

let imgPath = "Set via php on top of file";
let imgFiles = <?= json_encode($images) ?>;
let parts = [];
let partsNum;
let textureImg;
let imgTypography;
let dim;
let camera;
let myModel;
let currX = 0; 
let currY = 0;
let f;

function preload () {
  var options = [
    // 2,
    // 3, 4, 5, 6,
    9,
  ];
  partsNum = random(options);
  for (let i = 0; i < partsNum; i++) {
    img = loadImage(imgFiles.splice(floor(random() * imgFiles.length), 1));
    parts.push(img);
  }
  // myModel = loadModel('models/postojna-cave-postojnska-jama-simplier-0.05.obj', true);
  // myModel = loadModel('models/cave-malachite.obj', true);
  myModel = loadModel('models/cave-malachite-decimate0.2.obj', true);
  // myModel = loadModel('models/cave-malachite-decimate0.05.obj', true);
  // myModel = loadModel('models/mario.obj', true);
  // f = loadFont("fonts/SuisseIntlMono-Light.otf");
  f = loadFont("fonts/SuisseIntlMono-Regular.otf");
}

function setup () {
  var canvas = createCanvas(window.innerWidth, window.innerHeight, WEBGL);
  imageMode(CENTER)
  angleMode(DEGREES)
  noStroke()

  dim = min(width, height) * 0.5;
  textureImg = createGraphics(width*2, width*2)
  imgTypography = createGraphics(width, height);
  imgTypography.textFont(f)

  camera = createCamera();
  // ortho(-width / 2, width / 2, height / 2, -height / 2, -dim*3, dim*3);
  var n = 3000
  perspective(PI / 3.0 * n, width / height, 0.1, 500);

}

function draw() {

  imgTypography.clear();
  writeTexts();
  parts.pop();
  parts.push(imgTypography);

  textureImg.clear();
  parts.forEach(function (part, i) {
    var size = textureImg.width * 0.7 + noise(millis()/10000 + i) * textureImg.width * 0.3;
    textureImg.image(part, 0,0, size, size);
  });

  stroke(0);
  strokeWeight(0);
  background(150);
  texture(textureImg);

  // orbitControl(5)
  // rotateZ(PI)

  var scale = min(width, height) * 0.1;
  var targetX = sin(frameCount / 60) * scale + (mouseX/width - 0.5) * scale
  var targetY = (mouseY/height - 0.5) * scale
  var z = sin(millis()/100) * scale/2
  currX -= (currX - targetX) * 0.05
  currY -= (currY - targetY) * 0.05
  camera.lookAt(0, 0, 0);
  camera.setPosition(currX, currY, z);

  model(myModel);

  // push()
  // translate(0, 0, dim*1.6)
  // rotateY(frameCount / 1 % 360)
  // pop()

}    

function writeTexts () {
  var t1 = text4 + text4 + text4;
  var length = 16;
  var index = frameCount % (t1.length - length);
  var t2 = t1.substr(index) + t1.substr(index, length);
  imgTypography.fill(255);
  imgTypography.textSize(height/120);
  imgTypography.text(t2, width*0.3, height*0.2, width*0.4, height);
}

var text1 = `TRANSMEDIA RESEARCH INSTITUTE TRANSMEDIA RESEARCH INSTITUTE TRANSMEDIA RESEARCH INSTITUTE TRANSMEDIA RESEARCH INSTITUTE TRANSMEDIA RESEARCH INSTITUTE TRANSMEDIA RESEARCH INSTITUTE TRANSMEDIA RESEARCH INSTITUTE TRANSMEDIA       RESEARCH INSTITUTE                          TRANSMEDIA RESEARCH INSTITUTE                          TRANSMEDIA RESEARCH INSTITUTE TRANSMEDIA RESEARCH INSTITUTE TRANSMEDIA RESEARCH INSTITUTE TRANSMEDIA RESEARCH INSTITUTE TRANSMEDIA RESEARCH INSTITUTE TRANSMEDIA RESEARCH INSTITUTE TRANSMEDIA RESEARCH INSTITUTE TRANSMEDIA RESEARCH INSTITUTE TRANSMEDIA RESEARCH INSTITUTE TRANSMEDIA RESEARCH INSTITUTE TRANSMEDIA RESEARCH INSTITUTE TRANSMEDIA RESEARCH INSTITUTE TRANSMEDIA RESEARCH INSTITUTE TRANSMEDIA RESEARCH INSTITUTE TRANSMEDIA RESEARCH                          INSTITUTE TRANSMEDIA RESEARCH                          INSTITUTE TRANSMEDIA RESEARCH INSTITUTE TRANSMEDIA RESEARCH INSTITUTE TRANSMEDIA RESEARCH INSTITUTE TRANSMEDIA RESEARCH INSTITUTE TRANSMEDIA RESEARCH                          INSTITUTE TRANSMEDIA RESEARCH INSTITUTE TRANSMEDIA RESEARCH INSTITUTE                          TRANSMEDIA RESEARCH INSTITUTE TRANSMEDIA RESEARCH INSTITUTE TRANSMEDIA RESEARCH INSTITUTE TRANSMEDIA RESEARCH INSTITUTE TRANSMEDIA RESEARCH       INSTITUTE TRANSMEDIA RESEARCH       INSTITUTE TRANSMEDIA RESEARCH INSTITUTE TRANSMEDIA RESEARCH INSTITUTE TRANSMEDIA RESEARCH INSTITUTE                          TRANSMEDIA RESEARCH INSTITUTE TRANSMEDIA RESEARCH INSTITUTE TRANSMEDIA RESEARCH INSTITUTE TRANSMEDIA RESEARCH INSTITUTE TRANSMEDIA RESEARCH INSTITUTE TRANSMEDIA RESEARCH INSTITUTE TRANSMEDIA RESEARCH INSTITUTE TRANSMEDIA RESEARCH INSTITUTE TRANSMEDIA RESEARCH INSTITUTE       TRANSMEDIA RESEARCH INSTITUTE TRANSMEDIA RESEARCH INSTITUTE TRANSMEDIA RESEARCH INSTITUTE TRANSMEDIA RESEARCH INSTITUTE `;

var text2 = `
function setup () {   var canvas = createCanvas(window.innerWidth, window.innerHeight, WEBGL);   imageMode(CENTER)   angleMode(DEGREES)   noStroke()    dim = min(width, height) * 0.5;    // set up offscreen canvas imgTypography   imgTypography = createGraphics(width, height);   imgTypography.textFont(f)   ctx = imgTypography.drawingContext;   writeTex ts();   parts.push(imgTypography);    textureImg = createGraphics(width*2, width*2)   parts.forEach(function (part, i) {     var size = textureImg.width * 0.7 + noise(millis()/10000 + i) * textureImg.width * 0.3;     textureImg.image(part, 0,0, size, size);   });    camera = createCamera();   // ortho(-width / 2, width / 2, height / 2, -height / 2, -dim*3, dim*3);   var n = 3000   perspective(PI / 3.0 * n, width / height, 0.1, 500);  }  function draw() {   stroke(0);   strokeWeight(0);   background(150);   texture(textureImg);    // orbitControl(5)   // rotateZ(PI)    var scale = min(width, height) * 0.1;   var targetX = sin(frameCount / 60) * scale + (mouseX/width - 0.5) * scale   var targetY = (mouseY/height - 0.5) * scale   var z = sin(millis()/100) * scale/2   currX -= (currX - targetX) * 0.05   currY -= (currY - targetY) * 0.05`;

var text3 = `
function setup () {   var INSTITUTE TRA TRANSMEDITUTE TRANSSMEDIA RESWEBGL);   creen canvpography.ddth*2)   preImg.widt, width / t, 0.1, 50A RESEARCHNSMEDIA RESEARCH INSTITUTE TRANSMEDIA RESEARCH INSTITUTE TRANSMEDIA RESEARCH INSTITUTE TRANSMEDIA RESEARCH INSTITUTE TRANSMEDIA RESEARCH INSTITUTE TRANSMEDIA RESEARCH INSTITUTE TRANSMEDIA RESEARCH                          INSTITUTE TRANSMEDIA RESEARCH                          INSTITUTE TRANSMEDIA RESEARCH INSTITUTE TRANSMEDIA RESEARCH INSTITUTEA RESEARCH INSTITUTE TRANSMEDIA RESEARCH INSTITUTE TRANSMEDIA RESEARCH                          INSTIMEDIA RESEARCH INSTITUTE TRANSMEDIA RESEARCH INSTITUTE                          TRANEARCH                                     canvas = createCanvas(window.innerWidth, window.innerHeight, imageMode(CENTER)   angleMode(DEGREES)   noStroke()    dim = min(width, height) * 0.5;    // set up offsas imgTypography   imgTypography = createGraphics(width, height);   imgTypography.textFont(f)   ctx = imgTyrawingContext;   writeTex ts();   parts.push(imgTypography);    textureImg = createGraphics(width*2, wiarts.forEach(function (part, i) {     var size = textureImg.width * 0.7 + noise(millis()/10000 + i) * textuh * 0.3;     textureImg.image(part, 0,0, size, size);   });    camera = createCamera();   // ortho(-width / 22, height / 2, -height / 2, -dim*3, dim*3);   var n = 3000   perspective(PI / 3.0 * n, width / heigh0);  }  function draw() INSTITUTE TRANSMEDIA RESEARCH INSTITUTE TRANSMEDIA RESEARCH INSTITUTE TRANSMEDI INSTITUTE TRANSMEDIA RESEARCH INSTITUTE TRANSMEDIA RESEARCH INSTITUTE TRANSMEDIA RESEARCH INSTITUTE TRANSMEDIA                                    RESEARCH                          INSTITUTE TRANSMEDIA                                    RESEARCH                          INSTITUTE TRANSMEDIA                                    RESEARCH INSTITUTE TRANSMEDIA RESEARCH INSTITUTE TRANSMEDIA RESEARCH INSTITUTE TRANSMEDIA RESEARCH INSTITUTE TRANSMEDIA RESEARCH                                                             INSTITUTE TRANSMEDIA RESEARCH INSTITUTE TRANSMEDIA RESEARCH INSTITUTE                          TRANSMEDIA RESEARCH {   stroke(0);   strokeWeight(0);   background(150);   texture(textureImg);    // orbitControl                                   (5)   // rotateZ(PI)    var scale = min(width, height) * 0.1;   var targetX = sin(frameCount / 60) * scale + (mouseX/width - 0.5) * scale   var targetY = (mouseY/height - 0.5) * scale   var z = sin(millis()/100) * scale/2   currX -= (currX - targetX) * 0.05   currY -= (currY - targetY) * 0.05`;

var text4 = `
fwindow.innerWidth AAAGA, window.innerHeightREES)1:12010-13670 Homo CTCAT sapiens chromosome 1   noStroke()    dim = min(width, height) * 0.5;    // set up offscreen canvas imgGL           imgGL = CCATC createGraphics(widthCAGCAACTGCTGGCCTGTGCCAGGGTGCAAGCTGAGCAC, height);   imgGL.textFont(f)   ctxGGGAAAGATTGGAGG = imgGL.drawingContext GCTTT;   writeTex ts() TAGGT;   parts.push(imgTypographyTG        CCTAGAGTGGGATGGGCCATTGTTCATCTTCTGGCCC);    textureImg =GTGCTCATCTCCTTG createGraphics(width*2, width*2)   parts.forEach(function (part, i) {     var AGGCA size =CAGGCATAGGGGAAAGATTGGAGGAAAGATGAGTGAGAG textureImgCTGCCATCGGAGCCC.width *AAAGA 0.7 + noise(millis()/10000 + i) * GGCTC textureImg.width * 0Ö±CTGGCTTTGGCCCTG.3;     text        ureImgCTCAT.image(part, 0,0GTAGTGCTTGTGCTCATCTCCTTGGCTGTGATACGTGGC, size, size);   });    camera = createCamera();GCCTAGGTGGGATCTCCATC   // ortho(-width / 2, width / 2, height / 2, -height / 2, -dim*3, dimTGCCGTCTGCTGCCATCGGAGCCCAAAGCCGGGCTGTGA*3);GCTTT   varCGCAGGCACAGCCAA n = 3000   perspective(PI / 3.0 * n, width / height, 0.1, 500);  }  function draw()TAGGT {   stroke(0);CCAGGCTCCTGTCTC   strokeWeight(0TCAGCAGGTCTGb]EGCTTTGGCCCTGGGAGAGCAGGTGGAA);   backgroundAGGCA(150);           texture(textureImg);    orbitControl(5)GCTGCAGAAGACGAC   // rotateZ(PI)    varGGCTC scale = min(width,AGTGGATTGGCCTAGGTGGGATCTCTGAGCTCAACAAGC height) * 0GAGTAGACAGTGAGT.1;   var targetX = sin(frameCount / 60) * scale + (mouseX/width - 0.5) * scale   var targetY = (mouseYAGGCTTCGATGCCCC/heightGGGCAGAGCCGCAGGCACAGCCAAGAGGGCTGAAGAAAT - 0.5) * scale   var z = sin(millis()/100) * scale/2   currX -= (currX - targetX) * 0.05   currY -= (currY - targetY) * 0.05`;

/*
// chromosome
>NC_000001.11:12010-13670 Homo sapiens chromosome 1, GRCh38.p14 Primary Assembly
GTGTCTGACTTCCAGCAACTGCTGGCCTGTGCCAGGGTGCAAGCTGAGCACTGGAGTGGAGTTTTCCTGT
GGAGAGGAGCCATGCCTAGAGTGGGATGGGCCATTGTTCATCTTCTGGCCCCTGTTGTCTGCATGTAACT
TAATACCACAACCAGGCATAGGGGAAAGATTGGAGGAAAGATGAGTGAGAGCATCAACTTCTCTCACAAC
CTAGGCCAGTAAGTAGTGCTTGTGCTCATCTCCTTGGCTGTGATACGTGGCCGGCCCTCGCTCCAGCAGC
TGGACCCCTACCTGCCGTCTGCTGCCATCGGAGCCCAAAGCCGGGCTGTGACTGCTCAGACCAGCCGGCT
GGAGGGAGGGGCTCAGCAGGTCTGGCTTTGGCCCTGGGAGAGCAGGTGGAAGATCAGGCAGGCCATCGCT
GCCACAGAACCCAGTGGATTGGCCTAGGTGGGATCTCTGAGCTCAACAAGCCCTCTCTGGGTGGTAGGTG
CAGAGACGGGAGGGGCAGAGCCGCAGGCACAGCCAAGAGGGCTGAAGAAATGGTAGAACGGAGCAGCTGG
TGATGTGTGGGCCCACCGGCCCCAGGCTCCTGTCTCCCCCCAGGTGTGTGGTGATGCCAGGCATGCCCTT
CCCCAGCATCAGGTCTCCAGAGCTGCAGAAGACGACGGCCGACTTGGATCACACTCTTGTGAGTGTCCCC
AGTGTTGCAGAGGTGAGAGGAGAGTAGACAGTGAGTGGGAGTGGCGTCGCCCCTAGGGCTCTACGGGGCC
GGCGTCTCCTGTCTCCTGGAGAGGCTTCGATGCCCCTCCACACCCTCTTGATCTTCCCTGTGATGTCATC
TGGAGCCCTGCTGCTTGCGGTGGCCTATAAAGCCTCCTAGTCTGGCTCCAAGGCCTGGCAGAGTCTTTCC
CAGGGAAAGCTACAAGCAGCAAACAGTCTGCATGGGTCATCCCCTTCACTCCCAGCTCAGAGCCCAGGCC
AGGGGCCCCCAAGAAAGGCTCTGGTGGAGAACCTGTGCATGAAGGCTGTCAACCAGTCCATAGGCAAGCC
TGGCTGCCTCCAGCTGGGTCGACAGACAGGGGCTGGAGAAGGGGAGAAGAGGAAAGTGAGGTTGCCTGCC
CTGTCTCCTACCTGAGGCTGAGGAAGGAGAAGGGGATGCACTGTTGGGGAGGCAGCTGTAACTCAAAGCC
TTAGCCTCTGTTCCCACGAAGGCAGGGCCATCAGGCACCAAAGGGATTCTGCCAGCATAGTGCTCCTGGA
CCAGTGATACACCCGGCACCCTGTCCTGGACACGCTGTTGGCCTGGATCTGAGCCCTGGTGGAGGTCAAA
GCCACCTTTGGTTCTGCCATTGCTGCTGTGTGGAAGTTCACTCCTGCCTTTTCCTTTCCCTAGAGCCTCC
ACCACCCCGAGATCACATTTCTCACTGCCTTTTGTCTGCCCAGTTTCACCAGAAGTAGGCCTCTTCCTGA
CAGGCAGCTGCACCACTGCCTGGCGCTGTGCCCTTCCTTTGCTCTGCCCGCTGGAGACGGTGTTTGTCAT
GGGCCTGGTCTGCAGGGATCCTGCTACAAAGGTGAAACCCAGGAGAGTGTGGAGTCCAGAGTGTTGCCAG
GACCCAGGCACAGGCATTAGTGCCCGTTGGAGAAAACAGGGGAATCCCGAA

// Binary
\Nr*yô√  ZC;º.íP 0©˚‰‡G<[¸¥TŸ;Œ~ç’Ê◊fÕÍïF⁄2X(±«≤}]Ñ¥@µe∏/êb›Ìù˚È@†  -VaBπ„◊!˝ˆÆ]¡∞Æ6™.9QlÖ±ôSæ◊ﬁÂΩÍ‡O“Ä√˙çO˝[ ¬TY2%ˆp‡UU≈ƒ‹ù≤‡äF Äeª_;˜Y°@Õã<œ’Ì[N;Ù˛Ja˛6¡ùÊ`¬Á,Äpb]E¶b+ÓZo∫z3&¡◊U¶è±.áÏû>WÓ±Ó2ñÄ}f¢=¢"cñ;Ûg˜‹bπâ¡<å7ùπ’<≤<Ql® ®»˝ æwÿÏÆaiR–ªy®b©Æ*wSŒZ¬òÃ@|kZº…:üßßÔ◊:Pz_¡âá+êπ…9Ä¥ò±)/Âowø_¯«KòP/Û7™P¨1è9øL∫<å<IõÊ⁄·BrÀ‘ÉÃƒXçÛY∂áÙ¥‰ˆ√ÔëQ⁄˙ß•ç3ºg—˜6z Mw ¸¿môYjMç€méeZ-@œ<%añNY∂KÃø˝üt¿ﬂ∞/hÎ©2"∂à®ó^Ó’Å®€√ä“ü˜ep≈Nx Æõ£=öR8úDÕ£"œÁîÚM\i´5ÔÎiw;û

*/

</script>
</body>
</html>