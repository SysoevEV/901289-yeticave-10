<form class="form container <?php if (count($errors){print "form--invalid"}) ?>" action="login.php" method="post">
    <!-- form--invalid -->
    <?php if (isset($_GET['need_auth'])) : ?>
        <p style="color:red">Для возможности добавления лота необходимо авторизоваться на сайте</p>
    <?php endif; ?>
    <h2>Вход</h2>
    <?php if (array_search("Неверный пароль" , $errors) || array_search("Такой пользователь не найден" , $errors) ): ?>
        <h4 style="color:red;">Вы ввели неверный email/пароль.</h4>
    <?php endif; ?>
    <div class="form__item <?php if ($errors['email']) {
        print 'form__item--invalid';
    } ?>"> <!-- form__item--invalid -->
        <label for="email">E-mail <sup>*</sup></label>
        <input id="email" type="text" name="email" placeholder="Введите e-mail"
               value="<?= strip_tags(get_post_val('email')); ?>">
        <span class="form__error"><?= $errors['email']; ?></span>
    </div>
    <div class="form__item form__item--last <?php if ($errors['password']) {
        print 'form__item--invalid';
    } ?>">
        <label for="password">Пароль <sup>*</sup></label>
        <input id="password" type="password" name="password" placeholder="Введите пароль"
               value="<?= strip_tags(get_post_val('password')); ?>">
        <span class="form__error"><?= $errors['password']; ?></span>
    </div>
    <button type="submit" class="button">Войти</button>
</form>
