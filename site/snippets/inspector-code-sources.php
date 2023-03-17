<?php

$creatureJavascript = <<<EOT
function CreatureConciousness (trash) {

  this.trash = trash;
  var self = this;

  // ========================================================================================
  // Contents
  // ========================================================================================

  this.sentences = {
    "dont_understand": (command) => {
      return `I don't understand {command}. <a class="pointer" onclick="cc.actions.vocabulary();">Here</a> are the commands I know so far.`;
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
      self.userActions[existingCmd]();
    } else {
      self.saySentence("dont_understand", cmd);
    }
  }

  this.randomLog = (randomLogOptions) => {
    var defaults = {
      trashType: "hardcoded_server",
    };
    // remove undefined (via https://stackoverflow.com/a/38340374/2501713)
    randomLogOptions && Object.keys(randomLogOptions).forEach(key => randomLogOptions[key] === undefined && delete randomLogOptions[key]) 
    var options = Object.assign({}, defaults, randomLogOptions);

    var trashArray = self.trash.by_source[options.trashType];

    var logTypes = ["log", "log", "log", "log", "log", "log", "log", "log", "log", "log", "log", "log", "log", "log", "alert", "alert", "alert", "error"];
    var logModes = ["append", "append", "replaceLast"];
    var logTextClasses = ["font-asem-s", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", ""];
    var logType = logTypes[Math.floor(Math.random() * logTypes.length)];
    var logMode = logModes[Math.floor(Math.random() * logModes.length)];
    var logTextClass = logTextClasses[Math.floor(Math.random() * logTextClasses.length)];
    
    var data = trashArray[Math.floor(Math.random() * trashArray.length)];
    log(data, {"type": logType, "mode": logMode, "textClass": logTextClass});
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
          "text": `{text} {perc}%`,
          "mode": logMode,
          "time": time,
        });
        logMode = "replaceLast";
        // console.log(messages)
      }
      messages.push({
        "text": `{text} [DONE]`,
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
      var html = Object.keys(self.userActions).join("<br />")
      self.say(html);
    },

  }

  // ========================================================================================
  // User Actions (console user input)
  // ========================================================================================

  this.userActions = {
    "reset":    function () { window.location.reload(); },
    "hello":    function () { self.say("hello"); },
    "program":  function () { loadEvents(); },
  }
  this.userActionAliases = {
    "hi":     "hello",
    "ciao":   "hello",
    "hey":    "hello",
    
  }
}
EOT;
$sourcesTabContent = Html::encode($creatureJavascript);

echo $sourcesTabContent;