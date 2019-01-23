<?php

/**
 * Auto generated by MySQL Workbench Schema Exporter.
 * Version 3.0.3 (doctrine2-zf2inputfilterannotation) on 2019-01-20 12:32:01.
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
 * Base\Entity\Student
 *
 * 學生
 *
 * @ORM\Entity(repositoryClass="StudentRepository")
 * @ORM\Table(name="student", indexes={@ORM\Index(name="fk_student_user1_idx", columns={"user_id"})})
 */
class Student implements InputFilterAwareInterface
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
     * 姓名
     *
     * @ORM\Column(name="`name`", type="string", length=45)
     */
    protected $name;

    /**
     * 身分證hashkey
     *
     * @ORM\Column(type="string", length=65)
     */
    protected $edu_key;

    /**
     * 學號
     *
     * @ORM\Column(type="string", length=45, nullable=true)
     */
    protected $stud_no;

    /**
     * 性別
     *
     * @ORM\Column(type="string", length=6, nullable=true)
     */
    protected $sex;

    /**
     * 更新時間
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $update_time;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $user_id;

    /**
     * @ORM\OneToMany(targetEntity="SemesterStudent", mappedBy="student")
     * @ORM\JoinColumn(name="id", referencedColumnName="student_id", nullable=false)
     */
    protected $semesterStudents;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="students")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    protected $user;

    public function __construct()
    {
        $this->semesterStudents = new ArrayCollection();
    }

    /**
     * Set the value of id.
     *
     * @param integer $id
     * @return \Base\Entity\Student
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
     * Set the value of name.
     *
     * @param string $name
     * @return \Base\Entity\Student
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get the value of name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set the value of edu_key.
     *
     * @param string $edu_key
     * @return \Base\Entity\Student
     */
    public function setEduKey($edu_key)
    {
        $this->edu_key = $edu_key;

        return $this;
    }

    /**
     * Get the value of edu_key.
     *
     * @return string
     */
    public function getEduKey()
    {
        return $this->edu_key;
    }

    /**
     * Set the value of stud_no.
     *
     * @param string $stud_no
     * @return \Base\Entity\Student
     */
    public function setStudNo($stud_no)
    {
        $this->stud_no = $stud_no;

        return $this;
    }

    /**
     * Get the value of stud_no.
     *
     * @return string
     */
    public function getStudNo()
    {
        return $this->stud_no;
    }

    /**
     * Set the value of sex.
     *
     * @param string $sex
     * @return \Base\Entity\Student
     */
    public function setSex($sex)
    {
        $this->sex = $sex;

        return $this;
    }

    /**
     * Get the value of sex.
     *
     * @return string
     */
    public function getSex()
    {
        return $this->sex;
    }

    /**
     * Set the value of update_time.
     *
     * @param \DateTime $update_time
     * @return \Base\Entity\Student
     */
    public function setUpdateTime($update_time)
    {
        $this->update_time = $update_time;

        return $this;
    }

    /**
     * Get the value of update_time.
     *
     * @return \DateTime
     */
    public function getUpdateTime()
    {
        return $this->update_time;
    }

    /**
     * Set the value of user_id.
     *
     * @param integer $user_id
     * @return \Base\Entity\Student
     */
    public function setUserId($user_id)
    {
        $this->user_id = $user_id;

        return $this;
    }

    /**
     * Get the value of user_id.
     *
     * @return integer
     */
    public function getUserId()
    {
        return $this->user_id;
    }

    /**
     * Add SemesterStudent entity to collection (one to many).
     *
     * @param \Base\Entity\SemesterStudent $semesterStudent
     * @return \Base\Entity\Student
     */
    public function addSemesterStudent(SemesterStudent $semesterStudent)
    {
        $this->semesterStudents[] = $semesterStudent;

        return $this;
    }

    /**
     * Remove SemesterStudent entity from collection (one to many).
     *
     * @param \Base\Entity\SemesterStudent $semesterStudent
     * @return \Base\Entity\Student
     */
    public function removeSemesterStudent(SemesterStudent $semesterStudent)
    {
        $this->semesterStudents->removeElement($semesterStudent);

        return $this;
    }

    /**
     * Get SemesterStudent entity collection (one to many).
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSemesterStudents()
    {
        return $this->semesterStudents;
    }

    /**
     * Set User entity (many to one).
     *
     * @param \Base\Entity\User $user
     * @return \Base\Entity\Student
     */
    public function setUser(User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get User entity (many to one).
     *
     * @return \Base\Entity\User
     */
    public function getUser()
    {
        return $this->user;
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
                'name' => 'name',
                'required' => true,
                'filters' => array(),
                'validators' => array(),
            ),
            array(
                'name' => 'edu_key',
                'required' => true,
                'filters' => array(),
                'validators' => array(),
            ),
            array(
                'name' => 'stud_no',
                'required' => false,
                'filters' => array(),
                'validators' => array(),
            ),
            array(
                'name' => 'sex',
                'required' => false,
                'filters' => array(),
                'validators' => array(),
            ),
            array(
                'name' => 'update_time',
                'required' => false,
                'filters' => array(),
                'validators' => array(),
            ),
            array(
                'name' => 'user_id',
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
        $dataFields = array('id', 'name', 'edu_key', 'stud_no', 'sex', 'update_time', 'user_id');
        $relationFields = array('user');
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
        return array('id', 'name', 'edu_key', 'stud_no', 'sex', 'update_time', 'user_id');
    }
}