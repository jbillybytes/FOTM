<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Runaway Ads - FOTM</title>

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
  <body>

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    
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
                <li class="active"><a href="#" class="disable">Runaway Ads</a></li>
                <li><a href="#" class="disable">Transcriber</a></li>
                <li><a href="#" class="disable">Data parser</a></li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </nav>

    <section class="container well">
      <div class="col-sm-12">
        <?php
        // Connection to the db to get the files for the ads-->
        $db = pg_connect('host=localhost dbname=FOTM user=postgres password=root');

        // If the user chose the random search
        if ((isset($_GET['search'])) && ($_GET['search'] == 'random')) {

          // Get 12 ads randomly
          $result = pg_query("SELECT * FROM ads ORDER BY RANDOM() LIMIT 10");
          if (!$result) {
            $errormessage = pg_last_error();
            echo "Error with database insertion: " . $errormessage;
            exit();
          }
          ?>
          <!-- Display all the ads in a thumbnail list -->
          <div class="thumbs">
            <ul>
              <!-- PHP file displaying the ads -->
              <?php include "ads_thumbnail.php"; ?>
            </ul>
          </div>
          <?php
        }
        // Else, if the user chose one criteria
        elseif ((isset($_GET['search'])) && ($_GET['search'] == 'criteria') && (isset($_GET['criteria']))) {
          if ((isset($_GET[$_GET['criteria']])) && ($_GET['criteria'] == "newspapers")) {
            $news_search = $_GET['newspapers'];

            $nbQuery = "SELECT COUNT(*) FROM ads WHERE filename ~* '$news_search'";
            $adsQuery = "SELECT * FROM ads WHERE filename ~* '$news_search'";
          }
          elseif ((isset($_GET[$_GET['criteria']])) && ($_GET['criteria'] == "states")) {
            $state_search = $_GET['states'];

            $nbQuery = "SELECT COUNT(ads.*) FROM ads 
            INNER JOIN ads_to_runaways ON ads.adid=ads_to_runaways.adid
            INNER JOIN runawayads ON ads_to_runaways.runawayadid=runawayads.runaway_ad_id
            INNER JOIN newspapereditions ON runawayads.newspapereditionid=newspapereditions.newspapereditionid
            INNER JOIN newspapers ON newspapereditions.newspaperid=newspapers.newspaperid
            INNER JOIN states ON newspapers.stateid=states.stateid
            WHERE states.name='$state_search'";

            $adsQuery = "SELECT ads.* FROM ads 
            INNER JOIN ads_to_runaways ON ads.adid=ads_to_runaways.adid
            INNER JOIN runawayads ON ads_to_runaways.runawayadid=runawayads.runaway_ad_id
            INNER JOIN newspapereditions ON runawayads.newspapereditionid=newspapereditions.newspapereditionid
            INNER JOIN newspapers ON newspapereditions.newspaperid=newspapers.newspaperid
            INNER JOIN states ON newspapers.stateid=states.stateid
            WHERE states.name='$state_search'";
          }
          elseif ((isset($_GET[$_GET['criteria']])) && ($_GET['criteria'] == "tags")) {
            $tag_search = $_GET['tags'];

            $tags = preg_split("/\s*,\s*/", $tag_search);

            // Search if all the tags are present in one ad, without taking the case into account
            $nbQuery = "SELECT COUNT(*) FROM ads WHERE";
            $adsQuery = "SELECT * FROM ads WHERE";
            for ($i=0; $i < count($tags); $i++) { 
              if ($i == 0) {
                $nbQuery .= " tags ~* '$tags[$i]'";
                $adsQuery .= " tags ~* '$tags[$i]'";
              }
              else {
                $nbQuery .= " OR tags ~* '$tags[$i]'";
                $adsQuery .= " OR tags ~* '$tags[$i]'";
              }
            }
          }
          // If the user enters an empty field for his tag, or a criteria that does not exist, or does not select any newspaper or state, display all the ads
          else {
            // Create the queries: one to get the number of ads, one to get the ads
            $nbQuery = "SELECT COUNT(*) FROM ads";
            $adsQuery = "SELECT * FROM ads";
          }

          // Get the number of ads
          $query = pg_query($nbQuery);
          if (!$query) {
            $errormessage = pg_last_error();
            echo "Error with database select: " . $errormessage;
            exit();
          }

          $totalAds = pg_fetch_array($query, NULL, PGSQL_ASSOC);
          $totalAds = $totalAds['count'];

          if ($totalAds == 0) {
            ?>
            <section class="text-center">
              <h3>Sorry, no ad matches your criteria!</h3>
            </section>
            <?php
          }

          // Max number of ads per page
          $limit = 15;
          if (isset($_GET['page'])) {
            $page = (int) $_GET['page'];
            $start = ($page - 1) * $limit;
          }
          else {
            $page = 1; // Current page
            $start = 0; // Start of the query
          }
          // Last page for this query
          $lastPage = ceil($totalAds / $limit);

          // Get the corresponding ads for the current page, sorted by completion
          $adsQuery .= " ORDER BY ad_completion LIMIT $limit OFFSET $start";
          $result = pg_query($adsQuery);
          if (!$result) {
            $errormessage = pg_last_error();
            echo "Error with database insertion: " . $errormessage;
            exit();
          }

          ?>
          <!-- Display all the ads in a thumbnail list -->
          <div class="thumbs">
            <!-- <ul> -->
              <!-- PHP file displaying the ads -->
              <?php include "ads_thumbnail.php"; ?>
            <!-- </ul> -->
          </div>
          <div class="clear"></div>
          <div class="text-center">
            <ul class="pagination">
              <!-- PHP file creating the pagination -->
              <?php include "paging.php"; ?>
            </ul>
          </div>
          <?php
        }
        // Else, tell the user to select one criteria
        else {
          ?>
          <section class="text-center">
            <h3>Please select a searching criteria before coming to this page!</h3>
          </section>
          <?php
        }
        pg_close();
        ?>
      </div>
    </section>
  </body>
</html>