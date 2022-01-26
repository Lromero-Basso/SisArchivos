<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DepcajasFilterType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('codEstante')
            ->add('codLado')
            ->add('columna')
            ->add('piso')
            ->add('codArea')
            ->add('tituloCaja')
            ->add('nroDesdeCaja')
            ->add('nroHastaCaja')
            ->add('fechaDesdeCaja')
            ->add('fechaHastaCaja')
            ->add('archivadoHasta')
            ->add('observa');
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Depcajas'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_depcajas';
    }


}
