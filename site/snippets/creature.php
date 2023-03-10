
<div id="creature"></div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.11.4/gsap.min.js" integrity="sha512-f8mwTB+Bs8a5c46DEm7HQLcJuHMBaH/UFlcgyetMqqkvTcYg4g5VXsYR71b3qC82lZytjNYvBj2pf0VekA9/FQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

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
  // import Stats from 'three/addons/libs/stats.module.js';






  // -----------------------------------------
  // Global vars
  // -----------------------------------------
  
  c3 = new Creature3d();

  console.log("c3 created")
  cmain.creature3d = c3

  var state = {
    transition: false,
    prevMesh: null,
    currMesh: null,
  }
  



  // -----------------------------
  // OBJECT
  // -----------------------------

  function Creature3d (creatureMain) {

    // this.state = {
    //   transition: false,
    //   currentScale: null,
    // };
    this.scene = new THREE.Scene();
    this.group = new THREE.Group();
    this.camera = new THREE.PerspectiveCamera( 35, window.innerWidth / window.innerHeight, 1, 2000 );

    this.meshes = [];
    this.self = this;





    this.tl = new TimelineMax({paused: true});
    




    // Functions
    // =========

    this.check = function () {
      this.meshes.forEach(m => {
        console.log(m.userData)
      });
    }

    this.flicker = function () {
      switchRandomMesh();
    }

    this.animate = function (key) {

      if (key === 0) {
        this.tl.to(this.camera.position,  1, {z: -5, ease: Expo.easeOut}, "-=1")
        this.tl.play()
      }

      if (key === 1) {
        this.tl.to(this.group.scale,     1, {x: 2, ease: Expo.easeOut})
        this.tl.to(this.group.position,  1, {x: 1, y: 1, ease: Expo.easeOut}, "-=1")
        this.tl.to(this.group.scale,     1, {x: 1, ease: Expo.easeOut})
        this.tl.to(this.group.rotation,  1, {x: Math.PI * 0.5, ease: Expo.easeOut}, "-=1")
        this.tl.play()
      }

      if (key === 2) {
        this.tl.to(this.group.scale,     1, {x: 5, ease: Expo.easeOut})
        this.tl.to(this.group.position,  1, {x: -10, y: 10, ease: Expo.easeOut}, "-=1")
        this.tl.to(this.group.scale,     1, {x: 6, ease: Expo.easeOut})
        this.tl.to(this.group.rotation,  1, {x: Math.PI * 1.5, ease: Expo.easeOut}, "-=1")
        this.tl.play()
      }

    }

    this.resize = function (key) {
      var values = {
        "normal": {
          "scale": 0.6,
          "position": {x: 0, y: 0, z: 0},
        },
        "small": {
          "scale": 0.2,
          "position": {x: -1, y: 0, z: 0},
        }
      };
      if (values.hasOwnProperty(key)) {
        var s = values[key].scale;
        var p = values[key].position;
        this.group.scale.set(s, s, s);
        this.scene.translateX(-1.5);
      } else {
        console.log("error 23097652 - size key not found")
      }
    }

    // this.setFlicker = (amt) => {}

  }













  // -----------------------------------------
  // Setup: scene, camera, renderer
  // -----------------------------------------
  
  // c3.scene = new THREE.Scene();
  // const camera = new THREE.PerspectiveCamera( 35, window.innerWidth / window.innerHeight, 1, 2000 );
  c3.camera.position.z = 5;
  const renderer = new THREE.WebGLRenderer({antialias: true, alpha: true});
  renderer.setSize( window.innerWidth, window.innerHeight );
  renderer.setPixelRatio( window.devicePixelRatio );
  document.getElementById("creature").appendChild(renderer.domElement);

  console.log(document.querySelector("#creature canvas"))
  console.log(renderer.domElement)

  const controls = new OrbitControls( c3.camera, renderer.domElement );
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
    // modelsFolder +"1.obj",
    // modelsFolder +"2.obj",
    // modelsFolder +"3.obj",
    // modelsFolder +"6.obj", 
    // modelsFolder +"7.obj",
    // modelsFolder +"8.obj",
    // modelsFolder +"9.obj",
    // modelsFolder +"10.obj", 
    modelsFolder +"4.obj", 
    modelsFolder +"5.obj",
    modelsFolder +"11.obj",
    modelsFolder +"12.obj",
    modelsFolder +"13.obj", 
  ];

  function loadNextFile() {

    // All files loaded

    if (index > modelFiles.length - 1) {
      
      c3.meshes.forEach(mesh => {
        mesh.visible = false
      })
      state.prevMesh = c3.meshes[c3.meshes.length - 2];
      state.currMesh = c3.meshes[c3.meshes.length - 1];
      // state.currMesh.visible = true;
      c3.group.scale.set(0.6, 0.6, 0.6);
      c3.scene.add(c3.group)
      
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
            // color: 0x00ffff,
            polygonOffset: true,
            polygonOffsetFactor: 1, // positive value pushes polygon further away
            polygonOffsetUnits: 1
        } );
        var mesh = new THREE.Mesh( geometry, baseMaterial );
        var geo = new THREE.EdgesGeometry( mesh.geometry ); // or WireframeGeometry
        // var mat = new THREE.LineBasicMaterial( { color: 0xffffff } );
        var mat = new THREE.LineBasicMaterial( { color: 0xF4FF00 } );
        var wireframe = new THREE.LineSegments( geo, mat );
        mesh.add( wireframe );

        c3.group.add(mesh)
        // c3.scene.add(mesh)
        c3.meshes.push(mesh)
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
  c3.scene.add(light);

  const ambientLight = new THREE.AmbientLight( 0xcccccc, 0.8 );
  c3.scene.add( ambientLight );
  

  const pointLight = new THREE.PointLight( 0xffffff, 0.8 );
  c3.camera.add( pointLight );
  c3.scene.add( c3.camera );

  // -----------------------------------------
  // Render
  // -----------------------------------------

  // const stats = Stats()
  // document.body.appendChild(stats.dom)
  // stats.dom.style.top = "200px"

  function animate() {
    requestAnimationFrame( animate );
    render();
    // stats.update()
  }
  function render() {
    c3.group.rotation.y += 0.005;
    if (state.transition) {
      state.currMesh.visible = (Math.random() < 0.5);
      state.prevMesh.visible = !state.currMesh.visible;

      // var color = Math.floor(Math.random() * 0xffffff);
      // state.currMesh.material.color.setHex(color); 
      // var colorR = Math.floor(Math.random() * 255);
      // var colorG = Math.floor(Math.random() * 255);
      // var color = new THREE.Color(`rgb(${colorR}, 0, 255)`);
      // var color = new THREE.Color(`rgb(0, 255, 255)`);
      // state.currMesh.material.color.set(color); 
      
      var hcolors = [0x000000, 0x888888, 0xffffff];
      var hcolor = hcolors[Math.floor(Math.random(hcolors.length))]
      state.currMesh.material.color.setHex(hcolor);
      state.currMesh.material.color.setHex(0x888888);
    }
    c3.camera.lookAt( c3.scene.position );
    renderer.render( c3.scene, c3.camera );
  }




  // -----------------------------------------
  // Interactivity
  // -----------------------------------------
  
  var cssInterval;
  function switchRandomMesh () {
    var newI = Math.floor(Math.random() * c3.meshes.length)
    state.prevMesh = state.currMesh;
    state.currMesh = c3.meshes[newI];

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
    state.prevMesh.material.color.setHex(0x666666);
    state.currMesh.material.color.setHex(0x666666);
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
    c3.camera.aspect = window.innerWidth / window.innerHeight;
    c3.camera.updateProjectionMatrix();
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
