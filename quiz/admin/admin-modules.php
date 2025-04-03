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
    <link rel="stylesheet" href="css/module-sidebar.css">

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

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            // Ensure "Add Module" button works
            const addButton = document.getElementById("add-module-btn");
            if (addButton) {
                addButton.addEventListener("click", function () {
                    showModuleForm();
                });
            }
        });
        
        function cancelForm() {
            const cardBody = document.getElementById("module-card-body");
            cardBody.innerHTML = ""; // Clear the form
        }

        function clearImage() {
            const imageInput = document.getElementById("module-image");
            if (imageInput) {
                imageInput.value = ""; // Clear the file input
            }
        }

        // Function to dynamically show the module form inside the card
        function showModuleForm(moduleId = '', moduleName = '', moduleContent = '', imageFileName = '') {
            const cardBody = document.getElementById("module-card-body");

            // Generate the correct image URL if available
            let imageUrl = imageFileName ? `http://localhost/quiz/uploads/${imageFileName}` : '';

            cardBody.innerHTML = `
                <h5>${moduleId ? 'Edit Module' : 'Add New Module'}</h5>
                <input type="hidden" id="module-id" value="${moduleId}">
                <input type="text" id="module-name" class="form-control mt-2" placeholder="Module Name" value="${moduleName}">
                <textarea id="module-content" class="form-control mt-2" placeholder="Module Content" style="max-height: 50vh; min-height: 50vh;">${moduleContent}</textarea>
                
                <label class="mt-2">Upload Image:</label>

                ${imageUrl ? `<button class="btn btn-info mt-2" onclick="viewImage(${imageUrl})">View Image</button>` : ''}

                <div class="d-flex align-items-center mt-2">
                    <input type="file" id="module-image" class="form-control mt-2">
                    <button class="btn btn-warning mt-2" onclick="clearImage()">Clear Image</button>
                </div>
                
                <button class="btn btn-success mt-3" onclick="saveModule()">
                    ${moduleId ? 'Save Changes' : 'Add Module'}
                </button>
                <button class="btn btn-secondary mt-3" onclick="cancelForm()">Cancel</button>

                ${moduleId ? `<button class="btn btn-danger mt-3" onclick="deleteModule(${moduleId})">Delete Module</button>` : ''}
            `;
        }

        let currentImageUrl = showModuleForm.imageHtml || '';

        function viewImage(imageUrl) {
            // Check if the imageUrl exists
            const modalBody = document.querySelector("#viewImageModal .modal-body");

            // If an image URL exists, display the image
            if (imageUrl) {
                modalBody.innerHTML = `<img src="${imageUrl}" alt="Module Image" style="max-width: 100%;">`;
            } else {
                modalBody.innerHTML = "No image available.";
            }
            
            // Show the modal
            $('#viewImageModal').modal('show');
        }

        // Function to handle add/edit module
        function saveModule() {
            let id = document.getElementById("module-id").value;
            let name = document.getElementById("module-name").value.trim();
            let content = document.getElementById("module-content").value.trim();
            let image = document.getElementById("module-image").files[0];

            if (!name) {
                alert("Module name cannot be empty.");
                return;
            }

            let formData = new FormData();
            formData.append("module_name", name);
            formData.append("content", content);
            if (image) {
                formData.append("image", image);
            }
            if (id) {
                formData.append("id", id);
            }

            let url = id ? 'admin-modules/update_module.php' : 'admin-modules/add_module.php';

            fetch(url, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(id ? 'Module updated successfully!' : 'Module added successfully!');
                    location.reload();
                } else {
                    alert('Failed to process request.');
                }
            });
        }

        // Function to open edit mode
        function editModule(id, name, content, imageUrl) {
            // Decode HTML entities
            const tempElement = document.createElement("textarea");
            tempElement.innerHTML = content;
            const decodedContent = tempElement.value;

            showModuleForm(id, name, decodedContent, imageUrl);
        }

        function deleteModule(id) {
            if (!confirm("Are you sure you want to delete this module?")) {
                return;
            }

            fetch('admin-modules/delete_module.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: `id=${id}`
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

    </script>


</head>
<body>
    <?php include_once 'admin-sidebar.php'; ?>
    <div class="header" style="background-color: #004d40;">Modules</div>
    <div class="flex">
    <div class="container-flex">

        <div class="module-sidebar">
            <!-- Add Module Button - Fixed at the Top -->
            <div class="add-module-container">
                <button id="add-module-btn" class="btn btn-success">Add New Module</button>
            </div>

            <!-- Scrollable List -->
            <ul class="menu">
                <?php while ($module = $result->fetch_assoc()) { ?>
                    <li class="module-item" 
                        onclick='editModule(<?= json_encode($module['id']) ?>, <?= json_encode($module['module_name']) ?>, <?= json_encode($module['content']) ?>, <?= json_encode($module['image_url'] ?? '') ?>)'>
                        <?= htmlspecialchars($module['module_name'], ENT_QUOTES) ?>
                    </li>
                <?php } ?>
            </ul>
        </div>

        <div class="main-content">
            <div class="card" style="left: 27vh; width: 140vh; height: 85vh;">
                <div class="card-body" id="module-card-body">
                    <h5>Module Details</h5>
                    <p>Select a module from the left to view or edit its details.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- View Image Modal -->
    <div class="modal fade" id="viewImageModal" tabindex="-1" role="dialog" aria-labelledby="viewImageModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewImageModalLabel">View Module Image</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Module Modal -->
    <!-- <div class="modal fade" id="addModuleModal" tabindex="-1" role="dialog">
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
    </div> -->

    <!-- Edit Module Modal -->
    <!-- <div class="modal fade" id="editModuleModal" tabindex="-1" role="dialog">
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
    </div> -->

    
</body>
</html>