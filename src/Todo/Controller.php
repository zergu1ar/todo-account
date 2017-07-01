<?php
/**
 * Created by PhpStorm.
 * User: alexey
 * Date: 01.07.17
 * Time: 13:59
 */

namespace Todo;

use Psr\Container\ContainerInterface;
use \Slim\Http\Request as Request;
use \Slim\Http\Response as Response;
use Todo\User\Manager;
use Todo\User\Entity as User;
use Todo\Crypt\Coder;
use Todo\Session\Manager as Session;
use Todo\Validator\IValidator;

class Controller
{
    private $manager;
    private $validator;
    private $session;

    /**
     * Controller constructor.
     * @param ContainerInterface $container
     * @param IValidator $validator
     * @param Session $session
     */
    public function __construct(ContainerInterface $container, IValidator $validator, Session $session)
    {
        $this->manager = new Manager($container);
        $this->validator = $validator;
        $this->session = $session;
    }

    /**
     * @param Request $request
     * @param Response $response
     *
     * @return mixed
     */
    public function register(Request $request, Response $response)
    {
        $login = trim($request->getParam('login'));
        $password = trim($request->getParam('password'));
        $error = [];

        $loginValid = $this->validator->validateLogin($login);
        $passwordValid = $this->validator->validatePassword($password);
        if (!empty($loginValid) || !empty($passwordValid)) {
            $error = array_merge(
                ['login' => $loginValid],
                ['password' => $passwordValid]
            );
        }
        if (empty($error) && !$this->manager->isExists($login)) {
            $user = $this->manager->save(new User($login, $this->encryptPwd($password)));
            if ($user) {
                return $this->auth($request, $response);
            }
            $error = ['common' => 'Error, while save user'];
        } else if (empty($error)) {
            $error = ['login' => 'User already registered'];
        }
        return $this->sendResponse($response, ['error' => $error], 200);
    }

    /**
     * @param string $password
     *
     * @return string
     */
    private function encryptPwd($password)
    {
        return Coder::encrypt($password);
    }

    /**
     * @param Response $response
     * @param array $data
     * @param int $code
     *
     * @return mixed
     */
    private function sendResponse(Response $response, $data, $code)
    {
        return $response->withJson(
            $data,
            $code
        );
    }

    /**
     * @param Request $request
     * @param Response $response
     *
     * @return mixed
     */
    public function auth(Request $request, Response $response)
    {
        $login = trim($request->getParam('login'));
        $pwd = trim($request->getParam('password'));
        $user = $this->manager->getUserByLoginAndPwd($login, $this->encryptPwd($pwd));
        if (!$user) {
            return $this->sendResponse(
                $response,
                [
                    'error' => [
                        'common' => 'Incorrect Login or Password'
                    ]
                ],
                403
            );
        }

        return $this->sendResponse(
            $response,
            [
                'error' => null,
                'response' => $this->session->createSession($user)
            ],
            200
        );

    }

}