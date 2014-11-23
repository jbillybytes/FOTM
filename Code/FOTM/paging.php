<?php
function echoPage($pageNumber) {
  if (isset($_GET[$_GET['criteria']])) {
    echo "newspaper_ads.php?search=".$_GET['search']."&amp;criteria=".$_GET['criteria']."&amp;".$_GET['criteria']."=".$_GET[$_GET['criteria']]."&amp;page=".$pageNumber;
  }
  else {
    echo "newspaper_ads.php?search=".$_GET['search']."&amp;criteria=".$_GET['criteria']."&amp;page=".$pageNumber;
  }
}
// If we have less than 5 pages, we don't need to display all the pages numbers
if ($lastPage < 5) {
  switch ($lastPage) {
    case '1':
      if ($page == 1) {
        // Do not display any pages
      }
      break;
    
    case '2':
      if ($page == 1) {
        ?>
        <li class="active"><a href="<?php echoPage(1); ?>">1</a></li>
        <li><a href="<?php echoPage(2); ?>">2</a></li>
        <?php
      }
      elseif ($page == 2) {
        ?>
        <li><a href="<?php echoPage(1); ?>">1</a></li>
        <li class="active"><a href="<?php echoPage(2); ?>">2</a></li>
        <?php
      }
      break;

    case '3':
      if ($page == 1) {
        ?>
        <li class="active"><a href="<?php echoPage(1); ?>">1</a></li>
        <li><a href="<?php echoPage(2); ?>">2</a></li>
        <li><a href="<?php echoPage(3); ?>">3</a></li>
        <?php
      }
      elseif ($page == 2) {
        ?>
        <li><a href="<?php echoPage(1); ?>">1</a></li>
        <li class="active"><a href="<?php echoPage(2); ?>">2</a></li>
        <li><a href="<?php echoPage(3); ?>">3</a></li>
        <?php
      }
      elseif ($page == 3) {
        ?>
        <li><a href="<?php echoPage(1); ?>">1</a></li>
        <li><a href="<?php echoPage(2); ?>">2</a></li>
        <li class="active"><a href="<?php echoPage(3); ?>">3</a></li>
        <?php
      }
      break;

    case '4':
      if ($page == 1) {
        ?>
        <li class="active"><a href="<?php echoPage(1); ?>">1</a></li>
        <li><a href="<?php echoPage(2); ?>">2</a></li>
        <li><a href="<?php echoPage(3); ?>">3</a></li>
        <li><a href="<?php echoPage(4); ?>">4</a></li>
        <?php
      }
      elseif ($page == 2) {
        ?>
        <li><a href="<?php echoPage(1); ?>">1</a></li>
        <li class="active"><a href="<?php echoPage(2); ?>">2</a></li>
        <li><a href="<?php echoPage(3); ?>">3</a></li>
        <li><a href="<?php echoPage(4); ?>">4</a></li>
        <?php
      }
      elseif ($page == 3) {
        ?>
        <li><a href="<?php echoPage(1); ?>">1</a></li>
        <li><a href="<?php echoPage(2); ?>">2</a></li>
        <li class="active"><a href="<?php echoPage(3); ?>">3</a></li>
        <li><a href="<?php echoPage(4); ?>">4</a></li>
        <?php
      }
      elseif ($page == 4) {
        ?>
        <li><a href="<?php echoPage(1); ?>">1</a></li>
        <li><a href="<?php echoPage(2); ?>">2</a></li>
        <li><a href="<?php echoPage(3); ?>">3</a></li>
        <li class="active"><a href="<?php echoPage(4); ?>">4</a></li>
        <?php
      }
      break;
  }

}
else {
  ?>
  <li><a href="<?php echoPage(1); ?>"><span aria-hidden="true">&laquo;</span><span class="sr-only">First</span></a></li>
  <?php
  if ($page == 1) {
    ?>
    <li class="disabled"><a href="#"><span aria-hidden="true">&lsaquo;</span><span class="sr-only">Previous</span></a></li>
    <li class="active"><a href="<?php echoPage(1); ?>">1</a></li>
    <li><a href="<?php echoPage(2); ?>">2</a></li>
    <li><a href="<?php echoPage(3); ?>">3</a></li>
    <li><a href="<?php echoPage(4); ?>">4</a></li>
    <li><a href="<?php echoPage(5); ?>">5</a></li>
    <li><a href="<?php echo echoPage($page + 1); ?>"><span aria-hidden="true">&rsaquo;</span><span class="sr-only">Next</span></a></li>
    <?php
  }
  elseif ($page == 2) {
    ?>
    <li><a href="<?php echoPage($page - 1); ?>"><span aria-hidden="true">&lsaquo;</span><span class="sr-only">Previous</span></a></li>
    <li><a href="<?php echo echoPage(1); ?>">1</a></li>
    <li class="active"><a href="<?php echoPage(2); ?>">2</a></li>
    <li><a href="<?php echoPage(3); ?>">3</a></li>
    <li><a href="<?php echoPage(4); ?>">4</a></li>
    <li><a href="<?php echoPage(5); ?>">5</a></li>
    <li><a href="<?php echoPage($page + 1); ?>"><span aria-hidden="true">&rsaquo;</span><span class="sr-only">Next</span></a></li>
    <?php
  }
  elseif ($page == $lastPage - 1) {
    ?>
    <li><a href="<?php echoPage($page - 1); ?>"><span aria-hidden="true">&lsaquo;</span><span class="sr-only">Previous</span></a></li>
    <li><a href="<?php echoPage($lastPage - 4); ?>"><?php echo $lastPage - 4; ?></a></li>
    <li><a href="<?php echoPage($lastPage - 3); ?>"><?php echo $lastPage - 3; ?></a></li>
    <li><a href="<?php echoPage($lastPage - 2); ?>"><?php echo $lastPage - 2; ?></a></li>
    <li class="active"><a href="<?php echoPage($lastPage - 1); ?>"><?php echo $lastPage - 1; ?></a></li>
    <li><a href="<?php echoPage($lastPage); ?>"><?php echo $lastPage; ?></a></li>
    <li><a href="<?php echoPage($page + 1); ?>"><span aria-hidden="true">&rsaquo;</span><span class="sr-only">Next</span></a></li>
    <?php
  }
  elseif ($page == $lastPage) {
    ?>
    <li><a href="<?php echoPage($page - 1); ?>"><span aria-hidden="true">&lsaquo;</span><span class="sr-only">Previous</span></a></li>
    <li><a href="<?php echoPage($lastPage - 4); ?>"><?php echo $lastPage - 4; ?></a></li>
    <li><a href="<?php echoPage($lastPage - 3); ?>"><?php echo $lastPage - 3; ?></a></li>
    <li><a href="<?php echoPage($lastPage - 2); ?>"><?php echo $lastPage - 2; ?></a></li>
    <li><a href="<?php echoPage($lastPage - 1); ?>"><?php echo $lastPage - 1; ?></a></li>
    <li class="active"><a href="<?php echoPage($lastPage); ?>"><?php echo $lastPage; ?></a></li>
    <li class="disabled"><a href="#"><span aria-hidden="true">&rsaquo;</span><span class="sr-only">Next</span></a></li>
    <?php
  }
  else {
    ?>
    <li><a href="<?php echoPage($page - 1); ?>"><span aria-hidden="true">&lsaquo;</span><span class="sr-only">Previous</span></a></li>
    <li><a href="<?php echoPage($page - 2); ?>"><?php echo $page - 2; ?></a></li>
    <li><a href="<?php echoPage($page - 1); ?>"><?php echo $page - 1; ?></a></li>
    <li class="active"><a href="<?php echoPage($page); ?>"><?php echo $page; ?></a></li>
    <li><a href="<?php echoPage($page + 1); ?>"><?php echo $page + 1; ?></a></li>
    <li><a href="<?php echoPage($page + 2); ?>"><?php echo $page + 2; ?></a></li>
    <li><a href="<?php echoPage($page + 1); ?>"><span aria-hidden="true">&rsaquo;</span><span class="sr-only">Next</span></a></li>
    <?php
  }
  ?>
  <li><a href="<?php echoPage($lastPage); ?>"><span aria-hidden="true">&raquo;</span><span class="sr-only">Last</span></a></li>
  <?php
}

?>