<div class="login-form">
    <form method="post">
        <?php if (!empty($error)): ?>
            <div class="alert alert-error"><?= escape($error) ?></div>
        <?php endif; ?>
        <div class="form-group">
            <label for="username">Имя пользователя или Email</label>
            <input type="text" id="username" name="username" value="<?= escape(old('username')) ?>" required>
        </div>
        <div class="form-group">
            <label for="password">Пароль</label>
            <input type="password" id="password" name="password" required>
        </div>
        <button type="submit" class="btn btn-primary">Войти</button>
    </form>
    <div class="login-hint">
        <p><strong>admin</strong> / password — полный доступ</p>
        <p><strong>viewer</strong> / password — только просмотр</p>
    </div>
</div>
