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

namespace Nfq\Component\TimeDuration\Form\Type;

use Nfq\Component\TimeDuration\Form\Transformer\DurationTimeTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\Exception\ExceptionInterface;
use Symfony\Component\OptionsResolver\Exception\InvalidOptionsException;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class TimeDurationType.
 */
class TimeDurationType extends AbstractType
{
    const STORE_SECONDS = 'seconds';
    const STORE_MINUTES = 'minutes';

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->addViewTransformer(new DurationTimeTransformer($options['display_seconds'], $options['store_as']));
    }

    /**
     * {@inheritdoc}
     *
     * @throws ExceptionInterface
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            [
                'compound' => false,
                'store_as' => self::STORE_MINUTES,
                'display_seconds' => false,
            ]
        );

        $resolver->setAllowedValues('store_as', [self::STORE_SECONDS, self::STORE_MINUTES]);
        $resolver->setAllowedTypes('display_seconds', 'bool');
        $resolver->setNormalizer(
            'display_seconds',
            function (Options $options, $value) {
                if (true === $value && $options['store_as'] !== 'seconds') {
                    throw new InvalidOptionsException('Cannot display seconds when duration is not stored as seconds');
                }

                return $value;
            }
        );

        $resolver->setNormalizer(
            'attr',
            function (Options $options, $value) {
                if (array_key_exists('placeholder', $value)) {
                    return $value;
                }

                $value['placeholder'] = '00:00';
                if ($options['display_seconds']) {
                    $value['placeholder'] .= ':00';
                }

                return $value;
            }
        );
    }
}
