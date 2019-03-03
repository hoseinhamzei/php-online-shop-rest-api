<?php
class Admin{
 
    // database connection and table name
    private $conn;
    private $table_name = "admins";
 
    // object properties
    public $id;
    public $username;
    public $password;
    public $role;
    public $created;
 
    public function __construct($db){
        $this->conn = $db;
    }
 
    // used by select drop-down list
    public function readAll(){
        //select all data
        $query = "SELECT
                    id, username, password
                FROM
                    " . $this->table_name . "
                ORDER BY
                    username";
 
        $stmt = $this->conn->prepare( $query );
        $stmt->execute();
 
        return $stmt;
    }


    
public function read(){
 
    //select all data
    $query = "SELECT
                id, username, password
            FROM
                " . $this->table_name . "
            ORDER BY
                username";
 
    $stmt = $this->conn->prepare( $query );
    $stmt->execute();
 
    return $stmt;
}


///////////////////////////////////////////////////////////////

// create category
function create(){
 
    // query to insert record
    $query = "INSERT INTO
                " . $this->table_name . "
            SET
                username=:username, password=:password, role=:role";
 
    // prepare query
    $stmt = $this->conn->prepare($query);
 
    // sanitize
    $this->username=htmlspecialchars(strip_tags($this->username));
    $this->password=htmlspecialchars(strip_tags($this->password));
    $this->role=htmlspecialchars(strip_tags($this->role));
 
    // bind values
    $stmt->bindParam(":username", $this->username);
    $stmt->bindParam(":password", $this->password);
    $stmt->bindParam(":role", $this->role);
    
 
    // execute query
    if($stmt->execute()){
        return true;
    }
 
    return false;
     
}


///////////////////////////////////////////////////////////

// delete the category
function delete(){
 
    // delete query
    $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
 
    // prepare query
    $stmt = $this->conn->prepare($query);
 
    // sanitize
    $this->id=htmlspecialchars(strip_tags($this->id));
 
    // bind id of record to delete
    $stmt->bindParam(1, $this->id);
 
    // execute query
    if($stmt->execute()){
        return true;
    }
 
    return false;
     
}

///////////////////////////////////////////////////////////

function update(){
 
    // update query
    $query = "UPDATE
                " . $this->table_name . "
            SET
                username = :username,
                password = :password
            WHERE
                id = :id";
 
    // prepare query statement
    $stmt = $this->conn->prepare($query);
 
    // sanitize
    $this->username=htmlspecialchars(strip_tags($this->username));
    $this->password=htmlspecialchars(strip_tags($this->password));
    $this->id=htmlspecialchars(strip_tags($this->id));
 
    // bind new values
    $stmt->bindParam(':username', $this->username);
    $stmt->bindParam(':password', $this->password);
    $stmt->bindParam(':id', $this->id);
 
    // execute the query
    if($stmt->execute()){
        return true;
    }
 
    return false;
}


/////////////////////////////////////////////////////

function adminExists(){
 
    // query to check if admin exists
    $query = "SELECT id, username, password
            FROM " . $this->table_name . "
            WHERE username = ?
            LIMIT 0,1";
 
    // prepare the query
    $stmt = $this->conn->prepare( $query );
 
    // sanitize
    $this->username=htmlspecialchars(strip_tags($this->username));
 
    // bind given username value
    $stmt->bindParam(1, $this->username);
 
    // execute the query
    $stmt->execute();
 
    // get number of rows
    $num = $stmt->rowCount();
 
    // if username exists, assign values to object properties for easy access and use for php sessions
    if($num>0){
 
        // get record details / values
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
 
        // assign values to object properties
        $this->id = $row['id'];
        $this->password = $row['password'];
 
        // return true because admin exists in the database
        return true;
    }
 
    // return false if admin does not exist in the database
    return false;
}



}
?>