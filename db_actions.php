<?php
include 'config.php';//include config file

function SaveUserInfo($username,$name, $email, $password, $gender, $status)
{
  global $con; // Declare $con as a global variable
    
    //$dates = date("d/m/Y");
    
    // Insert data into the 'users' table
    $query = "INSERT INTO `users`(`username`, `name`, `email`, `password`, `gender`, `status`) VALUES ('$username','$name','$email','$password','$gender','$status')";
    $result1 = mysqli_query($con, $query); // Execute the query and store the result

   
    // Check if both queries were successful
    if ($result1) 
    {
        return true;//save
    } else 
    {
        return false;//not save
    }
}
function SaveIncome($username, $title, $description, $amount, $cr_date, $month, $year)
{
  global $con; // Declare $con as a global variable
    
    //$dates = date("d/m/Y");
    
    // Insert data into the 'users' table
    $query = "INSERT INTO `incomes`( `username`, `title`, `description`, `amount`, `cr_date`, `month`, `year`) VALUES ('$username','$title','$description','$amount','$cr_date','$month','$year')";
    $result1 = mysqli_query($con, $query); // Execute the query and store the result

   
    // Check if both queries were successful
    if ($result1) 
    {
        return true;//save
    } else 
    {
        return false;//not save
    }
}

function SaveExpense($username, $title, $description, $amount, $cr_date, $month, $year)
{
  global $con; // Declare $con as a global variable
    
    //$dates = date("d/m/Y");
    
    // Insert data into the 'users' table
    $query = "INSERT INTO `expenses`( `username`, `title`, `description`, `amount`, `cr_date`, `month`, `year`) VALUES ('$username','$title','$description','$amount','$cr_date','$month','$year')";
    $result1 = mysqli_query($con, $query); // Execute the query and store the result

   
    // Check if both queries were successful
    if ($result1) 
    {
        return true;//save
    } else 
    {
        return false;//not save
    }
}

function viewAllIncomes($username)
{
    global $con; // Declare $con as a global variable
    $query = "SELECT * FROM `incomes` where username='$username'";
    return $result = $con->query($query);
   
}
function viewAllExpense()
{
    global $con; // Declare $con as a global variable
    $query = "SELECT * FROM `expenses`";
    return $result = $con->query($query);
   
}

function totalIncome($username)
{
    
        global $con; // Declare $con as a global variable
        $query = "SELECT sum(amount) AS total_income FROM `incomes` where `username`='$username'";
        $result = mysqli_query($con, $query);
        
        if ($result) {
            $row = mysqli_fetch_assoc($result);
            return $row['total_income'];//total appointments
        } else {
            // Handle the error appropriately
            return 0; // or false, or throw an exception
        }
}
function totalexpense($username)
{
    
        global $con; // Declare $con as a global variable
        $query = "SELECT sum(amount) AS total_expense FROM `expenses` where `username`='$username'";
        $result = mysqli_query($con, $query);
        
        if ($result) {
            $row = mysqli_fetch_assoc($result);
            return $row['total_expense'];
        } else {
            // Handle the error appropriately
            return 0; // or false, or throw an exception
        }
}
function isValidUser($email,$password)
{
    global $con; // Declare $con as a global variable
    $query = "SELECT * FROM `users` 
    WHERE `username`='$email'and `password`='$password'";
    $result = $con->query($query);
    if ($result && $result->num_rows > 0) 
    {
       return true;//user is valid
    }
    return false; //user is invalid
}


function logout()
{
    session_start();
session_unset();  // Remove all session variables
session_destroy();  // Destroy the session

}


?>