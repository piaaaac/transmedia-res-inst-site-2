<?php

// -----------------------
// Helper functions
// -----------------------

function randomPadding () {
  $padding = (rand(0, 9) < 5) ? "padding-left: " : "padding-right: ";
  $padding .= (rand(0, 25)) ."%;";
  return $padding;
}

// -----------------------
// Site methods
// -----------------------

Kirby::plugin('my/plugin', [
  'siteMethods' => [

    'organicParagraphsTilde' => function ($text) {
      $textParagraphs = Str::split($text, "~");
      ob_start(); 
      // --- php recording start ----------------------------------------------
      ?>

        <?php foreach ($textParagraphs as $p): ?>
          <p class="text-center" style="<?= randomPadding() ?>"><?= kti($p) ?></p>
        <?php endforeach ?>
        
      <?php 
      // --- php recording end ------------------------------------------------
      return ob_get_clean();
    }

  ]
]);

?>
