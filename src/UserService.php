<?php

namespace Microservices;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Http;

class UserService
{
    // private $endpoint = 'http://nginx:8014/api';
    private $endpoint;

    public function __construct()
    {
        $this->endpoint = env('USERS_ENDPOINT');
    }

    public function headers()
    {
        return [
            'Authorization' => request()->headers->get('Authorization'),
        ];
    }

    public function request()
    {
        $headers = $this->headers();
        return Http::withHeaders($headers);
    }

    public function getUser()
    {
        $json = $this->request()->get($this->endpoint . '/user')->json();

        return new User($json);
    }

    public function isAdmin()
    {
        $response = $this->request()->get($this->endpoint . '/admin');

        return $response->successful();
    }

    public function isInfluencer()
    {
        $response = $this->request()->get($this->endpoint . '/influencer');

        return $response->successful();
    }

    public function allows($ability, $arguments = [])
    {
        $user = $this->getUser();
        return Gate::forUser($user)->authorize($ability, $arguments);
    }

    public function all($page = 1)
    {
        $response = $this->request()->get($this->endpoint . '/users?page=' . $page);
        return $response->json();
    }

    public function get($id)
    {
        $response = $this->request()->get($this->endpoint . '/users/' . $id);
        if (!$response instanceof Response || !$response->successful()) {
            return null;
        }

        $json = $response->json();
        return is_array($json) ? new User($json) : null;
    }

    public function create($data)
    {
        $json = $this->request()->post($this->endpoint . '/users', $data);
        return new User($json);
    }

    public function update($id, $data)
    {
        $json = $this->request()->put($this->endpoint . '/users/' . $id, $data);
        return new User($json);
    }

    public function delete($id)
    {
        return $this->request()->delete($this->endpoint . '/users/' . $id)->successful();
    }
}
