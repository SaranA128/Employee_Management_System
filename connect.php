<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "employee";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function calculateIncrementPercentage($basicPay, $workingDays) {
    if ($basicPay >= 10000 && $basicPay <= 20000) {
        return ($basicPay * 0.10 * $workingDays);
    } else if ($basicPay >= 20001 && $basicPay <= 30000) {
        return ($basicPay * 0.15 * $workingDays);
    } else if ($basicPay <= 9999) {
        return ($basicPay * 0.05 * $workingDays);
    }
    return 0;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $basicPay = floatval($_POST['basic_pay']);
    $workingDays = intval($_POST['working_days']);

    if ($id < 100 || $id > 999) {
        echo "ID must be a 3-digit number.";
        exit;
    }

    $incrementPercentage = calculateIncrementPercentage($basicPay, $workingDays);
    $totalpay = ($basicPay * $workingDays) + $incrementPercentage;

    $sqlCheck = "SELECT * FROM employees WHERE id = '$id'";
    $result = $conn->query($sqlCheck);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if ($row['name'] != $name || $row['bpay'] != $basicPay || $row['wdays'] != $workingDays) {
            $sqlUpdate = "UPDATE employees 
                          SET name='$name', bpay='$basicPay', wdays='$workingDays', ipercent='$incrementPercentage', totalpay='$totalpay' 
                          WHERE id='$id'";
            if ($conn->query($sqlUpdate) === TRUE) {
                $message = "Data updated successfully.";
            } else {
                $message = "Error updating record: " . $conn->error;
            }
        } else {
            $message = "Data Already Present.";
        }
    } else {
        $sqlInsert = "INSERT INTO employees (id, name, bpay, wdays, ipercent, totalpay)
                      VALUES ('$id', '$name', '$basicPay', '$workingDays', '$incrementPercentage', '$totalpay')";
        if ($conn->query($sqlInsert) === TRUE) {
            $message = "New record created successfully.";
        } else {
            $message = "Error: " . $sqlInsert . "<br>" . $conn->error;
        }
    }

    $sqlDisplay = "SELECT * FROM employees WHERE id = '$id'";
    $displayResult = $conn->query($sqlDisplay);

    echo"<!DOCTYPE html>
<html>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Employee Records</title>
    <style>
        body 
        {
            background-color: #1a1a1a;
            color: #f1f1f1;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
        }

        h3 
        {
            text-align: center;
        }

        table 
        {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td 
        {
            padding: 10px;
            text-align: left;
            border: 1px solid #444;
        }

        th 
        {
            background-color: #333;
            color: #ffffff;
        }

        tr:nth-child(even) 
        {
            background-color: #2a2a2a;
        }

        tr:hover {
            background-color: #444;
        }

        .sidebar 
        {
            height: 100%;
            width: 250px;
            position: fixed;
            top: 0;
            left: -300px;
            background-color: #333;
            transition: 0.3s;
            padding: 20px;
            box-shadow: 2px 0 5px rgba(0,0,0,0.5);
            z-index: 1;
        }

        .sidebar h2 {
            color: white;
            text-align: center;
        }

        .sidebar a {
            background-color: grey;
            color: white;
            border: none;
            padding: 10px;
            text-align: center;
            text-decoration: none;
            display: block;
            font-size: 16px;
            margin: 10px 0;
            cursor: pointer;
            border-radius: 5px;
        }

        .sidebar a:hover 
        {
            background-color: black;
        }

        .open-btn 
        {
            position: absolute;
            top: 20px;
            left: 20px;
            background-color: grey;
            color: white;
            border: none;
            padding: 10px;
            cursor: pointer;
            border-radius: 5px;
        }

        .dashboard-button 
        {
            position: absolute;
            top: 20px;
            right: 20px;
            padding: 10px;
            border-radius: 5px;
        }

        .dashboard-button button 
        {
            
            border: none;
            text-color:black;
            color: black;
            padding: 10px 20px;
            text-decoration: none;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
        }
    </style>
    <script>
        function toggleSidebar() 
        {
            var sidebar = document.getElementById('sidebar');
            if (sidebar.style.left === '0px') 
            {
                sidebar.style.left = '-300px';
            } 
            else 
            {
                sidebar.style.left = '0px';
            }
        }
    </script>
</head>
<body>
    <button class='open-btn' onclick='toggleSidebar()'>Open Sidebar</button>

    <div class='sidebar' id='sidebar'>
        <button onclick='toggleSidebar()' style='background-color: red; margin-bottom: 20px;'>Close Sidebar</button>
        <h2>Sidebar Menu</h2>
        <a href='display.php'>Display Employee Data</a>
        <a href='delete.html'>Delete Employee Data</a>
        <a href='index.html'>New Data Entry</a>
    </div>
";

    echo "<h3>$message</h3>";

    if ($displayResult->num_rows > 0) {
        $data = $displayResult->fetch_assoc();
        echo "<h3>Newly Entered/Updated Employee Details:</h3>";
        echo "<table>";
        echo "<tr><th>ID</th><th>Name</th><th>Basic Pay</th><th>Working Days</th><th>Increment Percentage</th><th>Total Pay</th></tr>";
        echo "<tr>";
        echo "<td>" . $data['id'] . "</td>";
        echo "<td>" . $data['name'] . "</td>";
        echo "<td>" . $data['bpay'] . "</td>";
        echo "<td>" . $data['wdays'] . "</td>";
        echo "<td>" . $data['ipercent'] . "</td>";
        echo "<td>" . $data['totalpay'] . "</td>";
        echo "</tr>";
        echo "</table>";
    } else {
        echo "<p>Error fetching data.</p>";
    }

    echo "<div class='dashboard-button'>
            <button><a href='index.html' style='color: white; text-decoration: none;'>Dashboard</a></button>
          </div>";

    echo "</body></html>";
}
?>
