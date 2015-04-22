<?php

namespace Sebdd\LaravelDoctrine\Traits;

trait IsAuthenticatable 
{
    /**
     * @Column(type="string")
     * @var string
     */
    protected $password;

    /**
     * @Column(type="string", name="remember_token", nullable=true)
     * @var string
     */
    protected $rememberToken;

    /**
     * @param string
     * @return void
     */
    public function setPassword(Password $password)
    {
        $this->password = $this->hash($password);
    }

    /**
     * @return string
     */
    public function getAuthPassword()
    {
        return $this->password;
    }

    /**
     * @return string
     */
    public function getRememberToken()
    {
        return $this->rememberToken;
    }

    /**
     * @param  string  $value
     * @return void
     */
    public function setRememberToken($value)
    {
        $this->rememberToken = $value;
    }

    /**
     * @return string
     */
    public function getRememberTokenName()
    {
        return 'remember_token';
    }

    /**
     * @param string
     * @return string
     */
    protected function hash($string)
    {
        return password_hash($string, PASSWORD_BCRYPT, array('cost' => 10));
    }
}
