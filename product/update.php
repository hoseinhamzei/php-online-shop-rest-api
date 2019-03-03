<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
 
// include database and object files
include_once '../config/database.php';
include_once '../objects/product.php';
 
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// prepare product object
$product = new Product($db);
 
// get id of product to be edited
$data = json_decode(file_get_contents("php://input"));
 

if(
    !empty($_POST['id']) &&
    !empty($_POST['name']) &&
    !empty($_POST['price']) &&
    !empty($_POST['description']) &&
    !empty($_POST['category_id'])
){


    // get and save image file if exists

    if(!empty($_FILES['image'])){
        $uploaddir = '../images/';

        $file = $_FILES['image'];

        if(move_uploaded_file($file['tmp_name'], $uploaddir .basename($file['name'])))
        {
            $finalimage = $file['name'];
            $product->image = $finalimage;
        }else{
            http_response_code(400);
 
            // tell the user
            echo json_encode(array("message" => "failed to upload image"));
        }
        
    }






// set ID property of product to be edited
$product->id = $_POST['id'];
 
// set product property values
$product->name = $_POST['name'];
$product->price = $_POST['price'];
$product->description = $_POST['description'];
$product->category_id = $_POST['category_id'];
 
// update the product
if($product->update()){
 
    // set response code - 200 ok
    http_response_code(200);
 
    // tell the user
    echo json_encode(array("message" => "Product was updated."));
}
 
// if unable to update the product, tell the user
else{
 
    // set response code - 503 service unavailable
    http_response_code(503);
 
    // tell the user
    echo json_encode(array("message" => "Unable to update product."));
}


} else{
 
    // set response code - 400 bad request
    http_response_code(400);
 
    // tell the user
    echo json_encode(array("message" => "Unable to update product. Data is incomplete."));
}
?>