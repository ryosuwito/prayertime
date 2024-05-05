<?php

// Command to create database schema using Doctrine ORM
$createSchemaCommand = 'php ./bin/doctrine.php orm:schema-tool:drop --force && php ./bin/doctrine.php orm:schema-tool:create';

// Command to run seeder
$runSeederCommand = 'php ./src/Seeders/Seeder.php';

// Execute the commands one after another
echo "Creating database schema...\n";
exec($createSchemaCommand, $schemaOutput, $schemaReturnCode);

if ($schemaReturnCode === 0) {
    echo "Database schema created successfully.\n";
    echo "Running seeder...\n";
    exec($runSeederCommand, $seederOutput, $seederReturnCode);
    
    if ($seederReturnCode === 0) {
        echo "Seeder executed successfully.\n";
    } else {
        echo "Error: Failed to execute seeder.\n";
        echo "Seeder Output:\n";
        foreach ($seederOutput as $line) {
            echo $line . "\n";
        }
    }
} else {
    echo "Error: Failed to create database schema.\n";
    echo "Schema Output:\n";
    foreach ($schemaOutput as $line) {
        echo $line . "\n";
    }
}
?>
