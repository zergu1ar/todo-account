<?php
/**
 * Created by PhpStorm.
 * User: alexey
 * Date: 01.07.17
 * Time: 13:59
 */

namespace Todo;

use Psr\Container\ContainerInterface;
use \Slim\Http\Request;
use \Slim\Http\Response;
use Todo\User\Entity;
use Todo\User\Manager;
use Todo\User\Entity as User;
use Todo\Crypt\Coder;
use Todo\Session\Manager as Session;
use Todo\Validator\CheckerInterface;

class Controller
{
    /** @var Manager */
    private $manager;
    /** @var CheckerInterface */
    private $validator;
    /** @var Session */
    private $session;

    /**
     * Controller constructor.
     * @param ContainerInterface $container
     * @param CheckerInterface $validator
     * @param Session $session
     */
    public function __construct(ContainerInterface $container, CheckerInterface $validator, Session $session)
    {
        $this->manager = new Manager($container->get('db'));
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

        $loginValid = $this->validator->validateString($login);
        $passwordValid = $this->validator->validateString($password);
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
            $error = ['login' => ['User already registered']];
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
                200
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

    /**
     * @param Request $request
     * @param Response $response
     *
     * @return mixed
     */
    public function checkAuth(Request $request, Response $response)
    {
        return $this->sendResponse(
            $response,
            [
                'response' => $this->session->validateSession(
                    $request->getParam('userId'),
                    $request->getParam('token')
                )
            ],
            200
        );
    }

    /**
     * @param Request $request
     * @param Response $response
     *
     * @return mixed
     */
    public function logout(Request $request, Response $response)
    {

        return $this->sendResponse(
            $response,
            [
                'response' => $this->session->dropSession(
                    $request->getParam('userId'),
                    $request->getParam('token')
                )
            ],
            200
        );
    }

    /**
     * @param Request $request
     * @param Response $response
     *
     * @return mixed
     */
    public function findUser(Request $request, Response $response)
    {
        if ($this->session->validateSession($request->getParam('userId'), $request->getParam('token'))) {
            $username = $request->getParam('username');
            $userNameValid = $this->validator->validateString($username);
            if (empty($userNameValid)) {
                /** @var Entity $user */
                $user = $this->manager->getOne(
                    ['login' => $username]
                );
                if ($user) {
                    return $this->responseUser($user, $response);
                }
                $error = ['username' => 'User not found'];
            } else {
                $error = ['username' => $userNameValid];
            }
        } else {
            $error = ['auth' => 'Invalid session'];
        }

        return $this->sendResponse(
            $response,
            [
                'error' => $error
            ],
            200
        );
    }

    /**
     * @param Entity $user
     * @param Response $response
     *
     * @return mixed
     */
    private function responseUser($user, Response $response)
    {
        return $this->sendResponse(
            $response,
            [
                'error' => null,
                'response' => [
                    'user' => $user->toArray(['password'])
                ]
            ],
            200
        );
    }

    /**
     * @param Request $request
     * @param Response $response
     *
     * @return mixed
     */
    public function getUserNameById(Request $request, Response $response)
    {
        /** @var Entity $user */
        $user = $this->manager->getById(
            $request->getParam('id')
        );
        if ($user) {
            return $this->sendResponse(
                $response,
                [
                    'error' => null,
                    'response' => [
                        'username' => $user->getLogin()
                    ]
                ],
                200
            );
        }

        return $this->sendResponse(
            $response,
            [
                'error' => ['id' => 'User not found']
            ],
            200
        );
    }

}