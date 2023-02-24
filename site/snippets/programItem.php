<?php
/**
 * 
 * @param $event - Kirby page
 * @param $asemicProb - 0-1
 * 
 * */

$ap = (isset($asemicProb)) ? $asemicProb : 0;

if(!function_exists("fontClassName")) {
  function fontClassName ($asemicProb) {
    return (rand(0, 1000)/1000 < $asemicProb) ? "font-asem-xl" : "font-sans-xl";
  }
}

// if(!function_exists("makeSpans")) {
//   function makeSpans ($str) {
//     return (rand(0, 1000)/1000 < $asemicProb) ? "font-asem-xl" : "font-sans-xl";
//   }
// }


?>

<div class="program-item">
  <p>
    <span class="<?= fontClassName($ap) ?>"><?= $event->title()->kti() ?></span>
    <br />
    <span class="<?= fontClassName($ap) ?>"><?= $event->names()->kti() ?></span>
    <br />
    <span class="<?= fontClassName($ap) ?>"><?= $event->dateText()->kti() ?></span>
  </p>
  <p>
    <!-- <span class="<?= fontClassName($ap) ?>"><?= $event->shortDescription()->kti() ?></span> -->
  </p>
  <!-- <p><?= $event->fullDescription()->kti() ?></p> -->
</div>
