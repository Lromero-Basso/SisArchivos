<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Lexik\Bundle\FormFilterBundle\Filter\Form\Type as Filters;

class HistarchFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
      
        $builder
            ->add('id', Filters\NumberFilterType::class, array(
                'label'     => 'Código interno - ID'
            ))
            ->add('codCarpeta', Filters\ChoiceFilterType::class, array(
                'choices'   => $options['data'],
                'label'     => 'Código carpeta'
            ))
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
            ))
            ->add('estado', Filters\ChoiceFilterType::class, array(
                'label' => 'Estado',
                'choices'  => [
                    'Carpeta destruida' => 1,
                ],
            ));
        $builder->setMethod("GET");
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
