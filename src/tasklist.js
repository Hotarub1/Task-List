function addTaskList(id, title, description) {
    const container = document.getElementById("tasklists-container");

    const noListsMsg = document.getElementById("no-lists");
    if (noListsMsg) noListsMsg.remove();

    const listContainer = document.createElement("li");
    listContainer.className = "tasklist-container";
    listContainer.dataset.tasklistId = id;

    const header = document.createElement("div");
    header.className = "tasklist-header";
    header.textContent = description ? `${title} - ${description}` : title;

    const tasksList = document.createElement("ul");
    tasksList.className = "tasks-container-wrapper";

    const noTasks = document.createElement("li");
    noTasks.className = "no-tasks";
    noTasks.textContent = "- No tasks found";

    tasksList.appendChild(noTasks);
    listContainer.appendChild(header);
    listContainer.appendChild(tasksList);
    container.appendChild(listContainer);
}

function addTask(taskListID, title, description) {
    const listContainer = document.querySelector(`.tasklist-container[data-tasklist-id="${taskListID}"]`);
    if (!listContainer) {
        console.error(`Could not find tasklist with ID ${taskListID}`);
        return;
    }

    const tasksList = listContainer.querySelector(".tasks-container-wrapper");

    const noTasksMsg = tasksList.querySelector(".no-tasks");
    if (noTasksMsg) noTasksMsg.remove();

    const taskItem = document.createElement("li");
    taskItem.className = "tasklist";
    taskItem.textContent = description ? `${title} - ${description}` : title;

    tasksList.appendChild(taskItem);
}

function showTaskForm(id, title) {
    const form = document.getElementById("create_task");

    const emptyMsg = document.getElementById("no-tasklists-message");
    if (emptyMsg) emptyMsg.remove();

    // Fields already rendered (e.g. two tasklists created back-to-back) — nothing more to do
    if (document.getElementById("taskList")) return;

    // Task-list select
    const selectGroup = document.createElement("div");
    selectGroup.className = "form-group";
    const selectLabel = document.createElement("label");
    selectLabel.setAttribute("for", "taskList");
    selectLabel.textContent = "Task-List: * ";
    const select = document.createElement("select");
    select.id = "taskList";
    select.name = "taskList";
    select.required = true;
    const option = document.createElement("option");
    option.value = id;
    option.textContent = title;
    select.appendChild(option);
    selectGroup.append(selectLabel, select);

    // Title input
    const titleGroup = document.createElement("div");
    titleGroup.className = "form-group";
    const titleLabel = document.createElement("label");
    titleLabel.setAttribute("for", "title");
    titleLabel.textContent = "Title * ";
    const titleInput = document.createElement("input");
    titleInput.type = "text";
    titleInput.id = "title";
    titleInput.name = "title";
    titleInput.required = true;
    titleInput.maxLength = 255;
    titleGroup.append(titleLabel, titleInput);

    // Description textarea
    const descGroup = document.createElement("div");
    descGroup.className = "form-group";
    const descLabel = document.createElement("label");
    descLabel.setAttribute("for", "description");
    descLabel.textContent = "Description ";
    const descTextarea = document.createElement("textarea");
    descTextarea.id = "description";
    descTextarea.name = "description";
    descTextarea.rows = 3;
    descGroup.append(descLabel, descTextarea);

    // Submit button
    const submitBtn = document.createElement("button");
    submitBtn.type = "submit";
    submitBtn.name = "create_task";
    submitBtn.textContent = "Create Task";

    form.append(selectGroup, titleGroup, descGroup, submitBtn);
}