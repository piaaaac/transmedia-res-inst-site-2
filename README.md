
# REFACTOR

- make tile highlight probability controllable independently from scroll
- make inspector & creature into objects to control from home.php
- scale 3d correctly when inspector is open

# TODO

- make a snippet generating an image with highlights 
    (that just works throughout the site)

- creature model flickering and randomly changing 
    (flicker corresponds to console log?)

- imagine larger amounts of content in the console
    (eg artist bios – even an article?)

- BILINGUE (Solo eventi)

- foglietto (su storyboard)



# HIGHLIGHT JS DARK THEMES

atom-one-dark
base16/solarized-dark
base16/railscasts
monokai-sublime
base16/zenburn
agate
androidstudio
base16/dracula


# CREDITS

visual research, concept & identity: @piaaaac with @zzzzz_project / @___radicale___
creative coding by @piaaaac


# CODE CONTENTS (SNIPPETS FOR SOCIAL ETC)

  ### Computer Vision 

  function addDetection (
    modelName, 
    modelOptions, 
    imageFilename, 
    timestamp, 
    label, 
    confidence, 
    normX, 
    normY, 
    normW, 
    normH
  ) {
    var detection = {
      "modelName": modelName,
      "modelOptions": modelOptions,
      "imageFilename": imageFilename,
      "timestamp": timestamp,
      "label": label,
      "confidence": confidence,
      "normX": normX,
      "normY": normY,
      "normW": normW,
      "normH": normH,
    };
    log.push(detection);
  }

  function highlightOn (highlightEl) {
    let h = highlightEl
    h.classList.add("active");
    h.dataset.leftOri = h.style.left;
    h.dataset.topOri = h.style.top;
    h.dataset.widthOri = h.style.width;
    h.dataset.heightOri = h.style.height;
    h.style.left = h.dataset.highlightX * 100 +"%";
    h.style.top = h.dataset.highlightY * 100 +"%";
    h.style.width = h.dataset.highlightW * 100 +"%";
    h.style.height = h.dataset.highlightH * 100 +"%";
    console.log(highlightEl);
  }

  ### 3d

  const objLoader = new OBJLoader();
  var index = 0;
  var modelsFolder = "<?= $kirby->url("assets") ?>/models/"
  var modelFiles = [
    modelsFolder +"2.obj",
    modelsFolder +"4.obj", 
    modelsFolder +"6.obj", 
    modelsFolder +"10.obj", 
    modelsFolder +"13.obj", 
  ];

  function loadNextFile() {
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
    objLoader.load(url, (object) => {
      var geometry = object.children[0].geometry;
      geometry.center();
      var baseMaterial = new THREE.MeshPhongMaterial( {
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
  }
  loadNextFile();

  ### Inspector & behaviour

  function CreatureConciousness () {
    this.sentences = {
      "dont_understand": (command) => {
        return `I don't understand ${command}`;
      },
    }

    this.say = (string) => {
      log(string, {
        type: "alert",
        content: "html",
        srcFile: "creature.js:"+ Math.floor(Math.random()*1000)
      })
    }

    this.actions = {
      "loading": function (text, totalTime) {
        var time = 0;
        var adv = 0;
        var tt = totalTime + (Math.random() - 0.5) * 1000 // +/- 500ms
        var logMode = "append"
        var messages = [];
        while (adv < 1) {
          time += 100 + Math.random() * 300;
          adv = time / tt;
          perc = Math.floor(adv * 100);
          messages.push({text, mode, time});
          logMode = "replaceLast";
        }

        messages.forEach(message => {
          setTimeout(function () {
            log(message.text, {mode: message.mode})
          }, message.time);
        })
      },

      "vocabulary": () => {
        log("genesis()")
        log("research()")
        log("language()")
      },
    }
  }
