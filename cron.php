<?php

include_once("init.php");

// Select all entries for today's date
$sql = "SELECT * FROM StudyConcepts WHERE date = DATE(NOW())";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $ch = curl_init();
    while ($row = $result->fetch_assoc()) {
        // Concat all our data together
        $data = "name=Study For " . $row['milestone'] . "&desc=Concepts: " . $row['concept'] . "&due=null&idList=$TRELLO_LIST&token=$TRELLO_TOKEN&key=$TRELLO_KEY";

        // Config curl
        $headers = array();
        $headers[] = "Content-Type: application/x-www-form-urlencoded";
        curl_setopt($ch, CURLOPT_URL, "https://api.trello.com/1/cards");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "$data");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        // Execute curl
        curl_exec($ch);

        // Handle errors
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }
    }
}
