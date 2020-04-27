<?php
// Get the product data
$category_id = filter_input(INPUT_POST, 'categoryId_hidden2', FILTER_VALIDATE_INT);
$product_id = filter_input(INPUT_POST, 'product_id_hidden2', FILTER_VALIDATE_INT);

//Retrieve and sanitize the codeinput
$codeInput = filter_input(INPUT_POST, 'productCode_hidden');
$codeInput = filter_var($codeInput, FILTER_SANITIZE_STRING);

//Retrieve and sanitize the name
$name = filter_input(INPUT_POST, 'name_hidden');
$name = filter_var($name, FILTER_SANITIZE_STRING);

//Retrieve and sanitize the price
$price = filter_input(INPUT_POST, 'price_hidden', FILTER_VALIDATE_FLOAT);

//set errors to nothing
$category_error = '';
$code_error = '';
$name_error = '';
$price_error = '';

//clean validate_failed if been used
if (isset($validate_failed)) {
    unset($validate_failed);
}

//clean update_successfull if been used
if (isset($update_successfull)) {
    unset($update_successfull);
}

//We don't need category validation because its impossible to shoose something else :)

if($codeInput == false){
    $code_error = "Please enter a code";
}

if($name == false){
    $name_error = "Please enter a name";
}

if($price == false){
    $price_error = "Please enter a price";
} else if($price < 0 || $price > 50000){
    $price_error = "Please enter a price between 0 and 50 000 dollars";
}

if($price_error!='' || $name_error!=''  || $code_error!=''  || $category_error!='' ) {
    $validate_failed = TRUE;
    include('update_form.php');
    exit();
} else {
    require_once('database.php');

    // Update the product   
    $query = 'UPDATE products
    SET `categoryID` = :category_id , `productCode` =:code, `productName`= :name, `listPrice` = :price
     Where `products`.`productID` =:product_id';

    // IN a case you want to check what is passing
    //error_log(print_r('category_id: ' . $category_id, TRUE)); 
    //error_log(print_r('code: ' . $codeInput, TRUE)); 
    //error_log(print_r('name: ' . $name, TRUE)); 
    //error_log(print_r('price: ' . $price, TRUE)); 
    //error_log(print_r('product_id: ' . $product_id, TRUE)); 

    $statement = $db->prepare($query);
    $statement->bindValue(':category_id', $category_id);
    $statement->bindValue(':code', $codeInput);
    $statement->bindValue(':name', $name);
    $statement->bindValue(':price', $price);
    $statement->bindValue(':product_id', $product_id);
    $statement->execute();
    $statement->closeCursor();
    // If everything been executed successfull
    $update_successfull = TRUE;

    // Display the Product List page
    include('index.php');
}
?>