<?php foreach($win_users as $user): ?>
<h1>Поздравляем с победой</h1>
<p>Здравствуйте, <?= $user["user.name"]; ?></p>
<p>Ваша ставка для лота <a href="lot.php?id=<?= $user["id"]; ?>"><?= $user["title"]; ?></a> победила.</p>
<p>Перейдите по ссылке <a href="my-bets.php">мои ставки</a>,
чтобы связаться с автором объявления</p>
<small>Интернет-Аукцион "YetiCave"</small>
<?php endforeach; ?>
