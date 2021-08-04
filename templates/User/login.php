<div class="body-user">
    <h1>Login user</h1>
    <?= $this->Form->create() ?>
    <div class="row">
        <div class="col-sm-2">
            <span>Email: </span>
        </div>
        <div class="col-sm-9">
            <input type="text" name="email" id="email">
        </div>
    </div>

    <div class="row">
        <div class="col-sm-2">
            <span>Password:  </span>
        </div>
        <div class="col-sm-9">
            <input type="password" name="password" id="password"></input>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-5"></div>
        <div class="col-sm-3">
            <?= $this->Html->link('Regist?', ['controller' => 'User', 'action' => 'regist']) ?>
        </div>
        <div class="col-sm-4">
            <button type="submit">Login</button>
        </div>
    </div>
    <?= $this->Form->end ?>
</div>
