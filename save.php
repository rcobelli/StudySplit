<?php

include_once("init.php");

// Calculate the number of days available to study
$start = date('Y-m-d');
$end = $_POST['date'];
$days_between = getWorkdays($start, $end) - 2;

// Read form data
$milestone = steralizeString($_POST['milestone']);
$concepts = explode('\r\n', steralizeString($_POST['concepts']));

$days = array();

// Day before the test is overall review
$days_available = $days_between - 1;
$days[$days_between-1] = "Overall Review";

// Fewer concepts than days
if (count($concepts) < $days_available) {
    // Backload the concepts so 1 per day but don't start studying immediately
    $emptyDays = $days_available - count($concepts);
    for ($i=0; $i < $emptyDays; $i++) {
        $days[$i] = null;
    }
    for ($i=0; $i < count($concepts); $i++) {
        $days[$i + $emptyDays] = trim($concepts[$i]);
    }
}
// Same number of concepts and days
elseif (count($concepts) == $days_available) {
    // One concept per day
    for ($i=0; $i < count($concepts); $i++) {
        $days[$i] = trim($concepts[$i]);
    }
}
// Fewer days than concepts
else {
    // Split the concepts as evenly as possible over remaining days
    for ($j=0; $j < count($concepts);) {
        for ($i=0; $i < $days_available; $i++) {
            if ($j == count($concepts)) {
                continue;
            }
            $days[$i] .= trim($concepts[$j]) . ", ";
            $j++;
        }
    }
}

$sql = "INSERT INTO StudyTests (testName, date) VALUES ('" . $milestone . "', '" . $end . "')";
if ($conn->query($sql) === true) {
    $testID = $conn->insert_id;
} else {
    echo "It didn't work</br>";
    echo $sql;
    echo "</br>";
    echo json_encode($days);
    die();
}

// Start of SQL Statement
$sql = "INSERT INTO StudyConcepts (date, testID, concept) VALUES ";

// Add the actual meat to the SQL statement
$dateOffset = 1;
for ($i=0; $i < count($days); $i++) {
    // Remove any trailing commas
    $days[$i] = rtrim($days[$i], ", ");

    // Get the next date in the list
    $dateObject = strtotime($start . "+" . ($i + $dateOffset) . " days");

    // Check if its a weekend
    if (date('N', $dateObject) == 6) {
        $dateOffset += 2;
    } elseif (date('N', $dateObject) == 7) {
        $dateOffset += 1;
    }

    // Reset the date object to account for any changes due to something falling on the weekend
    $dateObject = strtotime($start . "+" . ($i + $dateOffset) . " days");
    // Create the MySQL date
    $date = date('Y-m-d', $dateObject);

    // If there's nothing to study today, continue
    if (empty($days[$i])) {
        continue;
    }

    // Format the SQL
    $sql .= "('" . $date . "', '" . $testID . "', '" . $days[$i] . "'), ";
}

// Remove the extra comma and execute
$sql = rtrim($sql, ", ");
if ($conn->query($sql) === true) {
    // Return to main page and show success message
    header("Location: index.php?success=true");
} else {
    echo "It didn't work</br>";
    echo $sql;
    echo "</br>";
    echo json_encode($days);
    die();
}

// Steralize input (remove crazy characters)
function steralizeString($str)
{
    global $conn;
    return mysqli_real_escape_string($conn, $str);
}
