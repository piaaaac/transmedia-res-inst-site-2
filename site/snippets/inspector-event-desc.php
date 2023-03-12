<?php
/**
 * 
 * @param $event - Kirby page
 * 
 * */

// $sentences = $event->fullDescription()->split(".");
// kill($sentences);

?>

<div class="inspector-event-desc">
  <div class="font-sans-m">
    <?= $event->fullDescription()->kt() ?>
  </div>
  <div class="font-sans-m">
    <a class="pointer" onclick="loadEvents();">All events</a>
  </div>
</div>
