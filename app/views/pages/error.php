<?php require_once '../app/views/inc/header.php'; ?>
<div class="jumbotron text-center">
    <h1 class="display-4"><?php echo $data['title']; ?></h1>
    <p class="lead"><?php echo $data['description']; ?></p>
    <hr class="my-4">
    <a class="btn btn-primary btn-lg" href="<?php echo SITE_URL; ?>" role="button">На главную</a>
</div>
<?php require_once '../app/views/inc/footer.php'; ?> 