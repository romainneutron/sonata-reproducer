<?php

namespace AppBundle\Admin\Block;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\CoreBundle\Validator\ErrorElement;
use Sonata\BlockBundle\Model\BlockInterface;
use Sonata\BlockBundle\Block\BlockContextInterface;
use Sonata\BlockBundle\Block\Service\AbstractBlockService;

class GraphsBlockService extends AbstractBlockService
{
    public function getName()
    {
        return 'App Graphs';
    }

    public function setDefaultSettings(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'url'      => false,
            'title' => 'App Graphs',
            'template' => '@App/Admin/Block/app-graphs.html.twig',
            'metric_endpoint' => 'https://graphite.monitoring',
            'metric_prefix' => null,
        ]);
    }

    public function buildEditForm(FormMapper $formMapper, BlockInterface $block)
    {
        $formMapper->add('settings', 'sonata_type_immutable_array', [
            'keys' => [
                ['title', 'text', ['required' => false]],
            ],
        ]);
    }

    public function validateBlock(ErrorElement $errorElement, BlockInterface $block)
    {
        $errorElement
            ->with('settings[title]')
            ->assertNotNull(array())
            ->assertNotBlank()
            ->assertMaxLength(array('limit' => 50))
            ->end();
    }

    public function execute(BlockContextInterface $blockContext, Response $response = null)
    {
        return $this->renderResponse($blockContext->getTemplate(), [
            'block' => $blockContext->getBlock(),
            'settings' => $blockContext->getSettings(),
        ], $response);
    }
}
