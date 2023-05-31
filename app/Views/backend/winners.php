<div class="container winners">
    <div class="container-header">
        <h2>Ganhadores</h2>
    </div>
    <div class="container-body">

        <div class="new-winner">
            <button class="success" onclick="toggleNewWinner()">Cadastrar ganhador</button>
        </div>
        <div class="winners-list">

            <?php if($winners):?>
                
                <table>
                    <thead>
                        <tr>
                            <th>Nome</th>
                            <th>Rifa</th>
                            <th>Cota</th>
                            <th>Data</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($winners as $winner):?>
                           <tr>
                                <td><?php echo $winner->name ?></td>
                                <td><?php echo $winner->raffle->title ?></td>
                                <td><?php echo $winner->number ?></td>
                                <td><?php echo date('d/m/Y ', strtotime($winner->created_at)) ?></td>
                                <td>
                                    <a href="#" class="edit" onclick="editWinner(this)" data-winner="<?php echo htmlspecialchars(json_encode($winner))?>">Editar</a>
                                    <a href="<?php echo base_url('admin/ganhadores/excluir')?>/<?php echo $winner->id?>" onclick="return confirm('Tem certeza que deseja excluir o ganhador?')" class="delete">Excluir</a>
                                </td>
                           </tr>
                        <?php endforeach ?>
                    </tbody>
                </table>
               
            <?php else: ?>
                <p>Não há ganhadores cadastrados.</p>
            <?php endif ?>

        </div>
    </div>
</div>


<div class="modal modal-winner <?php echo (isset($user))?'active':''?>">
    <div class="modal-content">
        <div class="modal-header">
            Novo ganhador
            <span onclick="toggleNewWinner()">X</span>
        </div>

        <div class="modal-body">
            <form method="POST">
                <input type="hidden" name="id_winner">
                <label for="">
                    Nome
                    <input type="text" name="name" value="<?php echo $user->name??''?>">
                </label>

                <label for="">
                    Cota
                    <input type="text" name="number" value="<?php echo $number ?>">
                </label>

                <label for="">
                    Rifa
                    <select name="id_raffle" id="">
                        <?php if($raffles):?>
                            <?php foreach($raffles as $r): ?>
                                <option value="<?php echo $r->id?>" <?php echo (isset($raffle) && $raffle->id == $r->id) ?'selected':''?>><?php echo $r->title ?></option>
                            <?php endforeach ?>
                        <?php endif  ?>
                    </select>
                </label>
                <input type="submit" value="ATUALIZAR">
            </form>
        </div>
    </div>
</div>




<script>
    function toggleNewWinner()
    {
        document.querySelector('.modal.modal-winner').classList.toggle('active');
    }

    function editWinner(obj){
        let data = JSON.parse(obj.getAttribute('data-winner'));
       
        document.querySelector('input[name=name]').value = data.name;
        document.querySelector('input[name=number').value = data.number;
        document.querySelector('select[name=id_raffle]').value = data.id_raffle;
        document.querySelector('input[name=id_winner]').value = data.id;

        toggleNewWinner();
    }

</script>