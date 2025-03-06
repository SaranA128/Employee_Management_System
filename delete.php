<?php
session_start();

$servername = "localhost";
$username = "root";
$password = ""; 
$dbname = "employee";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST['delete_id'])) {
    $id = intval($_POST['delete_id']);
    $sql = "DELETE FROM employees WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $id);
    
    if ($stmt->execute()) {
        $_SESSION['message'] = "Record with ID $id Deleted Successfully"; 
    } else {
        $_SESSION['message'] = "Error Deleting Record with ID $id: " . $conn->error;
    }
    $stmt->close();

    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

$sql = "SELECT * FROM employees";
$result = $conn->query($sql);

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

        h1, h3 {
            text-align: center;
        }

        table {
            width: 80%;
            border-collapse: collapse;
            margin: 20px auto;
            background-color: #333;
            color: #fff;
        }

        th, td {
            padding: 10px;
            text-align: left; 
            border: 1px solid #444;
        }

        th {
            background-color: #555;
        }

        tr:nth-child(even) {
            background-color: #444;
        }

        tr:hover {
            background-color: #666;
        }

        .popup {
            display: none; 
            position: fixed; 
            left: 50%; 
            top: 50%; 
            transform: translate(-50%, -50%); 
            background-color: #444; 
            color: white;
            padding: 20px; 
            border-radius: 8px; 
            z-index: 1000;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.5);
        }

        .overlay {
            display: none; 
            position: fixed; 
            left: 0;
            top: 0;
            width: 100%; 
            height: 100%; 
            background-color: rgba(0, 0, 0, 0.7); 
            z-index: 999; 
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

        .dashboard-button {
            position: absolute;
            top: 20px;
            right: 20px;
            background-color:darkgrey;
        }
    </style>
    <script>
        function showPopup(message) {
            var popup = document.getElementById('popup');
            var overlay = document.getElementById('overlay');
            popup.innerText = message;
            popup.style.display = 'block';
            overlay.style.display = 'block';

            setTimeout(function() {
                popup.style.display = 'none';
                overlay.style.display = 'none';
            }, 5000);
        }

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
    </div>

    <div>
        <button > <a href='index.html' class='dashboard-button' style='color: white; text-decoration: none;'>Dashboard</a></button>
    </div>

    <center><h1>Employee Records</h1></center>";

if (isset($_SESSION['message'])) {
    echo "<script>document.addEventListener('DOMContentLoaded', function() {
        showPopup('" . $_SESSION['message'] . "');
    });</script>";
    unset($_SESSION['message']); //Clear PopUp
}

if ($result->num_rows > 0) {
    echo "<table>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Basic Pay</th>
                <th>Working Days</th>
                <th>Increment Amount</th>
                <th>Total Pay</th>
                <th>Action</th>
            </tr>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>" . $row["id"] . "</td>
                <td>" . $row["name"] . "</td>
                <td>" . $row["bpay"] . "</td>
                <td>" . $row["wdays"] . "</td>
                <td>" . $row["ipercent"] . "</td>
                <td>" . $row["totalpay"] . "</td>
                <td>
                    <form method='POST' style='display:inline;'>
                        <input type='hidden' name='delete_id' value='" . $row["id"] . "'>
                        <button type='submit' style='background-color: red; color: white; border: none; padding: 5px;'>Delete</button>
                    </form>
                </td>
              </tr>";
    }
    echo "</table>";
} else {
    echo "<center><h3>No Employee Records Found</h3></center>";
}

$conn->close();

echo "<div id='overlay' class='overlay'></div>
      <div id='popup' class='popup'></div>";

echo "</body></html>";
?>
