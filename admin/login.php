<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
 
// get database connection
include_once '../config/database.php';
 

include_once '../objects/admin.php';
 
$database = new Database();
$db = $database->getConnection();

$admin = new Admin($db);

// get posted data
$data = json_decode(file_get_contents("php://input"));
 
// set product property values
$admin->username = $data->username;

$admin_exists = $admin->adminExists();

if($admin_exists){

    if($data->password === $admin->password){

        $useritem = array(
            "message" => "login seccessful",
            "id" => $admin->id,
            "username" => $admin->username
        );

        // set response code - 200 OK
    http_response_code(200);
 
    // show categories data in json format
    echo json_encode($useritem);

    } else{

    // set response code
    http_response_code(400);
 
    // tell the user login failed
    echo json_encode(array("message" => "Login failed, password incorrect"));

    }


}else{
    // set response code - 404 Not found
    http_response_code(404);
 
    
    echo json_encode(
        array("message" => "admin not found, enter a valid username")
    );
}

