<?php

/*
 * This file is part of the Elcodi package.
 *
 * Copyright (c) 2014-2015 Elcodi.com
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

namespace Elcodi\Bundle\CommentBundle\Tests\Functional\Services;

use Elcodi\Bundle\TestCommonBundle\Functional\WebTestCase;

/**
 * Class CommentManagerTest
 */
class CommentManagerTest extends WebTestCase
{
    /**
     * Returns the callable name of the service
     *
     * @return string[] service name
     */
    public function getServiceCallableName()
    {
        return [
            'elcodi.manager.comment',
        ];
    }

    /**
     * Test add comment
     */
    public function testAddComment()
    {
        $commentManager = $this->get('elcodi.manager.comment');
        $source = 'http://page.com/product1';
        $commentManager->addComment(
            $source,
            'admin',
            'This is my comment #1',
            '1234',
            'Efervescencio',
            'uhsi@noseque.com',
            null
        );

        $storedComment = $this->find('comment', 1);
        $this->assertEquals('http://page.com/product1', $storedComment->getSource());
        $this->assertEquals('This is my comment #1', $storedComment->getContent());
        $this->assertEquals('This is my comment #1', $storedComment->getParsedContent());
        $this->assertEquals('none', $storedComment->getParsingType());
        $this->assertSame('Efervescencio', $storedComment->getAuthorName());
        $this->assertSame('uhsi@noseque.com', $storedComment->getAuthorEmail());
        $this->assertSame('1234', $storedComment->getAuthorToken());
        $this->assertNull($storedComment->getParent());
    }
}
