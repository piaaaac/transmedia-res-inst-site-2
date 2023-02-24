<?php


// --- V2

return [

  [
    'pattern' => '/video-modal/(:any)',
    'action'  => function($vimeoId) {
      return snippet("bits/video-player", ["vimeoId" => $vimeoId], true);
    }
  ],

  [
    'pattern' => '/audio-modal/(:any)',
    'action'  => function($itemIndex) {
      return snippet("bits/audio-player", ["itemIndex" => $itemIndex], true);
    }
  ],




  /**
   * @param $encodedImgUrl (string) relative to $site->url()
   * 
   * Example:
   * dither/media/pages/speakers/dame-wendy-hall/0ef7394063-1639416861/bitmap-copy-300x300-crop-300-bw-q70.jpg
   * 
   * */
  [
    'pattern' => '/dither/(:all)',
    'action'  => function($imgUrl) {
      return new Response(dither($imgUrl), 'image/png');
    }
  ],




  // LOW route
  // ---------

  [
    'pattern' => '/low',
    'action'  => function() {
      Config::set("low-energy-mode", true);
      return site()->visit(page("home"));
    }
  ],

  [
    'pattern' => '/low/(:all)',
    'action'  => function($uid) {
      Config::set("low-energy-mode", true);
      $page = page($uid);
      if (!$page) $page = site()->errorPage();
      return site()->visit($page);
    }
  ],

  // REG route
  // ---------

  [
    'pattern' => '/reg',
    'action'  => function() {
      Config::set("low-energy-mode", false);
      return site()->visit(page("home"));
    }
  ],

  [
    'pattern' => '/reg/(:all)',
    'action'  => function($uid) {
      Config::set("low-energy-mode", false);
      $page = page($uid);
      if (!$page) $page = site()->errorPage();
      return site()->visit($page);
    }
  ],

  // when no mode is specified
  // -------------------------

  [
    'pattern' => '/(:all)',
    'action'  => function($uid) {
      $low = Config::get("low-energy-mode");
      $base = $low ? "/low/" : "/reg/";
      go($base . $uid);
    }
  ],
  



]; 



// --- V1

// return [
//   [
//     'pattern' => '/(:all)',
//     'action'  => function($uid) {
//       Config::set("low-energy-mode", false);
//       $this->next();
//     }
//   ],
  
//   [
//     'pattern' => '/low',
//     'action'  => function() {
//       Config::set("low-energy-mode", true);
//       return site()->visit(page("home"));
//     }
//   ],

//   [
//     'pattern' => '/low/(:all)',
//     'action'  => function($uid) {
//       Config::set("low-energy-mode", true);
//       $page = page($uid);
//       if (!$page) $page = page('blog/' . $uid);
//       if (!$page) $page = site()->errorPage();
//       return site()->visit($page);
//     }
//   ] 
// ]; 
