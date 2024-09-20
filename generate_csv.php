<?php
function generateCSV($numRecords) {
    $names = [
        "Alice", "Bob", "Charlie", "David", "Eva",
        "Frank", "Grace", "Hannah", "Ivy", "Jack",
        "Kate", "Leo", "Mia", "Nina", "Oliver",
        "Paul", "Quinn", "Rita", "Sam", "Tina"
    ];
    
    $surnames = [
        "Anderson", "Brown", "Clark", "Davis", "Evans",
        "Garcia", "Hernandez", "Johnson", "King", "Lee",
        "Martinez", "Moore", "Nelson", "O'Brien", "Parker",
        "Robinson", "Smith", "Taylor", "Wilson", "Young"
    ];

    $uniqueRecords = [];

    // Ensure the output directory exists
    if (!is_dir('output')) {
        mkdir('output', 0755, true);
    }

    $filePath = 'output/output.csv';
    $file = fopen($filePath, 'w');

    if ($file === false) {
        die("Failed to open the file for writing.");
    }

    fputcsv($file, ['Id', 'Name', 'Surname', 'Initials', 'Age', 'DateOfBirth']);

    while (count($uniqueRecords) < $numRecords) {
        $name = $names[array_rand($names)];
        $surname = $surnames[array_rand($surnames)];
        $age = rand(18, 99);
        $dob = date('d/m/Y', strtotime("-$age years"));
        $initials = strtoupper(substr($name, 0, 1));

        $recordKey = "$name|$surname|$age|$dob";

        if (!isset($uniqueRecords[$recordKey])) {
            $uniqueRecords[$recordKey] = true;
            fputcsv($file, [count($uniqueRecords), $name, $surname, $initials, $age, $dob]);
        }
    }

    // Close the file only if it was opened successfully
    if (is_resource($file)) {
        fclose($file);
        echo '<div style="text-align: center; margin-top: 20px; padding: 15px; background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; border-radius: 5px; max-width: 400px; margin-left: auto; margin-right: auto;">CSV file generated successfully with ' . count($uniqueRecords) . ' records!</div>';
    }
}
?>
