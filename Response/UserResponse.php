<?php
namespace Magice\Bundle\ClientUserBundle\Response;

use HWI\Bundle\OAuthBundle\OAuth\Response\PathUserResponse;

class UserResponse extends PathUserResponse
{
    /**
     * @var array
     */
    protected $paths = array(
        'identifier'     => 'id',
        'nickname'       => 'display_name',
        'realname'       => 'fullname',
        'email'          => 'email',
        'profilepicture' => 'avatar',
        'username'       => 'username',
    );

    public function getUserId()
    {
        return $this->getValueForPath('identifier');
    }

    /**
     * {@inheritdoc}
     */
    public function getUsername()
    {
        return $this->getValueForPath('username');
    }
}