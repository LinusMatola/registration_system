<?php 

// fill the details for your database connection
$con = mysqli_connect('', '', '', '');

function row_count($result){

    return mysqli_num_rows($result);
}

function escape($string){
    global $con;

    return mysqli_real_escape_string($con, $string);
}

function query($query){

    global $con;

    return mysqli_query($con, $query);
}

function confirm($result){
    global $con;

    if(!$result){
        die("Query Execution Failed!!". mysqli_error($con));
    }

}

function fetch_array($result){
    global $con;

    return mysqli_fetch_array($result);
}

?>