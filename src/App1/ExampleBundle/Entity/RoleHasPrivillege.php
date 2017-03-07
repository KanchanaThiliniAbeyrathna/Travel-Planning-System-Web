<?php

namespace App1\ExampleBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * RoleHasPrivillege
 *
 * @ORM\Table(name="role_has_privillege", uniqueConstraints={@ORM\UniqueConstraint(name="id_UNIQUE", columns={"id"})}, indexes={@ORM\Index(name="fk_role_has_privillege_role1_idx", columns={"role_role_id"}), @ORM\Index(name="fk_role_has_privillege_privillege1_idx", columns={"privillege_id"})})
 * @ORM\Entity
 */
class RoleHasPrivillege
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var \App1\ExampleBundle\Entity\Role
     *
     * @ORM\ManyToOne(targetEntity="App1\ExampleBundle\Entity\Role")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="role_role_id", referencedColumnName="id")
     * })
     */
    private $roleRole;

    /**
     * @var \App1\ExampleBundle\Entity\Privillege
     *
     * @ORM\ManyToOne(targetEntity="App1\ExampleBundle\Entity\Privillege")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="privillege_id", referencedColumnName="id")
     * })
     */
    private $privillege;



    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set roleRole
     *
     * @param \App1\ExampleBundle\Entity\Role $roleRole
     *
     * @return RoleHasPrivillege
     */
    public function setRoleRole(\App1\ExampleBundle\Entity\Role $roleRole = null)
    {
        $this->roleRole = $roleRole;

        return $this;
    }

    /**
     * Get roleRole
     *
     * @return \App1\ExampleBundle\Entity\Role
     */
    public function getRoleRole()
    {
        return $this->roleRole;
    }

    /**
     * Set privillege
     *
     * @param \App1\ExampleBundle\Entity\Privillege $privillege
     *
     * @return RoleHasPrivillege
     */
    public function setPrivillege(\App1\ExampleBundle\Entity\Privillege $privillege = null)
    {
        $this->privillege = $privillege;

        return $this;
    }

    /**
     * Get privillege
     *
     * @return \App1\ExampleBundle\Entity\Privillege
     */
    public function getPrivillege()
    {
        return $this->privillege;
    }
}
