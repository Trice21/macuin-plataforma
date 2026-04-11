<?php

namespace App\Auth;

use App\MacuinApi;
use Illuminate\Auth\GenericUser;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Support\Facades\Http;

class ApiUserProvider implements UserProvider
{
    public function retrieveById($identifier)
    {
        $token = session('jwt_token');
        if (! $token) {
            return null;
        }

        $response = Http::timeout(15)
            ->withToken($token)
            ->acceptJson()
            ->get(MacuinApi::url().'/auth/me');

        if (! $response->successful()) {
            return null;
        }

        $data = $response->json();
        if ((int) ($data['id'] ?? 0) !== (int) $identifier) {
            return null;
        }

        return $this->mapUser($data);
    }

    public function retrieveByToken($identifier, $token)
    {
        return null;
    }

    public function updateRememberToken(Authenticatable $user, $token): void
    {
        //
    }

    public function retrieveByCredentials(array $credentials)
    {
        return null;
    }

    public function validateCredentials(Authenticatable $user, array $credentials)
    {
        return false;
    }

    public function rehashPasswordIfRequired(Authenticatable $user, #[\SensitiveParameter] array $credentials, bool $force = false): void
    {
        // Sin contraseña en almacenamiento local; el hash vive en la API.
    }

    /**
     * @param  array<string, mixed>  $data
     */
    protected function mapUser(array $data): GenericUser
    {
        return new GenericUser([
            'id' => $data['id'],
            'name' => $data['name'],
            'email' => $data['email'],
            'role' => $data['role'] ?? null,
            'password' => '',
            'remember_token' => null,
        ]);
    }
}
