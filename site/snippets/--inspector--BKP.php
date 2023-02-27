<?php
$events = page("summer-school-2023")->children()->listed();

// $creatureSourceCode = file_get_contents($kirby->root('snippets') ."/creature.php");
// $sourcesTabContent = Html::encode("aslfkcaeiv <span>ikiufg</span>");
// $sourcesTabContent = Html::encode($creatureSourceCode);

// $indexHtml = file_get_contents($kirby->root('snippets') ."/inspector.php");
// $elementsTabContent = Html::encode($indexHtml);

?>

<div id="inspector" data-tab="console">
  
  <div class="header">
    <a class="tab icon"><img src="<?= $kirby->url("assets") ?>/images/icon-console-select.svg" /></a>
    <span class="separator"></span>
    <a class="tab" onclick="toTab('elements');" data-name="elements">Elements</a>
    <a class="tab" onclick="toTab('console');" data-name="console">Console</a>
    <a class="tab" onclick="toTab('sources');" data-name="sources">Sources</a>
    <a class="tab" onclick="toTab('network');" data-name="network">Network</a>
  </div>

  <!-- elements -->
  <div class="content" id="inspector-content-elements">
    <div class="item c-log">
      <pre><code id="code-elements" class="language-html"><?php snippet("inspector-code-elements") ?></code></pre>
    </div>
  </div>
  
  <!-- network -->
  <div class="content" id="inspector-content-network"></div>
  
  <!-- sources -->
  <div class="content" id="inspector-content-sources">
    <div class="item c-log">
      <span class="src-file">…/src/creature.js</span>
      <pre><code id="code-sources" class="language-javascript"><?php snippet("inspector-code-sources") ?></code></pre>
    </div>
  </div>
  
  <!-- console -->
  <div class="content" id="inspector-content-console">
    
    <!--  
    <div class="item c-alert">Alert</div>    
    <div class="item c-error">
      <span class="src-file">phptest.phptest.phptest.php:234</span>
      Error
    </div>
    <div class="item c-error">
      <span class="src-file">phptest.phptest.phptest.php:234</span>
      Error assets/images/0-shape/ industrial bacteria penetrating and digesting each other, in the style of a technical drawing_1.jpeg [1] => assets/images/0-ape/Amorphic creature made of darkness_4.jpeg [19] => assets/images/0-shape/TRANSMEDIA RESEARCH INSTITUTE written on an alien organ_1.jpeg [20] => assets/images/0-shape/TRANSMEDIA RESEARCH INSTITUTE 
    </div>
    <div class="item c-error">Error</div>
    <div class="item c-log">Log</div>
    <div class="item c-log">lorem ipsum ~</div>
    <div class="item c-log">Log dei log</div>
    
<?php $event = $events->nth(0) ?>

    <div class="item c-content">
      <?php snippet("inspector-event-preview", ["event" => $event]) ?>
    </div>

    <div class="item c-alert">Alert</div>    
    <div class="item c-error">Error</div>
    <div class="item c-error">Error</div>

    <div class="item c-content">
      <?php snippet("inspector-event-desc", ["event" => $event]) ?>
    </div>

    <div class="item c-error">Error</div>
    <div class="item c-error">Error</div>
    <div class="item c-error">Error</div>
    <div class="item c-log">Log</div>
    <div class="item c-log">lorem ipsum ~</div>
    <div class="item c-log">Array ( [0] => assets/images/0-shape/ industrial bacteria penetrating and digesting each other, in the style of a technical drawing_1.jpeg [1] => assets/images/0-ape/Amorphic creature made of darkness_4.jpeg [19] => assets/images/0-shape/TRANSMEDIA RESEARCH INSTITUTE written on an alien organ_1.jpeg [20] => assets/images/0-shape/TRANSMEDIA RESEARCH INSTITUTE written on an animal organ_1.jpeg [21] => assets/images/0-shape/TRANSMEDIA RESEARCH INSTITUTE written on an animal organ_2.jpeg [22] => a</div>
    
<?php $event = $events->nth(1) ?>

    <div class="item c-content">
      <?php snippet("inspector-event-preview", ["event" => $event]) ?>
    </div>

    <div class="item c-alert">Alert</div>    
    <div class="item c-alert">_ga=GA1.1.1698373462.1622557363; PHPSESSID=kigbl2an1gla2jrbn2cer1ovrl; _ga_RZW91RHY42=GS1.1.1647526969.1.1.1647526979.50; _clck=179a0d4|1|f12|0; _ga_J08H1B8RKX=GS1.1.1652784376.3.1.1652784407.29; _iub_cs-22365185=%7B%22timestamp%22%3A%222022-05-16T21%3A24%3A44.422Z%22%2C%22version%22%3A%221.38.0%22%2C%22purposes%22%3A%7B%221%22%3Atrue%2C%222%22%3Afalse%2C%223%22%3Afalse%2C%224%22%3Afalse%2C%225%22%3Afalse%7D%2C%22id%22%3A22365185%2C%22cons%22%3A%7B%22rand%22%3A%22787fb1%22%7D%7D; usprivacy=%7B%22uspString%22%3A%221YY-%22%2C%22firstAcknowledgeDate%22%3A%222022-04-30T10%3A45%3A12.887Z%22%2C%22optOutDate%22%3A%222022-04-30T10%3A45%3A12.887Z%22%7D; _ga_GS4KD64V2Z=GS1.1.1657730090.19.0.1657730090.0; _y=13778cc4-F438-48E4-E1A0-7F2603532D34; _shopify_y=13778cc4-F438-48E4-E1A0-7F2603532D34; _ga_TCJEQRCH5Z=GS1.1.1663322096.18.1.1663322767.0.0.0; kirby_session=dhrq77v2odpgthubfjp3jg8iba; _ga_QE6MD5CYFH=GS1.1.1675881266.41.0.1675881266.0.0.0</div>
    <div class="item c-error">Error</div>

    <div class="item c-content">
      <?php snippet("inspector-event-desc", ["event" => $event]) ?>
    </div>

    <div class="item c-log">Array ( [0] => assets/images/0-shape/ industrial bacteria penetrating and digesting each other, in the style of a technical drawing_1.jpeg [1] => assets/images/0-shape/ industrial bacteria penetrating and digesting each other, in the style of a technical drawing_2.jpeg [2] => assets/images/0-shape/ industrial bacteria penetrating and digesting each other, in the style of encyclopedia illustration_1.jpeg [3] => assets/images/0-shape/ industrial organisms penetrating and digesting each other, in the style of a technical drawing_1.jpeg [4] => assets/images/0-shape/ industrial organisms penetrating and digesting each other, in the style of a technical drawing_2.jpeg [5] => assets/images/0-shape/ industrial organisms penetrating and digesting each other, in the style of a technical drawing_3.jpeg [6] => assets/images/0-shape/ industrial organisms penetrating and digesting each other, in the style of a technical drawing_4.jpeg [7] => assets/images/0-shape/ industrial organisms penetrating and digesting each other, in the style of a technical drawing_5.jpeg [8] => assets/images/0-shape/ industrial organisms penetrating and digesting each other, in the style of a technical drawing_6.jpeg [9] => assets/images/0-shape/ industrial organisms penetrating and digesting each other, in the style of a technical drawing_7.jpeg [10] => assets/images/0-shape/ industrial organisms penetrating and digesting each other_1.jpeg [11] => assets/images/0-shape/ tubular organisms absorbing each other_1.jpeg [12] => assets/images/0-shape/3d modeled animal organs inside of a computer_2.jpeg [13] => assets/images/0-shape/3d modeled human organs inside of a computer_1.jpeg [14] => assets/images/0-shape/3d modeled organs made of technology_1.jpeg [15] => assets/images/0-shape/Amorphic creature made of darkness_1.jpeg [16] => assets/images/0-shape/Amorphic creature made of darkness_2.jpeg [17] => assets/images/0-shape/Amorphic creature made of darkness_3.jpeg [18] => assets/images/0-shape/Amorphic creature made of darkness_4.jpeg [19] => assets/images/0-shape/TRANSMEDIA RESEARCH INSTITUTE written on an alien organ_1.jpeg [20] => assets/images/0-shape/TRANSMEDIA RESEARCH INSTITUTE written on an animal organ_1.jpeg [21] => assets/images/0-shape/TRANSMEDIA RESEARCH INSTITUTE written on an animal organ_2.jpeg [22] => a</div>

    <div class="item c-content">
      <br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br />
    </div>
    <div class="item c-alert">Alert</div>    
    
    <div class="item c-error">industrial bacteria penetrating and digesting each other, in the style of a technical drawing_1.jpeg</div>
    <div class="item c-error">industrial bacteria penetrating and digesting each other, in the style of a technical drawing_2.jpeg</div>
    <div class="item c-error">industrial bacteria penetrating and digesting each other, in the style of encyclopedia illustration_1.jpeg</div>
    <div class="item c-error">industrial organisms penetrating and digesting each other_1.jpeg</div>
    <div class="item c-error">industrial organisms penetrating and digesting each other, in the style of a technical drawing_1.jpeg</div>
    <div class="item c-error">industrial organisms penetrating and digesting each other, in the style of a technical drawing_2.jpeg</div>
    <div class="item c-error">industrial organisms penetrating and digesting each other, in the style of a technical drawing_3.jpeg</div>
    <div class="item c-error">industrial organisms penetrating and digesting each other, in the style of a technical drawing_4.jpeg</div>
    <div class="item c-error">industrial organisms penetrating and digesting each other, in the style of a technical drawing_5.jpeg</div>
    <div class="item c-error">industrial organisms penetrating and digesting each other, in the style of a technical drawing_6.jpeg</div>
    <div class="item c-error">industrial organisms penetrating and digesting each other, in the style of a technical drawing_7.jpeg</div>
    <div class="item c-error">tubular organisms absorbing each other_1.jpeg</div>
    <div class="item c-error">3d modeled animal organs inside of a computer_2.jpeg</div>
    <div class="item c-error">3d modeled human organs inside of a computer_1.jpeg</div>
    <div class="item c-error">3d modeled organs made of technology_1.jpeg</div>
    <div class="item c-error">a chimera created with a lion, a goat and a snake, in the style of albrecht durer etching_1.jpeg</div>
    <div class="item c-error">a cloud of synthetic organs and metal robotic arms_1.jpeg</div>
    <div class="item c-error">a cloud of synthetic organs and metal robotic arms_2.jpeg</div>
    <div class="item c-error">a one-bone creature born from melting_1.jpeg</div>
    <div class="item c-error">a rotting cell made of corrosive digesting melt_1.jpeg</div>
    <div class="item c-error">a seat made with bones_1.jpeg</div>
    <div class="item c-error">a temple made of bone inside of a human organ_1.jpeg</div>
    <div class="item c-error">a temple made of bone inside of a human organ_2.jpeg</div>
    <div class="item c-error">a temple made of bone inside of a human organ_3.jpeg</div>
    <div class="item c-alert">alien creature with the shape of a wardrobe, with wings and metal, inside a white temple_1.jpeg</div>
    <div class="item c-alert">alien creature with the shape of a wardrobe, with wings and metal, inside a white temple, in the style of codex seraphinianus_3.jpeg</div>
    <div class="item c-alert">alien creature with the shape of an industrial wardrobe, with tubes and metal, inside a white temple, in the style of 1960 movie_1.jpeg</div>
    <div class="item c-alert">alien creature with the shape of an industrial wardrobe, with tubes and metal, inside a white temple, in the style of 1960 movie_2.jpeg</div>
    <div class="item c-alert">alien creature with the shape of an industrial wardrobe, with tubes and metal, inside a white temple, in the style of 1960 movie_3.jpeg</div>
    <div class="item c-alert">alien jelly egg covered by sticky translucent fluid_1.jpeg</div>
    <div class="item c-alert">alien jelly egg covered by sticky translucent fluid_2.jpeg</div>
    <div class="item c-alert">alien jelly egg covered by sticky translucent fluid, with its shell broken and a black creature emerging_1.jpeg</div>
    <div class="item c-alert">alien organism coming out of a skin-colored pink plant that gives birth, all immersed in glossy fluids_1.jpeg</div>
    <div class="item c-alert">alien organism coming out of a skin-colored pink plant that gives birth, all immersed in glossy fluids_2.jpeg</div>
    <div class="item c-alert">alien organism coming out of a skin-colored pink plant that gives birth, all immersed in glossy fluids_3.jpeg</div>
    <div class="item c-alert">alien organism coming out of a skin-colored pink plant that gives birth, all immersed in glossy fluids_4.jpeg</div>
    <div class="item c-alert">alien organism coming out of a skin-colored pink plant that gives birth, all immersed in glossy fluids_5.jpeg</div>
    <div class="item c-alert">alien organism coming out of a skin-colored pink plant that gives birth, all immersed in glossy fluids_6.jpeg</div>
    <div class="item c-alert">alien organism coming out of a skin-colored pink plant that gives birth, all immersed in glossy fluids_7.jpeg</div>

    <div class="item c-content">
      <br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br />
    </div>

    <?php $event = $events->nth(0) ?>
    <div class="item c-content">
      <?php snippet("inspector-event-preview", ["event" => $event]) ?>
    </div>
    <div class="item c-content">
      <?php snippet("inspector-event-desc", ["event" => $event]) ?>
    </div>
    
    <?php $event = $events->nth(1) ?>
    <div class="item c-content">
      <?php snippet("inspector-event-preview", ["event" => $event]) ?>
    </div>
    <div class="item c-content">
      <?php snippet("inspector-event-desc", ["event" => $event]) ?>
    </div>

    -->

    <div id="input-area" onclick="focusInput();">
      <input type="text" id="console-input"  />
    </div>

  </div>
  <!-- .content end -->


</div>

<script>

  // -----------------------------
  // Vars
  // -----------------------------

  var bodyContent = document.getElementById("body-content") 
  var cc = new CreatureConciousness();
  
  // -----------------------------
  // Setup stuff
  // -----------------------------

  // bind src file names' click event
  $(".src-file").click(() => { handleSrcFileClick(); })

  // --- add page's html to inspector's element tab
  // document.querySelector("#inspector-content-elements pre").textContent = $('html').html();
  // document.querySelector("#inspector-content-elements pre code").textContent = escapeHtml($('html').html());

  // var html = $('html').html();
  // document.querySelector("#inspector-content-elements pre code").textContent = escapeHtml("aslfkcaeiv <span>ikiufg</span>");
  // debugger

  // Highlight code blocks
  document.addEventListener('DOMContentLoaded', (event) => {
    var codeBlocks = [];
    codeBlocks.push(document.querySelector("#code-elements"));
    codeBlocks.push(document.querySelector("#code-sources"));
    codeBlocks.forEach((el) => {
      hljs.highlightElement(el);
    });
  });

  // -----------------------------
  // Functions
  // -----------------------------

  function focusInput () {
    var input = document.getElementById("console-input")
    input.focus()
  }

  function loadEvent (uid) {
    var url = "<?= $site->url() ?>/summer-school-2023/"+ uid +".json";

    fetch(url).then(response => {
        return response.json();
      }).then(jsonData => {
        bodyContent.classList.add("blur")
        setTimeout(() => { 
          bodyContent.textContent = ""
          handleReceivedData(jsonData)
          handleReceivedData(jsonData)
        }, 1600);
      }).catch(err => {
        console.log("Error fetching page:", err);
      });

  }

  function handleReceivedData (jsonData) {
    console.log(jsonData);

    jsonData.imagesHighlights.forEach((d, i) => {
      setTimeout(() => {
        log(JSON.stringify(d));
      }, 100*i);
    })

    jsonData.images.forEach((imgUrl, i) => {
      var img = document.createElement("img")
      img.src = imgUrl
      img.classList.add("event-image")
      if (i == 0) {
        // img.scrollIntoView(true)
        window.scrollTo(0, 0);
        bodyContent.classList.remove("blur")
      }
      bodyContent.appendChild(img)
    })
  }

  function log (data, logOptions) {
    var defaults = {
      type: "log", // normal|alert|error
      content: "textNode", // textNode|html
      srcFile: "auto", // string "filename:line"
      mode: "append", // append|replaceLast
    };
    Object.keys(logOptions).forEach(key => logOptions[key] === undefined && delete logOptions[key]) // remove undefined (via https://stackoverflow.com/a/38340374/2501713)
    var options = Object.assign({}, defaults, logOptions);
    if (options.srcFile == "auto") {
      var d = new Date();
      var fakeVM = "VM"+ d.getDate();
      fakeVM += (d.getMinutes() < 10) ? "04" : "8";
      fakeVM += d.getMinutes() +":1";
      options.srcFile = fakeVM;
    }

    console.log("check", data, logOptions, options)
    
    var typeMap = {
      content: "c-content",
      log: "c-log",
      alert: "c-alert",
      error: "c-error",
      userinput: "c-log-user-input",
    }
    
    toTab("console")

    var typeClass = typeMap[options.type];
    var item = document.createElement("div");
    item.classList.add("item");
    item.classList.add(typeClass);

    var srcFile = document.createElement("span")
    srcFile.classList.add("src-file")
    srcFile.textContent = options.srcFile
    srcFile.onclick = () => { handleSrcFileClick(); }
    item.appendChild(srcFile)

    var child;
    if (options.content == "textNode") {
      child = document.createTextNode(data)
    } else if (options.content == "html") {
      child = document.createElement("span")
      child.innerHTML = data
    }
    item.appendChild(child)

    var contentDiv = document.querySelector("#inspector #inspector-content-console")
    var inputArea = document.querySelector("#input-area")
    if (options.mode == "append") {
      contentDiv.insertBefore(item, inputArea)
    } else if (options.mode == "replaceLast") {
      contentDiv.replaceChild(item, inputArea.previousSibling)
    }

    contentDiv.scrollTo(0, 1000000)
    focusInput()
  }

  function handleConsoleInput (command) {
    if (command.trim() == "") {
      return;
    }
    log(command, {type: "userinput", srcFile: ""})
    cc.saySentence("dont_understand", command);
  }

  function toTab (name) {
    document.getElementById("inspector").dataset.tab = name;
    if (name === "console") {
      focusInput()
    }
  }

  function handleSrcFileClick () {
    toTab("sources")
  }

  // via https://www.educative.io/answers/how-to-escape-unescape-html-characters-in-string-in-javascript
  // function escape (htmlStr) {
  //   return htmlStr.replace(/&/g, "&amp;")
  //     .replace(/</g, "&lt;")
  //     .replace(/>/g, "&gt;")
  //     .replace(/"/g, "&quot;")
  //     .replace(/'/g, "&#39;");        
  // }

  // via https://stackoverflow.com/a/18750001
  // function escapeHtml (rawStr) {
  //   var encodedStr = rawStr.replace(/[\u00A0-\u9999<>\&]/g, function(i) {
  //     return '&#'+i.charCodeAt(0)+';';
  //   });
  // }

  // via https://stackoverflow.com/a/18750001
  function escapeHtml (html) {
    var escaped = html.replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;").replace(/"/g, "&quot;").replace(/'/g, "&#039;");
    console.log(escaped)
    return escaped;
  }

  // -----------------------------
  // Events
  // -----------------------------

  $("#console-input").on('keyup', function (e) {
    if (e.key === 'Enter' || e.keyCode === 13) {
      var input = document.getElementById("console-input")
      var command = input.value
      handleConsoleInput(command);
      input.value = ""
    }
  });
    
  // -----------------------------
  // Data structures
  // -----------------------------

  function CreatureConciousness () {

    // Contents
    // ========

    this.sentences = {
      "dont_understand": (command) => {
        return `I don't understand ${command}. <a class="pointer" onclick="cc.actions.vocabulary();">Here</a> is what I know so far.`;
      },
    }

    // Actions (basic)
    // =======

    this.say = (string) => {
      log(string, {type: "alert", content: "html", srcFile: "creature.js:"+ Math.floor(Math.random()*1000)})
    }

    this.saySentence = (key, data) => {
      if (!this.sentences[key]) {
        console.error("Not found:"+ key);
      }
      this.say(this.sentences[key](data))
    }

    // Actions (structured)
    // =======

    this.actions = {

      "start": function () {
        toggleInspector(true);
        toTab("console");
        setTimeout(() => {
          this.loading("loading language", 2000)
        }, 1000)
      },

      "loading": function (text, totalTime) {

        var time = 0;
        var adv = 0;
        var tt = totalTime + (Math.random() - 0.5) * 1000 // +/- 500ms
        var logMode = "append"
        var messages = [];

        while (adv < 1) {
          time += 100 + Math.random() * 300;
          adv = time / tt;
          perc = Math.floor(adv * 100);
          perc = Math.min(perc, 99); // if it went over 100
          messages.push({
            "text": `${text} ${perc}%`,
            "mode": logMode,
            "time": time,
          });
          logMode = "replaceLast";
          console.log(messages)
        }
        messages.push({
          "text": `${text} [DONE]`,
          "mode": logMode,
          "time": time + Math.random() * 1000,
        })

        messages.forEach(message => {
          setTimeout(function () {
            console.log(message)
            log(message.text, {mode: message.mode})
          }, message.time);
        })
      },

      "vocabulary": () => {
        
        log("genesis()")
        log("research()")
        log("language()")
      },

    }
  }



</script>












