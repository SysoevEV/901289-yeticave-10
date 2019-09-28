<section class="rates container">
    <h2>Мои ставки</h2>
    <table class="rates__list">
        <?php foreach ($bets as $i => $val) : ?>
            <tr class="rates__item ">
                <td class="rates__info">
                    <div class="rates__img">
                        <img src="../<?= $val['img_ref'] ?>" width="54" height="40" alt="Сноуборд">
                    </div>
                    <h3 class="rates__title"><a href="lot.php?id=<?= $val['id']; ?>"><?= $val['name'] ?></a></h3>
                </td>
                <td class="rates__category">
                    <?= $val['NAME'] ?>
                </td>
                <?php $hh_mm = over_date($val['date_finish']); ?>
                <?php if ($val['user_id_winner'] == $_GET['id']): ?>
                    <td class="rates__timer">
                        <div class="timer timer--win">Ставка выиграла</div>
                    </td>
                <? elseif (($hh_mm["remain_hours"] + $hh_mm["remain_minutes"]) < 0) : ?>
                    <td class="rates__timer">
                        <div class="timer timer--end">Торги окончены</div>
                    </td>
                <?php else: ?>
                    <td class="rates__timer">
                        <div
                            class="lot-item__timer timer <?php if ($hh_mm["remain_hours"] < 1) print "timer--finishing" ?>">
                            <?= $hh_mm["remain_hours"] . " : " . $hh_mm["remain_minutes"]; ?>
                        </div>
                    </td>
                <?php endif; ?>

                <td class="rates__price">
                    <?= $val['price']; ?>
                </td>
                <td class="rates__time">
                    <?= get_passed_time($val["DATE_CREATE"]); ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</section>
