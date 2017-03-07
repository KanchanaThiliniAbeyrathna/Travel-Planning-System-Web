<?php
function connect(){
    $connection = mysqli_connect("localhost","root","","travelplanning");
    if(mysqli_connect_errno()){
        die("Connection failed".mysqli_connect_error()." ".mysqli_connect_errno());
    }
    return $connection;
}
function colse_connection($connection){
    if(isset($connection)){mysqli_close($connection);}
}
function confirm_query($result){
    if (!$result) {
    die("Database query failed");
    }
}



function get_all_hotelcategories(){
    $connection = connect();
    $query = "SELECT * from hotel_category";
    $result = mysqli_query($connection,$query);
    confirm_query($result);
    colse_connection($connection);
    return $result;

}