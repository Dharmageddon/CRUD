<?php include "../lock.php"; ?>

<?php
// Check existence of id parameter before processing further
if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
    // Prepare a select statement

    $sql = "SELECT * FROM employees WHERE id = ?";

    if($stmt = $mysqli->prepare($sql)){
        // Bind variables to the prepared statement as parameters
        $stmt->bind_param("i", $param_id);

        // Set parameters
        $param_id = trim($_GET["id"]);

        // Attempt to execute the prepared statement
        if($stmt->execute()){
            $result = $stmt->get_result();

            if($result->num_rows == 1){
                /* Fetch result row as an associative array. Since the result set contains only one row, we don't need to use while loop */
                $row = $result->fetch_array(MYSQLI_ASSOC);

                // Retrieve individual field value
                $name = $row["name"];
                $address = $row["address"];
                $city = $row["city"];
                $state = $row["state"];
                $zip = $row["zip"];
            } else{
                // URL doesn't contain valid id parameter. Redirect to error page
                //header("location: error.php");
                //exit();
            }

        } else{
            echo "Oops! Something went wrong. Please try again later.";
        }
    }

    // Close statement
    //$stmt->close();

    // Close connection
    //$mysqli->close();
} else{
    // URL doesn't contain id parameter. Redirect to error page
    //header("location: error.php");
    //exit();
}
?>

<?php
// Process delete operation after confirmation
if(isset($_POST["id"]) && !empty($_POST["id"])){

    // Prepare a delete statement
    $sql = "DELETE FROM employees WHERE id = ?";

    if($stmt = $mysqli->prepare($sql)){
        // Bind variables to the prepared statement as parameters
        $stmt->bind_param("i", $param_id);

        // Set parameters
        $param_id = trim($_POST["id"]);

        // Attempt to execute the prepared statement
        if($stmt->execute()){
            // Records deleted successfully. Redirect to landing page
            header("location: index.php");
            exit();
        } else{
            echo "Oops! Something went wrong. Please try again later.";
        }
    }

    // Close statement
    $stmt->close();

    // Close connection
    $mysqli->close();
} else{
    // Check existence of id parameter
    if(empty(trim($_GET["id"]))){
        // URL doesn't contain id parameter. Redirect to error page
        header("location: error.php");
        exit();
    }
}
?>

<?php include '../includes/header.php'; ?>

<main role="main" class="flex-shrink-0">
<div class="container">
    <!--
    <h2>Delete Record</h2>
    <p>Please submit this form to delete employee record from the database.</p>
    -->
    <form class="container p-3" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
      <fieldset class="">
        <legend class="pt-2"><i class="fas fa-trash"></i> Delete Record</legend>
        <hr>
        <div class="form-group row">
            <label class="col-sm-2 col-form-label text-right">Name</label>
            <input type="text" name="name" class="form-control col-sm-8" value="<?php echo $name; ?>" readonly>
        </div>
        <div class="form-group row">
            <label class="col-sm-2 col-form-label text-right">Address</label>
            <textarea name="address" class="form-control col-sm-8" readonly><?php echo $address; ?></textarea>
        </div>
        <div class="form-group row">
            <label class="col-sm-2 col-form-label text-right">City</label>
            <input type="text" name="city" class="form-control col-sm-8" value="<?php echo $city; ?>" readonly>
        </div>
        <div class="form-group row">
            <label class="col-sm-2 col-form-label text-right">State</label>
            <input type="text" name="state" class="form-control col-sm-8" value="<?php echo $state; ?>" readonly>
        </div>
        <div class="form-group row">
            <label class="col-sm-2 col-form-label text-right">Zip</label>
            <input type="text" name="zip" class="form-control col-sm-8" value="<?php echo $zip; ?>" readonly>
        </div>
        <div>
            <input type="hidden" name="id" value="<?php echo trim($_GET["id"]); ?>"/>
            <input type="submit" class="btn btn-danger" value="Delete">
            <a href="index.php" class="btn btn-link">Cancel</a>
        </div>
      </fieldset>
    </form>

</div>
</main>

<?php include '../includes/footer.php'; ?>
