
    <section class="promo">
        <h2 class="promo__title">Нужен стафф для катки?</h2>
        <p class="promo__text">На нашем интернет-аукционе ты найдёшь самое эксклюзивное сноубордическое и горнолыжное снаряжение.</p>
        <ul class="promo__list"><!--заполните этот список из массива категорий-->
             <?php foreach ($categories as $i => $val ) : ?>
                    <li class="promo__item promo__item--<?=$val['symbol_code'] ?>">
                        <a class="promo__link" href="pages/all-lots.html"><?= htmlspecialchars($val['name']); ?></a>
                    </li>
             <?php endforeach; ?>

        </ul>
    </section>
    <section class="lots">
        <div class="lots__header">
            <h2>Открытые лоты</h2>
        </div>
        <ul class="lots__list"><!--заполните этот список из массива с товарами-->
            <?php foreach ($items as $i => $val) : ?>
                       <li class="lots__item lot">
                        <div class="lot__image">
                            <img src="<?= $items[$i]["img_ref"] ?>" width="350" height="260" alt="">
                        </div>
                        <div class="lot__info">
                            <span class="lot__category"><?= $items[$i]["name"] ?></span>
                            <h3 class="lot__title"><a class="text-link" href="pages/lot.html"><?= $items[$i]["name"] ?></a></h3>
                            <div class="lot__state">
                                <div class="lot__rate">
                                    <span class="lot__amount">Стартовая цена</span>
                                    <span class="lot__cost"><?= htmlspecialchars(format_price($items[$i]["start_price"])) ?></span>
                                </div>
                                <?php
                                       $hh_mm=over_date($items[$i]["date_finish"]);

                                ?>
                                <div class="lot__timer timer <?php if($hh_mm["remain_hours"]<1) print "timer--finishing" ?>">
                                    <?= $hh_mm["remain_hours"] . " : " . $hh_mm["remain_minutes"];  ?>
                                </div>
                            </div>
                        </div>
                    </li>
             <?php endforeach; ?>

        </ul>
    </section>
