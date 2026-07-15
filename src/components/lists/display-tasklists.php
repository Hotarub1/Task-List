<h2>Existing Tasklists</h2>
<ul id="tasklists-container">
    <?php if (isset($pdo) && isset($taskLists)): ?>
        <?php foreach ($taskLists as $taskList): 
            // Fetch the tasks for this specific list
            $tasks = $tasksRepo->fetchAll($taskList->id);
        ?>
            <li class="tasklist-container" data-tasklist-id="<?= (int) $taskList->id ?>">
                <div class="tasklist-header"><?= htmlspecialchars($taskList->preview) ?></div>
                <ul class="tasks-container-wrapper">
                    <?php if ($tasks): ?>
                        <?php foreach ($tasks as $task): 
                        ?>
                            <li class="tasklist"><?= htmlspecialchars($task->preview) ?></li>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <li class="no-tasks">- No tasks found</li>
                    <?php endif; ?>
                </ul>
            </li>
        <?php endforeach; ?>
    <?php endif; ?>
</ul>