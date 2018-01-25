<?php
/**
 * Created by PhpStorm.
 * User: kost
 * Date: 12.10.17
 * Time: 21:02
 */

namespace VaderLab\SecurityBundle\Model;

use Symfony\Component\Security\Core\User\UserInterface;

class User implements UserInterface
{
    /**
     * @var integer
     */
    private $id;
    /**
     * @var string
     */
    protected $username;
    /**
     * @var string
     */
    private $email;
    /**
     * @var boolean
     */
    private $enabled;
    /**
     * @var array
     */
    private $userInformation;
    /**
     * @var array
     */
    private $roles;
    /**
     * @var string
     */
    private $phone;

    public function __construct(array $response)
    {
        $this->id = $response['id'];
        $this->phone = $response['username'];
        $this->username = $response['username'];
        $this->email = $response['email'];
        $this->enabled = $response['enabled'];
        $this->roles = $response['roles'];
        $this->groups = $response['groups'];
        $this->userInformation = $response['user_information'];
    }

    /**
     * @return int|mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getUserInformation()
    {
        return $this->userInformation;
    }
    /**
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @return array
     */
    public function getRoles()
    {
        return $this->roles;
    }

    /**
     * @return null
     */
    public function getPassword()
    {
        return null;
    }

    /**
     * @return null
     */
    public function getSalt()
    {
        return null;
    }

    /**
     * @return mixed
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @return null
     */
    public function eraseCredentials()
    {
       return null;
    }
}