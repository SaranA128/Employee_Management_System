<?php
$servername = "localhost"; 
$username = "root"; 
$password = "";  
$dbname = "employee";  

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) 
{
    die("Connection failed: " . $conn->connect_error);
}

echo"<!DOCTYPE html>
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

        tr:nth-child(even) 
        {
            background-color: #2a2a2a;
        }

        tr:hover {
            background-color: #444;
        }
    </style>
</head>
<body>";

if (isset($_GET['id']) && !empty($_GET['id'])) 
{
    $id = htmlspecialchars($_GET['id']);
    echo "<center><h1>Data for Employee ID: " . $id . "</h1>";

    $stmt = $conn->prepare("SELECT * FROM employees WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    echo "<h3>Employee Details:</h3>";

    if ($result && $result->num_rows > 0) 
    {
        $data = $result->fetch_assoc();
        echo "<table>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Basic Pay</th>
                    <th>Working Days</th>
                    <th>Increment Percentage</th>
                    <th>Total Pay</th>
                </tr>
                <tr>
                    <td>" . $data['id'] . "</td>
                    <td>" . $data['name'] . "</td>
                    <td>" . $data['bpay'] . "</td>
                    <td>" . $data['wdays'] . "</td>
                    <td>" . $data['ipercent'] . "</td>
                    <td>" . $data['totalpay'] . "</td>
                </tr>
              </table>";
    } 
    else 
    {
        echo "No results found.";
    }

    $stmt->close();
} 
else 
{
    echo "No ID provided.";
}

$conn->close();
echo "</center></body></html>";
?>