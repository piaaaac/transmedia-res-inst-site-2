<?php


// --- V2

return [


  [
    "pattern" => "summer-school-2023/(:any)",
    "page" => "home",
    "action"  => function ($slug) {

      // ignore route if it's the json representation
      if (Str::contains($slug, 'json')) {
        $this->next();
      }

      $uid = "null";
      if ($event = page("summer-school-2023/". $slug)) {
        $uid = $event->uid() ;
      }
      $data = [
        "eventUid" => $uid
      ];
      return page("home")->render($data);
    }
  ],

  // [
  //   'pattern' => 'summer-school-2023/(:any)',
  //   'action'  => function($slug) {
  //     if (site()->find('summer-school-2023/'. $slug)) {
  //       $p = page('summer-school-2023/'. $slug);
  //       return '<html><body>Page found<br /><br />'. print_r($p, true) .'</body></html>';
  //     } else {
  //       return '<html><body>Page not found.</body></html>';
  //     }
  //   }
  // ],

];