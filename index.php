<?php
$dbhost = 'localhost';
$dbuser = 'root';
$dbpass = '';
$dbname = 'Wordpress_DB';
$conn = new mysqli($dbhost, $dbuser, $dbpass, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$sql = "SELECT * FROM HSA_DATA ORDER BY updated_at ASC, employee_code ASC LIMIT 1";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
$employee_id = $row['employee_code'];
$url = "https://api2preview.sapsf.eu/odata/v2/User('" . $employee_id . "')?\$format=json";
$username = 'Tanweer@hayelsaeedT1';
$password = 'Tanweer@2024';
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
$output = curl_exec($ch);
$info = curl_getinfo($ch);
curl_close($ch);
$response = json_decode($output);
$arabic_name = isset($response->d->displayName) ? $response->d->displayName : null;
$country = isset($response->d->country) ? $response->d->country : null;
$location = isset($response->d->location) ? $response->d->location : null;
$company = isset($response->d->custom04) ? $response->d->custom04 : null;
$grade = isset($response->d->custom15) ? $response->d->custom15 : null;
$department = isset($response->d->department) ? $response->d->department : null;
$query = "UPDATE HSA_DATA SET
arabic_name = '" . $arabic_name . "',
country = '" . $country . "',
location = '" . $location . "',
company = '" . $company . "',
grade = '" . $grade . "',
department = '" . $department . "',
updated_at = '" . date("Y-m-d H:i:s") . "'
WHERE employee_code = '" . $employee_id . "'";

if ($conn->query($query) === true) {
    echo "Data updated successfully.";
} else {
    echo "Something went wrong: " . $conn->error;
}
$conn->close();
