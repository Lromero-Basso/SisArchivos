<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DepcajaFilterType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('id', Filters\NumberFilterType::class)
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
            ->add('codArea', Filters\NumberFilterType::class, array(
                'label' => 'Código área'
            ))
            ->add('tituloCaja', Filters\TextFilterType::class, array(
                'label' => 'Título caja'
            ))
            ->add('nroDesdeCaja', Filters\NumberFilterType::class, array(
                'label' => 'N° Desde'
            ))
            ->add('nroHastaCaja', Filters\NumberFilterType::class, array(
                'label' => 'N° Hasta'
            ))
            ->add('fechaDesdeCaja', Filters\DateRangeFilterType::class,  array(
                'label' => 'Fecha',
                'left_date_options' => array(
                    'widget' => 'single_text',
                    'label' => 'desde'
                ),
            ))
            ->add('fechaHastaCaja', Filters\DateRangeFilterType::class,  array(
                'label' => 'Fecha',
                'right_date_options' => array(
                    'widget' => 'single_text',
                    'label' => 'hasta'
                )
            ))
            ->add('archivadoHasta', Filters\DateRangeFilterType::class,  array(
                'label' => 'Fecha',
                'left_date_options' => array(
                    'widget' => 'single_text',
                    'label' => 'archivado desde'
                ),
                'right_date_options' => array(
                    'widget' => 'single_text',
                    'label' => 'archivado hasta'
                )
            ))
            ->add('observa', Filters\TextFilterType::class, array(
                'label' => 'Observa'
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
