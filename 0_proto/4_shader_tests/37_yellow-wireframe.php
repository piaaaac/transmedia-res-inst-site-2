<!---------------------------------------------------------
--  Load a model
--  via https://threejs.org/docs/#examples/en/loaders/OBJLoader
----------------------------------------------------------->

<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Yellow wireframs</title>
<link rel="stylesheet" href="https://unpkg.com/minimal-css-reset@1.1.0/reset.min.css">
<style>
  body {
    background: #666;
  }
</style>
</head>
<body>

<!-- <script src="https://cdn.jsdelivr.net/npm/three-obj-loader@1.1.3/dist/index.min.js"></script> -->

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

  // -----------------------------------------
  // threejs imports
  // -----------------------------------------

  import * as THREE from 'three';
  import { OBJLoader } from 'three/addons/loaders/OBJLoader.js';
  import { OrbitControls } from './threejs/examples/jsm/controls/OrbitControls.js';
  import Stats from './threejs/examples/jsm/libs/stats.module.js';

  // -----------------------------------------
  // Global vars
  // -----------------------------------------
  
  var meshes = [];
  var state = {
    transition: false,
    prevMesh: null,
    currMesh: null,
  }
  
  // -----------------------------------------
  // Setup: scene, camera, renderer
  // -----------------------------------------
  
  const scene = new THREE.Scene();
  const camera = new THREE.PerspectiveCamera( 35, window.innerWidth / window.innerHeight, 1, 2000 );
  camera.position.z = 5;
  const renderer = new THREE.WebGLRenderer({antialias: true, alpha: true});
  renderer.setSize( window.innerWidth, window.innerHeight );
  // renderer.setClearColor("#630");
  renderer.setPixelRatio( window.devicePixelRatio );
  document.body.appendChild( renderer.domElement );

  const controls = new OrbitControls( camera, renderer.domElement );
  controls.addEventListener( 'change', render ); // use if there is no animation loop
  controls.enablePan = true;
  controls.enableZoom = true;

  // -----------------------------------------
  // Add model
  // -----------------------------------------

  const objLoader = new OBJLoader();
  var index = 0;
  var modelFiles = [
  // "models/parts/1.obj",
  // "models/parts/3.obj",
  // "models/parts/5.obj",
  // "models/parts/7.obj",
  // "models/parts/8.obj",
  // "models/parts/9.obj",
  // "models/parts/11.obj",
  // "models/parts/12.obj",
    "models/parts/2.obj",
    "models/parts/4.obj", 
    "models/parts/6.obj", 
    "models/parts/10.obj", 
    "models/parts/13.obj", 
  ];

  function loadNextFile() {

    if (index > modelFiles.length - 1) {
      
      meshes.forEach(mesh => {
        mesh.visible = false
      })
      state.prevMesh = meshes[meshes.length - 2];
      state.currMesh = meshes[meshes.length - 1];
      state.currMesh.visible = true;
      
      animate();
      return;
    }

    var url = modelFiles[index];
    objLoader.load(url, (object) => {
      var geometry = object.children[0].geometry;
      geometry.center();

      // v2 - wireframe
      let material = new THREE.MeshBasicMaterial({ color: 0xF4FF00, wireframe: true })
      let mesh = new THREE.Mesh(geometry, material)

      // v3 - Points
      // let material = new THREE.PointsMaterial({ color: 0xFFFFFF, size: 0.005 })
      // let mesh = new THREE.Points(geometry, material)

      // v4 - Materials
      // const material = new THREE.MeshNormalMaterial();                   // b
      // const material = new THREE.MeshDepthMaterial();                    // c
      // const material = new THREE.MeshLambertMaterial({color: 0xFF0000}); // a
      // let mesh = new THREE.Mesh(geometry, material)

      scene.add(mesh)
      meshes.push(mesh)
      index++;
      loadNextFile();

    },
    xhr => { // loading progress
      console.log(url +" - "+ (xhr.loaded / xhr.total * 100) + '% loaded');
    },
    error => { // loading errors
      console.log('An error occurred while loading model: '+ url, error );
    });
  }
  loadNextFile();


  // -----------------------------------------
  // Render
  // -----------------------------------------

  const stats = Stats()
  document.body.appendChild(stats.dom)

  function animate() {
    requestAnimationFrame( animate );
    render();
    stats.update()
  }
  function render() {
    scene.rotation.y += 0.005;
    if (state.transition) {
      state.currMesh.visible = (Math.random() < 0.5);
      state.prevMesh.visible = (Math.random() < 0.5);
    }
    camera.lookAt( scene.position );
    renderer.render( scene, camera );
  }

  // -----------------------------------------
  // Interactivity
  // -----------------------------------------
  
  var cssInterval;
  function switchRandomMesh () {
    var newI = Math.floor(Math.random() * meshes.length)
    state.prevMesh = state.currMesh;
    state.currMesh = meshes[newI];

    state.transition = true;
    cssInterval = setInterval(glitchBg, 16);
    setTimeout(endTransition, 200);
  }

  function glitchBg () {
    var color = '#'+Math.floor(Math.random()*0xffffff).toString(16);
    document.body.style.backgroundColor = color;
  }
  function endTransition () {
    state.prevMesh.visible = false;
    state.currMesh.visible = true;
    state.transition = false;
    clearInterval(cssInterval);
    document.body.style.backgroundColor = "#666";
  }

  // -----------------------------------------
  // Window resize handling
  // -----------------------------------------

  window.addEventListener("resize", () => {
    renderer.setSize( window.innerWidth, window.innerHeight );
    camera.aspect = window.innerWidth / window.innerHeight;
    camera.updateProjectionMatrix();
  });

  window.addEventListener('keydown', (e) => {
    console.log(`Key "${e.key}" pressed`);
    if (e.key == "Enter") {
      switchRandomMesh();
    }
    // if (e.key == "Shift") {}
    // if (e.key == "Escape") {}
  });
</script>
</body>
</html>