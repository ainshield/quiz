<?php
session_start();

$host = "localhost";
$user = "root";
$password = "";
$dbname = "quiz_db";

$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$query = "SELECT * FROM modules";
$result = $conn->query($query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard</title>
    <?php include_once '../vendor/bootstrap.php'; ?>
    <link rel="stylesheet" href="css/dash.css">
    <link rel="stylesheet" href="css/sidebar.css">
    <link rel="stylesheet" href="css/user-module-sidebar.css">
    <script>
        function viewModule(moduleName, content, imageFileName) {
            let formattedContent = content
                .replace(/\n/g, "<br>")   
                .replace(/\t/g, "&nbsp;&nbsp;&nbsp;&nbsp;");

            let imageUrl = imageFileName ? `../uploads/${imageFileName}` : "";
            let imageHtml = imageFileName ? `<img src="${imageUrl}" alt="Module Image" style="max-width: 100%; margin-top: 10px;">` : "";

            document.getElementById("moduleTitle").innerText = moduleName; 
            document.getElementById("moduleContent").innerHTML = formattedContent + "<br>" + imageHtml;  
        }
    </script>

    <style>
        .main-content {
            width: 75%;
            float: left;
            padding: 20px;
        }

        .card {
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.2);
        }
    </style>
</head>
<body>
    <?php include_once 'sidebar.php'; ?>
    <div class="header" style="background-color: #004d40;">Modules</div>

    <div class="module-sidebar">
        <ul class="menu">
            <?php while ($module = $result->fetch_assoc()) { ?>
                <li class="module-item" 
                    onclick="viewModule(
                        '<?= htmlspecialchars($module['module_name'], ENT_QUOTES) ?>', 
                        `<?= htmlspecialchars($module['content'], ENT_QUOTES) ?>`,
                        '<?= isset($module['image_url']) ? basename($module['image_url']) : '' ?>'
                    )">
                    <?= htmlspecialchars($module['module_name'], ENT_QUOTES) ?>
                </li>
            <?php } ?>
        </ul>
    </div>
    
    <div class="main-content">
        <div class="card" style="left: 27vh; width: 140vh; height: 85vh;">
            <h3 id="moduleTitle">Select a Module</h3>
            <div id="moduleContent" style="padding: 20px; overflow-y: auto;">Click a module from the sidebar to view its content.</div>
        </div>
    </div>
</body>
</html>
