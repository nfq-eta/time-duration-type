# Form type for time durations [![Build Status](https://travis-ci.org/nfq-eta/time-duration-type.svg?branch=master)](https://travis-ci.org/nfq-eta/time-duration-type)
Allows to input durations in format `hh:mm` or `hh:mm:ss`

## Options
### store_as
Model data format

Valid values are
  - `seconds`(Nfq\Bundle\TimeBundle\Form\Type\TimeDurationType::STORE_SECONDS)
  - `minutes`(Nfq\Bundle\TimeBundle\Form\Type\TimeDurationType::STORE_MINUTES)(default)
### display_seconds
  boolean for displaying seconds parts. Must be true for when storing as seconds.
  Defaults to `false`
  
## Minutes stored as seconds example
```php
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'time',
                TimeDurationType::class,
                [
                    'store_as' => TimeDurationType::STORE_SECONDS,
                    'display_seconds' => false,
                    'required' => false,
                ]
            );
    }
```
