<main>
<?= $header; ?>
<section class="rates container">
      <h2>Мои ставки</h2>
      <?php if (!empty($bets)): ?>
      <table class="rates__list">
      <?php foreach($bets as $bet): ?>
        <tr class="rates__item">
          <td class="rates__info">
            <div class="rates__img">
              <img src="../<?= $bet["img"]; ?>" width="54" height="40" alt="Сноуборд">
            </div>
            <h3 class="rates__title"><a href="lot.php?id=<?= $bet["id"]; ?>"><?= $bet["title"]; ?></a></h3>
            <p></p>
          </td>
          <td class="rates__category">
          <?= $bet["name_category"]; ?>
          </td>
          <td class="rates__timer">
          <?php $time = get_time_left($bet["date_finish"]) ?>
            <div class="timer <?php if ($time[0] < 1 && $time[0] != 0): ?>timer--finishing <?php elseif($time[0] == 0): ?>timer--end<?php endif; ?>">
              <?php if ($time[0] != 0): ?>
              <?= "$time[0] : $time[1]"; ?>
              <?php else: ?>
                Торги окончены
              <?php endif; ?>
            </div>
          </td>
          <td class="rates__price">
          <?= format_num($bet["price_bet"]); ?>
          </td>
          <td class="rates__time">
          <?= $bet["date_bet"]; ?>
          </td>
        </tr>
        <?php endforeach; ?>
      </table>
      <?php endif; ?>
    </section>
</main>
