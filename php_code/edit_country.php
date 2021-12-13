<?php #edit_country.php

// This page edits a country demographics entry.

$page_title = 'Edit a Country Demographic';

// Check for a valid country demographic ID, through GET or POST.
if ( (isset($_GET['id'])) && (is_numeric($_GET['id'])) ) { // Accessed through CountriesPageable.php
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
	if (empty($_POST['year'])) {
		$errors[] = 'You forgot to enter the year of the country demographic.';
	} else {
		$year = $_POST['year'];
	}
	
	// Check for a population.
	if (empty($_POST['population'])) {
		$errors[] = 'You forgot to enter the population of the country demographic.';
	} else {
		$population = $_POST['population'];
	}
	
	// Check for a size_in_sq_km.
	if (empty($_POST['size_in_sq_km'])) {
		$errors[] = 'You forgot to enter the size_in_sq_km of the country demographic.';
	} else {
		$size_in_sq_km = $_POST['size_in_sq_km'];
	}
	
	// Check for life_expectancy.
	if (empty($_POST['life_expectancy'])) {
		$errors[] = 'You forgot to enter the life_expectancy of the country demographic.';
	} else {
		$life_expectancy = $_POST['life_expectancy'];
	}
	
	// Check for country_id.
	if (empty($_POST['country_id'])) {
		$errors[] = 'You forgot to enter the country_id of the country demographic.';
	} else {
		$country_id = $_POST['country_id'];
	}
	
	// Check for continent_id.
	if (empty($_POST['continent_id'])) {
		$errors[] = 'You forgot to enter the continent_id of the country demographic.';
	} else {
		$continent_id = $_POST['continent_id'];
	}
	
	if (empty($errors)) { // If everything's OK.
	
			// Make the query.
			$query = "UPDATE continents_countries SET year='$year', population='$population', size_in_sq_km='$size_in_sq_km', life_expectancy='$life_expectancy', fk_country_id='$country_id', fk_continent_id='$continent_id' WHERE continents_countries.index = $id";
			$result = @mysqli_query ($dbc, $query); // Run the query.
			if ((mysqli_affected_rows($dbc) == 1) || (mysqli_affected_rows($dbc) == 0)) { // If it ran OK.
			
				// Print a message.
				echo '<h1 id="mainhead">Edit a Country Demographic.</h1>
				<p>The country demographic record has been edited.</p><p><br /><br /></p>';	
				exit();
							
			} else { // If it did not run OK.
				echo '<h1 id="mainhead">System Error</h1>
				<p class="error">The country demographic could not be edited due to a system error. We apologize for any inconvenience.</p>'; // Public message.
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

// Retrieve the conutry demographic information.
$q1 = "SELECT continents_countries.index, year, population, size_in_sq_km, life_expectancy, countries.country_id AS country_id, continents.continent_id AS continent_id FROM continents_countries ";
$q1 .= "JOIN countries ON fk_country_id = countries.country_id ";
$q1 .= "JOIN continents ON fk_continent_id = continents.continent_id ";
$q1 .= "WHERE countries.country_id = continents_countries.fk_country_id AND continents.continent_id = continents_countries.fk_country_id AND continents_countries.index = $id;";
$result = @mysqli_query ($dbc, $q1); // Run the query.

if (mysqli_num_rows($result) == 1) { // Valid movie ID, show the form.

	// Get the movie's information.
	$row = mysqli_fetch_array ($result);
    $this_year=$row['year'];
    $this_population=$row['population'];
    $this_size_in_sq_km=$row['size_in_sq_km'];
    $this_life_expectancy=$row['life_expectancy'];
	$this_country_id=$row['country_id'];
	$this_continent_id=$row['continent_id'];
	// Create the form.


echo '<h2>Edit a Country Demographic</h2>

<form action="edit_country.php" method="post">';

echo "</p>
	<p>Country: <select name='country_id'>"; 
// Build the query
$query = "SELECT * FROM countries ORDER BY countries.name ASC";
$result = @mysqli_query ($dbc, $query);
while ($row = mysqli_fetch_array($result))
{
	if ($row['country_id'] == $this_country_id) 
	{
		echo '<option value="' . $row['country_id'] . '"
			 selected="selected">' . $row['name'] . '</option>';
	}
	else 
	{
		echo '<option value="' . $row['country_id'] . '">' .
			 $row['name'] . '</option>';
	}
}
	
//echo "'></p>
echo "</select></p>
<p>Continent: <select name='continent_id'>"; 
// Build the query
$query = "SELECT * FROM continents ORDER BY continents.name ASC";
$result = @mysqli_query ($dbc, $query);
while ($row = mysqli_fetch_array($result))
{
	if ($row['continent_id'] == $this_continent_id) 
	{
		echo '<option value="' . $row['continent_id'] . '"
			 selected="selected">' . $row['name'] . '</option>';
	}
	else 
	{
		echo '<option value="' . $row['continent_id'] . '">' .
			 $row['name'] . '</option>';
	}
    echo "$this_continent_id";
}

echo "</select></p>";

echo "<p>Year: <input type='text' name='year' size='4' maxlength='4' value='$this_year";
echo "'>&nbsp;&nbsp;&nbsp;</p>	
	<p>Population: <input type='text' name='population' size='12' 
	maxlength='12' value='$this_population";
echo "'> </p>
	<p>Size: <input type='text' name='size_in_sq_km' size='12' 
	maxlength='12' value='$this_size_in_sq_km";
echo "'> </p>
	<p>LIfe Expectancy: <input type='text' name='life_expectancy' size='3' 
	maxlength='3' value='$this_life_expectancy";
echo "'> </p>
    <input type='hidden' name='submitted' value='TRUE' />
    <input type='hidden' name='id' value='" . $id . "' />";
echo "<p><input type='submit' name='submit' value='Modify Country Demographics' /></p>
</form>";

} else { // Not a valid movie ID.
	echo '<h1 id="mainhead">Page Error</h1>
	<p class="error">This page has been accessed in error. Not a valid country ID.</p><p><br /><br /></p>';
}

mysqli_close($dbc); // Close the database connection.
		
?>