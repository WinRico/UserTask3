// Функція для завантаження вмісту
function loadContent() {
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
loadContent();


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
                console.log(response.message);
                loadContent();
            } else {
                console.error(response.message);
            }},
        error: function(xhr, status, error) {
            console.error('Error deleting user:', error);
        }
    });
}
// Функція для отримання в форму данних
function editUserData(userId,buttonId) {

    // Відправка AJAX-запиту на сервер для отримання даних про користувача за його ID
    $.ajax({
        url: 'src/Controllers/MainController.php', // URL-адреса маршруту, що повертає дані про користувача
        type: 'POST',
        data: {
            action: 'getUserById',
            user_id: userId
        },
        success: function(response) {
            // Заповнення отриманими даними полів форми редагування користувача
            const userData = JSON.parse(response);
            $('#firstName').val(userData[0].firstname);
            $('#lastName').val(userData[0].lastname);
            if (userData[0].status === 'No active'){
                $('#status').prop('checked', false);
            }
            else {
                $('#status').prop('checked', true);
            }
            $('#role').val(userData[0].role);

            // Відображення модального вікна редагування користувача
            $(userModal).data('button-id', buttonId).data('id', userId).modal('show');
        },
        error: function(xhr, status, error) {
            console.error(error);
        }
    });
}
// функція виводу модального вікна видалення;
function deleteFormField(userId) {
    // AJAX-запит для отримання імені користувача
    $.ajax({
        url: 'src/Controllers/MainController.php',
        type: 'POST',
        data: {
            action: 'getUserById',
            user_id: userId
        },
        success: function(response) {
            const userData = JSON.parse(response);
            const confirmModal = document.getElementById('confirmModal');
            const modalTitle = confirmModal.querySelector('.modal-title');
            const modalBody = confirmModal.querySelector('.modal-body');
            const modalAction = confirmModal.querySelector('.btn-danger');
            modalTitle.textContent = 'Delete Confirmation';
            modalBody.textContent = 'Are you sure you want to delete ' + userData[0].firstname + ' ' + userData[0].lastname + '?'; // Встановлюємо ім'я користувача у модальному вікні
            modalAction.textContent = 'Delete';
            $('#confirmModal').modal('show');
        },
        error: function(xhr, status, error) {
            console.error('Error:', error);
        }
    });

    // Обробник клікання на кнопку підтвердження видалення
    $('#confirmBtn').click(function() {
        doAction(userId, 'delete');
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
    $(userModal).data('button-id', buttonId).data('id', null).modal('show');
});

// Обробник події клікання на кнопку редагування користувача
$(document).on('click', '.editBtn', function() {
    let buttonId = 2;
    let userId = $(this).data('id');
    const userModal = document.getElementById('userModal');
    const modalTitle = userModal.querySelector('.modal-title');
    modalTitle.textContent = 'Update';
    editUserData(userId,buttonId);
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
    let action = (buttonId === 1) ? 'addUser' : 'editUser';
    let requestData = {
        action: action,
        firstName: firstName,
        lastName: lastName,
        status: status,
        role: role
    };

    // Додаємо userId до requestData, якщо buttonId !== 1
    if (buttonId !== 1) {
        requestData.userId = userId;
    }

    $.ajax({
        url: 'src/Controllers/MainController.php',
        method: 'POST',
        dataType: 'json',
        data: requestData,
        success: function (response) {
            if (response.status) {
                console.log(response.message);
                $('#userModal').modal('hide');
                loadContent();
                clearFormFields();
            } else {
                // Виникла помилка
                $('#error-message').text(response.message).show();
            }

        },
        error: function (xhr, status, error) {
            console.error('AJAX Error:', error);
        }
    });
});

// Обробник події клікання на кнопку видалення користувача
$(document).on('click', '.deleteBtn', function() {
    const userId = $(this).data('id');
    deleteFormField(userId);
});

// Обробник події клікання на кнопку збереження вибраних дій з користувачами
$('.buttonOk').click(function(){
    // Отримання значення action з вибраного елемента <select>
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
            modalBody.textContent = 'Are you sure you want to delete this users?'; // Встановлюємо ім'я користувача у модальному вікні
            modalAction.textContent = 'Delete';
            $('#confirmModal').modal('show'); // Показуємо модальне вікно підтвердження
            // Обробник клікання на кнопку підтвердження видалення
            $('#confirmBtn').click(function () {
                doAction(selectedUsers, action); // Викликаємо функцію для видалення користувача
                $('#confirmModal').modal('hide'); // Ховаємо модальне вікно підтвердження
            });
        }else {
            deleteFormField(selectedUsers[0]);
        }

    }else {
        doAction(selectedUsers, action);
    }


});

// Функція для очищення полів форми
function clearFormFields() {
    $('#firstName').val('');
    $('#lastName').val('');
    $('#role').val('');
    $('#status').prop('checked', false);
}

// Обробник події клікання на кнопку закриття модального вікна
$(document).on('click', '.close', function() {
    $('#userModal').modal('hide');
    $('#error-message').hide();
});