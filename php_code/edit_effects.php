<?php #edit_effects.php

// This page edits an effects history record entry.

$page_title = 'Edit an Effect History Record';

// Check for a valid effects history year as id, through GET or POST.
if ( (isset($_GET['id'])) && (is_numeric($_GET['id'])) ) { // Accessed through EffectsPageable.php
	$id = $_GET['id'];
} elseif ( (isset($_POST['id'])) && (is_numeric($_POST['id'])) ) { // Form has been submitted.
	$id = $_POST['id'];
} else { // No valid ID, kill the script.
	echo '<h1 id="mainhead">Page Error</h1>
	<p class="error">This page has been accessed in error. No valid id!!</p><p><br /><br /></p>';
	exit();
}

include ('../MysqlConnectToGlobalWarming.php');

// Connect to the db.

// Check if the form has been submitted.
if (isset($_POST['submitted'])) {

	$errors = array(); // Initialize error array.
	
	// Check for a year.
	if (empty($_POST['year'])) 
	{
		$errors[] = 'You forgot to enter the year of the effects history record.';
	} 
	else 
	{
		$year = $_POST['year'];
	}

	// Check for population.
	if (empty($_POST['global_temperature'])) 
	{
		$errors[] = 'You forgot to enter the global temperature of the effects history.';
	} 
	else 
	{
		$global_temperature = $_POST['global_temperature'];
	}
	
	// Check for size in sq km.
	if (empty($_POST['sea_level'])) 
	{
		$errors[] = 'You forgot to enter the sea level for the effects history record.';
	} 
	else 
	{
		$sea_level = $_POST['sea_level'];
	}
    
    // check for US wildfires in acres
    if (empty($_POST['us_wildfire'])) 
	{
		$errors[] = 'You forgot to enter the US wildfore acres';
	} 
	else 
	{
		$us_wildfire = $_POST['us_wildfire'];
	}
	
	if (empty($errors)) { // If everything's OK.
	
			// Make the query.
			$query = "UPDATE effects_history SET year='$year', global_temperature='$global_temperature', sea_level='$sea_level', us_wildfire='$us_wildfire', fk_global_temperature_id=1, fk_sea_level_information_id=2, fk_us_wildfire_information_id=3 WHERE effects_history.year = $id";
			$result = @mysqli_query ($dbc, $query); // Run the query.
			if ((mysqli_affected_rows($dbc) == 1) || (mysqli_affected_rows($dbc) == 0)) { // If it ran OK.
			
				// Print a message.
				echo '<h1 id="mainhead">Edit an effects history record.</h1>
				<p>The effects history record has been edited.</p><p><br /><br /></p>';	
				exit();
							
			} else { // If it did not run OK.
				echo '<h1 id="mainhead">System Error</h1>
				<p class="error">The effects history record could not be edited due to a system error. We apologize for any inconvenience.</p>'; // Public message.
				echo '<p>' . mysqli_error($dbc) . '<br /><br />Query: ' . $query . '</p>'; // Debugging message.
				exit();
			}
				
	} // End of if (empty($errors)) IF.
	
	else { // Report the errors.
	
		echo '<h1 id="mainhead">Error!</h1>
		<p class="error">The following error(s) occurred:<br />';
		foreach ($errors as $msg) { // Print each error.
			echo " - $msg<br />\n";
		} // End of foreach
		echo '</p><p>Please try again.</p><p><br /></p>';
		
	}  // End of report errors else()

} // End of submit conditional.

// Always show the form.

// Retrieve the effects history record information.
$q1 = "SELECT effects_history.year, global_temperature, sea_level, us_wildfire FROM effects_history ";
$q1 .= "WHERE effects_history.year = $id;";
$result = @mysqli_query ($dbc, $q1); // Run the query.

if (mysqli_num_rows($result) == 1) { // Valid movie ID, show the form.

	// Get the movie's information.
	$row = mysqli_fetch_array ($result);
    $this_year=$row['year'];
    $this_global_temperature=$row['global_temperature'];
    $this_sea_level=$row['sea_level'];
    $this_us_wildfire=$row['us_wildfire'];
	// Create the form.


echo '<h2>Edit an effects history record</h2>

<form action="edit_effects.php" method="post">';

echo "<p>Year: <input type='text' name='year' size='4' maxlength='4' value='$this_year";
echo "'>&nbsp;&nbsp;&nbsp;</p>	
	<p>global_temperature: <input type='text' name='global_temperature' size='4' 
	maxlength='4' value='$this_global_temperature";
echo "'> </p>
	<p>Sea Level: <input type='text' name='sea_level' size='6' 
	maxlength='6' value='$this_sea_level";
echo "'> </p>
	<p>US Wildfire acres: <input type='text' name='us_wildfire' size='3' 
	maxlength='3' value='$this_us_wildfire";
echo "'> </p>
    <input type='hidden' name='submitted' value='TRUE' />
    <input type='hidden' name='id' value='" . $id . "' />";
echo "<p><input type='submit' name='submit' value='Modify Effects History record' /></p>
</form>";

} else { // Not a valid year.
	echo '<h1 id="mainhead">Page Error</h1>
	<p class="error">This page has been accessed in error. Not a valid year.</p><p><br /><br /></p>';
}

mysqli_close($dbc); // Close the database connection.
		
?>