<?php

/**
 * Auto generated by MySQL Workbench Schema Exporter.
 * Version 2.1.6-dev (doctrine2-zf2inputfilterannotation) on 2015-07-10
 * 04:48:25.
 * Goto https://github.com/johmue/mysql-workbench-schema-exporter for more
 * information.
 */

namespace Base\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

/**
 * Base\Entity\User
 *
 * @ORM\Entity(repositoryClass="UserRepository")
 * @ORM\Table(name="`user`", uniqueConstraints={@ORM\UniqueConstraint(name="username", columns={"username"})})
 */
class User implements InputFilterAwareInterface
{
    /**
     * Instance of InputFilterInterface.
     *
     * @var InputFilter
     */
    private $inputFilter;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $username;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $email;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    protected $display_name;

    /**
     * @ORM\Column(name="`password`", type="string", length=128)
     */
    protected $password;

    /**
     * @ORM\Column(name="`state`", type="smallint", nullable=true)
     */
    protected $state;

    /**
     * @ORM\Column(name="`role`", type="string", length=10, nullable=true)
     */
    protected $role;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $register_time;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $last_logintime;

    /**
     * @ORM\Column(type="string", length=60, nullable=true)
     */
    protected $last_loginip;

    /**
     * @ORM\Column(type="string", length=60, nullable=true)
     */
    protected $check_code;

    /**
     * @ORM\Column(type="string", length=60, nullable=true)
     */
    protected $register_code;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    protected $first_name;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    protected $last_name;

    /**
     * @ORM\Column(type="string", length=45, nullable=true)
     */
    protected $raw_password;

    /**
     * @ORM\OneToMany(targetEntity="Page", mappedBy="user")
     * @ORM\JoinColumn(name="id", referencedColumnName="user_id")
     */
    protected $pages;

    public function __construct()
    {
        $this->pages = new ArrayCollection();
    }

    /**
     * Set the value of id.
     *
     * @param integer $id
     * @return \Base\Entity\User
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get the value of id.
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the value of username.
     *
     * @param string $username
     * @return \Base\Entity\User
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get the value of username.
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set the value of email.
     *
     * @param string $email
     * @return \Base\Entity\User
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get the value of email.
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set the value of display_name.
     *
     * @param string $display_name
     * @return \Base\Entity\User
     */
    public function setDisplayName($display_name)
    {
        $this->display_name = $display_name;

        return $this;
    }

    /**
     * Get the value of display_name.
     *
     * @return string
     */
    public function getDisplayName()
    {
        return $this->display_name;
    }

    /**
     * Set the value of password.
     *
     * @param string $password
     * @return \Base\Entity\User
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get the value of password.
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set the value of state.
     *
     * @param integer $state
     * @return \Base\Entity\User
     */
    public function setState($state)
    {
        $this->state = $state;

        return $this;
    }

    /**
     * Get the value of state.
     *
     * @return integer
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * Set the value of role.
     *
     * @param string $role
     * @return \Base\Entity\User
     */
    public function setRole($role)
    {
        $this->role = $role;

        return $this;
    }

    /**
     * Get the value of role.
     *
     * @return string
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * Set the value of register_time.
     *
     * @param \DateTime $register_time
     * @return \Base\Entity\User
     */
    public function setRegisterTime($register_time)
    {
        $this->register_time = $register_time;

        return $this;
    }

    /**
     * Get the value of register_time.
     *
     * @return \DateTime
     */
    public function getRegisterTime()
    {
        return $this->register_time;
    }

    /**
     * Set the value of last_logintime.
     *
     * @param \DateTime $last_logintime
     * @return \Base\Entity\User
     */
    public function setLastLogintime($last_logintime)
    {
        $this->last_logintime = $last_logintime;

        return $this;
    }

    /**
     * Get the value of last_logintime.
     *
     * @return \DateTime
     */
    public function getLastLogintime()
    {
        return $this->last_logintime;
    }

    /**
     * Set the value of last_loginip.
     *
     * @param string $last_loginip
     * @return \Base\Entity\User
     */
    public function setLastLoginip($last_loginip)
    {
        $this->last_loginip = $last_loginip;

        return $this;
    }

    /**
     * Get the value of last_loginip.
     *
     * @return string
     */
    public function getLastLoginip()
    {
        return $this->last_loginip;
    }

    /**
     * Set the value of check_code.
     *
     * @param string $check_code
     * @return \Base\Entity\User
     */
    public function setCheckCode($check_code)
    {
        $this->check_code = $check_code;

        return $this;
    }

    /**
     * Get the value of check_code.
     *
     * @return string
     */
    public function getCheckCode()
    {
        return $this->check_code;
    }

    /**
     * Set the value of register_code.
     *
     * @param string $register_code
     * @return \Base\Entity\User
     */
    public function setRegisterCode($register_code)
    {
        $this->register_code = $register_code;

        return $this;
    }

    /**
     * Get the value of register_code.
     *
     * @return string
     */
    public function getRegisterCode()
    {
        return $this->register_code;
    }

    /**
     * Set the value of first_name.
     *
     * @param string $first_name
     * @return \Base\Entity\User
     */
    public function setFirstName($first_name)
    {
        $this->first_name = $first_name;

        return $this;
    }

    /**
     * Get the value of first_name.
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->first_name;
    }

    /**
     * Set the value of last_name.
     *
     * @param string $last_name
     * @return \Base\Entity\User
     */
    public function setLastName($last_name)
    {
        $this->last_name = $last_name;

        return $this;
    }

    /**
     * Get the value of last_name.
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->last_name;
    }

    /**
     * Set the value of raw_password.
     *
     * @param string $raw_password
     * @return \Base\Entity\User
     */
    public function setRawPassword($raw_password)
    {
        $this->raw_password = $raw_password;

        return $this;
    }

    /**
     * Get the value of raw_password.
     *
     * @return string
     */
    public function getRawPassword()
    {
        return $this->raw_password;
    }

    /**
     * Add Page entity to collection (one to many).
     *
     * @param \Base\Entity\Page $page
     * @return \Base\Entity\User
     */
    public function addPage(Page $page)
    {
        $this->pages[] = $page;

        return $this;
    }

    /**
     * Remove Page entity from collection (one to many).
     *
     * @param \Base\Entity\Page $page
     * @return \Base\Entity\User
     */
    public function removePage(Page $page)
    {
        $this->pages->removeElement($page);

        return $this;
    }

    /**
     * Get Page entity collection (one to many).
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPages()
    {
        return $this->pages;
    }

    /**
     * Not used, Only defined to be compatible with InputFilterAwareInterface.
     * 
     * @param \Zend\InputFilter\InputFilterInterface $inputFilter
     * @throws \Exception
     */
    public function setInputFilter(InputFilterInterface $inputFilter)
    {
        throw new \Exception("Not used.");
    }

    /**
     * Return a for this entity configured input filter instance.
     *
     * @return InputFilterInterface
     */
    public function getInputFilter()
    {
        if ($this->inputFilter instanceof InputFilterInterface) {
            return $this->inputFilter;
        }
        $factory = new InputFactory();
        $filters = array(
            array(
                'name' => 'id',
                'required' => true,
                'filters' => array(),
                'validators' => array(),
            ),
            array(
                'name' => 'username',
                'required' => false,
                'filters' => array(),
                'validators' => array(),
            ),
            array(
                'name' => 'email',
                'required' => false,
                'filters' => array(),
                'validators' => array(),
            ),
            array(
                'name' => 'display_name',
                'required' => false,
                'filters' => array(),
                'validators' => array(),
            ),
            array(
                'name' => 'password',
                'required' => true,
                'filters' => array(),
                'validators' => array(),
            ),
            array(
                'name' => 'state',
                'required' => false,
                'filters' => array(),
                'validators' => array(),
            ),
            array(
                'name' => 'role',
                'required' => false,
                'filters' => array(),
                'validators' => array(),
            ),
            array(
                'name' => 'register_time',
                'required' => false,
                'filters' => array(),
                'validators' => array(),
            ),
            array(
                'name' => 'last_logintime',
                'required' => false,
                'filters' => array(),
                'validators' => array(),
            ),
            array(
                'name' => 'last_loginip',
                'required' => false,
                'filters' => array(),
                'validators' => array(),
            ),
            array(
                'name' => 'check_code',
                'required' => false,
                'filters' => array(),
                'validators' => array(),
            ),
            array(
                'name' => 'register_code',
                'required' => false,
                'filters' => array(),
                'validators' => array(),
            ),
            array(
                'name' => 'first_name',
                'required' => false,
                'filters' => array(),
                'validators' => array(),
            ),
            array(
                'name' => 'last_name',
                'required' => false,
                'filters' => array(),
                'validators' => array(),
            ),
            array(
                'name' => 'raw_password',
                'required' => false,
                'filters' => array(),
                'validators' => array(),
            ),
        );
        $this->inputFilter = $factory->createInputFilter($filters);

        return $this->inputFilter;
    }

    /**
     * Populate entity with the given data.
     * The set* method will be used to set the data.
     *
     * @param array $data
     * @return boolean
     */
    public function populate(array $data = array())
    {
        foreach ($data as $field => $value) {
            $setter = sprintf('set%s', ucfirst(
                str_replace(' ', '', ucwords(str_replace('_', ' ', $field)))
            ));
            if (method_exists($this, $setter)) {
                $this->{$setter}($value);
            }
        }

        return true;
    }

    /**
     * Return a array with all fields and data.
     * Default the relations will be ignored.
     * 
     * @param array $fields
     * @return array
     */
    public function getArrayCopy(array $fields = array())
    {
        $dataFields = array('id', 'username', 'email', 'display_name', 'password', 'state', 'role', 'register_time', 'last_logintime', 'last_loginip', 'check_code', 'register_code', 'first_name', 'last_name', 'raw_password');
        $relationFields = array();
        $copiedFields = array();
        foreach ($relationFields as $relationField) {
            $map = null;
            if (array_key_exists($relationField, $fields)) {
                $map = $fields[$relationField];
                $fields[] = $relationField;
                unset($fields[$relationField]);
            }
            if (!in_array($relationField, $fields)) {
                continue;
            }
            $getter = sprintf('get%s', ucfirst(str_replace(' ', '', ucwords(str_replace('_', ' ', $relationField)))));
            $relationEntity = $this->{$getter}();
            $copiedFields[$relationField] = (!is_null($map))
                ? $relationEntity->getArrayCopy($map)
                : $relationEntity->getArrayCopy();
            $fields = array_diff($fields, array($relationField));
        }
        foreach ($dataFields as $dataField) {
            if (!in_array($dataField, $fields) && !empty($fields)) {
                continue;
            }
            $getter = sprintf('get%s', ucfirst(str_replace(' ', '', ucwords(str_replace('_', ' ', $dataField)))));
            $copiedFields[$dataField] = $this->{$getter}();
        }

        return $copiedFields;
    }

    public function __sleep()
    {
        return array('id', 'username', 'email', 'display_name', 'password', 'state', 'role', 'register_time', 'last_logintime', 'last_loginip', 'check_code', 'register_code', 'first_name', 'last_name', 'raw_password');
    }
}