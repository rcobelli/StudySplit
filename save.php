<?php

include_once("init.php");

// Calculate the number of days available to study
$start = date('Y-m-d');
$end = $_POST['date'];
$days_between = getWorkdays($start, $end) - 2;

// Read form data
$milestone = $_POST['milestone'];
$concepts = explode("\n", $_POST['concepts']);

$days = array();

// More than 5 days to go: 2 overall review days
// If not: only 1
if ($days_between > 5) {
    $days_available = $days_between - 2;
    $days[$days_between-2] = "Overall Review";
    $days[$days_between-1] = "Overall Review";
} else {
    $days_available = $days_between - 1;
    $days[$days_between-1] = "Overall Review";
}


if (count($concepts) < $days_available) { // Fewer days than concepts
    // Backload the concepts so 1 per day but don't start studying immediately
    $emptyDays = $days_available - count($concepts);
    for ($i=0; $i < $emptyDays; $i++) {
        $days[$i] = null;
    }
    for ($i=0; $i < count($concepts); $i++) {
        $days[$i + $emptyDays] = trim($concepts[$i]);
    }
} elseif (count($concepts) == $days_available) { // Same number of concepts and days
    // One concept for today
    for ($i=0; $i < count($concepts); $i++) {
        $days[$i] = trim($concepts[$i]);
    }
} else { // Fewer days than concepts
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

// Start of SQL PDOStatement
$sql = "INSERT INTO StudyConcepts (date, milestone, concept) VALUES ";

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
    $sql .= "('" . $date . "', '" . $milestone . "', '" . $days[$i] . "'), ";
}

// Remove the extra comma and execute
$sql = rtrim($sql, ", ");
$conn->query($sql);

// Return to main page and show success message
header("Location: index.php?success=true");
