<?php

namespace Magice\Bundle\ClientUserBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;

/**
 * User
 */
class User extends BaseUser
{
    /**
     * @var integer
     */
    protected $id;

    /**
     * @var integer
     */
    protected $joypriceId;

    /**
     * @var string
     */
    protected $joypriceAccessToken;

    /**
     * @param integer $joypriceId
     * @return $this
     */
    public function setJoypriceId($joypriceId)
    {
        $this->joypriceId = $joypriceId;

        return $this;
    }

    /**
     * @return integer
     */
    public function getJoypriceId()
    {
        return $this->joypriceId;
    }

    /**
     * @param $joypriceAccessToken
     * @return $this
     */
    public function setJoypriceAccessToken($joypriceAccessToken)
    {
        $this->joypriceAccessToken = $joypriceAccessToken;

        return $this;
    }

    /**
     * @return string
     */
    public function getJoypriceAccessToken()
    {
        return $this->joypriceAccessToken;
    }
}
