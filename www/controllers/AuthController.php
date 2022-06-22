<?php


namespace app\controllers;


use app\constants\Template;
use app\core\Controller;
use app\core\Request;
use app\models\RegisterModel;

class AuthController extends Controller
{
    public function login() {
        $this->setLayout(Template::NAME_AUTH);
        return $this->render('login');
    }

    public function register(Request $request) {
        $registerModel = new RegisterModel();

        if ($request->isPost()) {
            $registerModel->loadData($request->getBody());

            if ($registerModel->validate() && $registerModel->register()) {
                return 'Success';
            }

            return $this->render('register', [
                'model' => $registerModel,
            ]);
        }

        $this->setLayout(Template::NAME_AUTH);

        return $this->render('register', [
            'model' => $registerModel,
        ]);
    }
}