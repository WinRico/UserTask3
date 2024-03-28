$(document).ready(function() {

    function selectAll() {
        $('.selectUser').prop('checked', true);
    }

    function updateSelectAll() {
        var allSelected = $('.selectUser:checked').length === $('.selectUser').length;
        $('#selectAll').prop('checked', allSelected);
    }

    // Обробник події клікання на кнопку вибору всіх користувачів
    $(document).on('click', '#selectAll', function() {
        $('.selectUser').prop('checked', $(this).prop('checked'));
    });


    // Обробник події клікання на окремого користувача для вибору
    $(document).on('click', '.selectUser', function() {
        if ($('.selectUser:checked').length === $('.selectUser').length) {
            $('#selectAll').prop('checked', true);
        } else {
            $('#selectAll').prop('checked', false);
        }
    });


    // Функція для очищення полів форми
    function clearFormFields() {
        $('#firstName').val('');
        $('#lastName').val('');
        $('#role').val('');
        $('#status').prop('checked', false);
    }


    // Функція додавання рядка в таблицю
    function addNewCollumn(userData, id) {
        statusClass = !userData.status ? 'offline' : 'online';
        const newRow = $('<tr id="userRow_' + id + '">' +
            '<td><input type="checkbox" class="selectUser" data-id="' + id + '"></td>' +
            '<td><a id="firstName_' + id + '">' + userData.firstName + '</a>' + ' ' + '<a id="lastName_' + id + '">' + userData.lastName + '</a></td>' +
            '<td id="status_' + id + '" class="status-indicator ' + statusClass + '"><i class="fa fa-circle"></i></td>' +
            '<td id="role_' + id + '">' + userData.role + '</td>' +
            '<td>' + '<div class="btn-group">' + '<button type="button" data-button-id="2" class="btn btn-sm btn-outline-secondary editBtn" data-id="' + id + '">' +
            '<i class="fa fa-pencil"></i></button>' +
            '<button type="button" class="btn btn-sm btn-outline-secondary deleteBtn" data-id="' + id + '"><i class="fa fa-trash"></i></i></button>' +
            '</div>' +
            '</td>' +
            '</tr>');
        $('#userTable tbody').append(newRow);
    }


    // Функція редагування рядка в таблиці
    function editCollumn(userData) {
        const table = $('#userTable');
        const row = table.find('#userRow_' + userData.userId);
        statusClass = !userData.status ? 'setNotActive' : 'setActive';
        updateStatus(userData.userId, statusClass);
        row.find('#firstName_' + userData.userId).text(userData.firstName);
        row.find('#lastName_' + userData.userId).text(userData.lastName);
        row.find('#role_' + userData.userId).text(userData.role);
    }

    // Функція для оновлення статусу користувача в таблиці
    function updateStatus(userId, status) {

        const statusElement = $('#status_' + userId); // Знаходимо елемент статусу за його ID
        if (status === "setActive") {
            statusElement.addClass('active');
        } else {
            statusElement.removeClass('active');
        }
    }


    // функція отримання даних користувача за id;
    function getUserData(userId) {
        return new Promise((resolve, reject) => {
            let firstName = $('#firstName_' + userId).text();
            let lastName = $('#lastName_' + userId).text();
            let role = $('#role_' + userId).text();
            let statusElement = $('#status_' + userId);
            let status = !!statusElement.hasClass('online');
            let userData = {
                userId: userId,
                firstName: firstName,
                lastName: lastName,
                role: role,
                status: status,
            };
            if (userData) {
                resolve(userData);
            } else {
                console.error('Missing user');
            }
        });
    }


    // Функція для отримання в форму данних
    function UserFormField(dataForm, buttonId) {
        clearFormFields();

        // Заповнення отриманими даними полів форми редагування користувача
        $('#firstName').val(dataForm['firstName'] || '');
        $('#lastName').val(dataForm['lastName'] || '');
        if (!dataForm['status']) {
            $('#status').prop('checked', false);
        } else {
            $('#status').prop('checked', true);
        }
        $('#role').val(dataForm['role'] || '');

        // Відображення модального вікна редагування користувача
        $(userModal).data('button-id', buttonId).data('id', dataForm['userId']).modal('show');

    }

    // Функція для додавання користувача
    function addUser(userData) {
        // Відправка AJAX-запиту на сервер для додавання користувача
        $.ajax({
            url: 'src/Controllers/MainController.php',
            type: 'POST',
            dataType: 'json',
            data: {
                action: 'addUser',
                userData: userData
            },
            success: function(response) {
                if (response.status) {
                    $('#userModal').modal('hide'); // При успішному додаванні користувача ховаємо модальне вікно
                    addNewCollumn(userData, response.user.id);
                    if ($('#selectAll').prop('checked')) {
                        selectAll();
                    }
                    console.log(response);
                } else {
                    console.error(response);
                    $('#error-message').text(response.error.message).show(); // Показуємо повідомлення про помилку
                }
            },
            error: function(xhr, status, error) {
                console.error(error);
            }
        });
    }


    // Функція для редагування користувача
    function editUser(userData) {
        // Відправка AJAX-запиту на сервер для отримання даних про користувача за його ID
        $.ajax({
            url: 'src/Controllers/MainController.php',
            type: 'POST',
            dataType: 'json',
            data: {
                action: 'editUser',
                userData: userData
            },
            success: function(response) {
                if (response.status) {
                    $('#userModal').modal('hide');
                    editCollumn(userData)
                    console.log(response);
                } else {
                    console.error(response);
                    $('#error-message').text(response.error.message).show(); // Показуємо повідомлення про помилку
                }
            },
            error: function(xhr, status, error) {
                console.error(error);
            }
        });
    }

    // Функція для видалення користувача чи кількох користувачів
    function deleteUser(userIds) {
        // Перевірка, чи є переданий параметр масивом
        if (Array.isArray(userIds)) {
            // AJAX-запит для видалення кількох користувачів одночасно
            $.ajax({
                url: 'src/Controllers/MainController.php',
                type: 'POST',
                dataType: 'json',
                data: {
                    action: 'deleteUser',
                    userId: userIds // Передача масиву userIds
                },
                success: function(response) {
                    if (response.status) {
                        // Видалення кожного користувача з таблиці
                        userIds.forEach(function(id) {
                            $('#userRow_' + id).remove();
                        });
                        updateSelectAll();
                            console.log(response);
                    } else {
                        console.error(response);
                    }
                },
                error: function(xhr, status, error) {
                    console.error(error);
                }
            });
        } else {
            // AJAX-запит для видалення одного користувача
            $.ajax({
                url: 'src/Controllers/MainController.php',
                type: 'POST',
                dataType: 'json',
                data: {
                    action: 'deleteUser',
                    userId: userIds // Передача одного userId
                },
                success: function(response) {
                    if (response.status) {
                        $('#userRow_' + userIds).remove();
                        updateSelectAll();
                        console.log(response);
                    } else {
                        console.error(response);
                    }
                },
                error: function(xhr, status, error) {
                    console.error(error);
                }
            });
        }
    }

    // Функція для виконання дії з вибраними користувачами
    function doAction(selectedUsers, action) {

        $.ajax({
            url: 'src/Controllers/MainController.php',
            method: 'POST',
            dataType: 'json',
            data: {
                action: 'updateStatusSelectedUsers',
                userIds: selectedUsers,
                actionSelected: action,
            },
            success: function(response) {
                if (response.status) {
                    selectedUsers.forEach(function(userId) {
                        updateStatus(userId, action);
                    });
                    console.log(response);
                } else {
                    console.error(response);
                }
            },
            error: function(xhr, status, error) {
                console.error(error);
            }
        });
    }


    // Вивід модального вікна підтвердження дії;
    function confirmAction(action, userData) {
        const confirmModal = document.getElementById('confirmModal');
        const modalTitle = confirmModal.querySelector('.modal-title');
        const modalBody = confirmModal.querySelector('.modal-body');
        const modalAction = confirmModal.querySelector('.btn-danger');
        modalTitle.textContent = 'Confirm ' + action; // Змінюємо заголовок модального вікна залежно від дії
        modalBody.textContent = 'Are you sure you want to ' + action + ' ' + userData['firstName'] + ' ' + userData['lastName'] + '?'; // Встановлюємо текст повідомлення залежно від дії та даних про користувача
        modalAction.textContent = action; // Встановлюємо текст кнопки підтвердження дії
        $('#confirmModal').modal('show');
        $('#confirmBtn').off('click').on('click', function() {
            if (action === 'delete') {
                deleteUser(userData['userId']);
                $('#confirmModal').modal('hide');
            }

        });
    }


    // Обробник події клікання на кнопку додавання користувача (два випадки)
    $('#buttonAdd1, #buttonAdd2').click(function() {
        let buttonId = 1;
        const userModal = document.getElementById('userModal');
        const modalTitle = userModal.querySelector('.modal-title');
        modalTitle.textContent = 'Add';
        clearFormFields();
        $('#error-message').hide();
        $('#userModal').data('button-id', buttonId).data('id', null).modal('show');
    });


    // Обробник події клікання на кнопку редагування користувача
    $(document).on('click', '.editBtn', function() {

        let buttonId = 2;
        let userId = $(this).data('id');

        getUserData(userId)
            .then(userData => {
                const userModal = document.getElementById('userModal');
                const modalTitle = userModal.querySelector('.modal-title');
                modalTitle.textContent = 'Update';
                $('#error-message').hide();
                UserFormField(userData, buttonId);
            })

    });



    // Обробник події клікання на кнопку видалення користувача
    $(document).on('click', '.deleteBtn', function() {
        const userId = $(this).data('id');
        getUserData(userId)
            .then(userData => {
                confirmAction('delete', userData);
            })
    });



    // Обробник події відправки форми для додавання/редагування користувача
    $(document).on('submit', '#userModal', function(event) {
        event.preventDefault();
        let firstName = $('#firstName').val();
        let lastName = $('#lastName').val();
        let role = $('#role').val();
        let status = !!$('#status').is(':checked');
        let buttonId = $(this).data('button-id');
        let userId = $(this).data('id');
        let requestData = {
            firstName: firstName,
            lastName: lastName,
            status: status,
            role: role
        };
        if (buttonId === 1) {
            addUser(requestData);
        } else {
            requestData.userId = userId;
            editUser(requestData)
        }
    });


    // Обробник події клікання на кнопку збереження вибраних дій з користувачами
    $('#buttonOk1, #buttonOk2').click(function() {
        let actionSelect = $(this).data('select');
        let action = $(actionSelect).val();

        if (action === '') {
            // Викликати функцію для обробки помилки обраної дії
            handleEmptyActionError();
            return false;
        }

        if ($('.selectUser:checked').length === 0) {
            // Викликати функцію для обробки помилки відсутності вибраних користувачів
            handleNoSelectedUsersError();
            return false;
        }

        let selectedUsers = [];
        $('.selectUser:checked').each(function() {
            let userId = $(this).data('id');
            selectedUsers.push(userId);
        });

        // Виклик функції для виконання дії в залежності від вибраної дії
        executeAction(selectedUsers, action);
    });

    function handleEmptyActionError() {
        // Обробка помилки обраної дії
        const massageModal = document.getElementById('massageModel');
        const modalBody = massageModal.querySelector('.modal-body');
        modalBody.textContent = 'Please select an action.';
        $('#massageModel').modal('show');
        $('#confirmMassageBtn').click(function() {
            $('#massageModel').modal('hide');
        });
    }

    function handleNoSelectedUsersError() {
        // Обробка помилки відсутності вибраних користувачів
        const massageModal = document.getElementById('massageModel');
        const modalBody = massageModal.querySelector('.modal-body');
        modalBody.textContent = 'Please select at least one user.';
        $('#massageModel').modal('show');
        $('#confirmMassageBtn').click(function() {
            $('#massageModel').modal('hide');
        });
    }

    function executeAction(selectedUsers, action) {
        // Виконання вибраної дії
        if (action === 'delete') {
            if (selectedUsers.length !== 1) {
                showDeleteConfirmationModal(selectedUsers);
            } else {
                getUserData(selectedUsers[0])
                    .then(userData => {
                        confirmAction('delete', userData);
                    })
                    .catch(error => {
                        console.log(error);
                    })
            }
        } else {
            doAction(selectedUsers, action);
        }
    }

    function showDeleteConfirmationModal(selectedUsers) {
        // Показ модального вікна підтвердження видалення
        const confirmModal = document.getElementById('confirmModal');
        const modalTitle = confirmModal.querySelector('.modal-title');
        const modalBody = confirmModal.querySelector('.modal-body');
        const modalAction = confirmModal.querySelector('.btn-danger');
        modalTitle.textContent = 'Delete Confirmation';
        modalBody.textContent = 'Are you sure you want to delete these users?';
        modalAction.textContent = 'Delete';
        $('#confirmModal').modal('show');
        $('#confirmBtn').off('click').on('click', function() {
            deleteUser(selectedUsers); // Викликаємо функцію для видалення користувачів
            $('#confirmModal').modal('hide'); // Ховаємо модальне вікно підтвердження
        });
    }
})