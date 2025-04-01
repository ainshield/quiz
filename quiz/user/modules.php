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
    <script>
        
        document.addEventListener("DOMContentLoaded", function() {
            document.getElementById("moduleModal").style.display = "none";
        });

        function viewModule(moduleName, content) {
            let formattedContent = content
                .replace(/\n/g, "<br>")   // Replace newlines with <br> for proper display
                .replace(/\t/g, "&nbsp;&nbsp;&nbsp;&nbsp;"); // Replace tab characters with spaces

            document.getElementById("modalTitle").innerText = moduleName; // Set title
            document.getElementById("modalContent").innerHTML = formattedContent; // Inject formatted content
            document.getElementById("moduleModal").style.display = "flex";
        }

        function closeModal() {
            document.getElementById("moduleModal").style.display = "none";
        }
    </script>

    <style>
        /* Modal styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 9999; /* Higher than table headers */
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .modal-content {
            background-color: white;
            width: 80vw; /* Adjust width */
            height: 80vh; /* Adjust height */
            padding: 20px;
            border-radius: 8px;
            overflow: auto;
            display: flex;
            flex-direction: column;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.2); /* Optional for better visibility */
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #ddd;
            padding-bottom: 10px;
        }

        .modal-body {
            flex: 1;
            overflow-y: auto; /* Enables scrolling if content overflows */
            padding: 10px;
            text-align: left;
            white-space: pre-wrap; /* Ensures newlines and spaces are preserved */
        }
        .close {
            font-size: 24px;
            cursor: pointer;
            background: none;
            border: none;
        }
    </style>

</head>
<body>
    <?php include_once 'sidebar.php'; ?>
    <div class="header" style="background-color: #004d40;">Modules</div>
    <div class="main-content justify-content-center">
        <div class="card" style="width: auto; height: auto;">
            <div class="card-body">
            <div class="table-responsive" style="max-height: 75vh; overflow-y: auto;">    
                <table class="table table-bordered">
                        <thead style="position: sticky; top: 0; background-color: white; z-index: 2;">
                            <tr>
                                <th>Module Name</th>
                                <th>View</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($module = $result->fetch_assoc()) { ?>
                            <tr>
                                <td><?= htmlspecialchars($module['module_name']) ?></td>
                                <td>
                                    <button class="btn btn-info" 
                                        onclick="viewModule('<?= htmlspecialchars($module['module_name'], ENT_QUOTES) ?>', `<?= htmlspecialchars($module['content'], ENT_QUOTES) ?>`)">
                                        View
                                    </button>
                                </td>
                            </tr>
                            <?php } ?>
                        </tbody>
                </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div id="moduleModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h4 id="modalTitle">Module Title</h4> <!-- Title will be set dynamically -->
                <button class="close" onclick="closeModal()">&times;</button>
            </div>
            <div class="modal-body" id="modalContent">
                <!-- Content will be injected here -->
            </div>
        </div>
    </div>
</body>
</html>
