<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Todo List App</title>
    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <div class="row justify-content-center mt-5">
            <div class="col-md-8">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h1 class="text-center mb-0">Todo List</h1>
                    </div>
                    <div class="card-body">
                        <!-- Add Todo Form -->
                        <form id="todo-form" class="mb-4">
                            <div class="input-group">
                                <input type="text" id="todo-input" class="form-control" placeholder="Add a new task..." required>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-plus"></i> Add
                                </button>
                            </div>
                        </form>

                        <!-- Todo List Controls -->
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div class="btn-group">
                                <button id="filter-all" class="btn btn-outline-primary active">All</button>
                                <button id="filter-active" class="btn btn-outline-primary">Active</button>
                                <button id="filter-completed" class="btn btn-outline-primary">Completed</button>
                            </div>
                            <!-- In the Todo List Controls section -->
                            <div class="btn-group">
                                <button id="check-all" class="btn btn-outline-success">
                                    <i class="fas fa-check-double"></i> Complete All
                                </button>
                                <button id="delete-completed" class="btn btn-outline-danger">
                                    <i class="fas fa-trash"></i> Delete Selected
                                </button>
                            </div>
                        </div>

                        <!-- Todo List -->
                        <div class="todo-container">
                            <div id="todo-list" class="list-group">
                                <!-- Todo items will be added here dynamically -->
                            </div>
                        </div>

                        <!-- Todo Stats -->
                        <div class="d-flex justify-content-between mt-3">
                            <span id="items-left">0 items left</span>
                            <span id="items-completed">0 completed</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Confirmation Modal -->
    <div class="modal fade" id="confirmationModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmationModalTitle">Confirmation</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="confirmationModalBody">
                    Are you sure you want to proceed?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="confirmationModalConfirm">Confirm</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <!-- SortableJS for drag and drop -->
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.14.0/Sortable.min.js"></script>
    <!-- Custom JS -->
    <script src="script.js"></script>
</body>
</html>