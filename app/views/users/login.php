<?php require_once '../app/views/inc/header.php'; ?>
<div class="row">
    <div class="col-md-6 mx-auto">
        <div class="card card-body bg-light mt-5">
            <h2>Вход в систему</h2>
            <p>Введите свои данные для входа</p>
            <form action="<?php echo SITE_URL; ?>users/login" method="post">
                <div class="form-group">
                    <label for="email">Email: <sup>*</sup></label>
                    <input type="email" name="email" class="form-control <?php echo (!empty($data['email_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['email']; ?>">
                    <span class="invalid-feedback"><?php echo $data['email_err']; ?></span>
                </div>
                <div class="form-group">
                    <label for="password">Пароль: <sup>*</sup></label>
                    <input type="password" name="password" class="form-control <?php echo (!empty($data['password_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['password']; ?>">
                    <span class="invalid-feedback"><?php echo $data['password_err']; ?></span>
                </div>

                <div class="row">
                    <div class="col">
                        <input type="submit" value="Войти" class="btn btn-success btn-block">
                    </div>
                    <div class="col">
                        <a href="<?php echo SITE_URL; ?>users/register" class="btn btn-light btn-block">Нет аккаунта? Регистрация</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<?php require_once '../app/views/inc/footer.php'; ?> 