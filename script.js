document.addEventListener('DOMContentLoaded', function() {
    // DOM Elements
    const todoForm = document.getElementById('todo-form');
    const todoInput = document.getElementById('todo-input');
    const todoList = document.getElementById('todo-list');
    const filterAll = document.getElementById('filter-all');
    const filterActive = document.getElementById('filter-active');
    const filterCompleted = document.getElementById('filter-completed');
    const checkAll = document.getElementById('check-all');
    const deleteCompleted = document.getElementById('delete-completed');
    const itemsLeft = document.getElementById('items-left');
    const itemsCompleted = document.getElementById('items-completed');
    const confirmationModal = new bootstrap.Modal(document.getElementById('confirmationModal'));
    const confirmationModalTitle = document.getElementById('confirmationModalTitle');
    const confirmationModalBody = document.getElementById('confirmationModalBody');
    const confirmationModalConfirm = document.getElementById('confirmationModalConfirm');

    // Update the delete button text
    deleteCompleted.innerHTML = '<i class="fas fa-trash"></i> Delete Selected';
    
    // Current filter
    let currentFilter = 'all';
    
    // Initialize Sortable
    const sortable = new Sortable(todoList, {
        animation: 150,
        handle: '.todo-handle',
        ghostClass: 'sortable-ghost',
        chosenClass: 'sortable-chosen',
        onEnd: function() {
            updatePositions();
        }
    });

    // Load todos on page load
    loadTodos();

    // Event Listeners
    todoForm.addEventListener('submit', addTodo);
    filterAll.addEventListener('click', () => setFilter('all'));
    filterActive.addEventListener('click', () => setFilter('incomplete'));
    filterCompleted.addEventListener('click', () => setFilter('completed'));
    checkAll.addEventListener('click', confirmCompleteAll);
    deleteCompleted.addEventListener('click', confirmDeleteCompleted);

    // Functions
    function loadTodos() {
        fetch(`api.php?filter=${currentFilter}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    renderTodos(data.todos);
                    updateStats(data.todos);
                }
            })
            .catch(error => console.error('Error loading todos:', error));
    }

    function renderTodos(todos) {
        todoList.innerHTML = '';
        
        if (todos.length === 0) {
            const emptyMessage = document.createElement('div');
            emptyMessage.className = 'text-center text-muted p-3';
            emptyMessage.textContent = 'No tasks found';
            todoList.appendChild(emptyMessage);
            return;
        }
        
        todos.forEach(todo => {
            const todoItem = document.createElement('div');
            todoItem.className = `todo-item ${todo.completed == 1 ? 'completed' : ''}`;
            todoItem.dataset.id = todo.id;
            todoItem.dataset.position = todo.position;
            
            todoItem.innerHTML = `
                <div class="todo-handle">
                    <i class="fas fa-grip-vertical"></i>
                </div>
                <div class="form-check">
                    <input class="form-check-input todo-checkbox" type="checkbox" ${todo.completed == 1 ? 'checked' : ''}>
                </div>
                <div class="todo-text">${escapeHtml(todo.task)}</div>
                <div class="todo-actions">
                    <button class="btn btn-sm btn-outline-danger todo-delete">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            `;
            
            todoList.appendChild(todoItem);
            
            // Add event listeners to the new todo item
            const checkbox = todoItem.querySelector('.todo-checkbox');
            const deleteBtn = todoItem.querySelector('.todo-delete');
            
            checkbox.addEventListener('change', () => {
                updateTodoStatus(todo.id, checkbox.checked);
            });
            
            deleteBtn.addEventListener('click', () => {
                deleteTodo(todo.id);
            });
        });
    }

    function addTodo(e) {
        e.preventDefault();
        const task = todoInput.value.trim();
        
        if (task) {
            fetch('api.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    action: 'add',
                    task: task
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    todoInput.value = '';
                    loadTodos();
                }
            })
            .catch(error => console.error('Error adding todo:', error));
        }
    }

    function updateTodoStatus(id, completed) {
        fetch('api.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                action: 'update',
                id: id,
                completed: completed
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                loadTodos();
            }
        })
        .catch(error => console.error('Error updating todo status:', error));
    }

    function updateAllTodosStatus(completed) {
        fetch('api.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                action: 'updateAll',
                completed: completed
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                loadTodos();
            }
        })
        .catch(error => console.error('Error updating all todos:', error));
    }

    function deleteTodo(id) {
        fetch('api.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                action: 'delete',
                id: id
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                loadTodos();
            }
        })
        .catch(error => console.error('Error deleting todo:', error));
    }

    function deleteCompletedTodos() {
        fetch('api.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                action: 'deleteCompleted'
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                loadTodos();
            }
        })
        .catch(error => console.error('Error deleting completed todos:', error));
    }

    function updatePositions() {
        const todoItems = document.querySelectorAll('.todo-item');
        const positions = {};
        
        todoItems.forEach((item, index) => {
            positions[item.dataset.id] = index + 1;
        });
        
        fetch('api.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                action: 'updatePositions',
                positions: positions
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // No need to reload, positions are already visually updated
            }
        })
        .catch(error => console.error('Error updating positions:', error));
    }

    function setFilter(filter) {
        currentFilter = filter;
        
        // Update active filter button
        [filterAll, filterActive, filterCompleted].forEach(btn => {
            btn.classList.remove('active');
        });
        
        switch (filter) {
            case 'all':
                filterAll.classList.add('active');
                break;
            case 'incomplete':
                filterActive.classList.add('active');
                break;
            case 'completed':
                filterCompleted.classList.add('active');
                break;
        }
        
        loadTodos();
    }

    function updateStats(todos) {
        const incomplete = todos.filter(todo => todo.completed == 0).length;
        const completed = todos.filter(todo => todo.completed == 1).length;
        
        itemsLeft.textContent = `${incomplete} item${incomplete !== 1 ? 's' : ''} left`;
        itemsCompleted.textContent = `${completed} completed`;
        
        // Update check all button text based on if all are completed
        if (todos.length > 0 && incomplete === 0) {
            checkAll.innerHTML = '<i class="fas fa-times-circle"></i> Unmark All';
        } else {
            checkAll.innerHTML = '<i class="fas fa-check-double"></i> Complete All';
        }
    }

    function confirmCompleteAll() {
        const allCompleted = itemsLeft.textContent.startsWith('0');
        
        confirmationModalTitle.textContent = allCompleted ? 'Mark All as Incomplete?' : 'Mark All as Complete?';
        confirmationModalBody.textContent = allCompleted 
            ? 'Are you sure you want to mark all tasks as incomplete?' 
            : 'Are you sure you want to mark all tasks as complete?';
        
        confirmationModalConfirm.onclick = function() {
            updateAllTodosStatus(!allCompleted);
            confirmationModal.hide();
        };
        
        confirmationModal.show();
    }

    function confirmDeleteCompleted() {
        // Check if any items are checked
        const checkedItems = document.querySelectorAll('.todo-checkbox:checked');
        
        if (checkedItems.length === 0) {
            // No items checked, show alert
            alert('Please select at least one item to delete.');
            return;
        }
        
        // Items are checked, show confirmation modal
        confirmationModalTitle.textContent = 'Delete Selected Tasks?';
        confirmationModalBody.textContent = `Are you sure you want to delete ${checkedItems.length} selected task(s)? This action cannot be undone.`;
        
        confirmationModalConfirm.onclick = function() {
            deleteCompletedTodos();
            confirmationModal.hide();
        };
        
        confirmationModal.show();
    }

    // Helper function to escape HTML
    function escapeHtml(unsafe) {
        return unsafe
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/"/g, "&quot;")
            .replace(/'/g, "&#039;");
    }
});