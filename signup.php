
<?php include 'nav.php'; ?>
    
<!DOCTYPE html>
<html>
<head>
	<title>sinup</title>
</head>

<style>

    /* form design */

.div7 {
    width: 50%;
    margin: 50px auto;
    text-align: center;
}


.div7 form {
    padding: 20px;
    border: 1px solid #ccc;
    border-radius: 8px;
    background-color: #f9f9f9;
}


 input[type="text"],

 input[type="email"],
 input[type="number"],
 input[type="file"],
 input[type="date"],
 /* input[type="radio"], */
 input[type="password"]
 {
    width: 400px;
    padding: 10px;
    margin-bottom: 10px;
    box-sizing: border-box;
    border: 1px solid #ccc;
  
}


.div7 button {
    background-color:orange ;
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 10px;
    width: 150px;
}


button:hover {
    background-color: #45a049;
}
</style>


<!-- data validation  -->

    <script>

         function validatePhoneNumber(number) {
            var phonePattern = /^[0-9]*\d{11}/;
            return phonePattern.test(number);
        }
        function validatePhoneEmail(email){
            var emailPattern =/^[a-zA-Z0-9]*[@][a-zA-Z]*[\.][a-z]/;
           return emailPattern.test(email);
        }
        function validatePhoneName(name){
            var namePattern =/^[a-zA-Z ]/

           return namePattern.test(name);
        }

        function validateForm() {
            var name = document.getElementById("v_name").value;
            var number = document.getElementById("v_number").value;
            var email = document.getElementById("v_email").value;
            var address = document.getElementById("v_address").value;
           
      
            var password = document.getElementById("v_password").value;


            if (name == "") {
                document.getElementById('err_name').innerHTML = "Please enter your name!";
            }   else if (!validatePhoneName(name)) {
                document.getElementById('err_number').innerHTML = "Please enter a valid Name!";
            }
            else if (!validatePhoneNumber(number)) {
                document.getElementById('err_number').innerHTML = "Please enter a valid phone number!";
            }
            else if (email == "") {
                document.getElementById('err_email').innerHTML = "Please enter your email address!";
            } else if (!validatePhoneEmail(email)){
                document.getElementById('err_email').innerHTML = "Please enter a valid Email!";
            } 
            else if (password == "") {
                document.getElementById('err_password').innerHTML = "Please enter your password!";
            } else if (password.length < 8) {
                document.getElementById('err_password').innerHTML = "Password must be at least 8 characters long!";
            }else {
                return true;
           }

           return false;
   
        }

    </script>

</head>

<body>
<!-- 
data insert form -->
<?php $show_num="";
$show_email=""; ?>
    <div class="div7">

        <form method="post" action="" enctype="multipart/form-data">
            <h1>Sign-Up Form</h1><br><br>

            <label>Enter Your Name</label><br>
            <span style="color: red" id="err_name"> </span><br>
            <input type="text" placeholder="Enter Your name" name="name" value="" id="v_name"><br><br>

            <label>Enter Your Phone Number</label><br>
            <span style="color: red" id="err_number" <?php echo $show_num; ?>></span> <br>
            <input type="number" placeholder="Enter Your Phone number" name="number" value="" id="v_number"><br>

            <label>Enter Your Email</label><br>
            <span style="color: red" id="err_email" <?php echo $show_email; ?>></span> <br>
            <input type="email" placeholder="Enter your Email Address" name="email" id="v_email"><br>

            <label>Enter Your Address</label><br>
            <span style="color: red" id="err_address"></span> <br>
            <input type="text" placeholder="Enter your Address" name="address" id="v_address"><br>
            
         

    


           <label>Enter Password</label><br>
            <span style="color: red" id="err_password"></span> <br>
            <input type="password" name="password" placeholder="Enter password" value="" id="v_password"><br>

            <button type="submit" name="submit" onclick="return validateForm()">Submit</button>
        </form>

    </div>
</body>
</html>


  
<?php
include 'db.php';

// data insert into database



if (isset($_POST['submit'])) {
    
    
        $name    =$_POST['name'];
        $number  =$_POST['number'];
        $email   =$_POST['email'];
        $address =$_POST['address'];
    
        $image = $_FILES['image']['name'];
        $temp_name = $_FILES['image']['tmp_name'];
        $upload_folder = 'image/';
        $uniq_image_name = uniqid();
        move_uploaded_file($temp_name, $upload_folder . "$uniq_image_name.jpg");
      
        $date    =$_POST['date'];
        $gender  =$_POST['gender'];
        $password=$_POST['password'];
        $incrpassword=md5(sha1($password));

        $check=" SELECT * FROM singup  WHERE number = '$number' OR  email = '$email'";

        $result=mysqli_query($database_connection, $check );
         $row=mysqli_num_rows($result);
        if ($row > 0) {
          while ($data = mysqli_fetch_assoc($result)) {
            if ($data['number'] == $number) {
              $show_num = 'Number Already Exists. Try with another one!';
              break;
            }
            else if($data['email']==$email){
              $show_email = 'Email Already Exists. Try with another one!';
              break;
            }
            
          }
        }

       $insart_query = "INSERT INTO singup(name,number,email,address,image,date_of_birth,gender,password) VALUES
        ('$name','$number','$email','$address',  '$uniq_image_name.jpg','$date','$gender','$incrpassword')";
     


if (mysqli_query($database_connection, $insart_query) == true) {
 ?>
<script>
        alert("successfully");
        window.location.replace("login.php");
      </script>/
      <?php
    } else {
        echo "Something went wrong with the database insert.";
    }

      
}

   
?>
