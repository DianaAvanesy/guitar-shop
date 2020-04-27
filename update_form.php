<?php
require('database.php');
$queryCategories = 'SELECT *
          FROM categories
          ORDER BY categoryID';
$statement1 = $db->prepare($queryCategories);
$statement1->execute();
$categories = $statement1->fetchAll();
$statement1->closeCursor();
?>

<?php
// Getting proguct ID from post form 
$product_id_hidden2 = $_POST['product_id_hidden2'];

// Check if this page has been loaded before
if (isset($validate_failed)) {

    // Senario 1:
    // YES, load data from $_POST array
    // while resubmiting and getting an error in one of the fields 
    // it will save other fields and show them updated in form, but
    // user still need to submit the updated product 
    $categoryId_hidden2 = $_POST['categoryId_hidden2'];
    $productCode_hidden = $_POST['productCode_hidden'];
    $name_hidden = $_POST['name_hidden'];
    $price_hidden = $_POST['price_hidden'];

} else {
    // Senario 2:
    // NO, and it's first time load, load data from database
    $queryProduct = 'SELECT *
        FROM products
        WHERE productID = :product_id';
    $statement2 = $db->prepare($queryProduct);
    $statement2->bindValue(':product_id', $product_id_hidden2);
    $statement2->execute();
    $product = $statement2->fetch();
    $statement2->closeCursor();

    $categoryId_hidden2 = $product['categoryID'];
    $productCode_hidden = $product['productCode'];
    $name_hidden = $product['productName'];
    $price_hidden = $product['listPrice'];
}
?>


<!DOCTYPE html>
<html>

<!-- the head section -->
<head>
    <title>My Guitar Shop</title>
    <link rel="stylesheet" type="text/css" href="main.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
</head>

<!-- the body section -->
<body>
    <div class="container">

    <main>
        <h1>Update Product</h1>

        <form action="update_product.php" method="post"
              id="update_form.php">
              <!-- This hidden field is used to store the productID -->
              <input type="hidden" name="product_id_hidden2"
                    value="<?php echo $product_id_hidden2; ?>">

            <!-- Category -->
            <div class="form-group">
                <label>Category:</label>
                <?php if(isset($category_error) && $category_error != ''){ ?>
                    <h3 class='text-danger'>
                    <?php echo $category_error; ?></h3>
                <?php } ?>                                                       
                <select class="form-control" name="categoryId_hidden2">
                <?php foreach ($categories as $category) : 
                    if ($category['categoryID'] == $categoryId_hidden2){?>
                        <option value="<?php echo $category['categoryID']; ?>" selected > 
                        <?php echo $category['categoryName']; ?>
                        </option><?php 
                    continue; } ?>
                    <option value="<?php echo $category['categoryID']; ?>">
                     <?php echo $category['categoryName']; ?>
                    </option>
                <?php endforeach; ?>
                </select><br>
            </div>

            
            <!-- Code -->
            <div class="form-group">
                <label for="code">Code:</label>
                <?php if(isset($code_error) && $code_error != ''){ ?>
                    <h3 class='text-danger'><?php echo $code_error; ?></h3>
                <?php } ?>
                <input class="form-control" type="text" name="productCode_hidden" id="productCode_hidden"
                value="<?php echo $productCode_hidden ?>"><br>
            </div>

            <!-- Name -->
            <div class="form-group">
                <label for="name">Name:</label>
                <?php if(isset($name_error) && $name_error != ''){ ?>
                    <h3 class='text-danger'><?php echo $name_error; ?></h3>
                <?php } ?>
                <input class="form-control" type="text" name="name_hidden" id="name_hidden"
                value="<?php echo $name_hidden ?>"><br>
            </div>

            <!-- List Price -->
            <div class="form-group">
                <label for="price">List Price:</label>
                <?php if(isset($price_error) && $price_error != ''){ ?>
                    <h3 class='text-danger'><?php echo $price_error; ?></h3>
                <?php } ?>
                <input class="form-control" type="text" name="price_hidden" id="price_hidden"
                value="<?php echo $price_hidden ?>"><br>
            </div>

            <label>&nbsp;</label>
            <input class="btn btn-primary" type="submit" value="Update"><br>
        </form>
        <p><br><a href="index.php">Cancel and go back</a></p>
    </main>
    </div>
</body>
</html>