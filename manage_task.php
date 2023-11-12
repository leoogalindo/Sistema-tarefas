<?php 
if(isset($_GET['id']) && $_GET['id'] > 0){
   
    $sql = "SELECT * FROM `task_list` where `task_id` = '{$_GET['id']}' ";
    $query = $conn->query($sql);
    $data = $query->fetchArray();

}


$_SESSION['formToken']['task-form'] = password_hash(uniqid(),PASSWORD_DEFAULT);
?>
<h1 class="text-center fw-bolder"><?= isset($data['task_id']) ? "Detalhes da Tarefa" : "Nova Tarefa" ?></h1>
<hr class="mx-auto opacity-100" style="width:50px;height:3px">
<div class="col-lg-6 col-md-8 col-sm-12 col-12 mx-auto">
    <div class="card rounded-0">
        <div class="card-body">
            <div class="container-fluid">
                <form action="" id="task-form">
                    <input type="hidden" name="formToken" value="<?= $_SESSION['formToken']['task-form'] ?>">
                    <input type="hidden" name="task_id" value="<?= $data['task_id'] ?? '' ?>">
                    <div class="mb-3">
                        <label for="title" class="text-body-tertiary">Titulo</label>
                        <input type="text" class="form-control rounded-0" id="title" name="title" required="required" autofocus value="<?= $data['title'] ?? "" ?>">
                    </div>
                    <div class="mb-3">
                        <label for="description" class="text-body-tertiary">Descrição</label>
                        <textarea rows="5" class="form-control rounded-0" id="description" name="description" required="required" ><?= $data['description'] ?? "" ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="assigned_id" class="form-label">Atribuida à</label>
                        <select name="assigned_id" id="assigned_id" class="form-select rounded-0" requried>
                            <?php 
                            $employees = $conn->query("SELECT `user_id`, `fullname` FROM `user_list` where `type` = 2 and `status` = 1 order by `fullname` asc");
                            while($row = $employees->fetchArray()):
                            ?>
                            <option value="<?= $row['user_id'] ?>" <?= (isset($data['assigned_id']) && $data['assigned_id'] == $row['user_id']) ? 'selected' : '' ?>><?= $row['fullname'] ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select name="status" id="status" class="form-select rounded-0" requried>
                            <option value="0" <?= isset($data['status']) && $data['status'] == 0 ? "selected" : "" ?>>Pendente</option>
                            <option value="1" <?= isset($data['status']) && $data['status'] == 1 ? "selected" : "" ?>>Em Progresso</option>
                            <option value="2" <?= isset($data['status']) && $data['status'] == 2 ? "selected" : "" ?>>Em análise</option>
                            <option value="3" <?= isset($data['status']) && $data['status'] == 3 ? "selected" : "" ?>>Fechada</option>
                        </select>
                    </div>
                </form>
            </div>
        </div>
        <div class="card-footer">
            <div class="row justify-content-evenly">
                <button class="btn col-lg-4 col-md-5 col-sm-12 col-12 btn-primary rounded-0" form="task-form">Salvar</button>
                <a class="btn col-lg-4 col-md-5 col-sm-12 col-12 btn-secondary rounded-0" href='./?page=task'>Cancelar</a>
            </div>
        </div>
    </div>
</div>
<script>
    $(function(){
        $('#task-form').submit(function(e){
            e.preventDefault();
            $('.pop_msg').remove()
            var _this = $(this)
            var _el = $('<div>')
                _el.addClass('pop_msg')
            _this.find('button').attr('disabled',true)
            $.ajax({
                url:'./Master.php?a=save_task',
                method:'POST',
                data:$(this).serialize(),
                dataType:'JSON',
                error:err=>{
                    console.log(err)
                    _el.addClass('alert alert-danger')
                    _el.text("An error occurred.")
                    _this.prepend(_el)
                    _el.show('slow')
                    _this.find('button').attr('disabled',false)
                },
                success:function(resp){
                    if(resp.status == 'success'){
                        location.replace("./?page=task");
                    }else{
                        _el.addClass('alert alert-danger')
                    }
                    _el.text(resp.msg)

                    _el.hide()
                    _this.prepend(_el)
                    _el.show('slow')
                    _this.find('button').attr('disabled',false)
                }
            })
        })
    })
</script>