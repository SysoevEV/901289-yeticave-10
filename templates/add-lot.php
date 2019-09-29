<form class="form form--add-lot container <?php if (count($errors){print "form--invalid"}) ?> " action="add.php"
      method="post" enctype="multipart/form-data"> <!-- form--invalid -->
    <h2>Добавление лота</h2>
    <div class="form__container-two">
        <div class="form__item <?php if ($errors['lot-name']) {
            print 'form__item--invalid';
        } ?>"> <!-- form__item--invalid -->
            <label for="lot-name">Наименование <sup>*</sup></label>
            <input id="lot-name" type="text" name="lot-name" placeholder="Введите наименование лота"
                   value="<?= get_post_val('lot-name'); ?>">
            <span class="form__error"><?= $errors['lot-name']; ?></span>
        </div>
        <div class="form__item  <?php if ($errors['category']) {
            print 'form__item--invalid';
        } ?>">
            <label for="category">Категория <sup>*</sup></label>
            <select id="category" name="category">
                <option>Выберите категорию</option>
                <?php foreach ($categories as $i => $val) : ?>
                    <option <?php if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['category'] == $val['name']) {
                        print 'selected';
                    } ?>><?= $val['name'] ?></option>
                <?php endforeach; ?>
            </select>
            <span class="form__error"><?= $errors['category']; ?></span>
        </div>
    </div>
    <div class="form__item form__item--wide <?php if ($errors['message']) {
        print 'form__item--invalid';
    } ?>">
        <label for="message">Описание <sup>*</sup></label>
        <textarea id="message" name="message"
                  placeholder="Напишите описание лота"><?= get_post_val('message'); ?></textarea>
        <span class="form__error"><?= $errors['message']; ?></span>
    </div>
    <div class="form__item form__item--file  <?php if ($errors['lot-img']) {
        print 'form__item--invalid';
    } ?>">
        <label>Изображение <sup>*</sup></label>
        <div class="form__input-file">
            <input class="visually-hidden" type="file" id="lot-img" name="lot-img" value="">
            <label for="lot-img">
                Добавить
            </label>
            <span class="form__error"><?= $errors['lot-img']; ?></span>
        </div>
    </div>
    <div class="form__container-three">
        <div class="form__item form__item--small  <?php if ($errors['lot-rate']) {
            print 'form__item--invalid';
        } ?>">
            <label for="lot-rate">Начальная цена <sup>*</sup></label>
            <input id="lot-rate" type="text" name="lot-rate" value="<?= get_post_val('lot-rate'); ?>">
            <span class="form__error"><?= $errors['lot-rate']; ?></span>
        </div>
        <div class="form__item form__item--small  <?php if ($errors['lot-step']) {
            print 'form__item--invalid';
        } ?>">
            <label for="lot-step">Шаг ставки <sup>*</sup></label>
            <input id="lot-step" type="text" name="lot-step" value="<?= get_post_val('lot-step'); ?>">
            <span class="form__error"><?= $errors['lot-step']; ?></span>
        </div>
        <div class="form__item  <?php if ($errors['lot-date']) {
            print 'form__item--invalid';
        } ?>">
            <label for="lot-date">Дата окончания торгов <sup>*</sup></label>
            <input class="form__input-date" id="lot-date" type="text" name="lot-date"
                   placeholder="Введите дату в формате ГГГГ-ММ-ДД" value="<?= get_post_val('lot-date'); ?>">
            <span class="form__error"><?= $errors['lot-date']; ?></span>
        </div>
    </div>
    <span class="form__error form__error--bottom">Пожалуйста, исправьте ошибки в форме.</span>
    <button type="submit" class="button">Добавить лот</button>
</form>




