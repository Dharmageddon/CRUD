<?php include "../lock.php"; ?>

<?php

$name = $address = $city = $state = $zip = "";
$name_err = $address_err = $city_err = $state_err = $zip_err = "";

// Processing form data when form is submitted
if(isset($_POST["id"]) && !empty($_POST["id"])){
    // Get hidden input value
    $id = $_POST["id"];

    // Validate name
    $input_name = trim($_POST["name"]);
    if(empty($input_name)){
        $name_err = "Please enter a name.";
    } elseif(!filter_var($input_name, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/")))){
        $name_err = "Please enter a valid name.";
    } else{
        $name = $input_name;
    }

    // Validate address address
    $input_address = trim($_POST["address"]);
    if(empty($input_address)){
        $address_err = "Please enter an address.";
    } else{
        $address = $input_address;
    }

    $input_city = trim($_POST["city"]);
    if(empty($input_city)){
        $city_err = "Please enter a city.";
    } else{
        $city = $input_city;
    }

    $input_state = trim($_POST["state"]);
    if(empty($input_state)){
        $state_err = "Please enter a state.";
    } else{
        $state = $input_state;
    }

    // Validate salary
    $input_zip = trim($_POST["zip"]);
    if(empty($input_zip)){
        $zip_err = "Please enter the zip code.";
    } elseif(!ctype_digit($input_zip)){
        $zip_err = "Please enter a positive integer value.";
    } else{
        $zip = $input_zip;
    }

    // Check input errors before inserting in database
    if(empty($name_err) && empty($address_err) && empty($city_err) && empty($state_err) && empty($zip_err)){
        // Prepare an update statement
        $sql = "UPDATE employees SET name=?, address=?, city=?, state=?, zip=? WHERE id=?";

        if($stmt = $mysqli->prepare($sql)){
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("sssssi", $param_name, $param_address, $param_city, $param_state, $param_zip, $param_id);

            // Set parameters
            $param_name = $name;
            $param_address = $address;
            $param_city = $city;
            $param_state = $state;
            $param_zip = $zip;
            $param_id = $id;

            // Attempt to execute the prepared statement
            if($stmt->execute()){
                // Records updated successfully. Redirect to landing page
                header("location: index.php");
                exit();
            } else{
                echo "Something went wrong. Please try again later.";
            }
        }

        // Close statement
        $stmt->close();
    }

    // Close connection
    $mysqli->close();
} else{
    // Check existence of id parameter before processing further
    if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
        // Get URL parameter
        $id =  trim($_GET["id"]);

        // Prepare a select statement
        $sql = "SELECT * FROM employees WHERE id = ?";
        if($stmt = $mysqli->prepare($sql)){
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("i", $param_id);

            // Set parameters
            $param_id = $id;

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
                    // URL doesn't contain valid id. Redirect to error page
                    header("location: error.php");
                    exit();
                }

            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }

        // Close statement
        $stmt->close();

        // Close connection
        $mysqli->close();
    }  else{
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
    <h2>Update Record</h2>
    <p>Please edit the input values and submit to update the record.</p>
    -->
    <form class="container p-3" action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">
      <fieldset>
        <legend class="pt-2"><i class="fas fa-edit"></i> Update Record</legend>
        <hr>
        <div class="form-group row <?php echo (!empty($name_err)) ? 'has-error' : ''; ?>">
            <label class="col-sm-2 col-form-label text-right">Name</label>
            <input type="text" name="name" class="form-control col-sm-8" placeholder="Name" value="<?php echo $name; ?>">
            <span class="text-danger offset-sm-2"><?php echo $name_err;?></span>
        </div>
        <div class="form-group row <?php echo (!empty($address_err)) ? 'has-error' : ''; ?>">
            <label class="col-sm-2 col-form-label text-right">Address</label>
            <textarea name="address" class="form-control col-sm-8"  placeholder="Address"><?php echo $address; ?></textarea>
            <span class="text-danger offset-sm-2"><?php echo $address_err;?></span>
        </div>
        <div class="form-group row <?php echo (!empty($city_err)) ? 'has-error' : ''; ?>">
            <label class="col-sm-2 col-form-label text-right">City</label>
            <input type="text" name="city" class="form-control col-sm-8" placeholder="City" value="<?php echo $city; ?>">
            <span class="text-danger offset-sm-2"><?php echo $city_err;?></span>
        </div>
        <div class="form-group row <?php echo (!empty($state_err)) ? 'has-error' : ''; ?>">
            <label class="col-sm-2 col-form-label text-right">State</label>
            <input type="text" name="state" class="form-control col-sm-8" placeholder="State" value="<?php echo $state; ?>">
            <span class="text-danger offset-sm-2"><?php echo $state_err;?></span>
        </div>
        <div class="form-group row <?php echo (!empty($zip_err)) ? 'has-error' : ''; ?>">
            <label class="col-sm-2 col-form-label text-right">Zip</label>
            <input type="text" name="zip" class="form-control col-sm-8" placeholder="Zip" value="<?php echo $zip; ?>">
            <span class="text-danger offset-sm-2"><?php echo $zip_err;?></span>
        </div>
        <div>
            <input type="hidden" name="id" value="<?php echo $id; ?>"/>
            <input type="submit" class="btn btn-primary" value="Update">
            <a href="index.php" class="btn btn-link">Cancel</a>
        </div>
      </fieldset>
    </form>

</div>
</main>

<?php include '../includes/footer.php'; ?>
