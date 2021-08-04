<div class="body-user">
    <h1>Regist user</h1>
    <?= $this->Form->create() ?>
    <div class="row">
        <div class="col-sm-3">
            <span>E-mail: </span>
        </div>
        <div class="col-sm-8">
            <input type="text" name="email" id="email">
        </div>
    </div>

    <div class="row">
        <div class="col-sm-3">
            <span>Password: </span>
        </div>
        <div class="col-sm-8">
            <input type="password" name="password" id="password"></input>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-3">
            <span>Name: </span>
        </div>
        <div class="col-sm-8">
            <input type="text" name="name" id="name">
        </div>
    </div>

    <div class="row">
        <div class="col-sm-8"></div>
        <div class="col-sm-1">
            <?= $this->Html->link('Login?', ['controller' => 'User', 'action' => 'login']) ?>
        </div>
        <div class="col-sm-3">
            <button type="submit">Regist</button>
        </div>
    </div>
    <?= $this->Form->end ?>
</div>
