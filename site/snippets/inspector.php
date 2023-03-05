<?php
$events = page("summer-school-2023")->children()->listed();
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
  <div class="content" id="inspector-content-network">
    <?php foreach ($events as $event): ?>
      <div class="item c-content">
        <?php snippet("inspector-event-preview", ["event" => $event]) ?>
      </div>
    <?php endforeach ?>
    <div class="item c-log">
      <?php snippet("inspector-code-network-trash") ?>
    </div>
  </div>
  
  <!-- sources -->
  <div class="content" id="inspector-content-sources">
    <div class="item c-log">
      <span class="src-file">…/src/creature.js</span>
      <pre><code id="code-sources" class="language-javascript"><?php snippet("inspector-code-sources") ?></code></pre>
    </div>
  </div>
  
  <!-- console -->
  <div class="content" id="inspector-content-console">
    <div id="console-sensi-bg" onclick="focusInput();"></div>
    <div id="input-area" onclick="focusInput();">
      <input type="text" id="console-input"  />
    </div>
  </div>

</div>

<script>

  // -----------------------------
  // Vars
  // -----------------------------

  var bodyContent = document.getElementById("body-content") 
  var consoleContentDiv = document.querySelector("#inspector #inspector-content-console")
  var cc = new CreatureConciousness();
  
  // -----------------------------
  // Setup stuff
  // -----------------------------

  // bind src file names' click event
  $(".src-file").click(() => { handleSrcFileClick(); })

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
      type: "log", // log|alert|error|userinput|content
      content: "textNode", // textNode|html
      srcFile: "auto", // string "filename:line"
      mode: "append", // append|replaceLast
    };
    logOptions && Object.keys(logOptions).forEach(key => logOptions[key] === undefined && delete logOptions[key]) // remove undefined (via https://stackoverflow.com/a/38340374/2501713)
    var options = Object.assign({}, defaults, logOptions);
    if (options.srcFile == "auto") {
      var d = new Date();
      var fakeVM = "VM"+ d.getDate();
      fakeVM += (d.getMinutes() < 10) ? "04" : "8";
      fakeVM += d.getMinutes() +":1";
      options.srcFile = fakeVM;
    }

    // console.log("check", data, logOptions, options)
    
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

    var inputArea = document.querySelector("#input-area")
    if (options.mode == "append") {
      consoleContentDiv.insertBefore(item, inputArea)
    } else if (options.mode == "replaceLast") {
      consoleContentDiv.replaceChild(item, inputArea.previousSibling)
    }

    consoleContentDiv.scrollTo(0, 1000000)
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

  function clearConsole () {
    var items = consoleContentDiv.querySelectorAll(".item");
    items.forEach(item => {
      consoleContentDiv.removeChild(item);
    });
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

    this.randomLog = () => {
      var types = ["log", "alert", "alert", "error"];
      var modes = ["append", "append", "replaceLast"];
      var type = types[Math.floor(Math.random() * types.length)];
      var mode = modes[Math.floor(Math.random() * modes.length)];
      var data = trashLines[Math.floor(Math.random() * trashLines.length)];
      log(data, {"type": type, "mode": mode});
    }

    // Actions (structured)
    // =======
    var self = this;

    this.actions = {

      "start": function () {
        toggleInspector(true);
        toTab("console");
        setTimeout(() => {
          self.actions.loading("loading language", 2000)
        }, 1000)
        var times = 130;
        var i = 0;
        var interval;
        setTimeout(() => { 
          interval = setInterval(() => {
            if (i >= times) { clearInterval(interval) }
            if (Math.random() < 0.06) { clearConsole() }
            if (Math.random() < 0.6) { self.randomLog() }
            i++;
          }, 10);
        }, 3200)

        // function 
      },

      "loading": function (text, totalTime) {

        var time = 0;
        var adv = 0;
        var tt = totalTime + (Math.random() - 0.5) * 1000 // +/- 500ms
        var logMode = "append"
        var messages = [];

        while (adv < 1) {
          time += 50 + Math.random() * 150;
          adv = time / tt;
          perc = Math.floor(adv * 100);
          perc = Math.min(perc, 99); // if it went over 100
          messages.push({
            "text": `${text} ${perc}%`,
            "mode": logMode,
            "time": time,
          });
          logMode = "replaceLast";
          // console.log(messages)
        }
        messages.push({
          "text": `${text} [DONE]`,
          "mode": logMode,
          "time": time + Math.random() * 1000,
        })

        messages.forEach(message => {
          setTimeout(function () {
            // console.log(message)
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

  var trashLines = [
    "create mode 100644 assets/lib/highlight/es/languages/abnf.min.js",
    "create mode 100644 assets/lib/highlight/es/languages/accesslog.min.js",
    "create mode 100644 assets/lib/highlight/es/languages/actionscript.min.js",
    "create mode 100644 assets/lib/highlight/es/languages/ada.min.js",
    "create mode 100644 assets/lib/highlight/es/languages/angelscript.min.js",
    "create mode 100644 assets/lib/highlight/es/languages/apache.min.js",
    "create mode 100644 assets/lib/highlight/es/languages/applescript.min.js",
    "create mode 100644 assets/lib/highlight/es/languages/arcade.min.js",
    "create mode 100644 assets/lib/highlight/es/languages/arduino.min.js",
    "create mode 100644 assets/lib/highlight/es/languages/armasm.min.js",
    "create mode 100644 assets/lib/highlight/es/languages/asciidoc.min.js",
    "create mode 100644 assets/lib/highlight/es/languages/aspectj.min.js",
    "create mode 100644 assets/lib/highlight/es/languages/autohotkey.min.js",
    "create mode 100644 assets/lib/highlight/es/languages/autoit.min.js",
    "create mode 100644 assets/lib/highlight/es/languages/avrasm.min.js",
    "create mode 100644 assets/lib/highlight/es/languages/awk.min.js",
    "create mode 100644 assets/lib/highlight/es/languages/axapta.min.js",
    "create mode 100644 assets/lib/highlight/es/languages/bash.min.js",
    "create mode 100644 assets/lib/highlight/es/languages/basic.min.js",
    "create mode 100644 assets/lib/highlight/es/languages/bnf.min.js",
    "create mode 100644 assets/lib/highlight/es/languages/brainfuck.min.js",
    "create mode 100644 assets/lib/highlight/es/languages/c.min.js",
    "create mode 100644 assets/lib/highlight/es/languages/cal.min.js",
    "create mode 100644 assets/lib/highlight/es/languages/capnproto.min.js",
    "create mode 100644 assets/lib/highlight/es/languages/ceylon.min.js",
    "create mode 100644 assets/lib/highlight/es/languages/clean.min.js",
    "create mode 100644 assets/lib/highlight/es/languages/clojure-repl.min.js",
    "create mode 100644 assets/lib/highlight/es/languages/clojure.min.js",
    "create mode 100644 assets/lib/highlight/es/languages/cmake.min.js",

    "modified:   README.md",
    "modified:   assets/css/index.css",
    "deleted:    assets/css/index.css.map",
    "modified:   assets/css/index.scss",
    "modified:   content/2_summer-school-2023/events.txt",
    "modified:   site/snippets/creature.php",
    "modified:   site/snippets/inspector.php",
    "modified:   site/snippets/load-scripts.php",
    "modified:   site/templates/home.php",
    "assets/css/prepros.config",
    "assets/lib/es-module-shims.js",

  ];

</script>












