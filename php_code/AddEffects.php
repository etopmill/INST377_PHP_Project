<?php # AddCountries.php

// we will default the temperature, sea level and us wildfire source fireign keys 
// to 1, 2, 3 as foreign keys because they are not supposed to change.  
// If they do then we will need to update the script
$global_temperature_source = 1;
$sea_level_source = 2;
$us_wildfire_source = 3;

require_once ('MysqlConnectToGlobalWarming.php');

// Check if the form has been submitted.
if (isset($_POST['year'])) {

	$errors = array(); // Initialize error array.

    // change this to year, population, size_in_sq_km, life_expectancy
    
	// Check for a year.
	if (empty($_POST['year'])) 
	{
		$errors[] = 'You forgot to enter the year of the country demographics.';
	} 
	else 
	{
		$year = $_POST['year'];
	}

	// Check for population.
	if (empty($_POST['global_temperature'])) 
	{
		$errors[] = 'You forgot to enter the population of the country demographics.';
	} 
	else 
	{
		$global_temperature = $_POST['global_temperature'];
	}
	
	// Check for size in sq km.
	if (empty($_POST['sea_level'])) 
	{
		$errors[] = 'You forgot to enter the size in square kilometers of the country demographics.';
	} 
	else 
	{
		$sea_level = $_POST['sea_level'];
	}
    
    // check for life expectancy
    if (empty($_POST['us_wildfire'])) 
	{
		$errors[] = 'You forgot to enter the life expectancy of the country demographics.';
	} 
	else 
	{
		$us_wildfire = $_POST['us_wildfire'];
	}

 	// Build the country query
	$query = "SELECT effects_history.year, global_temperature, sea_level, us_wildfire from effects_history;";
	$result = @mysqli_query ($dbc, $query);
	while ($row = mysqli_fetch_array($result))
	{
        $effect_year=$row['year'];
	}

	if (empty($errors)) { // If everything's okay.
		// Add the country demographics to the database.
        
        // get the max index
        $query = "SELECT MAX(effects_history.year) as max_index FROM effects_history";
        $result = @mysqli_query ($dbc, $query);
        $max_index_row = @mysqli_fetch_array ($result, MYSQLI_NUM);
        $max_index = 0;
        $max_index = $max_index_row[0] + 1;
		
		// Build the query.
		//$query = "SET IDENTITY_INSERT continents_countries ON; INSERT INTO continents_countries(index, year, population, size_in_sq_km, life_expectancy, fk_country_id, fk_continent_id) VALUES ($max_index, '$year', '$population', '$size_in_sq_km', '$life_expectancy', '$country_id', '$continent_id'); SET IDENTITY_INSERT continents_countries OFF;";		
		$query = "INSERT INTO effects_history (year, global_temperature, sea_level, us_wildfire, fk_global_temperature_id, fk_sea_level_information_id, fk_us_wildfire_information_id) VALUES ('$year', '$global_temperature', '$sea_level', '$us_wildfire', '$global_temperature_source', '$sea_level_source','$us_wildfire_source');";
		
		// Run the query.
		$result = @mysqli_query ($dbc, $query); 
		if ($result) // If the query ran OK.
		{ 
			// Print a success message.
			echo '<h1>Success!</h1>
			<p>You have added:</p>';

		    echo "<table>
				<tr><td>Year:</td><td>$year</td></tr>
				<tr><td>global_temperature:</td><td>$global_temperature</td></tr>
				<tr><td>sea_level:</td><td>$sea_level</td></tr>
				<tr><td>us_wildfire:</td><td>$us_wildfire</td></tr>
			</table>";	
		} 
		else // If it did not run OK.
		{ 
			echo '<h1>System Error</h1>
			<p>The global warming effects history could not be added due to a system error. We apologize for any inconvenience.</p>'; // Public message.
			
			echo '<p>' . mysqli_error($dbc) . '<br />
			<br />Query: ' . $query . '</p>'; 
			// Debugging message.	
		}	
	}	
	else 
	{ // Report the errors.
	
		echo '<h1>Error!</h1>
		<p >The following error(s) occurred:<br />';

		foreach ($errors as $msg) // Print each error.
		{ 
			echo " - $msg<br />\n";
		}

		echo '</p><p>Please try again.</p><p><br /></p>';
		
	} // End of if (empty($errors)) IF.

} // End of the main Submit conditional.

//if (isset($_POST['global_temperature_source'])) $this_global_temperature_source_id=$_POST['global_temperature_source']; 
// this will be used for form stickiness
//if (isset($_POST['sea_level_source'])) $this_sea_level_source_id=$_POST['sea_level_source']; 
//if (isset($_POST['us_wildfire_source'])) $this_us_wildfire_source_id=$_POST['us_wildfire_source']; 

// this will be used for form stickiness

echo "<h2>Add Effects History record</h2>
	<form action='AddEffects.php' method='post'>
	<p>Year: <input type='text' name='year' size='4' maxlength='4' value='";
    if (isset($_POST['year'])) echo $_POST['year'];

echo "'>&nbsp;&nbsp;&nbsp;</p>	
	<p>global_temperature: <input type='text' name='global_temperature' size='12' 
	maxlength='12' value='";
	if (isset($_POST['global_temperature'])) echo $_POST['global_temperature']; 

echo "'> </p>
	<p>sea_level: <input type='text' name='sea_level' size='12' 
	maxlength='12' value='";
	if (isset($_POST['sea_level'])) echo $_POST['sea_level'];  

echo "'> </p>
	<p>us_wildfire: <input type='text' name='us_wildfire' size='10' 
	maxlength='10' value='";
	if (isset($_POST['us_wildfire'])) echo $_POST['us_wildfire'];  
	
echo "'></p>
	<p><input type='submit' name='submit' value='Add Effects History record' /></p>

</form>";
