<div class="container my-requests">

    <div class="my-requests-content">
        <div class="my-requests-content-header">
            <h2>MEUS PEDIDOS</h2>
            <p><?php echo $customer->phone ?> <a href="#" onclick="toggleMyRequestModal()">Não é você?</a></p>
        </div>
        <div class="my-requests-content-body">

            <div class="table">
                <table>
                    <thead>
                        <tr>
                            <th>Rifa</th>
                            <th></th>
                            <th>Data do pedido</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Expira em</th>
                            <th style="text-align: center">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($orders as $o): ?>

                            <?php 
                                $images = json_decode($o->raffle->images);
                                
                                if(isset($images[0])){
                                    $image = base_url('public/images') .'/'.$images[0];
                                } else{
                                    $image = base_url('public/images') .'/default.png';
                                }
                            ?>

                            <tr data-expires="<?php echo $o->expires_in ?>" data-status="<?php echo $o->status?'paid':'pending'?>">
                                <td style="background-image: url('<?php echo $image ?>');background-position: center;">

                                </td>
                                <td><a href="<?php echo base_url()?>/<?php echo $o->raffle->slug ?>" target="_blank"><?php echo $o->raffle->title ?></a></td>
                                
                                <td><?php echo date('d/m/Y à\s H:i', strtotime($o->created_at)) ?></td>

                                <td>R$ <?php echo number_format($o->price,2,',','.')?> </td>
                                <td class="<?php echo $o->status?'status_paid':''?>"><?php echo $o->status?'Pago': 'Aguardando pgto'?> </td>
                                <td class="<?php echo $o->status?'paid': 'expires_in'?>"> <?php echo $o->status?'-': '00:00' ?> </td>
                                <td class="td-actions">
                                    <button class="my-numbers" data-numbers='<?php echo htmlspecialchars($o->numbers) ?>' onclick="showNumbers(this)">Ver números</button>

                                    <?php if ($o->status == 0 &&  (time() < $o->expires_in)): ?>
                                        <button class="buy-numbers" data-wp-group="<?php echo htmlspecialchars($o->raffle->wp_group)?>" data-order="<?php echo $o->id?>" data-payment='<?php echo htmlspecialchars(json_encode($o->payment))?>' onclick="setPayment(this)">Efetuar pagamento</button>
                                    <?php else:?>
                                        <button class="bought-numbers">Efetuar pagamento</button>
                                    <?php endif ?>
                                </td>
                            </tr>

                        <?php endforeach ?>

                    </tbody>

                </table>
            </div>
            
        </div>
    </div>

</div>

<div class="modal my-numbers">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Meus números</h2>
            <span onclick="toggleMyNumbers()">x</span>
        </div>
        <div class="modal-body">
            <div class="numbers">
                
            </div>
        </div>
    </div>
</div>

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

<?php if(isset($status) && $status == 'approved'): ?>

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
                    <p>Já confirmamos seu pagamento, agora é só torcer! Não se esqueça de seguir nossa página no instagram para acompanhar o resultados e os futuros sorteios.</p>
                </div>
                
                
                <a href="https://www.instagram.com/nandorifas_/" class="wp_group_url">NOSSA PÁGINA NO INSTAGRAM</a>
                
            </div>
        </div>
    </div>

<?php endif  ?>



<script>
    

    let date_now = new Date(<?php echo date("Y, n - 1, d, H, i, s") ?>);

    setInterval(() => {


        now = Math.floor(date_now.getTime() / 1000);

        /* Atualiza adicionando mais um segundo */
        date_now.setTime(+date_now.getTime() + 1000);

        document.querySelectorAll('tr[data-expires]').forEach((tr)=>{

            let status = tr.getAttribute('data-status');

            if(status == 'pending') {
                
                let expires = tr.getAttribute('data-expires');

                var diff = expires - now;

                let minutes = Math.floor((diff % 3600 / 60));
                let seconds = (diff % 60);


                if(minutes < 0 && seconds < 0){

                    tr.querySelector('.expires_in').innerText = `00:00`;
                    
                    buyButton =  tr.querySelector('.buy-numbers');

                    if(buyButton){
                        buyButton.classList.add('bought-numbers');
                        buyButton.removeAttribute('onclick');
                        buyButton.classList.remove('buy-numbers');
                    }
                
                    
                } else {

                    minutes =  minutes < 10 ? '0'+minutes : minutes;
                    seconds = seconds < 10 ? '0'+seconds: seconds;

                    tr.querySelector('.expires_in').innerText = `${minutes}:${seconds}`;
                }
                
            } 

        });
    }, 1000);

    let refreshInterval;

    function toggleMyNumbers(){
        document.querySelector('.modal.my-numbers').classList.toggle('active');
    }
    function togglePayment(){
        document.querySelector('.modal.payment').classList.toggle('active');
    }

    function toggleModalThanks(){
        document.querySelector('.modal.thanks').classList.toggle('active');
        clearInterval(refreshInterval);
    }

    function setPayment(obj){
        
        let order_id = obj.getAttribute('data-order');

        let payment = JSON.parse(obj.getAttribute('data-payment'));
        console.log(payment)

        document.querySelector('.payment-qrcode img').setAttribute('src','data:image/jpeg;base64,'+payment.image);
        document.querySelector('.ticket__extralarge-text--lighter').value = payment.qrcode;
        document.querySelector('.copy-value').setAttribute('data-value', payment.qrcode);
        //document.querySelector('.payment_url').setAttribute('href',payment.url);
        //document.querySelector('.payment_url').setAttribute('target','_blank');

    
        togglePayment();

        /* Começa a buscar no banco de dados a cada minuto para ver se o pagamento foi reconhecido */
        
        refreshInterval = setInterval( async ()=>{
           let res = await fetch(`<?php echo base_url('buscar-pagamento')?>`, {
            method: 'POST',
            headers : {
                'Content-Type': 'application/json; charset=utf-8'
            },
            body: JSON.stringify({order_id})
           });

           res = await res.json();

           
            if(res.status == 'approved'){
                /* Pedido aprovado, troca a tela pra obrigado, e pede pro cliente entrar no grupo */
                window.location = `<?php echo current_url()?>?order_id=${order_id}&status=approved`;
            }

            if(res.error){
                $.notify('Pedido não encontrado','error');
                clearInterval(refreshInterval);
            }
        }, 1000);
    }

    function showNumbers(obj){

        let numbers = JSON.parse(obj.getAttribute('data-numbers'));

        document.querySelector('.my-numbers .modal-body .numbers').innerText = "";
        numbers.forEach((n)=>{
            let div = document.createElement('div');
            div.classList.add('number');
            div.innerText = n;
            document.querySelector('.my-numbers .modal-body .numbers').appendChild(div);
        });

        document.querySelector('.modal.my-numbers').classList.add('active');
    }

    function copyText(){
        textoCopiado = document.querySelector(".ticket__extralarge-text--lighter");
        textoCopiado.select();
        textoCopiado.setSelectionRange(0, 99999);
        document.execCommand("copy");
        alert('Código pix copiado com sucesso, agora vá para seu banco para efetuar o pagamento com pix.');
    }

</script>