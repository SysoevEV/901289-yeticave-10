<section class="lot-item container">
    <h2><?= strip_tags($lot_data['name']) ?></h2>
    <div class="lot-item__content">
        <div class="lot-item__left">
            <div class="lot-item__image">
                <img src="../<?= $lot_data['img_ref'] ?>" width="730" height="548" alt=""
                     onerror="this.src = '/uploads/nopicture.png'">
            </div>
            <p class="lot-item__category">Категория: <span><?= strip_tags($lot_data['NAME']) ?></span></p>
            <p class="lot-item__description"><?= $lot_data['description'] ?></p>
        </div>
        <div class="lot-item__right">
            <?php if ($show_bet_block) : ?>
                <div class="lot-item__state">
                    <?php $hh_mm = over_date($lot_data['date_finish']); ?>
                    <div
                        class="lot-item__timer timer <?php if ($hh_mm["remain_hours"] < 1) print "timer--finishing" ?>">
                        <?= $hh_mm["remain_hours"] . " : " . $hh_mm["remain_minutes"]; ?>
                    </div>
                    <div class="lot-item__cost-state">
                        <div class="lot-item__rate">
                            <span class="lot-item__amount">Текущая цена</span>
                            <span class="lot-item__cost"><?=format_price($lot_data['start_price']) ?></span>
                        </div>
                        <div class="lot-item__min-cost">
                            Мин. ставка
                            <span><?= strip_tags($lot_data['start_price'] + $lot_data['bet_step']); ?></span>
                        </div>
                    </div>
                    <form class="lot-item__form" action="lot.php?id=<?= $lot_data['id']; ?>" method="post"
                          autocomplete="off">
                        <p class="lot-item__form-item form__item <?php if ($errors['cost']) {
                            print "form__item--invalid";
                        } ?>">
                            <label for="cost">Ваша ставка</label>
                            <input id="cost" type="text" name="cost" placeholder="">
                            <span class="form__error"><?= strip_tags($errors['cost']); ?></span>
                        </p>
                        <button type="submit" class="button">Сделать ставку</button>
                    </form>
                </div>
            <?php endif; ?>
                <div class="history">
                    <?php if (!$lot_active ) : ?>
                    <h3 style="color:lightslategray; border: 1px solid lightslategray; text-align: center">Торги окончены</h3>
                    <?php endif; ?>
                    <h3>История ставок:<?php if(!$bets ){print " на данный лот не было ставок";}?></h3>
                    <table class="history__list">
                        <?php foreach ($bets as $i => $val): ?>
                            <tr class="history__item">
                                <td class="history__name"><?= strip_tags($val["username"]); ?></td>
                                <td class="history__price"><?= strip_tags(format_price(($val["price"]))); ?></td>
                                <td class="history__time"><?= get_passed_time($val["date_create"]); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
        </div>
    </div>
</section>

