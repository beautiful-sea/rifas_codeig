<div class="container">
    <div class="dashboard-itens">

        <div class="dashboard-item profit block-copy"> 
            <div class="dashboard-item-body">
                <p>Lucro</p>
                <p>R$ <?php echo number_format($profit,2,',','.')?></p>  
                <i class="fa-solid fa-dollar-sign"></i>
            </div>    
        </div>
        <div class="dashboard-item request block-copy"> 
            <div class="dashboard-item-body">
                <p>Pedidos</p>
                <p><?php echo $count_orders ?></p>  
                <i class="fa-solid fa-receipt"></i>
            </div>    
        </div>
        <div class="dashboard-item pending_request block-copy "> 
            <div class="dashboard-item-body">
                <p>Aguardando Pgto.</p>
                <p><?php echo $count_pending_orders ?></p>    
                <i class="fa-solid fa-hourglass"></i>
            </div>    
        </div>
        <div class="dashboard-item pending_entry block-copy"> 
            <div class="dashboard-item-body">
                <p>Entrada pendente</p>
                <p>R$ <?php echo number_format($pending_profit,2,',','.')?></p>    
                <i class="fa-solid fa-dollar-sign"></i>
            </div>    
        </div>
    </div>
</div>