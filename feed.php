<?php

include_once("init.php");

// Make it actually be an ics file
header('Content-Type: text/calendar; charset=utf-8');
header('Content-Disposition: attachment; filename=feed.ics');

// the iCal date format. Note the Z on the end indicates a UTC timestamp.
define('DATE_ICAL', 'Ymd');

// Boilerplate
$output = "BEGIN:VCALENDAR
METHOD:PUBLISH
VERSION:2.0\n";

$sql = "SELECT StudyTests.testName, concept FROM StudyConcepts, StudyTests WHERE StudyConcepts.date >= DATE(NOW()) AND testID = StudyTests.id";
$result = $conn->query($sql);

// loop over events
while ($row = $result->fetch_assoc()) {
    $output .=
"BEGIN:VEVENT
SUMMARY:" . $row['testName'] . ": " . $row['concept'] . "
UID:" . $row['id'] . "
DTSTART;VALUE=DATE:" . date(DATE_ICAL, strtotime($row['date'])) . "
DTEND;VALUE=DATE:" . date(DATE_ICAL, strtotime($row['date'])) . "
END:VEVENT\n";
}

// close calendar
$output .= "END:VCALENDAR";

echo $output;
