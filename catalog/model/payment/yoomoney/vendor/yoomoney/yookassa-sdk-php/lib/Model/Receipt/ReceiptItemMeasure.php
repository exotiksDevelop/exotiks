<?php

/**
 * The MIT License
 *
 * Copyright (c) 2022 "YooMoney", NBСO LLC
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

namespace YooKassa\Model\Receipt;

use YooKassa\Common\AbstractEnum;

/**
 * Мера количества предмета расчета передается в массиве `items`, в параметре `measure`.
 * Параметр нужно передавать, начиная с ФФД 1.2.
 */
class ReceiptItemMeasure extends AbstractEnum
{
    /** @var string Штука, единица товара */
    const PIECE = 'piece';
    /** @var string Грамм */
    const GRAM = 'gram';
    /** @var string Килограмм */
    const KILOGRAM = 'kilogram';
    /** @var string Тонна */
    const TON = 'ton';
    /** @var string Сантиметр */
    const CENTIMETER = 'centimeter';
    /** @var string Дециметр */
    const DECIMETER = 'decimeter';
    /** @var string Метр */
    const METER = 'meter';
    /** @var string Квадратный сантиметр */
    const SQUARE_CENTIMETER = 'square_centimeter';
    /** @var string Квадратный дециметр */
    const SQUARE_DECIMETER = 'square_decimeter';
    /** @var string Квадратный метр */
    const SQUARE_METER = 'square_meter';
    /** @var string Миллилитр */
    const MILLILITER = 'milliliter';
    /** @var string Литр */
    const LITER = 'liter';
    /** @var string Кубический метр */
    const CUBIC_METER = 'cubic_meter';
    /** @var string Килловат-час */
    const KILOWATT_HOUR = 'kilowatt_hour';
    /** @var string Гигакалория */
    const GIGACALORIE = 'gigacalorie';
    /** @var string Сутки */
    const DAY = 'day';
    /** @var string Час */
    const HOUR = 'hour';
    /** @var string Минута */
    const MINUTE = 'minute';
    /** @var string Секунда */
    const SECOND = 'second';
    /** @var string Килобайт */
    const KILOBYTE = 'kilobyte';
    /** @var string Мегабайт */
    const MEGABYTE = 'megabyte';
    /** @var string Гигабайт */
    const GIGABYTE = 'gigabyte';
    /** @var string Терабайт */
    const TERABYTE = 'terabyte';
    /** @var string Другое */
    const ANOTHER = 'another';

    protected static $validValues = array(
        self::PIECE             => true,
        self::GRAM              => true,
        self::KILOGRAM          => true,
        self::TON               => true,
        self::CENTIMETER        => true,
        self::DECIMETER         => true,
        self::METER             => true,
        self::SQUARE_CENTIMETER => true,
        self::SQUARE_DECIMETER  => true,
        self::SQUARE_METER      => true,
        self::MILLILITER        => true,
        self::LITER             => true,
        self::CUBIC_METER       => true,
        self::KILOWATT_HOUR     => true,
        self::GIGACALORIE       => true,
        self::DAY               => true,
        self::HOUR              => true,
        self::MINUTE            => true,
        self::SECOND            => true,
        self::KILOBYTE          => true,
        self::MEGABYTE          => true,
        self::GIGABYTE          => true,
        self::TERABYTE          => true,
        self::ANOTHER           => true,
    );
}
