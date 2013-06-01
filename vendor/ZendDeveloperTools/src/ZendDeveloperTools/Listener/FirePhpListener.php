<?php
/**
 * Zend Developer Tools for Zend Framework (http://framework.zend.com/)
 *
 * @link       http://github.com/zendframework/ZendDeveloperTools for the canonical source repository
 * @copyright  Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd New BSD License
 * @package    ZendDeveloperTools
 * @subpackage Listener
 */

namespace ZendDeveloperTools\Listener;

use ZendDeveloperTools\Profiler;
use ZendDeveloperTools\ProfilerEvent;
use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\ListenerAggregateInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * FirePHP Listener
 *
 * @category   Zend
 * @package    ZendDeveloperTools
 * @subpackage Listener
 */
class FirePhpListener implements ListenerAggregateInterface
{
    /**
     * @var ServiceLocatorInterface
     */
    protected $serviceLocator;

    /**
     * @var array
     */
    protected $listeners = array();

    /**
     * Constructor.
     *
     * @param ServiceLocatorInterface $serviceLocator
     */
    public function __construct(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
    }

    /**
     * @inheritdoc
     */
    public function attach(EventManagerInterface $events)
    {
        $this->listeners[] = $events->attach(
            ProfilerEvent::EVENT_COLLECTED,
            array($this, 'onCollected'),
            Profiler::PRIORITY_FIREPHP
        );
    }

    /**
     * @inheritdoc
     */
    public function detach(EventManagerInterface $events)
    {
        foreach ($this->listeners as $index => $listener) {
            if ($events->detach($listener)) {
                unset($this->listeners[$index]);
            }
        }
    }

    /**
     * ProfilerEvent::EVENT_COLLECTED event callback.
     *
     * @param ProfilerEvent $event
     */
    public function onCollected(ProfilerEvent $event)
    {

    }
}