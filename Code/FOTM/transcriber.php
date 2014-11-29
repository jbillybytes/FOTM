<!DOCTYPE html>
<html lang="en" ng-app="fotmApp">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Transcriber - FOTM</title>

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
  </head>
  <body ng-controller="transcrCtrl">
    <!-- Bootstrap core JavaScript
    ================================================== -->
    <script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/angular.js"></script>
    <script src="js/fotm.js"></script>
    
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
                <li class="active"><a href="#" class="disable">Transcriber</a></li>
                <li><a href="#" class="disable">Data parser</a></li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </nav>
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
              Your data has not been saved, some errors were present in your form.<br>
              Redirecting to the transcriber page...
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
              Your data has been successfully saved!
            </div>
          </div>
        </div>
        <?php
      }

      pg_close();
    }
    else {
      session_start();
    }

    // Check if some ID is existing
    if (isset($_GET['id'])) {
      $id = $_GET['id'];

      // Store the ad ID in a session variable to access it in the next page
      $_SESSION['adid'] = $id;

      $db = pg_connect('host=localhost dbname=FOTM user=postgres password=root');

      // Get the corresponding ad
      $result = pg_query("SELECT * FROM ads WHERE adid = '$id'");
      if (!$result) {
        $errormessage = pg_last_error();
        echo "Error with select query: " . $errormessage;
      }

      $arr = pg_fetch_array($result, NULL, PGSQL_ASSOC);
      ?>
      <section class="container">
        <article class="row">
          <div class="col-md-6">
            <div class="panel panel-primary">
              <div class="panel-heading">
                <h2 class="panel-title">Original AD</h2>
              </div>
              <div class="panel-body">
                <!-- Ad image file -->
                <img src="imagehd.php?file=<?php echo $arr['filepath']. '\\' .$arr['filename']; ?>">
              </div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="panel panel-primary">
              <div class="panel-heading">
                <h2 class="panel-title">Transcribed AD</h2>
              </div>
              <div class="panel-body">
                <form action="crowdsourcing_form.php" method="post">
                  <div class="row">
                    <div class="form-group">
                      <label for="transcribed control-label">Enter your transcription here:</label>
                      <textarea class="form-control" rows="15" name="transcribed" id="transcribed"><?php echo htmlspecialchars($arr['ocr_text']); ?></textarea>
                    </div>
                  </div>
                  <div class="row">
                    <div class="form-group">
                      <label class="control-label" for="tag">Enter any tags to help you find this ad later, separated by comas</label>
                      <input type="text" class="form-control" name="tag" id="tag" value="<?php echo htmlspecialchars($arr['tags']); ?>">
                    </div>
                  </div>
                  <button class="btn btn-info pull-right" type="submit">Submit <span class="glyphicon glyphicon-share-alt"></span></button>
                </form>
              </div>
            </div>
          </div>
        </article>
      </section>
      <?php
    }
    // Else, tell the user to select one ad
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