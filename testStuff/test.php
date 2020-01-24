<?php
        $servername = 'localhost';
        $username = 'root';
        $password = '';


        $conn = mysqli_connect($servername, $username, $password,'TestDB');
        if(!$conn)
        {
                die("connection failed: " . mysqli_connect_error());
        }

        $sql = "INSERT INTO Users (Username, Password)
                VALUES ('".$_POST["Usrn"]."' , '".$_POST["Pass"]."')";
        if(mysqli_query($conn, $sql))
        {
                echo " inserted </br>";
        }
        $check = "SELECT * FROM Users";
        $result = mysqli_query($conn, $check);
        if(mysqli_num_rows($result) > 0)
        
        $result = mysqli_query($conn, $check);
        if(mysqli_num_rows($result) > 0)
        {
                while($row = mysqli_fetch_assoc($result))
                {
                        echo "Username: " . $row["Username"] . "Password: ". $r$
                }
        }
        else
        {
                echo "none";
        }
        mysqli_close($conn);

?>
