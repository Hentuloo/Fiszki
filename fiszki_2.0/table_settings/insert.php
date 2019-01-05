<?php
include('../baza.php');
if(!$connect){
    header('location: ../index.php');
}
$select_database=$_POST['select_database'];
$value1=$_POST['value1'];
$value2=$_POST['value2'];
$query="SELECT count(id) as ilosc FROM $select_database";
$result=mysqli_query($connect,$query);
$data=mysqli_fetch_assoc($result);
$count =  $data['ilosc'];
$query = "INSERT INTO $select_database (id, v1, v2, weight) values (NULL,'$value1','$value2','1')";      
mysqli_query($connect,$query);
echo 'Dodałem nowe słówko pod id = '.$count; 
    


?>