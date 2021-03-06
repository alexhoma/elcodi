<?php

/*
 * This file is part of the Elcodi package.
 *
 * Copyright (c) 2014-2016 Elcodi Networks S.L.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * Feel free to edit as you please, and have fun.
 *
 * @author Marc Morera <yuhu@mmoreram.com>
 * @author Aldo Chiecchia <zimage@tiscali.it>
 * @author Elcodi Team <tech@elcodi.com>
 */

namespace Elcodi\Component\Core\Factory\Traits;

use Elcodi\Component\Core\Factory\Abstracts\AbstractFactory;

/**
 * Trait FactoryTrait.
 */
trait FactoryTrait
{
    /**
     * @var AbstractFactory
     *
     * Factory
     */
    private $factory;

    /**
     * Get Factory.
     *
     * @return AbstractFactory
     */
    protected function getFactory()
    {
        return $this->factory;
    }

    /**
     * Get entity namespace
     *
     * @return string
     */
    protected function getEntityNamespace()
    {
        return $this
            ->factory
            ->getEntityNamespace();
    }

    /**
     * Create from factory
     *
     * @return Object
     */
    protected function createFromFactory()
    {
        return $this
            ->factory
            ->create();
    }

    /**
     * Sets Factory.
     *
     * @param AbstractFactory $factory
     */
    public function setFactory(AbstractFactory $factory)
    {
        $this->factory = $factory;
    }
}
