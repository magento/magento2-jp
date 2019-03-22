<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace CommunityEngineering\CurrencyPrecision\Model\Config\Source;

/**
 * Rounding Mode Types.
 */
class RoundingMode implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * Ceiling rounding mode.
     *
     * Round towards positive infinity.
     */
    const CEILING = 'ceiling';

    /**
     * Down rounding mode.
     *
     * Round towards zero.
     */
    const DOWN = 'down';

    /**
     * Floor rounding mode.
     *
     * Round towards negative infinity.
     */
    const FLOOR = 'floor';

    /**
     * Half Down rounding mode.
     *
     * Round towards "nearest neighbor" unless both neighbors are equidistant,
     * in which case round down.
     */
    const HALFDOWN = 'halfdown';

    /**
     * Half Even rounding mode.
     * Round towards "nearest neighbor" unless both neighbors are equidistant,
     * in which case ound towards the even neighbor.
     */
    const HALFEVEN = 'halfeven';

    /**
     * Half Up rounding mode.
     *
     * Round towards "nearest neighbor" unless both neighbors are equidistant,
     * in which case round up.
     */
    const HALFUP = 'halfup';

    /**
     * Up rounding mode.
     *
     * Round away from zero.
     */
    const UP = 'up';

    /**
     * List available currency rounding modes.
     *
     * @return array
     */
    public function toOptionArray()
    {
        return [
            self::UP =>
                __('Up') .
                __(' (round away from zero)'),
            self::CEILING =>
                __('Ceiling') .
                __(' (round towards positive infinity)'),
            self::DOWN =>
                __('Down') .
                __(' (round towards zero)'),
            self::FLOOR =>
                __('Floor') .
                __(' (round towards negative infinity)'),
            self::HALFDOWN =>
                __('Half Down') .
                __(' (round towards "nearest neighbor" unless both neighbors are equidistant, in which case ') .
                __('round down)'),
            self::HALFEVEN =>
                __('Half Even') .
                __(' (round towards "nearest neighbor" unless both neighbors are equidistant, in which case ') .
                __('round towards the even neighbor)'),
            self::HALFUP =>
                __('Half Up') .
                __(' (round towards "nearest neighbor" unless both neighbors are equidistant, in which case ') .
                __('round up)'),
        ];
    }
}
