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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="css/admin-dash.css">
    <link rel="stylesheet" href="css/admin-sidebar.css">

    <style>
        .truncate-text {
            max-width: 300px; /* Adjust width as needed */
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        /* Custom modal size */
        .modal-dialog.custom-modal {
            width: 60vw; /* 60% of the screen width */
            max-width: none; /* Override Bootstrap's max-width limit */
            height: auto; /* 70% of the screen height */
        }
        
        /* Make sure the modal content takes up full height */
        .modal-content {
            height: auto;
        }
        
        /* Ensure the modal body is scrollable if needed */
        .modal-body {
            flex-grow: 1;
            overflow-y: auto;
            height: auto;
        }

        /* Increase textarea size for better usability */
        .modal-body textarea {
            height: 70vh; /* Increase textarea height */
        }
    </style>
</head>
<body>
    <?php include_once 'admin-sidebar.php'; ?>
    <div class="header" style="background-color: #004d40;">Modules</div>
    <div class="main-content justify-content-center">
        <div class="card" style="width: auto; height: auto;">
            <div class="card-body">
                <h5>Modules</h5>
                <button class="btn btn-success" data-toggle="modal" data-target="#addModuleModal">Add New Module</button>
                <br><br>
                <div class="table-responsive" style="max-height: 65vh; overflow-y: auto;">
                    <table class="table table-bordered">
                        <thead style="position: sticky; top: 0; background-color: white; z-index: 2;">
                            <tr>
                                <th>Module Name</th>
                                <th style="width: 300px;">Content</th> <!-- Adjust width as needed -->
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($module = $result->fetch_assoc()) { ?>
                            <tr>
                                <td><?= $module['module_name'] ?></td>
                                <td class="truncate-text"><?= $module['content'] ?></td>
                                <td>
                                    <button class="btn btn-primary" onclick='openEditModal(<?= $module['id'] ?>, <?= json_encode($module['module_name']) ?>, <?= json_encode($module['content']) ?>)'>Edit</button>
                                    <button class="btn btn-danger" onclick="deleteModule(<?= $module['id'] ?>)">Delete</button>
                                </td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Module Modal -->
    <div class="modal fade" id="addModuleModal" tabindex="-1" role="dialog">
        <div class="modal-dialog custom-modal" role="document"> 
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Module</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <input type="text" id="new-module-name" class="form-control" placeholder="Module Name">
                    <textarea id="new-module-content" class="form-control" placeholder="Module Content" ></textarea>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-success" onclick="addModule()">Add</button>
                    <button class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Module Modal -->
    <div class="modal fade" id="editModuleModal" tabindex="-1" role="dialog">
        <div class="modal-dialog custom-modal" role="document"> 
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Module</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="edit-module-id">
                    <input type="text" id="edit-module-name" class="form-control">
                    <textarea id="edit-module-content" class="form-control" ></textarea>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary" onclick="saveModule()">Save</button>
                    <button class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Function to handle tab key in textarea
        document.addEventListener("DOMContentLoaded", function () {
            document.querySelectorAll("textarea").forEach(textarea => {
                textarea.addEventListener("keydown", function (event) {
                    if (event.key === "Tab") {
                        event.preventDefault(); // Prevent default tabbing behavior
                        
                        let start = this.selectionStart;
                        let end = this.selectionEnd;

                        // Insert a tab character at the cursor position
                        this.value = this.value.substring(0, start) + "\t" + this.value.substring(end);

                        // Move the cursor after the inserted tab
                        this.selectionStart = this.selectionEnd = start + 1;
                    }
                });
            });
        });
        
        function addModule() {
            let name = document.getElementById('new-module-name').value;
            let content = document.getElementById('new-module-content').value;

            fetch('admin-modules/add_module.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `module_name=${encodeURIComponent(name)}&content=${encodeURIComponent(content)}`
            }).then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Module added successfully!');
                    location.reload();
                } else {
                    alert('Failed to add module.');
                }
            });
        }

        function openEditModal(id, name, content) {
            document.getElementById('edit-module-id').value = id;
            document.getElementById('edit-module-name').value = name;
            document.getElementById('edit-module-content').value = content.replace(/\\n/g, "\n").replace(/\\t/g, "\t");

            $('#editModuleModal').modal('show');
        }

        function saveModule() {
            let id = document.getElementById('edit-module-id').value;
            let name = document.getElementById('edit-module-name').value;
            let content = document.getElementById('edit-module-content').value;

            fetch('admin-modules/update_module.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `id=${id}&module_name=${encodeURIComponent(name)}&content=${encodeURIComponent(content)}`
            }).then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Module updated successfully!');
                    location.reload();
                } else {
                    alert('Failed to update module.');
                }
            });
        }

        function deleteModule(id) {
            if (confirm('Are you sure you want to delete this module?')) {
                fetch('admin-modules/delete_module.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: `id=${id}`
                }).then(response => response.json())
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
</body>
</html>