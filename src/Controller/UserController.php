<?php
declare(strict_types=1);

namespace App\Controller;

class UserController extends AppController
{
    /**
     * Undocumented function
     *
     * @return void
     */
    public function initialize(): void
    {
        parent::initialize();
        $this->loadModel('tUser');
        $this->loadComponent('Flash');
        $session = $this->request->getSession();
        if ($session->read('User.name')) {
            $this->redirect(['controller' => 'Chat', 'action' => 'feed']);
        }
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function login()
    {
        $title = 'Login';
        if ($this->request->is('post')) {
            $data = $this->request->getData();
            $user = $this->tUser->find()->where(
                [
                    'email' => $data['email'],
                    'password' => $data['password'],
                ]
            )->first();
            if ($user) {
                $this->request->getSession()->write(['User.id' => $user->id,'User.name' => $user->name, 'User.e-mail' => $user->email]);
                $this->redirect(['controller' => 'Chat', 'action' => 'feed']);
            } else {
                $this->Flash->error(__('Email or Password invalid'));
            }
        }
        $this->set(compact('title'));
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function regist()
    {
        $title = 'Regist';
        if ($this->request->is('post')) {
            $data = $this->request->getData();
            $userEmail = $this->tUser->find()->where(
                [
                    'email' => $data['email'],
                ]
            )->first();
            $userName = $this->tUser->find()->where(
                [
                    'name' => $data['name'],
                ]
            )->first();
            if ($userEmail) {
                $this->Flash->error(__('Email is exist'));
            } elseif ($userName) {
                $this->Flash->error(__('Name is exist'));
            } elseif ($data['name'] == '' || $data['email'] == '') {
                $this->Flash->error(__('Name or Email is not empty'));
            } else {
                $userNew = $this->tUser->newEntity($data);
                $this->tUser->save($userNew);
                $this->request->getSession()->write(['User.id' => $userNew->id, 'User.name' => $data['name'], 'User.e-mail' => $data['email']]);
                $this->redirect(['controller' => 'Chat', 'action' => 'feed']);
            }
        }
        $this->set(compact('title'));
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function logout()
    {
        $this->request->getSession()->destroy();
        $this->redirect(['action' => 'login']);
    }
}
