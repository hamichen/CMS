<?php
namespace Base\Entity;

use Doctrine\ORM\EntityRepository;

class MenuRepository extends EntityRepository
{

    /**
     * 檢查是否為子層的 id
     *
     * @param integer $id
     * @param integer $parentId
     * @return boolean
     */
    public function checkIsChild($id, $parentId)
    {
        $res = $this->find($id);

        $collection = new \Doctrine\Common\Collections\ArrayCollection($res);

        $articleKind_iterator = new RecursiveMenuIterator($collection);

        $flag = false;
        /** @var  $option \Base\Entity\Menu */
        foreach ($articleKind_iterator as $option) {
            if ($option->getMenu() and $option->getId() == $parentId) {
                $flag = true;
                break;
            }
        }

        return $flag;
    }


    /**
     * 取出子層 ID 陣列
     *
     * @param unknown $id
     * @return Ambigous <multitype:, multitype:NULL >
     */
    public function getChildIdArray($id)
    {
        $res = $this->_em->createQueryBuilder()
            ->select('u')
            ->from('base\Entity\Menu', 'u')
            ->leftJoin('u.menu','m')
            ->orderBy('u.order_id')
            ->where('m.id=:id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getResult();

        $collection = new \Doctrine\Common\Collections\ArrayCollection($res);

        $articleKind_iterator = new RecursiveMenuIterator($collection);
        $arr = array();
        /** @var  $val \Base\Entity\Menu */
        foreach ($articleKind_iterator as $val) {
            $arr[] = $val->getId();
            if ($articleKind_iterator->hasChildren()) {
                $arr = array_merge($arr, $this->getChildIdArray($val->getId()));
            }
        }
        return $arr;
    }

    /**
     * 取出選單 options
     *
     * @return multitype:string
     */
    public function getMenuArray($id=null)
    {
        if ($id)
            $id = $this->find($id);

        $rootResource = $this->findBy(array(
            'menu' => $id
        ));

        $collection = new \Doctrine\Common\Collections\ArrayCollection($rootResource);
        $recursive_iterator = new RecursiveMenuIterator($collection);
        $options = array();
        /** @var  $childMenu \Base\Entity\Menu */
        foreach ($recursive_iterator as $childMenu) {
            $cid = $childMenu->getId();
            $options[$cid]['value'] = $childMenu->getId();
            $options[$cid]['label'] = $childMenu->getName();
            $options[$cid]['url'] = $childMenu->getUrl();
            $options[$cid]['target'] = $childMenu->getTarget();
            $options[$cid]['params'] = unserialize($childMenu->getParams());

            if ($recursive_iterator->hasChildren())
            $options[$cid]['pages'] = $this->getMenuArray($cid);
        }

        return $options;
    }


    /**
     * 取出選單 options
     *
     * @return multitype:string
     */
    public function getMenuOptions($id = null)
    {
        if ($id)
            $id = $this->find($id);

        $rootResource = $this->findBy(array(
            'menu' => $id
        ));

        $collection = new \Doctrine\Common\Collections\ArrayCollection($rootResource);
        $category_iterator = new RecursiveMenuIterator($collection);
        $recursive_iterator = new \RecursiveIteratorIterator($category_iterator, \RecursiveIteratorIterator::SELF_FIRST);
        // 上層選單 options
        $options = array();
        /** @var  $childMenu \Base\Entity\Menu */
        foreach ($recursive_iterator as $childMenu) {
            $temp['value'] = $childMenu->getId();
            $temp['label'] =  str_repeat('--', $recursive_iterator->getDepth()) . ' ' . $childMenu->getName();
            $options[$childMenu->getId()] = $temp;
        }

        return $options;
    }

    /**
     * 取出導覽列
     *
     * @param unknown $id
     * @return Ambigous <number, multitype:NULL >
     */
    public function breadcrumbs($id)
    {
        $arr = array();
        if ($menu = $this->find($id)) {
            $arr[$menu->getId()] = $menu->getName();
            if ($menu->getMenu())
                $arr = $this->breadcrumbs($menu->getMenu()->getId()) + $arr;
        }
        return $arr;
    }


    /**
     * 取出導覽列以Id
     *
     * @param unknown $id
     * @return Ambigous <number, multitype:NULL >
     */
    public function systemBreadcrumbsById($id)
    {
        $arr = array();
        if ($menu = $this->find($id)) {
            $arr[$menu->getId()]['name'] = $menu->getName();
            if ($menu->getMenu())
                $arr =  $this->systemBreadcrumbsById($menu->getMenu()
                        ->getId()) + $arr;
        }
        return $arr;
    }




}