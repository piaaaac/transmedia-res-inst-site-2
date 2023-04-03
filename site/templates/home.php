<?php
/**
 * @param $trash from controller
 * 
 * */

$images0Shape = page("landing/shape")->files()->shuffle();
$images1Language = page("landing/language")->files()->shuffle();
$filedata = file_get_contents($kirby->root('assets') .'/data/5-merge1-2-4-objectsDetected.json');
$allDetectedObjects = json_decode($filedata, true);

if (!$trash || $trash == null || $trash == "" || !isset($trash)) {
  kill("no trash");
}
?>
<!-- 
<?php
// var_dump($trash);
?>
 -->
<script>
// Variables from php

var cc, cmain;
// var trash = <?= json_encode($trash) ?>;
var trash = <?= safe_json_encode($trash) ?>;
var eventUid = "<?= $eventUid ?>";

console.log(trash)
console.log(eventUid);
</script>


<?php snippet("header") ?>

<?php
$stepMarkers = [
  ["advancement" => 0.5, "id" => "console"],
];
function marker ($id) {
  return "<div class='marker' data-id='$id'></div>";
}
?>

<div id="body-content">

  <!-- --------------------------------------- -->
  <!-- Tiles -->
  <!-- --------------------------------------- -->

  <div id="tiles">
    <?php 
    $index = -1;
    $nextAdvancementIndex = 0;
    $fff = new Files();
    $fff->add($images0Shape);
    $fff->add($images1Language);
    $fffEdited = $fff->shuffle()->slice(0, 120);
    
    /* REC */ // $fffEdited = $fff->shuffle();

    foreach ($fffEdited as $file): 
      $index++;
      $normAdvancement = map($index, 0, $fffEdited->count(), 0, 1);
      $probHighlight = map($normAdvancement, 0, 1, 0.5, 1);
      $probHighlight = pow($probHighlight, 2);
      $amtHighlightTexts = rand(0, 1000)/1000;
      $thisImageFilename = $file->filename();
      $objects = A::filter($allDetectedObjects, function ($val, $key) use ($thisImageFilename) {
        return $val["imageFilename"] === $thisImageFilename;
      });
      ?>

      <div class="tile">
        <div class="img" style="background-image: url('<?= $file->url() ?>');"></div>
        <?php snippet("computer-vision-highlights", [
          "probHighlight" => $probHighlight,
          "amtHighlightTexts" => $amtHighlightTexts,
          "objects" => $objects,
        ]) ?>
      </div>

      <?php 
      if (isset($stepMarkers[$nextAdvancementIndex])) {
        $nextMarker = $stepMarkers[$nextAdvancementIndex];
        if ($normAdvancement > $nextMarker["advancement"]) {
          echo marker($nextMarker["id"]);
          $nextAdvancementIndex++;
        }
      }
      ?>
    <?php endforeach ?>
  </div>
</div>

<!-- --------------------------------------- -->
<!-- Easter -->
<!-- --------------------------------------- -->

<div id="easter"></div>

<!-- --------------------------------------- -->
<!-- Create js variables -->
<!-- Inspector, Creature -->
<!-- --------------------------------------- -->

<?php snippet("inspector") ?>

<script>

// ----------------------------------------
// EVENTS
// ----------------------------------------

// window.addEventListener('DOMContentLoaded', (event) => {
//   setTimeout(() => { 
//     window.scrollTo(0, 0); 
//     console.log("scrolled");
//   }, 100);
// });

window.onbeforeunload = function () {
  document.querySelector('html').style.scrollBehavior = '';
  window.scrollTo(0, 0);
}

window.addEventListener('keydown', (e) => {
  // console.log(`Key "${e.key}" pressed`);
  if (e.key == "Enter") {
    // highlightRandomTile();
    // injectTrash();
  }
  if (e.key == "Shift") {
    // injectImage();
  }
  if (e.key == "Escape") {
    toggleInspector();
  }
});

// ----------------------------------------
// Inspector
// ----------------------------------------

function toggleInspector (bool) {
  var doOpen = !document.body.dataset.inspectorOpen || document.body.dataset.inspectorOpen == "false";
  if (bool === true || bool === false) doOpen = bool;
  if (doOpen) {
    document.body.dataset.inspectorOpen = "true";

    // focus console input
    // var input = document.getElementById("console-input")
    // input.focus()  

    // change observer margin on small screens
    // if (breakpointIs("md", "down")) {
    //   highlightsObserver.disconnect();
    //   highlights.forEach(h => { highlightsObserverHalfH.observe(h); });
    // }
    refreshHighlightObserver();

  } else {
    document.body.dataset.inspectorOpen = "false";
  }
}

// ----------------------------------------
// OBJECT
// ----------------------------------------

cmain = new CreatureMain();

console.log("cmain created")

function CreatureMain () {

  this.conciousness = cc;
  this.self = this;

  this.actions = {

    /**
     * 
     * Map to php's $stepMarkers.
     * eg:
     *    "appear"
     *    "console"
     * 
     * */

    // "appear": function (self) {
    //   self.creature3d.flicker();
    // },

    // "flick": function (self) {
    //   self.creature3d.flicker();
    // },

    "console": function (self) {
      self.conciousness.actions.start();
      // self.creature3d.resize("small");
    },

  };
}


// ----------------------------------------
// MARKERS
// ----------------------------------------

// var php json_encode($stepMarkers)

var markers = document.querySelectorAll("#tiles .marker")
const observerMarkers = new IntersectionObserver(entries => {
  entries.forEach(entry => {
    if (entry.isIntersecting) {
      // console.log("step marker intersected", entry)
      // console.log(entry.target.dataset)
      observerMarkers.unobserve(entry.target);
      var d = entry.target.dataset;
      var id = (d.hasOwnProperty("id")) ? d.id : null;
      if (id && cmain.actions.hasOwnProperty(id)) {
        cmain.actions[id](cmain);
      } else {
        console.log(`marker "${id}" not found in cmain actions`);
      }
    }
  })
}, { 
  // rootMargin: "-45% 0px", // make window smaller vertically, observing only a central strip of the window
  rootMargin: "0% 0px",   // observe the whole window height
})
markers.forEach(h => { observerMarkers.observe(h); });

// ----------------------------------------
// HIGHLIGHTS
// ----------------------------------------


function highlightsObserverCallback (entries) {
  entries.forEach(entry => {
    if (entry.isIntersecting) {
      // console.log(entry)
      if (Math.random() < entry.target.dataset.highlightProbability) { // eg 0.3
        setTimeout(() => { 
          highlightOn(entry.target)
          setTimeout(() => {
            highlightOff(entry.target) 
          }, 2500 + Math.random() * 2500); // duration on screen
        }, Math.random() * 1500); // time on
      }
    }
  })
}

// var highlights = document.querySelectorAll("#tiles .tile .highlight")
var highlights = document.querySelectorAll(".highlight")
const highlightsObserver = new IntersectionObserver(highlightsObserverCallback, { 
  // threshold: [ 0.3 ], // relative to element
  rootMargin: "100px 0px", // relative to window (or other container)
});
const highlightsObserverHalfH = new IntersectionObserver(highlightsObserverCallback, { 
  rootMargin: "100px 0px "+ -window.innerHeight*0.60 + "px 0px",
});

var currentHighlightsObserver = highlightsObserver; // always start with full height
highlights.forEach(h => { highlightsObserver.observe(h); });

function refreshHighlightObserver () {
  currentHighlightsObserver.disconnect();
  if (breakpointIs("md", "down")) {
    currentHighlightsObserver = highlightsObserverHalfH;
  } else {
    currentHighlightsObserver = highlightsObserver;
  }
  highlights = document.querySelectorAll(".highlight")
  highlights.forEach(h => { currentHighlightsObserver.observe(h); });
}

function tileHighlightsOn (el) {
  var highlights = el.querySelectorAll(".highlight");
  highlights.forEach(h => { highlightOn(h) });
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
  // console.log(highlightEl);

  increaseEasterInteractions();
  var span = document.createElement("span");
  span.textContent = JSON.stringify(h.dataset);
  easterLog(span);
}

function highlightOff (highlightEl) {
  let h = highlightEl
  h.classList.remove("active");
  h.style.left = h.dataset.leftOri;
  h.style.top = h.dataset.topOri;
  h.style.width = h.dataset.widthOri;
  h.style.height = h.dataset.heightOri;
}

function highlightRandomTile () {
  var tiles = document.querySelectorAll(".computer-vision:not([data-detect-count='0'])");
  var index = Math.floor(tiles.length * Math.random());
  var randomEl = tiles[index];
  tileHighlightsOn(randomEl);
}

// -------------------------
// Easter
// -------------------------

var interactions = 0;
var firstAppearance = 1500;
var nextAppearance = firstAppearance;
var every = 50;
var easterFS = 11;
var easterOp = 0.3;
var easterEl = document.getElementById("easter");
function increaseEasterInteractions (amt = 1) {
  interactions += amt;
  if (interactions > nextAppearance) {
    easterFS++;
    easterOp += 0.1;
    if (easterOp > 0.5) { easterOp = Math.random() * 0.3; }
    nextAppearance += every;
    easterEl.style.opacity = easterOp;
  }
  console.log(interactions +"/"+ nextAppearance);
}
function easterLog (node) {
  if (interactions > firstAppearance) {
    if (Math.random < 0.02) {
      var span = document.createElement("span");
      span.textContent = "";
      while (Math.random() < 0.5) {
        span.textContent += `                  `;;
      }
      easterLog(span);
    }
    if (Math.random() < 0.05) {
      node.classList.add("font-asem-s-important");
    }
    easterEl.prepend(node);
  }
}

// -------------------------
// Storyboard
// -------------------------

window.addEventListener('keydown', (e) => {
  increaseEasterInteractions(5);

  // --- c ----------------------------
  // if (e.key == "c") {
  //   clearConsole();
  // }
  // --- p ----------------------------
  if (e.key == "p") {
    var randomLogOptions = {
      trashType: "all",
      forceLogMode: "append",
    };
    console.log("randomLogOptions", randomLogOptions)
    cmain.conciousness.randomLogs(40, randomLogOptions, null);
  }
  // ----------------------------------

});
window.addEventListener('click', (e) => {
  increaseEasterInteractions(5);
});


// Load event if uid from route > controller
if (eventUid) {
  loadEvent(eventUid);
}


</script>

<!-- --------------------------------------- -->
<!-- Titles -->
<!-- --------------------------------------- -->

<div onclick="loadAbout();" class="pointer" id="title-logo"><img src="<?= $kirby->url("assets")?>/images/title-logo.svg" /></div>
<div onclick="loadEvents();" class="pointer" id="title-summerschool"><img src="<?= $kirby->url("assets")?>/images/title-summerschool.svg" /></div>

<?php snippet("footer") ?>
