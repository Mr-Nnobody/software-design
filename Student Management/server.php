<?php
  
$cn = pg_connect("host=localhost port=5432 dbname=student_record user=postgres password=nkemdiran@gmail1");

if($cn)
{
  echo "connected succesfully";
}
?>
