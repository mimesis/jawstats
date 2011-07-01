<?php
  // output booleans for javascript
  function BooleanToText($bValue) {
    if ($bValue == true) {
      return "true";
    } else {
      return "false";
    }
  }

  // error display
  function Error($sReason, $sExtra="") {
    // echo "ERROR!<br />" . $sReason;
    switch ($sReason) {
      case "BadConfig":
        $sProblem     = str_replace("[FILENAME]", "\"config.php\"", Lang("There is an error in [FILENAME]"));
        $sResolution  = "<p>" . str_replace("[VARIABLE]", ("<i>" . $sExtra . "</i>"), Lang("The variable [VARIABLE] is missing or invalid.")) . "</p>";
        break;
      case "BadConfigNoSites":
        $sProblem     = str_replace("[FILENAME]", "\"config.php\"", Lang("There is an error in [FILENAME]"));
        $sResolution  = "<p>" . Lang("No individual AWStats configurations have been defined.") . "</p>";
        break;
      case "CannotLoadClass":
        $sProblem     = str_replace("[FILENAME]", "\"clsAWStats.php\"", Lang("Cannot find required file [FILENAME]"));
        $sResolution  = "<p>" . Lang("At least one file required by JAWStats has been deleted, renamed or corrupted.") . "</p>";
        break;
     case "CannotLoadConfig":
        $sProblem     = str_replace("[FILENAME]", "\"config.php\"", Lang("Cannot find required file [FILENAME]"));
        $sResolution = "<p>" . str_replace("[CONFIGDIST]", "<i>config.dist.php</i>", str_replace("[CONFIG]", "<i>config.php</i>", Lang("JAWStats cannot find it's configuration file, [CONFIG]. Did you successfully copy and rename the [CO")));
        break;
      case "CannotLoadLanguage":
        $sProblem     = str_replace("[FILENAME]", "\"languages/translations.php\"", Lang("Cannot find required file [FILENAME]"));
        $sResolution  = "<p>" . Lang("At least one file required by JAWStats has been deleted, renamed or corrupted.") . "</p>";
        break;
      case "CannotOpenLog":
        $sStatsPath = $GLOBALS["aConfig"][$GLOBALS["g_sConfig"]]["statspath"];
        $sProblem     = Lang("JAWStats could not open an AWStats log file");
        $sResolution  = "<p>" . Lang("Is the specified AWStats log file directory correct? Does it have a trailing slash?") . "<br />" .
                        str_replace("[VARIABLE]", "<strong>\"statspath\"</strong>", str_replace("[CONFIG]", "<i>config.php</i>", Lang("The problem may be the variable [VARIABLE] in your [CONFIG] file."))) . "</p>" .
                        "<p>" . str_replace("[FOLDER]", "<strong>" . $sStatsPath . "</strong>\n", str_replace("[FILE]", "<strong>awstats" . date("Yn") . "." . $GLOBALS["g_sConfig"] . ".txt</strong>", Lang("The data file being looked for ")));
        if (substr($sStatsPath, -1) != "/") {
          $sResolution  .= "<br />" . str_replace("[FOLDER]", "<strong>" . $sStatsPath . "</strong>", Lang("Try changing the folder to [FOLDER]"));
        }
        $sResolution  .= "</p>";
        break;
      case "NoLogsFound":
        $sStatsPath = $GLOBALS["aConfig"][$GLOBALS["g_sConfig"]]["statspath"];
        $sProblem     = Lang("No AWStats Log Files Found");
        $sResolution  = "<p>JAWStats cannot find any AWStats log files in the specified directory: <strong>" . $sStatsPath . "</strong><br />" .
                        "Is this the correct folder? Is your config name, <i>" . $GLOBALS["g_sConfig"] . "</i>, correct?</p>\n";
        break;
      case "Unknown":
        $sProblem     = "";
        $sResolution  = "<p>" . $sExtra . "</p>\n";
        break;
    }
    echo "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" " .
         "\"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">\n" .
         "<html xmlns=\"http://www.w3.org/1999/xhtml\">\n" .
         "<head>\n" .
         "<title>JAWStats</title>\n" .
         "<style type=\"text/css\">\n" .
         "html, body { background: #33332d; border: 0; color: #eee; font-family: arial, helvetica, sans-serif; font-size: 15px; margin: 20px; padding: 0; }\n" .
         "a { color: #9fb4cc; text-decoration: none; }\n" .
         "a:hover { color: #fff; text-decoration: underline; }\n" .
         "h1 { border-bottom: 1px solid #cccc9f; color: #eee; font-size: 22px; font-weight: normal; } \n" .
         "h1 span { color: #cccc9f !important; font-size: 16px; } \n" .
         "p { margin: 20px 30px; }\n" .
         "</style>\n" .
         "</head>\n<body>\n" .
         "<h1><span>" . Lang("An error has occured") . ":</span><br />" . $sProblem . "</h1>\n" . $sResolution .
         "<p>" . str_replace("[LINKSTART]", "<a href=\"http://www.jawstats.com/documentation\" target=\"_blank\">", str_replace("[LINKEND]", "</a>", Lang("Please refer to the [LINKSTART]installation instructions[LINKEND] for more information"))) .
         "</body>\n</html>";
    exit;
  }

// error handler
  function ErrorHandler ($errno, $errstr, $errfile, $errline, $errcontext) {
    if (strpos($errfile, "index.php") != false) {
      switch ($errline) {
        case 39:
          Error("CannotLoadClass");
          break;
        case 40:
          Error("CannotLoadLanguage");
          break;
        case 41:
          Error("CannotLoadConfig");
          break;
        default:
          Error("Unknown", ("Line #" . $errline . "<br />" . $errstr));
      }
    }
  }

  // translator
  function Lang($sString) {
    if (isset($GLOBALS["g_aCurrentTranslation"][$sString]) == true) {
      return $GLOBALS["g_aCurrentTranslation"][$sString];
    } else {
      return $sString;
    }
  }

