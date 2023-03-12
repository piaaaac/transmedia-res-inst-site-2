
<div id="creature"></div>

<!-- <script async src="https://unpkg.com/es-module-shims@1.3.6/dist/es-module-shims.js"></script> -->
<script async src="<?= $kirby->url("assets") ?>/lib/es-module-shims.js"></script>
<script>
  var creatureRefresh;
</script>
<script type="importmap">
  {
    "imports": {
      "three": "<?= $kirby->url("assets") ?>/lib/threejs/three.module.js",
      "three/addons/": "<?= $kirby->url("assets") ?>/lib/threejs/examples/jsm/"
    }
  }
</script>
<script type="module">

  // -----------------------------------------
  // threejs imports
  // -----------------------------------------

  import * as THREE from 'three';
  import { OBJLoader } from 'three/addons/loaders/OBJLoader.js';
  import { OrbitControls } from 'three/addons/controls/OrbitControls.js';
  import Stats from 'three/addons/libs/stats.module.js';






  // -----------------------------------------
  // Global vars
  // -----------------------------------------
  
  var c3 = new Creature3d();

  var meshes = [];
  var state = {
    transition: false,
    prevMesh: null,
    currMesh: null,
  }
  



  // -----------------------------
  // OBJECT
  // -----------------------------

  function Creature3d () {

    this.meshes = [];

    // Functions
    // =========

    this.setFlicker = (amt) => {

    }

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
  document.getElementById("creature").appendChild(renderer.domElement);

  const controls = new OrbitControls( camera, renderer.domElement );
  controls.addEventListener( 'change', render ); // use if there is no animation loop
  controls.enablePan = false;
  controls.enableZoom = false;

  // -----------------------------------------
  // Add model
  // -----------------------------------------

  const objLoader = new OBJLoader();
  var index = 0;
  var modelsFolder = "<?= $kirby->url("assets") ?>/models/"
  var modelFiles = [
    modelsFolder +"1.obj",
    modelsFolder +"3.obj",
    modelsFolder +"5.obj",
    modelsFolder +"7.obj",
    modelsFolder +"8.obj",
    modelsFolder +"9.obj",
    modelsFolder +"11.obj",
    modelsFolder +"12.obj",
    modelsFolder +"2.obj",
    modelsFolder +"4.obj", 
    modelsFolder +"6.obj", 
    modelsFolder +"10.obj", 
    modelsFolder +"13.obj", 
  ];

  function loadNextFile() {

    // All files loaded

    if (index > modelFiles.length - 1) {
      
      meshes.forEach(mesh => {
        mesh.visible = false
      })
      state.prevMesh = meshes[meshes.length - 2];
      state.currMesh = meshes[meshes.length - 1];
      state.currMesh.visible = true;
      scene.scale.set(0.6, 0.6, 0.6);
      
      animate();
      return;
    }

    var url = modelFiles[index];
    objLoader.load(url, 
      (object) => {
        console.log(url + '% loaded');
        var geometry = object.children[0].geometry;
        geometry.center();
        var baseMaterial = new THREE.MeshPhongMaterial( {
            // color: 0xFF0100,;;;;;;;;;;;;
            color: 0x666666,
            color: 0x00ffff,
            polygonOffset: true,
            polygonOffsetFactor: 1, // positive value pushes polygon further away
            polygonOffsetUnits: 1
        } );
        var mesh = new THREE.Mesh( geometry, baseMaterial );
        var geo = new THREE.EdgesGeometry( mesh.geometry ); // or WireframeGeometry
        var mat = new THREE.LineBasicMaterial( { color: 0xffffff } );
        var mat = new THREE.LineBasicMaterial( { color: 0xF4FF00 } );
        var wireframe = new THREE.LineSegments( geo, mat );
        mesh.add( wireframe );

        scene.add(mesh)
        meshes.push(mesh)
        index++;
        loadNextFile();
      },
      (xhr) => { // loading progress
        // console.log(url +" - "+ (xhr.loaded / xhr.total * 100) + '% loaded');
      },
      (error) => { // loading errors
        console.log('An error occurred while loading model: '+ url, error );
      });
  }
  loadNextFile();

  // -----------------------------------------
  // Lights
  // -----------------------------------------

  var light = new THREE.PointLight(0xFFFFFF, 0.3, 500);
  light.position.set(10, 0, 25);
  scene.add(light);

  const ambientLight = new THREE.AmbientLight( 0xcccccc, 0.8 );
  scene.add( ambientLight );
  // const pointLight = new THREE.PointLight( 0xffffff, 0.8 );
  // camera.add( pointLight );
  // scene.add( camera );

  // -----------------------------------------
  // Render
  // -----------------------------------------

  const stats = Stats()
  document.body.appendChild(stats.dom)
  stats.dom.style.top = "200px"

  function animate() {
    requestAnimationFrame( animate );
    render();
    stats.update()
  }
  function render() {
    scene.rotation.y += 0.005;
    if (state.transition) {
      state.currMesh.visible = (Math.random() < 0.5);
      state.prevMesh.visible = !state.currMesh.visible;

      // var color = Math.floor(Math.random() * 0xffffff);
      // state.currMesh.material.color.setHex(color); 
      var colorR = Math.floor(Math.random() * 255);
      var colorG = Math.floor(Math.random() * 255);
      var color = new THREE.Color(`rgb(${colorR}, ${colorG}, 255)`);
      state.currMesh.material.color.setHex(color); 
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
    if (cssInterval) {
      clearInterval(cssInterval);
    }
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
    state.prevMesh.material.color.setHex(0xf4ff00);
    state.currMesh.material.color.setHex(0xf4ff00);
    state.transition = false;
    clearInterval(cssInterval);
    document.body.style.backgroundColor = "#666";
  }

  // https://stackoverflow.com/a/5624139
  function rgbToHex(r, g, b) {
    return "#" + (1 << 24 | r << 16 | g << 8 | b).toString(16).slice(1);
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
    // console.log(`Key "${e.key}" pressed`);
    if (e.key == "Enter") {
      switchRandomMesh();
    }
    // if (e.key == "Shift") {}
    // if (e.key == "Escape") {}
  });

  creatureRefresh = switchRandomMesh;

</script>
