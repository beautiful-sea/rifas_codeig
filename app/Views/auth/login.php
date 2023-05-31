<div class="form-content">
    <h1>Login</h1>
    <form action="<?php echo base_url('login')?>" method="post">
        <label for="">
            Usuário
            <input type="text" name="email" />
        </label>
        <label for="">
            Senha
            <input type="password" name="password">
        </label>

        <input type="submit" value="Acessar">
    </form>
</div>
