<?php

return [

  // --- Kirby

  "debug" => true,
  "whoops" => true,

  "routes" => require_once 'routes.php',

  // "hooks" => require_once "hooks.php", // currently []
  
  "thumbs" => [
    "presets" => [
      "default" => ["width" => 1024, "quality" => 80],
      // "speaker" => ["crop" => 300, "width" => 300, "height" => 300, "greyscale" => true] /*"blur" => true*/
    ]
  ],

  "pedroborges.meta-tags.default" => function ($page, $site) {

    // default
    $socialTitle = "Summer School of Bits and Atoms";
    $socialDesc = "Transmedia Research Institute";
    $socialImgUrl = $site->url() ."/assets/images/socialcard.jpg";

    return [
      'title'         => $site->title() ." | ". $page->title(),
      'meta' => [
        'description' => $socialDesc,
      ],
      'link' => [
        'canonical'   => $page->url()
      ],
      'og' => [
        'type'        => 'website',
        'title'       => $socialTitle,
        'site_name'   => $socialTitle,
        'image'       => $socialImgUrl,
        'url'         => $page->url(),
        'description' => $socialDesc,
      ],
      'twitter' => [
        'card' => 'summary_large_image', // summary - summary_large_image
        'site' => "",
        'title' => $socialTitle,
        'namespace:image' => $socialImgUrl,
      ],
    ];
  }

];