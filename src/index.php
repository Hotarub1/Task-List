<?php

require_once 'config.php';

// Fetch all tasklists      
$stmt1 = $pdo->prepare('SELECT * FROM taskLists');
$stmt1->execute();
$taskLists = $stmt1->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create_task'])) {
    $taskListID = trim($_POST['taskList'] ?? '');
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');

    if (empty($taskListID)) {
        $message = '❌ Task List is required.';
    } elseif (empty($title)) {
        $message = '❌ Title is required.';
    } elseif (isset($pdo)) {
        try {
            // Prepare an SQL statement to prevent SQL injection
            $stmt = $pdo->prepare("INSERT INTO tasks (task_list_id, title, description) 
                VALUES (:task_list_id, :title, :description)");
            $stmt->execute([
                'task_list_id' => $taskListID,
                'title' => $title,
                'description' => $description
            ]);
            $_SESSION['flash_message'] = '✅ Tasklist created successfully!';
            header('Location: /?success=1');
            exit;
        } catch (PDOException $e) {
            $message = '❌ Error saving to database: ' . $e->getMessage();
        }
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Tasklist</title>
    <style>
        body {
            font-family: sans-serif;
            max-width: 600px;
            margin: 4rem auto;
        }

        .status {
            padding: .75rem 1rem;
            border-radius: 4px;
            background: #f0f4ff;
        }

        .tasklist {
            padding: .25rem;
        }

        li.tasks-container-wrapper {
            list-style-type: none;
        }

        li.no-tasks {
            list-style-type: none;
        }
    </style>
</head>

<body>
    <h1>🐘 PHP + Nginx + MySQL</h1>
    <p> <strong>PHP Version:</strong> <?= PHP_VERSION ?></p>
    <p class="status"><strong>Database:</strong> <?= htmlspecialchars($dbStatus) ?></p>

    <!-- Display Message -->
    <?php if (!empty($message)): ?>
        <p class="status"><?= htmlspecialchars($message) ?></p> 
    <?php endif; ?>

    <!-- HTML Form to Create Tasklist -->
    <h2>Create New Tasklist</h2>
    <form id="create_tasklist">
        <div class="form-group">
            <label for="title">Title *</label>
            <input type="text" id="title" name="title" required maxlength="255">
        </div>
        <div class="form-group">
            <label for="description">Description</label>
            <textarea id="description" name="description" rows="3"></textarea>
        </div>
        <button type="submit" name="create_list">Create List</button>
    </form>

    <!-- HTML Form to Create Task -->
    <h2>Create New Task</h2>
    <form method="POST" action="">
        <div class="form-group">
        <label for="taskList">Task-List: *</label>
            <?php if (isset($pdo)): ?>
                <select id="taskList" name="taskList" required>
                    <?php foreach ($taskLists as $taskList): ?>
                        <option value="<?= htmlspecialchars($taskList['id']) ?>"><?= htmlspecialchars($taskList['title']) ?></option>
                    <?php endforeach; ?>
                </select>
            <?php endif ?>
        </div>
        <div class="form-group">
            <label for="title">Title *</label>
            <input type="text" id="title" name="title" required maxlength="255">
        </div>
        <div class="form-group">
            <label for="description">Description</label>
            <textarea id="description" name="description" rows="3"></textarea>
        </div>
        <button type="submit" name="create_task">Create Task</button>
    </form>

    <!-- Display Existing Tasklists -->
    <h2>Existing Tasklists</h2>
    <ul id="tasklists-container">
        <?php if (isset($pdo)): ?>
            <?php foreach ($taskLists as $taskList): 
                $listText = empty($taskList['description']) ?
                    $taskList['title'] :
                    $taskList['title'] . ' - ' . $taskList['description'];

                // Fetch the tasks for this specific list
                $stmt2 = $pdo->prepare('SELECT * FROM tasks WHERE task_list_id = :list_id');
                $stmt2->execute(['list_id' => $taskList['id']]);
                $tasks = $stmt2->fetchAll(PDO::FETCH_ASSOC);
            ?>
                <li>
                    <?= htmlspecialchars($listText) ?>
                </li>

                <li class="tasks-container-wrapper">
                    <ul>
                        <?php if ($tasks): ?>
                            <?php foreach ($tasks as $task): 
                                $taskText = empty($task['description']) ?
                                    $task['title'] :
                                    $task['title'] . ' - ' . $task['description'];
                            ?>
                                <li class="tasklist"><?= htmlspecialchars($taskText) ?></li>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <li class="no-tasks">- No tasks found</li>
                        <?php endif; ?>
                    </ul>
                </li>
            <?php endforeach; ?>
        <?php endif; ?>
    </ul>

    <?php if (empty($taskLists)): ?>
        <p id="no-lists">- No lists found </p>
    <?php endif; ?>
    
    <script src="tasklist.js"> </script>

    <script> 
        document.addEventListener("DOMContentLoaded", () => init());
        function init() {
            const Status = {
                Error: 'error',
                Success: 'success'
            };

            const create_form = document.getElementById("create_tasklist");
            const container = document.getElementById("tasklists-container");
            
            create_form.addEventListener("submit", async function (event) {
                event.preventDefault();
                const title = create_form.querySelector('input[name="title"]').value;
                console.log(title);
                const description = create_form.querySelector('textarea[name="description"]').value;
                console.log(description);
                const url = "/api/create_tasklist.php";
                const method = "POST";
                const payload = {
                    title: title,
                    description: description
                };

                try {
                    console.log(JSON.stringify(payload));
                    const response = await fetch(url, {
                        method: method,
                        headers: {
                            "Content-Type": "application/json",
                        },
                        body: JSON.stringify(payload)
                    });
                    
                    if (!response.ok) {
                        throw new Error("http error");
                    }

                    const data = await response.json();
                    console.log(data);
                    if (data.status === Status.Success) {
                        addTaskList(data.data.title, data.data.description);

                        // DYNAMICALLY UPDATE THE DROPDOWN SELECTOR
                        const dropdown = document.getElementById("taskList");
                        if (dropdown) {
                            // Create a new <option value="NEW_ID">NEW_TITLE</option>
                            const newOption = document.createElement("option");
                            newOption.value = data.data.id; // The database ID returned by PHP
                            newOption.textContent = data.data.title; // The clean title text
                            
                            // Append it to the dropdown list instantly
                            dropdown.appendChild(newOption);
                        }

                        create_form.reset();
                    }
                } catch (error) {
                    console.error(error);
                }
            });
        }
    </script>
</body>

</html>