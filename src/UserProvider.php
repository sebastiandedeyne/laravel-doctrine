<?php

namespace Sebdd\LaravelDoctrine;

use Doctrine\ORM\EntityManager;
use Illuminate\Contracts\Auth\UserProvider as UserProviderContract;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Hashing\Hasher;

class UserProvider implements UserProviderContract
{
    /**
     * An instance of the entity manager.
     * 
     * @var \Doctrine\ORM\EntityManager
     */
    protected $entityManager;

    /**
     * A user entity repository.
     * 
     * @var \Doctrine\ORM\EntityRepository
     */
    protected $repository;

    /**
     * The identifier's column name.
     * 
     * @var string
     */
    protected $identifierName;

    /**
     * The remember_token's column name.
     * 
     * @var string
     */
    protected $rememberTokenName;

    /**
     * Hasher instance.
     * 
     * @var \Illuminate\Contracts\Hashing\Hasher
     */
    protected $hasher;

    /**
     * Constructor method.
     * Sets the class properties and retreives a repository.
     * 
     * @param  \Doctrine\ORM\EntityManager $em
     * @param  \Illuminate\Contracts\Auth\Authenticatable $entity
     * @param  string $concrete
     */
    public function __construct(EntityManager $entityManager, $entityName, $identifierName, $rememberTokenName,
        Hasher $hasher)
    {
        $this->entityManager = $entityManager;
        $this->repository = $entityManager->getRepository($entityName);
        $this->identifierName = $identifierName;
        $this->rememberTokenName = $rememberTokenName;
        $this->hasher = $hasher;
    }

    /**
     * Retrieve a user by their unique identifier.
     * 
     * @param  mixed  $identifier
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function retrieveById($identifier)
    {
        return $this->repository->find($identifier);
    }

    /**
     * Retrieve a user by by their unique identifier and "remember me" token.
     * 
     * @param  mixed   $identifier
     * @param  string  $token
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function retrieveByToken($identifier, $token)
    {
        return $this->repository->findOneBy([
            $this->identifierName => $identifier,
            $this->rememberTokenName => $token,
        ]);
    }

    /**
     * Update the "remember me" token for the given user in storage.
     * 
     * @param  \Illuminate\Contracts\Auth\Authenticatable  $user
     * @param  string  $token
     * @return void
     */
    public function updateRememberToken(Authenticatable $user, $token)
    {
        $user->setRememberToken($token);
        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }

    /**
     * Retrieve a user by the given credentials.
     * 
     * @param  array  $credentials
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function retrieveByCredentials(array $credentials)
    {
        if (isset($credentials['password'])) {
            unset($credentials['password']);
        }

        return $this->repository->findOneBy($credentials);
    }

    /**
     * Validate a user against the given credentials.
     * 
     * @param  \Illuminate\Contracts\Auth\Authenticatable  $user
     * @param  array  $credentials
     * @return bool
     */
    public function validateCredentials(Authenticatable $user, array $credentials)
    {
        return $this->hasher->check($credentials['password'], $user->getAuthPassword());
    }
}
