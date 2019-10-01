<form class="form container <?php if (count($errors){print "form--invalid"}) ?>" action="sign-up.php" method="post"
      autocomplete="off"> <!-- form
    --invalid -->
    <h2>Регистрация нового аккаунта</h2>
    <div class="form__item <?php if ($errors['email']) {
        print 'form__item--invalid';
    } ?>"> <!-- form__item--invalid -->
        <label for="email">E-mail <sup>*</sup></label>
        <input id="email" type="text" name="email" placeholder="Введите e-mail"
               value="<?= strip_tags(get_post_val('email')); ?>">
        <span class="form__error"><?= $errors['email']; ?></span>
    </div>
    <div class="form__item <?php if ($errors['password']) {
        print 'form__item--invalid';
    } ?>">
        <label for="password">Пароль <sup>*</sup></label>
        <input id="password" type="password" name="password" placeholder="Введите пароль"
               value="<?= strip_tags(get_post_val('password')); ?>">
        <span class="form__error"><?= $errors['password']; ?></span>
    </div>
    <div class="form__item <?php if ($errors['name']) {
        print 'form__item--invalid';
    } ?>">
        <label for="name">Имя <sup>*</sup></label>
        <input id="name" type="text" name="name" placeholder="Введите имя"
               value="<?= strip_tags(get_post_val('name')); ?>">
        <span class="form__error"><?= $errors['name']; ?></span>
    </div>
    <div class="form__item <?php if ($errors['message']) {
        print 'form__item--invalid';
    } ?>">
        <label for="message ">Контактные данные <sup>*</sup></label>
        <textarea id="message" name="message"
                  placeholder="Напишите как с вами связаться"><?= strip_tags(get_post_val('message')); ?></textarea>
        <span class="form__error"><?= $errors['message']; ?></span>
    </div>
    <?php if ($errors): ?>
        <span class="form__error form__error--bottom">Пожалуйста, исправьте ошибки в форме.</span>
    <?php endif; ?>
    <button type="submit" class="button">Зарегистрироваться</button>
    <a class="text-link" href="login.php">Уже есть аккаунт</a>
</form>
