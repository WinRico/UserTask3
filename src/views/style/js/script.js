// Функція для завантаження вмісту
function loadContent() {
    $.ajax({
        url: 'src/Controllers/MainController.php',
        method: "post",
        data:{
            action: "live_contend"
        },
        success: function (data) {
            $('#live_contend').html(data);
        }
    });
}
loadContent();

// Функція для виконання дії з вибраними користувачами
function doAction(selectedUsers, action) {
    $.ajax({
        url: 'src/Controllers/MainController.php',
        method: 'POST',
        dataType: 'json',
        data: {
            action: "actionWithSelectedUsers",
            userIds: selectedUsers,
            actionSelected: action,
        },
        success: function (response) {
            if (response.status) {
                console.log(response.message);
                loadContent();
            } else {
                console.error(response.error);
            }},
        error: function(xhr, status, error) {
            console.error('Error deleting user:', error);
        }
    });
}
// Функція для отримання в форму данних
function getUserData(userId,buttonId) {

    // Відправка AJAX-запиту на сервер для отримання даних про користувача за його ID
    $.ajax({
        url: 'src/Controllers/MainController.php', // URL-адреса маршруту, що повертає дані про користувача
        type: 'POST',
        data: {action: "getUserById",
                    user_id: userId},
        success: function(response) {
            // Заповнення отриманими даними полів форми редагування користувача
            var userData = JSON.parse(response);
            console.log(userData[0].status);
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
            // Обробка помилки, якщо щось пішло не так при отриманні даних
            console.error(error);
            alert('An error occurred while fetching user data.');
        }
    });
}

// Обробник події клікання на кнопку вибору всіх користувачів
$(document).on('click', "#selectAll", function(){
    $(".selectUser").prop('checked', $(this).prop('checked'));
});


// Обробник події клікання на окремого користувача для вибору
$(document).on('click', ".selectUser", function(){
    if($(".selectUser:checked").length === $(".selectUser").length) {
        $("#selectAll").prop('checked', true);
    } else {
        $("#selectAll").prop('checked', false);
    }
});


// Обробник події клікання на кнопку додавання користувача (два випадки)
$("#buttonAdd1, #buttonAdd2").click(function(){
    let buttonId = 1;
    const userModal = document.getElementById('userModal');
    const modalTitle = userModal.querySelector('.modal-title');
    modalTitle.textContent = "Add";
    clearFormFields();
    $(userModal).data('button-id', buttonId).data('id', null).modal('show');
});

// Обробник події клікання на кнопку редагування користувача
$(document).on('click', ".editBtn", function() {
    let buttonId = 2;
    let userId = $(this).data('id');
    const userModal = document.getElementById('userModal');
    const modalTitle = userModal.querySelector('.modal-title');
    modalTitle.textContent = "Update";
    getUserData(userId,buttonId);
});

// Обробник події відправки форми для додавання/редагування користувача
$(document).on('submit', "#userModal", function(event){
    event.preventDefault();

    let firstName = $('#firstName').val();
    let lastName = $('#lastName').val();
    let role = $('#role').val();
    let status = $('#status').is(':checked') ? 1 : 0;
    let buttonId = $(this).data('button-id');
    let userId = $(this).data('id');

    let action = (buttonId === 1) ? "addUser" : "editUser";
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
                // Все пройшло успішно
                alert(response.message);
                $("#userModal").modal('hide');
                loadContent();
                clearFormFields();
            } else {
                // Виникла помилка
                alert(response.message);
            }

        },
        error: function (xhr, status, error) {
            console.error('AJAX Error:', error);
        }
    });
});
// Функція для очищення полів форми
function clearFormFields() {
    $('#firstName').val('');
    $('#lastName').val('');
    $('#role').val('');
    $('#status').prop('checked', false);
}

// Обробник події клікання на кнопку видалення користувача
$(document).on('click', ".deleteBtn", function() {
    let userId = $(this).data('id');
    if (confirm("Are you sure you want to delete this user?")) {
        $.ajax({
            url: 'src/Controllers/MainController.php',
            method: 'POST',
            dataType: 'json',
            data: {
                action: "deleteUser",
                userId: userId
            },
            success: function (response) {
                if (response.status) {
                    console.log(response.message);
                    loadContent();
                } else {
                    console.error(response.error);
                    alert('Failed to delete user. Please try again.');
                }
            },
            error: function(xhr, status, error) {
                console.error('Error deleting user:', error);
            }
        });
    }
});

// Обробник події клікання на кнопку збереження вибраних дій з користувачами
$(".buttonOk").click(function(){
    // Отримання значення action з вибраного елемента <select>
    let actionSelect = $(this).data('select');
    let action = $(actionSelect).val();

    // Перевірка, чи обрано дію
    if(action === "") {
        alert("Please select an action.");
        return false;
    }

    // Перевірка, чи обрано хоча б одного користувача
    if($(".selectUser:checked").length === 0) {
        alert("Please select at least one user.");
        return false;
    }

    let selectedUsers = [];

    // Збір ID обраних користувачів
    $(".selectUser:checked").each(function() {
        let userId = $(this).data('id');
        selectedUsers.push(userId);
    });
    console.log(action);
    // Виклик функції для виконання дії
    if(action === 'delete'){
        if (confirm("Are you sure you want to delete this user?")) {
            doAction(selectedUsers, action);
        }
    }else {
        doAction(selectedUsers, action);
    }


});

// Обробник події клікання на кнопку закриття модального вікна
$(document).on('click', '.close', function() {
    $('#userModal').modal('hide');
});