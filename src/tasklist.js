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