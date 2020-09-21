<form action="" method="post" class="loginForm">
    <h1>Login</h1>
    <div class="form-group row">
        <label class="col-sm-2 col-form-label">Email</label>
        <div class="col-sm-10">
            <input id="i_email" type="text" name="email" class="form-control">
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-2 col-form-label">Password</label>
        <div class="col-sm-10">
            <input id="i_password" type="password" name="password" class="form-control">
        </div>
    </div>
    <div class="buttons">
        <button type="button" to="CT<?= time() ?>" class="btn btn-success" onclick="SYS.login(this);" > Login </button>
        <div id="CT<?= time() ?>" class="CT1"></div>
    </div>
</form>