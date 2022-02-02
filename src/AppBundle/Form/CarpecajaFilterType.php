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
                'label'     => 'Código carpeta'
            ))
            ->add('nroCarpeta', Filters\ChoiceFilterType::class, array(
                'choices'   => $options['data'][1],//0 porque es la posicion de los n° carpeta en arrayOptions
                'label'     => 'N° Carpeta'
            ))
            ->add('codCaja', Filters\TextFilterType::class, array(
                'label'     => 'Código caja'
            ))
            // ->add('tituloCarp', Filters\TextFilterType::class, array(
            //     'label'         => 'Título carpeta',
            // ))
            ->add('tituloCarp', Filters\ChoiceFilterType::class, array(
                'choices'   => $options['data'][0], //0 porque es la posicion de los titulos en arrayOptions
                'label'     => 'Título carpeta'
            ))
            ->add('fechaDesdeCarp', Filters\DateRangeFilterType::class,  array(
                'label'     => 'Fecha',
                'left_date_options' => array(
                    'widget' => 'single_text',
                    'label' => 'desde'
                ),
            ))
            ->add('fechaHastaCarp', Filters\DateRangeFilterType::class,  array(
                'label'     => 'Fecha',
                'right_date_options' => array(
                    'widget' => 'single_text',
                    'label' => 'hasta'
                )
            ))
            // ->add('estado', Filters\ChoiceFilterType::class, array(
            //     'choices'  => [
            //         //Esto puedo dejarlo asi , porque lo que sucede es que el campo
            //         //tiene 40 caracteres y completa los demas con vacios, entonces tengo que rellenarlos
            //         //con la busqueda tambien, y sino la solucion prolija es usar la nueva tabla
            //         'En Archivo' => 'En archivo                              ',
            //         'Retirada' => 'Retirada                                ',
            //     ],
            //     'label'     => 'Estado'
            // ));
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
