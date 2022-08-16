<?php

class Zend_Application_Bootstrap_BootstrapAbstractTest_Foo
    extends Zend_Application_Resource_ResourceAbstract
{
    public $bootstrapSetInConstructor = false;

    public function __construct($options = null)
    {
        parent::__construct($options);
        if (null !== $this->getBootstrap()) {
            $this->bootstrapSetInConstructor = true;
        }
    }

    public function init()
    {
        return $this;
    }
}
