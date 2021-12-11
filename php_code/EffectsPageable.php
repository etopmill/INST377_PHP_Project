<?php # Script 10.5 - view_users.php #5
// This script retrieves all the records from the countries_continents table.
// This new version allows the results to be sorted in different ways.

$page_title = 'View the Global Warming Effects History';
//include ('includes/header.html');
echo '<h1>Global Warming Effects History</h1>';

require_once ('MysqlConnectToGlobalWarming.php');

// Number of records to show per page:
$display = 10;

// Determine how many pages there are...
if (isset($_GET['p']) && is_numeric($_GET['p'])) { // Already been determined.
    $pages = $_GET['p'];
} else { // Need to determine.
    // Count the number of records:
    $q = "SELECT COUNT(effects_history.year) FROM effects_history;";
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
    case 'yr':
        $order_by = 'effects_history.year ASC';
        break;
    case 'g':
        $order_by = 'effects_history.global_temperature ASC';
        break;
    case 's':
        $order_by = 'effects_history.sea_level ASC';
        break;
    case 'w':
        $order_by = 'effects_history.us_wildfire ASC';
        break;
    case 't':
        $order_by = 'global_temperature_source ASC';
        break;
    case 'sea':
        $order_by = 'sea_level_source ASC';
        break;
    case 'wi':
        $order_by = 'us_wildfire_information_source ASC';
        break;
    default:
        $order_by = 'effects_history.year ASC';
        $sort = 'yr';
    break;
}

// Define the query:
$q1 = "SELECT effects_history.year, global_temperature, sea_level, us_wildfire from effects_history ";
$q1 .= "ORDER BY $order_by LIMIT $start, $display;";
$r1 = @mysqli_query ($dbc, $q1); // Run the query.
$rowcount=mysqli_num_rows($r1);

// Table header:
echo '<table align="center" cellspacing="0" cellpadding="5" width="75%">
<tr>
    <td align="left"><b>Edit</b></td>
    <td align="left"><b>Delete</b></td>
 
    <td align="left"><b><a href="EffectsPageable.php?sort=yr">year</a></b></td>
    <td align="left"><b><a href="EffectsPageable.php?sort=g">global_temperature</a></b></td>
	<td align="left"><b><a href="EffectsPageable.php?sort=s">sea_level</a></b></td>
	<td align="left"><b><a href="EffectsPageable.php?sort=w">us_wildfire</a></b></td>
</tr>
';

// Fetch and print all the records....
$bg = '#eeeeee';
while ($row = mysqli_fetch_array($r1)) {
    $bg = ($bg =='#eeeeee' ? '#ffffff' : '#eeeeee');
    echo '<tr bgcolor="' . $bg . '">
        <td align="left"><a href="edit_effects.php?id=' . $row['year'] . '">Edit</a></td>
        <td align="left"><a href="delete_effects.php?id=' . $row['year'] .'">Delete</a></td>
        <td align="left">' . $row['year'] . '</td>
		<td align="left">' . $row['global_temperature'] . '</td>
		<td align="left">' . $row['sea_level'] . '</td>
		<td align="left">' . $row['us_wildfire'] . '</td>
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
        echo '<a href="EffectsPageable.php?s=' . ($start - $display) . '&p=' . $pages . '&sort=' . $sort . '">Previous</a> ';
    }

    // Make all the numbered pages:
    for ($i = 1; $i <= $pages; $i++) {
        if ($i != $current_page) {
            echo '<a href="EffectsPageable.php?s=' . (($display * ($i - 1))) . '&p=' . $pages . '&sort=' . $sort . '">' . $i . '</a> ';
        } else {
            echo $i . ' ';
        }
    } // End of FOR loop.

    // If it's not the last page, make a Next button:
    if ($current_page != $pages) {
        echo '<a href="EffectsPageable.php?s=' . ($start + $display) .'&p=' . $pages . '&sort=' . $sort . '">Next</a>';
    }

    echo '</p>'; // Close the paragraph.

} // End of links section.


?> 