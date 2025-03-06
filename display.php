<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "employee";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sqlDisplay = "SELECT * FROM employees";
$displayResult = $conn->query($sqlDisplay);

echo "<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Employee Records</title>
    <style>
        body {
            background-color: #1a1a1a;
            color: #f1f1f1;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
        }

        h3 {
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 10px;
            text-align: left;
            border: 1px solid #444;
        }

        th {
            background-color: #333;
            color: #ffffff;
        }

        tr:nth-child(even) {
            background-color: #2a2a2a;
        }

        tr:hover {
            background-color: #444;
        }

        .dashboard-button {
            position: absolute;
            top: 20px;
            right: 20px;
            background-color: grey;
            padding: 10px;
            border-radius: 5px;
        }

        .dashboard-button button {
            background-color: #007bff;
            border: none;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
        }

        .sidebar {
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

        .sidebar a:hover {
            background-color: black;
        }

        .open-btn {
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
    </style>
    <script>
        function toggleSidebar() {
            var sidebar = document.getElementById('sidebar');
            if (sidebar.style.left === '0px') {
                sidebar.style.left = '-300px';
            } else {
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
    </div>";

if ($displayResult->num_rows > 0) {
    echo "<h3>Employee Details:</h3>";
    echo "<table>";
    echo "<tr><th>ID</th><th>Name</th><th>Basic Pay</th><th>Working Days</th><th>Increment Percentage</th><th>Total Pay</th></tr>";

    while ($data = $displayResult->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $data['id'] . "</td>";
        echo "<td>" . $data['name'] . "</td>";
        echo "<td>" . $data['bpay'] . "</td>";
        echo "<td>" . $data['wdays'] . "</td>";
        echo "<td>" . $data['ipercent'] . "</td>";
        echo "<td>" . $data['totalpay'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "No data found.";
}

echo "<div>
        <button ><a href='index.html' class='dashboard-button' style='color: white; text-decoration: none;'>Dashboard</a></button>
      </div>";

echo "</body></html>";

$conn->close();
?>
