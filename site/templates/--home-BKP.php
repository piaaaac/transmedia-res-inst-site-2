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

<?php 
/* 
<!-- V1 random -->
<div id="tiles">
  <?php foreach ($images0Shape as $file): 
    $hw = 0.2 + rand(0, 1000)/1000 * 0.7;
    $hh = 0.2 + rand(0, 1000)/1000 * 0.7;
    $hx = rand(0, 1000)/1000 * (1 - $hw);
    $hy = rand(0, 1000)/1000 * (1 - $hh);
    ? >
    <div class="tile">
      <div class="img" style="background-image: url('<?= $file->url() ? >');"></div>
      <div class="highlight"
        data-highlight-x="<?= $hx ? >"
        data-highlight-y="<?= $hy ? >"
        data-highlight-w="<?= $hw ? >"
        data-highlight-h="<?= $hh ? >"
      ></div>
    </div>
  <?php endforeach ? >
</div>
*/ ?>

<!-- V2 from json -->

<div id="body-content">
  <div id="tiles">
    <?php 
    $index = -1;
    // foreach ($images0Shape as $file): 
    
    $fff = new Files();
    $fff->add($images0Shape->slice(0, 60));
    $fff->add($images1Language);
    foreach ($fff as $file): 

      $index++;
      
      // Normalize $probHighlight and apply power^2
      $probHighlight = map($index, 0, $fff->count(), 0, 1);
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
            <!--
            <p>
             <?= $o["label"] ." ". $o["confidence"] ?>
             <?= (rand(0, 100) < $amt * 50) ? randomString(rand(0, $amt * 500), "<br />", 0.03) : "" ?>
            </p>
            -->
          </div>
        <?php endforeach ?>
      </div>
    <?php endforeach ?>
  </div>
</div>

<!-- Spacing -->
<!-- <div class="my-5 py-5"></div><div class="my-5 py-5"></div><div class="my-5 py-5"></div><div class="my-5 py-5"></div> -->
<!-- <div class="my-5 py-5"></div><div class="my-5 py-5"></div><div class="my-5 py-5"></div><div class="my-5 py-5"></div> -->

<!-- --------------------------------------- -->
<!-- Type test -->
<!-- --------------------------------------- -->
<!-- 

<div class="container-fluid">
  <div class="row">
    <div class="col-4">
      <p class="font-sans-s">Lorem ipsum 123000 ATGC</p>
      <p class="font-sans-m">Lorem ipsum 123000 ATGC</p>
      <p class="font-sans-l">Lorem ipsum 123000 ATGC</p>
      <p class="font-sans-xl">Lorem ipsum 123000 ATGC</p>
    </div>
    <div class="col-4">
      <p class="font-asem-s">Lorem ipsum 123000 ATGC</p>
      <p class="font-asem-m">Lorem ipsum 123000 ATGC</p>
      <p class="font-asem-l">Lorem ipsum 123000 ATGC</p>
      <p class="font-asem-xl">Lorem ipsum 123000 ATGC</p>
    </div>
    <div class="col-4">
      <p class="font-mono-s">Lorem ipsum 123000 ATGC</p>
      <p class="font-mono-m">Lorem ipsum 123000 ATGC</p>
      <p class="font-mono-l">Lorem ipsum 123000 ATGC</p>
      <p class="font-mono-xl">Lorem ipsum 123000 ATGC</p>
    </div>
  </div>
</div>

<div class="container-fluid">
  <div class="row">
    <div class="col-12">
      <p>
        <span class="font-sans-s">Lorem ipsum 123000 ATGC</span>
        <span class="font-asem-s">Lorem ipsum 123000 ATGC</span>
        <span class="font-mono-s">Lorem ipsum 123000 ATGC</span>
      <p>
      </p>
        <span class="font-sans-m">Lorem ipsum 123000 ATGC</span>
        <span class="font-asem-m">Lorem ipsum 123000 ATGC</span>
        <span class="font-mono-m">Lorem ipsum 123000 ATGC</span>
      <p>
      </p>
        <span class="font-asem-l">Lorem ipsum 123000 ATGC</span>
        <span class="font-sans-l">Lorem ipsum 123000 ATGC</span>
        <span class="font-mono-l">Lorem ipsum 123000 ATGC</span>
      <p>
      </p>
        <span class="font-mono-xl">Lorem ipsum 123000 ATGC</span>
        <span class="font-sans-xl">Lorem ipsum 123000 ATGC</span>
        <span class="font-asem-xl">Lorem ipsum 123000 ATGC</span>
      </p>
    </div>
  </div>
</div>

--------------------------------------- -->






<!-- --------------------------------------- -->
<!-- Program text -->
<!-- --------------------------------------- -->
<?php /*

<?php 
$events = page("summer-school-2023")->children()->listed();
? >

<div id="program">
  <div class="program-asemic">
    <div class="container-fluid">
      <div class="row">
        <div class="col-8 offset-2">
          <?php 
          $index = -1;
          foreach ($events as $event) { 
            $index++;
            $asemicProb = pow(map($index, 0, $events->count(), 1, 0.75), 2);
            snippet("programItem", ["event" => $event, "asemicProb" => $asemicProb]); 
          }
          foreach ($events as $event) { 
            $index++;
            $asemicProb = pow(map($index, 0, $events->count(), 0.75, 0.5), 2);
            snippet("programItem", ["event" => $event, "asemicProb" => $asemicProb]); 
          }
          foreach ($events as $event) { 
            $index++;
            $asemicProb = pow(map($index, 0, $events->count(), 0.5, 0.25), 2);
            snippet("programItem", ["event" => $event, "asemicProb" => $asemicProb]); 
          }
          foreach ($events as $event) { 
            $index++;
            $asemicProb = pow(map($index, 0, $events->count(), 0.25, 0), 2);
            snippet("programItem", ["event" => $event, "asemicProb" => $asemicProb]); 
          }
          ? >
        </div>
      </div>
    </div>
  </div>
  <div class="program">
    <div class="container-fluid">
      <div class="row">
        <div class="col-8 offset-2">
          <?php foreach ($events as $event) { snippet("programItem", ["event" => $event]); } ? >
          <?php foreach ($events as $event) { snippet("programItem", ["event" => $event]); } ? >
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Spacing -->
<div class="my-5 py-5"></div><div class="my-5 py-5"></div><div class="my-5 py-5"></div><div class="my-5 py-5"></div>
<div class="my-5 py-5"></div><div class="my-5 py-5"></div><div class="my-5 py-5"></div><div class="my-5 py-5"></div>

*/ ?>


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
<!-- Inspector -->
<!-- --------------------------------------- -->

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
  console.log(`Key "${e.key}" pressed`);
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
// HIGHLIGHTS
// ----------------------------------------

var highlights = document.querySelectorAll("#tiles .tile .highlight")
const observer = new IntersectionObserver(entries => {
  entries.forEach(entry => {
    if (entry.isIntersecting) {
      console.log(entry)
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
  console.log(highlightEl);
}

function highlightOff (highlightEl) {
  let h = highlightEl
  h.classList.remove("active");
  // h.style.left = (h.dataset.highlightX + h.dataset.highlightW/2) * 100 +"%";
  // h.style.top = (h.dataset.highlightY + h.dataset.highlightH/2) * 100 +"%";
  // h.style.width = "0%";
  // h.style.height = "0%";
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

</script>


<?php snippet("footer") ?>
