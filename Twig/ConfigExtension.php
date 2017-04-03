<?php
/**
 * Created by PhpStorm.
 * User: sjoder
 * Date: 03.04.2017
 * Time: 14:16
 */

namespace PM\Bundle\ToolBundle\Twig;

use PM\Bundle\ToolBundle\Entity\Config;
use PM\Bundle\ToolBundle\Framework\Traits\Services\HasDoctrineServiceTrait;
use Twig_Extension;
use Doctrine\ORM\Mapping\ClassMetadata;

/**
 * Class ConfigExtension
 *
 * @package PM\Bundle\ToolBundle\Twig
 */
class ConfigExtension extends Twig_Extension
{
    use HasDoctrineServiceTrait;

    /**
     * Get Filters
     *
     * @return array
     */
    public function getFilters()
    {
        return [
            new \Twig_SimpleFilter('config_get_value_by_key', [
                $this,
                'getValueByKey',
            ]),
        ];
    }

    /**
     * Get Value By Key
     *
     * @param string $key
     *
     * @return string
     */
    public function getValueByKey($key)
    {
        $result = $this->getDoctrine()->getRepository($this->getEntityClass())->findOneBy(
            [
                'key' => $key,
            ]
        );

        if (null === $result) {
            return '';
        }

        return $result->getValue();
    }

    /**
     * Get Entity Class
     *
     * @return string
     */
    private function getEntityClass()
    {
        /** @var ClassMetadata $metaData */
        foreach ($this->getDoctrineManager()->getMetadataFactory()->getAllMetadata() as $metaData) {
            if ($metaData->getName() === Config::class) {
                continue;
            }

            $reflectionClass = new \ReflectionClass($metaData->getName());
            if (true === in_array(Config::class, $this->getParentClassNames($reflectionClass))) {
                return $metaData->getName();
            }
        }

        throw new \RuntimeException('Missing config entity!');
    }

    /**
     * Get Parent ClassNames
     *
     * @param \ReflectionClass $reflectionClass
     *
     * @return array
     */
    private function getParentClassNames(\ReflectionClass $reflectionClass)
    {
        $parentClassNames = [];

        while ($parent = $reflectionClass->getParentClass()) {
            $parentClassNames[] = $parent->getName();
            $reflectionClass = $parent;
        }

        return $parentClassNames;
    }
}