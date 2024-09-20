<?php
// Create a new SQLite3 database connection
$db = new SQLite3('database.db');

// Create the table if it doesn't exist
$db->exec("CREATE TABLE IF NOT EXISTS csv_import (
    Id INTEGER PRIMARY KEY,
    Name TEXT,
    Surname TEXT,
    Initials TEXT,
    Age INTEGER,
    DateOfBirth TEXT
)");

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES['csvFile'])) {
    $filename = $_FILES['csvFile']['tmp_name'];

    if (!file_exists($filename) || $_FILES['csvFile']['error'] !== UPLOAD_ERR_OK) {
        die("Error: The uploaded file does not exist or there was an error uploading.");
    }

    if (($handle = fopen($filename, 'r')) !== FALSE) {
        fgetcsv($handle); // Skip header row
        $count = 0;

        // Prepare the insert statement with IGNORE to handle unique constraint
        $stmt = $db->prepare("INSERT OR IGNORE INTO csv_import (Id, Name, Surname, Initials, Age, DateOfBirth) VALUES (?, ?, ?, ?, ?, ?)");

        // Begin transaction
        $db->exec("BEGIN TRANSACTION");

        while (($data = fgetcsv($handle)) !== FALSE) {
            if (count($data) < 6) {
                continue; // Skip if not enough fields
            }

            // Validate fields (Name, Surname, Date of Birth)
            if (!preg_match("/^[a-zA-Z\s]+$/", $data[1]) || !preg_match("/^[a-zA-Z\s]+$/", $data[2]) || !validateDate($data[5])) {
                continue; // Skip invalid records
            }

            // Bind the values
            $stmt->bindValue(1, (int)$data[0], SQLITE3_INTEGER);
            $stmt->bindValue(2, $data[1], SQLITE3_TEXT);
            $stmt->bindValue(3, $data[2], SQLITE3_TEXT);
            $stmt->bindValue(4, $data[3], SQLITE3_TEXT);
            $stmt->bindValue(5, (int)$data[4], SQLITE3_INTEGER);
            $stmt->bindValue(6, $data[5], SQLITE3_TEXT);

            // Execute the statement
            if ($stmt->execute()) {
                $count++;
            }
        }

        // Commit transaction
        $db->exec("COMMIT");
        fclose($handle);
        echo "<div style='text-align: center; margin-top: 20px; background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; border-radius: 5px; padding: 10px; max-width: 400px; margin: 20px auto;margin-top:300px'>" . 
        "$count records imported successfully." . 
     "</div>";

    } else {
        echo "Error opening the file.";
    }
}

// Function to validate date format
function validateDate($date, $format = 'd/m/Y') {
    $d = DateTime::createFromFormat($format, $date);
    return $d && $d->format($format) === $date;
}
?>
