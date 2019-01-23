<?php
namespace app\commands;

use app\models\User;
use yii\console\Controller;

class UserController extends Controller
{
    /**
     * @var array
     */
    protected $actionOptions = [
        'create-user' => [
            'username',
            'password',
        ],
    ];

    /**
     * @var string
     */
    public $username;

    /**
     * @var string
     */
    public $password;

    /**
     * {@inheritDoc}
     * @see \yii\console\Controller::options()
     */
    public function options($actionID)
    {
        $result = [];
        if (isset($this->actionOptions[$actionID])) {
            $result = $this->actionOptions[$actionID];
        }
        return $result;
    }

    /**
     * Syntax
     *   ./yii user/create-user --username=<username> --password=<password>
     */
    public function actionCreateUser()
    {
        User::getDb()->transaction(function() {
            $user = User::findOne(['username' => $this->username]);
            if (!$user) {
                $user = new User(['username' => $this->username]);
            }
            $fields = ['password'];
            foreach ($fields as $field) {
                if ($this->$field) {
                    $user->$field = $this->$field;
                }
            }
            $user->save();
        });
    }
}
