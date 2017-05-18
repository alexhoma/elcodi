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
 */

declare(strict_types=1);

namespace Elcodi\Bundle\CartCouponBundle\Tests\Functional\Applicator;

use Elcodi\Bundle\CartCouponBundle\Tests\Functional\ElcodiCartCouponFunctionalTest;

/**
 * Class MxNCartCouponApplicatorTest.
 */
class MxNCartCouponApplicatorTest extends ElcodiCartCouponFunctionalTest
{
    /**
     * Load fixtures of these bundles.
     *
     * @return array
     */
    protected static function loadFixturePaths() : array
    {
        return [
            '@ElcodiCartBundle/DataFixtures/ORM',
            '@ElcodiCouponBundle/DataFixtures/ORM',
            '@ElcodiCartCouponBundle/DataFixtures/ORM',
            '@ElcodiProductBundle/DataFixtures/ORM',
            '@ElcodiCurrencyBundle/DataFixtures/ORM',
        ];
    }

    /**
     * Test getCouponAbsoluteValue.
     *
     * @dataProvider dataGetCouponAbsoluteValue
     */
    public function testGetCouponAbsoluteValue(
        $value,
        $result
    ) {
        $cartManager = $this->get('elcodi.manager.cart');
        $cartCouponManager = $this->get('elcodi.manager.cart_coupon');
        $cart = $this->find('elcodi:cart', 1);

        $this
            ->get('elcodi.event_dispatcher.cart')
            ->dispatchCartLoadEvents($cart);

        $cartManager->addPurchasable(
            $cart,
            $this->find('elcodi:purchasable', 3),
            4
        );

        $cartManager->addPurchasable(
            $cart,
            $this->find('elcodi:purchasable', 7),
            2
        );

        $cartManager->addPurchasable(
            $cart,
            $this->find('elcodi:purchasable', 9),
            3
        );

        $coupon = $this->findOneBy('elcodi:coupon', [
            'code' => '2x1category1',
        ]);

        $coupon->setValue($value);
        $cartCouponManager->addCoupon(
            $cart,
            $coupon
        );

        $this->assertEquals(
            $result,
            $cart->getAmount()->getAmount()
        );

        $cartCouponManager->removeCoupon(
            $cart,
            $coupon
        );

        $cartManager->emptyLines(
            $cart
        );
    }

    /**
     * Data for testGetCouponAbsoluteValue.
     */
    public function dataGetCouponAbsoluteValue()
    {
        return [

            // Specific scenarios
            ['2x1', 13500],
            ['2x1:p("3,7,9")', 13500],
            ['2x1:m(1)&c(2)&p("3,7")', 18500],
            ['2x1:m(1)&c(2)&p("3,7"):P', 20000],
            ['2x1:m(1)&c(2)&p("3,7"):V', 20500],
            ['2x1:m(1)&c(2)&p("3,7"):K', 22000],
            ['2x1:p(9):P', 22000],
            ['2x1:p(9):K', 17000],
            ['2x1:c(1)', 22000],
            ['2x1:c(2)', 13500],
            ['3x2:c(2)', 16000],
            ['10x1:c(2)', 7500],
            ['4x3:c(2)', 21000],
            ['2x1::V', 20500],

            // Group scenarios (with G modifier)
            ['2x1::G', 18000],
            ['2x1:m(1)&c(2)&p("3,7"):G', 19000],
            ['2x1:m(1)&c(2)&p("3,7"):PG', 20000],
            ['2x1:m(1)&c(2)&p("3,7"):VG', 20500],
            ['2x1:m(1)&c(2)&p("3,7"):KG', 22000],
            ['2x1:p(9):PG', 22000],
            ['2x1:p(9):KG', 17000],
            ['2x1:c(1):G', 22000],
            ['2x1:c(2):G', 18000],
            ['3x2:c(2):G', 19000],
            ['9x1:c(2):G', 5000],
            ['9x2:c(2):G', 10000],
            ['9x2:c(2):VG', 22000],
            ['10x1:c(2):G', 22000],
            ['2x1::VG', 20500],
        ];
    }
}