<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="src/views/style/css/style.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-dark sticky-top bg-dark flex-md-nowrap p-0">
    <a class="navbar-brand col-sm-3 col-md-2 mr-0" href="#">User</a>
</nav>

<div class="container">
    <div id="error" style="color: red;"></div>
    <div class="row">
        <main role="main" class="col-md-12 ml-sm-auto col-lg-12 pt-3 px-4">
            <h3>Users</h3>
            <div class="action-buttons d-flex align-items-center mb-2 gap-2">
                <button type="button" id="buttonAdd1" data-button-id="1" class="btn btn-primary">Add</button>
                <form>
                    <select class="form-select actionSelect" id="actionSelect1">
                        <option value="">-Please Select-</option>
                        <option value="setActive">Set Active</option>
                        <option value="setNotActive">Set Not Active</option>
                        <option value="delete">Delete</option>
                    </select>
                </form>
                <button type="button" class="btn btn-primary buttonOk" data-select="#actionSelect1">Ok</button>
            </div>
        </main>
    </div>
    <div class="row">
        <main role="main" class="col-md-9 ml-sm-auto col-lg-10 pt-3 px-4">
            <table id="userTable" class="table table-bordered">
                <thead>
                <tr>
                    <th><input type="checkbox" id="selectAll"></th>
                    <th>Name</th>
                    <th>Status</th>
                    <th>Role</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody id="live_content">
                </tbody>
            </table>
        </main>
    </div>
    <div class="row">
        <main role="main" class="col-md-12 ml-sm-auto col-lg-12 pt-3 px-4">
            <div class="action-buttons d-flex align-items-center mb-3 gap-2">
                <button type="button" id="buttonAdd2" data-button-id="1" class="btn btn-primary">Add</button>
                <form>
                    <select class="form-select actionSelect" id="actionSelect2">
                        <option value="">-Please Select-</option>
                        <option value="setActive">Set Active</option>
                        <option value="setNotActive">Set Not Active</option>
                        <option value="delete">Delete</option>
                    </select>
                </form>
                <button type="button" class="btn btn-primary buttonOk" data-select="#actionSelect2">Ok</button>
            </div>
        </main>
    </div>
    <!-- Modal -->
    <div class="modal" id="userModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title"></h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <!-- Modal Body -->
                <div class="modal-body">
                    <form id="userForm">
                        <div id="error-message" class="alert alert-danger" style="display: none;"></div>
                        <div class="form-group">
                            <label for="firstName">First Name:</label>
                            <input type="text" class="form-control" id="firstName" name="firstName">
                        </div>
                        <div class="form-group">
                            <label for="lastName">Last Name:</label>
                            <input type="text" class="form-control" id="lastName" name="lastName">
                        </div>
                        <div class="form-group">
                            <label class="switch" for="status">Status:</label>
                            <div class="cl-toggle-switch">
                                <label class="cl-switch">
                                    <input type="checkbox" id="status">
                                    <span></span>
                                </label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="role">Role:</label>
                            <select class="form-select" id="role" name="role">
                                <option value="">-Please Select-</option>
                                <option value="admin">Admin</option>
                                <option value="user">User</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary" id="submitBtn">Submit</button>
                        <button type="button" class="btn btn-secondary float-end" data-bs-dismiss="modal">Cancel</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal delete confirm -->
    <div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmModalLabel"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <h4 class="modal-body"></h4>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="confirmBtn"></button>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal massages -->
    <div class="modal fade" id="massageModel" tabindex="-1" aria-labelledby="massageModelLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="massageModelLabel"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <h4 class="modal-body"></h4>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" id="confirmMassageBtn">Ok</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/feather-icons/dist/feather.min.js"></script>
    <script src="src/views/style/js/script.js"></script>
    <script>
        feather.replace()
    </script>
</body>
</html>
