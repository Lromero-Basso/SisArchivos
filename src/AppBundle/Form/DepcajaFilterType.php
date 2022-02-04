<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Lexik\Bundle\FormFilterBundle\Filter\Form\Type as Filters;

class DepcajaFilterType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('id', Filters\NumberFilterType::class, array(
                'label' => 'Código caja - ID'
            ))
            ->add('codEstante', Filters\NumberFilterType::class, array(
                'label' => 'Código estante'
            ))
            ->add('codLado', Filters\NumberFilterType::class, array(
                'label' => 'Código lado'
            ))
            ->add('columna', Filters\NumberFilterType::class, array(
                'label' => 'Columna'
            ))
            ->add('piso', Filters\NumberFilterType::class, array(
                'label' => 'Piso'
            ))
            ->add('codArea', Filters\ChoiceFilterType::class, array(
                'choices' => $options['data'][1],
                'label' => 'Código área'
            ))
            ->add('tituloCaja', Filters\ChoiceFilterType::class, array(
                'choices' => $options['data'][0],
                'label' => 'Título caja'
            ))
            ->add('fechaDesdeCaja', Filters\DateRangeFilterType::class,  array(
                'label'     => 'Fecha Desde',
                'left_date_options' => array(
                    'widget' => 'single_text',
                    'label' => 'desde'
                ),
                'right_date_options' => array(
                    'widget' => 'single_text',
                    'label' => 'hasta'
                )
            ))
            ->add('archivadoHasta', Filters\DateRangeFilterType::class,  array(
                'label'     => 'Archivado Desde',
                'left_date_options' => array(
                    'widget' => 'single_text',
                    'label' => 'desde'
                ),
                'right_date_options' => array(
                    'widget' => 'single_text',
                    'label' => 'hasta'
                )
            ))
            ->add('observa', Filters\TextFilterType::class, array(
                'label' => 'Observa'
            ))
            ->add('estado', Filters\ChoiceFilterType::class, array(
                'label' => 'Estado',
                'choices'  => [
                    'Vigente' => 0,
                    'Destruida' => 1,
                    'No encontrada' => 2
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
