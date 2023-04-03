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
    <!-- <?= $event->fullDescription()->kt() ?> -->
    <?= $site->organicParagraphsTilde($event->fullDescription()->value()) ?>
  </div>
  <div class="font-sans-m text-center">
    <br />
    <a class="pointer" onclick="loadEvents();">Program</a>
    &nbsp;
    <a class="pointer" onclick="loadAbout();">About</a>
  </div>
</div>
