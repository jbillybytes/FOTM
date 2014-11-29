<!DOCTYPE html>
<html lang="en" ng-app="fotmApp">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Data Parser - FOTM</title>

    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/fotm.css" rel="stylesheet">
    <link rel="shortcut icon" href="img/fotm_icon.jpg">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/angular.js"></script>
    <script src="js/fotm.js"></script>
    
  </head>
  <body>
    <!-- Navigation bar -->
    <nav class="navbar navbar-default">
      <div class="container">
        <div class="row">
          <div class="col-md-5 navbar-header">
            <a href="http://freedomonthemove.org/" class="navbar-brand">Freedom on the Move</a>
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
            </button>
          </div>
          <div class="col-md-7">
            <div class="collapse navbar-collapse">
              <ul class="nav navbar-nav">
                <li class="home"><a href="home.php"><span class="glyphicon glyphicon-home"></span></a></li>
                <li><a href="#" class="disable">Runaway Ads</a></li>
                <li><a href="#" class="disable">Transcriber</a></li>
                <li class="active"><a href="#" class="disable">Data parser</a></li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </nav>

    <?php

    // If the user clicked on submit on the previous page
    if (isset($_POST['transcribed'])) {

      // Get the ad ID from the session variable
      session_start();
      $id = $_SESSION['adid'];
      $text = $_POST['transcribed'];
      $text_to_db = preg_replace("/'/", "''", $text); // Escape the single quotes to allow the DB insertion
      $tag = $_POST['tag'];

      $db = pg_connect('host=localhost dbname=FOTM user=postgres password=root');

      // Update the ad in the DB
      $updateAd = pg_query("UPDATE ads SET ocr_text = '$text_to_db', tags = '$tag' WHERE adid = '$id'");
      if (!$updateAd) {
        $errormessage = pg_last_error();
        echo "Error with update query: " . $errormessage;
        exit();
      }

      // Get this ad information
      $adQuery = pg_query("SELECT * FROM ads WHERE adid = '$id'");
      if (!$adQuery) {
        $errormessage = pg_last_error();
        echo "Error with select ads query: " . $errormessage;
        exit();
      }
      $ad = pg_fetch_array($adQuery, NULL, PGSQL_ASSOC);

      // File that contains functions to retrieve information from filename
      include "get_file_info.php";

      $newspaperName = getName($ad['filename']);
      $newspaperDate = getDistributionDate($ad['filename']);
      $newspaperPage = getPage($ad['filename']);

      // Get existing data for this ad
      $crowdQuery = pg_query("select * from get_crowdsourcing_info_for_ads('$id')");
      if (!$crowdQuery) {
        $errormessage = pg_last_error();
        echo "Error with select crowdsourcing information query: " . $errormessage;
        exit();
      }
      $crowdInfo = pg_fetch_array($crowdQuery, NULL, PGSQL_ASSOC);

      // Get child data for this ad
      $childQuery = pg_query("select * from get_child_info('$id')");
      if (!$childQuery) {
        $errormessage = pg_last_error();
        echo "Error with select child info query: " . $errormessage;
        exit();
      }

      // Create an array for child info
      $i = 0;
      while ($array = pg_fetch_array($childQuery, NULL, PGSQL_ASSOC)) {
        $childArray[$i]['name'] = $array['childname'];
        $childArray[$i]['age'] = $array['childage'];
        $childArray[$i]['gender'] = $array['childgender'];
        $i++;
      }

      // Get nb of children
      $childNbQuery = pg_query("select COUNT(*) from get_child_info('$id')");
      $childNb = pg_fetch_array($childNbQuery, NULL, PGSQL_ASSOC);
      $childNb = $childNb['count'];

      // Get the states
      $statesQuery = pg_query("SELECT name FROM states ORDER BY name");
      if (!$statesQuery) {
        $errormessage = pg_last_error();
        echo "Error with select states query: " . $errormessage;
        exit();
      }
      ?>

      <section class="container" ng-controller="formCtrl">
        <article class="row">
          <form class="form-horizontal" id="form" name="fotmForm" id="fotmForm" method="post" ng-keydown="blockEnter($event)">
            <!-- Transcribed text -->
            <div class="col-md-6">
              <div class="fixed">
                <div class="panel panel-primary">
                  <div class="panel-heading">
                    <h2 class="panel-title">Transcribed Text</h2>
                  </div>
                  <div class="panel-body">
                    <p>
                      <?php
                      foreach(preg_split("/((\r?\n)|(\r\n?))/", $text) as $line) {
                        echo htmlspecialchars($line) . "<br>";
                      }
                      ?>
                    </p>
                  </div>
                  <div class="panel-footer text-center">
                    <button type="submit" class="btn btn-info" ng-click="submitForm('transcriber.php?id=<?php echo $id; ?>')"><span class="glyphicon glyphicon-arrow-left"></span> Back to transcriber</button>
                  </div>
                </div>
                <div class="panel panel-primary">
                  <div class="panel-body">
                    <div>
                      <!-- Progress bar -->
                      <ol class="progtrckr">
                        <li class="progtrckr-todo" id="1">Newspaper</li><!--
                     --><li class="progtrckr-todo" id="2">Enslaver</li><!--
                     --><li class="progtrckr-todo" id="3">Runaway</li><!--
                     --><li class="progtrckr-todo" id="4">Event</li><!--
                     --><li class="progtrckr-todo" id="5">Children</li>
                      </ol> 
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="panel panel-primary">
                <div class="panel-heading">
                  <h2 class="panel-title" ng-show="part == 1">Newspaper Information</h2>
                  <h2 class="panel-title" ng-show="part == 2">Enslaver Information</h2>
                  <h2 class="panel-title" ng-show="part == 3">Runaway Information</h2>
                  <h2 class="panel-title" ng-show="part == 4">Runaway Event</h2>
                  <h2 class="panel-title" ng-show="part == 5">Runaway Family and Children</h2>
                </div>
                <div class="panel-body">
                <!-- Crowdsourcing form -->
                  <fieldset ng-show="part == 1">
                    <div class="row">
                      <div class="form-group">
                        <label class="col-xs-5 control-label" for="news-state">Newspaper state</label>
                        <div class="col-xs-7">
                          <select class="form-control" name="news-state" id="news-state">
                            <!-- Put the Unknown option first -->
                            <option value="Unknown">Unknown</option>
                            <?php

                            // Get the states from the DB query; if the state corresponds to the one that was previously entered, select it
                            while ($arr = pg_fetch_array($statesQuery, NULL, PGSQL_ASSOC)) {
                              if (($crowdInfo['newspaper_state_name'] != "Unknown") && (isset($crowdInfo['newspaper_state_name']))) {
                                if ($crowdInfo['newspaper_state_name'] == $arr['name']) {
                                  ?>
                                  <option value="<?php echo htmlspecialchars($arr['name']); ?>" selected="selected"><?php echo htmlspecialchars($arr['name']); ?></option>
                                  <?php
                                }
                                elseif ($arr['name'] != "Unknown") {
                                  ?>
                                  <option value="<?php echo htmlspecialchars($arr['name']); ?>"><?php echo htmlspecialchars($arr['name']); ?></option>
                                  <?php
                                }
                              }
                              elseif ($arr['name'] != "Unknown") {
                                ?>
                                <option value="<?php echo htmlspecialchars($arr['name']); ?>"><?php echo htmlspecialchars($arr['name']); ?></option>
                                <?php
                              }
                            }
                            pg_result_seek($statesQuery, 0);
                            ?>
                          </select>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="form-group has-feedback" ng-class="{'has-error' : news.name.error}">
                        <label class="col-xs-5 control-label" for="news-name">Newspaper name</label>
                        <div class="col-xs-7">
                          <input type="text" class="form-control" name="news-name" id="news-name" ng-model="news.name.input" ng-keyup="news.name.check()" ng-init="news.name.input = '<?php echo htmlspecialchars($newspaperName); ?>'" readonly="readonly">
                          <span class="glyphicon glyphicon-remove form-control-feedback" ng-show="news.name.error == true"></span>
                        </div>
                        <span class="col-xs-7 col-xs-offset-5 help-block" ng-show="news.name.error == true">The name should not contain any number</span>
                      </div>
                    </div>
                    <div class="row">
                      <div class="form-group has-feedback" ng-class="{'has-error' : news.city.error}">
                        <label class="col-xs-5 control-label" for="news-city">Newspaper city</label>
                        <div class="col-xs-7">
                          <input type="text" class="form-control" name="news-city" id="news-city" ng-model="news.city.input" ng-keyup="news.city.check()" ng-init="news.city.input = '<?php echo htmlspecialchars($crowdInfo['newspaper_city_name']); ?>'">
                          <span class="glyphicon glyphicon-remove form-control-feedback" ng-show="news.city.error == true"></span>
                        </div>
                        <span class="col-xs-7 col-xs-offset-5 help-block" ng-show="news.city.error == true">The city should not contain any number</span>
                      </div>
                    </div>
                    <div class="row">
                      <div class="form-group has-feedback" ng-class="{'has-error' : news.county.error}">
                        <label class="col-xs-5 control-label" for="news-county">Newspaper county</label>
                        <div class="col-xs-7">
                          <input type="text" class="form-control" name="news-county" id="news-county" ng-model="news.county.input" ng-keyup="news.county.check()" ng-init="news.county.input = '<?php echo htmlspecialchars($crowdInfo['newspaper_county_name']); ?>'">
                          <span class="glyphicon glyphicon-remove form-control-feedback" ng-show="news.county.error == true"></span>
                        </div>
                        <span class="col-xs-7 col-xs-offset-5 help-block" ng-show="news.county.error == true">The county should not contain any number</span>
                      </div>
                    </div>
                    <div class="row">
                      <div class="form-group has-feedback" ng-class="{'has-error' : news.date.error}">
                        <label class="col-xs-5 control-label" for="news-date">Newspaper distribution date</label>
                        <div class="col-xs-7">
                          <input type="text" class="form-control" name="news-date" id="news-date" ng-model="news.date.input" ng-keyup="news.date.check()" ng-init="news.date.input = '<?php echo htmlspecialchars($newspaperDate); ?>'" readonly="readonly">
                          <span class="glyphicon glyphicon-remove form-control-feedback" ng-show="news.date.error == true"></span>
                        </div>
                        <span class="col-xs-7 col-xs-offset-5 help-block" ng-show="news.date.error == true">The date format should be mm/dd/yyyy</span>
                      </div>
                    </div>
                    <div class="row">
                      <div class="form-group has-feedback" ng-class="{'has-error' : news.page.error}">
                        <label class="col-xs-5 control-label" for="news-page">Page number of ad</label>
                        <div class="col-xs-7">
                          <input type="text" class="form-control" name="news-page" id="news-page" ng-model="news.page.input" ng-keyup="news.page.check()" ng-init="news.page.input = <?php echo htmlspecialchars($newspaperPage); ?>" readonly="readonly">
                          <span class="glyphicon glyphicon-remove form-control-feedback" ng-show="news.page.error == true"></span>
                        </div>
                        <span class="col-xs-7 col-xs-offset-5 help-block" ng-show="news.page.error == true">The page must be a number</span>
                      </div>
                    </div>
                    <div class="row">
                      <div class="form-group has-feedback" ng-class="{'has-error' : news.url.error}">
                        <label class="col-xs-5 control-label" for="news-url">Source URL</label>
                        <div class="col-xs-7">
                          <input type="text" class="form-control" name="news-url" id="news-url" ng-model="news.url.input" ng-keyup="news.url.check()" ng-init="news.url.input = '<?php echo htmlspecialchars($crowdInfo['sourceurl']); ?>'">
                          <span class="glyphicon glyphicon-remove form-control-feedback" ng-show="news.url.error == true"></span>
                        </div>
                        <span class="col-xs-7 col-xs-offset-5 help-block" ng-show="news.url.error == true">The URL must be valid</span>
                      </div>
                    </div>
                    <div class="row">
                      <div class="form-group">
                        <label class="col-xs-5 control-label">Was this ad placed by a jailor?</label>
                        <div class="col-xs-7">
                          <?php
                          if ($crowdInfo['isjailor'] == "t") {
                            ?>
                            <label for="jailor-yes" class="radio-inline">
                              <input type="radio" name="news-jailor" value="TRUE" id="jailor-yes" checked="checked">
                              Yes
                            </label>
                            <label for="jailor-no" class="radio-inline">
                              <input type="radio" name="news-jailor" value="FALSE" id="jailor-no">
                              No
                            </label>
                            <?php
                          }
                          elseif ($crowdInfo['isjailor'] == "f") {
                            ?>
                            <label for="jailor-yes" class="radio-inline">
                              <input type="radio" name="news-jailor" value="TRUE" id="jailor-yes">
                              Yes
                            </label>
                            <label for="jailor-no" class="radio-inline">
                              <input type="radio" name="news-jailor" value="FALSE" id="jailor-no" checked="checked">
                              No
                            </label>
                            <?php
                          }
                          else {
                            ?>
                            <label for="jailor-yes" class="radio-inline">
                              <input type="radio" name="news-jailor" value="TRUE" id="jailor-yes">
                              Yes
                            </label>
                            <label for="jailor-no" class="radio-inline">
                              <input type="radio" name="news-jailor" value="FALSE" id="jailor-no">
                              No
                            </label>
                            <?php
                          }
                          ?>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="form-group">
                        <label class="col-xs-5 control-label" for="news-notes">Newspaper notes</label>
                        <div class="col-xs-7">
                          <textarea class="form-control" name="news-notes" id="news-notes"><?php echo htmlspecialchars($crowdInfo['runawayads_notes']); ?></textarea>
                        </div>
                      </div>
                    </div>
                  </fieldset>

                  <fieldset ng-show="part == 2">
                    <div class="row">
                      <div class="form-group has-feedback" ng-class="{'has-error' : ensl.first.error}">
                        <label class="col-xs-5 control-label" for="ensl-first">Enslaver first name</label>
                        <div class="col-xs-7">
                          <input type="text" class="form-control" name="ensl-first" id="ensl-first" ng-model="ensl.first.input" ng-keyup="ensl.first.check()" ng-init="ensl.first.input = '<?php echo htmlspecialchars($crowdInfo['f_name']); ?>'">
                          <span class="glyphicon glyphicon-remove form-control-feedback" ng-show="ensl.first.error == true"></span>
                        </div>
                        <span class="col-xs-7 col-xs-offset-5 help-block" ng-show="ensl.first.error == true">The first name should not contain any number</span>
                      </div>
                    </div>
                    <div class="row">
                      <div class="form-group has-feedback" ng-class="{'has-error' : ensl.last.error}">
                        <label class="col-xs-5 control-label" for="ensl-last">Enslaver last name</label>
                        <div class="col-xs-7">
                          <input type="text" class="form-control" name="ensl-last" id="ensl-last" ng-model="ensl.last.input" ng-keyup="ensl.last.check()" ng-init="ensl.last.input = '<?php echo htmlspecialchars($crowdInfo['l_name']); ?>'">
                          <span class="glyphicon glyphicon-remove form-control-feedback" ng-show="ensl.last.error == true"></span>
                        </div>
                        <span class="col-xs-7 col-xs-offset-5 help-block" ng-show="ensl.last.error == true">The last name should not contain any number</span>
                      </div>
                    </div>
                    <div class="row">
                      <div class="form-group">
                        <label class="col-xs-5 control-label" for="ensl-state">Enslaver state</label>
                        <div class="col-xs-7">
                          <select class="form-control" name="ensl-state" id="ensl-state">
                            <option value="Unknown">Unknown</option>
                            <?php
                            while ($arr = pg_fetch_array($statesQuery, NULL, PGSQL_ASSOC)) {
                              if (($crowdInfo['owner_statename'] != "Unknown") && (isset($crowdInfo['newspaper_state_name']))) {
                                if ($crowdInfo['owner_statename'] == $arr['name']) {
                                  ?>
                                  <option value="<?php echo htmlspecialchars($arr['name']); ?>" selected="selected"><?php echo htmlspecialchars($arr['name']); ?></option>
                                  <?php
                                }
                                elseif ($arr['name'] != "Unknown") {
                                  ?>
                                  <option value="<?php echo htmlspecialchars($arr['name']); ?>"><?php echo htmlspecialchars($arr['name']); ?></option>
                                  <?php
                                }
                              }
                              elseif ($arr['name'] != "Unknown") {
                                ?>
                                <option value="<?php echo htmlspecialchars($arr['name']); ?>"><?php echo htmlspecialchars($arr['name']); ?></option>
                                <?php
                              }
                            }

                            pg_result_seek($statesQuery, 0);
                            ?>
                          </select>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="form-group has-feedback" ng-class="{'has-error' : ensl.city.error}">
                        <label class="col-xs-5 control-label" for="ensl-city">Enslaver city</label>
                        <div class="col-xs-7">
                          <input type="text" class="form-control" name="ensl-city" id="ensl-city" ng-model="ensl.city.input" ng-keyup="ensl.city.check()" ng-init="ensl.city.input = '<?php echo htmlspecialchars($crowdInfo['owner_cityname']); ?>'">
                          <span class="glyphicon glyphicon-remove form-control-feedback" ng-show="ensl.city.error == true"></span>
                        </div>
                        <span class="col-xs-7 col-xs-offset-5 help-block" ng-show="ensl.city.error == true">The city should not contain any number</span>
                      </div>
                    </div>
                    <div class="row">
                      <div class="form-group has-feedback" ng-class="{'has-error' : ensl.county.error}">
                        <label class="col-xs-5 control-label" for="ensl-county">Enslaver county</label>
                        <div class="col-xs-7">
                          <input type="text" class="form-control" name="ensl-county" id="ensl-county" ng-model="ensl.county.input" ng-keyup="ensl.county.check()" ng-init="ensl.county.input = '<?php echo htmlspecialchars($crowdInfo['owner_countyname']); ?>'">
                          <span class="glyphicon glyphicon-remove form-control-feedback" ng-show="ensl.county.error == true"></span>
                        </div>
                        <span class="col-xs-7 col-xs-offset-5 help-block" ng-show="ensl.county.error == true">The county should not contain any number</span>
                      </div>
                    </div>
                    <div class="row">
                      <div class="form-group has-feedback" ng-class="{'has-error' : ensl.prevFirst.error}">
                        <label class="col-xs-5 control-label" for="ensl-prev-first">Enslaver previous first name</label>
                        <div class="col-xs-7">
                          <input type="text" class="form-control" name="ensl-prev-first" id="ensl-prev-first" ng-model="ensl.prevFirst.input" ng-keyup="ensl.prevFirst.check()" ng-init="ensl.prevFirst.input = '<?php echo htmlspecialchars($crowdInfo['ownerprevfname']); ?>'">
                          <span class="glyphicon glyphicon-remove form-control-feedback" ng-show="ensl.prevFirst.error == true"></span>
                        </div>
                        <span class="col-xs-7 col-xs-offset-5 help-block" ng-show="ensl.prevFirst.error == true">The first name should not contain any number</span>
                      </div>
                    </div>
                    <div class="row">
                      <div class="form-group has-feedback" ng-class="{'has-error' : ensl.prevLast.error}">
                        <label class="col-xs-5 control-label" for="ensl-prev-last">Enslaver previous last name</label>
                        <div class="col-xs-7">
                          <input type="text" class="form-control" name="ensl-prev-last" id="ensl-prev-last" ng-model="ensl.prevLast.input" ng-keyup="ensl.prevLast.check()" ng-init="ensl.prevLast.input = '<?php echo htmlspecialchars($crowdInfo['ownerprevlname']); ?>'">
                          <span class="glyphicon glyphicon-remove form-control-feedback" ng-show="ensl.prevLast.error == true"></span>
                        </div>
                        <span class="col-xs-7 col-xs-offset-5 help-block" ng-show="ensl.prevLast.error == true">The last name should not contain any number</span>
                      </div>
                    </div>
                  </fieldset>
            
                  <fieldset ng-show="part == 3">
                    <div class="row">
                      <div class="form-group has-feedback" ng-class="{'has-error' : runw.name.error}">
                        <label class="col-xs-5 control-label" for="runw-name">What was this slave's name?</label>
                        <div class="col-xs-7">
                          <input type="text" class="form-control" name="runw-name" id="runw-name" ng-model="runw.name.input" ng-keyup="runw.name.check()" ng-init="runw.name.input = '<?php echo htmlspecialchars($crowdInfo['slavename']); ?>'">
                          <span class="glyphicon glyphicon-remove form-control-feedback" ng-show="runw.name.error == true"></span>
                        </div>
                        <span class="col-xs-7 col-xs-offset-5 help-block" ng-show="runw.name.error == true">The name should not contain any number</span>
                      </div>
                    </div>
                    <div class="row">
                      <div class="form-group">
                        <label class="col-xs-5 control-label">What is this slave's gender?</label>
                        <div class="col-xs-7">
                          <?php
                          if ($crowdInfo['slavegender'] == "m") {
                            ?>
                            <label for="gender-male" class="radio-inline">
                              <input type="radio" name="runw-gender" value="m" id="gender-male" checked="checked">
                              Male
                            </label>
                            <label for="gender-female" class="radio-inline">
                              <input type="radio" name="runw-gender" value="f" id="gender-female">
                              Female
                            </label>
                            <?php
                          }
                          elseif ($crowdInfo['slavegender'] == "f") {
                            ?>
                            <label for="gender-male" class="radio-inline">
                              <input type="radio" name="runw-gender" value="m" id="gender-male">
                              Male
                            </label>
                            <label for="gender-female" class="radio-inline">
                              <input type="radio" name="runw-gender" value="f" id="gender-female" checked="checked">
                              Female
                            </label>
                            <?php
                          }
                          else {
                            ?>
                            <label for="gender-male" class="radio-inline">
                              <input type="radio" name="runw-gender" value="m" id="gender-male">
                              Male
                            </label>
                            <label for="gender-female" class="radio-inline">
                              <input type="radio" name="runw-gender" value="f" id="gender-female">
                              Female
                            </label>
                            <?php
                          }
                          ?>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="form-group has-feedback" ng-class="{'has-error' : runw.age.error}">
                        <label class="col-xs-5 control-label" for="runw-age">How old is this slave?</label>
                        <div class="col-xs-7">
                          <input type="text" class="form-control" name="runw-age" id="runw-age" ng-model="runw.age.input" ng-keyup="runw.age.check()" ng-init="runw.age.input = <?php echo htmlspecialchars($crowdInfo['slaveage']); ?>">
                          <span class="glyphicon glyphicon-remove form-control-feedback" ng-show="runw.age.error == true"></span>
                        </div>
                        <span class="col-xs-7 col-xs-offset-5 help-block" ng-show="runw.age.error == true">The age should be a number</span>
                      </div>
                    </div>
                    <div class="row">
                      <div class="form-group">
                        <label class="col-xs-5 control-label">Is this age an approximate value?</label>
                        <div class="col-xs-7">
                          <?php
                          if ($crowdInfo['isageapproximate'] == "t") {
                            ?>
                            <label for="approx-yes" class="radio-inline">
                              <input type="radio" name="runw-approx" value="TRUE" id="approx-yes" checked="checked">
                              Yes
                            </label>
                            <label for="approx-no" class="radio-inline">
                              <input type="radio" name="runw-approx" value="FALSE" id="approx-no">
                              No
                            </label>
                            <?php
                          }
                          elseif ($crowdInfo['isageapproximate'] == "f") {
                            ?>
                            <label for="approx-yes" class="radio-inline">
                              <input type="radio" name="runw-approx" value="TRUE" id="approx-yes">
                              Yes
                            </label>
                            <label for="approx-no" class="radio-inline">
                              <input type="radio" name="runw-approx" value="FALSE" id="approx-no" checked="checked">
                              No
                            </label>
                            <?php
                          }
                          else {
                            ?>
                            <label for="approx-yes" class="radio-inline">
                              <input type="radio" name="runw-approx" value="TRUE" id="approx-yes">
                              Yes
                            </label>
                            <label for="approx-no" class="radio-inline">
                              <input type="radio" name="runw-approx" value="FALSE" id="approx-no">
                              No
                            </label>
                            <?php
                          }
                          ?>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="form-group has-feedback" ng-class="{'has-error' : runw.height.error}">
                        <label class="col-xs-5 control-label" for="runw-height">What is this runaway height?</label>
                        <div class="col-xs-7">
                          <input type="text" class="form-control" name="runw-height" id="runw-height" ng-model="runw.height.input" ng-keyup="runw.height.check()" ng-init="runw.height.input = <?php echo htmlspecialchars($crowdInfo['slaveheightinches']); ?>">
                          <span class="glyphicon glyphicon-remove form-control-feedback" ng-show="runw.height.error == true"></span>
                        </div>
                        <span class="col-xs-7 col-xs-offset-5 help-block">Please enter the height in inches, if possible. If not, please make a note of it in the notes field below.</span>
                        <span class="col-xs-7 col-xs-offset-5 help-block" ng-show="runw.height.error == true">The height should be a float</span>
                      </div>
                    </div>
                    <div class="row">
                      <div class="form-group has-feedback" ng-class="{'has-error' : runw.weight.error}">
                        <label class="col-xs-5 control-label" for="runw-weight">How much does this runaway weigh?</label>
                        <div class="col-xs-7">
                          <input type="text" class="form-control" name="runw-weight" id="runw-weight" ng-model="runw.weight.input" ng-keyup="runw.weight.check()" ng-init="runw.weight.input = <?php echo htmlspecialchars($crowdInfo['slaveweightpounds']); ?>">
                          <span class="glyphicon glyphicon-remove form-control-feedback" ng-show="runw.weight.error == true"></span>
                        </div>
                        <span class="col-xs-7 col-xs-offset-5 help-block">Please enter the weight in pounds, if possible. If not, please make a note of it in the notes field below.</span>
                        <span class="col-xs-7 col-xs-offset-5 help-block" ng-show="runw.weight.error == true">The weight should be a float</span>
                      </div>
                    </div>
                    <div class="row">
                      <div class="form-group has-feedback" ng-class="{'has-error' : runw.color.error}">
                        <label class="col-xs-5 control-label" for="runw-color">What color description was given for this slave?</label>
                        <div class="col-xs-7">
                          <input type="text" class="form-control" name="runw-color" id="runw-color" ng-model="runw.color.input" ng-keyup="runw.color.check()" ng-init="runw.color.input = '<?php echo htmlspecialchars($crowdInfo['slavecolor']); ?>'">
                          <span class="glyphicon glyphicon-remove form-control-feedback" ng-show="runw.color.error == true"></span>
                        </div>
                        <span class="col-xs-7 col-xs-offset-5 help-block" ng-show="runw.color.error == true">The color should not contain any number</span>
                      </div>
                    </div>
                    <div class="row">
                      <div class="form-group">
                        <label class="col-xs-5 control-label" for="runw-dress">What was the slave wearing at the time they ran away?</label>
                        <div class="col-xs-7">
                          <input type="text" class="form-control" name="runw-dress" id="runw-dress" value="<?php echo htmlspecialchars($crowdInfo['slavewearingdesc']); ?>">
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="form-group">
                        <label class="col-xs-5 control-label" for="runw-build">How is the slave's build described in this ad?</label>
                        <div class="col-xs-7">
                          <input type="text" class="form-control" name="runw-build" id="runw-build" value="<?php echo htmlspecialchars($crowdInfo['slavebuilddesc']); ?>">
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="form-group has-feedback" ng-class="{'has-error' : runw.lang.error}">
                        <label class="col-xs-5 control-label" for="runw-lang">What language did the slave speak?</label>
                        <div class="col-xs-7">
                          <input type="text" class="form-control" name="runw-lang" id="runw-lang" ng-model="runw.lang.input" ng-keyup="runw.lang.check()" ng-init="runw.lang.input = '<?php echo htmlspecialchars($crowdInfo['slavelanguagespoken']); ?>'">
                          <span class="glyphicon glyphicon-remove form-control-feedback" ng-show="runw.lang.error == true"></span>
                        </div>
                        <span class="col-xs-7 col-xs-offset-5 help-block" ng-show="runw.lang.error == true">The language should not contain any number</span>
                      </div>
                    </div>
                    <div class="row">
                      <div class="form-group">
                        <label class="col-xs-5 control-label" for="runw-scar">Please describe any scars or mutilations that this slave had at the time of their flight</label>
                        <div class="col-xs-7">
                          <input type="text" class="form-control" name="runw-scar" id="runw-scar" value="<?php echo htmlspecialchars($crowdInfo['slavemarksscarsmutilations']); ?>">
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="form-group">
                        <label class="col-xs-5 control-label" for="runw-phys">Were there any other physical attributes given to describe this runaway?</label>
                        <div class="col-xs-7">
                          <input type="text" class="form-control" name="runw-phys" id="runw-phys" value="<?php echo htmlspecialchars($crowdInfo['slavemarksphysicalattributes']); ?>">
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="form-group">
                        <label class="col-xs-5 control-label" for="runw-notes">Notes</label>
                        <div class="col-xs-7">
                          <textarea class="form-control" name="runw-notes" id="runw-notes"><?php echo htmlspecialchars($crowdInfo['runaways_notes']); ?></textarea>
                        </div>
                      </div>
                    </div>
                  </fieldset>

                  <fieldset ng-show="part == 4">
                    <div class="row">
                      <div class="form-group">
                        <label class="col-xs-5 control-label" for="event-state-caught">What state was the slave caught in?</label>
                        <div class="col-xs-7">
                          <select class="form-control" name="event-state-caught" id="event-state-caught">
                            <option value="Unknown">Unknown</option>
                            <?php
                            while ($arr = pg_fetch_array($statesQuery, NULL, PGSQL_ASSOC)) {
                              if (($crowdInfo['stateslavecaught'] != "Unknown") && (isset($crowdInfo['newspaper_state_name']))) {
                                if ($crowdInfo['stateslavecaught'] == $arr['name']) {
                                  ?>
                                  <option value="<?php echo htmlspecialchars($arr['name']); ?>" selected="selected"><?php echo htmlspecialchars($arr['name']); ?></option>
                                  <?php
                                }
                                elseif ($arr['name'] != "Unknown") {
                                  ?>
                                  <option value="<?php echo htmlspecialchars($arr['name']); ?>"><?php echo htmlspecialchars($arr['name']); ?></option>
                                  <?php
                                }
                              }
                              elseif ($arr['name'] != "Unknown") {
                                ?>
                                <option value="<?php echo htmlspecialchars($arr['name']); ?>"><?php echo htmlspecialchars($arr['name']); ?></option>
                                <?php
                              }
                            }

                            pg_result_seek($statesQuery, 0);
                            ?>
                          </select>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="form-group">
                        <label class="col-xs-5 control-label" for="event-state-sold">What state was the slave sold from?</label>
                        <div class="col-xs-7">
                          <select class="form-control" name="event-state-sold" id="event-state-sold">
                            <option value="Unknown">Unknown</option>
                            <?php
                            while ($arr = pg_fetch_array($statesQuery, NULL, PGSQL_ASSOC)) {
                              if (($crowdInfo['soldfromstate'] != "Unknown") && (isset($crowdInfo['newspaper_state_name']))) {
                                if ($crowdInfo['soldfromstate'] == $arr['name']) {
                                  ?>
                                  <option value="<?php echo htmlspecialchars($arr['name']); ?>" selected="selected"><?php echo htmlspecialchars($arr['name']); ?></option>
                                  <?php
                                }
                                elseif ($arr['name'] != "Unknown") {
                                  ?>
                                  <option value="<?php echo htmlspecialchars($arr['name']); ?>"><?php echo htmlspecialchars($arr['name']); ?></option>
                                  <?php
                                }
                              }
                              elseif ($arr['name'] != "Unknown") {
                                ?>
                                <option value="<?php echo htmlspecialchars($arr['name']); ?>"><?php echo htmlspecialchars($arr['name']); ?></option>
                                <?php
                              }
                            }

                            pg_result_seek($statesQuery, 0);
                            ?>
                          </select>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="form-group has-feedback" ng-class="{'has-error' : event.countySold.error}">
                        <label class="col-xs-5 control-label" for="event-county-sold">What county was the slave sold from?</label>
                        <div class="col-xs-7">
                          <input type="text" class="form-control" name="event-county-sold" id="event-county-sold" ng-model="event.countySold.input" ng-keyup="event.countySold.check()" ng-init="event.countySold.input = '<?php echo htmlspecialchars($crowdInfo['soldfromcounty']); ?>'">
                          <span class="glyphicon glyphicon-remove form-control-feedback" ng-show="event.countySold.error == true"></span>
                        </div>
                        <span class="col-xs-7 col-xs-offset-5 help-block" ng-show="event.countySold.error == true">The county should not contain any number</span>
                      </div>
                    </div>
                    <div class="row">
                      <div class="form-group has-feedback" ng-class="{'has-error' : event.citySold.error}">
                        <label class="col-xs-5 control-label" for="event-city-sold">What city was the slave sold from?</label>
                        <div class="col-xs-7">
                          <input type="text" class="form-control" name="event-city-sold" id="event-city-sold" ng-model="event.citySold.input" ng-keyup="event.citySold.check()" ng-init="event.citySold.input = '<?php echo htmlspecialchars($crowdInfo['soldfromcity']); ?>'">
                          <span class="glyphicon glyphicon-remove form-control-feedback" ng-show="event.citySold.error == true"></span>
                        </div>
                        <span class="col-xs-7 col-xs-offset-5 help-block" ng-show="event.citySold.error == true">The city should not contain any number</span>
                      </div>
                    </div>
                    <div class="row">
                      <div class="form-group">
                        <label class="col-xs-5 control-label">Was the slave recently sold?</label>
                        <div class="col-xs-7">
                          <?php
                          if ($crowdInfo['wasslaverecentlysold'] == "t") {
                            ?>
                            <label for="recently-yes" class="radio-inline">
                              <input type="radio" name="event-recently-sold" value="TRUE" id="recently-yes" checked="true">
                              Yes
                            </label>
                            <label for="recently-no" class="radio-inline">
                              <input type="radio" name="event-recently-sold" value="FALSE" id="recently-no">
                              No
                            </label>
                            <?php
                          }
                          elseif ($crowdInfo['wasslaverecentlysold'] == "f") {
                            ?>
                            <label for="recently-yes" class="radio-inline">
                              <input type="radio" name="event-recently-sold" value="TRUE" id="recently-yes">
                              Yes
                            </label>
                            <label for="recently-no" class="radio-inline">
                              <input type="radio" name="event-recently-sold" value="FALSE" id="recently-no" checked="true">
                              No
                            </label>
                            <?php
                          }
                          else {
                            ?>
                            <label for="recently-yes" class="radio-inline">
                              <input type="radio" name="event-recently-sold" value="TRUE" id="recently-yes">
                              Yes
                            </label>
                            <label for="recently-no" class="radio-inline">
                              <input type="radio" name="event-recently-sold" value="FALSE" id="recently-no">
                              No
                            </label>
                            <?php
                          }
                          ?>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="form-group has-feedback" ng-class="{'has-error' : event.where.error}">
                        <label class="col-xs-5 control-label" for="event-where">Where was the runaway headed?</label>
                        <div class="col-xs-7">
                          <input type="text" class="form-control" name="event-where" id="event-where" ng-model="event.where.input" ng-keyup="event.where.check()" ng-init="event.where.input = '<?php echo htmlspecialchars($crowdInfo['headeddesc']); ?>'">
                          <span class="glyphicon glyphicon-remove form-control-feedback" ng-show="event.where.error == true"></span>
                        </div>
                        <span class="col-xs-7 col-xs-offset-5 help-block" ng-show="event.where.error == true">The place should not contain any number</span>
                      </div>
                    </div>
                    <div class="row">
                      <div class="form-group">
                        <label class="col-xs-5 control-label">Was the runaway headed home?</label>
                        <div class="col-xs-7">
                          <?php
                          if ($crowdInfo['washeadedhome'] == "t") {
                            ?>
                            <label for="home-yes" class="radio-inline">
                              <input type="radio" name="event-home" value="TRUE" id="home-yes" checked="checked">
                              Yes
                            </label>
                            <label for="home-no" class="radio-inline">
                              <input type="radio" name="event-home" value="FALSE" id="home-no">
                              No
                            </label>
                            <?php
                          }
                          elseif ($crowdInfo['washeadedhome'] == "f") {
                            ?>
                            <label for="home-yes" class="radio-inline">
                              <input type="radio" name="event-home" value="TRUE" id="home-yes">
                              Yes
                            </label>
                            <label for="home-no" class="radio-inline">
                              <input type="radio" name="event-home" value="FALSE" id="home-no" checked="checked">
                              No
                            </label>
                            <?php
                          }
                          else {
                            ?>
                            <label for="home-yes" class="radio-inline">
                              <input type="radio" name="event-home" value="TRUE" id="home-yes">
                              Yes
                            </label>
                            <label for="home-no" class="radio-inline">
                              <input type="radio" name="event-home" value="FALSE" id="home-no">
                              No
                            </label>
                            <?php
                          }
                          ?>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="form-group">
                        <label class="col-xs-5 control-label">Did they run alone?</label>
                        <div class="col-xs-7">
                          <?php
                          if ($crowdInfo['ranalone'] == "t") {
                            ?>
                            <label for="alone-yes" class="radio-inline">
                              <input type="radio" name="event-alone" value="TRUE" id="alone-yes" checked="checked">
                              Yes
                            </label>
                            <label for="alone-no" class="radio-inline">
                              <input type="radio" name="event-alone" value="FALSE" id="alone-no">
                              No
                            </label>
                            <?php
                          }
                          elseif ($crowdInfo['ranalone'] == "f") {
                            ?>
                            <label for="alone-yes" class="radio-inline">
                              <input type="radio" name="event-alone" value="TRUE" id="alone-yes">
                              Yes
                            </label>
                            <label for="alone-no" class="radio-inline">
                              <input type="radio" name="event-alone" value="FALSE" id="alone-no" checked="checked">
                              No
                            </label>
                            <?php
                          }
                          else {
                            ?>
                            <label for="alone-yes" class="radio-inline">
                              <input type="radio" name="event-alone" value="TRUE" id="alone-yes">
                              Yes
                            </label>
                            <label for="alone-no" class="radio-inline">
                              <input type="radio" name="event-alone" value="FALSE" id="alone-no">
                              No
                            </label>
                            <?php
                          }
                          ?>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="form-group has-feedback" ng-class="{'has-error' : event.number.error}">
                        <label class="col-xs-5 control-label" for="event-number">How many people did this runaway run away with?</label>
                        <div class="col-xs-7">
                          <input type="text" class="form-control" name="event-number" id="event-number" ng-model="event.number.input" ng-keyup="event.number.check()" ng-init="event.number.input = <?php echo htmlspecialchars($crowdInfo['ranwithnumber']); ?>">
                          <span class="glyphicon glyphicon-remove form-control-feedback" ng-show="event.number.error == true"></span>
                        </div>
                        <span class="col-xs-7 col-xs-offset-5 help-block" ng-show="event.number.error == true">The number of people should be a number</span>
                      </div>
                    </div>
                    <div class="row">
                      <div class="form-group">
                        <label class="col-xs-5 control-label">Did he run with his mother and children?</label>
                        <div class="col-xs-7">
                          <?php
                          if ($crowdInfo['motherandchildren'] == "t") {
                            ?>
                            <label for="mother-children-yes" class="radio-inline">
                              <input type="radio" name="event-mother-children" value="TRUE" id="mother-children-yes" checked="checked">
                              Yes
                            </label>
                            <label for="mother-children-no" class="radio-inline">
                              <input type="radio" name="event-mother-children" value="FALSE" id="mother-children-no">
                              No
                            </label>
                            <?php
                          }
                          elseif ($crowdInfo['motherandchildren'] == "f") {
                            ?>
                              <label for="mother-children-yes" class="radio-inline">
                              <input type="radio" name="event-mother-children" value="TRUE" id="mother-children-yes">
                              Yes
                            </label>
                            <label for="mother-children-no" class="radio-inline">
                              <input type="radio" name="event-mother-children" value="FALSE" id="mother-children-no" checked="checked">
                              No
                            </label>
                            <?php
                          }
                          else {
                            ?>
                              <label for="mother-children-yes" class="radio-inline">
                              <input type="radio" name="event-mother-children" value="TRUE" id="mother-children-yes">
                              Yes
                            </label>
                            <label for="mother-children-no" class="radio-inline">
                              <input type="radio" name="event-mother-children" value="FALSE" id="mother-children-no">
                              No
                            </label>
                            <?php
                          }
                          ?>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="form-group">
                        <label class="col-xs-5 control-label">Did the slave run mid-forced migration?</label>
                        <div class="col-xs-7">
                          <?php
                          if ($crowdInfo['ran_mid_forcedmigration'] == "t") {
                            ?>
                            <label for="migration-yes" class="radio-inline">
                              <input type="radio" name="event-migration" value="TRUE" id="migration-yes" checked="checked">
                              Yes
                            </label>
                            <label for="migration-no" class="radio-inline">
                              <input type="radio" name="event-migration" value="FALSE" id="migration-no">
                              No
                            </label>
                            <?php
                          }
                          elseif ($crowdInfo['ran_mid_forcedmigration'] == "f") {
                            ?>
                            <label for="migration-yes" class="radio-inline">
                              <input type="radio" name="event-migration" value="TRUE" id="migration-yes">
                              Yes
                            </label>
                            <label for="migration-no" class="radio-inline">
                              <input type="radio" name="event-migration" value="FALSE" id="migration-no" checked="checked">
                              No
                            </label>
                            <?php
                          }
                          else {
                            ?>
                            <label for="migration-yes" class="radio-inline">
                              <input type="radio" name="event-migration" value="TRUE" id="migration-yes">
                              Yes
                            </label>
                            <label for="migration-no" class="radio-inline">
                              <input type="radio" name="event-migration" value="FALSE" id="migration-no">
                              No
                            </label>
                            <?php
                          }
                          ?>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="form-group">
                        <label class="col-xs-5 control-label">Had the slave been caught before?</label>
                        <div class="col-xs-7">
                          <?php
                          if ($crowdInfo['wascaught'] == "t") {
                            ?>
                            <label for="before-yes" class="radio-inline">
                              <input type="radio" name="event-caught-before" value="TRUE" id="before-yes" checked="checked">
                              Yes
                            </label>
                            <label for="before-no" class="radio-inline">
                              <input type="radio" name="event-caught-before" value="FALSE" id="before-no">
                              No
                            </label>
                            <?php
                          }
                          elseif ($crowdInfo['wascaught'] == "f") {
                            ?>
                            <label for="before-yes" class="radio-inline">
                              <input type="radio" name="event-caught-before" value="TRUE" id="before-yes">
                              Yes
                            </label>
                            <label for="before-no" class="radio-inline">
                              <input type="radio" name="event-caught-before" value="FALSE" id="before-no" checked="checked">
                              No
                            </label>
                            <?php
                          }
                          else {
                            ?>
                            <label for="before-yes" class="radio-inline">
                              <input type="radio" name="event-caught-before" value="TRUE" id="before-yes">
                              Yes
                            </label>
                            <label for="before-no" class="radio-inline">
                              <input type="radio" name="event-caught-before" value="FALSE" id="before-no">
                              No
                            </label>
                            <?php
                          }
                          ?>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="form-group has-feedback" ng-class="{'has-error' : event.reward.error}">
                        <label class="col-xs-5 control-label" for="event-reward">Was there a reward listed? What was the amount?</label>
                        <div class="col-xs-7">
                          <input type="text" class="form-control" name="event-reward" id="event-reward" ng-model="event.reward.input" ng-keyup="event.reward.check()" ng-init="event.reward.input = <?php echo htmlspecialchars($crowdInfo['reward']); ?>">
                          <span class="glyphicon glyphicon-remove form-control-feedback" ng-show="event.reward.error == true"></span>
                        </div>
                        <span class="col-xs-7 col-xs-offset-5 help-block" ng-show="event.reward.error == true">This should be a number</span>
                      </div>
                    </div>
                    <div class="row">
                      <div class="form-group">
                        <label class="col-xs-5 control-label" for="event-notes">Notes</label>
                        <div class="col-xs-7">
                          <textarea class="form-control" name="event-notes" id="event-notes"><?php echo htmlspecialchars($crowdInfo['runawayevents_notes']); ?></textarea>
                        </div>
                      </div>
                    </div>
                  </fieldset>

                  <fieldset ng-show="part == 5">
                    <div class="row">
                      <div class="form-group has-feedback" ng-class="{'has-error' : child.number.error}">
                        <label class="col-xs-5 control-label" for="child-number">How many children did the slave had?</label>
                        <div class="col-xs-7">
                          <?php
                          // Check if some child is existing for this ad in the DB
                          if ((isset($childArray)) && (count($childArray) >= 1) && ($childArray[0]['name'] != "N/A")) {
                            ?>
                            <input type="text" class="form-control" name="child-number" id="child-number" ng-model="child.number.input" ng-keyup="child.number.check()" ng-init="child.number.input = <?php echo htmlspecialchars($childNb); ?>">
                            <?php
                          }
                          else {
                            ?>
                            <input type="text" class="form-control" name="child-number" id="child-number" ng-model="child.number.input" ng-keyup="child.number.check()">
                            <?php
                          }
                          ?>
                          <span class="glyphicon glyphicon-remove form-control-feedback" ng-show="child.number.error == true"></span>
                        </div>
                        <span class="col-xs-7 col-xs-offset-5 help-block">If there are more than 4 children, please make a note in the previous notes field</span>
                        <span class="col-xs-7 col-xs-offset-5 help-block" ng-show="child.number.error == true">The number of children should be a number</span>
                      </div>
                    </div>
                    <div class="row" ng-show="child.number.input >= 1">
                      <div class="form-group has-feedback" ng-class="{'has-error' : child.oneName.error}">
                        <label class="col-xs-5 control-label" for="child-one-name">Child 1 name</label>
                        <div class="col-xs-7">
                          <?php
                          if ((isset($childArray)) && (count($childArray) >= 1) && ($childArray[0]['name'] != "N/A")) {
                            ?>
                            <input type="text" class="form-control" name="child-one-name" id="child-one-name" ng-model="child.oneName.input" ng-keyup="child.oneName.check()" ng-init="child.oneName.input = '<?php echo htmlspecialchars($childArray[0]['name']); ?>'">
                            <?php
                          }
                          else {
                            ?>
                            <input type="text" class="form-control" name="child-one-name" id="child-one-name" ng-model="child.oneName.input" ng-keyup="child.oneName.check()">
                            <?php
                          }
                          ?>
                          <span class="glyphicon glyphicon-remove form-control-feedback" ng-show="child.oneName.error == true"></span>
                        </div>
                        <span class="col-xs-7 col-xs-offset-5 help-block" ng-show="child.oneName.error == true">The name should not contain any number</span>
                      </div>
                    </div>
                    <div class="row" ng-show="child.number.input >= 1">
                      <div class="form-group">
                        <label class="col-xs-5 control-label">Child 1 gender</label>
                        <div class="col-xs-7">
                          <?php
                          if ((isset($childArray)) && (count($childArray) >= 1) && ($childArray[0]['name'] != "N/A")) {
                            if ($childArray[0]['gender'] == "m") {
                              ?>
                              <label for="gender-one-male" class="radio-inline">
                                <input type="radio" name="child-one-gender" value="m" id="gender-one-male" checked="checked">
                                Male
                              </label>
                              <label for="gender-one-female" class="radio-inline">
                                <input type="radio" name="child-one-gender" value="f" id="gender-one-female">
                                Female
                              </label>
                              <?php
                            }
                            elseif ($childArray[0]['gender'] == "f") {
                              ?>
                              <label for="gender-one-male" class="radio-inline">
                                <input type="radio" name="child-one-gender" value="m" id="gender-one-male">
                                Male
                              </label>
                              <label for="gender-one-female" class="radio-inline">
                                <input type="radio" name="child-one-gender" value="f" id="gender-one-female" checked="checked">
                                Female
                              </label>
                              <?php
                            }
                            else {
                              ?>
                              <label for="gender-one-male" class="radio-inline">
                                <input type="radio" name="child-one-gender" value="m" id="gender-one-male">
                                Male
                              </label>
                              <label for="gender-one-female" class="radio-inline">
                                <input type="radio" name="child-one-gender" value="f" id="gender-one-female">
                                Female
                              </label>
                              <?php
                            }
                          }
                          else {
                            ?>
                            <label for="gender-one-male" class="radio-inline">
                              <input type="radio" name="child-one-gender" value="m" id="gender-one-male">
                              Male
                            </label>
                            <label for="gender-one-female" class="radio-inline">
                              <input type="radio" name="child-one-gender" value="f" id="gender-one-female">
                              Female
                            </label>
                            <?php
                          }
                          ?>
                        </div>
                      </div>
                    </div>
                    <div class="row" ng-show="child.number.input >= 1">
                      <div class="form-group has-feedback" ng-class="{'has-error' : child.oneAge.error}">
                        <label class="col-xs-5 control-label" for="child-one-age">Child 1 age</label>
                        <div class="col-xs-7">
                          <?php
                          if ((isset($childArray)) && (count($childArray) >= 1) && ($childArray[0]['name'] != "N/A")) {
                            ?>
                            <input type="text" class="form-control" name="child-one-age" id="child-one-age" ng-model="child.oneAge.input" ng-keyup="child.oneAge.check()" ng-init="child.oneAge.input = <?php echo htmlspecialchars($childArray[0]['age']); ?>">
                            <?php
                          }
                          else {
                            ?>
                            <input type="text" class="form-control" name="child-one-age" id="child-one-age" ng-model="child.oneAge.input" ng-keyup="child.oneAge.check()">
                            <?php
                          }
                          ?>
                          <span class="glyphicon glyphicon-remove form-control-feedback" ng-show="child.oneAge.error == true"></span>
                        </div>
                        <span class="col-xs-7 col-xs-offset-5 help-block" ng-show="child.oneAge.error == true">The age should be a number</span>
                      </div>
                    </div>
                    <div class="row" ng-show="child.number.input >= 2">
                      <div class="form-group has-feedback" ng-class="{'has-error' : child.twoName.error}">
                        <label class="col-xs-5 control-label" for="child-two-name">Child 2 name</label>
                        <div class="col-xs-7">
                          <?php
                          if ((isset($childArray)) && (count($childArray) >= 2)) {
                            ?>
                            <input type="text" class="form-control" name="child-two-name" id="child-two-name" ng-model="child.twoName.input" ng-keyup="child.twoName.check()" ng-init="child.twoName.input = '<?php echo htmlspecialchars($childArray[1]['name']); ?>'">
                            <?php
                          }
                          else {
                            ?>
                            <input type="text" class="form-control" name="child-two-name" id="child-two-name" ng-model="child.twoName.input" ng-keyup="child.twoName.check()">
                            <?php
                          }
                          ?>
                          <span class="glyphicon glyphicon-remove form-control-feedback" ng-show="child.twoName.error == true"></span>
                        </div>
                        <span class="col-xs-7 col-xs-offset-5 help-block" ng-show="child.twoName.error == true">The name should not contain any number</span>
                      </div>
                    </div>
                    <div class="row" ng-show="child.number.input >= 2">
                      <div class="form-group">
                        <label class="col-xs-5 control-label">Child 2 gender</label>
                        <div class="col-xs-7">
                          <?php
                          if ((isset($childArray)) && (count($childArray) >= 2)) {
                            if ($childArray[1]['gender'] == "m") {
                              ?>
                              <label for="gender-two-male" class="radio-inline">
                                <input type="radio" name="child-two-gender" value="m" id="gender-two-male" checked="checked">
                                Male
                              </label>
                              <label for="gender-two-female" class="radio-inline">
                                <input type="radio" name="child-two-gender" value="f" id="gender-two-female">
                                Female
                              </label>
                              <?php
                            }
                            elseif ($childArray[1]['gender'] == "f") {
                              ?>
                              <label for="gender-two-male" class="radio-inline">
                                <input type="radio" name="child-two-gender" value="m" id="gender-two-male">
                                Male
                              </label>
                              <label for="gender-two-female" class="radio-inline">
                                <input type="radio" name="child-two-gender" value="f" id="gender-two-female" checked="checked">
                                Female
                              </label>
                              <?php
                            }
                            else {
                              ?>
                              <label for="gender-two-male" class="radio-inline">
                                <input type="radio" name="child-two-gender" value="m" id="gender-two-male">
                                Male
                              </label>
                              <label for="gender-two-female" class="radio-inline">
                                <input type="radio" name="child-two-gender" value="f" id="gender-two-female">
                                Female
                              </label>
                              <?php
                            }
                          }
                          else {
                            ?>
                            <label for="gender-two-male" class="radio-inline">
                              <input type="radio" name="child-two-gender" value="m" id="gender-two-male">
                              Male
                            </label>
                            <label for="gender-two-female" class="radio-inline">
                              <input type="radio" name="child-two-gender" value="f" id="gender-two-female">
                              Female
                            </label>
                            <?php
                          }
                          ?>
                        </div>
                      </div>
                    </div>
                    <div class="row" ng-show="child.number.input >= 2">
                      <div class="form-group has-feedback" ng-class="{'has-error' : child.twoAge.error}">
                        <label class="col-xs-5 control-label" for="child-two-age">Child 2 age</label>
                        <div class="col-xs-7">
                          <?php
                          if ((isset($childArray)) && (count($childArray) >= 2)) {
                            ?>
                            <input type="text" class="form-control" name="child-two-age" id="child-two-age" ng-model="child.twoAge.input" ng-keyup="child.twoAge.check()" ng-init="child.twoAge.input = <?php echo htmlspecialchars($childArray[1]['age']); ?>">
                            <?php
                          }
                          else {
                            ?>
                            <input type="text" class="form-control" name="child-two-age" id="child-two-age" ng-model="child.twoAge.input" ng-keyup="child.twoAge.check()">
                            <?php
                          }
                          ?>
                          <span class="glyphicon glyphicon-remove form-control-feedback" ng-show="child.twoAge.error == true"></span>
                        </div>
                        <span class="col-xs-7 col-xs-offset-5 help-block" ng-show="child.twoAge.error == true">The age should be a number</span>
                      </div>
                    </div>
                    <div class="row" ng-show="child.number.input >= 3">
                      <div class="form-group has-feedback" ng-class="{'has-error' : child.threeName.error}">
                        <label class="col-xs-5 control-label" for="child-three-name">Child 3 name</label>
                        <div class="col-xs-7">
                          <?php
                          if ((isset($childArray)) && (count($childArray) >= 3)) {
                            ?>
                            <input type="text" class="form-control" name="child-three-name" id="child-three-name" ng-model="child.threeName.input" ng-keyup="child.threeName.check()" ng-init="child.threeName.input = '<?php echo htmlspecialchars($childArray[2]['name']); ?>'">
                            <?php
                          }
                          else {
                            ?>
                            <input type="text" class="form-control" name="child-three-name" id="child-three-name" ng-model="child.threeName.input" ng-keyup="child.threeName.check()">
                            <?php
                          }
                          ?>
                          <span class="glyphicon glyphicon-remove form-control-feedback" ng-show="child.threeName.error == true"></span>
                        </div>
                        <span class="col-xs-7 col-xs-offset-5 help-block" ng-show="child.threeName.error == true">The name should not contain any number</span>
                      </div>
                    </div>
                    <div class="row" ng-show="child.number.input >= 3">
                      <div class="form-group">
                        <label class="col-xs-5 control-label">Child 3 gender</label>
                        <div class="col-xs-7">
                          <?php
                          if ((isset($childArray)) && (count($childArray) >= 3)) {
                            if ($childArray[2]['gender'] == "m") {
                              ?>
                              <label for="gender-three-male" class="radio-inline">
                                <input type="radio" name="child-three-gender" value="m" id="gender-three-male" checked="checked">
                                Male
                              </label>
                              <label for="gender-three-female" class="radio-inline">
                                <input type="radio" name="child-three-gender" value="f" id="gender-three-female">
                                Female
                              </label>
                              <?php
                            }
                            elseif ($childArray[2]['gender'] == "f") {
                              ?>
                              <label for="gender-three-male" class="radio-inline">
                                <input type="radio" name="child-three-gender" value="m" id="gender-three-male">
                                Male
                              </label>
                              <label for="gender-three-female" class="radio-inline">
                                <input type="radio" name="child-three-gender" value="f" id="gender-three-female" checked="checked">
                                Female
                              </label>
                              <?php
                            }
                            else {
                              ?>
                              <label for="gender-three-male" class="radio-inline">
                                <input type="radio" name="child-three-gender" value="m" id="gender-three-male">
                                Male
                              </label>
                              <label for="gender-three-female" class="radio-inline">
                                <input type="radio" name="child-three-gender" value="f" id="gender-three-female">
                                Female
                              </label>
                              <?php
                            }
                          }
                          else {
                            ?>
                            <label for="gender-three-male" class="radio-inline">
                              <input type="radio" name="child-three-gender" value="m" id="gender-three-male">
                              Male
                            </label>
                            <label for="gender-three-female" class="radio-inline">
                              <input type="radio" name="child-three-gender" value="f" id="gender-three-female">
                              Female
                            </label>
                            <?php
                          }
                          ?>
                        </div>
                      </div>
                    </div>
                    <div class="row" ng-show="child.number.input >= 3">
                      <div class="form-group has-feedback" ng-class="{'has-error' : child.threeAge.error}">
                        <label class="col-xs-5 control-label" for="child-three-age">Child 3 age</label>
                        <div class="col-xs-7">
                          <?php
                          if ((isset($childArray)) && (count($childArray) >= 3)) {
                            ?>
                            <input type="text" class="form-control" name="child-three-age" id="child-three-age" ng-model="child.threeAge.input" ng-keyup="child.threeAge.check()" ng-init="child.threeAge.input = <?php echo htmlspecialchars($childArray[2]['age']); ?>">
                            <?php
                          }
                          else {
                            ?>
                            <input type="text" class="form-control" name="child-three-age" id="child-three-age" ng-model="child.threeAge.input" ng-keyup="child.threeAge.check()">
                            <?php
                          }
                          ?>
                          <span class="glyphicon glyphicon-remove form-control-feedback" ng-show="child.threeAge.error == true"></span>
                        </div>
                        <span class="col-xs-7 col-xs-offset-5 help-block" ng-show="child.threeAge.error == true">The age should be a number</span>
                      </div>
                    </div>
                    <div class="row" ng-show="child.number.input >= 4">
                      <div class="form-group has-feedback" ng-class="{'has-error' : child.fourName.error}">
                        <label class="col-xs-5 control-label" for="child-four-name">Child 4 name</label>
                        <div class="col-xs-7">
                          <?php
                          if ((isset($childArray)) && (count($childArray) >= 4)) {
                            ?>
                            <input type="text" class="form-control" name="child-four-name" id="child-four-name" ng-model="child.fourName.input" ng-keyup="child.fourName.check()" ng-init="child.fourName.input = '<?php echo htmlspecialchars($childArray[3]['name']); ?>'">
                            <?php
                          }
                          else {
                            ?>
                            <input type="text" class="form-control" name="child-four-name" id="child-four-name" ng-model="child.fourName.input" ng-keyup="child.fourName.check()">
                            <?php
                          }
                          ?>
                          <span class="glyphicon glyphicon-remove form-control-feedback" ng-show="child.fourName.error == true"></span>
                        </div>
                        <span class="col-xs-7 col-xs-offset-5 help-block" ng-show="child.fourName.error == true">The name should not contain any number</span>
                      </div>
                    </div>
                    <div class="row" ng-show="child.number.input >= 4">
                      <div class="form-group">
                        <label class="col-xs-5 control-label">Child 4 gender</label>
                        <div class="col-xs-7">
                          <?php
                          if ((isset($childArray)) && (count($childArray) >= 4)) {
                            if ($childArray[3]['gender'] == "m") {
                              ?>
                              <label for="gender-four-male" class="radio-inline">
                                <input type="radio" name="child-four-gender" value="m" id="gender-four-male" checked="checked">
                                Male
                              </label>
                              <label for="gender-four-female" class="radio-inline">
                                <input type="radio" name="child-four-gender" value="f" id="gender-four-female">
                                Female
                              </label>
                              <?php
                            }
                            elseif ($childArray[3]['gender'] == "f") {
                              ?>
                              <label for="gender-four-male" class="radio-inline">
                                <input type="radio" name="child-four-gender" value="m" id="gender-four-male">
                                Male
                              </label>
                              <label for="gender-four-female" class="radio-inline">
                                <input type="radio" name="child-four-gender" value="f" id="gender-four-female" checked="checked">
                                Female
                              </label>
                              <?php
                            }
                            else {
                              ?>
                              <label for="gender-four-male" class="radio-inline">
                                <input type="radio" name="child-four-gender" value="m" id="gender-four-male">
                                Male
                              </label>
                              <label for="gender-four-female" class="radio-inline">
                                <input type="radio" name="child-four-gender" value="f" id="gender-four-female">
                                Female
                              </label>
                              <?php
                            }
                          }
                          else {
                            ?>
                            <label for="gender-four-male" class="radio-inline">
                              <input type="radio" name="child-four-gender" value="m" id="gender-four-male">
                              Male
                            </label>
                            <label for="gender-four-female" class="radio-inline">
                              <input type="radio" name="child-four-gender" value="f" id="gender-four-female">
                              Female
                            </label>
                            <?php
                          }
                          ?>
                        </div>
                      </div>
                    </div>
                    <div class="row" ng-show="child.number.input >= 4">
                      <div class="form-group has-feedback" ng-class="{'has-error' : child.fourAge.error}">
                        <label class="col-xs-5 control-label" for="child-four-age">Child 4 age</label>
                        <div class="col-xs-7">
                          <?php
                          if ((isset($childArray)) && (count($childArray) >= 4)) {
                            ?>
                            <input type="text" class="form-control" name="child-four-age" id="child-four-age" ng-model="child.fourAge.input" ng-keyup="child.fourAge.check()" ng-init="child.fourAge.input = <?php echo htmlspecialchars($childArray[3]['age']); ?>">
                            <?php
                          }
                          else {
                            ?>
                            <input type="text" class="form-control" name="child-four-age" id="child-four-age" ng-model="child.fourAge.input" ng-keyup="child.fourAge.check()">
                            <?php
                          }
                          ?>
                          <span class="glyphicon glyphicon-remove form-control-feedback" ng-show="child.fourAge.error == true"></span>
                        </div>
                        <span class="col-xs-7 col-xs-offset-5 help-block" ng-show="child.fourAge.error == true">The age should be a number</span>
                      </div>
                    </div>
                    <h4 class="text-center">Additional Information</h4>
                    <div class="row">
                      <div class="form-group">
                        <label class="col-xs-5 control-label" for="completion">What is the degree of completion of this ad?</label>
                        <div class="col-xs-7">
                          <select class="form-control" name="completion" id="completion">
                            <?php
                            $numbers = array(0, 10, 20, 30, 40, 50, 60, 70, 80, 90, 100);
                            foreach ($numbers as $nb) {
                              if ($nb == $crowdInfo['ad_completion']) {
                                ?>
                                <option value="<?php echo $nb; ?>" selected="selected"><?php echo $nb; ?>%</option>
                                <?php
                              }
                              else {
                                ?>
                                <option value="<?php echo $nb; ?>"><?php echo $nb; ?>%</option>
                                <?php
                              }
                            }
                            ?>
                          </select>
                        </div>
                        <span class="col-xs-7 col-xs-offset-5 help-block">Please enter a value that estimates the overall completion of this ad. If you think that the transcribed text and the form are fully completed, put 100%</span>
                      </div>
                    </div>
                  </fieldset>

                  <div class="row">
                    <div class="form-group prev-next">
                      <a href="#" class="btn btn-info pull-left" ng-click="previous()" ng-if="part > 1"><span class="glyphicon glyphicon-chevron-left"></span> Previous</a>
                      <a href="#" class="btn btn-info pull-right" ng-click="next()" ng-if="part < 5">Next <span class="glyphicon glyphicon-chevron-right"></span></a>
                      <button type="submit" class="btn btn-info pull-right" ng-click="submitForm('home.php')" ng-if="part == 5">Submit <span class="glyphicon glyphicon-send"></span></button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </form>
        </article>
      </section>

      <?php
    }
    else {
      ?>
      <section class="text-center">
        <h4>You must click on one ad to come to this page!</h4>
      </section>
      <?php
    }
    ?>
  </body>
</html>