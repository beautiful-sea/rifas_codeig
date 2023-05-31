<style>
    .approve{
        color: green !important;
    }
</style>


<div class="container customers_page">
    <div class="container-header">
       
        <h1 class="title">Todos os clientes</h1>
        <div class="row">
            <form action="">
                <div class="row-input">
                    <label for="">
                        <input type="text" name="search" placeholder="Pesquise pelo nome, email, ou telefone do cliente" value="<?php echo $search ?>" autocomplete="off">
                    </label>
                </div>
                <button><i class="fa-solid fa-magnifying-glass"></i></button>
            </form>
        </div>
        
    
    </div>
    <div class="container-body">

        <div class="customers">

            <table>
                <thead>
                    <tr style="text-align: center;">
                        <th>Nome</th>
                        <th>E-mail</th>
                        <th>Cobrança</th>
                        <th>Cadastrado em</th>
                        <th>Ações</th>
                    </tr>
                    <tbody>

                        <?php if($customers):?>
                            <?php foreach($customers as $customer):?>
                              
                                <tr style="text-align: center;">
                                    <td>
                                        <?php echo $customer->name ?>
                                    </td>
                                    <td>
                                        <?php echo $customer->email ?>
                                    </td>
                                    <td>
                                        <?php 
                                            if($customer->cobrar){
                                                echo 'Ativa';
                                            }
                                            else{
                                                echo 'Desativada';
                                            }
                                        ?>
                                    </td>
                       
                                    <td>
                                        <?php echo date('d/m/Y', strtotime($customer->created_at)); ?>
                                    </td>
                             
                                    <td>
                                        <?php
                                            if($customer->cobrar){
                                        ?>
                                                <a href="<?php echo base_url('/dashboard/clientes/toggle-cobranca')?>/<?php echo $customer->id ?>" onclick="return confirm('Tem certeza que deseja desativar as cobranças desse cliente?')" class="delete">Desativar Cobrança</a>
                                        <?php
                                            }
                                            else{
                                        ?>
                                                <a href="<?php echo base_url('/dashboard/clientes/toggle-cobranca')?>/<?php echo $customer->id ?>" onclick="return confirm('Tem certeza que deseja ativar as cobranças desse cliente?')" class="approve">Ativar Cobrança</a>
                                        <?php
                                            }
                                        ?>
                                        <a href="<?php echo base_url('/dashboard/clientes/rifas')?>/<?php echo $customer->id ?>" class="see-orders">Ver Rifas</a>
                                        <a href="<?php echo base_url('/dashboard/clientes/excluir')?>/<?php echo $customer->id ?>" onclick="return confirm('Tem certeza que deseja excluir o cliente e todos seus pedidos?')" class="delete">Excluir</a>
                                    </td>
                                </tr>
                            <?php endforeach ?>
                        <?php endif?>
                    </tbody>
                </thead>

            </table>

            <?php echo $pager->links() ?>

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