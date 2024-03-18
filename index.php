<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
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
            <div class="action-buttons d-flex align-items-center mb-3">
                <button type="button" id="buttonAdd1" data-button-id="1" class="btn btn-primary">Add</button>
                <form>
                    <select class="form-select actionSelect" id="actionSelect1">
                        <option value="">Please Select</option>
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
            <div id="live_contend">
            </div>
        </main>
    </div>
    <div class="row">
        <main role="main" class="col-md-12 ml-sm-auto col-lg-12 pt-3 px-4">
            <div class="action-buttons d-flex align-items-center mb-3">
                <button type="button" id="buttonAdd2" data-button-id="1" class="btn btn-primary">Add</button>
                <form>
                    <select class="form-select actionSelect" id="actionSelect2">
                        <option value="">Please Select</option>
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
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <!-- Modal Body -->
                <div class="modal-body">
                    <form id="userForm">
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
                            <select class="form-control" id="role" name="role">
                                <option value="admin">Admin</option>
                                <option value="user">User</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary" style="margin-top: 5px">Submit</button>
                    </form>
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
