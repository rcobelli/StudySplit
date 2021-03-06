<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">

    <title>Study Split</title>
</head>
<body>
    <?php if ($_GET['success'] == 'true'): ?>
        <div class="alert alert-success" role="alert">
            Successfully saved exam!
        </div>
    <?php endif; ?>
    <div class="container">
        <h1>Study Split</h1>
        <form method="post" action="save.php" id="form1">
            <div class="form-group">
                <label for="exampleInputEmail1">Test Date:</label>
                <input type="date" name="date" class="form-control" id="date" placeholder="yyyy-mm-dd" required>
            </div>
            <div class="form-group">
                <label for="exampleInputEmail1">Test Name:</label>
                <input type="text" name="milestone" class="form-control" id="milestone" placeholder="EAS Midterm" required>
            </div>
            <div class="form-group">
                <label for="exampleFormControlTextarea1">Test Concepts:</label>
                <textarea class="form-control" id="exampleFormControlTextarea1" rows="8" aria-describedby="conceptHelp" name="concepts"></textarea>
                <small id="conceptHelp" class="form-text text-muted">Put each concept on a new line</small>
            </div>
            <button type="submit" form="form1" class="btn btn-primary">Submit</button>
        </form>

        <?php
        include_once("init.php");

        $sql = "SELECT testName, date FROM StudyTests WHERE date > NOW() ORDER BY date";
        $results = $conn->query($sql);
        if ($results !== false) {
            echo "<hr/><h2>Upcoming Tests</h2>";
            foreach ($results as $row) {
                echo $row['testName'] . ": " . date('m/d/Y', strtotime($row['date'])) . "</br>";
            }
        }
        ?>
    </div>
    <!-- Optional JavaScript -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
</body>
</html>
