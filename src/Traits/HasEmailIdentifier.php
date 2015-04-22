<?php

namespace Sebdd\LaravelDoctrine\Traits;

trait HasEmailIdentifier
{
    /**
     * @Column(type="string")
     * @var string
     */
    protected $email;

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     * @return void
     */
    public function setEmail(EmailAddress $email)
    {
        $this->email = $email;
    }

    /**
     * @return mixed
     */
    public function getAuthIdentifier()
    {
        return $this->email;
    }
}
