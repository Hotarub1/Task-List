<?php

use Services\TaskListRepository;
use Services\TaskRepository;

require_once 'config.php';

$taskListRepo = new TaskListRepository($pdo);
$taskLists = $taskListRepo->fetchAll();
// var_dump(json_encode($taskLists));

$tasksRepo = new TaskRepository($pdo);

?>
<!DOCTYPE html>
<html lang="en">

<head> 
    <meta charset="UTF-8"> 
    <title>Tasklist</title> 
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <div class="big-one">
        <div class="left-column">
            <div class="intro">
                <h1>PHP + Nginx + MySQL</h1>
                <p> <strong>PHP Version:</strong> <?= PHP_VERSION ?></p>
                <p class="status"><strong>Database:</strong> <?= htmlspecialchars($dbStatus) ?></p> 

                <!-- Display Message -->
                <?php if (!empty($message)): ?>
                    <p class="status"><?= htmlspecialchars($message) ?></p> 
                <?php endif; ?>
            </div>

            <div class="forms">
                <!-- HTML Form to Create Tasklist -->
                <?php include 'components/forms/create-tasklist.php'; ?>

                <!-- HTML Form to Create Task -->
                <?php include 'components/forms/create-task.php'; ?>
            </div>
        </div> 

        <div class="tasklists-div">
            <!-- Display Existing Tasklists -->
            <?php include 'components/lists/display-tasklists.php'; ?>
        </div>
    </div>
    
    <script src="tasklist.js"> </script>

    <script> 
        document.addEventListener("DOMContentLoaded", () => init());
        function init() {
            const Status = {
                Error: 'error',
                Success: 'success'
            };

            const create_taskList_form = document.getElementById("create_tasklist");
            const create_task_form = document.getElementById("create_task");
            const container = document.getElementById("tasklists-container");
            
            create_taskList_form.addEventListener("submit", async function (event) {
                event.preventDefault();
                const title = create_taskList_form.querySelector('input[name="title"]').value;
                console.log(title);
                const description = create_taskList_form.querySelector('textarea[name="description"]').value;
                console.log(description);
                const url = "/api/create_tasklist.php";
                const method = "POST";
                const payload = {
                    title: title,
                    description: description
                };

                try {
                    console.log(JSON.stringify(payload));
                    const data = await sendRequest("/api/create_tasklist.php", payload);
                    console.log(data);
                    if (data.status === Status.Success) {
                        addTaskList(data.data.id, data.data.title, data.data.description);

                        // DYNAMICALLY UPDATE THE DROPDOWN SELECTOR
                        const dropdown = document.getElementById("taskList");
                        if (dropdown) {
                            // Create a new <option value="NEW_ID">NEW_TITLE</option>
                            const newOption = document.createElement("option");
                            newOption.value = data.data.id; // The database ID returned by PHP
                            newOption.textContent = data.data.title; // The clean title text
                            
                            // Append it to the dropdown list instantly
                            dropdown.appendChild(newOption);
                        } else {
                            showTaskForm(data.data.id, data.data.title);
                        }

                        create_taskList_form.reset();
                    }
                } catch (error) {
                    console.error(error);
                }
            });

            create_task_form.addEventListener("submit", async function (event) {
                event.preventDefault();
                const taskListID = create_task_form.querySelector('select[name="taskList"]').value;
                console.log(taskListID);
                const title = create_task_form.querySelector('input[name="title"]').value;
                console.log(title);
                const description = create_task_form.querySelector('textarea[name="description"]').value;
                console.log(description);
                const url = "/api/create_task.php";
                const method = "POST";
                const payload = {
                    taskListID: taskListID,
                    title: title,
                    description: description
                };

                try {
                    console.log(JSON.stringify(payload));
                    const data = await sendRequest("/api/create_task.php", payload);
                    console.log(data);
                    if (data.status === Status.Success) {
                        addTask(taskListID, data.data.title, data.data.description);
                        create_task_form.reset();
                    }
                } catch (error) {
                    console.error(error);
                }
            });

            // Helper function to eliminate repetitive fetch code
            async function sendRequest(url, payload) {
                const response = await fetch(url, {
                    method: "POST",
                    headers: { "Content-Type": "application/json" },
                    body: JSON.stringify(payload)
                });
                
                if (!response.ok) throw new Error("HTTP error " + response.status);
                return await response.json();
            }
        }
    </script>
</body>

</html>