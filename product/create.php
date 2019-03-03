<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
 
// get database connection
include_once '../config/database.php';
 
// instantiate product object
include_once '../objects/product.php';
 
$database = new Database();
$db = $database->getConnection();
 
$product = new Product($db);
 

 
// make sure data is not empty
if(
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
 
    // set product property values
    $product->name = $_POST['name'];
    $product->price = $_POST['price'];
    $product->description = $_POST['description'];
    $product->category_id = $_POST['category_id'];
    $product->created = date('Y-m-d H:i:s');
    
 
    // create the product
    if($product->create()){
 
        // set response code - 201 created
        http_response_code(200);
 
        // tell the user
        echo json_encode(array("message" => "Product was created."));
    }
 
    // if unable to create the product, tell the user
    else{
 
        // set response code - 503 service unavailable
        http_response_code(503);
 
        // tell the user
        echo json_encode(array("message" => "Unable to create product."));
    }
}
 
// tell the user data is incomplete
else{
 
    // set response code - 400 bad request
    http_response_code(400);
 
    // tell the user
    echo json_encode(array("message" => "Unable to create product. Data is incomplete."));
}
?>