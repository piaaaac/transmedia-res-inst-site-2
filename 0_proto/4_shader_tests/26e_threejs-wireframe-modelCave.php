<!DOCTYPE html>
<html lang="en">
  <head>
    <title>three.js webgl - loaders - OBJ loader</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">
    <!-- <link type="text/css" rel="stylesheet" href="threejs/examples/main.css"> -->
    <style>
      body { 
        margin: 0; 
        background: #223;
      }
    </style>
  </head>

  <body>
    <div id="info" style="display: none;">
    <a href="https://threejs.org" target="_blank" rel="noopener">three.js</a> - OBJLoader test
    </div>

    <!-- Import maps polyfill -->
    <!-- Remove this when import maps will be widely supported -->
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

      init();
      animate();

      function init() {

        container = document.createElement( 'div' );
        document.body.appendChild( container );

        camera = new THREE.PerspectiveCamera( 35, window.innerWidth / window.innerHeight, 1, 2000 );
        camera.position.z = 2;
        camera.position.y = 2;

        // transparent bg - via https://stackoverflow.com/a/20496296/2501713
        renderer = new THREE.WebGLRenderer({ alpha: true });
        renderer.setClearColor( 0x000000, 0 );
        renderer.setPixelRatio( window.devicePixelRatio );
        renderer.setSize( window.innerWidth, window.innerHeight );
        container.appendChild( renderer.domElement );

        const controls = new OrbitControls( camera, renderer.domElement );
        controls.addEventListener( 'change', render ); // use if there is no animation loop
        controls.enablePan = false;
        controls.enableZoom = false;

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
          // './models/joints_DECIMATED_3.obj', // creature
          './models/cave-malachite-decimate0.2.obj', // cave
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
        render();
      }

      function render() {
        // camera.position.x += ( mouseX - camera.position.x ) * .5;
        // camera.position.y += ( - mouseY - camera.position.y ) * .5;
        camera.lookAt( scene.position );
        renderer.render( scene, camera );
      }

    </script>

  </body>
</html>