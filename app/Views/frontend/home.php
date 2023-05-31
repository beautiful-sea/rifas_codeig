<style>

    .swiper-slide {

        background-image: url('<?php echo base_url('public/img/banner1.jpg')?>'); 

    }
    
    .winners{
            margin-top: 0px !important;
        }

    @media (max-width: 768px){
        .swiper-container {
            display: none;
        }
        .swiper-slide {
            background-image: url('<?php echo base_url('public/img/banner1.jpg')?>') !important;

        }
        
        

    }

</style>



<div class="" id="" style="background-color:#000;padding-bottom:10px;border-radius: 0px 0px 0px 0px;margin-top: 65px;" >

<?php if(!isset($category)):?>





    <!-- SLIDER-->

    <div class="swiper-container">

        

        <!-- swiper slides -->

        <div class="swiper-wrapper">

            <div class="swiper-slide"></div>

        </div>

        <!-- !swiper slides -->

        

        <!-- next / prev arrows -->

      

        <!-- !next / prev arrows -->

        

        <!-- pagination dots -->
        <div class="swiper-pagination"></div>

        <!-- !pagination dots -->

    </div>



<?php endif ?>



<!-- SECTION SORTEIO -->

<style>
    .text-muted{
        --bs-text-opacity: 1;
        color: var(--bs-secondary-color)!important;
    }
    
    @media (max-width: 768px){
        .swiper-container{
            margin-bottom: 0px !important;
        }    
    }
</style>
    <!-- <div class="titulo-lex">
        <br><h1 style="font-size: 30px;margin-left: 20px;">
            ⚡  Prêmios
            <small class="text-muted" style="font-size: 15px; margin-left: 10px; font-weight: 100;">Escolha sua sorte</small>
        </h1>
    </div> -->
<!--START RIFA FAVORITA LEXLUTHOR-->
<div class="container-lex" >
    <div class="card-lex" style="margin-top: 10px !important;">
        <?php if($raffles):?>
            <?php if ($rifafavorita):?>
                <?php foreach($rifafavorita as $raffle):?>
                <?php endforeach; ?>
                <?php
                $images = json_decode($raffle->images);
                $desc = substr($raffle->description,0,30) . "...";
                if(isset($images[0])){
                    $image = base_url('public/images') .'/'.$images[0];
                } else{
                    $image = base_url('public/images') .'/default.png';
                }
                ?>
                <div class="sorteio-lex">
                    <a href="<?php echo base_url()?>/<?php echo $raffle->slug?>">
                        <div class="sorteio-lex-img" style="background-image: url('<?php echo $image ?>');"> </div>
                    </a>
                </div>
                <div class="sorteio-lex-desc">
                    <p class="" style="font-weight: 600; font-size: 25px;"><?php echo $raffle->title?>
                    <p style="font-size: 12px;"><?php echo $desc;?></p>
                    <?php if($raffle->percent_level == 100 || $raffle->fake_percent_level == 100):?>
                        <span class="sorteio-status esgotado">Encerrado</span>
                    <?php else: ?>
                        <span class="sorteio-status disponivel">Disponível</span>
                    <?php endif ?>
                </div>

            <?php endif?>
        <?php else: ?>
            <div class="not-found">
                <p>Nada por aqui...</p>
                <a href="<?php echo base_url()?>?c=todas" class="see-all">Ver todas</a>
            </div>
        <?php endif?>
    </div>
</div>
<!--END RIFA FAVORITA LEXLUTHOR-->

<!-- START SEÇÃO DÚVIDA LEXLUTHOR-->
            <br>
    <article class="sorteio-lex-duvida" style="margin-top: 10px;">
        <a href="https://contate.me/osincriveissorteioscombr">
            <div class="container  d-flex duvida"  style="display:flex;background-color:#151944;border-radius:10px;height: 60px;max-width:92%;align-items: center;justify-content: center;margin-top:140px;">
                <div class="row d-flex" id="container_duvidas" style="height:60px;width:345px;border-radius:10px;align-items: center;justify-content:center;" >
                    <div class="d-flex" style="margin-right:5px;width:50px;justify-content:center;align-items:center;background-color:#b9b9b9;height:35px;border-radius:10px;text-align:center;font-size:20px">🤷</div>
                    <div class="col" style="display:flex;flex-direction:column;justify-content:center">
                        <h6 class="mb-0 font-md" style="font-size:15px;">Dúvidas</h6>
                        <p class="mb-0  font-sm text-muted" style="font-size:12px;">Fale conosco</p>
                    </div>
                </div>
            </div>
        </a>
    </article>
    <!-- END SEÇÃO DÚVIDAS LEXLUTHOR -->

<section class="sorteios">

    <div class="container">
        <div class="">
            <button class="btn-pisca" onclick="cadastro()">CRIE SUA RIFA AGORA!</button>
        </div>


        <div class="section-body sorteios-body">



            <?php if($raffles):?>




                <?php foreach($raffles as $raffle): ?>

                    <?php 

                        $images = json_decode($raffle->images);
                        $desc = substr($raffle->description,0,30) . "...";

                        

                        if(isset($images[0])){

                            $image = base_url('public/images') .'/'.$images[0];

                        } else{

                            $image = base_url('public/images') .'/default.png';

                        }

                    ?>



                    <article class="sorteio">

                        <a href="<?php echo base_url()?>/<?php echo $raffle->slug?>">

                            

                            <div class="sorteio-img" style="background-image: url('<?php echo $image ?>');"> </div>

                            

                            <div class="sorteio-desc">

                                <p class="" style="font-weight: 300; font-size: 18px;"><?php echo $raffle->title?>
                                
                                <p style="font-size: 12px;"><?php echo $desc;?></p>

                                <?php if($raffle->percent_level == 100 || $raffle->fake_percent_level == 100):?>

                                    <span class="sorteio-status esgotado">Encerrado</span>

                                <?php else: ?>

                                    <span class="sorteio-status disponivel">Disponível</span>

                                <?php endif ?>
                               

                            </div>

                        </a>

                    </article>



                <?php endforeach; ?>


            <?php else: ?>



                <div class="not-found">

                    <p>Nada por aqui...</p>



                    <a href="<?php echo base_url()?>?c=todas" class="see-all">Ver todas</a>

                </div>


                
            <?php endif?>



           

        </div>

        <?php if(isset($pager)):?>

            <?php echo $pager->links() ?>

        <?php endif ?>



  

    </div>

</section>





<!-- <section class="winners" id="ganhadores">
    <div class="container">
        <div class="section-header">
            <div class="d-flex">
                <h1 class="">
                    🎉 Ganhadores
                    <small class="text-muted" style="font-size: 15px;">sortudos</small>
                </h1>
            </div>
        </div>
        <div class="section-body">
            <div class="winners-list">
                <?php if($winners): ?>
                    <?php foreach($winners as $winner): ?>
                        <div class="row" style="width: 100%; display: flex;border-radius: 10px;background-color: #151944;margin-bottom: 5px;">
                            <div class="col-2" style="align-items: center;display: flex;justify-content: center;align-items: center;">
                                <div class="sorteio-lex-ganhador-img" style="background-image: url('<?php echo  base_url('public/images') .'/'.$winner['images'] ?>');"> </div>
                            </div>
                            <div class="col-8" style="margin-top: 3px;margin-left: 10px;margin-bottom: 3px;">
                                <b> <?php echo $winner['name']?> N°: <?php echo $winner['number'] ?></b> <br/>
                                Sorteio: <a href="<?php echo base_url()?>/<?php echo $winner['slug']?>" style="color: #9bc1d9;"> <?php echo $winner['title'] ?> <i class="fas fa-external-link-alt" style="color: #9bc1d9;"></i> </a>
                            </div>
                            <div class="col-2" style="padding-right:10px;align-items: center;display: flex;">
                                <i class="fas fa-trophy trofeu" style="color: #9bc1d9; font-size: 30px;"></i>
                            </div>
                        </div>
                    <?php endforeach ?>



                <?php else : ?>

                <?php endif ?>

            </div>
        </div>
    </div>
</section> -->
</div>
<script>
    function cadastro(){
        window.location.href = '/cadastro';
    }
</script>

<style>
    .btn-pisca {
  background-color: #35b084;
  -webkit-border-radius: 5px;
  border-radius: 5px;
  border: none;
  color: #FFFFFF;
  cursor: pointer;
  display: inline-block;
  font-family: Arial;
  font-size: 14px;
  padding: 5px 10px;
  text-align: center;
  text-decoration: none;
  width: 100%;
    display: block;
    height: 45px;
    border-radius: 5px;
    margin-bottom: 20px;
    font-weight: bold;
    font-size: 14px;
}
@-webkit-keyframes glowing {
  0% { background-color: #35b084; -webkit-box-shadow: 0 0 3px #35b084; }
  50% { background-color: #8BC34A; -webkit-box-shadow: 0 0 3px #8BC34A; }
  100% { background-color: #35b084; -webkit-box-shadow: 0 0 3px #35b084; }
}

@-moz-keyframes glowing {
  0% { background-color: #35b084; -moz-box-shadow: 0 0 3px #35b084; }
  50% { background-color: #8BC34A; -moz-box-shadow: 0 0 3px #8BC34A; }
  100% { background-color: #35b084; -moz-box-shadow: 0 0 3px #35b084; }
}

@-o-keyframes glowing {
  0% { background-color: #35b084; box-shadow: 0 0 3px #35b084; }
  50% { background-color: #8BC34A; box-shadow: 0 0 3px #8BC34A; }
  100% { background-color: #35b084; box-shadow: 0 0 3px #35b084; }
}

@keyframes glowing {
  0% { background-color: #35b084; box-shadow: 0 0 3px #35b084; }
  50% { background-color: #8BC34A; box-shadow: 0 0 3px #8BC34A; }
  100% { background-color: #35b084; box-shadow: 0 0 3px #35b084; }
}

.btn-pisca {
  -webkit-animation: glowing 1500ms infinite;
  -moz-animation: glowing 1500ms infinite;
  -o-animation: glowing 1500ms infinite;
  animation: glowing 1500ms infinite;
}
</style>