<?php
/**
 * 
 * @param $text - Kirby field
 * 
 * */

// function randomPadding () {
//   $padding = (rand(0, 9) < 5) ? "padding-left: " : "padding-right: ";
//   $padding .= (rand(0, 25)) ."%;";
//   return $padding;
// }

function randomAlign () {
  $alignments = ["text-left", "text-center", "text-right"];
  $index = array_rand($alignments);
  return $alignments[$index];
}

// $textParagraphs = $text->split("~");
?>

<div>
  <h2 class="font-sans-l color-uicolor mt-1">
    <p class="<?= randomAlign() ?>">TRANSMEDIA RESEARCH INSTITUTE</p>
    <p class="<?= randomAlign() ?>">Summer School of Bits and Atoms</p>
    <p class="text-center">
      July 3rd to 9th, 2023
      <br />Fano (PU), Italy
    </p>
  </h2>

  <div class="font-sans-m">
    <?= $site->organicParagraphsTilde($text->value()) ?>
  </div>

  <div class="font-sans-m text-center">
    <br />
    <a class="pointer" onclick="loadEvents();">Program</a>
    <!--  
    &nbsp;
    <a class="pointer" onclick="loadAbout();">About</a>
    -->
  </div>
  
  <div class="font-sans-m">
    <br />
    Website: <a href="https://alexpiacentini.com/" target="_blank">Alex Piacentini</a>
  </div>
</div>
