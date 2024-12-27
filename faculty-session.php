<?php
   include('config.php');
   session_start();
   
   $user_check = $_SESSION['login_user'];
   
   $ses_sql = mysqli_query($db,"select faculty_ID, faculty_name , email from faculty where faculty_name = '$user_check' ");
   
   $row = mysqli_fetch_array($ses_sql,MYSQLI_ASSOC);
   
   $login_session = $row['faculty_name'];
   //$faculty_id=$row['$faculty_id'];
   $login_id = $row['faculty_ID'];
   $faculty_email=$row['email'];
   if(!isset($_SESSION['login_user'])){
      header("location:faculty-login.php");
      die();
   }
?>