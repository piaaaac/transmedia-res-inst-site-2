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












