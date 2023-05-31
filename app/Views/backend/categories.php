<div class="container categories">
    <div class="container-header">
        <h1 class="title">Todas as categorias</h1>

        <div class="row">
            <form action="">
                <div class="row-input">
                    <label for="">
                        <input type="text" name="search" value="<?php echo $search ?>" placeholder="Pesquise pelo título ou descrição da categoria">
                    </label>
                </div>
                <div class="row-input">
                    <label for="">
                        <select name="type" id="">
                            <option value="">Todas</option>
                            <option <?php echo $type == 'active'?'selected':'' ?> value="active">Ativa</option>
                            <option <?php echo $type == 'inactive'?'selected':'' ?> value="inactive">Inativa</option>
                        </select>
                    </label>
                </div>
                <button><i class="fa-solid fa-magnifying-glass"></i></button>
            </form>
            <button class="success" onclick="setCategory()">Adicionar Categoria</button>

        </div>
        
    </div>

    <div class="container-body">
        <table>
            <thead>
                <tr>
                    <th>Título</th>
                    <th>Descrição</th>
                    <th>Criado em</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php if($categories):?>
                    <?php foreach($categories as $c):?>
                        <tr>
                            <td><?php echo $c->title ?></td>
                            <td><?php echo $c->description ?></td>
                            <td><?php echo date('d/m/Y H:i', strtotime($c->created_at))?></td>
                            <td class="td-actions">
                            
                                <!-- Toggle rifa status -->
                                <div class="toggleWrapper">
                                    <input type="checkbox" name="toggle1" class="mobileToggle" id="toggle1" checked="">
                                    <label for="toggle1"></label>
                                </div>

                                <a href="#" class="edit" data-category="<?php echo htmlspecialchars(json_encode($c)) ?>" onclick="setCategory(this)">Editar</a>
                                <a href="<?php echo base_url('admin/categorias/excluir')?>/<?php echo $c->id ?>" class="delete" onclick="return confirm('Tem certeza que deseja excluir a categoria?')">Excluir</a>
                        
                            </td>
                        </tr>
                    <?php endforeach?>

                <?php endif?>
            </tbody>
        </table>
        <?php echo $pager->links() ?>
    </div>

</div>

<div class="modal modal-category">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Adicionar categoria</h2>
            <span onclick="toggleCategory()">x</span>
        </div>
        <div class="modal-body">
            <form method="POST" action="<?php echo base_url('admin/categorias') ?>">
                
                <label for="">
                    Título
                    <input type="text" name="title" required>
                </label>
                <label for="">
                    Descrição
                   <textarea name="description"  cols="30" rows="10"></textarea>
                </label>

                <input type="hidden" name="id_category">
        
                <input type="submit" value="Adicionar">
              
            </form>
        </div>
    </div>
</div>


<script>
    function toggleCategory(){
        document.querySelector('.modal.modal-category').classList.toggle('active');
    }

    function setCategory(obj = false){

        if(obj){

            let category = JSON.parse(obj.getAttribute('data-category'));

            document.querySelector('.modal-category .modal-header h2').innerText = 'Editar categoria';
            document.querySelector('input[name=title]').value = category.title;
            document.querySelector('textarea[name=description]').value = category.description;
            document.querySelector('input[name=id_category]').value = category.id;

            document.querySelector('input[type=submit]').value = 'Atualizar';

        } else {
            document.querySelector('.modal-category .modal-header h2').innerText = 'Adicionar categoria';
            document.querySelector('input[type=submit]').value = 'Adicionar';
            document.querySelector('input[name=title]').value = '';
            document.querySelector('textarea[name=description]').value = '';
            document.querySelector('input[name=id_category]').value = '';
        }
        
        toggleCategory();
    }
</script>