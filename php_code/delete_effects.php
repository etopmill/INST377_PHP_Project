<?php # delete_effects.php

// This page deletes a effects history entry .
// This page is accessed through EffectsPagaeable.php.

// we will default the temperature, sea level and us wildfire source fireign keys 
// to 1, 2, 3 as foreign keys because they are not supposed to change.  
// If they do then we will need to update the script
$global_temperature_source = 1;
$sea_level_source = 2;
$us_wildfire_source = 3;

// Check for a valid act. ID, through GET or POST.
if ( (isset($_GET['id'])) && (is_numeric($_GET['id'])) ) { // Accessed through view_acts.php
	$id = $_GET['id'];
} elseif ( (isset($_POST['id'])) && (is_numeric($_POST['id'])) ) { // Form has been submitted.
	$id = $_POST['id'];
} else { // No valid ID, kill the script.
	echo '<h1>Page Error</h1>
	<p>This page has been accessed in error.<br /><br /></p>';
	exit();
}

include ('../MysqlConnectToGlobalWarming.php'); // Connect to the db.

// Check if the form has been submitted.
if (isset($_POST['id'])) {

	if ($_POST['sure'] == 'Yes') { // Delete the record.
		
		$query = "DELETE FROM effects_history WHERE effects_history.year=$id;";		
		$result_del = @mysqli_query ($dbc, $query); // Run the query.
		if (mysqli_affected_rows($dbc) == 1) { // If it ran OK.

		// Create the result page.
		echo '<h1>Delete the effect history entry?</h1>
		<p>The record has been deleted.<br /><br /></p>';	
	} 
		
	 else { // If the query did not run OK.
			echo '<h1>System Error</h1>
			<p>The effects_history entry could not be deleted due to a system error.</p>'; // Public message.
			echo '<p>' . mysqli_error($dbc) . '<br /><br />Query: ' . $query . '</p>'; // Debugging message.
		}
	
	} else { // Wasn't sure about deleting the movie.
		echo '<h1>Delete an affect</h1>';

  echo'<p>The record has NOT been deleted.<br /><br /></p>';	
	} 


} //End of if(isset()) block
 else { // Show the form.

	// Retrieve the effects history entry.
    $q1 = "SELECT effects_history.year, global_temperature, sea_level, us_wildfire from effects_history ";  
    $q1 .= "WHERE effects_history.year = $id;";    
	$result = @mysqli_query ($dbc, $q1); // Run the query.
    //echo "q1=" . $q1 . "   result=" . $result;
	
	if (mysqli_num_rows($result) == 1) { // Valid act. ID, show the form.

		// Get the effects entry.
		$row = mysqli_fetch_array ($result);
		
		// Create the form.
		echo '<h2>Delete an affect</h2>
            <form action="delete_effects.php" method="post">
            <h3>year: ' . $row[0] . '<h3>
            <h3>global_temperature: ' . $row[1] . '</h3>
            <h3>sea_level: ' . $row[2] . '</h3>
            <h3>us_wildfire: ' . $row[3] . '</h3>
	 
            <p>Are you sure you want to delete this  affect?<br />
            <input type="radio" name="sure" value="Yes" /> Yes 
            <input type="radio" name="sure" value="No" checked="checked" /> No</p>
            <p><input type="submit" name="submit" value="Submit" /></p>
            <input type="hidden" name="id" value="' . $id . '" />
            </form>';
	
	} else { // Not a valid act. ID.
		echo '<h1>Page Error</h1>
		<p>There is no record with the given ID.<br /><br /></p>';
	}

} // End of the main Submit conditional.

mysqli_close($dbc); // Close the database connection.

?>