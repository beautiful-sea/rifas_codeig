<div class="container settings">
    <div class="container-header">
        <h1 class="title">Configurações</h1>
    </div>
    <div class="container-body">

        <div class="form-left">
            <form method="POST">


                <div class="mercadopago-tuto">
                    <p>Obtenha o <strong> ACCESS_TOKEN </strong> pelas <strong>CREDENCIAIS DE PRODUÇÃO</strong> do Mercado Pago através do link </p>
                    <p><a href="https://www.mercadopago.com.br/settings/account/credentials" target="_blank"><i class="fa-solid fa-arrow-up-right-from-square"></i> https://www.mercadopago.com.br/settings/account/credentials</a></p>
                    <p>Talvez seja necessário iniciar uma nova aplicação, defina o modo do checkout como "CHECKOUT PRO".</p>
                </div>

                <label for="">
                    Mercado Pago ACCESS_TOKEN
                    <input type="text" name="mp_access_token"  value="<?php echo $user->mp_access_token ?>">
                </label>

                <div class="paggue-tuto">
                    <p>Obtenha as chaves CLIENT_KEY e CLIENT_SECRET clicando em   <a href="https://portal.paggue.io/integrations" target="_blank"><i class="fa-solid fa-arrow-up-right-from-square"></i> https://portal.paggue.io/integrations</a></p>
                </div>

                <label for="">
                    Paggue CLIENT_KEY
                    <input type="text" name="paggue_client_key" value="<?php echo $user->paggue_client_key ?>">
                </label>
                <label for="">
                    Paggue CLIENT_SECRET
                    <input type="text" name="paggue_client_secret"  value="<?php echo $user->paggue_client_secret ?>">
                </label>

                <div class="paggue-tuto">
                    <p>Outra etapa importante é inserir o link abaixo na aba Webhook URL. Ele é o responsável pala baixa automática</p>
                    <strong>  https://sorteiosdopedeferro.com.br/api/v1/webhook_paggue </strong>
                    <p>Exatamente como na imagem abaixo, em seguida clique em salvar. </p>
                    <img src="<?php echo base_url('public/img/paggue_tuto.png')?>" alt="">
                </div>


                <div class="container-fluid">
                    <div class="row d-flex">
                        <div class="col-6">
                            <label for="">
                                Tempo de expiração das reservas (minutos)
                                <input type="number" name="expires_time" required value="<?php echo $user->expires_time ?>">
                            </label>
                        </div>
                        <div class="col-6">
                            <label for="">
                                Quantidade de Rifas na Página Inicial
                                <input type="number" name="qtd_raffles_home" required value="<?php echo $user->qtd_raffles_home ?>">
                            </label>
                        </div>
                    </div>
                </div>

                
                <input type="submit" class="success" value="Salvar">
            </form>

        </div>
        <div class="form-right">

            <form method="POST">



            </form>

        </div>


    </div>



</div>