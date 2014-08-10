<?php

namespace Magice\Bundle\ClientUserBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    const FIREWALL_NAMR     = 'magice_client_user';
    const RESOURCE_NAME     = 'joyprice';
    const RESOURCE_PROPERTY = 'joypriceId';
    const USER_PROVIDER     = 'mg.client.user.provider';

    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode    = $treeBuilder->root('magice_client_user');

        $rootNode
            ->children()
                ->scalarNode('driver')->defaultValue('orm')->cannotBeEmpty()->end()
                ->scalarNode('already_logedin_redirect_path')->defaultValue('/')->end()
                ->scalarNode('firewall')->defaultValue(self::FIREWALL_NAMR)->cannotBeEmpty()->end()
                ->scalarNode('firewall_pattern')->defaultValue('/.*')->cannotBeEmpty()->end()
                ->scalarNode('provider')->defaultValue(self::USER_PROVIDER)->cannotBeEmpty()->end()
                ->scalarNode('username_iterations')->defaultValue(30)->cannotBeEmpty()->end()
                ->scalarNode('url_login')->defaultValue('/login')->cannotBeEmpty()->end()
                ->scalarNode('url_login_failure')->defaultValue('/login')->cannotBeEmpty()->end()
                ->scalarNode('url_connect')->defaultValue('/login/' . self::RESOURCE_NAME)->cannotBeEmpty()->end()

                ->scalarNode('client_id')->isRequired()->cannotBeEmpty()->end()
                ->scalarNode('client_secret')->isRequired()->cannotBeEmpty()->end()
                ->scalarNode('scope')->isRequired()->cannotBeEmpty()->end()
                ->scalarNode('url_token')->isRequired()->cannotBeEmpty()->end()
                ->scalarNode('url_auth')->isRequired()->cannotBeEmpty()->end()
                ->scalarNode('url_info')->isRequired()->cannotBeEmpty()->end()
                ->scalarNode('url_register')->isRequired()->cannotBeEmpty()->end()

                ->arrayNode('class')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('user')->defaultValue('Magice\Bundle\ClientUserBundle\Entity\User')->cannotBeEmpty()->end()
                        ->scalarNode('response')->defaultValue('Magice\Bundle\ClientUserBundle\Response\UserResponse')->cannotBeEmpty()->end()
                    ->end()
                ->end()
            ->end();


        return $treeBuilder;
    }
}
