<?php

/*
 * This file is part of the NFQ package.
 *
 * (c) NFQ Technologies UAB <info@nfq.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Tests\Nfq\Component\TimeDuration\Form\Transformer;

use Nfq\Component\TimeDuration\Form\Transformer\DurationTimeTransformer;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\Exception\TransformationFailedException;

/**
 * Class DurationTimeTransformerTest.
 */
class DurationTimeTransformerTest extends TestCase
{
    /**
     * @return array
     */
    public function getTestTransformData(): array
    {
        return [
            'null_1' => [
                'seconds' => true,
                'store' => 'seconds',
                'input' => null,
                'exception' => false,
                'expected' => null,
            ],
            'null_2' => [
                'seconds' => false,
                'store' => 'seconds',
                'input' => null,
                'exception' => false,
                'expected' => null,
            ],
            'null_3' => [
                'seconds' => false,
                'store' => 'minutes',
                'input' => null,
                'exception' => false,
                'expected' => null,
            ],
            'invalid_input_1' => [
                'seconds' => true,
                'store' => 'seconds',
                'input' => '1a',
                'exception' => true,
                'expected' => null,
            ],
            'invalid_input_2' => [
                'seconds' => false,
                'store' => 'seconds',
                'input' => 'asd',
                'exception' => true,
                'expected' => null,
            ],
            'invalid_input_3' => [
                'seconds' => false,
                'store' => 'minutes',
                'input' => '0x123',
                'exception' => true,
                'expected' => null,
            ],
            'valid_1' => [
                'seconds' => true,
                'store' => 'seconds',
                'input' => '3661',
                'exception' => false,
                'expected' => '01:01:01',
            ],
            'valid_2' => [
                'seconds' => false,
                'store' => 'seconds',
                'input' => 3660,
                'exception' => false,
                'expected' => '01:01',
            ],
            'valid_3' => [
                'seconds' => false,
                'store' => 'minutes',
                'input' => 3661,
                'exception' => false,
                'expected' => '61:01',
            ],
            'valid_4' => [
                'seconds' => false,
                'store' => 'seconds',
                'input' => 3661,
                'exception' => false,
                'expected' => '01:01:01',
            ],
            'valid_5' => [
                'seconds' => false,
                'store' => 'minutes',
                'input' => 1501,
                'exception' => false,
                'expected' => '25:01',
            ],
        ];
    }

    /**
     * @param bool $display
     * @param string $store
     * @param mixed $input
     * @param bool $exception
     * @param null|string $expected
     *
     * @dataProvider getTestTransformData
     */
    public function testTransform(bool $display, string $store, $input, bool $exception, string $expected = null): void
    {
        $transformer = new DurationTimeTransformer($display, $store);

        if ($exception) {
            $this->expectException(TransformationFailedException::class);
        }

        self::assertSame($expected, $transformer->transform($input));
    }

    /**
     * @return array
     */
    public function getTestReverseTransformData(): array
    {
        return [
            'null_1' => [
                'seconds' => true,
                'store' => 'seconds',
                'input' => null,
                'exception' => false,
                'expected' => null,
            ],
            'null_2' => [
                'seconds' => false,
                'store' => 'seconds',
                'input' => null,
                'exception' => false,
                'expected' => null,
            ],
            'null_3' => [
                'seconds' => false,
                'store' => 'minutes',
                'input' => null,
                'exception' => false,
                'expected' => null,
            ],
            'invalid_1' => [
                'seconds' => false,
                'store' => 'seconds',
                'input' => '01:01:61',
                'exception' => true,
                'expected' => null,
            ],
            'invalid_2' => [
                'seconds' => false,
                'store' => 'seconds',
                'input' => '01',
                'exception' => true,
                'expected' => null,
            ],
            'invalid_3' => [
                'seconds' => false,
                'store' => 'seconds',
                'input' => '01:',
                'exception' => true,
                'expected' => null,
            ],
            'invalid_4' => [
                'seconds' => false,
                'store' => 'seconds',
                'input' => ':01',
                'exception' => true,
                'expected' => null,
            ],
            'invalid_5' => [
                'seconds' => false,
                'store' => 'seconds',
                'input' => '::',
                'exception' => true,
                'expected' => null,
            ],
            'invalid_6' => [
                'seconds' => true,
                'store' => 'seconds',
                'input' => '01:61',
                'exception' => true,
                'expected' => null,
            ],
            'invalid_7' => [
                'seconds' => true,
                'store' => 'seconds',
                'input' => '-1:0:0',
                'exception' => true,
                'expected' => null,
            ],
            'invalid_8' => [
                'seconds' => false,
                'store' => 'seconds',
                'input' => '0x1:0',
                'exception' => true,
                'expected' => null,
            ],
            'valid_1' => [
                'seconds' => false,
                'store' => 'seconds',
                'input' => '1:0',
                'exception' => false,
                'expected' => 3600,
            ],
            'valid_2' => [
                'seconds' => false,
                'store' => 'seconds',
                'input' => '1:00',
                'exception' => false,
                'expected' => 3600,
            ],
            'valid_3' => [
                'seconds' => false,
                'store' => 'seconds',
                'input' => '01:0',
                'exception' => false,
                'expected' => 3600,
            ],
            'valid_4' => [
                'seconds' => false,
                'store' => 'seconds',
                'input' => '01:00',
                'exception' => false,
                'expected' => 3600,
            ],
            'valid_5' => [
                'seconds' => true,
                'store' => 'seconds',
                'input' => '01:00:00',
                'exception' => false,
                'expected' => 3600,
            ],
            'valid_6' => [
                'seconds' => false,
                'store' => 'minutes',
                'input' => '01:00',
                'exception' => false,
                'expected' => 60,
            ],
            'valid_7' => [
                'seconds' => false,
                'store' => 'minutes',
                'input' => '30:01',
                'exception' => false,
                'expected' => 1801,
            ],
            'valid_8' => [
                'seconds' => false,
                'store' => 'minutes',
                'input' => '1:01:01',
                'exception' => false,
                'expected' => 61,
            ],
            'valid_9' => [
                'seconds' => true,
                'store' => 'seconds',
                'input' => '1:01:01',
                'exception' => false,
                'expected' => 3661,
            ],
            'valid_10' => [
                'seconds' => true,
                'store' => 'seconds',
                'input' => '1:01',
                'exception' => false,
                'expected' => 3660,
            ],
            'valid_11' => [
                'seconds' => false,
                'store' => 'seconds',
                'input' => '01:01:01',
                'exception' => false,
                'expected' => 3661,
            ],
            'valid_12' => [
                'seconds' => false,
                'store' => 'minutes',
                'input' => '25:01',
                'exception' => false,
                'expected' => 1501,
            ],
        ];
    }

    /**
     * @param bool $display
     * @param string $store
     * @param mixed $input
     * @param bool $exception
     * @param null|int $expected
     *
     * @dataProvider getTestReverseTransformData
     */
    public function testReverseTransform(
        bool $display,
        string $store,
        $input,
        bool $exception,
        int $expected = null
    ): void {
        $transformer = new DurationTimeTransformer($display, $store);

        if ($exception) {
            $this->expectException(TransformationFailedException::class);
        }

        self::assertSame($expected, $transformer->reverseTransform($input));
    }
}
