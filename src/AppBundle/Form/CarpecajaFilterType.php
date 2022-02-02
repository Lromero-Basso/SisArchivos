<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Lexik\Bundle\FormFilterBundle\Filter\Form\Type as Filters;

class CarpecajaFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('id', Filters\NumberFilterType::class, array(
                'label'     => 'Código carpeta - ID'
            ))
            ->add('nroCarpeta', Filters\ChoiceFilterType::class, array(
                'choices'   => $options['data'][1],//0 porque es la posicion de los n° carpeta en arrayOptions
                'label'     => 'N° Carpeta'
            ))
            ->add('codCaja', Filters\ChoiceFilterType::class, array(
                'choices'   => $options['data'][2],//2 porque es la posicion de los codigo caja en arrayOptions
                'label'     => 'Código caja'
            ))
            ->add('tituloCarp', Filters\ChoiceFilterType::class, array(
                'choices'   => $options['data'][0], //0 porque es la posicion de los titulos en arrayOptions
                'label'     => 'Título carpeta'
            ))
            ->add('fechaDesdeCarp', Filters\DateRangeFilterType::class,  array(
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
              ->add('nEstado', Filters\ChoiceFilterType::class, array(
                'choices'  => [
                    'En Archivo' => 0,
                    'Retirada' => 1
                ],
                'label'     => 'Estado'
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
