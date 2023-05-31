<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="<?php echo base_url('public/css/login.css')?>" />
    <title> <?php echo $title ?> </title>
</head>
<body>

    <div class="container">
        <div class="container-header">
            <h1>Login</h1>
        </div>
        <div class="container-body">

            <?php if(session()->getFlashdata('msg')):?>
                <div class="error">
                    Dados inválidos
                </div>
            <?php endif ?>
          
            <form method="POST">
                <label for="">
                    E-mail
                    <input type="text" name="email">
                </label>
                <label for="">
                    Senha
                    <input type="password" name="password">
                </label>
                <input type="submit" value="ENTRAR">
            </form>
        </div>
    </div>

</body>
</html>
