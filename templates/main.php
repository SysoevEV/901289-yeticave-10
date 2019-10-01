<section class="lots">
    <div class="lots__header">
        <h2>Открытые лоты</h2>
    </div>
    <ul class="lots__list"><!--заполните этот список из массива с товарами-->
        <?php foreach ($lots as $i => $val) : ?>
            <li class="lots__item lot">
                <div class="lot__image">
                    <img src="<?= $val["img_ref"] ?> " width="350" height="260" alt=""
                         onerror="this.src = '/uploads/nopicture.png'">
                </div>
                <div class="lot__info">
                    <span class="lot__category"><?= $val["name"] ?></span>
                    <h3 class="lot__title"><a class="text-link"
                                              href="lot.php?id=<?= $val["id"] ?>"><?= $val["NAME"] ?></a></h3>
                    <div class="lot__state">
                        <div class="lot__rate">
                            <span class="lot__amount">Цена</span>
                            <span class="lot__cost"><?= htmlspecialchars(format_price($val["start_price"])) ?></span>
                        </div>
                        <?php
                        $hh_mm = over_date($val["date_finish"]);

                        ?>
                        <div class="lot__timer timer <?php if ($hh_mm["remain_hours"] < 1) print "timer--finishing" ?>">
                            <?= $hh_mm["remain_hours"] . " : " . $hh_mm["remain_minutes"]; ?>
                        </div>
                    </div>
                </div>
            </li>
        <?php endforeach; ?>

    </ul>
</section>
