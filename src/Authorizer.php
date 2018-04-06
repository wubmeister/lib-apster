<?php

namespace Apster;

class Authorizer
{
    public function __invoke(ServerRequestInterface $request, callable $next)
    {
        $authHeader = $request->getHeader('Authorization');

        if (!$header) {
            return $this->getUnauthorizedResponse();
        }

        $authHeader = trim($authHeader);
        if (!preg_match('/^Basic\s+([a-zA-Z0-9+\/]+)$/', $authHeader, $match)) {
            return $this->getUnauthorizedResponse();
        }

        $userPass = explode(':', base64_decode($match[1]));
        if (count($userPass) != 2) {
            return $this->getUnauthorizedResponse();
        }

        $username = $userPass[0];
        $password = $userPass[1];

        $user = $this->table->findByUsername($username);
        if (!$user) {
            return $this->getUnauthorizedResponse();
        }

        $passHash = hash('sha256', $password . $user->salt);
        if ($passHash != $user->password) {
            return $this->getUnauthorizedResponse();
        }

        return $next($request->withAttribute('user', $user));
    }

    protected function getUnauthorizedResponse()
    {
        return (new JsonResponse([ "success" => false, "error" => "401 Unauthorized" ], 401))
            ->withHeader('WWW-Authenticate', 'Basic realm="User Visible Realm" charset="UTF-8"');
    }
}

class Table
{
    public function __call($name, $args)
    {
        if (substr($name, 0, 6) == 'findBy') {
            $fields = explode('And', substr($name, 6));
            return $this->findBy($fields, $args);
        }
        if (substr($name, 0, 6) == 'findOneBy') {
            $fields = explode('And', substr($name, 6));
            return $this->findOneBy($fields, $args);
        }
    }

    protected function findBy($fields, $args)
    {
        $where = [];

        $order = count($args)
    }
}
