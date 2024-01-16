<?php
include 'db.php';

$errors = [];



if (isset($_POST['submit'])) {
    $gender = isset($_POST['gender']) ? $_POST['gender'] : '';
    $dynamicData = array(
        'name' => $_POST['name'],
        'number' => $_POST['number'],
        'email' => $_POST['email'],
        'address' => $_POST['address'],
        'date' => $_POST['date'],
        'gender' => $gender
    );

    // Handle image uploads
    $uploadedImages = array();
    $imageFolder = 'image/';

    foreach ($_FILES['image']['tmp_name'] as $key => $tmpName) {
        $imageName = $_FILES['image']['name'][$key];
        $imagePath = $imageFolder . $imageName;
        move_uploaded_file($tmpName, $imagePath);
        $uploadedImages[] = $imageName;
    }

    foreach ($dynamicData['name'] as $key => $value) {
        // Validate each set of form fields
        $name = $dynamicData['name'][$key];
        $number = $dynamicData['number'][$key];
        $email = $dynamicData['email'][$key];
        $address = $dynamicData['address'][$key];
        $image = $dynamicData['image'][$key];
        $date = $dynamicData['date'][$key];

    
        // Validate if the 'number' or 'email' already exists
        $check = "SELECT * FROM employee_info WHERE number = '$number' OR email = '$email'";
        $result = mysqli_query($database_connection, $check);
    
       
        $row = mysqli_num_rows($result);
    
        if ($row > 0) {
            while ($data = mysqli_fetch_assoc($result)) {
                if ($data['number'] == $number) {
                  
                    echo "<script>alert('Number Already Exists. Try with another one!'); window.location.replace('index.php');</script>";
                    exit();
                    
                } elseif ($data['email'] == $email) {
                    echo "<script>alert('Email Already Exists. Try with another one!'); window.location.replace('index.php');</script>";
                    exit();
                }
            }
        } else
    
    

      
        if (empty($name) || !preg_match("/^[a-zA-Z ]*$/", $name)) {
            $errors['name'][$key] = "Please enter a valid name";
            echo "<script>alert('{$errors['name'][$key]}'); window.location.replace('index.php');</script>";
            exit();
        }
        
        

        if (empty($number) || !preg_match("/^[0-9]{11}$/", $number)) {
            $errors['number'][$key] = "Please enter a valid phone number";
            echo   $errors['number'][$key];
            echo "<script>alert('{$errors['number'][$key]}'); window.location.replace('index.php');</script>";
            exit();
        }

        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'][$key] = "Please enter a valid email address";
            echo   $errors['email'][$key];
            echo "<script>alert('{$errors['email'][$key]}'); window.location.replace('index.php');</script>";
            exit();
        }
        

        if (empty($address)) {
            $errors['address'][$key] = "Please enter your address";
            echo   $errors['address'][$key];
            echo "<script>alert('{$errors['address'][$key]}'); window.location.replace('index.php');</script>";
            exit();
        }
        if (empty($date)) {
            $errors['date'][$key] = "Please enter the joining date";
            echo $errors['date'][$key];
            echo "<script>alert('{$errors['date'][$key]}'); window.location.replace('index.php');</script>";
            exit();
        }
        
        // Validate that the date is not a future date
        $currentDate = date("Y-m-d");
        if (strtotime($date) > strtotime($currentDate)) {
            $errors['date'][$key] = "Joining date cannot be in the future";
            echo $errors['date'][$key];
            echo "<script>alert('{$errors['date'][$key]}'); window.location.replace('index.php');</script>";
            exit();
        }
         
           if (empty($imageName) || !preg_match("/^.*\.(jpg|jpeg|png)$/i", $imageName)) {
            $errors['image'][$key] = "Invalid image file name.";
            echo $errors['image'][$key];
            echo "<script>alert('{$errors['image'][$key]}'); window.location.replace('index.php');</script>";
            exit();
        }
        
        }
    }

        if (isset($dynamicData['gender']) && is_array($dynamicData['gender']) && isset($dynamicData['gender'][$key])) {
            $gender = $dynamicData['gender'][$key];
        
            if (empty($gender)) {
                $errors['gender'][$key] = "Please select your gender";
                echo $errors['gender'][$key];
                exit();
            }
        } else {
            $errors['gender'][$key] = "Gender information is missing";
            echo $errors['gender'][$key];
            exit();
        }
        
    

    
    
    
    
    
    // Insert data into the database
        foreach ($dynamicData['name'] as $key => $value) {
            $dynamicForm = "INSERT INTO employee_info (name, number, email, address, image, date, gender) 
                            VALUES ('{$dynamicData['name'][$key]}', '{$dynamicData['number'][$key]}', '{$dynamicData['email'][$key]}',
                                    '{$dynamicData['address'][$key]}', '{$uploadedImages[$key]}', '{$dynamicData['date'][$key]}','{$dynamicData['gender'][$key]}')";

            if (mysqli_query($database_connection, $dynamicForm)) {
                ?>
                <script>
                    alert("Successfully inserted data");
                    window.location.replace("index.php");
                </script>
                <?php
            } else {
                echo "Something went wrong with the database insert.";
            }
        }
    
    

?>

