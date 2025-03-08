<?php require_once '../app/views/inc/header.php'; ?>
<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card card-body bg-light mt-5">
            <h2>Добавление задачи</h2>
            <p>Заполните форму для создания новой задачи</p>
            <form action="<?php echo SITE_URL; ?>tasks/add" method="post">
                <div class="form-group">
                    <label for="title">Заголовок: <sup>*</sup></label>
                    <input type="text" name="title" class="form-control <?php echo (!empty($data['title_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['title']; ?>">
                    <span class="invalid-feedback"><?php echo $data['title_err']; ?></span>
                </div>
                <div class="form-group">
                    <label for="description">Описание:</label>
                    <textarea name="description" class="form-control <?php echo (!empty($data['description_err'])) ? 'is-invalid' : ''; ?>" rows="5"><?php echo $data['description']; ?></textarea>
                    <span class="invalid-feedback"><?php echo $data['description_err']; ?></span>
                </div>
                <div class="form-group">
                    <label for="status">Статус:</label>
                    <select name="status" class="form-control">
                        <option value="pending" <?php echo ($data['status'] == 'pending') ? 'selected' : ''; ?>>Ожидает</option>
                        <option value="in_progress" <?php echo ($data['status'] == 'in_progress') ? 'selected' : ''; ?>>В процессе</option>
                        <option value="completed" <?php echo ($data['status'] == 'completed') ? 'selected' : ''; ?>>Завершено</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="priority">Приоритет:</label>
                    <select name="priority" class="form-control">
                        <option value="low" <?php echo ($data['priority'] == 'low') ? 'selected' : ''; ?>>Низкий</option>
                        <option value="medium" <?php echo ($data['priority'] == 'medium') ? 'selected' : ''; ?>>Средний</option>
                        <option value="high" <?php echo ($data['priority'] == 'high') ? 'selected' : ''; ?>>Высокий</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="due_date">Срок выполнения:</label>
                    <input type="date" name="due_date" class="form-control" value="<?php echo $data['due_date']; ?>">
                </div>
                <div class="row">
                    <div class="col">
                        <input type="submit" value="Добавить задачу" class="btn btn-success btn-block">
                    </div>
                    <div class="col">
                        <a href="<?php echo SITE_URL; ?>tasks" class="btn btn-light btn-block">Отмена</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<?php require_once '../app/views/inc/footer.php'; ?> 