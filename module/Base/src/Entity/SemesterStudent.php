<?php

/**
 * Auto generated by MySQL Workbench Schema Exporter.
 * Version 3.0.3 (doctrine2-zf2inputfilterannotation) on 2019-01-20 12:32:01.
 * Goto https://github.com/johmue/mysql-workbench-schema-exporter for more
 * information.
 */

namespace Base\Entity;

use Doctrine\ORM\Mapping as ORM;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

/**
 * Base\Entity\SemesterStudent
 *
 * @ORM\Entity(repositoryClass="SemesterStudentRepository")
 * @ORM\Table(name="semester_student", indexes={@ORM\Index(name="fk_semester_student_student1_idx", columns={"student_id"}), @ORM\Index(name="fk_semester_student_semester_class1_idx", columns={"semester_class_id"})})
 */
class SemesterStudent implements InputFilterAwareInterface
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
     * @ORM\Column(type="integer")
     */
    protected $student_id;

    /**
     * @ORM\Column(name="`number`", type="integer", nullable=true)
     */
    protected $number;

    /**
     * @ORM\Column(type="integer")
     */
    protected $semester_class_id;

    /**
     * @ORM\ManyToOne(targetEntity="Student", inversedBy="semesterStudents")
     * @ORM\JoinColumn(name="student_id", referencedColumnName="id", nullable=false)
     */
    protected $student;

    /**
     * @ORM\ManyToOne(targetEntity="SemesterClass", inversedBy="semesterStudents")
     * @ORM\JoinColumn(name="semester_class_id", referencedColumnName="id", nullable=false)
     */
    protected $semesterClass;

    public function __construct()
    {
    }

    /**
     * Set the value of id.
     *
     * @param integer $id
     * @return \Base\Entity\SemesterStudent
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
     * Set the value of student_id.
     *
     * @param integer $student_id
     * @return \Base\Entity\SemesterStudent
     */
    public function setStudentId($student_id)
    {
        $this->student_id = $student_id;

        return $this;
    }

    /**
     * Get the value of student_id.
     *
     * @return integer
     */
    public function getStudentId()
    {
        return $this->student_id;
    }

    /**
     * Set the value of number.
     *
     * @param integer $number
     * @return \Base\Entity\SemesterStudent
     */
    public function setNumber($number)
    {
        $this->number = $number;

        return $this;
    }

    /**
     * Get the value of number.
     *
     * @return integer
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * Set the value of semester_class_id.
     *
     * @param integer $semester_class_id
     * @return \Base\Entity\SemesterStudent
     */
    public function setSemesterClassId($semester_class_id)
    {
        $this->semester_class_id = $semester_class_id;

        return $this;
    }

    /**
     * Get the value of semester_class_id.
     *
     * @return integer
     */
    public function getSemesterClassId()
    {
        return $this->semester_class_id;
    }

    /**
     * Set Student entity (many to one).
     *
     * @param \Base\Entity\Student $student
     * @return \Base\Entity\SemesterStudent
     */
    public function setStudent(Student $student = null)
    {
        $this->student = $student;

        return $this;
    }

    /**
     * Get Student entity (many to one).
     *
     * @return \Base\Entity\Student
     */
    public function getStudent()
    {
        return $this->student;
    }

    /**
     * Set SemesterClass entity (many to one).
     *
     * @param \Base\Entity\SemesterClass $semesterClass
     * @return \Base\Entity\SemesterStudent
     */
    public function setSemesterClass(SemesterClass $semesterClass = null)
    {
        $this->semesterClass = $semesterClass;

        return $this;
    }

    /**
     * Get SemesterClass entity (many to one).
     *
     * @return \Base\Entity\SemesterClass
     */
    public function getSemesterClass()
    {
        return $this->semesterClass;
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
                'name' => 'student_id',
                'required' => true,
                'filters' => array(),
                'validators' => array(),
            ),
            array(
                'name' => 'number',
                'required' => false,
                'filters' => array(),
                'validators' => array(),
            ),
            array(
                'name' => 'semester_class_id',
                'required' => true,
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
        $dataFields = array('id', 'student_id', 'number', 'semester_class_id');
        $relationFields = array('student', 'semesterClass');
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
        return array('id', 'student_id', 'number', 'semester_class_id');
    }
}