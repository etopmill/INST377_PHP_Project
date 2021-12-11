<?php # delete_country.php

// This page deletes a country demographic entry .
// This page is accessed through CountriesPagaeable.php.

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
		
		$query = "DELETE FROM continents_countries WHERE continents_countries.index=$id;";		
		$result_del = @mysqli_query ($dbc, $query); // Run the query.
		if (mysqli_affected_rows($dbc) == 1) { // If it ran OK.

		// Create the result page.
		echo '<h1>Delete a Country Demographic entry?</h1>
		<p>The record has been deleted.<br /><br /></p>';	
	} 
		
	 else { // If the query did not run OK.
			echo '<h1>System Error</h1>
			<p>The country demographic entry could not be deleted due to a system error.</p>'; // Public message.
			echo '<p>' . mysqli_error($dbc) . '<br /><br />Query: ' . $query . '</p>'; // Debugging message.
		}
	
	} else { // Wasn't sure about deleting the movie.
		echo '<h1>Delete an Actor/Actress</h1>';

  echo'<p>The record has NOT been deleted.<br /><br /></p>';	
	} 


} //End of if(isset()) block
 else { // Show the form.

	// Retrieve the actor/actress information.
    $q1 = "SELECT continents_countries.index, year, population, size_in_sq_km, life_expectancy, countries.name AS country, continents.name AS continent FROM continents_countries ";
    $q1 .= "JOIN countries ON fk_country_id = countries.country_id ";
    $q1 .= "JOIN continents ON fk_continent_id = continents.continent_id ";
    $q1 .= "WHERE continents_countries.index = $id;";    
	$result = @mysqli_query ($dbc, $q1); // Run the query.
    //echo "q1=" . $q1 . "   result=" . $result;
	
	if (mysqli_num_rows($result) == 1) { // Valid act. ID, show the form.

		// Get the actor/actress information.
		$row = mysqli_fetch_array ($result);
		
		// Create the form.
		echo '<h2>Delete an Actor/Actress</h2>
	<form action="delete_country.php" method="post">
    <h3>Index: ' . $row[0] . '<h3>
	<h3>Year: ' . $row[1] . '</h3>
	<h3>Population: ' . $row[2] . '</h3>
	<h3>Size in Square Kilometers: ' . $row[3] . '</h3>
	<h3>Life Expectancy: ' . $row[4] . '</h3>
	<h3>Country: ' . $row[5] . '</h3>
	<h3>Continent: ' . $row[6] . '</h3>
	<p>Are you sure you want to delete this country demograhic?<br />
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