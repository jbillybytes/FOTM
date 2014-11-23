<html> 
    <body> 
        <?php 
        $db = pg_connect('host=localhost dbname=FOTM user=postgres password=root'); 
		
		// Ad ID
		session_start();
		$id = $_SESSION['adid'];

		// Newspaper Info
        $newsState = pg_escape_string($_POST['news-state']); 
        $newsName = pg_escape_string($_POST['news-name']); 
        $newsCity = pg_escape_string($_POST['news-city']); 
		$newsCounty = pg_escape_string($_POST['news-county']); 
		// Test if the variable exists, else initialise it to "NULL"
		// Same for other fields below
		if ($_POST['news-date'] != "") {
			$newsDistDate = "'" . pg_escape_string($_POST['news-date']) . "'";
		}
		else {
			$newsDistDate = "NULL";
		}
		$sourceUrl = pg_escape_string($_POST['news-url']); 
		if ($_POST['news-page'] != "") {
			$pageNumber = pg_escape_string($_POST['news-page']);
		}
		else {
			$pageNumber = "NULL";
		}
		$newsNote = pg_escape_string($_POST['news-notes']);
		if (isset($_POST['news-jailor'])) {
			$isJailor = pg_escape_string($_POST['news-jailor']);
		}
		else {
			$isJailor = "NULL";
		}

		// Enslaver Info
		$enslFirst = pg_escape_string($_POST['ensl-first']);
		$enslLast = pg_escape_string($_POST['ensl-last']);
		$enslPrevFirst = pg_escape_string($_POST['ensl-prev-first']);
		$enslPrevLast = pg_escape_string($_POST['ensl-prev-last']);
		$enslState = pg_escape_string($_POST['ensl-state']);
		$enslCounty = pg_escape_string($_POST['ensl-county']);
		$enslCity = pg_escape_string($_POST['ensl-city']);

		// Runaway Info
		$slaveName = pg_escape_string($_POST['runw-name']);
		if (isset($_POST['runw-gender'])) {
			$slaveGender = pg_escape_string($_POST['runw-gender']);
		}
		else {
			$slaveGender = "N";
		}
		if ($_POST['runw-age'] != "") {
			$slaveAge = pg_escape_string($_POST['runw-age']);
		}
		else {
			$slaveAge = "NULL";
		}
		if ($_POST['runw-height'] != "") {
			$slaveHeight = pg_escape_string($_POST['runw-height']);
		}
		else {
			$slaveHeight = "NULL";
		}
		if ($_POST['runw-weight'] != "") {
			$slaveWeight = pg_escape_string($_POST['runw-weight']);
		}
		else {
			$slaveWeight = "NULL";
		}
		$buildDesc = pg_escape_string($_POST['runw-build']);
		$physAttributes = pg_escape_string($_POST['runw-phys']);
		$notes = pg_escape_string($_POST['runw-notes']);
		$color = pg_escape_string($_POST['runw-color']);
		$wearingDesc = pg_escape_string($_POST['runw-dress']);
		$marksScarMutilation = pg_escape_string($_POST['runw-scar']);

		// Runaway Event
		$stateCaught = pg_escape_string($_POST['event-state-caught']);
		$stateSold = pg_escape_string($_POST['event-state-sold']);
		$countySold = pg_escape_string($_POST['event-county-sold']);
		$citySold = pg_escape_string($_POST['event-city-sold']);
		$dateEntered = date("m/d/y"); 
		$language = pg_escape_string($_POST['runw-lang']);
		if (isset($_POST['event-recently-sold'])) {
			$recentlySold = pg_escape_string($_POST['event-recently-sold']);
		}
		else {
			$recentlySold = "NULL";
		}
		$headedDesc = pg_escape_string($_POST['event-where']);
		if (isset($_POST['event-home'])) {
			$headedHome = pg_escape_string($_POST['event-home']);
		}
		else {
			$headedHome = "NULL";
		}
		if (isset($_POST['event-alone'])) {
			$ranAlone = pg_escape_string($_POST['event-alone']);
		}
		else {
			$ranAlone = "NULL";
		}
		if ($_POST['event-number'] != "") {
			$ranWithNumber = pg_escape_string($_POST['event-number']);
		}
		else {
			$ranWithNumber = "NULL";
		}
		if (isset($_POST['event-mother-children'])) {
			$motherChildren = pg_escape_string($_POST['event-mother-children']);
		}
		else {
			$motherChildren = "NULL";
		}
		if (isset($_POST['event-migration'])) {
			$migration = pg_escape_string($_POST['event-migration']);
		}
		else {
			$migration = "NULL";
		}
		if (isset($_POST['event-caught-before'])) {
			$wasCaught = pg_escape_string($_POST['event-caught-before']);
		}
		else {
			$wasCaught = "NULL";
		}
		if ($_POST['event-reward'] != "") {
			$reward = pg_escape_string($_POST['event-reward']);
		}
		else {
			$reward = "NULL";
		}
		if (isset($_POST['runw-approx'])) {
			$ageApproximate = pg_escape_string($_POST['runw-approx']);
		}
		else {
			$ageApproximate = "NULL";
		}
		$eventNotes = pg_escape_string($_POST['event-notes']);
		
		// Runaway Family and Children
		$childNum = pg_escape_string($_POST['child-number']);
		if (isset($_POST['child-one-gender'])) {
			$oneGender = pg_escape_string($_POST['child-one-gender']);
		}
		else {
			$oneGender = "N";
		}
		if (isset($_POST['child-two-gender'])) {
			$twoGender = pg_escape_string($_POST['child-two-gender']);
		}
		else {
			$twoGender = "N";
		}
		if (isset($_POST['child-three-gender'])) {
			$threeGender = pg_escape_string($_POST['child-three-gender']);
		}
		else {
			$threeGender = "N";
		}
		if (isset($_POST['child-four-gender'])) {
			$fourGender = pg_escape_string($_POST['child-four-gender']);
		}
		else {
			$fourGender = "N";
		}
		// Store the children info as an array
		// E.g. '(james,12,m)','(sara,13,f)'
		$childArray = '';
		if ($childNum == 0) {
			$childArray = "'(N/A,0,N)'";
		}
		if ($childNum >= 1) {
			$childArray = "'(". pg_escape_string($_POST['child-one-name']).",".pg_escape_string($_POST['child-one-age']).",".$oneGender.")'" ;
		}
		if ($childNum >= 2) {
			$childArray = $childArray.","."'(". pg_escape_string($_POST['child-two-name']).",".pg_escape_string($_POST['child-two-age']).",".$twoGender.")'" ;
		}
		if ($childNum >= 3) {
			$childArray = $childArray.","."'(". pg_escape_string($_POST['child-three-name']).",".pg_escape_string($_POST['child-three-age']).",".$threeGender.")'" ;
		}
		if ($childNum >= 4) {
			$childArray = $childArray.","."'(". pg_escape_string($_POST['child-four-name']).",".pg_escape_string($_POST['child-four-age']).",".$fourGender.")'" ;
		}

		// Completion
		$completion = pg_escape_string($_POST['completion']);

		// Construct the query
		$query = "select insert_into_fotm('".$id."', '".$newsState."', '".$newsCounty."', '".$newsCity."', '".$newsName."', '".$enslFirst."', '".$enslLast."', '".$enslPrevFirst."', '".$enslPrevLast."', ".$newsDistDate.", '".$sourceUrl."', '".$newsNote."', ".$pageNumber.", ".$isJailor.", '".$slaveName."', '".$slaveGender."', ".$slaveAge.", ".$slaveHeight.", ".$slaveWeight.", '".$buildDesc."', '".$physAttributes."', '".$notes."', '".$color."',(ARRAY[". $childArray."])::childinfo[], '".$stateCaught."', '".$stateSold."', '".$countySold."', '".$citySold."', '".$dateEntered."', '".$wearingDesc."', '".$language."', ".$recentlySold.", '".$headedDesc."', ".$headedHome.", ".$ranAlone.", ".$ranWithNumber.", ".$motherChildren.", ".$migration.", '".$eventNotes."', '".$marksScarMutilation."', ".$ageApproximate.", ".$wasCaught.", ".$reward.", '".$enslState."', '".$enslCity."', '".$enslCounty."', ".$completion.")"; 

		// Sample query
		// $query = "select INSERT_INTO_FOTM ('6bb36211-26fb-41a0-b75c-ae2408ba7317', 'California', 'Los Angeles', 'Los Angeles', 'California Times', 'Bob', 'johnson', 'john', 'mary', '10/10/1883', 'http:\\www.google.com', 'Fled from LA', 3, TRUE, 'Mike', 'm', 23, 5, 63, 'thin', 'scar in right hand', 'very tall', 'black', (ARRAY['(james,12,m)','(sara,13,f)'])::childinfo[], 'New York', 'New York', 'Tompkins', 'Ithaca', 'California', 'Los Angeles', 'Los Angeles', '12/12/1912', 'Long shirt', 'Spanish', FALSE, 'headed to newyork', TRUE, FALSE, 4, TRUE, FALSE, 'escaped from enslaver', 'scar in right cheek', TRUE, TRUE, 1000, "California", "Los Angeles", "Los Angeles", 30)";
		
		// Check query
		// echo $query;
		?>
    </body> 
</html>