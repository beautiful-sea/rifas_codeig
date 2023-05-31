<div class="background_div" style="background-image: url('<?php echo base_url() ?>/public/images/<?php echo $images ? $images[0] : 'default.png' ?>');background-size: cover;-webkit-filter: blur(5px);
            -moz-filter: blur(5px);
            -o-filter: blur(5px);
            -ms-filter: blur(5px);
            filter: blur(5px);">
</div>

<section class="raffle_page raffle_auto">

    <?php if ($raffle->payment_status == 0) : ?>
        <div class="demo-alert">
            <h2>MODO DEMONSTRAÇÃO</h2>
            <p>Efetue o pagamento para começar esta campanha.</p>

        </div>
    <?php endif ?>

    <style>
        @media (max-width: 768px){
            .img-principal{
                height: 400px !important;
                max-height: 500px !important;
            }
            
            .swiper-wrapper{
                margin-top: -40px !important;
            }
            
            .raffle-page-right{
                margin-top: -40px !important;
            }
            
            .raffle_page .raffle-desc h1{
                position: relative !important;
                top: 0px !important;
            }
        }
    </style>

    <div class="container">

        <div class="raffle-page-left">
            <div style="--swiper-navigation-color: #fff; --swiper-pagination-color: #fff" class="swiper mySwiper2">
                <div class="swiper-wrapper">
                    <?php if ($images) : ?>
                        <?php foreach ($images as $img) : ?>
                            <div class="swiper-slide">
                                <img class="img-principal" src="<?php echo base_url('public/images') ?>/<?php echo $img ?>" />
                            </div>
                        <?php endforeach ?>

                    <?php else : ?>

                        <div class="swiper-slide">
                            <img src="<?php echo base_url('public/images') ?>/default.png" />
                        </div>

                    <?php endif ?>
                </div>
                <div class="swiper-pagination"></div>
                <div class="swiper-button-next"></div>
                <div class="swiper-button-prev"></div>
            </div>
            <div thumbsSlider="" class="swiper mySwiper">
                <div class="swiper-wrapper">

                    <?php if ($images) : ?>
                        <?php foreach ($images as $img) : ?>
                            <div class="swiper-slide">
                                <img style="height: 100px;" src="<?php echo base_url('public/images') ?>/<?php echo $img ?>" />
                            </div>
                        <?php endforeach ?>

                    <?php else : ?>
                        <div class="swiper-slide">
                            <img src="<?php echo base_url('public/images') ?>/default.png" />
                        </div>

                    <?php endif ?>

                </div>
            </div>

        </div>
        <div class="raffle-page-right ">


            <!-- RAFFLE DESC PC -->
            <div class="raffle-desc raffle-desc-pc">
                <h1><?php echo $raffle->title ?></h1>
                <div class="progress-sell">
                    <div class="progress">
                        <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: <?php echo $raffle->fake_percent_level ?? $raffle->percent_level ?>%" aria-valuenow="10" aria-valuemin="0" aria-valuemax="100">
                            <?php echo $raffle->fake_percent_level ?? $raffle->percent_level ?>%
                        </div>
                    </div>
                </div>
                <div class="raffle-share-price">
                    <div class="share-icons">
                        <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo base_url() ?>/<?php echo $raffle->slug ?>" class="share-icon share-facebook" target="_blank">
                            <i class="fa-brands fa-facebook"></i>
                        </a>
                        <a href="https://t.me/share/url?url=<?php echo base_url() ?>/<?php echo $raffle->slug ?>&text=<?php echo urlencode($raffle->title) ?>" class="share-icon share-telegram" target="_blank">
                            <i class="fa-brands fa-telegram"></i>
                        </a>
                        <a href="https://twitter.com/share?url=<?php echo base_url() ?>/<?php echo $raffle->slug ?>" class="share-icon share-twitter" target="_blank">
                            <i class="fa-brands fa-twitter"></i>
                        </a>
                        <a href="https://api.whatsapp.com/send/?text=<?php echo urlencode($raffle->title) ?>+<?php echo base_url() ?>/<?php echo $raffle->slug ?>&type=custom_url&app_absent=0" target="_blank" class="share-icon share-whatsapp">
                            <i class="fa-brands fa-whatsapp"></i>
                        </a>
                    </div>
                    <div class="raffle-desc-price">
                        <p>R$ <?php echo number_format($raffle->price, 2, ',', '.') ?></p>
                    </div>
                </div>

                

                <?php if (preg_match('/[1-9]/', $raffle->draw_date)) : ?>
                    <div class="raffle-desc-data-draw">
                        <p>Data do sorteio</p>
                        <p><i class="fa-solid fa-calendar-days"></i> &nbsp; <?php echo date('d/m/Y à\s H:i', strtotime($raffle->draw_date)) ?></p>
                    </div>
                <?php endif ?>

                <?php if($raffle->parcial == 1): ?>
                    <div class="row" style="margin-top: 10px;">
                        <div class="option livre">
                            Livre (<?php echo $quantity_of_avaiable_numbers ?>)
                        </div>
                        <div class="option reservado">
                            Reservados (<?php echo $reservados ?>)
                        </div>
                        <div class="option pago">
                            Pago (<?php echo $pagos ?>)
                        </div>
                    </div>
                <?php endif ?>

                <div class="raffle-desc-description">
                    <h3><i class="fa-regular fa-file-lines"></i> DESCRIÇÃO</h3>

                    <div class="raffle-desc-description-area">
                        <?php echo $raffle->description ?>
                    </div>
                </div>

                <?php if (isset($winners) && !empty($winners)) : ?>

                    <div class="raffle-desc-winners">
                        <h3><i class="fa-solid fa-ranking-star"></i> GANHADORES</h3>

                        <div class="raffle-desc-winners-body">
                            <?php foreach ($winners as $winner) : ?>

                                <div class="winner-item">
                                    <img src="<?php echo base_url('public/img') ?>/<?php echo $winner->position ?>.png" alt="">
                                    <p><strong><?php echo $winner->name ?></strong> ganhou com a cota <strong><?php echo $winner->number ?></strong></p>
                                </div>
                            <?php endforeach ?>
                        </div>
                    </div>


                <?php endif ?>

            </div>

            <!-- RAFFLE DESC MOBILE -->
            <div class="raffle-desc raffle-desc-mobile">
                <h1><?php echo mb_strtoupper($raffle->title) ?></h1>
                <div class="progress-sell">
                    <div class="progress">
                        <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: <?php echo $raffle->fake_percent_level ?? $raffle->percent_level ?>%" aria-valuenow="10" aria-valuemin="0" aria-valuemax="100">
                            <?php echo $raffle->fake_percent_level ?? $raffle->percent_level ?>%
                        </div>
                    </div>
                </div>
                <div class="for-only">
                    POR APENAS 
                    <span>R$ <?php echo number_format($raffle->price, 2, ',', '.') ?></span>
                </div>
                <!--<button class="first-buy">COMPRAR</button>-->

                <style>
                    .row{
                        display: flex;
                        justify-content: center;
                        border-radius: 5px;
                    }

                    .option{
                        padding: 10px;
                        display: block;
                        text-align: center;
                    }

                    .livre{
                        border-top-left-radius: 5px;
                        border-bottom-left-radius: 5px;
                        background-color: white;
                        color: black;
                        width: 30%;
                    }

                    .reservado{
                        background-color: #0094f0;
                        width: 40%;
                    }

                    .pago{
                        background-color: green;
                        border-top-right-radius: 5px;
                        border-bottom-right-radius: 5px;
                        width: 30%;
                    }
                </style>

                <?php if($raffle->parcial == 1): ?>
                    <div class="row">
                        <div class="option livre">
                            Livre (<?php echo $quantity_of_avaiable_numbers ?>)
                        </div>
                        <div class="option reservado">
                            Reservados (<?php echo $reservados ?>)
                        </div>
                        <div class="option pago">
                            Pago (<?php echo $pagos ?>)
                        </div>
                    </div>
                <?php endif ?>

                <?php if (preg_match('/[1-9]/', $raffle->draw_date)) : ?>
                    <div class="raffle-desc-data-draw">
                        <p>Data do sorteio</p>
                        <p><i class="fa-solid fa-calendar-days"></i> &nbsp; <?php echo date('d/m/Y à\s H:i', strtotime($raffle->draw_date)) ?></p>
                    </div>
                <?php endif ?>

                

                

                <div class="raffle-desc-description">
                    <h3><i class="fa-regular fa-file-lines"></i> DESCRIÇÃO</h3>

                    <div class="raffle-desc-description-area">
                        <?php echo $raffle->description ?>
                    </div>
                </div>

                <?php if (isset($winners) && !empty($winners)) : ?>

                    <div class="raffle-desc-winners">
                        <h3><i class="fa-solid fa-ranking-star"></i> GANHADORES</h3>

                        <div class="raffle-desc-winners-body">
                            <?php foreach ($winners as $winner) : ?>

                                <div class="winner-item">
                                    <img src="<?php echo base_url('public/img') ?>/<?php echo $winner->position ?>.png" alt="">
                                    <p><strong><?php echo $winner->name ?></strong> ganhou com a cota <strong><?php echo $winner->number ?></strong></p>
                                </div>
                            <?php endforeach ?>
                        </div>
                    </div>


                <?php endif ?>

                <div class="raffle-share-price">

                    <div class="share-icons">
                        <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo base_url() ?>/<?php echo $raffle->slug ?>" class="share-icon share-facebook" target="_blank">
                            <i class="fa-brands fa-facebook"></i>
                        </a>
                        <a href="https://t.me/share/url?url=<?php echo base_url() ?>/<?php echo $raffle->slug ?>&text=<?php echo urlencode($raffle->title) ?>" class="share-icon share-telegram" target="_blank">
                            <i class="fa-brands fa-telegram"></i>
                        </a>
                        <a href="https://twitter.com/share?url=<?php echo base_url() ?>/<?php echo $raffle->slug ?>" class="share-icon share-twitter" target="_blank">
                            <i class="fa-brands fa-twitter"></i>
                        </a>
                        <a href="https://api.whatsapp.com/send/?text=<?php echo urlencode($raffle->title) ?>+<?php echo base_url() ?>/<?php echo $raffle->slug ?>&type=custom_url&app_absent=0" target="_blank" class="share-icon share-whatsapp">
                            <i class="fa-brands fa-whatsapp"></i>
                        </a>
                    </div>

                </div>
            </div>

            <?php if ($raffle->status != 2) : ?>

                <div class="raffle-body">
                    <div class="raffle-body-header">
                        <h3>NÚMEROS AUTOMÁTICOS</h3>
                        <p>O site escolhe os números para você</p>
                    </div>

                    <div class="raffle-body-body" id="payment-content">
                        <h3>📣 PROMOÇÃO</h3>
                        <?php if ($raffle->discount_status) : ?>
                            <div class="raffle-discount_area">
                                <p>Acima de <?php echo $raffle->discount_quantity ?> cotas, pague apenas <span>R$
                                        <?php echo number_format($raffle->discount_price, 2, ',', '.') ?>/cada</span></p>
                            </div>
                        <?php endif ?>

                        <?php if ($raffle->packs) : ?>

                            <?php
                            $packs = json_decode($raffle->packs);
                            ?>
                            <div class="raffle-packs-area">
                                <div class="raffle-packs-body">
                                    <div class="raffle-packs">
                                        <?php foreach ($packs as $pack) : ?>
                                            <?php if ($quantity_of_avaiable_numbers >= $pack->qnt_numbers) : ?>
                                                <button onclick="setPackage(<?php echo $pack->qnt_numbers ?>,<?php echo currencyToDecimal($pack->price) ?>)" class="raffle-pack">
                                                    ⚡<?php echo $pack->qnt_numbers ?> cotas por R$
                                                    <?php echo number_format(currencyToDecimal($pack->price), 2, ',', '.') ?>
                                                </button>
                                            <?php endif ?>
                                        <?php endforeach ?>
                                    </div>
                                </div>

                            </div>


                        <?php endif ?>

                        <p class="quantity_of_numbers"><span class="quantity">1</span>x R$ <?php echo number_format($raffle->price, 2, ',', '.') ?> = <span class="price">R$ <?php echo number_format($raffle->price, 2, ',', '.') ?></span><span class="discount_price"> / R$ 0,00</span></p>

                        <p>Selecione uma quantidade</p>

                        <div class="select-numbers">
                            <div class="select-numbers-box">
                                <div class="select-number" onclick="increaseQnt(10)">
                                    <div class="select-number-top">
                                        10
                                    </div>
                                    <div class="select-number-bottom">
                                        Selecionar
                                    </div>
                                </div>
                                <div class="select-number" onclick="increaseQnt(50)">
                                    <div class="select-number-top">
                                        50
                                    </div>
                                    <div class="select-number-bottom">
                                        Selecionar
                                    </div>
                                </div>
                                <div class="select-number " onclick="increaseQnt(100)">
                                    <div class="select-number-top">
                                        100
                                    </div>
                                    <div class="select-number-bottom">
                                        Selecionar
                                    </div>

                                </div>
                                <div class="select-number most-popular" onclick="increaseQnt(500)">
                                    <div class="select-number-top">
                                        500
                                    </div>
                                    <div class="select-number-bottom">
                                        Selecionar
                                    </div>
                                    <span>MAIS POPULAR</span>
                                </div>

                            </div>

                            <div class="input-number">
                                <div class="decrease" onclick="decreaseQnt()">
                                    <button><i class="fa-solid fa-minus"></i></button>
                                </div>
                                <form method="POST" id="form_buy">
                                    <input type="number" id="qntInput" name="qnt_numbers" value="1" min="1" max="<?php echo $quantity_of_avaiable_numbers ?>" required>
                                </form>
                                <div class="increase" onclick="increaseQnt()">
                                    <button><i class="fa-solid fa-plus"></i></button>
                                </div>
                            </div>

                            <div class="buy-button">
                                <button onclick="buyNumbers()">Comprar</button>
                            </div>

                        </div>
                        
                        

                    </div>
                    
                    <div class="">
                        <button onclick="toggleMyRequestModal()" style="margin-top: 10px;background-color: #0094f0 !important;width: 100%;display: block;height: 45px;border-radius: 5px;margin-bottom: 20px;font-weight: bold;font-size: 14px;">VER MEUS NÚMEROS</button>
                    </div>

                </div>
                <br><br>
            <?php endif ?>

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

            <form action="<?php echo base_url('/buy-auto-raffle') ?>" method="POST">
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
                <button class="finish" disabled>FINALIZAR</button>
            </form>

        </div>
    </div>
</div>

<!-- RANKING 

<section class="ranking-content">
    <div class="section-header">

    </div>
</section>
-->

<?php if ($raffle->wp_group) : ?>
    <div class="wp_group">
        <a href="<?php echo $raffle->wp_group ?>" target="_blank">
            <img src="<?php echo base_url('/public/img/whatsapp.png') ?>" alt="Grupo no Whatsapp">
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

    document.querySelector('.first-buy').addEventListener('click', () => window.scrollTo({
        top: 710,
        behavior: 'smooth',
    }));
</script>

<script>
    /* Vars */
    let quantity_of_avaiable_numbers = <?php echo $quantity_of_avaiable_numbers ?>;
    qnt_numbers = 1;
    let inputElement = document.querySelector('#qntInput');
    let price = <?php echo $raffle->price ?>;
    let discount_status = <?php echo $raffle->discount_status ?? false ?>;
    let discount_quantity = <?php echo $raffle->discount_quantity ?? 0 ?>;
    let discount_price = <?php echo $raffle->discount_price ?? 0 ?>;


    /* Events */
    document.querySelector('#qntInput').addEventListener('change', increaseInput);
    document.querySelector('#qntInput').addEventListener('keyup', increaseInput);


    /* Functions */
    function increaseInput() {
        qnt_numbers = inputElement.value;
        render();
    }

    function setPackage(nmr, d_price) {
        qnt_numbers = nmr;
        let originalPrice = qnt_numbers * price;

        console.log(originalPrice);

        document.querySelector('span.quantity').innerText = qnt_numbers;
        document.querySelector('span.price').innerText = originalPrice.toLocaleString('pt-br', {
            style: 'currency',
            currency: 'BRL'
        });
        document.querySelector('#qntInput').value = nmr;
        document.querySelector('span.price').style.textDecoration = 'line-through';
        document.querySelector('span.discount_price').innerText = d_price.toLocaleString('pt-br', {
            style: 'currency',
            currency: 'BRL'
        });
        document.querySelector('span.discount_price').style.display = 'inline-block';

        return false
    }

    function decreaseQnt() {
        qnt_numbers--;
        render();
    }

    function increaseQnt(qnt = 1) {

        if (quantity_of_avaiable_numbers == 0) {
            $.notify('Os números já foram esgotados', 'error');
        }

        qnt_numbers = qnt_numbers + parseInt(qnt);

        render();
    }

    function render() {

        if (qnt_numbers > quantity_of_avaiable_numbers) {
            qnt_numbers = quantity_of_avaiable_numbers;
        }
        if (qnt_numbers <= 0) {
            qnt_numbers = 1;
        }
        inputElement.value = qnt_numbers;
        let totalPrice = qnt_numbers * price;
        document.querySelector('span.quantity').innerText = qnt_numbers;
        document.querySelector('span.price').innerText = totalPrice.toLocaleString('pt-br', {
            style: 'currency',
            currency: 'BRL'
        });

        /* Verifica se possui desconto */
        if (discount_status) {
            if (qnt_numbers >= discount_quantity) {

                totalPrice = qnt_numbers * discount_price;
                document.querySelector('span.price').style.textDecoration = 'line-through';
                document.querySelector('span.discount_price').innerText = totalPrice.toLocaleString('pt-br', {
                    style: 'currency',
                    currency: 'BRL'
                });
                document.querySelector('span.discount_price').style.display = 'inline-block';
                document.querySelector('span.price').style.display = 'inline-block';
            } else {
                document.querySelector('span.price').style.textDecoration = 'none';
                document.querySelector('span.discount_price').style.display = 'none';
            }
        } else {
            document.querySelector('span.price').style.textDecoration = 'none';
            document.querySelector('span.discount_price').style.display = 'none';
        }

    }

    function buyNumbers() {
        document.querySelector('.modal.modal-buy-numbers').classList.toggle('active');
        document.querySelector('input[name=numbers').value = document.querySelector('#qntInput').value;
    }

    function toggleBuyNumbers() {
        document.querySelector('.modal.modal-buy-numbers').classList.toggle('active');
    }



    function setMainImage(obj) {
        let img = obj.getAttribute('data-image');
        document.querySelector('.raffle-main-image').style.backgroundImage = `url('${img}')`;
    }



    document.querySelector('button.finish').addEventListener('click', (e) => {

        setTimeout(() => {

            e.target.setAttribute('disabled', 'disabled');
            e.target.innerHTML = ` <div class="lds-ring"><div></div><div></div><div></div><div></div></div> `;

        }, 500);

    });


    /* Check if phone number is equal */

    document.querySelector('input[name=phone_confirm]').addEventListener('keyup', toggleActive);
    document.querySelector('input[name=phone_confirm]').addEventListener('keypress', toggleActive);
    document.querySelector('input[name=phone_confirm]').addEventListener('click', toggleActive);



    function toggleActive() {

        let phone1 = document.querySelector('input[name=phone]');
        let phone_confirm = document.querySelector('input[name=phone_confirm]');


        if (phone1.value == phone_confirm.value && phone1.value.length == 15 && phone_confirm.value.length == 15) {

            phone_confirm.classList.remove('wrong');
            document.querySelector('form button').removeAttribute('disabled');


        } else {

            document.querySelector('form button').setAttribute('disabled', 'disabled');
            phone_confirm.classList.add('wrong');

            setTimeout(() => phone_confirm.click(), 500);

        }

        //console.log('clicou');

    }
</script>
