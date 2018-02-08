<?php

/*
 * @copyright C UAB NFQ Technologies
 *
 * This Software is the property of NFQ Technologies
 * and is protected by copyright law – it is NOT Freeware.
 *
 * Any unauthorized use of this software without a valid license key
 * is a violation of the license agreement and will be prosecuted by
 * civil and criminal law.
 *
 * Contact UAB NFQ Technologies:
 * E-mail: info@nfq.lt
 * http://www.nfq.lt
 */

namespace Nfq\Component\TimeDuration\Form\Transformer;

use Nfq\Component\TimeDuration\Form\Type\TimeDurationType;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

/**
 * Class DurationTimeTransformer.
 */
class DurationTimeTransformer implements DataTransformerInterface
{
    /**
     * @var bool
     */
    private $displaySeconds;

    /**
     * @var string
     */
    private $storeAs;

    /**
     * DurationTimeTransformer constructor.
     *
     * @param bool $displaySeconds
     * @param string $storeAs
     */
    public function __construct(bool $displaySeconds, string $storeAs)
    {
        $this->displaySeconds = $displaySeconds;
        $this->storeAs = $storeAs;
    }

    /**
     * {@inheritdoc}
     */
    public function transform($norm)
    {
        if (null === $norm) {
            return null;
        }

        if (false === filter_var($norm, FILTER_VALIDATE_INT, ['min_range' => 0])) {
            throw new TransformationFailedException('Invalid data');
        }

        switch ($this->storeAs) {
            case TimeDurationType::STORE_MINUTES:
                $minutes = $norm;
                $seconds = 0;
                break;
            case TimeDurationType::STORE_SECONDS:
                $minutes = intdiv($norm, 60);
                $seconds = $norm % 60;
                break;
            default:
                throw new TransformationFailedException('Invalid data store option');
        }

        $hours = str_pad(intdiv($minutes, 60), 2, 0, STR_PAD_LEFT);
        $minutes = str_pad($minutes % 60, 2, 0, STR_PAD_LEFT);

        $string = "{$hours}:{$minutes}";

        if ($this->displaySeconds || $seconds > 0) {
            $seconds = str_pad($seconds, 2, 0, STR_PAD_LEFT);
            $string .= ":{$seconds}";
        }

        return $string;
    }

    /**
     * {@inheritdoc}
     */
    public function reverseTransform($view)
    {
        if (null === $view || '' === $view) {
            return null;
        }

        if (false === \is_string($view)) {
            throw new TransformationFailedException('Invalid data');
        }

        $pieces = explode(':', $view);
        $count = \count($pieces);
        if (2 !== $count && 3 !== $count) {
            throw new TransformationFailedException('Invalid data');
        }

        $config = [
            ['min_range' => 0],
            ['min_range' => 0, 'max_range' => 59],
            ['min_range' => 0, 'max_range' => 59],
        ];

        foreach ($pieces as $i => &$piece) {
            if ('' === $piece) {
                throw new TransformationFailedException('Invalid data');
            }

            $piece = ltrim($piece, '0');
            if ('' === $piece) {
                $piece = 0;
            }

            if (false === filter_var($piece, FILTER_VALIDATE_INT, ['options' => $config[$i]])) {
                throw new TransformationFailedException('Invalid data');
            }

            $piece = (int)$piece;
        }
        unset($piece);

        list($hours, $minutes) = $pieces;

        $minutes += $hours * 60;
        if ($this->storeAs === TimeDurationType::STORE_MINUTES) {
            return $minutes;
        }

        $seconds = 0;
        if ($this->displaySeconds || false === empty($pieces[2])) {
            $seconds = $pieces[2] ?? 0;
        }

        $seconds += $minutes * 60;

        return $seconds;
    }
}
