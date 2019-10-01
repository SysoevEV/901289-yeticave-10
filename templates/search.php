<div class="container">
    <section class="lots">
        <?php if (!isset($_GET['search']) && !empty($lots)) : ?>
            <h2><?= $lots[0]['NAME'] ?></h2>
        <?php elseif (empty($lots)) : ?>
            <h2>Ничего не найдено по вашему запросу</h2>
        <?php else : ?>
            <h2>Результаты поиска по запросу: <span><?= $_GET['search']; ?></span></h2>

        <?php endif; ?>
        <ul class="lots__list"><?php $lots_offset = array_slice($lots, $offset, $page_items) ?>
            <?php foreach ($lots_offset as $i => $val) : ?>

                <li class="lots__item lot">
                    <div class="lot__image">
                        <img src="<?= $val['img_ref']; ?>" width="350" height="260" alt="Сноуборд">
                    </div>
                    <div class="lot__info">
                        <span class="lot__category"><?= strip_tags($val['NAME']); ?></span>
                        <h3 class="lot__title"><a class="text-link"
                                                  href="lot.php?id=<?= $val['id']; ?>"><?= strip_tags($val['name']); ?></a>
                        </h3>
                        <div class="lot__state">
                            <div class="lot__rate">
                                <span class="lot__amount">Цена</span>
                                <span class="lot__cost"><?= $val['start_price']; ?><b class="rub">р</b></span>
                            </div>
                            <?php $hh_mm = over_date($val['date_finish']); ?>
                            <div
                                class="lot__timer timer <?php if ($hh_mm["remain_hours"] < 1) print "timer--finishing" ?>">
                                <?= $hh_mm["remain_hours"] . " : " . $hh_mm["remain_minutes"]; ?>
                            </div>
                        </div>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>
    </section>


    <?php if ($pages_count > 1): ?>
        <ul class="pagination-list">

            <li class='pagination-item pagination-item-prev'><a
                    href="search.php?search=<?= $search; ?>&page=<?php if ($cur_page > 1) {
                        print $cur_page - 1;
                    } else {
                        print $cur_page;
                    } ?>">Назад</a></li>
            <?php foreach ($pages as $page): ?>
                <li class="pagination-item <?php if ($page == $cur_page) print "pagination-item-active" ?>">
                    <a href="search.php?search=<?= $search; ?>&page=<?= $page; ?>"><?= $page; ?></a>
                </li>

            <?php endforeach; ?>
            <li class='pagination-item pagination-item-next'><a
                    href="search.php?search=<?= $search; ?>&page=<?php if ($cur_page < $pages_count) {
                        print $cur_page + 1;
                    } else {
                        print $cur_page;
                    } ?>">Вперёд</a></li>
        </ul>
    <?php endif; ?>

</div>
