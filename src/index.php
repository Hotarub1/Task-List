<?php

require_once 'config.php';

// Handle taskList submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create_list'])) {
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');

    if (empty($title)) {
        $message = '❌ Title is required.';
    } elseif (isset($pdo)) {
        try {
            // Prepare an SQL statement to prevent SQL injection
            $stmt = $pdo->prepare("INSERT INTO taskLists (title, description) VALUES (:title, :description)");
            $stmt->execute([
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
    <form method="POST" action="">
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
            <?php
            if(isset($pdo)) {
                // Fetch all tasklists
                $stmt = $pdo->prepare('SELECT * FROM taskLists');
                $stmt->execute();
                $taskLists = $stmt->fetchAll(PDO::FETCH_ASSOC);

                echo '<select id="taskList" name="taskList" required>';
                foreach ($taskLists as $taskList) {
                    echo '<option value="' . htmlspecialchars($taskList['id']) . '">' . htmlspecialchars($taskList['title']) . '</option>';
                }
                echo '</select>';
            }
            ?>
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
    <?php
    if (isset($pdo)) {
        // Fetch all tasklists      
        $stmt = $pdo->prepare('SELECT * FROM taskLists');
        $stmt->execute();
        $taskLists = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // If tasklists exist, for each tasklist, display list of tasks
        if ($taskLists) {
            echo '<ul>'; 
            foreach ($taskLists as $taskList) {
                $text = empty(htmlspecialchars($taskList['description'])) ? 
                    htmlspecialchars($taskList['title']) : 
                    htmlspecialchars($taskList['title']) . ' - ' . htmlspecialchars($taskList['description']);
                echo '<li>' . $text . '</li>';

                // Show all tasks in tasklist
                $stmt = $pdo->prepare('SELECT * FROM tasks WHERE task_list_id = :list_id');
                $stmt->execute(['list_id' => $taskList['id']]);
                $tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);
                if ($tasks) {
                    echo '<ul>'; 
                    foreach ($tasks as $task) {
                        $text = empty(htmlspecialchars($task['description'])) ? 
                            htmlspecialchars($task['title']) : 
                            htmlspecialchars($task['title']) . ' - ' . htmlspecialchars($task['description']);
                        echo '<li class="tasklist">' . $text . '</li>';
                    }
                    echo '</ul>';
                } else {
                    echo '<p>- No tasks found</p>';
                }

            }
            echo '</ul>';
        } else {
            echo '<p>No tasklists found</p>';
        }
    }
    ?>
</body>

</html>