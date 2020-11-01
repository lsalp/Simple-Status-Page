<!doctype html>
<html lang="en" class="h-100">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Status Page · Server Monitor</title>
   <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
   <style>
   .container {
     width: auto;
     max-width: 950px;
     padding: 0 15px;
   }

   .footer {
     background-color: #f5f5f5;
   }
   </style>
  </head>
  <body class="d-flex flex-column h-100">

<main role="main" class="flex-shrink-0">
  <div class="container">
    <h1 class="mt-5">Status Page</h1>
    <div class="alert alert-secondary" role="alert">
Welcome to the public status page! Below you can see a list of the currently monitored servers.
</div>

<?php
require_once('config.php');

$host = constant("PSM_DB_HOST");
$port = constant("PSM_DB_PORT");
$db = constant("PSM_DB_NAME");
$dbuser = constant("PSM_DB_USER");
$passw = constant("PSM_DB_PASS");
$dbpf = constant("PSM_DB_PREFIX");


$con = mysqli_connect($host, $dbuser, $passw, $db);
// Check connection
if (mysqli_connect_errno())
{
echo "Failed to connect to the database server: " . mysqli_connect_error();
}

$result = mysqli_query($con,"SELECT * FROM ". $dbpf ."servers WHERE active='yes';");

echo "<table class='table table-dark'>
<tr>
<th>Server</th>
<th>Status</th>
<th>Last check</th>
<th>Last offline</th>
</tr>";

while($row = mysqli_fetch_array($result))
{
if ($row['status'] === "off") {
 $statusx = "Offline";
 $statusy = "danger";
 $lonline = "Currently offline. Last online: " .$row['last_online'];
} else {
 $statusx = "Operational";
 $statusy = "success";
 $lonline = "";
}
echo "<tr class='bg-". $statusy ."'>";
echo "<td>" . $row['label'] . "</td>";
echo "<td>" . $statusx . "</td>";
echo "<td>" . $row['last_check'] . "</td>";
if (strlen($row['last_offline']) < 1 || $row['status'] === "off") {
 if ($row['status'] === "off") {
 echo "<td>" . $lonline . "</td>";
 } else {
 echo "<td>Never</td>";
 }
} else {
 echo "<td>" . $row['last_offline'] . " (". $row['last_offline_duration'] .")</td>";
}
echo "</tr>";
}
echo "</table>";

mysqli_close($con);
?>
<button type="button" onClick="window.location.reload();" class="btn btn-outline-secondary">Reload</button> <?php echo date('m/d/Y h:i:s a', time()); ?>

</main>

<footer class="footer mt-auto py-3">
  <div class="container">
    <span class="text-muted"><a href="https://github.com/lsalp/Simple-Status-Page" target="_blank">Simple Status Page</a> for <a href="https://github.com/phpservermon/phpservermon/" target="_blank">PHP Server Monitor</a>.</span>
  </div>
</footer>
</body>
</html>
