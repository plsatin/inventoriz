<div class="modal" id="login-form-dialog" tabindex="-1" role="dialog" aria-labelledby="login-form-label" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="login-form-label">
                    Вход пользователя
                </h4>
            </div>
            <div class="modal-body">
                <form id="login-form">
                    <div class="form-group">
                        <label for="email">Имя пользователя</label>
                        <input type="email" class="form-control" id="email" name="email" placeholder="Имя пользователя" required>
                    </div>
                
                    <div class="form-group">
                        <label for="password">Пароль</label>
                        <input type="password" class="form-control" id="password" name="password" placeholder="Пароль" required>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">
                            Вход
                        </button>
                        <button type="button" class="btn btn-info" data-dismiss="modal">
                            Отмена
                        </button>
                    </div>
                </form>
            </div>
            <div class="modal-footer">

            </div>
        </div>
    </div>
</div>