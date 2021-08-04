<?php

declare(strict_types=1);

namespace App\Controller;

class ChatController extends AppController
{
    /**
     * Undocumented function
     *
     * @return void
     */
    public function initialize(): void
    {
        parent::initialize();
        $this->loadModel('tFeed');
        $this->loadModel('tUser');
        $session = $this->request->getSession();
        if (!$session->read('User.e-mail')) {
            $this->redirect(['controller' => 'User', 'action' => 'login']);
        }
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function feed()
    {
        $messageTable = $this->getTableLocator()->get('tFeed');
        $messageNew = $messageTable->newEmptyEntity();

        if ($this->request->is('post')) {
            $data = $this->request->getData();
            $file = $data['file'];
            $fileName = $file->getClientFilename();
            if (!empty($fileName)) {
                $fileName = $this->saveFile($file);
            }
            $messageNew = $messageTable->patchEntity($messageNew, $data);
            $messageNew->image_file_name = $fileName;
            if (!empty($data['idStamp'])) {
                $messageNew->stamp_id  = $data['idStamp'];
            }
            $messageNew->user_id =  $this->request->getSession()->read('User.id');
            $messageTable->save($messageNew);
        }

        $messageTable = $this->getTableLocator()->get('tFeed');
        $messageNew = $messageTable->newEmptyEntity();
        $messages = $messageTable->find()->orderDesc('create_at');
        $title = 'Chat-Feed';
        $this->set(compact('messages', 'messageNew', 'title'));
    }

    /**
     * Undocumented function
     *
     * @param [type] $name : user name edit
     * @return void
     */
    public function edit($name): void
    {
        $session = $this->request->getSession();
        $user = $this->tUser->find()->where([
            'name' => $name,
        ])->first();
        if ($user) {

            $this->Flash->error(__('Your name exist ' . $name));
        } else {
            $user = $this->tUser->find()->where([
                'email' => $session->read('User.e-mail'),
            ])->first();
            $msg = $this->tFeed->find()->where([
                'name' => $user->name,
            ])->all();
            $user->name = $name;

            if ($this->tUser->save($user)) {
                foreach ($msg as $mess) {
                    $mess->name = $name;
                    $this->tFeed->save($mess);
                }
                $this->Flash->success(__('Your name changed '));
                $session->write(['User.name' => $user->name]);
            }
        }

        $this->redirect(['controller' => 'Chat', 'action' => 'feed']);
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function editMessage()
    {
        if ($this->request->is('post')) {
            $data = $this->request->getData();

            $msg = $this->tFeed->find()->where([
                'id' => $data['idMsg'],
            ])->first();
            $msg->message = $data['message'];
            $pathFile = WWW_ROOT . 'img' . DS . 'upload' . DS . $msg->image_file_name;
            $file = $data['file'];
            $fileName = $file->getClientFilename();
            if (!empty($fileName)) {
                if (file_exists($pathFile)) {
                    unlink($pathFile);
                }
                $msg->image_file_name = $this->saveFile($file);
            }
            if (!empty($data['idStamp'])) {
                $msg->stamp_id  = $data['idStamp'];
            }
            if ($this->tFeed->save($msg)) {
                $this->Flash->success('Edited!');
            } else {
                $this->Flash->error("Can't edit");
            }
        }
        $this->redirect(['controller' => 'Chat', 'action' => 'feed']);
    }

    /**
     * Undocumented function
     *
     * @param [type] $id id of Message delete
     * @return void
     */
    public function deleteMessage($id)
    {
        $msg = $this->tFeed->find()->where([
            'id' => $id,
        ])->first();
        $pathFile = WWW_ROOT . 'img' . DS . 'upload' . DS . $msg->image_file_name;
        $this->tFeed->delete($msg);
        if (file_exists($pathFile)) {
            unlink($pathFile);
        }
        $this->redirect(['controller' => 'Chat', 'action' => 'feed']);
    }

    /**
     * Undocumented function
     *
     * @param [type] $file
     * @return void
     */
    public function saveFile($file)
    {
        $fileName = $file->getClientFilename();
        $fileType = $file->getClientMediaType();
        $pathSave = WWW_ROOT . 'img' . DS . 'upload' . DS . $fileName;
        while (file_exists($pathSave)) {
            $fileName = rand(0, 99999) . $fileName;
            $pathSave = WWW_ROOT . 'img' . DS . 'upload' . DS . $fileName;
        }
        if ($fileType == 'image/jpeg' || $fileType == 'image/jpg' || $fileType == 'image/png' || $fileType == 'image/gif' || $fileType == 'video/mp4' || $fileType == 'video/avi' || $fileType == 'video/mov') {
            if ($file->getSize() > 0 && $file->getError() == 0) {
                $file->moveTo($pathSave);
            }
        }
        return $fileName;
    }

    public function feedRealTime()
    {
        $data = $this->request->getData();
        $messageNew =  $this->tFeed->newEmptyEntity();
        $messageNew = $this->tFeed->patchEntity($messageNew, $data);
        $file = $data['file'];
        $fileName = $file->getClientFilename();
        // if (!empty($fileName)) {
        //     $fileName = $this->saveFile($file);
        // }
        $messageNew->image_file_name = $fileName;
        // if (!empty($data['idStamp'])) {
        //     $messageNew->stamp_id  = $data['idStamp'];
        // }
        $messageNew->name = $this->request->getSession()->read('User.name');
        $messageNew->user_id =  $this->request->getSession()->read('User.id');
        $this->tFeed->save($messageNew);
        // return $data;
        // echo "xxx";
        exit();
    }
}
