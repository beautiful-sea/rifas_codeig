<div class="form-content">
    <h1>Cadastro</h1>
    <form action="<?php echo base_url('cadastro')?>" method="post">

        <label for="">
            Nome Completo
            <input type="text" name="name" />
        </label>
        <label for="">
            Email
            <input type="email" name="email" />
        </label>
        <label for="">
            Senha
            <input type="text" name="password">
        </label>
        <input type="submit" value="Cadastre-se">
    </form>
    <p>Já possui conta? <a href="<?php echo base_url('login')?>">Acesse</a></p>
</div>