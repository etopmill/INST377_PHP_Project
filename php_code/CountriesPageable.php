 
 
<?php # Script 10.5 - view_users.php #5
// This script retrieves all the records from the countries_continents table.
// This new version allows the results to be sorted in different ways.

$page_title = 'View the Current Countries Information';
//include ('includes/header.html');
echo '<h1>Country Demographics for Global Warming</h1>';

require_once ('MysqlConnectToGlobalWarming.php');

// Number of records to show per page:
$display = 10;

// Determine how many pages there are...
if (isset($_GET['p']) && is_numeric($_GET['p'])) { // Already been determined.
    $pages = $_GET['p'];
} else { // Need to determine.
    // Count the number of records:
    $q = "SELECT COUNT(continents_countries.index) FROM continents_countries;";
    $r = @mysqli_query ($dbc, $q);
    $row = @mysqli_fetch_array ($r);
    $records = $row[0];
    // Calculate the number of pages...
    if ($records > $display) { // More n 1 page.
        $pages = ceil ($records/$display);
    } else {
        $pages = 1;
    }
} // End of p IF.

// Determine where in the database to start returning results...
if (isset($_GET['s']) && is_numeric($_GET['s'])) {
    $start = $_GET['s'];
} else {
    $start = 0;
}

// Determine the sort...
// Default is by registration date.
$sort = (isset($_GET['sort'])) ? $_GET['sort'] : 'yr';

// Determine the sorting order:
switch ($sort) {
    case 'in':
        $order_by = 'continents_countries.index ASC';
        break;
    case 'yr':
        $order_by = 'continents_countries.year ASC';
        break;
    case 'p':
        $order_by = 'continents_countries.population ASC';
        break;
    case 'sq':
        $order_by = 'continents_countries.size_in_sq_km ASC';
        break;
    case 'life':
        $order_by = 'continents_countries.life_expectancy ASC';
        break;
    case 'cn':
        $order_by = 'country ASC';
        break;
    case 'con':
        $order_by = 'continent ASC';
        break;
    default:
        $order_by = 'continents_countries.year ASC';
        $sort = 'yr';
    break;
}

// Define the query:
$q1 = "SELECT continents_countries.index, year, population, size_in_sq_km, life_expectancy, countries.name AS country, continents.name AS continent FROM continents_countries ";
$q1 .= "JOIN countries ON fk_country_id = countries.country_id ";
$q1 .= "JOIN continents ON fk_continent_id = continents.continent_id ";
$q1 .= "ORDER BY $order_by LIMIT $start, $display;";
$r1 = @mysqli_query ($dbc, $q1); // Run the query.
$rowcount=mysqli_num_rows($r1);

// Table header:
echo '<table align="center" cellspacing="0" cellpadding="5" width="75%">
<tr>
    <td align="left"><b>Edit</b></td>
    <td align="left"><b>Delete</b></td>
 
    <td align="left"><b><a href="CountriesPageable.php?sort=in">index</a></b></td>
    <td align="left"><b><a href="CountriesPageable.php?sort=yr">year</a></b></td>
	<td align="left"><b><a href="CountriesPageable.php?sort=p">population</a></b></td>
	<td align="left"><b><a href="CountriesPageable.php?sort=sq">size_in_sq_km</a></b></td>
	<td align="left"><b><a href="CountriesPageable.php?sort=life">life_expectancy</a></b></td>
	<td align="left"><b><a href="CountriesPageable.php?sort=cn">country</a></b></td>
	<td align="left"><b><a href="CountriesPageable.php?sort=con">continent</a></b></td>
</tr>
';

// Fetch and print all the records....
$bg = '#eeeeee';
while ($row = mysqli_fetch_array($r1)) {
    $bg = ($bg =='#eeeeee' ? '#ffffff' : '#eeeeee');
    echo '<tr bgcolor="' . $bg . '">
        <td align="left"><a href="edit_country.php?id=' . $row['index'] . '">Edit</a></td>
        <td align="left"><a href="delete_country.php?id=' . $row['index'] .'">Delete</a></td>
        <td align="left">' . $row['index'] . '</td>
		<td align="left">' . $row['year'] . '</td>
		<td align="left">' . $row['population'] . '</td>
		<td align="left">' . $row['size_in_sq_km'] . '</td>
		<td align="left">' . $row['life_expectancy'] . '</td>
		<td align="left">' . $row['country'] . '</td>
		<td align="left">' . $row['continent'] . '</td>
        </tr>
    ';
} // End of WHILE loop.

echo '</table>';
mysqli_free_result ($r1);
mysqli_close($dbc);

// Make the links to other pages, if necessary.
if ($pages > 1) {

    echo '<br /><p>';
    $current_page = ($start/$display) + 1;

    // If it's not the first page, make a Previous button:
    if ($current_page != 1) {
        echo '<a href="CountriesPageable.php?s=' . ($start - $display) . '&p=' . $pages . '&sort=' . $sort . '">Previous</a> ';
    }

    // Make all the numbered pages:
    for ($i = 1; $i <= $pages; $i++) {
        if ($i != $current_page) {
            echo '<a href="CountriesPageable.php?s=' . (($display * ($i - 1))) . '&p=' . $pages . '&sort=' . $sort . '">' . $i . '</a> ';
        } else {
            echo $i . ' ';
        }
    } // End of FOR loop.

    // If it's not the last page, make a Next button:
    if ($current_page != $pages) {
        echo '<a href="CountriesPageable.php?s=' . ($start + $display) .'&p=' . $pages . '&sort=' . $sort . '">Next</a>';
    }

    echo '</p>'; // Close the paragraph.

} // End of links section.


?> 