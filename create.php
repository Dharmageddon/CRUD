<?php include "../lock.php"; ?>

<?php

$name = $address = $city = $state = $zip = "";
$name_err = $address_err = $city_err = $state_err = $zip_err = "";

if($_SERVER["REQUEST_METHOD"] == "POST"){
    // Validate name
    $input_name = trim($_POST["name"]);
    if(empty($input_name)){
        $name_err = "Please enter a name.";
    } elseif(!filter_var($input_name, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/")))){
        $name_err = "Please enter a valid name.";
    } else{
        $name = $input_name;
    }

    // Validate address
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

    // Validate zip
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
        // Prepare an insert statement
        $sql = "INSERT INTO employees (name, address, city, state, zip) VALUES (?, ?, ?, ?, ?)";

        if($stmt = $mysqli->prepare($sql)){
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("sssss", $param_name, $param_address, $param_city, $param_state, $param_zip);

            // Set parameters
            $param_name = $name;
            $param_address = $address;
            $param_city = $city;
            $param_state = $state;
            $param_zip = $zip;

            // Attempt to execute the prepared statement
            if($stmt->execute()){
                // Records created successfully. Redirect to landing page
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
}
?>

<?php include '../includes/header.php'; ?>

<main role="main" class="flex-shrink-0">
<div class="container">
    <!--
    <h2>Create Record</h2>
    <p>Please fill this form and submit to add employee record to the database.</p>
    -->
    <form class="container p-3" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
      <fieldset>
        <legend class="pt-2"><i class="fas fa-plus-circle"></i> Create Record</legend>
        <hr>
        <div class="form-group row <?php echo (!empty($name_err)) ? 'has-error' : ''; ?>">
            <label class="col-sm-2 col-form-label text-right">Name</label>
            <input type="text" name="name" class="form-control col-sm-8" placeholder="Name" value="<?php echo $name; ?>">
            <div class="text-danger offset-sm-2"><?php echo $name_err;?></div>
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
            <input type="submit" class="btn btn-primary" value="Submit">
            <a href="index.php" class="btn btn-link">Cancel</a>
        </div>
      </fieldset>
    </form>

</div>
</main>

<?php include '../includes/footer.php'; ?>
