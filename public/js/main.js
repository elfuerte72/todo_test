/**
 * Основной JavaScript файл для приложения
 */

// Функция для обновления статуса задачи через AJAX
function updateTaskStatus(id, status) {
    $.ajax({
        url: SITE_URL + 'tasks/updateStatus',
        type: 'POST',
        contentType: 'application/json',
        data: JSON.stringify({
            id: id,
            status: status
        }),
        success: function(response) {
            if(response.success) {
                // Обновляем визуальное отображение статуса
                location.reload();
            } else {
                alert(response.message);
            }
        },
        error: function() {
            alert('Произошла ошибка при обновлении статуса');
        }
    });
}

// Функция для фильтрации задач через AJAX
function filterTasks(filters) {
    $.ajax({
        url: SITE_URL + 'tasks/filter',
        type: 'POST',
        contentType: 'application/json',
        data: JSON.stringify(filters),
        success: function(response) {
            if(response.success) {
                // Обновляем список задач
                updateTasksList(response.tasks);
            } else {
                alert(response.message);
            }
        },
        error: function() {
            alert('Произошла ошибка при фильтрации задач');
        }
    });
}

// Функция для обновления списка задач
function updateTasksList(tasks) {
    var container = $('#tasks-container');
    container.empty();
    
    if(tasks.length === 0) {
        container.html('<div class="alert alert-info">Задачи не найдены</div>');
        return;
    }
    
    for(var i = 0; i < tasks.length; i++) {
        var task = tasks[i];
        var statusClass = '';
        var statusText = '';
        
        if(task.status === 'pending') {
            statusClass = 'warning';
            statusText = 'Ожидает';
        } else if(task.status === 'in_progress') {
            statusClass = 'info';
            statusText = 'В процессе';
        } else {
            statusClass = 'success';
            statusText = 'Завершено';
        }
        
        var priorityClass = '';
        var priorityText = '';
        
        if(task.priority === 'low') {
            priorityClass = 'secondary';
            priorityText = 'Низкий';
        } else if(task.priority === 'medium') {
            priorityClass = 'primary';
            priorityText = 'Средний';
        } else {
            priorityClass = 'danger';
            priorityText = 'Высокий';
        }
        
        var html = '<div class="card card-body mb-3">' +
                   '<div class="row">' +
                   '<div class="col-md-8">' +
                   '<h4>' + task.title + '</h4>' +
                   '<p class="text-muted">' + task.description.substring(0, 100) + (task.description.length > 100 ? '...' : '') + '</p>' +
                   '<div class="badge badge-' + statusClass + '">' + statusText + '</div>' +
                   '<div class="badge badge-' + priorityClass + '">' + priorityText + '</div>';
        
        if(task.due_date) {
            var dueDate = new Date(task.due_date);
            var formattedDate = dueDate.getDate() + '.' + (dueDate.getMonth() + 1) + '.' + dueDate.getFullYear();
            html += '<div class="badge badge-dark">Срок: ' + formattedDate + '</div>';
        }
        
        var createdDate = new Date(task.created_at);
        var formattedCreatedDate = createdDate.getDate() + '.' + (createdDate.getMonth() + 1) + '.' + createdDate.getFullYear() + ' ' + createdDate.getHours() + ':' + createdDate.getMinutes();
        
        html += '<p class="text-muted mt-2">Создано: ' + formattedCreatedDate + '</p>' +
                '</div>' +
                '<div class="col-md-4">' +
                '<div class="btn-group float-right">' +
                '<a href="' + SITE_URL + 'tasks/show/' + task.id + '" class="btn btn-sm btn-info"><i class="fas fa-eye"></i></a>' +
                '<a href="' + SITE_URL + 'tasks/edit/' + task.id + '" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></a>' +
                '<a href="' + SITE_URL + 'tasks/delete/' + task.id + '" class="btn btn-sm btn-danger" onclick="return confirm(\'Вы уверены, что хотите удалить эту задачу?\');"><i class="fas fa-trash"></i></a>' +
                '</div>' +
                '<div class="mt-3 float-right">' +
                '<select class="form-control form-control-sm status-change" data-id="' + task.id + '">' +
                '<option value="pending"' + (task.status === 'pending' ? ' selected' : '') + '>Ожидает</option>' +
                '<option value="in_progress"' + (task.status === 'in_progress' ? ' selected' : '') + '>В процессе</option>' +
                '<option value="completed"' + (task.status === 'completed' ? ' selected' : '') + '>Завершено</option>' +
                '</select>' +
                '</div>' +
                '</div>' +
                '</div>' +
                '</div>';
        
        container.append(html);
    }
    
    // Переподключаем обработчики событий
    $('.status-change').change(function() {
        var id = $(this).data('id');
        var status = $(this).val();
        updateTaskStatus(id, status);
    });
}

// Инициализация при загрузке страницы
$(document).ready(function() {
    // Обработчик изменения статуса задачи
    $('.status-change').change(function() {
        var id = $(this).data('id');
        var status = $(this).val();
        updateTaskStatus(id, status);
    });
    
    // Обработчик отправки формы фильтрации
    $('#filter-form').submit(function(e) {
        e.preventDefault();
        
        var status = $('#status').val();
        var priority = $('#priority').val();
        var search = $('#search').val();
        
        var filters = {};
        
        if(status) {
            filters.status = status;
        }
        
        if(priority) {
            filters.priority = priority;
        }
        
        if(search) {
            filters.search = search;
        }
        
        filterTasks(filters);
    });
}); 