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
        <?php snippet("inspector-content-event-preview", ["event" => $event]) ?>
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

    <!--  
    <div class="item c-conciousness">
      <span class="src-file">VM12835:1</span>
      <div>create mode 129842 assets/lib/threejs/three.module.js</div>
    </div>
    -->

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
  cc = new CreatureConciousness(trash); // parsed from php in home template, comes from site/controllers/home.php
  
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

  function loadAbout () {
    toggleInspector(true);
    var url = "<?= $site->url() ?>/summer-school-2023.json";
    fetch(url).then(response => {
        return response.json();
      }).then(jsonData => {
        // console.log(jsonData)
        handleReceivedAboutData(jsonData.htmlText);
        bodyContent.classList.add("blur")
      }).catch(err => {
        console.log("Error fetching page:", err);
      });
  }

  function loadEvents () {
    var url = "<?= $site->url() ?>/summer-school-2023.json";
    fetch(url).then(response => {
        return response.json();
      }).then(jsonData => {
        // console.log(jsonData)
        handleReceivedProgramData(jsonData.htmlProgramCategories);
        // handleReceivedEventsData(jsonData.events);
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
          // bodyContent.textContent = ""
          handleReceivedEventData(jsonData)
        }, 600);
      }).catch(err => {
        console.log("Error fetching page:", err);
      });
  }

  function handleReceivedAboutData (aboutHtml) {
    setTimeout(() => { cc.randomLogs(4); }, 50);
    setTimeout(() => {
      var stickTo = log(aboutHtml, { type: "content", srcFile: false, content: "html" })
      stickConsole(stickTo)
    }, 150);
    setTimeout(() => { cc.randomLogs(6, {}, unstickConsole); }, 250);
  }

  


  function handleReceivedProgramData (htmlProgramCategories) {
    // setTimeout(() => { 
      cc.randomLogs(12, {}, () => {
        var time = 0;
        htmlProgramCategories.forEach((categoryHtml, i) => {
          time += 150 * Math.random()
          setTimeout(() => {
            var logEl = log(categoryHtml, { type: "content", srcFile: false, content: "html" })
            if (i === 0) { stickConsole(logEl) }
          }, time);
        })
        setTimeout(() => { cc.randomLogs(16, {}, unstickConsole); }, time+100);
      }); 
    // }, 50);
  }

  


  /* OLD */
  function handleReceivedEventsData (events) {
    // events = [{ names, title, dateText, htmlPreview }, {}, …]
    
    function logNextEventPreview (events) {
      if (Math.random() < 0.2) {
        var event = events.splice(events.length-1, 1)[0]
        // console.log(event)
        log(event.htmlPreview, { type: "content", srcFile: false, content: "html" })
      } else {
        cc.randomLog();
      }
      if (events.length > 0) {
        // var time = 100 + Math.random() * 100;
        setTimeout(() => {
          logNextEventPreview(events)
        }, 100);
      } else {
        cc.randomLogs(8)
      }
    }
    logNextEventPreview(events);
  }

  function handleReceivedEventData (jsonData) {
    console.log("--------------------");
    console.log(jsonData);
    console.log("--------------------");

    // htmlImages

    cc.messyCleanConsole(() => {

      // V2 – Content & codes
      // var time = 0;
      // jsonData.imagesHighlights.forEach((d, i) => {
      //   if (i < 12) {
      //     time += 150 * Math.random()
      //     setTimeout(() => {
      //       // log(JSON.stringify(d).substring(0, 50 + Math.random()*13));
      //       log(JSON.stringify(d));
      //     }, time);
      //   }
      // })
      // time += 150 * Math.random()
      // setTimeout(() => {
      //   cc.stickEl = log(jsonData.htmlPreview, { type: "content", srcFile: false, content: "html" })
      //   log(jsonData.htmlDescription, { type: "content", srcFile: false, content: "html" })
      // }, time);    

      // V1 – Content & codes
      setTimeout(() => {
        var stickTo = log(jsonData.htmlPreview, { type: "content", srcFile: false, content: "html" })
        stickConsole(stickTo);
        log(jsonData.htmlDescription, { type: "content", srcFile: false, content: "html" })
        // cc.logEventHtml(jsonData.htmlFullDescription, "m")
      }, 500*Math.random());

      var time = 0;
      jsonData.imagesHighlights.forEach((d, i) => {
        time += 150 * Math.random()
        setTimeout(() => {
          log(JSON.stringify(d).substring(0, 50 + Math.random()*13));
        }, time);
      })
      unstickConsole(time + 100);

      // add images
      bodyContent.textContent = ""
      bodyContent.querySelectorAll("img.event-image").forEach(imgEl => { imgEl.remove(); });
      bodyContent.insertAdjacentHTML("afterbegin", jsonData.htmlImages)
      bodyContent.insertAdjacentHTML("afterbegin", jsonData.htmlImages)
      bodyContent.insertAdjacentHTML("afterbegin", jsonData.htmlImages)
      bodyContent.classList.remove("blur")
      window.scrollTo(0, 0);

      // connect intersectionObserver
      highlightsObserver.disconnect();
      highlights = document.querySelectorAll(".highlight")
      highlights.forEach(h => { highlightsObserver.observe(h); });
    });
  }

  function stickConsole (el, duration) {
    cc.stickEl = el;
    if (duration) {
      unstickConsole(duration);
    }
  }
  function unstickConsole (milliseconds = 0) {
    setTimeout(() => { cc.stickEl = null }, milliseconds);
  }

  function log (data, logOptions) {
    var defaults = {
      type: "log",          // log|alert|error|userinput|content|conciousness
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
      conciousness: "c-conciousness",
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

    if (cc.stickEl) {
      cc.stickEl.scrollIntoView(true);
    } else {
      consoleContentDiv.scrollTo(0, 1000000)
    }
    focusInput()
    return(item)
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
    // console.log(escaped)
    return escaped;
  }

  // -----------------------------
  // Events
  // -----------------------------

  $("#console-input").on('keyup', function (e) {
    if (e.key === 'Enter' || e.keyCode === 13) {
      var input = document.getElementById("console-input")
      var command = input.value
      cc.handleConsoleInput(command);
      input.value = ""
    }
  });
    
  // -----------------------------
  // OBJECT
  // Data structures
  // -----------------------------

  function CreatureConciousness (trash) {

    this.trash = trash;
    this.stickEl = null;
    var self = this;

    // ========================================================================================
    // Contents
    // ========================================================================================

    this.sentences = {
      "dont_understand": (command) => {
        return `I don't understand ${command}. <a class="pointer" onclick="cc.actions.vocabulary();">Here</a> are the commands I know so far.`;
      },
    }

    // ========================================================================================
    // Base Actions
    // ========================================================================================

    this.say = (string) => {
      log(string, {type: "conciousness", content: "html", srcFile: "conciousness.js:"+ Math.floor(Math.random()*1000)})
    }

    this.saySentence = (key, data) => {
      if (!this.sentences[key]) {
        console.error("Not found:"+ key);
      }
      this.say(this.sentences[key](data))
    }


    this.handleConsoleInput = function (command) {
      var cmd = command.trim();
      if (cmd == "") {
        return;
      }
      log(cmd, {type: "userinput", srcFile: ""})
      
      // check if command exists
      var existingCmd = null;
      if (Object.keys(self.userActions).includes(cmd)) {
        existingCmd = cmd;
      } else if(Object.keys(self.userActionAliases).includes(cmd)) {
        existingCmd = self.userActionAliases[cmd];
      }

      // react
      if (existingCmd !== null) {
        // self.actions.loading("~", 100, function () {
        //   self.userActions[existingCmd]();
        // });
        

        // self.userActions[existingCmd]();


        log("");
        self.randomLogs(Math.random()*5 + 5, {forceLogMode: "replaceLast"}, function () {
          // log(cmd, {type: "userinput", srcFile: "", mode: "replaceLast"})
          self.userActions[existingCmd]();
        });


      } else {
        self.saySentence("dont_understand", cmd);
      }
    }

    this.commandLinkHtml = function (command) {
      return `<a class="pointer" onclick="cc.userActions['${command}']();">${command}</a>`;
    }

    /**
     *
     * -----------------------
     * @param randomLogOptions = {
     *    trashType
     *        site_contents
     *        geoplugin_raw
     *        geoplugin_parsed
     *        wikipedia_api
     *        hardcoded_git_create
     *        hardcoded_git_edits
     *        hardcoded_server
     *        hardcoded_all
     *        apache_request_headers
     *    forceLogMode
     *        false         - random
     *        "append"      - according to log options
     *        "replaceLast" - according to log options
     * 
     * }
     * 
     * */
    this.randomLog = (randomLogOptions) => {
      var defaults = {
        trashType: "hardcoded_server",
        trashType: "all",
        forceLogMode: false,
      };
      // remove undefined (via https://stackoverflow.com/a/38340374/2501713)
      randomLogOptions && Object.keys(randomLogOptions).forEach(key => randomLogOptions[key] === undefined && delete randomLogOptions[key]) 
      var options = Object.assign({}, defaults, randomLogOptions);

      var trashArray = self.trash.by_source[options.trashType];
      if (options.trashType === "all") {
        trashArray = [];
        Object.keys(self.trash.by_source).forEach((key) => {
          trashArray = trashArray.concat(self.trash.by_source[key]);
        });
      }

      var logTypes = ["log", "log", "log", "log", "log", "log", "log", "log", "log", "log", "log", "log", "log", "log", "alert", "alert", "alert", "error"];
      var logModes = ["append", "append", "replaceLast"];
      var logTextClasses = ["font-asem-s", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", ""];
      var logType = logTypes[Math.floor(Math.random() * logTypes.length)];
      var logMode = logModes[Math.floor(Math.random() * logModes.length)];
      if (options.forceLogMode !== false) { logMode = options.forceLogMode; }
      var logTextClass = logTextClasses[Math.floor(Math.random() * logTextClasses.length)];
      
      var index = Math.floor(Math.random() * trashArray.length);
      var data = trashArray[index];
      if (data && data.length > 150) {
        console.log("data.length", data.length)
        console.log("data", data)
        console.log("index", index)
        console.log("trashArray", trashArray)
        data = data.substring(0, 150) +" […"+ (data.length-150) +"chr]";
      } else {
        console.log("data", data)

      }
      log(data, {"type": logType, "mode": logMode, "textClass": logTextClass});
    }

    this.randomLogs = function (times = 1, randomLogOptions = {}, callback) {
      var self = this;
      for (var i = 0; i < times; i++) {
        setTimeout(function () {
          self.randomLog(randomLogOptions); 
        }, 10*i);
      }
      if (callback && typeof callback === "function") {
        setTimeout(callback, times * 100 + 10);
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

    // this.logEventText = (text, size) => {
    //   var textClass = "";
    //   if (size === "l") {
    //     textClass = "font-sans-l";
    //   } else if (size === "m") {
    //     textClass = "font-sans-m";
    //   }
    //   log(text, { type: "content", textClass: textClass, srcFile: false })
    // }

    // this.logEventHtml = (html, size) => {
    //   var textClass = "";
    //   if (size === "l") {
    //     textClass = "font-sans-l";
    //   } else if (size === "m") {
    //     textClass = "font-sans-m";
    //   }
    //   log(html, { type: "content", textClass: textClass, srcFile: false, content: "html" })
    // }


    // ========================================================================================
    // Actions (structured)
    // ========================================================================================

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

      "loading": function (text, totalTime, callback) {

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
        }

        var finalTime = time + Math.random() * 400;
        messages.push({
          "text": `${text} [DONE]`,
          "mode": logMode,
          "time": finalTime,
        })

        messages.forEach(message => {
          setTimeout(function () {
            log(message.text, {mode: message.mode})
          }, message.time);
        })

        if (callback && typeof callback === "function") {
          setTimeout(function () {
            callback();
          }, finalTime);
        }


      },

      "vocabulary": () => {
        // var html = Object.keys(self.userActions).join("<br />")
        // log(html);

        // Object.keys(self.userActions).forEach(key => {
        //   self.say(key)
        // });

        Object.keys(self.userActions).forEach(key => {
          self.say(self.commandLinkHtml(key))
        });
      },

    }

    // ========================================================================================
    // User Actions (console user input)
    // ========================================================================================
    // 
    // Only oneliners! 
    // for more complex actions define one in this.actions
    // 

    this.userActions = {
      "help":     function () { self.actions.vocabulary(); },
      "about":    function () { loadAbout(); },
      "program":  function () { loadEvents(); },
      "hello":    function () { self.say("hello"); },
      "reset":    function () { window.location.reload(); },
    }
    this.userActionAliases = {
      "hi":     "hello",
      "ciao":   "hello",
      "hey":    "hello",
    }
  }

</script>












