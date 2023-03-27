<?php

/**
 *  @param $probHighlight       - float number 0-1
 *  @param $amtHighlightTexts   - float number 0-1
 *  @param $objects             - array
 * 
 * */

?>

<div class="computer-vision-highlights" data-detect-count="<?= count($objects) ?>">

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
      <p>
         <!-- <?= $o["label"] ." ". $o["confidence"] ?> -->
         <!-- <?= (rand(0, 100) < $amt * 50) ? randomString(rand(0, $amt * 500), "<br />", 0.03) : "" ?> -->
      </p>
    </div>
  <?php endforeach ?>
</div>
