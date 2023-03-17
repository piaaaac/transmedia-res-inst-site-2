<?php
/**
 * 
 * @param $text - Kirby field
 * 
 * */

function randomPadding () {
  $padding = (rand(0, 9) < 5) ? "padding-left: " : "padding-right: ";
  $padding .= (rand(0, 25)) ."%;";
  return $padding;
}

function randomAlign () {
  $alignments = ["text-left", "text-center", "text-right"];
  $index = array_rand($alignments);
  return $alignments[$index];
}

$textParagraphs = $text->split("~");
?>

<div>
  <h2 class="font-sans-l mt-1">
    <p class="<?= randomAlign() ?>">TRANSMEDIA RESEARCH INSTITUTE</p>
    <p class="<?= randomAlign() ?>">SUMMER SCHOOL OF BITS AND ATOMS</p>
    <p class="text-center">
      JULY 3rd to 9th, 2023
      <br />FANO (PU), ITALY
    </p>
  </h2>

  <!--  
  <div class="font-sans-m">
    <?= $text->kt() ?>
  </div>
  -->

  <div class="font-sans-m">
    <?php foreach ($textParagraphs as $p): ?>
      <p class="text-center" style="<?= randomPadding() ?>"><?= $p ?></p>
    <?php endforeach ?>
  </div>

  <!--  
  <div class="font-sans-m">
    <p class="text-center" style="<?= randomPadding() ?>">
      The Transmedia Research Institute is a hybrid environment halfway in between a school, a research lab and a residency program for artists where art meets science, technology&nbsp;and&nbsp;ecology.
    </p>
    <p class="text-center" style="<?= randomPadding() ?>">
      Part online but mostly offline, the Summer School of Bits and Atoms is the place where a transdisciplinary group of artists, scholars, technologists, hackers meet and explore the impact of new technologies and new forms of intelligence on society and culture, focusing in particular on&nbsp;artistic&nbsp;intervention.
    </p>
    <p class="text-center" style="<?= randomPadding() ?>">
      The philosophy of the institute and the summer school is based on the concept of antidisciplinarity, defined by the MIT Media Lab as working in spaces that simply do not fit into any existing academic discipline; it refers to a research that is placed transversally in the white spaces between the disciplines, with particular words, structures&nbsp;and&nbsp;methods.
    </p>
    <p class="text-center" style="<?= randomPadding() ?>">
      Running from Monday, July 3rd to Sunday, July 9th, the format of the summer school spans from 5 days workshops, 2 days workshops (weekend), masterclasses, field trips, performances and a final collective exhibition. Participants will join and contribute to a collective vision of a near future coexistence of&nbsp;humans and&nbsp;more-than-humans.
    </p>
  </div>
  -->

  <div class="font-sans-m">
    <a class="pointer" onclick="loadEvents();">Program</a>
    &nbsp;
    <a class="pointer" onclick="loadAbout();">About</a>
  </div>
</div>
