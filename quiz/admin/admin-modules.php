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

// Fetch modules from the database
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
    <link rel="stylesheet" href="css/admin-dash.css">
    <link rel="stylesheet" href="css/admin-sidebar.css">
    <script>
        function toggleEdit(row, moduleId) {
            let inputs = document.querySelectorAll(`#row-${row} input`);
            let editButton = document.querySelector(`#edit-btn-${row}`);

            if (!inputs[0].disabled) {
                let updatedName = inputs[0].value;
                let updatedUrl = inputs[1].value;

                fetch('admin-modules/update_module.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: `id=${moduleId}&module_name=${encodeURIComponent(updatedName)}&url=${encodeURIComponent(updatedUrl)}`
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Module updated successfully!');
                        location.reload();
                    } else {
                        alert('Failed to update module.');
                    }
                });
            }
            inputs.forEach(input => input.disabled = !input.disabled);
            editButton.textContent = inputs[0].disabled ? 'Edit' : 'Save';
        }

        function addModule() {
            let name = document.querySelector("#new-module-name").value;
            let url = document.querySelector("#new-module-url").value;

            if (!name || !url) {
                alert("Please fill in both fields.");
                return;
            }

            fetch('admin-modules/add_module.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `module_name=${encodeURIComponent(name)}&url=${encodeURIComponent(url)}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Module added successfully!');
                    location.reload();
                } else {
                    alert('Failed to add module.');
                }
            });
        }

        function deleteModule(moduleId) {
            if (confirm('Are you sure you want to delete this module?')) {
                fetch('admin-modules/delete_module.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: `id=${moduleId}`
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Module deleted successfully!');
                        location.reload();
                    } else {
                        alert('Failed to delete module.');
                    }
                });
            }
        }
    </script>
</head>
<body>
    <?php include_once 'admin-sidebar.php'; ?>
    <div class="header" style="background-color: #004d40;">Modules</div>
    <div class="main-content justify-content-center">
        <div class="card" style="width: 95rem; height: auto;">
            <div class="card-body">
                <h5>Add New Module</h5>
                <div class="form-inline">
                    <input type="text" id="new-module-name" class="form-control mr-2" placeholder="Module Name">
                    <input type="text" id="new-module-url" class="form-control mr-2" placeholder="Module URL">
                    <button class="btn btn-success" onclick="addModule()">Add Module</button>
                </div>
                <br>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Module Name</th>
                            <th>URL</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($module = $result->fetch_assoc()) { ?>
                        <tr id="row-<?= $module['id'] ?>">
                            <td><input type="text" value="<?= $module['module_name'] ?>" disabled></td>
                            <td><input type="text" value="<?= $module['url'] ?>" disabled></td>
                            <td>
                                <button id="edit-btn-<?= $module['id'] ?>" class="btn btn-primary" 
                                        onclick="toggleEdit(<?= $module['id'] ?>, <?= $module['id'] ?>)">Edit</button>
                                <button class="btn btn-danger" 
                                        onclick="deleteModule(<?= $module['id'] ?>)">Delete</button>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
