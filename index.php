<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Generate and Import CSV File</title>
</head>
<body>
    <div>
    <h2 style="border: 2px solid #000; padding: 10px; text-align: center; width: 300px; border-bottom: none;margin: 0 auto; margin-top:150px">Generate CSV File</h2>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <label for="numRecords">Number of Records:</label>
            <input type="number" id="numRecords" name="numRecords" min="1" placeholder="blank" required>
            <button type="submit" name="generate">Generate CSV</button>
        </form>
      

        <?php
        // Include the CSV generation function
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['generate'])) {
            // Validate and sanitize input
            $numRecords = (int)$_POST['numRecords'];
            set_time_limit(0); // Allow script to run indefinitely
            
            include 'generate_csv.php'; // Include the file containing the function
            generateCSV($numRecords); // Call your CSV generation function
        }
        ?>
    </div>

    <div>
        <h2 style="border: 2px solid #000; padding: 10px; text-align: center; width: 300px; border-bottom: none;margin: 0 auto; margin-top:40px">Upload CSV File</h2>
        <form action="upload.php" method="post" enctype="multipart/form-data">
            <label for="csvFile">Choose CSV file:</label>
            <input type="file" id="csvFile" name="csvFile" accept=".csv" required>
            <button type="submit" name="upload">Upload CSV</button>
        </form>
    </div>
</body>
</html>
