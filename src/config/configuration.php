<?php

declare(strict_types=1);

use Symfony\Component\Config\Definition\Configurator\DefinitionConfigurator;

/** @php-cs-fixer-ignore */
return static function (DefinitionConfigurator $definition) {
    $definition->rootNode()
        ->addDefaultsIfNotSet()
        ->children()
            ->scalarNode('user')
                ->info('Unix user of processes. Default: current user')
                ->defaultNull()
                ->end()
            ->scalarNode('group')
                ->info('Unix group of processes. Default: current group')
                ->defaultNull()
                ->end()
            ->integerNode('stop_timeout')
                ->info('Max seconds of child process work before force kill')
                ->defaultValue(2)
                ->end()
            ->scalarNode('pid_file')
                ->info('File to store master process PID')
                ->cannotBeEmpty()
                ->defaultValue('%kernel.project_dir%/var/run/workerman.pid')
                ->end()
            ->scalarNode('log_file')
                ->info('Log file')
                ->cannotBeEmpty()
                ->defaultValue('%kernel.project_dir%/var/log/workerman.log')
                ->end()
            ->scalarNode('stdout_file')
                ->info('File to write all output (echo var_dump, etc.) to when server is running as daemon')
                ->cannotBeEmpty()
                ->defaultValue('%kernel.project_dir%/var/log/workerman.stdout.log')
                ->end()
            ->integerNode('max_package_size')
                ->info('Max package size can be received')
                ->defaultValue(10 * 1024 * 1024)
                ->end()
            ->integerNode('response_chunk_size')
                ->info('Response chunk size')
                ->defaultValue(2048)
                ->end()
            ->booleanNode('symfony_native')
                ->info('(Experimental) use symfony native Request handler instead of workerman')
                ->defaultFalse()
                ->end()
            ->arrayNode('servers')
                ->prototype('array')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('name')
                            ->info('Server process name')
                            ->isRequired()
                            ->cannotBeEmpty()
                            ->end()
                        ->scalarNode('listen')
                            ->info('Listen address. Supported protocols: http://, https://, ws://, wss://, tcp://')
                            ->defaultNull()
                            ->example('http://0.0.0.0:80')
                            ->end()
                        ->scalarNode('local_cert')
                            ->info('Path to local certificate file on filesystem')
                            ->defaultNull()
                            ->end()
                        ->scalarNode('local_pk')
                            ->info('Path to local private key file on filesystem')
                            ->defaultNull()
                            ->end()
                        ->integerNode('processes')
                            ->info('Number of webserver worker processes. Default: number of CPU cores * 2')
                            ->defaultNull()
                            ->end()
                        ->booleanNode('reuse_port')
                            ->info('Set whether the current worker enables the reuse of listening ports (SO_REUSEPORT).')
                            ->defaultFalse()
                            ->end()
                        ->booleanNode('serve_files')
                            ->info('Should current worker serve files from public directory')
                            ->defaultTrue()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->arrayNode('reload_strategy')
                ->info('Reload strategies configuration')
                ->addDefaultsIfNotSet()
                ->children()
                    ->arrayNode('exception')
                        ->info('Reload worker each time that an exception is thrown during the request handling')
                        ->addDefaultsIfNotSet()
                        ->children()
                            ->booleanNode('active')
                                ->info('Is exception strategy active')
                                ->defaultTrue()
                                ->end()
                            ->arrayNode('allowed_exceptions')
                                ->info('List of allowed exceptions that do not trigger a reload')
                                ->prototype('scalar')->end()
                                ->defaultValue([
                                    'Symfony\Component\HttpKernel\Exception\HttpExceptionInterface',
                                    'Symfony\Component\Serializer\Exception\ExceptionInterface',
                                ])
                                ->end()
                            ->end()
                        ->end()
                    ->arrayNode('max_requests')
                        ->info('Reload worker on every N request to prevent memory leaks')
                        ->addDefaultsIfNotSet()
                        ->children()
                            ->booleanNode('active')
                                ->info('Is max_requests strategy active')
                                ->defaultFalse()
                                ->end()
                            ->integerNode('requests')
                                ->info('Maximum number of requests after which the worker will be reloaded')
                                ->defaultValue(1000)
                                ->end()
                            ->integerNode('dispersion')
                                ->info('Prevent simultaneous restart of all workers (1000 requests and 20% dispersion will restart between 800 and 1000)')
                                ->defaultValue(20)
                                ->end()
                            ->end()
                        ->end()
                    ->arrayNode('file_monitor')
                        ->info('Reload all workers each time you change the code')
                        ->addDefaultsIfNotSet()
                        ->children()
                            ->booleanNode('active')
                                ->info('Is file_monitor strategy active')
                                ->defaultFalse()
                                ->end()
                            ->arrayNode('source_dir')
                                ->info('Source directories for file monitoring')
                                ->prototype('scalar')->end()
                                ->defaultValue([
                                    '%kernel.project_dir%/src',
                                    '%kernel.project_dir%/config',
                                ])
                                ->end()
                            ->arrayNode('file_pattern')
                                ->info('Monitored file patterns')
                                ->prototype('scalar')->end()
                                ->defaultValue([
                                    '*.php',
                                    '*.yaml',
                                ])
                                ->end()
                            ->end()
                        ->end()
                    ->arrayNode('always')
                        ->info('Reload worker after each request')
                        ->addDefaultsIfNotSet()
                        ->children()
                            ->booleanNode('active')
                                ->info('Is always strategy active')
                                ->defaultFalse()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end();
};
