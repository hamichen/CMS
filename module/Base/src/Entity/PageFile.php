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
 * Base\Entity\PageFile
 *
 * @ORM\Entity
 * @ORM\Table(name="page_file", indexes={@ORM\Index(name="fk_page_file_page1_idx", columns={"page_id"})})
 */
class PageFile implements InputFilterAwareInterface
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
    protected $page_id;

    /**
     * @ORM\Column(type="string", length=200, nullable=true)
     */
    protected $file_name;

    /**
     * @ORM\Column(type="string", length=45, nullable=true)
     */
    protected $file_ext;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    protected $file_size;

    /**
     * @ORM\Column(type="string", length=200, nullable=true)
     */
    protected $source_name;

    /**
     * @ORM\ManyToOne(targetEntity="Page", inversedBy="pageFiles")
     * @ORM\JoinColumn(name="page_id", referencedColumnName="id", nullable=false)
     */
    protected $page;

    public function __construct()
    {
    }

    /**
     * Set the value of id.
     *
     * @param integer $id
     * @return \Base\Entity\PageFile
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
     * Set the value of page_id.
     *
     * @param integer $page_id
     * @return \Base\Entity\PageFile
     */
    public function setPageId($page_id)
    {
        $this->page_id = $page_id;

        return $this;
    }

    /**
     * Get the value of page_id.
     *
     * @return integer
     */
    public function getPageId()
    {
        return $this->page_id;
    }

    /**
     * Set the value of file_name.
     *
     * @param string $file_name
     * @return \Base\Entity\PageFile
     */
    public function setFileName($file_name)
    {
        $this->file_name = $file_name;

        return $this;
    }

    /**
     * Get the value of file_name.
     *
     * @return string
     */
    public function getFileName()
    {
        return $this->file_name;
    }

    /**
     * Set the value of file_ext.
     *
     * @param string $file_ext
     * @return \Base\Entity\PageFile
     */
    public function setFileExt($file_ext)
    {
        $this->file_ext = $file_ext;

        return $this;
    }

    /**
     * Get the value of file_ext.
     *
     * @return string
     */
    public function getFileExt()
    {
        return $this->file_ext;
    }

    /**
     * Set the value of file_size.
     *
     * @param float $file_size
     * @return \Base\Entity\PageFile
     */
    public function setFileSize($file_size)
    {
        $this->file_size = $file_size;

        return $this;
    }

    /**
     * Get the value of file_size.
     *
     * @return float
     */
    public function getFileSize()
    {
        return $this->file_size;
    }

    /**
     * Set the value of source_name.
     *
     * @param string $source_name
     * @return \Base\Entity\PageFile
     */
    public function setSourceName($source_name)
    {
        $this->source_name = $source_name;

        return $this;
    }

    /**
     * Get the value of source_name.
     *
     * @return string
     */
    public function getSourceName()
    {
        return $this->source_name;
    }

    /**
     * Set Page entity (many to one).
     *
     * @param \Base\Entity\Page $page
     * @return \Base\Entity\PageFile
     */
    public function setPage(Page $page = null)
    {
        $this->page = $page;

        return $this;
    }

    /**
     * Get Page entity (many to one).
     *
     * @return \Base\Entity\Page
     */
    public function getPage()
    {
        return $this->page;
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
                'name' => 'page_id',
                'required' => true,
                'filters' => array(),
                'validators' => array(),
            ),
            array(
                'name' => 'file_name',
                'required' => false,
                'filters' => array(),
                'validators' => array(),
            ),
            array(
                'name' => 'file_ext',
                'required' => false,
                'filters' => array(),
                'validators' => array(),
            ),
            array(
                'name' => 'file_size',
                'required' => false,
                'filters' => array(),
                'validators' => array(),
            ),
            array(
                'name' => 'source_name',
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
        $dataFields = array('id', 'page_id', 'file_name', 'file_ext', 'file_size', 'source_name');
        $relationFields = array('page');
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
        return array('id', 'page_id', 'file_name', 'file_ext', 'file_size', 'source_name');
    }
}
