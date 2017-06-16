<?php
/**
 * Created by PhpStorm.
 * User: sjoder
 * Date: 09.08.2016
 * Time: 16:25
 */

namespace PM\Bundle\ToolBundle\Framework\Traits\Services;


use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Class HasDoctrineTrait
 *
 * @package PM\CoreBundle\Component\Traits
 */
trait HasDoctrineServiceTrait
{
    /**
     * @var Registry
     */
    private $doctrine;

    /**
     * @return Registry
     */
    public function getDoctrine()
    {
        return $this->doctrine;
    }

    /**
     * @param Registry $doctrine
     *
     * @return $this
     */
    public function setDoctrine($doctrine)
    {
        $this->doctrine = $doctrine;

        return $this;
    }

    /**
     * Get Doctrine Manager
     *
     * @return ObjectManager|object
     */
    public function getDoctrineManager()
    {
        return $this->getDoctrine()->getManager();
    }

    /**
     * Persist and flush
     *
     * @param mixed|object|array $entities
     *
     * @return $this
     */
    public function persistAndFlush($entities)
    {
        if (0 === func_num_args()) {
            throw new \LogicException('Missing arguments');
        }

        $persists = [];
        $entities = func_get_args();

        foreach ($entities as $entity) {
            if (true === is_array($entity)) {
                $persists = array_merge($persists, $entity);
            } else {
                $persists[] = $entity;
            }
        }

        foreach ($persists as $persist) {
            $this->getDoctrineManager()->persist($persist);
        }

        $this->getDoctrineManager()->flush();

        return $this;
    }
}