<?php

namespace Magice\Bundle\ClientUserBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class MagiceClientUserExtension extends Extension implements PrependExtensionInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        //$configuration = new Configuration();
        //$config        = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yml');
    }

    /**
     * Allow an extension to prepend the extension configurations.
     * @param ContainerBuilder $container
     */
    public function prepend(ContainerBuilder $container)
    {
        // process the configuration
        $config = $container->getExtensionConfig($this->getAlias());

        // use the Configuration class to generate a config array with the settings
        $config = $this->processConfiguration(new Configuration(), $config);

        $this->forFosUser($container, $config);
        $this->forHwiOauth($container, $config);
        $this->forSecurity($container, $config);
    }

    private function forSecurity(ContainerBuilder $container, array $config)
    {
        $container->setParameter('magice.client.user.already_logedin_redirect_target', $config['already_logedin_redirect_path']);
        $container->setParameter('magice.client.uesr.register_url', $config['url_register']);

        # not use build-in firewall
        if ($config['firewall'] !== Configuration::FIREWALL_NAMR) {
            return;
        }

        $name     = 'security';
        $defaults = array(
            'encoders'  => array(
                'FOS\UserBundle\Model\UserInterface' => 'sha512'
            ),
            'providers' => array(
                'fos_userbundle' => array('id' => 'fos_user.user_provider.username_email')
            ),
            'firewalls' => array(
                Configuration::FIREWALL_NAMR => array(
                    'anonymous' => true,
                    'logout'    => true,
                    'pattern'   => $config['firewall_pattern'],
                    'oauth'     => array(
                        'use_forward'         => false,
                        'login_path'          => $config['url_login'],
                        'failure_path'        => $config['url_login_failure'],
                        'oauth_user_provider' => array(
                            'service' => $config['provider']
                        ),
                        'resource_owners'     => array(
                            Configuration::RESOURCE_NAME => $config['url_connect']
                        )
                    )
                )
            )
        );

        $config = $container->getExtensionConfig($name);
        $config = array_replace_recursive($defaults, $config[0]);

        // access_control cannot be override
        unset($config['access_control']);

        $container->prependExtensionConfig($name, $config);
    }

    private function forFosUser(ContainerBuilder $container, array $config)
    {
        $name = 'fos_user';

        $container->setParameter('magice.client.user.class.user.entity', $config['class']['user']);

        $defaults = array(
            'db_driver'     => $config['driver'],
            'firewall_name' => $config['firewall'],
            'user_class'    => $config['class']['user']
        );

        $config = $container->getExtensionConfig($name);
        $config = array_replace_recursive($defaults, $config[0]);

        $container->prependExtensionConfig($name, $config);
    }

    private function forHwiOauth(ContainerBuilder $container, array $config)
    {
        $name = 'hwi_oauth';

        $container->setParameter('magice.client.user.class.user.response', $config['class']['response']);

        $defaults = array(
            'firewall_name'   => $config['firewall'],
            'connect'         => array(
                'account_connector' => $config['provider']
            ),
            'fosub'           => array(
                'username_iterations' => $config['username_iterations'],
                'properties'          => array(Configuration::RESOURCE_NAME => Configuration::RESOURCE_PROPERTY)
            ),
            'resource_owners' => array(
                Configuration::RESOURCE_NAME => array(
                    'type'                => 'oauth2',
                    'options'             => array('csrf' => true),
                    'user_response_class' => $config['class']['response'],
                    'client_id'           => $config['client_id'],
                    'client_secret'       => $config['client_secret'],
                    'scope'               => $config['scope'],
                    'access_token_url'    => $config['url_token'],
                    'authorization_url'   => $config['url_auth'],
                    'infos_url'           => $config['url_info'],
                )
            )
        );

        $config = $container->getExtensionConfig($name);
        $config = array_replace_recursive($defaults, $config[0]);
        $container->prependExtensionConfig($name, $config);
    }
}
