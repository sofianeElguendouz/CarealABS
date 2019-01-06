 <?php 
 $id=$_POST['id'];
 $bd=new PDO('mysql:dbname=project;localhost=localhost','root','');
 $query=$bd->prepare('DELETE FROM salary WHERE ID=?');
 $query->execute([$id]);
