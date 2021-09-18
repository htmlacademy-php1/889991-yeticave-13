<main>
<?= $header; ?>
<?php $classname = isset($errors) ? "form--invalid" : ""; ?>
<form class="form container <?= $classname; ?>" action="sign-up.php" method="post" autocomplete="off"> <!-- form
    --invalid -->
    <h2>Регистрация нового аккаунта</h2>
    <?php $classname = isset($errors["email"]) ? "form__item--invalid" : ""; ?>
    <div class="form__item <?= $classname; ?>"> <!-- form__item--invalid -->
        <label for="email">E-mail <sup>*</sup></label>
        <input id="email" type="text" name="email" placeholder="Введите e-mail" value="<?= $user['email'] ;?>">
        <span class="form__error"><?= $errors["email"]; ?></span>
    </div>
    <?php $classname = isset($errors["password"]) ? "form__item--invalid" : ""; ?>
    <div class="form__item <?= $classname; ?>">
        <label for="password">Пароль <sup>*</sup></label>
        <input id="password" type="password" name="password" placeholder="Введите пароль" value="<?= $user['password'] ;?>">
        <span class="form__error"><?= $errors["password"]; ?></span>
    </div>
    <?php $classname = isset($errors["name"]) ? "form__item--invalid" : ""; ?>
    <div class="form__item <?= $classname; ?>">
        <label for="name">Имя <sup>*</sup></label>
        <input id="name" type="text" name="name" placeholder="Введите имя" value="<?= $user['name'] ;?>">
        <span class="form__error"><?= $errors["name"]; ?></span>
    </div>
    <?php $classname = isset($errors["message"]) ? "form__item--invalid" : ""; ?>
    <div class="form__item <?= $classname; ?>">
        <label for="message">Контактные данные <sup>*</sup></label>
        <textarea id="message" name="message" placeholder="Напишите как с вами связаться"><?= $user['message'] ;?></textarea>
        <span class="form__error"><?= $errors["message"]; ?></span>
    </div>
    <span class="form__error form__error--bottom">Пожалуйста, исправьте ошибки в форме.</span>
    <button type="submit" class="button">Зарегистрироваться</button>
    <a class="text-link" href="login.php">Уже есть аккаунт</a>
</form>
</main>
