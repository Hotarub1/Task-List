<h2>Create New Task</h2>
<form id="create_task">
    <div class="form-group">
    <label for="taskList">Task-List: *</label>
        <?php if (isset($pdo) && isset($taskLists)): ?>
            <select id="taskList" name="taskList" required>
                <?php foreach ($taskLists as $taskList): ?>
                    <option value="<?= htmlspecialchars($taskList->id) ?>"><?= htmlspecialchars($taskList->title) ?></option>
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