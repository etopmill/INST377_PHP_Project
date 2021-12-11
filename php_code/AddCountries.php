<?php # AddCountries.php

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
	if (empty($_POST['population'])) 
	{
		$errors[] = 'You forgot to enter the population of the country demographics.';
	} 
	else 
	{
		$population = $_POST['population'];
	}
	
	// Check for size in sq km.
	if (empty($_POST['size_in_sq_km'])) 
	{
		$errors[] = 'You forgot to enter the size in square kilometers of the country demographics.';
	} 
	else 
	{
		$size_in_sq_km = $_POST['size_in_sq_km'];
	}
    
    // check for life expectancy
    if (empty($_POST['life_expectancy'])) 
	{
		$errors[] = 'You forgot to enter the life expectancy of the country demographics.';
	} 
	else 
	{
		$life_expectancy = $_POST['life_expectancy'];
	}

    $country_id = $_POST['country_id'];
	// Build the country query
	$query = "SELECT * FROM countries WHERE country_id=$country_id ORDER BY countries.name ASC;";
	$result = @mysqli_query ($dbc, $query);
	while ($row = mysqli_fetch_array($result))
	{
        $country_name=$row['name'];
	}

    $continent_id = $_POST['continent_id'];
	// Build the country query
	$query = "SELECT * FROM continents WHERE continent_id=$continent_id ORDER BY continents.name ASC;";
	$result = @mysqli_query ($dbc, $query);
	while ($row = mysqli_fetch_array($result))
	{
        $continent_name=$row['name'];
	}

	if (empty($errors)) { // If everything's okay.
		// Add the country demographics to the database.
        
        // get the max index
        $query = "SELECT MAX(continents_countries.index) as max_index FROM continents_countries";
        $result = @mysqli_query ($dbc, $query);
        $max_index_row = @mysqli_fetch_array ($result, MYSQLI_NUM);
        $max_index = 0;
        $max_index = $max_index_row[0] + 1;
        echo "$max_index=" . $max_index;
		
		// Build the query.
		//$query = "SET IDENTITY_INSERT continents_countries ON; INSERT INTO continents_countries(index, year, population, size_in_sq_km, life_expectancy, fk_country_id, fk_continent_id) VALUES ($max_index, '$year', '$population', '$size_in_sq_km', '$life_expectancy', '$country_id', '$continent_id'); SET IDENTITY_INSERT continents_countries OFF;";		
		$query = "INSERT INTO continents_countries(year, population, size_in_sq_km, life_expectancy, fk_country_id, fk_continent_id) VALUES ('$year', '$population', '$size_in_sq_km', '$life_expectancy', '$country_id', '$continent_id');";		
		
		// Run the query.
		$result = @mysqli_query ($dbc, $query); 
		if ($result) // If the query ran OK.
		{ 
			// Print a success message.
			echo '<h1>Success!</h1>
			<p>You have added:</p>';

		    echo "<table>
				<tr><td>Year:</td><td>$year</td></tr>
				<tr><td>Population:</td><td>$population</td></tr>
				<tr><td>Size in square kilometers:</td><td>$size_in_sq_km</td></tr>
				<tr><td>Life Expectancy:</td><td>$life_expectancy</td></tr>
				<tr><td>Country Name:</td><td>$country_name</td></tr>
				<tr><td>Continent Name:</td><td>$continent_name</td></tr>
			</table>";	
		} 
		else // If it did not run OK.
		{ 
			echo '<h1>System Error</h1>
			<p>The country demographics could not be added due to a system error. We apologize for any inconvenience.</p>'; // Public message.
			
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

if (isset($_POST['country_id'])) $this_country_id=$_POST['country_id']; 
// this will be used for form stickiness
if (isset($_POST['continent_id'])) $this_continent_id=$_POST['continent_id']; 
// this will be used for form stickiness

echo "<h2>Add Country Demographics</h2>
	<form action='AddCountries.php' method='post'>
	<p>Year: <input type='text' name='year' size='4' maxlength='4' value='";
    if (isset($_POST['year'])) echo $_POST['year'];

echo "'>&nbsp;&nbsp;&nbsp;</p>	
	<p>Population: <input type='text' name='population' size='12' 
	maxlength='12' value='";
	if (isset($_POST['population'])) echo $_POST['population']; 

echo "'> </p>
	<p>Size: <input type='text' name='size_in_sq_km' size='12' 
	maxlength='12' value='";
	if (isset($_POST['size_in_sq_km'])) echo $_POST['size_in_sq_km'];  

echo "'> </p>
	<p>LIfe Expectancy: <input type='text' name='life_expectancy' size='3' 
	maxlength='3' value='";
	if (isset($_POST['life_expectancy'])) echo $_POST['life_expectancy'];  
	
echo "'></p>
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
echo "</select>&nbsp;&nbsp;&nbsp;</p>
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

echo "</select>&nbsp;&nbsp;&nbsp;</p>
	<p><input type='submit' name='submit' value='Add Country Demographics' /></p>

</form>";
