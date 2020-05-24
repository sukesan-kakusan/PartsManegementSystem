<?= $this->Html->css('style'); ?>

<!--ナビゲーションバー-->
<nav class="navbar navbar-expand navbar-light fixed-top bg-primary">
    <a class="navbar-brand" href="#">
        <font color="white">PartsManagementSystem</font>
    </a>
    <div class="collapse navbar-collapse justify-content-end">
        <ul class="navbar-nav">
            <form class="form-inline">
                <input class="form-control mr-sm-2" type="search" aria-label="Search">
                <button class="btn btn-info my-2 my-sm-0" type="submit">Search</button>
            </form>
            <li class="nav-item">
                <a class="nav-link " href="#">
                    <font color="white">ログアウト</font>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">
                    <font color="white">部品を追加</font>
                </a>
            </li>
        </ul>
    </div>
</nav>

<!--検索結果-->
<table>
    <tr>
        <th>Id</th>
        <th>Username</th>
    </tr>
    <?php foreach ($users as $user): ?>
    <tr>
        <td><?= $user->id ?></td>
        <td><?= $this->Html->link($user->username, ['action' => 'view', $user->id]) ?></td>
    </tr>
    <?php endforeach; ?>
</table>