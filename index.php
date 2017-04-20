<!DOCTYPE html>


    <html>

        <head>
            <meta charset="UTF-8">
            <title></title>
        </head>
        
        <body>
            <?php
            $servername_db = "localhost";
            $username_db = "root";
            $password_db = "";
            $name_db = "securitydb";
            
            $name_error_1 = $password_error_1 = $name_error_2 = $password_error_2 = "";
            $name_1 = $password_1 = $name_2 = $password_2 = "";
            

            if (isset($_POST['submit_1']))
            {
                if (empty($_POST["name_1"]))
                {
                    $name_error_1 = "Name is required";
                } 
                else {
                    $name_1 = $_POST["name_1"];
                }
                if (empty($_POST["password_1"])) 
                {
                    $password_error_1 = "Password is required";
                } 
                else {
                    $password_1 = $_POST["password_1"];
                }
            } 
            
            else if (isset($_POST['submit_2'])) 
            {
                if (empty($_POST["name_2"])) 
                {
                    $name_error_2 = "Name is required";
                } 
                else {
                    $name_2 = $_POST["name_2"];
                }
                if (empty($_POST["password_2"])) 
                {
                    $password_error_2 = "Password is required";
                } else {
                    $password_2 = $_POST["password_2"];
                }
            }
            ?>
            
            <div style="float: left; width: 40%; padding-left: 10%;">
                <b> Unsafe login form </b>

                <form method="post"  action="">
                    Unsafe name: <input type="text" name="name_1" value="<?php echo $name_1; ?>">

                    <br>
                    Unsafe password: <input type="text" name="password_1" value="<?php echo $password_1; ?>">

                    <br>
                    <input type="submit" name="submit_1" value="Unsafe Submit">
                    
                    <br><br>
                    <?php echo $name_error_1; ?>
                    <br>
                    <?php echo $password_error_1; ?> <br>

                </form>
            
            </div>
            
<!--------------------------------------------------------------------------------------------------------------------->
<!--------------------------------------------------------------------------------------------------------------------->
<!--------------------------------------------------------------------------------------------------------------------->
<!--------------------------------------------------------------------------------------------------------------------->
<!--------------------------------------------------------------------------------------------------------------------->
<!--------------------------------------------------------------------------------------------------------------------->

            <div style="float: left; width: 50%;">
                
                <b> Safe login form </b>

                <form method="post"  action="">
                    Safe name: <input type="text" name="name_2" value="<?php echo $name_2; ?>">

                    <br>
                    Safe password: <input type="text" name="password_2" value="<?php echo $password_2; ?>">

                    <br>
                    <input type="submit" name="submit_2" value="Safe Submit">
                    
                    <br><br>
                    <?php echo $name_error_2; ?>
                    <br>
                    <?php echo $password_error_2; ?> <br>
                    
                </form>
                
            </div>
            
            <?php

            $info_state ="";
            
            if (empty($_POST["name_1"]) && empty($_POST["password_1"]))
                {
                        
                } 
            else {
                $conn = new mysqli($servername_db, $username_db, $password_db, $name_db);
                $sql = "SELECT * FROM user WHERE user.Name = '$name_1' AND user.Password = '$password_1' LIMIT 1;";
            
                $result = mysqli_query($conn, $sql);
            
                if (mysqli_affected_rows($conn) == 1)
                {
                    $info_state .="<br>Succeded login attempt.<br>Login sql: <br><b>".$sql."</b>";
                } 
                else 
                {
                    $info_state .="<br>Failed login attempt.<br>Login sql: <br><b>".$sql."</b>";
                }
                $conn->close();
            }
            
            
//<!--------------------------------------------------------------------------------------------------------------------->
//<!--------------------------------------------------------------------------------------------------------------------->
//<!--------------------------------------------------------------------------------------------------------------------->
//<!--------------------------------------------------------------------------------------------------------------------->
//<!--------------------------------------------------------------------------------------------------------------------->
//<!--------------------------------------------------------------------------------------------------------------------->
            $stmt="";
            
            if (empty($_POST["name_2"]) && empty($_POST["password_2"])) 
            {
            
            } 
            else {
                $conn = new mysqli($servername_db, $username_db, $password_db, $name_db);
                $sql = "SELECT user.Name, user.Password FROM user WHERE user.Name = ?;";
            
                $stmt = $conn->prepare($sql);
                $stmt->bind_param('s', $name_2);
                $stmt->execute();
                $stmt->store_result();
            
                $stmt->bind_result($Name, $Digest);

                
                while ($stmt->fetch()) 
                {
                    if (password_verify($password_2, $Digest)) 
                    {
                        $info_state .="<br>Succeded login attempt.<br>Login sql: <br><b>".$sql."</b>";
                    }
                    else 
                    {
                        $info_state .="<br>Failed login attempt.<br>Login sql: <br><b>".$sql."</b>";
                    }
                }
            
                $conn->close();
            }
            ?>


            <div style="float: left; width: 100%;">
                <?php
                
                    echo $info_state;
                    
                    echo "<pre>";
                    print_r($stmt);
                    echo "</pre>";
                
                ?>
            </div>

        </body>
    </html>