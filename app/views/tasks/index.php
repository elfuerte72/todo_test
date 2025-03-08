<?php require_once '../app/views/inc/header.php'; ?>
<div class="row mb-3">
    <div class="col-md-6">
        <h1>Мои задачи</h1>
    </div>
    <div class="col-md-6">
        <a href="<?php echo SITE_URL; ?>tasks/add" class="btn btn-primary float-right">
            <i class="fas fa-plus"></i> Добавить задачу
        </a>
    </div>
</div>

<div class="card card-body mb-3">
    <h3>Фильтры</h3>
    <form id="filter-form" class="mb-3">
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label for="status">Статус:</label>
                    <select name="status" id="status" class="form-control">
                        <option value="">Все</option>
                        <option value="pending" <?php echo ($data['filters']['status'] == 'pending') ? 'selected' : ''; ?>>Ожидает</option>
                        <option value="in_progress" <?php echo ($data['filters']['status'] == 'in_progress') ? 'selected' : ''; ?>>В процессе</option>
                        <option value="completed" <?php echo ($data['filters']['status'] == 'completed') ? 'selected' : ''; ?>>Завершено</option>
                    </select>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="priority">Приоритет:</label>
                    <select name="priority" id="priority" class="form-control">
                        <option value="">Все</option>
                        <option value="low" <?php echo ($data['filters']['priority'] == 'low') ? 'selected' : ''; ?>>Низкий</option>
                        <option value="medium" <?php echo ($data['filters']['priority'] == 'medium') ? 'selected' : ''; ?>>Средний</option>
                        <option value="high" <?php echo ($data['filters']['priority'] == 'high') ? 'selected' : ''; ?>>Высокий</option>
                    </select>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="search">Поиск:</label>
                    <input type="text" name="search" id="search" class="form-control" placeholder="Поиск по названию или описанию" value="<?php echo $data['filters']['search']; ?>">
                </div>
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Применить фильтры</button>
        <a href="<?php echo SITE_URL; ?>tasks" class="btn btn-secondary">Сбросить</a>
    </form>
</div>

<div id="tasks-container">
    <?php if(empty($data['tasks'])) : ?>
        <div class="alert alert-info">У вас пока нет задач</div>
    <?php else : ?>
        <?php foreach($data['tasks'] as $task) : ?>
            <div class="card card-body mb-3">
                <div class="row">
                    <div class="col-md-8">
                        <h4><?php echo $task->title; ?></h4>
                        <p class="text-muted"><?php echo substr($task->description, 0, 100); ?><?php echo (strlen($task->description) > 100) ? '...' : ''; ?></p>
                        <div class="badge badge-<?php 
                            if($task->status == 'pending') {
                                echo 'warning';
                            } elseif($task->status == 'in_progress') {
                                echo 'info';
                            } else {
                                echo 'success';
                            }
                        ?>"><?php 
                            if($task->status == 'pending') {
                                echo 'Ожидает';
                            } elseif($task->status == 'in_progress') {
                                echo 'В процессе';
                            } else {
                                echo 'Завершено';
                            }
                        ?></div>
                        <div class="badge badge-<?php 
                            if($task->priority == 'low') {
                                echo 'secondary';
                            } elseif($task->priority == 'medium') {
                                echo 'primary';
                            } else {
                                echo 'danger';
                            }
                        ?>"><?php 
                            if($task->priority == 'low') {
                                echo 'Низкий';
                            } elseif($task->priority == 'medium') {
                                echo 'Средний';
                            } else {
                                echo 'Высокий';
                            }
                        ?></div>
                        <?php if($task->due_date) : ?>
                            <div class="badge badge-dark">Срок: <?php echo date('d.m.Y', strtotime($task->due_date)); ?></div>
                        <?php endif; ?>
                        <p class="text-muted mt-2">Создано: <?php echo date('d.m.Y H:i', strtotime($task->created_at)); ?></p>
                    </div>
                    <div class="col-md-4">
                        <div class="btn-group float-right">
                            <a href="<?php echo SITE_URL; ?>tasks/show/<?php echo $task->id; ?>" class="btn btn-sm btn-info">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="<?php echo SITE_URL; ?>tasks/edit/<?php echo $task->id; ?>" class="btn btn-sm btn-warning">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a href="<?php echo SITE_URL; ?>tasks/delete/<?php echo $task->id; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Вы уверены, что хотите удалить эту задачу?');">
                                <i class="fas fa-trash"></i>
                            </a>
                        </div>
                        <div class="mt-3 float-right">
                            <select class="form-control form-control-sm status-change" data-id="<?php echo $task->id; ?>">
                                <option value="pending" <?php echo ($task->status == 'pending') ? 'selected' : ''; ?>>Ожидает</option>
                                <option value="in_progress" <?php echo ($task->status == 'in_progress') ? 'selected' : ''; ?>>В процессе</option>
                                <option value="completed" <?php echo ($task->status == 'completed') ? 'selected' : ''; ?>>Завершено</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<script>
    // AJAX для изменения статуса задачи
    $(document).ready(function() {
        $('.status-change').change(function() {
            var id = $(this).data('id');
            var status = $(this).val();
            
            $.ajax({
                url: '<?php echo SITE_URL; ?>tasks/updateStatus',
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
        });
        
        // AJAX для фильтрации задач
        $('#filter-form').submit(function(e) {
            e.preventDefault();
            
            var status = $('#status').val();
            var priority = $('#priority').val();
            var search = $('#search').val();
            
            // Обновляем URL с параметрами фильтрации
            var url = '<?php echo SITE_URL; ?>tasks';
            var params = [];
            
            if(status) {
                params.push('status=' + status);
            }
            
            if(priority) {
                params.push('priority=' + priority);
            }
            
            if(search) {
                params.push('search=' + search);
            }
            
            if(params.length > 0) {
                url += '?' + params.join('&');
            }
            
            window.location.href = url;
        });
    });
</script>

<?php require_once '../app/views/inc/footer.php'; ?> 