<?php 
$sql = "SELECT `user_id`, `fullname`, `username`, `status`, `type` FROM `user_list` where `user_id` NOT IN (1, {$_SESSION['user_id']})";
$query = $conn->query($sql);
?>
<h1 class="text-center fw-bolder">Lista de Usuários</h1>
<hr class="mx-auto opacity-100" style="width:50px;height:3px">
<div class="card rounded-0">
    <div class="card-body">
        <div class="container">

            <div class="table-responsive">
                <table class="table table-striped table-bordered table-sm">
                    <colgroup>
                        <col width="5%">
                        <col width="30%">
                        <col width="20%">
                        <col width="15%">
                        <col width="15%">
                        <col width="15%">
                    </colgroup>
                    <thead>
                        <tr>
                            <th class="text-center">ID</th>
                            <th class="text-center">Nome</th>
                            <th class="text-center">Usuário</th>
                            <th class="text-center">Tipo</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Ação</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = $query->fetchArray()): ?>
                            <tr>
                                <td class="text-center"><?= $row['user_id'] ?></td>
                                <td><?= $row['fullname'] ?></td>
                                <td><?= $row['username'] ?></td>
                                <td>
                                    <?php 
                                    switch($row['type']){
                                        case 0:
                                            echo 'Administrador';
                                            break;
                                        case 1:
                                            echo 'Gerente de Projeto';
                                            break;
                                        case 2:
                                            echo 'Funcionário';
                                            break;
                                        default:
                                            echo "N/A";
                                            break;
                                    }
                                    ?>
                                </td>
                                <td class="text-center">
                                    <?php 
                                        switch($row['status']){
                                            case 0:
                                                echo "<small class='text-body-tertiary'>Pendente</small>";
                                                break;
                                            case 1:
                                                echo "<small class='text-body-tertiary'>Aprovado</small>";
                                                break;
                                            case 2:
                                                echo "<small class='text-danger-emphasis'>Negado</small>";
                                                break;
                                            case 3:
                                                echo "<small class='text-danger'>Bloqueado</small>";
                                                break;
                                        }
                                    ?>

                                </td>
                                <td class="text-center">
                                    <div class="btn-group btn-group-sm">
                                        <a href="./?page=manage_user&id=<?= $row['user_id'] ?>" class="btn btn-sm btn-primary rounded-0">Gerenciar</a>
                                    </div>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                        <?php if(!$query->fetchArray()): ?>
                            <tr>
                                <td colspan="6" class="text-center">No data found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>