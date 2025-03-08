<?php require_once '../app/views/inc/header.php'; ?>
<div class="jumbotron text-center">
    <h1 class="display-4"><?php echo $data['title']; ?></h1>
    <p class="lead"><?php echo $data['description']; ?></p>
    <hr class="my-4">
    <?php if(!isset($_SESSION['user_id'])) : ?>
        <p>Зарегистрируйтесь или войдите, чтобы начать работу с задачами</p>
        <a class="btn btn-primary btn-lg" href="<?php echo SITE_URL; ?>users/register" role="button">Регистрация</a>
        <a class="btn btn-success btn-lg" href="<?php echo SITE_URL; ?>users/login" role="button">Вход</a>
    <?php else : ?>
        <p>Управляйте своими задачами</p>
        <a class="btn btn-primary btn-lg" href="<?php echo SITE_URL; ?>tasks" role="button">Мои задачи</a>
    <?php endif; ?>
</div>
<?php require_once '../app/views/inc/footer.php'; ?> 