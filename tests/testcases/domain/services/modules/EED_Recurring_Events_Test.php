<?php

use EventEspresso\core\services\loaders\LoaderInterface;
use EventEspresso\RecurringEvents\domain\Domain;
use EventEspresso\RecurringEvents\domain\entities\config\RecurringEventsConfig;
use PHPUnit\Framework\TestCase;

class EED_Recurring_Events_Test extends TestCase
{


    public function setUp()
    {
        parent::setUp();
        require_once EE_REM_PLUGIN_DIR . 'domain/services/modules/EED_Recurring_Events.module.php';
    }


    public function testInstance()
    {
        $this->assertInstanceOf(EED_Recurring_Events::class, EED_Recurring_Events::instance());
    }


    public function testDomain()
    {
        $this->assertInstanceOf(Domain::class, EED_Recurring_Events::domain());
    }


    public function testLoader()
    {
        $this->assertInstanceOf(LoaderInterface::class, EED_Recurring_Events::loader());
    }


    public function testRemConfig()
    {
        $this->assertInstanceOf(RecurringEventsConfig::class, EED_Recurring_Events::remConfig());
    }
}
