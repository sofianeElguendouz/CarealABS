<?php 
 $id=$_POST['id'];
 echo "<script> alert(".$id.");<script>";
 $bd=new PDO('mysql:dbname=project;localhost=localhost','root','');
 $query=$bd->prepare('DELETE FROM department WHERE Id=?');
 $query->execute([$id]);
