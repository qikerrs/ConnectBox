
<?php
$filename = "../../.env";

// Open the file in read-only mode ('r')
$file = fopen($filename, "r");

if ($file) {
    // Read the entire file content
    $content = fread($file, filesize($filename));
    echo $content;

    // Always close the file pointer
    fclose($file);
} else {
    echo "Failed to open the file.";
}
?>
