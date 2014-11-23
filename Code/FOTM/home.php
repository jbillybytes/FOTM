<!DOCTYPE html>
<html lang="en" ng-app="fotmApp">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Freedom on the Move</title>

    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/fotm_home.css" rel="stylesheet">
    <link rel="shortcut icon" href="img/fotm_icon.jpg">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

	<!-- Start WOWSlider.com HEAD section --> <!-- add to the <head> of your page -->
	<link rel="stylesheet" type="text/css" href="engine1/style.css" />
	<script type="text/javascript" src="engine1/jquery.js"></script>
	<!-- End WOWSlider.com HEAD section -->

  </head>
  <body ng-controller="searchCtrl">
    <!-- Bootstrap core JavaScript
    ================================================== -->
    <script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/angular.js"></script>
    <script src="js/fotm.js"></script>

    <header class="cornell-header">
      <div class="container">
        <div class="row">
          <div class="col-xs-6">
            <a href="http://www.cornell.edu"><img src="img/cornell.gif" alt="Cornell University"></a>
          </div>
          <div class="col-xs-6 cornell-search">
            <a href="http://www.cornell.edu/search/">Search Cornell</a>
          </div>
        </div>
      </div>
    </header>
  
    <?php
    // Check if we are coming back from third tab
    if ($_POST) {
      include "submit_crowd_info.php";

      $result = pg_query($query); 
    
      if (!$result) {
        ?>
        <div id="form-failure" class="text-center">
          <div class="panel panel-primary">
            <div class="panel-body">
              Your data has not been inserted, some errors were present in your form.<br>
              Redirecting to the home page...
            </div>
          </div>
        </div>
        <?php
      }
      else {
        ?>
        <div id="form-success" class="text-center">
          <div class="panel panel-primary">
            <div class="panel-body">
              Your data has been successfully inserted in the database!
            </div>
          </div>
        </div>
        <?php
      }

      pg_close();
    }
    ?>

    <br>
    <br>

    <!-- Start slider -->
    <div id="wowslider-container1" ng-hide="slider">
      <div class="ws_images">
        <ul>
        	<li><img src="img/slides/1.jpg" alt="1" title="1" id="wows1_0"/></li>
        	<li><img src="img/slides/2.jpg" alt="2" title="2" id="wows1_1"/></li>
        	<li><img src="img/slides/3.jpg" alt="3" title="3" id="wows1_2"/></li>
        	<li><img src="img/slides/4.jpg" alt="4" title="4" id="wows1_3"/></li>
        	<li><img src="img/slides/5.jpg" alt="5" title="5" id="wows1_4"/></li>
        	<li><img src="img/slides/6.jpg" alt="6" title="6" id="wows1_5"/></li>
        </ul>
      </div>
      <div class="ws_bullets">
        <div>
        	<a href="#" title="1"><img src="img/tooltips/1.jpg" alt="1"/>1</a>
        	<a href="#" title="2"><img src="img/tooltips/2.jpg" alt="2"/>2</a>
        	<a href="#" title="3"><img src="img/tooltips/3.jpg" alt="3"/>3</a>
        	<a href="#" title="4"><img src="img/tooltips/4.jpg" alt="4"/>4</a>
        	<a href="#" title="5"><img src="img/tooltips/5.jpg" alt="5"/>5</a>
        	<a href="#" title="6"><img src="img/tooltips/6.jpg" alt="6"/>6</a>
        </div>
      </div>
      <div class="ws_shadow">
      </div>
    </div>	
    <script type="text/javascript" src="engine1/wowslider.js"></script>
    <script type="text/javascript" src="engine1/script.js"></script>
    <!-- End slider -->
    
    <?php
    $db = pg_connect('host=localhost dbname=FOTM user=postgres password=root');
    
    // Get states that have at least one newpaper
    $statesQuery = pg_query("SELECT name FROM states WHERE stateid IN (SELECT DISTINCT ON (stateid) stateid FROM newspapers) ORDER BY name");

    if (!$statesQuery) {
      $errormessage = pg_last_error();
      echo "Error with select states query: " . $errormessage;
      exit();
    }
    ?>

    <section class="container">
      <div class="panel panel-primary col-sm-12">
        <div class="panel-body text-center">
          <div class= "welcome" ng-hide="welcome">
            <h2>Welcome to Freedom on the Move website</h2>
            <div class="well">
              <a href="#" ng-hide = "click" ng-click="slider = !slider; show = !show; welcome = !welcome; click = !click" style="color:grey"> - Click to view our data - </a>
            </div>
          </div>
          <form class="form-search" id="search-form" action="newspaper_ads.php" method="GET">
            <fieldset class="row" ng-show="show">
              <div class="legend">Please choose a way to retrieve the ads</div>
              <div class="col-sm-5">
                <label for="random">
                  <div class="row">
                    Randomly
                  </div>
                  <div class="row">
                    <input type="radio" name="search" id="random" value="random" ng-model="search">
                  </div>
                </label>
              </div>
              <div class="col-sm-2">
                OR
              </div>
              <div class="col-sm-5">
                <div class="row">
                  <label for="criteria">
                    <div class="row">
                      By criteria
                    </div>
                    <div class="row">
                      <input type="radio" name="search" id="criteria" value="criteria" ng-model="search">
                    </div>
                  </label>
                </div>
              </div>
            </fieldset>
            <fieldset class="row" ng-show="search == 'criteria'">
              <div class="legend">Select one of the following criterias</div>
              <div class="col-sm-4">
                <label for="newspapers">
                  <div class="row">
                    <input type="radio" name="criteria" id="newspapers" value="newspapers" ng-model="criteria">
                  </div>
                  <div class="row">
                    By newspaper name
                  </div>
                </label>
                <div class="row" ng-show="criteria == 'newspapers'">
                  <select class="form-control" name="newspapers">
                    <option selected disabled hidden value=''></option>
                    <option value="CharlestonCourier">Charleston Courier</option>
                    <option value="MobileRegister">Mobile Register</option>
                    <option value="RichmondEnquirer">Richmond Enquirer</option>
                  </select>
                </div>
              </div>
              <div class="col-sm-4">
                <label for="states">
                  <div class="row">
                    <input type="radio" name="criteria" id="states" value="states" ng-model="criteria">
                  </div>
                  <div class="row">
                    By state name
                  </div>
                </label>
                <div class="row" ng-show="criteria == 'states'">
                  <select class="form-control" name="states">
                    <option selected disabled hidden value=''></option>
                    <?php
                    // Get the states from the DB query; if the state corresponds to the one that was previously entered, select it
                    while ($arr = pg_fetch_array($statesQuery, NULL, PGSQL_ASSOC)) {
                      ?>
                      <option value="<?php echo htmlspecialchars($arr['name']); ?>"><?php echo htmlspecialchars($arr['name']); ?></option>
                      <?php
                    }

                    pg_close();
                    ?>
                  </select>
                </div>
                <div class="row" ng-show="criteria == 'states'">
                  <p>This state corresponds to the state of the newspaper</p>
                </div>
              </div>
              <div class="col-sm-4">
                <label for="tags">
                  <div class="row">
                    <input type="radio" name="criteria" id="tags" value="tags" ng-model="criteria">
                  </div>
                  <div class="row">
                    By tag
                  </div>
                </label>
                <div class="row" ng-show="criteria == 'tags'">
                  <input class="form-control" type="text" name="tags" id="tags-input" placeholder="Enter any tag here">
                </div>
                <div class="row" ng-show="criteria == 'tags'">
                  <p>Please separate the tags with comas</p>
                </div>
              </div>
            </fieldset>
            <button class="btn btn-info btn-lg btn-search" type="submit" ng-show="search == 'random' || criteria != undefined"><span class="glyphicon glyphicon-search"></span></button>
          </form>
        </div>
      </div>
    </section>
  </body>
</html>