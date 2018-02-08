<?php

namespace Base\Form\View\Helper;
use Zend\Form\ElementInterface;
use TwbBundle\Form\View\Helper\TwbBundleFormRow;

class RequiredMarkInFormLabel extends TwbBundleFormRow
{
    public function __invoke(ElementInterface $element = null,  $labelPosition = null, $renderErrors = null,  $partial = null)
    {

        if($element->getAttribute('required') === true){
            $aLabelAttributes = $element->getLabelAttributes()?:$this->labelAttributes;
            if(empty($aLabelAttributes['class']))$aLabelAttributes['class'] = 'required';
            elseif(strpos($aLabelAttributes['class'], 'required') === false)$aLabelAttributes['class'] .= ' required';
            $element->setLabelAttributes($aLabelAttributes);
        }
        return parent::render($element);
    }
}