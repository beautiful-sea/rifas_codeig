<div class="container ranking">
    <div class="container-header">
        <h1 class="title">Ranqueamento</h1>
        <div class="row">
            <form action="">
                <div class="row-input">
                    <label for="">
                        <select name="id_raffle" id="">

                        <?php if($raffles):?>
                            <?php foreach($raffles as $r): ?>
                                <option <?php echo (isset($raffle) && $raffle->id == $r->id ) ?'selected':''?> value="<?php echo $r->id ?>"><?php echo $r->title ?></option>
                            <?php endforeach ?>

                        <?php endif ?>
                        
                        </select>
                    </label>
                </div>
                <button><i class="fa-solid fa-magnifying-glass"></i> Buscar</button>
            </form>
        </div>
    </div>
    <div class="container-body">
        <div class="orders">
            <div class="table">
                <table>
                    <thead>
                        <tr>
                            <th></th>
                            <th>Cliente</th>
                            <th>Rifa</th>
                            <th>Quantidade.</th>
                            <th>Total</th>
                        </tr>
                        <tbody>
                            <?php if(isset($ranking_orders)):?>
                                <?php $x = 1; ?>
                                <?php foreach($ranking_orders as $order ):?>
                                   
                                    <tr>
                                        <td>
                                            <img src="<?php echo base_url('public/img')?>/<?php echo $x?>.png" alt="">
                                        </td>
                                        <td>
                                            <a href="<?php echo base_url('dashboard/clientes')?>?search=<?php echo urlencode($order->customer->name)?>" target="_blank"><?php echo $order->customer->name ?></a>
                                        </td>
                                        <td>
                                            <?php echo $raffle->title ?>
                                        </td>
                                    
                                        <td>
                                            <?php echo $order->totalNumbers; ?>
                                        </td>
                                        <td>
                                            R$ <?php echo number_format($order->totalPrice,2,',','.'); ?>
                                        </td>
                                  
                                    </tr>

                                    <?php $x++; ?>
                                <?php endforeach ?>
                            <?php endif?>
                        </tbody>
                    </thead>

                </table>
            </div>
            
        </div>
    </div>
</div>


<div class="modal my-numbers">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Números</h2>
            <span onclick="toggleMyNumbers()">x</span>
        </div>
        <div class="modal-body">
            <div class="numbers">
                <div class="number">333</div>
                <div class="number">153</div>
            </div>
        </div>
    </div>
</div>



<script>

    function toggleMyNumbers(){
        document.querySelector('.modal.my-numbers').classList.toggle('active');
    }

    function myNumbers(obj){
        let numbers = JSON.parse(obj.getAttribute('data-numbers'));

        document.querySelector('.my-numbers .numbers').innerText = "";
        numbers.forEach((n)=>{
            let div = document.createElement('div');
            div.classList.add('number');
            div.innerText = n;
            document.querySelector('.my-numbers .numbers').appendChild(div);
        });

        toggleMyNumbers();
    }


</script>