<style>
    .approve{
        color: green !important;
    }
</style>

<div class="container customers_page">
    <div class="container-header">
        <h1 class="title">Rifas Cliente <?php echo $customer->name ?></h1>
    </div>
    <div class="container-body">

        <div class="customers">

            <table>
                <thead>
                    <tr style="text-align: center;">
                        <th>ID</th>
                        <th>Titulo</th>
                        <th>Taxa</th>
                        <th>Criada em</th>
                        <th>Pagamento</th>
                        <th>Ação</th>
                    </tr>
                <tbody>
                    <?php if ($raffles) : ?>
                        <?php foreach ($raffles as $raffle) : ?>

                            <tr style="text-align: center;">
                                <td>
                                    <?php echo $raffle->id ?>
                                </td>
                                <td>
                                    <?php echo $raffle->title ?>
                                </td>
                                <td>
                                    <?php echo number_format($raffle->payment_price, 2, ",", ".") ?>
                                </td>
                                <td>
                                    <?php echo date('d/m/Y', strtotime($raffle->created_at)) ?>
                                </td>
                                <td>
                                    <?php 
                                        if($raffle->payment_status == 0){
                                            echo '<span style="color: red">Pendente</span>';
                                        }
                                        else{
                                            echo '<span style="color: green">Confirmado</span>';
                                        }
                                    ?>
                                </td>
                                <td>
                                    <?php if($raffle->payment_status == 0): ?>
                                        <a href="<?php echo base_url('/dashboard/clientes/rifas/aprovar-pagamento')?>/<?php echo $raffle->id ?>" onclick="return confirm('Tem certeza que confirmar o pagamento?')" class="approve">Confirmar Pagamento</a>
                                    <?php endif ?>
                                    
                                </td>
                            </tr>
                        <?php endforeach ?>
                    <?php endif ?>

                </tbody>
                </thead>

            </table>

            <?php echo $pager->links() ?>

        </div>
    </div>
</div>