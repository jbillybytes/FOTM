<?php
// Displays the list of ads in the newspaper ads page

// File that contains functions to retrieve information from filename
include "get_file_info.php";

while ($arr = pg_fetch_array($result, NULL, PGSQL_ASSOC)) {
?>
<div class="panel panel-primary thumbnail">
  <img src="image.php?file=<?php echo $arr['filepath']. '\\' .$arr['filename']; ?>">
  <span>
    <div class="title">
      <?php
      if (getName($arr['filename']) != "") {
        echo getName($arr['filename']);
      }
      else {
        echo "Name missing";
      }
      ?>
    </div>
    <div class="date">
      <?php
      if (getDistributionDate($arr['filename']) != "") {
        echo getDistributionDate($arr['filename']);
      }
      else {
        echo "Date missing";
      }
      ?>
    </div>
    <div class="desc">
      <a class="btn btn-info" href="transcriber.php?id=<?php echo $arr['adid'] ?>">Transcribe</a>
    </div>
    <div class="progress">
      <div class="progress-bar progress-bar-success progress-bar-striped" role="progressbar" aria-valuenow="<?php echo $arr['ad_completion']; ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $arr['ad_completion']; ?>%;"><?php echo $arr['ad_completion']; ?>%</div>
    </div>
  </span>
</div>

<?php
}
?>