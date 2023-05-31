<div class="container customers_page">
    <div class="container-header">
        <h1 class="title">Planos</h1>
        <a href="<?php echo base_url('/dashboard/planos/novo-plano') ?>">
            <button class="success">Novo Plano</button>
        </a>
    </div>
    <div class="container-body">

        <div class="customers">

            <table>
                <thead>
                    <tr style="text-align: center;">
                        <th>Qtd de cotas</th>
                        <th>Valor</th>
                        <th>Ações</th>
                    </tr>
                <tbody>
                    <?php if ($planos) : ?>
                        <?php foreach ($planos as $plano) : ?>

                            <tr style="text-align: center;">
                                <td>
                                    <?php echo $plano->cotas ?>
                                </td>
                                <td>
                                    <?php echo $plano->valor ?>
                                </td>

                                <td>
                                    <a href="<?php echo base_url('/dashboard/planos/excluir')?>/<?php echo $plano->cotas ?>" onclick="return confirm('Tem certeza que deseja excluir o plano?')" class="delete">Excluir</a>
                                </td>
                            </tr>
                        <?php endforeach ?>
                    <?php endif ?>

                </tbody>
                </thead>

            </table>

        </div>
    </div>
</div>