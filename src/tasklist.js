function addTaskList(title, description) {
    const container = document.getElementById("tasklists-container");
    const listItems = document.createElement("ul");
    const itemWrapper = document.createElement("li");
    const tasks = document.createElement("li");

    const noListsMsg = document.getElementById("no-lists");
    if (noListsMsg) {
        noListsMsg.remove();
    }

    taskList.textContent = `${title} - ${description}`;

    tasks.className = "no-tasks";
    tasks.textContent = "- No tasks found";

    itemWrapper.className = "tasks-container-wrapper";

    container.appendChild(taskList); 
    container.appendChild(itemWrapper); 
    itemWrapper.appendChild(listItems);
    listItems.appendChild(tasks);
}

function addTask(taskListID, title, description) {
    const container = document.getElementById("tasklists-container-wrapper");
    const task = document.createElement("li");
    task.textContent = `${title} - ${description}`;
    container.appendChild(task); 
}