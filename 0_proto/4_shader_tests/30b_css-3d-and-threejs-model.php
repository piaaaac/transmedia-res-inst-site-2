<?php
// VIA TUTORIAL clicktorelease.com
// https://www.clicktorelease.com/blog/how-to-make-clouds-with-css-3d/
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>CSS world</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">
  <style>
  *{
    box-sizing: border-box;
    margin: 0;
    padding: 0
  }
  body {
    color: #eee;
    text-shadow: 0 -1px 0 rgba( 0, 0, 0, .6 );
    font-family: 'Open Sans', sans-serif;
    font-size: 13px;
    line-height: 16px;
    overflow: hidden;
    background-color: #666;
  }
  #viewport {
    -webkit-perspective: 50;
    -moz-perspective: 50;
    -o-perspective: 50;
    position: absolute;
    left: 0;
    top: 0;
    right: 0;
    bottom: 0;
    overflow: hidden;
  }

  #world {
    position: absolute;
    left: 50%;
    top: 50%;
    margin-left: -256px;
    margin-top: -256px;
    height: 512px;
    width: 512px;
    background-color: rgba( 255, 0, 0, .2 );
    -webkit-transform-style: preserve-3d;
    -moz-transform-style: preserve-3d;
    -o-transform-style: preserve-3d;
  }

  #world div {
    -webkit-transform-style: preserve-3d;
    -moz-transform-style: preserve-3d;
    -o-transform-style: preserve-3d;
  }

  .cloudBase {
    background-color: rgba( 255, 0, 255, .5 );
    position: absolute;
    left: 256px;
    top: 256px;
    width: 20px;
    height: 20px;
    margin-left: -10px;
    margin-top: -10px;
  }

  .cloudLayer {
    position: absolute;
    left: 50%;
    top: 50%;
    width: 256px;
    height: 256px;
    margin-left: -128px;
    margin-top: -128px;
    background-color: rgba( 0, 255, 255, .1 );
    -webkit-transition: opacity .5s ease-out;
    -moz-transition: opacity .5s ease-out;
    -o-transition: opacity .5s ease-out;
  }
  </style>
  </head>
  <body>

  <div id="viewport" >
    <div id="world" >
    </div>
  </div>

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
  var x = 256 - ( Math.random() * 512 );
  var y = 256 - ( Math.random() * 512 );
  var z = 256 - ( Math.random() * 512 );
  var t = 'translateX( ' + x + 'px ) translateY( ' + y + 'px ) translateZ( ' + z + 'px )';
  div.style.webkitTransform = t;
  div.style.MozTransform = t;
  div.style.oTransform = t;
  world.appendChild( div );

  for( var j = 0; j < 5 + Math.round( Math.random() * 10 ); j++ ) {
    var cloud = document.createElement( 'img' );
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

  worldYAngle = -( .5 - ( x / window.innerWidth ) ) * 180;
  worldXAngle = ( .5 - ( y / window.innerHeight ) ) * 180;
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

  for( var j = 0; j < 5; j++ ) {
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
    layer.style.webkitTransform = t;
    layer.style.MozTransform = t;
    layer.style.oTransform = t;
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
      "three": "./threejs/three.module.js",
      "three/addons/": "./threejs/examples/jsm/"
    }
  }
</script>

<script type="module">

  import * as THREE from 'three';
  import { OBJLoader } from 'three/addons/loaders/OBJLoader.js';
  import { OrbitControls } from './threejs/examples/jsm/controls/OrbitControls.js';

  let container;
  let camera, scene, renderer;
  let mouseX = 0, mouseY = 0;
  let windowHalfX = window.innerWidth / 2;
  let windowHalfY = window.innerHeight / 2;
  let object;
  let controls;

  init();
  animate();

  function init() {

    container = document.createElement( 'div' );
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

    controls = new OrbitControls( camera, renderer.domElement );
    controls.addEventListener( 'change', render ); // use if there is no animation loop
    controls.enablePan = false;
    controls.enableZoom = false;
    controls.autoRotate = true;

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
      './models/joints_union_5_wireframe.obj', // creature
      // './models/cave-malachite-decimate0.2.obj', // cave
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
    controls.update();
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


</body>
</html>
