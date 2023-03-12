<?php
$events = page("summer-school-2023")->children()->listed();
?>

<div id="inspector" data-tab="console" data-skin="dark">
  
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
    <div id="input-area" onclick="focusInput();">
      <input type="text" id="console-input"  />
    </div>
    <div id="console-sensi-bg" onclick="focusInput();"></div>
  </div>

</div>

<script>

  // -----------------------------
  // Vars
  // -----------------------------

  var bodyContent = document.getElementById("body-content") 
  var consoleContentDiv = document.querySelector("#inspector #inspector-content-console")
  cc = new CreatureConciousness();
  
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

  function loadEvents () {
    var url = "<?= $site->url() ?>/summer-school-2023.json";
    fetch(url).then(response => {
        return response.json();
      }).then(jsonData => {
        console.log(jsonData)
        handleReceivedEventsData(jsonData);
      }).catch(err => {
        console.log("Error fetching page:", err);
      });
  }

  function loadEvent (uid) {
    var url = "<?= $site->url() ?>/summer-school-2023/"+ uid +".json";
    fetch(url).then(response => {
        return response.json();
      }).then(jsonData => {
        bodyContent.classList.add("blur")
        setTimeout(() => { 
          bodyContent.textContent = ""
          handleReceivedEventData(jsonData)
        }, 1600);
      }).catch(err => {
        console.log("Error fetching page:", err);
      });
  }

  function handleReceivedEventsData (jsonData) {
    // jsonData = [{ names, title, dateText, htmlPreview }, {}, …]
    
    function logNextEventPreview (jsonData) {
      if (Math.random() < 0.2) {
        var event = jsonData.splice(jsonData.length-1, 1)[0]
        console.log(event)
        log(event.htmlPreview, { type: "content", srcFile: false, content: "html" })
      } else {
        cc.randomLog();
      }
      if (jsonData.length > 0) {
        // var time = 100 + Math.random() * 100;
        setTimeout(() => {
          logNextEventPreview(jsonData)
        }, 100);
      } else {
        cc.randomLogs(8)
      }
    }
    logNextEventPreview(jsonData);
  }

  function handleReceivedEventData (jsonData) {
    console.log(jsonData);

    cc.messyCleanConsole(() => {

      setTimeout(() => {
        log(jsonData.htmlPreview, { type: "content", srcFile: false, content: "html" })
        log(jsonData.htmlDescription, { type: "content", srcFile: false, content: "html" })
        // cc.logEventHtml(jsonData.htmlFullDescription, "m")
      }, 500*Math.random());    

      jsonData.imagesHighlights.forEach((d, i) => {
        setTimeout(() => {
          log(JSON.stringify(d).substring(0, 50 + Math.random()*13));
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
    });
  }

  function log (data, logOptions) {
    var defaults = {
      type: "log",          // log|alert|error|userinput|content
      textClass: "",        // string
      content: "textNode",  // textNode|html
      srcFile: "auto",      // string "filename:line"
      mode: "append",       // append|replaceLast
    };
    // remove undefined (via https://stackoverflow.com/a/38340374/2501713)
    logOptions && Object.keys(logOptions).forEach(key => logOptions[key] === undefined && delete logOptions[key]) 
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

    if (options.srcFile) {
      var srcFile = document.createElement("span")
      srcFile.classList.add("src-file")
      srcFile.textContent = options.srcFile
      srcFile.onclick = () => { handleSrcFileClick(); }
      item.appendChild(srcFile)
    }

    var div = document.createElement("div");
    if (options.textClass !== "") {
      div.classList.add(options.textClass)
    }
    if (options.content == "textNode") {
      var txt = document.createTextNode(data);
      div.appendChild(txt);
    } else if (options.content == "html") {
      div.innerHTML = data
    }
    item.appendChild(div)

    var inputArea = document.querySelector("#input-area")
    // var replaceable = (inputArea.previousSibling !== null)
    var replaceable = (inputArea.previousSibling !== null 
      && inputArea.previousSibling.classList
      && inputArea.previousSibling.classList.contains("c-content") === false
      )
    if (options.mode == "replaceLast" && replaceable) {
      consoleContentDiv.replaceChild(item, inputArea.previousSibling)
    } else {
      // if (options.mode == "append" || ) {
      consoleContentDiv.insertBefore(item, inputArea)
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
  // OBJECT
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

    this.logEventText = (text, size) => {
      var textClass = "";
      if (size === "l") {
        textClass = "font-sans-l";
      } else if (size === "m") {
        textClass = "font-sans-m";
      }
      log(text, { type: "content", textClass: textClass, srcFile: false })
    }

    this.logEventHtml = (html, size) => {
      var textClass = "";
      if (size === "l") {
        textClass = "font-sans-l";
      } else if (size === "m") {
        textClass = "font-sans-m";
      }
      log(html, { type: "content", textClass: textClass, srcFile: false, content: "html" })
    }

    this.randomLog = () => {
      var types = ["log", "log", "log", "log", "log", "log", "log", "log", "log", "log", "log", "log", "log", "log", "alert", "alert", "alert", "error"];
      var modes = ["append", "append", "replaceLast"];
      var textClasses = ["font-asem-s", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", ""];
      var type = types[Math.floor(Math.random() * types.length)];
      var mode = modes[Math.floor(Math.random() * modes.length)];
      var textClass = textClasses[Math.floor(Math.random() * textClasses.length)];
      var data = trashLines[Math.floor(Math.random() * trashLines.length)];
      log(data, {"type": type, "mode": mode, "textClass": textClass});
    }

    this.randomLogs = function (times = 1) {
      var self = this;
      for (var i = 0; i < times; i++) {
        setTimeout(self.randomLog, 100*i);
      }
    }

    this.messyCleanConsole = function (callback, time = 800, finalClean = false) {
      var messInterval = setInterval(() => {
        if (Math.random() < 0.06) { clearConsole() }
        if (Math.random() < 0.4) { self.randomLog() }
      }, 10);
      setTimeout(() => { 
        clearInterval(messInterval);
        setTimeout(() => { 
          if (finalClean) {
            clearConsole();
          }
          if (callback && typeof callback === "function") {
            callback();
          }
        }, 300)
      }, time)
    }

    // Actions (structured)
    // =======
    var self = this;

    this.actions = {

      "start": function () {
        toggleInspector(true);
        toTab("console");
        setTimeout(() => {
          self.actions.loading("loading language", 600)
        }, 1000)
        var times = 160;
        var i = 0;
        var interval;
        setTimeout(() => { 
          interval = setInterval(() => {
            if (Math.random() < 0.06) { clearConsole() }
            if (Math.random() < 0.4) { self.randomLog() }
            if (i >= times) { 
              clearInterval(interval);
              setTimeout(() => {
                clearConsole();
              }, 500)
              setTimeout(() => {
                loadEvents();
              }, 600)
            }
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
    "create mode 129842 assets/css/index.scss",
    "create mode 129842 assets/css/_typography.scss",
    "create mode 129842 assets/css/_reset.scss",
    "create mode 129842 assets/css/hamburger-settings.css.map",
    "create mode 129842 assets/css/bootstrap-custom.css.map",
    "create mode 129842 assets/css/index.css",
    "create mode 129842 assets/css/index.css.map",
    "create mode 129842 assets/css/prepros.config",
    "create mode 129842 assets/css/bootstrap-custom.css",
    "create mode 129842 assets/css/hamburger-settings.css",
    "create mode 129842 assets/css/_mixins.scss",
    "create mode 129842 assets/css/bootstrap-custom.scss",
    "create mode 129842 assets/models/13.obj",
    "create mode 129842 assets/models/12.obj",
    "create mode 129842 assets/models/10.obj",
    "create mode 129842 assets/models/11.obj",
    "create mode 129842 assets/models/9.obj",
    "create mode 129842 assets/models/8.obj",
    "create mode 129842 assets/models/3.obj",
    "create mode 129842 assets/models/2.obj",
    "create mode 129842 assets/models/1.obj",
    "create mode 129842 assets/models/5.obj",
    "create mode 129842 assets/models/4.obj",
    "create mode 129842 assets/models/6.obj",
    "create mode 129842 assets/models/7.obj",
    "create mode 129842 assets/lib/jquery-3.6.0.min.js",
    "create mode 129842 assets/lib/es-module-shims.js",
    "create mode 129842 assets/lib/threejs",
    "create mode 129842 assets/lib/threejs/three.module.js",
    "create mode 129842 assets/data",
    "create mode 129842 assets/data/1-mergedObjectsDetected.json",
    "create mode 129842 assets/data/5-merge1-2-4-objectsDetected.json",
    "create mode 129842 assets/data/3-merge1-2-objectsDetected.json",

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

    "[REMOTE_ADDR] => 93.35.168.247",
    "[HTTP_ACCEPT_LANGUAGE] => en-US,en;q=0.9",
    "[HTTP_ACCEPT_ENCODING] => gzip, deflate, br",
    "[HTTP_SEC_FETCH_DEST] => document",
    "[HTTP_SEC_FETCH_USER] => ?1",
    "[HTTP_SEC_FETCH_MODE] => navigate",
    "[HTTP_SEC_FETCH_SITE] => none",
    "[HTTP_ACCEPT] => text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,* /*;q=0.8,application/signed-exchange;v=b3;q=0.7",
    "[HTTP_USER_AGENT] => Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/110.0.0.0 Safari/537.36",
    "[HTTP_DNT] => 1",
    "[HTTP_SEC_CH_UA_PLATFORM] => 'macOS'",
    "[HTTP_SEC_CH_UA_MOBILE] => ?0",
    "[HTTP_SEC_CH_UA] => 'Not A(Brand';v='24', 'Chromium';v='110'",
    "[HTTP_CACHE_CONTROL] => max-age=0",
    "[HTTP_CONNECTION] => close",
    "[HTTP_X_FORWARDED_PROTO] => https",
    "[HTTP_X_FORWARDED_PORT] => 443",
    "[HTTP_X_REAL_IP] => 93.35.168.247",
    "[HTTP_HOST] => www.transmediaresearch.institute",
    "[proxy-nokeepalive] => 1",
    "[HTTPS] => on",
    "[FCGI_ROLE] => RESPONDER",
    "[PHP_SELF] => /ktest/index.php",
    "[REQUEST_TIME_FLOAT] => 1676452827.7297",
    "[REQUEST_TIME] => 1676452827",

  ];

</script>












