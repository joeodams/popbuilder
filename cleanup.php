  <?php
$postdata = file_get_contents("php://input");

$files = json_decode($postdata);



foreach ($files as $name) {
  unlink($name);
}

echo $postdata;
?>
