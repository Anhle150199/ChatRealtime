<h1>Feed</h1>
<div class="row">
    <div class="col-lg-3"></div>
    <div class="col-lg-6">
        <div>
            <?= $this->Form->create($messageNew, ['id' => 'myForm', 'onsubmit' => 'return validateForm()', 'enctype' => 'multipart/form-data']); ?>
            <div class="row">
                <div class="col-sm-2">
                    <span>Name: </span>
                </div>
                <div class="col-sm-5">
                    <input type="hidden" name="idMsg" id="idMsg" value="" />
                    <input type="hidden" name="idStamp" id="idStamp" value="" />
                    <input type="file" name="file" id="file" value="" class="hidden" />
                    <input type="text" name="name" id="name" value="<?= $this->request->getSession()->read('User.name'); ?>">
                </div>
                <div class="col-sm-5">
                    <button type="submit" class="btn btn-success" id="post"><span class="glyphicon glyphicon-ok"></span></p></button>
                    <a onclick="editName()"><button type="button" class="btn btn-info">Edit</button></a>
                    <button type="button" class="btn btn-info" onclick="inputPhoto()"><span class="glyphicon glyphicon-paperclip"></span></button>
                    <a href="user/logout"><button type="button" class="btn btn-danger"><span class="glyphicon glyphicon-log-out"></span></button></a>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-2">
                    <span>Message: </span>
                </div>
                <div class="col-sm-10">
                    <div class="row">
                        <textarea name="message" id="message"></textarea>&nbsp;&nbsp;
                        <button type="button" id='btn-cancel' class="btn btn-danger" hidden onclick="cancelEdit()"><span class="glyphicon glyphicon-remove"></span></button>

                    </div>
                </div>
            </div>
            <div class="row stamps">
                <?php
                $number = range(01, 24);
                foreach ($number as $num) {
                    $num = sprintf("%02d", $num); ?>
                    <img src="/stamps/<?= h($num) ?>.png" onclick="addStamp(<?= $num ?>)">
                <?php } ?>
            </div>
            <?= $this->Form->end ?>
        </div>
        <div id="validation_error"></div>
        <div class="" id="message-area"></div>
        <div id="noidung"></div>

        <div data-spy="scroll" data-target=".navbar" data-offset="50">
            <?php foreach ($messages as $msg) : ?>
                <div class="container-fluid shadow-lg msg-list ">
                    <div class="row">
                        <div class="col-lg-9">
                            <p class=""> <?= h($msg->name) ?> :&nbsp;
                                <?= h($msg->message) ?> &nbsp;&nbsp;
                                <?= h($msg->create_at) ?>
                            </p>
                            <?php if ($msg->image_file_name != "") : ?>
                                <?php $fileName = explode(".", $msg->image_file_name);
                                $fileName = $fileName[count($fileName) - 1];
                                if ($fileName == "jpeg" || $fileName == "jpg" || $fileName == "png" || $fileName == "gif") :
                                ?>
                                    <img src="/img/upload/<?= h($msg->image_file_name) ?>" class="img-msg">
                                <?php else : ?>
                                    <video src="/img/upload/<?= h($msg->image_file_name) ?>" class="img-msg" controls></video>
                                <?php endif ?>
                            <?php endif ?>
                            <?php if ($msg->stamp_id != "") :
                                $num = sprintf("%02d", $msg->stamp_id); ?>
                                <img src="/stamps/<?= h($num) ?>.png">
                            <?php endif ?>
                        </div>
                        <div class="col-lg-3">
                            <?php if ($msg->name == $this->request->getSession()->read('User.name')) : ?>
                                <input type="hidden" id="msg<?= $msg->id ?>" value="<?= $msg->message ?>">
                                <a onclick="editMsg(<?= $msg->id ?>)"><button type="button" class="btn btn-info" id="btn-edit"><span class="glyphicon glyphicon-edit"></span></button></a>
                                <a onclick="delMsg(<?= $msg->id ?>)"><button type="button" class="btn btn-danger"><span class="glyphicon glyphicon-trash"></span></button></a>
                            <?php endif ?>

                        </div>
                    </div>
                </div>
            <?php endforeach ?>
        </div>

    </div>
    <div class="col-lg-3"></div>

</div>


<style>
    .stamps {
        width: 77%;
        margin-left: 15%;
        overflow-x: auto;

    }

    .msg-list {
        flex: auto;
        margin: auto;
        margin-bottom: 10px;
        min-height: 40px;
        height: auto;
        margin-right: auto;
        padding: 10px;
        padding-left: 3%;
        padding-right: 3%;
        border-radius: 2rem;
        border: 2px solid gray;
    }

    .img-msg {
        width: 40%;
        height: auto;
        max-height: 350px;
    }

    #message {
        width: 89%;
    }
</style>

<script>
    var post = document.getElementById('post');
    var msg = document.getElementById('message');
    var idMsg = document.getElementById('idMsg');
    var idStamp = document.getElementById('idStamp');

    var btnCancel = document.getElementById('btn-cancel');
    var form = document.getElementById('myForm');
    var file = document.getElementById('file');

    function validateForm() {

        var name = document.getElementById('name').value;
        if (name == "") {
            alert("Please, Enter your name");
            return false;
        }

        if (msg.value == "" && file.value == "" && idStamp.value == "") {
            alert("Please, Enter your Message or Media or Sticker");
            return false;
        }

        if (file.value != "") {
            var extension = file.value.split(/[\s,]+/);
            extension = extension[extension.length - 1];
            console.log(extension);
            if (!/(mp4|avi|mov|jpeg|jpg|png|gif)$/ig.test(extension)) {
                alert("Invalid file type: " + extension + ".  Please use mp4, avi, mov, jpeg, jpg, png, gif.");
                $("#file").val("");
                return false;
            }
        }

        if (idMsg.value != "") {
            form.action = "/editMessage";
        }
    }

    function editName() {
        var name = document.getElementById('name').value;
        window.location.href = '/editName/' + name;
    }

    function inputPhoto() {
        file.click();
    }

    function addStamp(id) {
        idStamp.value = id;
        post.click();
    }

    function editMsg(id) {
        var msgEdit = document.getElementById('msg' + id).value;
        msg.value = msgEdit;
        idMsg.value = id;
        btnCancel.hidden = false;
    }

    function cancelEdit() {
        idMsg.value = "";
        msg.value = "";
        btnCancel.hidden = true;
    }

    function delMsg(id) {
        window.location.href = '/deleteMessage/' + id;
    }

    if (window.history.replaceState) {
        window.history.replaceState(null, null, window.location.href);
    }
</script>
<script>
    $(document).ready(function() {
        var conn = new WebSocket('ws://localhost:8889');
        conn.onopen = function(e) {
            console.log("Connection established!");
        };

        conn.onmessage = function(e) {
            console.log(e.data);
            var data = JSON.parse(e.data);
            var html_data = "<p>" + data.name + ":" + data.msg + "</p>"
            $('#message-area').append(html_data);

        };

        $('#myForm').action = '';
        $('#myForm').on('submit', function(event) {
            event.preventDefault();
            if (validateForm() != false) {
                var userName = $('#name').val();
                var msg = $('#message').val();
                var file = $('#file').val();

                var data = {
                    name: userName,
                    msg: msg
                };
                conn.send(JSON.stringify(data));

                // var request = $.ajax({
                //     url: "<?= $this->Url->build(['controller' => 'Chat', 'action' => 'feedRealTime']) ?>",
                //     type: 'POST',
                //     dataType: "array",
                //     data: {
                //         message: msg,
                //         file: file,

                //     },
                //     headers: {
                //         'X-CSRF-Token': $('meta[name="csrfToken"]').attr('content')
                //     }
                // })
            }
        })
    })
</script>
