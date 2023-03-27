<?php
/**
 * 
 * @param $event - Kirby page
 * 
 * */

// $sentences = $event->fullDescription()->split(".");
// kill($sentences);

?>

<div>
  <div class="font-sans-m">
    <?= $event->fullDescription()->kt() ?>
  </div>
  <div class="font-sans-m text-center mt-3">
    <a class="pointer" onclick="loadEvents();">Program</a>
    &nbsp;
    <a class="pointer" onclick="loadAbout();">About</a>
  </div>
</div>
