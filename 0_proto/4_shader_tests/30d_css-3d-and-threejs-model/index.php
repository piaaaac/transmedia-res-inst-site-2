
<?php

// --------------------------------------------------------------------------------------------
// CSS 3d via tutorial: https://www.clicktorelease.com/blog/how-to-make-clouds-with-css-3d/
// --------------------------------------------------------------------------------------------

$thumbsFolder = "../proto-thumbs";
$thumbs = glob("$thumbsFolder/*.{jpeg,jpg,gif,png}", GLOB_BRACE);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<script>
var text = `
<br />18—24 July
<br />Coded Biophilia - Hacking Marea
<br />Giulia Tomasello
<br />
<br />
<br />20—23 July
<br />Adversarial Images
<br />Emidio Battipaglia
<br />
<br />
<br />Sat 23 July
<br />Room69: A Manual for Virtual Squatting
<br />+ Student exhibition
<br />
<br />
<br />26—29 July
<br />Body Architectures for Kinesthetic Memory
<br />Luca Pagan
<br />
<br />
<br />Sat 30 July, 18:30
<br />Economie Instabili / Margherita Severe
<br />+ Performance / Luca Pagan
<br />
<br />
<br />Sat 30 + Sun 31 July
<br />AI Ethics and Prosthetics
<br />Marco Donnarumma
<br />
<br />
<br />Sun 31 July, 21:15
<br />Dopplereffekt live a/v
<br />+ Gabor Lazar live
`;
</script>
  <title>CSS world</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">
  <link rel="stylesheet" type="text/css" href="style.css">
  </head>
  <body>

  <div id="viewport" >
    <div id="world" >
    </div>
  </div>

<!-- Common vars START -->
<script>
  let __3__interface = {};  
  let __imgFiles = <?= json_encode($thumbs) ?>;
  console.log(__imgFiles)
</script>
<!-- Common vars END -->



<!-------------------------------------------------------------------------------------------------------------------------------------------->
<!-------------------------------------------------------------------------------------------------------------------------------------------->
<!-------------------------------------------------------------------------------------------------------------------------------------------->


<!-- Clouds (windows) code START -->
<script>

(function() {
  var lastTime = 0;
  var vendors = ['ms', 'moz', 'webkit', 'o'];
  for(var x = 0; x < vendors.length && !window.requestAnimationFrame; ++x) {
    window.requestAnimationFrame = window[vendors[x]+'RequestAnimationFrame'];
    window.cancelRequestAnimationFrame = window[vendors[x]+
      'CancelRequestAnimationFrame'];
  }

  if (!window.requestAnimationFrame) {
    window.requestAnimationFrame = function(callback, element) {
      var currTime = new Date().getTime();
      var timeToCall = Math.max(0, 16 - (currTime - lastTime));
      var id = window.setTimeout(function() { callback(currTime + timeToCall); },
        timeToCall);
      lastTime = currTime + timeToCall;
      return id;
    }
  }

  if (!window.cancelAnimationFrame) {
    window.cancelAnimationFrame = function(id) {
      clearTimeout(id);
    }
  }
}())

var layers = [],
  objects = [],

  world = document.getElementById( 'world' ),
  viewport = document.getElementById( 'viewport' ),

  d = 0,
  p = 400,
  worldXAngle = 0,
  worldYAngle = 0;

viewport.style.webkitPerspective = p;
viewport.style.MozPerspective = p;
viewport.style.oPerspective = p;

generate();

function createCloud() {

  var div = document.createElement( 'div'  );
  div.className = 'cloudBase';
  var x = 256*3 - ( Math.random() * 512*3 );
  var y = 256*3 - ( Math.random() * 512*3 );
  var z = 256*3 - ( Math.random() * 512*3 );
  var t = 'translateX( ' + x + 'px ) translateY( ' + y + 'px ) translateZ( ' + z + 'px )';
  div.style.webkitTransform = t;
  div.style.MozTransform = t;
  div.style.oTransform = t;
  world.appendChild( div );

  for( var j = 0; j < Math.round( Math.random() * 4 ); j++ ) {
    var cloud = document.createElement( 'div' );
    cloud.className = 'cloudLayer';

    var x = 256 - ( Math.random() * 512 );
    var y = 256 - ( Math.random() * 512 );
    var z = 100 - ( Math.random() * 200 );
    var a = Math.random() * 360;
    var s = .25 + Math.random();
    x *= .2; y *= .2;
    cloud.data = {
      x: x,
      y: y,
      z: z,
      a: a,
      s: s,
      speed: .1 * Math.random()
    };
    var t = 'translateX( ' + x + 'px ) translateY( ' + y + 'px ) translateZ( ' + z + 'px ) rotateZ( ' + a + 'deg ) scale( ' + s + ' )';
    cloud.style.webkitTransform = t;
    cloud.style.MozTransform = t;
    cloud.style.oTransform = t;

    // ----------- AP -----------
    var imgSrc = __imgFiles[Math.floor(Math.random() * __imgFiles.length)];
    console.log(__imgFiles)
    var img = document.createElement( 'img' );
    img.src = imgSrc;
    img.style.width = (Math.random() * 100 + 25) +"%";
    cloud.appendChild(img);
    // ----------- AP -----------

    div.appendChild( cloud );
    layers.push( cloud );
  }

  return div;
}

window.addEventListener( 'mousewheel', onContainerMouseWheel );
window.addEventListener( 'DOMMouseScroll', onContainerMouseWheel );
window.addEventListener( 'mousemove', onMouseMove );
window.addEventListener( 'touchmove', onMouseMove );

function onMouseMove ( e ) {

  var x = e.clientX || e.touches[ 0 ].clientX;
  var y = e.clientY || e.touches[ 0 ].clientY;

  worldYAngle = -( .5 - ( x / window.innerWidth ) ) * 360;
  worldXAngle = ( .5 - ( y / window.innerHeight ) ) * 360;
  updateView();
  event.preventDefault();

}

function onContainerMouseWheel( event ) {

  event = event ? event : window.event;
  d = d - ( event.detail ? event.detail * -5 : event.wheelDelta / 8 );
  updateView();
  event.preventDefault();

}

function generate() {

  objects = [];

  if ( world.hasChildNodes() ) {
    while ( world.childNodes.length >= 1 ) {
      world.removeChild( world.firstChild );
    }
  }

  for( var j = 0; j < 10; j++ ) {
    objects.push( createCloud() );
  }

}

function updateView() {

  var t = 'translateZ( ' + d + 'px ) rotateX( ' + worldXAngle + 'deg) rotateY( ' + worldYAngle + 'deg)';
  world.style.webkitTransform = t;
  world.style.MozTransform = t;
  world.style.oTransform = t;


}

function update (){

  for( var j = 0; j < layers.length; j++ ) {
    var layer = layers[ j ];
    layer.data.a += layer.data.speed;
    var t = 'translateX( ' + layer.data.x + 'px ) translateY( ' + layer.data.y + 'px ) translateZ( ' + layer.data.z + 'px ) rotateY( ' + ( - worldYAngle ) + 'deg ) rotateX( ' + ( - worldXAngle ) + 'deg ) scale( ' + layer.data.s + ')';
    layer.style.transform = t;
  }

  requestAnimationFrame( update );

}

update();

</script>
<!-- Clouds (windows) code END -->


<!-------------------------------------------------------------------------------------------------------------------------------------------->
<!-------------------------------------------------------------------------------------------------------------------------------------------->
<!-------------------------------------------------------------------------------------------------------------------------------------------->


<!-- Threejs code START -->
<script async src="https://unpkg.com/es-module-shims@1.3.6/dist/es-module-shims.js"></script>

<script type="importmap">
  {
    "imports": {
      "three": "./../threejs/three.module.js",
      "three/addons/": "./../threejs/examples/jsm/"
    }
  }
</script>

<script type="module">

  import * as THREE from 'three';
  import { OBJLoader } from 'three/addons/loaders/OBJLoader.js';
  import { OrbitControls } from './../threejs/examples/jsm/controls/OrbitControls.js';

  let container;
  let camera, scene, renderer;
  let mouseX = 0, mouseY = 0;
  let windowHalfX = window.innerWidth / 2;
  let windowHalfY = window.innerHeight / 2;
  let object;

  init();
  animate();

  function init() {

    container = document.createElement( 'div' );
    container.style.pointerEvents = "none";
    document.body.appendChild( container );

    camera = new THREE.PerspectiveCamera( 45, window.innerWidth / window.innerHeight, 1, 2000 );
    camera.position.z = 10;
    camera.position.y = 4;

    // transparent bg - via https://stackoverflow.com/a/20496296/2501713
    renderer = new THREE.WebGLRenderer({ alpha: true });
    renderer.setClearColor( 0x000000, 0 );
    renderer.setPixelRatio( window.devicePixelRatio );
    renderer.setSize( window.innerWidth, window.innerHeight );
    container.appendChild( renderer.domElement );

    __3__interface.controls = new OrbitControls( camera, renderer.domElement );
    __3__interface.controls.addEventListener( 'change', render ); // use if there is no animation loop
    __3__interface.controls.enablePan = false;
    __3__interface.controls.enableZoom = false;
    __3__interface.controls.autoRotate = true;
    
    __3__interface.controls.target = new THREE.Vector3(0, 1000, 0);
    
    __3__interface.randomizeTarget = function () {
      __3__interface.controls.target = new THREE.Vector3(Math.random() * 10, Math.random() * 10, Math.random() * 10);
      __3__interface.controls.update();
    }

    // scene

    scene = new THREE.Scene();

    const ambientLight = new THREE.AmbientLight( 0xcccccc, 0.4 );
    scene.add( ambientLight );

    const pointLight = new THREE.PointLight( 0xffffff, 0.8 );
    camera.add( pointLight );
    scene.add( camera );

    // manager

    function loadModel() {
      object.traverse( function ( child ) {
        // if ( child.isMesh ) child.material.map = texture;
        console.log(child)
        console.log(child)
        console.log(child)
        console.log(child.isMesh)
        console.log(child.isMesh)
        console.log(child.isMesh)
      });

      object.position.y = 0;
      scene.add( object );
    }

    const manager = new THREE.LoadingManager( loadModel );

    // texture

    // const textureLoader = new THREE.TextureLoader( manager );
    // const texture = textureLoader.load( 'textures/uv_grid_opengl.jpg' );
    

    // AP material tests --------------------------------------
    // MeshBasicMaterial( parameters : Object )

    // var geo = new THREE.EdgesGeometry( geometry ); // or WireframeGeometry( geometry )
    // var mat = new THREE.LineBasicMaterial( { color: 0xffffff, linewidth: 2 } );
    // var wireframe = new THREE.LineSegments( geo, mat );



    // !AP material tests --------------------------------------



    // model

    const loader = new OBJLoader( manager );
    loader.load(
      './../models/joints_union_5_wireframe.obj', // creature
      // './../models/cave-malachite-decimate0.2.obj', // cave
      function ( obj ) {
        object = obj;
      }, 
      function onProgress( xhr ) {
        if ( xhr.lengthComputable ) {
          const percentComplete = xhr.loaded / xhr.total * 100;
          console.log( 'model ' + Math.round( percentComplete, 2 ) + '% downloaded' );
        }
      },
      function onError() {}
    );

    document.addEventListener( 'mousemove', function onDocumentMouseMove( event ) {
      mouseX = ( event.clientX - windowHalfX ) / 2;
      mouseY = ( event.clientY - windowHalfY ) / 2;
    });

    window.addEventListener( 'resize', function onWindowResize() {
      windowHalfX = window.innerWidth / 2;
      windowHalfY = window.innerHeight / 2;
      camera.aspect = window.innerWidth / window.innerHeight;
      camera.updateProjectionMatrix();
      renderer.setSize( window.innerWidth, window.innerHeight );
    });
  }
  
  function animate() {
    requestAnimationFrame( animate );
    __3__interface.controls.update();
    render();
  }

  function render() {
    // camera.position.x += ( mouseX - camera.position.x ) * .05;
    // camera.position.y += ( - mouseY - camera.position.y ) * .05;
    camera.lookAt( scene.position );
    renderer.render( scene, camera );
  }

</script>
<!-- Threejs code END -->


<!-------------------------------------------------------------------------------------------------------------------------------------------->
<!-------------------------------------------------------------------------------------------------------------------------------------------->
<!-------------------------------------------------------------------------------------------------------------------------------------------->

<!-- AP code START -->
<script>

randomizeScreens(0.1);



window.addEventListener("click", function () {
  var tTotal = 2000;
  var tStep = 150;
  var tStart = new Date().getTime();
  var int = setInterval(() => { 

    var tNow = new Date().getTime();
    var tAdv = tNow - tStart;
    var normAdv = tAdv / tTotal;
    var sinNorm = Math.sin(degsToRads(180 * normAdv));

    console.log("sinNorm", sinNorm)

    randomizeScreens(sinNorm);
  }, tStep);
  setTimeout(function () {
    clearInterval(int);
    randomizeScreens(0.1);
  }, tTotal);


  // test with random
  __3__interface.randomizeTarget();

});

function randomizeScreens (probActive) {
  for( var j = 0; j < layers.length; j++ ) {
    var layer = layers[ j ];
    layer.classList.remove("active");
    if (Math.random() < probActive) {
      layer.classList.add("active");
    }
  }
}
  
const radsToDegs = rad => rad * 180 / Math.PI;

const degsToRads = deg => (deg * Math.PI) / 180.0;

</script>
<!-- AP code END -->



</body>
</html>


