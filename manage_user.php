<?php 
if(!isset($_GET['id']) || (isset($_GET['id']) && $_GET['id'] <= 0)){
    throw new ErrorException("Error: ". $conn->lastErrorMsg());
    exit;
}
$sql = "SELECT `user_id`, `fullname`, `username`, `status`, `type` FROM `user_list` where `user_id` = '{$_GET['id']}' ";
$query = $conn->query($sql);
$data = $query->fetchArray();
if(empty($data)){
    throw new ErrorException("Error: ". $conn->lastErrorMsg());
    exit;
}

$_SESSION['formToken']['manage_user'] = password_hash(uniqid(),PASSWORD_DEFAULT);
?>
<h1 class="text-center fw-bolder">Gerenciamento de usuários</h1>
<hr class="mx-auto opacity-100" style="width:50px;height:3px">
<div class="col-lg-6 col-md-8 col-sm-12 col-12 mx-auto">
    <div class="card rounded-0">
        <div class="card-body">
            <div class="container-fluid">
                <form action="" id="update-user">
                    <input type="hidden" name="formToken" value="<?= $_SESSION['formToken']['manage_user'] ?>">
                    <input type="hidden" name="user_id" value="<?= $_GET['id'] ?? '' ?>">
                    <dl>
                        <dt class="text-body-tertiary">Nome</dt>
                        <dd class="ps-4"><?= $data['fullname'] ?? '' ?></dd>
                        <dt class="text-body-tertiary">Usuário</dt>
                        <dd class="ps-4"><?= $data['username'] ?? '' ?></dd>
                    </dl>
                    <div class="mb-3">
                        <label for="type" class="form-label">Tipo de usuário</label>
                        <select name="type" id="type" class="form-select rounded-0" requried>
                            <option value="0" <?= isset($data['type']) && $data['type'] == 0 ? "selected" : "" ?>>Administrador</option>
                            <option value="1" <?= isset($data['type']) && $data['type'] == 1 ? "selected" : "" ?>>Gerente de Projeto</option>
                            <option value="2" <?= isset($data['type']) && $data['type'] == 2 ? "selected" : "" ?>>Funcionário</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select name="status" id="status" class="form-select rounded-0" requried>
                            <option value="0" <?= isset($data['status']) && $data['status'] == 0 ? "selected" : "" ?>>Pendente</option>
                            <option value="1" <?= isset($data['status']) && $data['status'] == 1 ? "selected" : "" ?>>Aprovado</option>
                            <option value="2" <?= isset($data['status']) && $data['status'] == 2 ? "selected" : "" ?>>Negado</option>
                            <option value="3" <?= isset($data['status']) && $data['status'] == 3 ? "selected" : "" ?>>Bloqueado</option>
                        </select>
                    </div>
                </form>
            </div>
        </div>
        <div class="card-footer">
            <div class="row justify-content-evenly">
                <button class="btn col-lg-4 col-md-5 col-sm-12 col-12 btn-primary rounded-0" form="update-user">Atualizar</button>
                <a class="btn col-lg-4 col-md-5 col-sm-12 col-12 btn-secondary rounded-0" href='./?page=users'>Cancelar</a>
            </div>
        </div>
    </div>
</div>
<script>
    $(function(){
        $('#update-user').submit(function(e){
            e.preventDefault();
            $('.pop_msg').remove()
            var _this = $(this)
            var _el = $('<div>')
                _el.addClass('pop_msg')
            _this.find('button').attr('disabled',true)
            $.ajax({
                url:'./LoginRegistration.php?a=update_user',
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
                        _el.addClass('alert alert-success')
                        setTimeout(() => {
                            location.replace("./?page=users");
                        }, 2000);
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