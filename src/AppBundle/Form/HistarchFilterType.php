<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class HistarchFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('id', Filters\NumberFilterType::class)
            ->add('codCarpeta', Filters\NumberFilterType::class)
            ->add('fechaRetiro', Filters\DateRangeFilterType::class,  array(
                'label' => 'Fecha',
                'left_date_options' => array(
                    'widget' => 'single_text',
                    'label' => 'desde'
                ),
            ))
            ->add('fechaDevolucion', Filters\DateRangeFilterType::class,  array(
                'label' => 'Fecha',
                'right_date_options' => array(
                    'widget' => 'single_text',
                    'label' => 'hasta'
                )
            ))
            ->add('legajo', Filters\NumberFilterType::class, array(
                'label' => 'Legajo'
            ));
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'allow_extra_fields' => true,
            'csrf_protection' => false,
            'validation_groups' => array('filtering') // avoid NotBlank() constraint-related message
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return null;
    }


}
