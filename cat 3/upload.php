<?php
require_once 'vendor/autoload.php';
include 'db.php';

$client = new Google_Client();
$client->setAuthConfig('credentials.json');
$client->addScope(Google_Service_Drive::DRIVE_FILE);
$client->setAccessType("offline");

$service = new Google_Service_Drive($client);

if (isset($_FILES['file'])) {
    $file_path = $_FILES['file']['tmp_name'];
    $file_name = $_FILES['file']['name'];

    $file_metadata = new Google_Service_Drive_DriveFile(['name' => $file_name]);

    $content = file_get_contents($file_path);
    $uploaded = $service->files->create($file_metadata, [
        'data' => $content,
        'mimeType' => $_FILES['file']['type'],
        'uploadType' => 'multipart',
        'fields' => 'id'
    ]);

    echo "Uploaded to Google Drive with File ID: " . $uploaded->id;
    echo "<br><a href='index1.php'>Back</a>";
}
?>
