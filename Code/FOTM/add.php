<html> 
    <body> 
        <?php 
        $db = pg_connect('host=localhost dbname=FOTM user=postgres password=root'); 
		
		// Newspaper Info
        $newsState = pg_escape_string($_POST['news-state']); 
        $newsName = pg_escape_string($_POST['news-name']); 
        $newsCity = pg_escape_string($_POST['news-city']); 
		$newsCounty = pg_escape_string($_POST['news-county']); 
		$newsDistDate = pg_escape_string($_POST['news-date']); 
		$sourceUrl = pg_escape_string($_POST['news-url']); 
		$pageNumber = pg_escape_string($_POST['news-page']); 
		$newsNote = pg_escape_string($_POST['news-notes']); 
		$isJailor = pg_escape_string($_POST['news-jailor']); 

		// Enslaver Info
		$enslFirst = pg_escape_string($_POST['ensl-first']); 
		$enslLast = pg_escape_string($_POST['ensl-last']); 
		$enslPrevFirst = pg_escape_string($_POST['ensl-prev-first']); 
		$enslPrevLast = pg_escape_string($_POST['ensl-prev-last']); 

		// Runaway Info
		$slaveName = pg_escape_string($_POST['runw-name']);
		$slaveGender = pg_escape_string($_POST['runw-gender']);
		$slaveAge = pg_escape_string($_POST['runw-age']);
		$slaveHeight = pg_escape_string($_POST['runw-height']);
		$slaveWeight = pg_escape_string($_POST['runw-weight']);
		$buildDesc = pg_escape_string($_POST['runw-build']);
		$phyatTributes = pg_escape_string($_POST['runw-phys']);
		$notes = pg_escape_string($_POST['runw-notes']);
		$color = pg_escape_string($_POST['runw-color']);
		$wearingDesc = pg_escape_string($_POST['runw-dress']);
		$marksScarMutilation = pg_escape_string($_POST['runw-scar']);

		// Runaway Event
		$stateCaught = pg_escape_string($_POST['event-state-caught']);
		$stateSold = pg_escape_string($_POST['event-state-sold']);
		$countySold = pg_escape_string($_POST['event-county-sold']);
		$citySold = pg_escape_string($_POST['event-city-sold']);
		$stateRanFrom  = pg_escape_string($_POST['event-state-run']);
		$countyRanFrom = pg_escape_string($_POST['event-county-run']);
		$cityRanFrom = pg_escape_string($_POST['event-city-run']);
		$dateEntered = date("m/d/y"); 
		$wearingDesc = pg_escape_string($_POST['runw-dress']);
		$language = pg_escape_string($_POST['runw-lang']);
		$recentlySold = pg_escape_string($_POST['event-recently-sold']);
		$headedDesc = pg_escape_string($_POST['event-where']);
		$ranAlone = pg_escape_string($_POST['event-alone']);
		$ranWithNumber = pg_escape_string($_POST['event-number']);
		$migration = pg_escape_string($_POST['event-migration']);
		$wasCaught = pg_escape_string($_POST['event-caught-before']);
		$reward = pg_escape_string($_POST['event-reward']);
		$ageApproximate = pg_escape_string($_POST['runw-approx']);
		
		// Runaway Family and Children
		$childNum = pg_escape_string($_POST['child-number']);
		// Store the children info as an array
		// E.g. '(james,12,m)','(sara,13,f)'
		$childArray = '';
		if ($childNum == 0) {
			$childArray = "'(N/A,0,N)'";
		}
		if ($childNum >= 1) {
			$childArray = "'(". pg_escape_string($_POST['child-one-name']).",".pg_escape_string($_POST['child-one-age']).",".pg_escape_string($_POST['child-one-gender']).")'" ;
		}
		if ($childNum >= 2) {
			$childArray = $childArray.","."'(". pg_escape_string($_POST['child-two-name']).",".pg_escape_string($_POST['child-two-age']).",".pg_escape_string($_POST['child-two-gender']).")'" ;
		}
		if ($childNum >= 3) {
			$childArray = $childArray.","."'(". pg_escape_string($_POST['child-three-name']).",".pg_escape_string($_POST['child-three-age']).",".pg_escape_string($_POST['child-three-gender']).")'" ;
		}
		if ($childNum >= 4) {
			$childArray = $childArray.","."'(". pg_escape_string($_POST['child-four-name']).",".pg_escape_string($_POST['child-four-age']).",".pg_escape_string($_POST['child-four-gender']).")'" ;
		}

		// Construct the query
		$query = "select INSERT_INTO_FOTM('".$newsState."', '".$newsCounty."', '".$newsCity."', '".$newsName."', '".$enslFirst."', '".$enslLast."', '".$enslPrevFirst."', '".$enslPrevLast."', '".$newsDistDate."', '".$sourceUrl."', '".$newsNote."', ".$pageNumber.", ".$isJailor.", '".$slaveName."', '".$slaveGender."', '".$slaveAge."', '".$slaveHeight."' , '".$slaveWeight."', '".$buildDesc."', '".$phyatTributes."', '".$notes."', '".$color."',(ARRAY[". $childArray."])::childinfo[], '".$stateCaught."', '".$stateSold."', '".$countySold."', '".$citySold."', '".$stateRanFrom."', '".$countyRanFrom."', '".$cityRanFrom."', '".$dateEntered."', '".$wearingDesc."', '".$language."', ".$recentlySold.", '".$headedDesc."', TRUE, ".$ranAlone.", ".$ranWithNumber.", TRUE, ".$migration.", 'Ran away notes', '".$marksScarMutilation."', ".$ageApproximate.", ".$wasCaught.", ".$reward.")";
		
		// Check query
		//echo $query;

		/** Sample query
		$query = "select INSERT_INTO_FOTM ('California','Los Angeles','Los Angeles','California Times','Bob','johnson','john','mary','10/10/1883','http:\\www.google.com',
		 'Fled from LA',3,TRUE,'Mike','m','23','5','63','thin','scar in right hand','very tall','black',(ARRAY['(james,12,m)','(sara,13,f)'])::childinfo[],
		'NewYork','NewYork','Tompkins','Ithaca','California','Los Angeles','Los Angeles','11/11/1912','Long shirt','Spanish',FALSE,'headed to newyork',
		TRUE,FALSE,4,TRUE,FALSE,'escaped from enslaver','scar in right cheek',TRUE,TRUE,1000)";**/
        
		$result = pg_query($query); 
        if (!$result) { 
            $errormessage = pg_last_error(); 
            echo "Error with database insertion: " . $errormessage; 
            exit(); 
        }
        //printf ("These values were inserted into the database correctly");
		/**
		echo '<script language="javascript">';
		echo 'alert("Data has been successfully recorded in the FOTM database!")';
		echo '</script>';
		
		//exit(); 

		

		if(showAlertBox("Data has been successfully recorded in the FOTM database!")) {
			window.location = 'crowdsourcing_form.html';
		} 

		function showAlertBox($messageToDisplay) {
			echo '<script type="text/javascript">'.'alert("'.$messageToDisplay.'")</script>';
			return true;
		}**/

		echo "<script>
			alert('Data has been successfully recorded in the FOTM database!');
			window.location.href='crowdsourcing_form.html';
		</script>";

        pg_close();
        ?> 

    </body> 
</html>