<script>
/*
// https://stackoverflow.com/a/57795495/2501713
if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
  // dark mode
}

// move threejs canvas
var c = document.querySelector("canvas")
c.style.transform = "translateX(-500px)"
'translateX(-500px)'

*/
</script>





<?php
/**
 * @param $trash_fragments from controller
 * 
 * */

$images0Shape = page("landing/shape")->files()->shuffle();
$images1Language = page("landing/language")->files()->shuffle();
$filedata = file_get_contents($kirby->root('assets') .'/data/5-merge1-2-4-objectsDetected.json');
$allDetectedObjects = json_decode($filedata, true);

// Later
// $imagesPerRow = 10;
// make wrappers each row to minimize the n of observed elements


$trashImagesTestFiles = page("summer-school-2023")->children()->listed()->shuffle()->first()->files();
$trashImagesTestUrls = [];
foreach ($trashImagesTestFiles as $img) {
  $trashImagesTestUrls[] = $img->url();
}
?>

<?php
// kill($trash_fragments);
?>

<?php snippet("header") ?>

<!-- --------------------------------------- -->
<!-- Tiles -->
<!-- --------------------------------------- -->


<!-- V2 from json -->

<?php
$stepMarkers = [
  ["advancement" => 0.2, "id" => "appear"],
  ["advancement" => 0.3, "id" => "flick"],
  ["advancement" => 0.4, "id" => "flick"],
  ["advancement" => 0.5, "id" => "console"],
];

function marker ($id) {
  return "<div class='marker' data-id='$id'></div>";
}
?>


<div id="body-content">
  <div id="tiles">
    <?php 
    $index = -1;
    $nextAdvancementIndex = 0;
    $fff = new Files();
    $fff->add($images0Shape->slice(0, 80));
    $fff->add($images1Language);
    foreach ($fff as $file): 
      $index++;
      $normAdvancement = map($index, 0, $fff->count(), 0, 1);
      $probHighlight = map($normAdvancement, 0, 1, 0.5, 1);
      $probHighlight = pow($probHighlight, 2);
      $amtHighlightTexts = rand(0, 1000)/1000;
      $thisImageFilename = $file->filename();
      $objects = A::filter($allDetectedObjects, function ($val, $key) use ($thisImageFilename) {
        return $val["imageFilename"] === $thisImageFilename;
      });
      ?>
      <div class="tile" data-detect-count="<?= count($objects) ?>">
        <div class="img" style="background-image: url('<?= $file->url() ?>');"></div>
        <?php foreach ($objects as $o):
          $amt = $amtHighlightTexts;
          $startX = $o["normX"] + $o["normW"]/2;
          $startY = $o["normY"] + $o["normH"]/2;
            // animate from center // style="left: <?= $startX * 100 ? >%; top: <?= $startY * 100 ? >%;"
            // animate from large // style="left: 0; top: 0; width: 100%; height: 100%;"
          ?>
          <div class="highlight"
            data-highlight-x="<?= $o["normX"] ?>"
            data-highlight-y="<?= $o["normY"] ?>"
            data-highlight-w="<?= $o["normW"] ?>"
            data-highlight-h="<?= $o["normH"] ?>"
            data-highlight-probability="<?= $probHighlight ?>"
            style="left: -10%; top: -10%; width: 110%; height: 110%;"
          >
            <!-- asemic labels -->
            <!--<p>
             <?= $o["label"] ." ". $o["confidence"] ?>
             <?= (rand(0, 100) < $amt * 50) ? randomString(rand(0, $amt * 500), "<br />", 0.03) : "" ?>
            </p>-->
          </div>
        <?php endforeach ?>
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
<!-- Trash columns -->
<!-- --------------------------------------- -->

<div id="trash-columns">
  <div class="container-fluid h-100">
    <div class="row h-100">
      <div class="col-2 h-100">
        <div class="trash-column h-100"><?= $trash_fragments[array_rand($trash_fragments)] ?></div>
      </div>
      <div class="col-2 h-100">
        <div class="trash-column h-100"><?= $trash_fragments[array_rand($trash_fragments)] ?></div>
      </div>
      <div class="col-2 h-100">
        <div class="trash-column h-100"><?= $trash_fragments[array_rand($trash_fragments)] ?></div>
      </div>
      <div class="col-2 h-100">
        <div class="trash-column h-100"><?= $trash_fragments[array_rand($trash_fragments)] ?></div>
      </div>
      <div class="col-2 h-100">
        <div class="trash-column h-100"><?= $trash_fragments[array_rand($trash_fragments)] ?></div>
      </div>
      <div class="col-2 h-100">
        <div class="trash-column h-100"><?= $trash_fragments[array_rand($trash_fragments)] ?></div>
      </div>
    </div>
  </div>
</div>


<!-- --------------------------------------- -->
<!-- Create js variables -->
<!-- Inspector, Creature -->
<!-- --------------------------------------- -->

<script>
var c3, cc, cmain;
</script>

<?php snippet("inspector") ?>
<?php snippet("creature") ?>

<!-- --------------------------------------- -->
<!-- Titles -->
<!-- --------------------------------------- -->

<div onclick="toggleInspector();" id="title-logo"><img src="<?= $kirby->url("assets")?>/images/title-logo.svg" /></div>
<div onclick="creatureRefresh();" id="title-summerschool"><img src="<?= $kirby->url("assets")?>/images/title-summerschool.svg" /></div>

<script>

var trashFragments = <?= json_encode($trash_fragments) ?>;
var trashImagesTestUrls = <?= json_encode($trashImagesTestUrls) ?>;

// ----------------------------------------
// EVENTS
// ----------------------------------------

window.addEventListener('keydown', (e) => {
  // console.log(`Key "${e.key}" pressed`);
  if (e.key == "Enter") {
    // highlightRandomTile();
    injectTrash();
  }
  if (e.key == "Shift") {
    injectImage();
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
    var input = document.getElementById("console-input")
    input.focus()  
  } else {
    document.body.dataset.inspectorOpen = "false";
  }
}

// ----------------------------------------
// TRASH COLUMNS
// ----------------------------------------

function injectTrash () {
  var trashCols = document.querySelectorAll(".trash-column")
  trashCols.forEach(col => {
    if (Math.random() < 0.7) {
      var fragment = "";
      if (Math.random() < 0.3) {
        fragment = trashFragments[Math.floor(Math.random() * trashFragments.length - 1)]
      } else {
        fragment = "\u00A0".repeat(Math.floor(Math.random() * 300))
      }

      var div = document.createElement("p");
      div.append(fragment);
      
      var probAsemicFont = 0.5;
      var cl = (Math.random() < probAsemicFont) ? "font-asem-s" : "font-mono-s";
      div.classList.add(cl);

      col.prepend(div);
      var duration = Math.random() * 1000 + 200;
      $(div).hide().slideDown(duration, "linear", function () {
        while (col.childNodes.length > 40) {
          col.removeChild(col.lastChild);
        }
      });
    }
  });
}

function injectImage () {
  var trashCols = document.querySelectorAll(".trash-column")
  var col = trashCols[Math.floor(Math.random() * trashCols.length)];
  var img = document.createElement("img");
  img.src = trashImagesTestUrls[Math.floor(Math.random() * trashImagesTestUrls.length)];
  img.classList.add("img-fluid");
  col.prepend(img);
  var duration = 300;
  $(img).hide().slideDown(duration, "linear", function () {
    while (col.childNodes.length > 40) {
      col.removeChild(col.lastChild);
    }
  });
}


// ----------------------------------------
// PROGRAM INFINITE SCROLL
// ----------------------------------------

// "program-copy-1"
// "program-copy-2"
// const observer = new IntersectionObserver(entries => {
//   entries.forEach(entry => {
//     if (entry.isIntersecting) {
//       console.log(entry)
//     }
//   })
// }, { 
//   // options
//   // threshold: [ 0.3 ], // relative to element
//   rootMargin: "100px 0px", // relative to window (or other container)
// })
// highlights.forEach(h => { observer.observe(h); });


// ----------------------------------------
// OBJECT
// ----------------------------------------

cmain = new CreatureMain();

console.log("cmain created")

function CreatureMain () {

  this.conciousness = cc;
  this.creature3d = c3;
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

    "appear": function (self) {
      self.creature3d.flicker();
    },

    "flick": function (self) {
      self.creature3d.flicker();
    },

    "console": function (self) {
      self.conciousness.actions.start();
      self.creature3d.resize("small");
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
  rootMargin: "-45% 0px", // make window smaller vertically, observing only a central strip of the window
})
markers.forEach(h => { observerMarkers.observe(h); });








// ----------------------------------------
// HIGHLIGHTS
// ----------------------------------------

// var highlights = document.querySelectorAll("#tiles .tile .highlight")
var highlights = document.querySelectorAll(".highlight")
const observer = new IntersectionObserver(entries => {
  entries.forEach(entry => {
    if (entry.isIntersecting) {
      // console.log(entry)
      if (Math.random() < entry.target.dataset.highlightProbability) { // eg 0.3
        setTimeout(() => { 
          highlightOn(entry.target)
          setTimeout(() => {
            highlightOff(entry.target) 
          }, 2500); // duration on screen
        }, Math.random() * 1500); // time on
      }
    }
  })
}, { 
  // options
  // threshold: [ 0.3 ], // relative to element
  rootMargin: "100px 0px", // relative to window (or other container)
})
highlights.forEach(h => { observer.observe(h); });

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
  var tiles = document.querySelectorAll("#tiles .tile:not([data-detect-count='0'])");
  var index = Math.floor(tiles.length * Math.random());
  var randomEl = tiles[index];
  tileHighlightsOn(randomEl);
}

// -------------------------
// Storyboard
// -------------------------

window.addEventListener('keydown', (e) => {
  if (e.key == "a") { log("pressed a")}
  if (e.key == "s") { cc.actions.start()}
  if (e.key == "a") { log("pressed a")}
});


</script>


<?php snippet("footer") ?>
