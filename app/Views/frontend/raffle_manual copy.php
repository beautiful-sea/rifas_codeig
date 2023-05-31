<section class="raffle_page raffle_manual">

    <div class="container">
        <div class="raffle-page-left">

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
            
        </div>
    </div>

    <div class="container">
        <div class="numbers-reserved">

        </div>

        <div class="select-numbers">
            <?php $numbers = json_decode($raffle->numbers)?>

            <?php $row = 0; ?>
            <div class="select-numbers-row">

                <?php foreach($numbers as $n): ?>

                    <?php if($row == 10){

                        echo " </div>
                        <div class='select-numbers-row'>";

                        $row = 0;
                       
                    }
                    ?>

                    <div class="number <?php echo $n->status == '1'?'reserved tooltip':'' ?>" <?php echo $n->status == '0'?'onclick="toggleNumber(this)"':'' ?> data-number="<?php echo $n->number ?>">
                        <?php echo $n->number ?>

                        <?php if($n->status == '1' || $n->status === 2):?>
                            <span class="tooltiptext"> Reservado por: <b><?php echo $n->user ?></b> </span>
                        <?php endif ?>

                    </div>

                    <?php $row++ ?>
                <?php endforeach ?>

            </div>
          
        </div>

    </div>

</section>

<!-- MODAL TO SELECT RAFFLE -->
<div class="selected-numbers">
    <div class="my-numbers">
        <div class="my-numbers-header">
           <p>Quantidade: <span class='quantity'> </span> | Valor total: <span class='price'></span> <span class='discount_price'></span></p>
        </div>
        <div class="my-numbers-body">

        </div>
    </div>
    <div class="buy-numbers">
        <button onclick="buyNumbers()">Comprar</button>
    </div>
</div>

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
            
            <form action="<?php echo base_url('/buy-manual-raffle')?>" method="POST">
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
    });
</script>


<script>

    let numbers = [];
    let price = <?php echo $raffle->price ?>;

    let discount_status = <?php echo $raffle->discount_status ?? false?>;
    let discount_quantity = <?php echo $raffle->discount_quantity ?? 0?>;
    let discount_price = <?php echo $raffle->discount_price ?? 0?>;


    function toggleNumber(obj){

        let number = obj.getAttribute('data-number');

        obj.classList.toggle('active');

        if(obj.classList.contains('active')){
            
            numbers.push(number);
        } else {
            numbers = numbers.filter((n)=> n !== number);
        }

       render();
    }

    function removeNumber(key){

        // remove o active do elemento
        document.querySelector(`.number.active[data-number="${numbers[key]}"]`).classList.remove('active');
        numbers = numbers.filter((n, k)=>k!== key);
        
        render();
    }

    function render(){

        if(numbers.length > 0){

            /* limpa antes de inserir os números */
            document.querySelector('.selected-numbers .my-numbers .my-numbers-body').innerText = "";

            numbers.forEach((n, key)=>{
                let numberElement = document.createElement('div');

                numberElement.innerText = n;
                numberElement.classList.add('number');
                numberElement.setAttribute('onclick', 'removeNumber('+key+')');

                document.querySelector('.selected-numbers .my-numbers .my-numbers-body').appendChild(numberElement);
            });

            document.querySelector('.selected-numbers').classList.add('active');

        } else {
            /* limpa e desaparece */
            document.querySelector('.selected-numbers .my-numbers .my-numbers-body').innerText = "";
            document.querySelector('.selected-numbers').classList.remove('active');
        }

        totalPrice = numbers.length * price;
        document.querySelector('span.quantity').innerText = numbers.length;
        document.querySelector('span.price').innerText = totalPrice.toLocaleString('pt-br',{style: 'currency', currency: 'BRL'});

        /* Verifica se possui desconto */
        if(discount_status){
            if(numbers.length >= discount_quantity) {

                totalPrice = numbers.length * discount_price;
                document.querySelector('span.price').style.textDecoration = 'line-through';
                document.querySelector('span.discount_price').innerText = totalPrice.toLocaleString('pt-br',{style: 'currency', currency: 'BRL'});
                document.querySelector('span.discount_price').style.display = 'inline-block';
                document.querySelector('span.price').style.display = 'inline-block';
            } else{
                document.querySelector('span.price').style.textDecoration = 'none';
                document.querySelector('span.discount_price').style.display = 'none';
            }
        }
    }

    function buyNumbers(){
        document.querySelector('.selected-numbers').classList.remove('active');
        //document.querySelector('.selected-numbers').classList.toggle('active');
        document.querySelector('.modal.modal-buy-numbers').classList.toggle('active');

        /* Pega os números e joga no formulário */

        document.querySelector('input[name=numbers]').value = JSON.stringify(numbers);
    }

    function toggleBuyNumbers(){
        document.querySelector('.modal.modal-buy-numbers').classList.toggle('active');
        document.querySelector('.selected-numbers').classList.toggle('active');
    }

    

    function setMainImage(obj){
        let img = obj.getAttribute('data-image');
        document.querySelector('.raffle-main-image').style.backgroundImage = `url('${img}')`;
    }
    


</script>
