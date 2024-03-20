fetchData();
// Функція для завантаження вмісту
function fetchData() {
    $.ajax({
        url: 'src/Controllers/MainController.php',
        method: 'post',
        data:{
            action: 'live_content'
        },
        success: function (data) {
            $('#live_content').html(data);
        }
    });
}



// Обробник події клікання на кнопку вибору всіх користувачів
$(document).on('click', '#selectAll', function(){
    $('.selectUser').prop('checked', $(this).prop('checked'));
});


// Обробник події клікання на окремого користувача для вибору
$(document).on('click', '.selectUser', function(){
    if($('.selectUser:checked').length === $('.selectUser').length) {
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
function addNewCollumn(userData,id){
    statusClass = userData.status !== 0 ? 'offline' : 'online';
    console.log(userData.firstName);
    console.log(id);
    const newRow = $('<tr id="userRow_' + id + '">' +
        '<td><input type="checkbox" class="selectUser" data-id="' + id +'"></td>' +
        '<td id="userName' + id + '">' + userData.firstName + ' ' + userData.lastName + '</td>' +
        '<td id="status' + id + '" class="status-indicator ' + statusClass + '"><i class="fa fa-circle"></i></td>'  +
        '<td id="role' + id + '">' + userData.role + '</td>' +
        '<td>' + '<div class="btn-group">' + '<button type="button" data-button-id="2" class="btn btn-sm btn-outline-secondary editBtn" data-id="' + id + '">' +
        '<i class="fa-regular fa-pen-to-square"></i></button>' +
        '<button type="button" class="btn btn-sm btn-outline-secondary deleteBtn" data-id="' + id + '"><i class="fa-solid fa-trash"></i></button>' +
        '</div>' +
        '</td>' +
        '</tr>');
    $('#userTable tbody').append(newRow);
}


// Функція редагування рядка в таблиці
function editCollumn(userData){
    const table = $('#userTable');
    const row = table.find('#userRow_' + userData.userId); // Змінено з 'userRow_' на '#userRow_'
    statusClass = userData.status === 0 ? 'setNotActive' : 'setActive';
    updateStatus(userData.userId, statusClass);
    row.find('#userName' + userData.userId).text(userData.firstName + ' ' + userData.lastName); // Виправлено індекси
    row.find('#role' + userData.userId).text(userData.role); // Виправлено індекси
}

// Функція для оновлення статусу користувача в таблиці
function updateStatus(userId, status) {

    const statusElement = $('#status' + userId); // Знаходимо елемент статусу за його ID
    if (status === "setActive") {
        statusElement.removeClass('offline').addClass('online');
    } else {
        statusElement.removeClass('online').addClass('offline');
    }
}


// функція отримання даних користувача за id;
function getUserData(userId) {
    return new Promise((resolve, reject) => {
        // AJAX-запит для отримання даних користувача з бази даних
        $.ajax({
            url: 'src/Controllers/MainController.php',
            type: 'POST',
            data: {
                action: 'getUserById',
                user_id: userId
            },
            success: function(response) {
                const userData = JSON.parse(response);
                resolve(userData); // Повертаємо отримані дані через обіцянку
            },
            error: function(xhr, status, error) {
                reject(error); // Повертаємо помилку через обіцянку
            }
        });
    });
}

// Функція для отримання в форму данних
function UserFormField(userId,buttonId) {

    // Відправка AJAX-запиту на сервер для отримання даних про користувача за його ID
    $.ajax({
        url: 'src/Controllers/MainController.php',
        type: 'POST',
        dataType: 'json',
        data: {
            action: 'getUserById',
            user_id: userId
        },
        success: function(response) {
            // Заповнення отриманими даними полів форми редагування користувача
            $('#firstName').val(response[0].firstname);
            $('#lastName').val(response[0].lastname);
            if (response[0].status === 'No active'){
                $('#status').prop('checked', false);
            }
            else {
                $('#status').prop('checked', true);
            }
            $('#role').val(response[0].role);

            // Відображення модального вікна редагування користувача
            $(userModal).data('button-id', buttonId).data('id', userId).modal('show');
        },
        error: function(xhr, status, error) {
            console.error(error);
        }
    });
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
                addNewCollumn(userData,response.id);
                console.log(response.message);
            } else {
                console.error(response.message);
                $('#error-message').text(response.message).show(); // Показуємо повідомлення про помилку
            }
        },
        error: function(xhr, status, error) {
            console.error(error);
        }
    });
}


// Функція для редагування користувача
function editUser(userData){
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
                console.log(response.message);
            } else {
                console.error(response.message);
                $('#error-message').text(response.message).show(); // Показуємо повідомлення про помилку
            }},
        error: function(xhr, status, error) {
            console.error(error);
        }
    });
}

// Функція для видалення користувача
function deleteUser(userId) {

    // Відправка AJAX-запиту на сервер для отримання даних про користувача за його ID
    $.ajax({
        url: 'src/Controllers/MainController.php',
        type: 'POST',
        data: {
            action: 'deleteUser',
            userId: userId
        },
        success: function(response) {
            if (response.status) {
                if (Array.isArray(userId)) {
                    userId.forEach(function(id) {
                        $('#userRow_' + id).remove();
                    });
                } else {
                    $('#userRow_' + userId).remove();
                }
                console.log(response.message);
            } else {
                console.error(response.message);
            }},
        error: function(xhr, status, error) {
            console.error(error);
        }
    });
}


// Функція для виконання дії з вибраними користувачами
function doAction(selectedUsers, action) {

    $.ajax({
        url: 'src/Controllers/MainController.php',
        method: 'POST',
        dataType: 'json',
        data: {
            action: 'actionWithSelectedUsers',
            userIds: selectedUsers,
            actionSelected: action,
        },
        success: function (response) {
            if (response.status) {
                selectedUsers.forEach(function(userId) {
                    updateStatus(userId,action);
                });
                console.log(response.message);
            } else {
                console.error(response.message);
            }},
        error: function(xhr, status, error) {
            console.error('Error deleting user:', error);
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
    modalBody.textContent = 'Are you sure you want to ' + action + ' ' + userData[0].firstname + ' ' + userData[0].lastname + '?'; // Встановлюємо текст повідомлення залежно від дії та даних про користувача
    modalAction.textContent = action; // Встановлюємо текст кнопки підтвердження дії
    $('#confirmModal').modal('show');
    $('#confirmBtn').off('click').on('click', function() {
        if (action === 'delete') {
            deleteUser(userData[0].id);
        }
        $('#confirmModal').modal('hide');
    });
}


// Обробник події клікання на кнопку додавання користувача (два випадки)
$('#buttonAdd1, #buttonAdd2').click(function(){
    let buttonId = 1;
    const userModal = document.getElementById('userModal');
    const modalTitle = userModal.querySelector('.modal-title');
    modalTitle.textContent = 'Add';
    clearFormFields();
    $('#error-message').hide();
    $(userModal).data('button-id', buttonId).data('id', null).modal('show');
});


// Обробник події клікання на кнопку редагування користувача
$(document).on('click', '.editBtn', function() {
    let buttonId = 2;
    let userId = $(this).data('id');
    const userModal = document.getElementById('userModal');
    const modalTitle = userModal.querySelector('.modal-title');
    modalTitle.textContent = 'Update';
    $('#error-message').hide();
    UserFormField(userId,buttonId);
});



// Обробник події клікання на кнопку видалення користувача
$(document).on('click', '.deleteBtn', function() {
    const userId = $(this).data('id');
    getUserData(userId)
        .then(userData =>{
            confirmAction('delete',userData);
        })
        .catch(error =>{
            console.log(error);
        })
});



// Обробник події відправки форми для додавання/редагування користувача
$(document).on('submit', '#userModal', function(event){
    event.preventDefault();

    let firstName = $('#firstName').val();
    let lastName = $('#lastName').val();
    let role = $('#role').val();
    let status = $('#status').is(':checked') ? 1 : 0;
    let buttonId = $(this).data('button-id');
    let userId = $(this).data('id');
    let requestData = {
        firstName: firstName,
        lastName: lastName,
        status: status,
        role: role
    };
    if (buttonId === 1){
        addUser(requestData);
    }else {
        requestData.userId = userId;
        editUser(requestData)
    }
});


// Обробник події клікання на кнопку збереження вибраних дій з користувачами
$('.buttonOk').click(function(){
    let actionSelect = $(this).data('select');
    let action = $(actionSelect).val();
    //Поля форми повідомлень помилок
    const massageModal = document.getElementById('massageModel');
    const modalBody = massageModal.querySelector('.modal-body');

    // Перевірка, чи обрано дію
    if(action === '') {
        modalBody.textContent = 'Please select an action.';
        $('#massageModel').modal('show');
        $('#confirmMassageBtn').click(function () {
            $('#massageModel').modal('hide');
        });
        return false;
    }

    // Перевірка, чи обрано хоча б одного користувача
    if($('.selectUser:checked').length === 0) {
        modalBody.textContent = 'Please select at least one user.';
        $('#massageModel').modal('show');
        $('#confirmMassageBtn').click(function () {
            $('#massageModel').modal('hide');
        });
        return false;
    }

    let selectedUsers = [];

    // Збір ID обраних користувачів
    $('.selectUser:checked').each(function() {
        let userId = $(this).data('id');
        selectedUsers.push(userId);
    });

    // Виклик функції для виконання дії
    if(action === 'delete'){
        if (selectedUsers.length !== 1) {
            const confirmModal = document.getElementById('confirmModal');
            const modalTitle = confirmModal.querySelector('.modal-title');
            const modalBody = confirmModal.querySelector('.modal-body');
            const modalAction = confirmModal.querySelector('.btn-danger');
            modalTitle.textContent = 'Delete Confirmation';
            modalBody.textContent = 'Are you sure you want to delete this users?';
            modalAction.textContent = 'Delete';
            $('#confirmModal').modal('show');
            $('#confirmBtn').click(function () {
                deleteUser(selectedUsers); // Викликаємо функцію для видалення користувача
                $('#confirmModal').modal('hide'); // Ховаємо модальне вікно підтвердження
            });
        }else {
            getUserData(selectedUsers[0])
                .then(userData =>{
                    confirmAction('delete',userData);
                })
                .catch(error =>{
                    console.log(error);
                })
        }

    }else {
        doAction(selectedUsers, action);
    }

});


