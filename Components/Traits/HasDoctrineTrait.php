<?php
/**
 * Created by PhpStorm.
 * User: sjoder
 * Date: 11.04.2018
 * Time: 16:20
 */

namespace PM\Bundle\ToolBundle\Components\Traits;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;

/**
 * Trait HasDoctrineTrait
 *
 * @package PM\Bundle\ToolBundle\Components\Traits
 */
trait HasDoctrineTrait
{
    /**
     * @var RegistryInterface
     */
    private $doctrine;

    /**
     * Persist and Flush
     *
     * @return $this
     */
    public function persistAndFlush()
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
            $this->getDoctrine()->getManager()->persist($persist);
        }

        $this->getDoctrine()->getManager()->flush();

        return $this;
    }

    /**
     * Remove and Flush
     *
     * @return $this
     */
    public function removeAndFlush()
    {
        if (0 === func_num_args()) {
            throw new \LogicException('Missing arguments');
        }

        foreach (func_get_args() as $remove) {
            $this->getDoctrine()->getManager()->remove($remove);
        }

        $this->getDoctrine()->getManager()->flush();

        return $this;
    }


    /**
     * @return ObjectManager
     */
    public function getDoctrineManager()
    {
        return $this->getDoctrine()->getManager();
    }

    /**
     * @return RegistryInterface
     */
    public function getDoctrine()
    {
        if ($this instanceof ContainerAwareInterface) {
            if ($this instanceof ContainerAwareCommand) {
                return $this->getContainer()->get('doctrine');
            }

            return $this->get('doctrine');
        }

        return $this->doctrine;
    }

    /**
     * @param RegistryInterface $doctrine
     *
     * @return HasDoctrineTrait
     */
    public function setDoctrine($doctrine)
    {
        $this->doctrine = $doctrine;

        return $this;
    }


}