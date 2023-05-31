<div class="background_div" style="background-image: url('<?php echo base_url()?>/public/images/<?php echo $images[0]?$images[0]:'default.png'?>');background-size: cover;-webkit-filter: blur(5px);
            -moz-filter: blur(53px);
            -o-filter: blur(53px);
            -ms-filter: blur(53px);
            filter: blur(53px);"></div>
<section class="raffle_page raffle_auto" >

    <div class="container">

        <div class="raffle-page-left">

            <div class="background_div"></div>

            <div style="--swiper-navigation-color: #fff; --swiper-pagination-color: #fff" class="swiper mySwiper2">
                <div class="swiper-wrapper">
                    <?php if($images):?>
                        <?php foreach($images as $img): ?>
                            <div class="swiper-slide">
                                <img src="<?php echo base_url('public/images')?>/<?php echo $img ?>" />
                            </div>
                        <?php endforeach ?>

                    <?php else: ?>

                        <div class="swiper-slide">
                            <img src="<?php echo base_url('public/images')?>/default.png" />
                        </div>

                    <?php endif ?>
                </div>
                <div class="swiper-pagination"></div>
                <div class="swiper-button-next"></div>
                <div class="swiper-button-prev"></div>
                
            </div>
            <div thumbsSlider="" class="swiper mySwiper">
                <div class="swiper-wrapper">
                    
                    <?php if($images):?>
                        <?php foreach($images as $img): ?>
                            <div class="swiper-slide">
                                <img src="<?php echo base_url('public/images')?>/<?php echo $img ?>" />
                            </div>
                        <?php endforeach ?>

                    <?php else: ?>
                        <div class="swiper-slide">
                            <img src="<?php echo base_url('public/images')?>/default.png" />
                        </div>

                    <?php endif ?>
                    
                </div>
            </div>

        </div>
        <div class="raffle-page-right">
            <div class="raffle-desc">
                <h1><?php echo $raffle->title ?></h1>
                <div class="progress-sell">
                    <div class="progress">
                        <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: <?php echo $raffle->percent_level ?>%" aria-valuenow="10" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
                <div class="raffle-share-price">
                    <div class="share-icons">
                        <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo base_url()?>/<?php echo $raffle->slug?>" class="share-icon share-facebook" target="_blank">
                            <i class="fa-brands fa-facebook"></i>
                        </a>
                        <a href="https://t.me/share/url?url=<?php echo base_url()?>/<?php echo $raffle->slug?>&text=<?php echo urlencode($raffle->title)?>" class="share-icon share-telegram" target="_blank">
                            <i class="fa-brands fa-telegram"></i>
                        </a>
                        <a href="https://twitter.com/share?url=<?php echo base_url()?>/<?php echo $raffle->slug ?>" class="share-icon share-twitter" target="_blank">
                            <i class="fa-brands fa-twitter"></i>
                        </a>
                        <a href="https://api.whatsapp.com/send/?text=<?php echo urlencode($raffle->title)?>+<?php echo base_url()?>/<?php echo $raffle->slug ?>&type=custom_url&app_absent=0" target="_blank" class="share-icon share-whatsapp">
                            <i class="fa-brands fa-whatsapp"></i>
                        </a>
                    </div>
                    <div class="raffle-desc-price">
                        <p>R$ <?php echo number_format($raffle->price,2,',','.') ?></p>
                    </div>
                </div>
                <?php if(preg_match('/[1-9]/', $raffle->draw_date)): ?>   
                    <div class="raffle-desc-data-draw">
                        <p>Data do sorteio</p>
                        <p><i class="fa-solid fa-calendar-days"></i> &nbsp; <?php echo date('d/m/Y à\s H:i',strtotime($raffle->draw_date)) ?></p>
                    </div>           
                <?php endif ?>
              
                <div class="raffle-desc-description">
                    <?php echo $raffle->description ?>
                </div>
            </div>
            <div class="raffle-body">
                <div class="raffle-body-header">
                    <h3>NÚMEROS AUTOMÁTICOS</h3>
                    <p>O site escolhe os números para você</p>
                </div>
                <div class="raffle-body-body">

                    <?php if($raffle->packs):?>

                        <?php 
                            $packs = json_decode($raffle->packs);
                        ?>

                        <div class="raffle-packs-area">
                            <div class="raffle-packs-header">
                                <h4>PACOTE DE NÚMEROS</h4>
                            </div>
                            <div class="raffle-packs-body">
                                <div class="raffle-packs">
                                    <?php foreach($packs as $pack):?>
                                        <?php if ( $quantity_of_avaiable_numbers >= $pack->qnt_numbers): ?>
                                            <button onclick="setPackage(<?php echo $pack->qnt_numbers ?>,<?php echo currencyToDecimal($pack->price) ?>)" class="raffle-pack">
                                                <?php echo $pack->qnt_numbers ?> números por R$
                                                <?php echo number_format(currencyToDecimal($pack->price),2,',','.') ?>
                                            </button>
                                        <?php endif ?>
                                    <?php endforeach ?>
                                </div>
                            </div>

                        </div>


                    <?php endif ?>

                    <p class="quantity_of_numbers"><span class="quantity">1</span>x R$ <?php echo number_format($raffle->price, 2,',','.') ?> = <span class="price">R$ <?php echo number_format($raffle->price,2,',','.') ?></span><span class="discount_price"> / R$ 0,00</span></p>
                    
                    <p>Selecione uma quantidade</p>

                    <div class="select-numbers">
                        <div class="select-numbers-box">
                            <div class="select-number" onclick="increaseQnt(1)">
                                <div class="select-number-top">
                                    01
                                </div>
                                <div class="select-number-bottom">
                                    Selecionar
                                </div>
                            </div>
                            <div class="select-number" onclick="increaseQnt(5)">
                                <div class="select-number-top">
                                    05
                                </div>
                                <div class="select-number-bottom">
                                    Selecionar
                                </div>
                            </div>
                            <div class="select-number" onclick="increaseQnt(10)">
                                <div class="select-number-top">
                                    10
                                </div>
                                <div class="select-number-bottom">
                                    Selecionar
                                </div>
                            </div>
                            <div class="select-number" onclick="increaseQnt(15)">
                                <div class="select-number-top">
                                    15
                                </div>
                                <div class="select-number-bottom">
                                    Selecionar
                                </div>
                            </div>
                        </div>

                        <div class="input-number">
                            <div class="decrease" onclick="decreaseQnt()">
                                <button ><i class="fa-solid fa-minus" ></i></button>
                            </div>
                            <form method="POST" id="form_buy">
                                <input type="number" id="qntInput" name="qnt_numbers" value="1" min="1" max="<?php echo $quantity_of_avaiable_numbers?>" required>
                            </form>
                            <div class="increase" onclick="increaseQnt()">
                                <button ><i class="fa-solid fa-plus"></i></button>
                            </div>
                        </div>

                        <div class="buy-button">
                            <button onclick="buyNumbers()">Comprar</button>
                        </div>

                    </div>
                
                </div>
        
            </div>
        </div>
    </div>

</section>

<!-- MODAL TO BUY RAFFLE -->
<div class="modal modal-buy-numbers">
    <div class="modal-content">
        <div class="modal-header">
            <h2 class="title"><i class="fa-solid fa-cart-shopping"></i> CHECKOUT</h2>
            <span onclick="toggleBuyNumbers()">x</span>
        </div>
        <div class="modal-body">
            <p>Para continuar com a compra, preencha os campos abaixo</p>
            <p class="modal-body-raffle-title">Pagamento do sorteio: <?php echo $raffle->title ?></p>
            
            <form action="<?php echo base_url('/buy-auto-raffle')?>" method="POST">
                <label for="">
                    Nome completo
                    <input type="text" name="name" required autocomplete="off">
                </label>
                <label for="">
                    Seu telefone
                    <input type="tel" name="phone" class="phone-number" minlength="15" maxlength="15" required autocomplete="off">
                </label>
                <label for="">
                    Confirme seu telefone
                    <input type="tel" name="phone_confirm" class="phone-number" minlength="15" maxlength="15" required autocomplete="off">
                </label>
                <label for="">
                    Seu email (opcional)
                    <input type="text" name="email" autocomplete="off">
                </label>
     
                <input type="hidden" name="numbers">
                <input type="hidden" name="raffle_id" value="<?php echo $raffle->id ?>">

                <p class="modal-body-raffle-term">Ao clicar em finalizar, você está de acordo com o regulamento do sorteio.</p>
                <input type="submit" value="FINALIZAR">
            </form>

        </div>
    </div>
</div>
<?php if($raffle->wp_group):?>
    <div class="wp_group">
        <a href="<?php echo $raffle->wp_group ?>" target="_blank">
            <img src="<?php echo base_url('/public/img/whatsapp.png')?>" alt="Grupo no Whatsapp">
        </a>
    </div>
<?php endif  ?>

<!-- Swiper JS -->
<script src="https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.js"></script>
<script>
    var swiper = new Swiper(".mySwiper", {
        spaceBetween: 1,
        slidesPerView: 4,
        freeMode: true,
        watchSlidesProgress: true,
        
    });
    var swiper2 = new Swiper(".mySwiper2", {
        spaceBetween: 10,
        navigation: {
            nextEl: ".swiper-button-next",
            prevEl: ".swiper-button-prev",
        },
        thumbs: {
            swiper: swiper,
        },
        pagination: {
          el: ".swiper-pagination",
          clickable: true,
        },
    });
</script>

<script>

    /* Vars */
    let quantity_of_avaiable_numbers = <?php echo $quantity_of_avaiable_numbers ?>;
    qnt_numbers = 1;
    let inputElement = document.querySelector('#qntInput');
    let price = <?php echo $raffle->price ?>;
    let discount_status = <?php echo $raffle->discount_status ?? false?>;
    let discount_quantity = <?php echo $raffle->discount_quantity ?? 0 ?>;
    let discount_price = <?php echo $raffle->discount_price ?? 0?>;


    /* Events */
    document.querySelector('#qntInput').addEventListener('change',increaseInput);
    document.querySelector('#qntInput').addEventListener('keyup',increaseInput);

    
    /* Functions */
    function increaseInput(){
        qnt_numbers = inputElement.value;
        render();
    }

    function setPackage(nmr, d_price) {
        qnt_numbers = nmr;
        let originalPrice = qnt_numbers * price;

        console.log(originalPrice);

        document.querySelector('span.quantity').innerText = qnt_numbers;
        document.querySelector('span.price').innerText = originalPrice.toLocaleString('pt-br',{style: 'currency', currency: 'BRL'});
        document.querySelector('#qntInput').value = nmr;
        document.querySelector('span.price').style.textDecoration = 'line-through';
        document.querySelector('span.discount_price').innerText = d_price.toLocaleString('pt-br',{style: 'currency', currency: 'BRL'});
        document.querySelector('span.discount_price').style.display = 'inline-block';

        return false
    }

    function decreaseQnt(){
        qnt_numbers--;
        render();
    }
    function increaseQnt(qnt = 1){

        if (quantity_of_avaiable_numbers == 0){
            $.notify('Os números já foram esgotados','error');
        }
        
        qnt_numbers = qnt_numbers + parseInt(qnt);
      
        render();
    }

    function render(){
       
        if(qnt_numbers > quantity_of_avaiable_numbers){
            qnt_numbers = quantity_of_avaiable_numbers;
        }
        if(qnt_numbers <= 0){
            qnt_numbers = 1;
        }
        inputElement.value = qnt_numbers;
        let totalPrice = qnt_numbers * price;
        document.querySelector('span.quantity').innerText = qnt_numbers;
        document.querySelector('span.price').innerText = totalPrice.toLocaleString('pt-br',{style: 'currency', currency: 'BRL'});

        /* Verifica se possui desconto */
        if(discount_status){
            if(qnt_numbers >= discount_quantity) {

                totalPrice = qnt_numbers * discount_price;
                document.querySelector('span.price').style.textDecoration = 'line-through';
                document.querySelector('span.discount_price').innerText = totalPrice.toLocaleString('pt-br',{style: 'currency', currency: 'BRL'});
                document.querySelector('span.discount_price').style.display = 'inline-block';
                document.querySelector('span.price').style.display = 'inline-block';
            } else{
                document.querySelector('span.price').style.textDecoration = 'none';
                document.querySelector('span.discount_price').style.display = 'none';
            }
        } else {
            document.querySelector('span.price').style.textDecoration = 'none';
            document.querySelector('span.discount_price').style.display = 'none';
        }
        
    }

    function buyNumbers(){
        document.querySelector('.modal.modal-buy-numbers').classList.toggle('active');
        document.querySelector('input[name=numbers').value = document.querySelector('#qntInput').value;
    }

    function toggleBuyNumbers(){
        document.querySelector('.modal.modal-buy-numbers').classList.toggle('active');
    }



    function setMainImage(obj){
        let img = obj.getAttribute('data-image');
        document.querySelector('.raffle-main-image').style.backgroundImage = `url('${img}')`;
    }
    







</script>