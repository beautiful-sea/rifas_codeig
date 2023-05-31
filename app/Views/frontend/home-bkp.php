<style>

    .swiper-slide {

        background-image: url('<?php echo base_url('public/img/banner1.jpg')?>'); 

    }
    
    .winners{
            margin-top: 0px !important;
        }

    @media (max-width: 768px){

        .swiper-slide {

            background-image: url('<?php echo base_url('public/img/banner1.jpg')?>') !important;
        }
        
        

    }

</style>



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
g
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

<section class="sorteios">

    <div class="container">
        <!--<div class="">-->
        <!--    <button class="btn-pisca" onclick="cadastro()">CRIE SUA RIFA AGORA!</button>-->
        <!--</div>-->
        <h1 style="font-size: 30px;">
            ⚡  Prêmios
           <small class="text-muted" style="font-size: 15px; margin-left: 10px; font-weight: 100;">Escolha sua sorte</small>
        </h1>
        
        <div class="section-header sorteios-header <?php if(isset($_GET['c'])){ echo "mt";}?>">

            <!--<div class="d-flex">-->

            <!--    <i class="fa-solid fa-bookmark"></i>-->

            <!--    <?php if(isset($category)):?>-->

            <!--        <h2><?php echo $category->title ?></h2>-->

            <!--    <?php else: ?>-->

            <!--        <h2>GRANDES PRÊMIOS</h2>-->

            <!--    <?php endif ?>-->

            <!--</div>-->
            
            <div>
                
            </div>

            <!--<?php if(isset($category)):?>-->

            <!--    <p><?php echo $category->description??'Sua chance de mudar de vida!'?></p>-->

            <!--<?php else: ?>-->

            <!--<p>Sua chance de mudar de vida!</p>-->

            <!--<?php endif ?>-->

            

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

                            

                            <div class="sorteio-desc" style="background-color: #1c1c1c;" >

                                <p class="sorteio-title"><?php echo $raffle->title?>
                                
                    
                                <?php if(preg_match('/[1-9]/', $raffle->draw_date)): ?>   

                                    <p><?php echo date('d/m/Y H:i', strtotime($raffle->draw_date)) ?></p>

                                <?php endif ?>



                                <p class="sorteio-price">R$ <?php echo number_format($raffle->price,2,',','.')?></p>
                                
                                <span style="font-size: 10px;"><?php echo $desc;?></span>

                               

                                <div class="progress-sell">

                                    <div class="progress">

                                        <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: <?php echo $raffle->fake_percent_level??$raffle->percent_level ?>%" aria-valuenow="10" aria-valuemin="0" aria-valuemax="100">
                                            <?php echo $raffle->fake_percent_level??$raffle->percent_level ?>%
                                        </div>

                                    </div>

                                </div>

                            

                            </div>

                            <!-- status da rifa -->

                            <?php if($raffle->percent_level == 100 || $raffle->fake_percent_level == 100):?>

                                <span class="sorteio-status esgotado">Encerrado</span>

                            <?php else: ?>

                                <span class="sorteio-status disponivel">Disponível</span>

                            <?php endif ?>

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





<section class="winners" id="ganhadores">



    <div class="container">

        <div class="section-header">

            <div class="d-flex">
                
                <h2>
                    <i class="fas fa-trophy text-primary" style="font-size: 20px; color: #0094f0"></i>
                    GANHADORES
                </h2>

            </div>

            <p>Os sortudos mais recentes!</p>

        </div>

        <div class="section-body">
        
            <div class="winners-list">

                <?php if($winners): ?>



                    <?php foreach($winners as $winner): ?>
                        <div class="row" style="width: 100%; display: flex; padding: 10px;border: solid;border-width: thin;border-radius: 10px;background-color: #1c1c1c;border-color: #343434;margin-bottom: 10px;">
                            <div class="col-2" style="padding: 10px; align-items: center;display: flex;">
                                <i class="fas fa-trophy text-primary" style="color: #0094f0; font-size: 60px;"></i>
                            </div>
                            <div class="col-10">
                                <b> <?php echo $winner['name'] ?></b> <br/>
                                Sorteio: <a href="<?php echo base_url()?>/<?php echo $winner['slug']?>" style="color: #0094f0;"> <?php echo $winner['title'] ?> <i class="fas fa-external-link-alt" style="color: #0094f0"></i> </a> <br />
                                <?php echo date('d/m/Y', strtotime($winner['draw_date'])) ?> <br />
                                Cota de número: <b><?php echo $winner['number'] ?>
                            </div>
                        </div>

                        <!-- <div class="winner-content">

                            <a href="<?php echo base_url()?>/<?php echo $winner->slug ?>">

                                <div class="raffle-image">



                                </div>

                                <div class="winner-name">

                                    <p>

                                        <b>🎊🎉 <?php echo $winner['name'] ?></b> <br/>

                                        Ganhou: <?php echo $winner['title'] ?> com a cota: <b><?php echo $winner['number'] ?></b>

                                    </p>

                                    <p class="draw-date">Data do Sorteio: <b> <?php echo date('d/m/Y', strtotime($winner['draw_date'])) ?></b></p>

                                </div>

                            </a>

                        </div> -->



                        

                    <?php endforeach ?>



                <?php else : ?>

                <?php endif ?>

            </div>





        </div>





    </div>

   

</section>

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