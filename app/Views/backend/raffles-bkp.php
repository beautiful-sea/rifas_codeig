<div class="container raffles">
    <div class="container-header">
        <h1 class="title">Todas as rifas</h1>
        <div class="row">
            <form action="">
                <div class="row-input">
                    <label for="">
                        <input type="text" name="search" placeholder="Pesquise pelo título da rifa" value="<?php echo $search ?>" autocomplete="off">
                    </label>
                </div>
              
                <div class="row-input">
                    <label for="">
                        <select name="c" id="">
                            <option value="">Todas</option>
                            <?php if($categories):?>
                                <?php foreach($categories as $c): ?> 
                                    <option value="<?php echo $c->id ?>" <?php echo $id_category == $c->id?'selected':''?>><?php echo $c->title ?></option>
                                <?php endforeach ?>
                            <?php endif ?>
                        </select>
                    </label>
                </div>

                <div class="row-input">
                    <label for="">
                        <select name="type" id="">
                            <option value="">Todas</option>
                            <option value="active" <?php echo $type == 'active'?'selected':''?> >Ativa</option>
                            <option value="inactive" <?php echo $type == 'inactive'?'selected':''?>>Inativa</option>
                        </select>
                    </label>
                </div>

                <button><i class="fa-solid fa-magnifying-glass"></i> Buscar</button>
            </form>
        </div>
    </div>
    <div class="container-body">
        <div class="raffles">

           <?php if($raffles):?>
                <?php foreach($raffles as $raffle):?>

                    <?php 
                        $images = json_decode($raffle->images);
                    ?>
                    <div class="raffle-box">
                        <div class="raffle-box-content">

                            <div class="raffle-img" style="background-image: url('<?php echo base_url('public/images')?>/<?php echo $images[0] ?? 'default.png'?>');background-size: cover;background-position: center;">
                            </div>

                            <div class="raffle-desc">

                                <h3><a href="<?php echo base_url()?>/<?php echo $raffle->slug ?>" target="_blank"><?php echo $raffle->title ?> <span> <i class="fa-solid fa-arrow-up-right-from-square"></i> Demonstração </span> </a></h3>
                                
                                
                                <p>R$ <?php echo number_format($raffle->price,2,',','.') ?></p>
                                <p><?php echo number_format($raffle->number_of_numbers,0,'','.') ?> cotas</p>
                                <?php if(preg_match('/[1-9]/', $raffle->draw_date)): ?>                                
                                    <p>Data do sorteio: <?php echo date('d/m/Y H:i', strtotime($raffle->draw_date)) ?></p>                                    
                                <?php endif ?>
                                
                                <div class="raffle-desc-tax">
                                    <p class="paid_numbers"><?php echo number_format($raffle->paid_total,0,'','.') ?> PAGOS <span>/ R$ <?php echo number_format($raffle->paid, 2,',','.')?> de R$ <?php echo number_format($raffle->total, 2,',','.')?></span></p>
                                    <p class="free_numbers"><?php echo number_format($raffle->free_total,0,'','.') ?> RESTANTES <span>/ R$ <?php echo number_format($raffle->free, 2,',','.')?></span> </p>

                                    <p class="price_rate">TAXA: R$ <?php echo number_format($raffle->payment_price,2,',','.') ?></p>
                                    
                                </div>
                                

                                <?php if($raffle->parcial == 0): ?>  

                                    <a class="raffle-active" style="color: green" href="<?php echo base_url('dashboard/toggle-parcial')?>/<?php echo $raffle->id ?>"><i style="color: green" class="fa-solid fa-toggle-off"></i> Ativar Parcial</a>

                                    <?php else: ?>

                                        <a class="raffle-active" style="color: #b24848" href="<?php echo base_url('dashboard/toggle-parcial')?>/<?php echo $raffle->id ?>"><i style="color: #b24848" class="fa-solid fa-toggle-on"></i> Desativar Parcial</a>

                                <?php endif ?>

                                <div class="progress-sell">
                                    <div class="progress">
                                        <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: <?php echo $raffle->percent_level ?>%" aria-valuenow="10" aria-valuemin="0" aria-valuemax="100">
                                            <?php echo $raffle->percent_level ?>%
                                        </div>
                                    </div>
                                </div>
                            </div>
                           
                           
                            <div class="raffle-actions">

                                <div class="raffle-controls">
                                    
                                    <?php if($raffle->payment_status == 0): ?>  

                                        <a class="raffle-payment" href="#"  data-payment='<?php echo htmlspecialchars(json_encode($raffle->payment))?>' data-raffle-id="<?php echo $raffle->id ?>" onclick="setPayment(this)"><i class="fa-solid fa-dollar-sign"></i> Pagar</a>
                                       
                                    <?php else: ?>

                                        <?php if($raffle->status != 2): ?>

                                            <?php if($raffle->status == 0):?>
                                                <a class="raffle-active" href="<?php echo base_url('dashboard/toggle-raffle')?>/<?php echo $raffle->id ?>"><i class="fa-solid fa-toggle-off"></i> Ativar</a>
                                            <?php else: ?>
                                                <a class="raffle-inactive" href="<?php echo base_url('dashboard/toggle-raffle')?>/<?php echo $raffle->id ?>"><i class="fa-solid fa-toggle-on"></i> Desativar</a>

                                            <?php endif ?>

                                        <?php endif ?>
                                     
                                       
                                        <a class="raffle-info" href="<?php echo base_url('dashboard/pedidos')?>?id_raffle=<?php echo $raffle->id ?>" target="_blank"><i class="fa-solid fa-list"></i> Pedidos</a>

                                    <?php endif ?>

                                    <a class="raffle-edit" href="<?php echo base_url('dashboard/rifas/editar')?>/<?php echo $raffle->id ?>" class="raffle-edit" data-raffle=""><i class="fa-solid fa-pen-to-square"></i> Editar</a>
                                    <a class="raffle-delete" href="<?php echo base_url('dashboard/rifas/excluir')?>/<?php echo $raffle->id ?>" class="raffle-trash" onclick="return confirm('Tem certeza que deseja excluir a rifa e todos os seus pedidos?')"><i class="fa-solid fa-trash"></i> Excluir</a>


                                </div>
                                
                              
                            
                            </div>
                        </div>
                        
                    </div>
                <?php endforeach ?>
           <?php endif?>
           <?php echo $pager->links() ?>

        </div>
    </div>
</div>


<!-- 

<div class="modal modal-raffle-orders">

    <div class="modal-content">
        <div class="modal-header">
            Pedidos
            <span>x</span>
        </div>
        <div class="modal-body">

        </div>
    </div>

</div>

-->


<div class="modal payment">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Efetuar pagamento</h2>
            <span onclick="togglePayment()">x</span>
        </div>
        <div class="modal-body">
            <p class="payment-info">
                Copie o código e cole no aplicativo do seu banco para efetuar o pagamento com PIX. Em seguida, aguarde alguns instantes até que seu pagamento seja reconhecido.
            </p>
            <div class="payment-qrcode">
                <img src='' />
            </div>
            <div class="ticket__code">
                <div class="ticket__plaincode">
                    <span class="ticket__small-text">Código de pagamento</span>
                    <div class="ticket__plaincode-value">
                        <input class="ticket__extralarge-text--lighter" readonly>
                        

                        <i class="fa-solid fa-copy copy-value" onclick="copyText()"></i>
                        
                    </div>
                    <!--<a href="" class="payment_url">IR PARA O MERCADO PAGO (Opcional)</a>-->
                </div>
            </div>
          
            
        </div>
    </div>
</div>

<?php if(isset($_GET['status']) && $_GET['status'] == 'approved'): ?>

    <div class="modal thanks active">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Confirmação de pagamento</h2>
                <span onclick="toggleModalThanks()">x</span>
            </div>
            <div class="modal-body">
                <div class="check-icon">
                    <i class="fa-solid fa-circle-check fa-4x"></i>
                    <h3>Confirmação de pagamento</h3>
                    <p>Já confirmamos seu pagamento e já aprovamos sua campanha. Boa sorte!</p>
                </div>
                <a href="https://www.instagram.com/nandorifas_/" class="wp_group_url">NOSSA PÁGINA NO INSTAGRAM</a>
                
            </div>
        </div>
    </div>

<?php endif  ?>


<script>
    function togglePayment(){
        document.querySelector('.modal.payment').classList.toggle('active');
    }

    function setPayment(obj){
        
        let raffle_id = obj.getAttribute('data-raffle-id');

        let payment = JSON.parse(obj.getAttribute('data-payment'));

        document.querySelector('.payment-qrcode img').setAttribute('src','data:image/jpeg;base64,'+payment.image);
        document.querySelector('.ticket__extralarge-text--lighter').value = payment.qrcode;
        document.querySelector('.copy-value').setAttribute('data-value', payment.qrcode);
        //document.querySelector('.payment_url').setAttribute('href',payment.url);
        //document.querySelector('.payment_url').setAttribute('target','_blank');

    
        togglePayment();

        /* Começa a buscar no banco de dados a cada minuto para ver se o pagamento foi reconhecido */
        
        refreshInterval = setInterval( async ()=>{
           let res = await fetch(`<?php echo base_url('api/buscar-pagamento')?>`, {
            method: 'POST',
            headers : {
                'Content-Type': 'application/json; charset=utf-8'
            },
            body: JSON.stringify({raffle_id})
           });

           res = await res.json();

           
            if(res.status == 'approved'){
                /* Pedido aprovado, troca a tela pra obrigado, e pede pro cliente entrar no grupo */
                window.location = `<?php echo current_url()?>?status=approved`;
            }

            if(res.error){
                $.notify('Pedido não encontrado','error');
                clearInterval(refreshInterval);
            }

        }, 1000);
    }


    function copyText(){
        textoCopiado = document.querySelector(".ticket__extralarge-text--lighter");
        textoCopiado.select();
        textoCopiado.setSelectionRange(0, 99999);
        document.execCommand("copy");
        alert('Código pix copiado com sucesso, agora vá para seu banco para efetuar o pagamento com pix.');
    }

    function toggleModalThanks(){
        document.querySelector('.modal.thanks').classList.toggle('active');
        clearInterval(refreshInterval);
    }

</script>