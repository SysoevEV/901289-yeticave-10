<section class="rates container">
    <h2>Мои ставки</h2>
    <table class="rates__list">
        <?php if (isset($_SESSION['user']['id']) && $_SESSION['user']['id'] == $_GET['id']) : ?>
            <?php foreach ($bets as $i => $val) : ?>
                <tr class="rates__item <?php if ($val['user_id_winner'] == $_GET['id']){print "rates__item--win";} ?>">
                    <td class="rates__info">
                        <div class="rates__img">
                            <img src="../<?= $val['img_ref'] ?>" width="54" height="40" alt="Сноуборд"
                                 onerror="this.src = '/uploads/nopicture.png'">
                        </div>
                        <div>
                         <h3 class="rates__title"><a
                                href="lot.php?id=<?= $val['id']; ?>"><?= strip_tags($val['name']) ?></a></h3>
                        <?php if ($val['user_id_winner'] == $_GET['id']) : ?>
                            <p><?=$val['contacts']  ?></p>
                        <?php endif; ?>
                        </div>

                    </td>
                    <td class="rates__category">
                        <?= strip_tags($val['NAME']) ?>
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
                        <?= strip_tags($val['price']); ?> &#x20bd
                    </td>
                    <td class="rates__time">
                        <?= strip_tags(get_passed_time($val["DATE_CREATE"])); ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Вы ещё не делали ставок</p>
        <?php endif; ?>
    </table>
</section>
