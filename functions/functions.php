<?php 

function clean($string){

    return htmlentities($string);
}

function redirect($location){

    return header("Location: {$location}");
}

function set_message($message){

    if(!empty($message)){

        $_SESSION['message'] = $message;
    }else {
        $message = "";
    }
}

function display_message(){

    if(isset( $_SESSION['message'])){

        echo  $_SESSION['message'];
        unset( $_SESSION['message']);
    }
}

function token_generator(){

    $token = $_SESSION['token'] = md5(uniqid(mt_rand(), true));
    return $token;
}

function email_exists($email){
     $sql = "SELECT id from users where email = '$email'";
     $result = query($sql);
     if(row_count($result) == 1){
         return true;
     } else {
         return false;
     }

}

function phonenumber_exists($phonenumber){
    $sql = "SELECT id from users where phonenumber = '$phonenumber'";
    $result = query($sql);
    if(row_count($result) == 1){
        return true;
    } else {
        return false;
    }

}

/***************************Validation functions******************************* */
function validate_user_registration(){

    $errors = [];
    $min = 3;
    $max = 20;

    if($_SERVER['REQUEST_METHOD'] == "POST"){

        $first_name         = clean($_POST['first_name']);
        $last_name          = clean($_POST['last_name']);
        $phonenumber        = clean($_POST['phonenumber']);
        $email              = clean($_POST['email']);
        $role               = clean($_POST['role']);
        $p_location         = clean($_POST['p_location']);
        $date               = clean($_POST['date']);
        $password           = clean($_POST['password']);
        $confirm_password   = clean($_POST['confirm_password']);

        if(strlen($first_name)<$min){
            $errors[] = "Your First Name cannot be less than {$min} characters";
        }
        
        if(strlen($last_name)<$min){
            $errors[] = "Your Last Name cannot be less than {$min} characters"; 
        }
        if(email_exists($email)){
            $errors[] = "The Email already exists";
        }

        if(phonenumber_exists($phonenumber)){
            $errors[] = "The phonenumber is already taken";
        }

        if($password !== $confirm_password){
            $errors[] = "Your password fields do not match";

        }

        if(!empty($errors)){
            foreach($errors as $error){

                echo $error;
                // echo '<div class="alert alert-danger alert-dismissible" role="alert">
                // <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                // <span aria-hidden="true"></span></buttton>
                // ' .$error .'
                // </div>';
            }
        } else{
            if(register_user($first_name, $last_name, $phonenumber, $email, $role, $p_location, $date, $password)){

                echo "User Registered Successfully!!";
            }
        }

    } //post request
} //function

/***************************Validation_code functions******************************* */
function randomstring($len) { 
    $validation_code = ""; 
    $chars = "123456789ABCDEFGHIJKL123456789MNOPQ123456789RSTUVW123456789XYZ123456789"; 
    for($i=0;$i<$len;$i++) 
    $validation_code.=substr($chars,rand(0,strlen($chars)),1); 
    return $validation_code; 
} //echo randomstring(9);

/***************************Register user functions******************************* */

function register_user($first_name, $last_name, $phonenumber, $email, $role, $p_location, $date, $password){
    
    $first_name = escape($first_name);
    $last_name  = escape($last_name);
    $phonenumber= escape($phonenumber);
    $email      = escape($email);
    $role       = escape($role);
    $p_location = escape($p_location);
    $date       = escape($date);
    $password   = escape($password);

    if(email_exists($email)){

        return false;
    } elseif(phonenumber_exists($phonenumber)){

        return false;
    } else{

        $password   = md5($password);
        $validation_code = randomstring(9);

        $sql = "INSERT INTO users(first_name, last_name, phonenumber, email, roles, p_location, reg_date, password, validation_code, active)";
        $sql.= " VALUES('$first_name', '$last_name', '$phonenumber', '$email', '$role', '$p_location', '$date', '$password', '$validation_code', 0)";
        $result = query($sql);
        confirm($result);

        return true;
    }

}

/***************************Activate user functions******************************* */
function activate_user(){

    if($_SERVER['REQUEST_METHOD'] =="GET"){

        if(isset($_GET['email'])){

            echo $email = clean($_GET['email']);
            echo $validation_code = clean($_GET['code']);
        }
    }
}

?>