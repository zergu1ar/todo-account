<?php

namespace Zergular\Todo;

use Zergular\Todo\Crypt\Coder;
use Slim\Http\Request;
use Slim\Http\Response;
use Zergular\Todo\User\UserInterface;
use Zergular\Todo\User\Entity as User;
use Zergular\Todo\Session\SessionManagerInterface;
use Zergular\Todo\User\UserManagerInterface;
use Zergular\Todo\Validator\CheckerInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Class Controller
 * @package Zergular\Todo
 */
class Controller
{
    /** @var UserManagerInterface */
    private $manager;
    /** @var CheckerInterface */
    private $validator;
    /** @var SessionManagerInterface */
    private $session;

    /**
     * Controller constructor.
     * @param CheckerInterface $validator
     * @param SessionManagerInterface $session
     * @param UserManagerInterface $manager
     */
    public function __construct(
        CheckerInterface $validator,
        SessionManagerInterface $session,
        UserManagerInterface $manager
    ) {
        $this->manager = $manager;
        $this->validator = $validator;
        $this->session = $session;
    }

    /**
     * @param RequestInterface $request
     * @param ResponseInterface $response
     *
     * @return mixed
     */
    public function register(RequestInterface $request, ResponseInterface $response)
    {
        /** @var Request $request */
        $login = trim($request->getParam('login'));
        $password = trim($request->getParam('password'));

        $error = [];
        $loginValidError = $this->validator->validateString($login);
        $passwordValidError = $this->validator->validateString($password);
        if (!empty($loginValidError) || !empty($passwordValidError)) {
            $error = array_merge(
                ['login' => $loginValidError],
                ['password' => $passwordValidError]
            );
        }
        if (empty($error) && !$this->manager->isExists($login)) {
            $user = $this->manager->save(new User($login, $this->encryptPwd($password)));
            if ($user) {
                return $this->auth($request, $response);
            }
            $error = ['common' => 'Error, while save user'];
        } elseif (empty($error)) {
            $error = ['login' => ['User already registered']];
        }
        return $this->sendResponse($response, ['error' => $error]);
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
     * @param ResponseInterface $response
     * @param array $data
     * @param int $code
     *
     * @return mixed
     */
    private function sendResponse(ResponseInterface $response, $data, $code = 200)
    {
        /** @var Response $response */
        return $response->withJson(
            $data,
            $code
        );
    }

    /**
     * @param RequestInterface $request
     * @param ResponseInterface $response
     *
     * @return mixed
     */
    public function auth(RequestInterface $request, ResponseInterface $response)
    {
        /** @var Request $request */
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
                ]
            );
        }

        return $this->sendResponse(
            $response,
            [
                'error' => NULL,
                'response' => $this->session->createSession($user)
            ]
        );

    }

    /**
     * @param RequestInterface $request
     * @param ResponseInterface $response
     *
     * @return mixed
     */
    public function checkAuth(RequestInterface $request, ResponseInterface $response)
    {
        /** @var Request $request */
        return $this->sendResponse(
            $response,
            [
                'response' => $this->session->validateSession(
                    $request->getParam('userId'),
                    $request->getParam('token')
                )
            ]
        );
    }

    /**
     * @param RequestInterface $request
     * @param ResponseInterface $response
     *
     * @return mixed
     */
    public function logout(RequestInterface $request, ResponseInterface $response)
    {
        /** @var Request $request */
        return $this->sendResponse(
            $response,
            [
                'response' => $this->session->dropSession(
                    $request->getParam('userId'),
                    $request->getParam('token')
                )
            ]
        );
    }

    /**
     * @param RequestInterface $request
     * @param ResponseInterface $response
     *
     * @return mixed
     */
    public function findUser(RequestInterface $request, ResponseInterface $response)
    {
        /** @var Request $request */
        if ($this->session->validateSession($request->getParam('userId'), $request->getParam('token'))) {
            $username = $request->getParam('username');
            $userNameValidError = $this->validator->validateString($username);
            if (empty($userNameValidError)) {
                /** @var UserInterface $user */
                $user = $this->manager->getOne(
                    ['login' => $username]
                );
                if ($user) {
                    return $this->responseUser($user, $response);
                }
            }
            $error = [
                'username' => empty($userNameValidError)
                    ? 'User not found'
                    : $userNameValidError
            ];
        } else {
            $error = ['auth' => 'Invalid session'];
        }

        return $this->sendResponse($response, ['error' => $error]);
    }

    /**
     * @param UserInterface $user
     * @param ResponseInterface $response
     *
     * @return mixed
     */
    private function responseUser(UserInterface $user, ResponseInterface $response)
    {
        return $this->sendResponse(
            $response,
            [
                'error' => NULL,
                'response' => [
                    'user' => $user->toArray(['password'])
                ]
            ]
        );
    }

    /**
     * @param RequestInterface $request
     * @param ResponseInterface $response
     *
     * @return mixed
     */
    public function getUserNameById(RequestInterface $request, ResponseInterface $response)
    {
        /**
         * @var Request $request
         * @var UserInterface $user
         */
        $user = $this->manager->getById($request->getParam('id'));
        if ($user) {
            return $this->sendResponse(
                $response,
                [
                    'error' => NULL,
                    'response' => [
                        'username' => $user->getLogin()
                    ]
                ]
            );
        }
        return $this->sendResponse($response, ['error' => ['id' => 'User not found']]);
    }
}
