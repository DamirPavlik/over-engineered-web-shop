<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

use App\Admin;
use App\Config;
use App\Contracts\AdminInterface;
use App\Contracts\AdminServiceInterface;
use App\Contracts\EntityManagerServiceInterface;
use App\Contracts\SessionInterface;
use App\Contracts\ValidatorFactoryInterface;
use App\DataObjects\SessionConfig;
use App\Enum\AppEnvironment;
use App\Enum\SameSite;
use App\RouteEntityBindingStrategy;
use App\Services\AdminService;
use App\Services\EntityManagerService;
use App\Session;
use App\Validators\ValidatorFactory;
use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\ORMSetup;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Slim\App;
use Slim\Factory\AppFactory;
use Slim\Views\Twig;
use Symfony\Bridge\Twig\Extension\AssetExtension;
use Symfony\Component\Asset\Package;
use Symfony\Component\Asset\Packages;
use Symfony\Component\Asset\VersionStrategy\JsonManifestVersionStrategy;
use Symfony\WebpackEncoreBundle\Asset\EntrypointLookup;
use Symfony\WebpackEncoreBundle\Asset\TagRenderer;
use Symfony\WebpackEncoreBundle\Twig\EntryFilesTwigExtension;
use Twig\Extra\Intl\IntlExtension;

use function DI\create;

return [
    App::class                              => function (ContainerInterface $container) {
        AppFactory::setContainer($container);

        $addMiddlewares = require CONFIG_PATH . '/middleware.php';
        $router         = require CONFIG_PATH . '/routes/web.php';

        $app = AppFactory::create();

        $app->getRouteCollector()->setDefaultInvocationStrategy(new RouteEntityBindingStrategy($container->get(EntityManagerServiceInterface::class), $app->getResponseFactory()));

        $router($app);

        $addMiddlewares($app);

        return $app;
    },
    Config::class                           => create(Config::class)->constructor(
        require CONFIG_PATH . '/app.php'
    ),
    EntityManagerInterface::class                    => function (Config $config) {
        $ormConfig = ORMSetup::createAttributeMetadataConfiguration(
            $config->get('doctrine.entity_dir'),
            $config->get('doctrine.dev_mode')
        );

//        $ormConfig->addFilter('user', UserFilter::class);

        return new EntityManager(
            DriverManager::getConnection($config->get('doctrine.connection'), $ormConfig),
            $ormConfig
        );
    },

    Twig::class                     => function (Config $config, ContainerInterface $container) {
        $twig = Twig::create(VIEW_PATH, [
            'cache'       => STORAGE_PATH . '/cache/templates',
            'auto_reload' => AppEnvironment::isDevelopment($config->get('app_environment')),
        ]);

        $twig->addExtension(new IntlExtension());
        $twig->addExtension(new EntryFilesTwigExtension($container));
        $twig->addExtension(new AssetExtension($container->get('webpack_encore.packages')));

        return $twig;
    },
    /**
     * The following two bindings are needed for EntryFilesTwigExtension & AssetExtension to work for Twig
     */
    'webpack_encore.packages'               => fn() => new Packages(
        new Package(new JsonManifestVersionStrategy(BUILD_PATH . '/manifest.json'))
    ),
    'webpack_encore.tag_renderer'           => fn(ContainerInterface $container) => new TagRenderer(
        new EntrypointLookup(BUILD_PATH . '/entrypoints.json'),
        $container->get('webpack_encore.packages')
    ),

    ResponseFactoryInterface::class => fn(App $app) => $app->getResponseFactory(),
    AdminServiceInterface::class => fn(ContainerInterface $container) => $container->get(
        AdminService::class
    ),
    AdminInterface::class => fn(ContainerInterface $container) => $container->get(
      Admin::class
    ),
    SessionInterface::class => fn(Config $config) => new Session(
        new SessionConfig(
           $config->get('session.name', ''),
           $config->get('session.flash_name',  'flash'),
           $config->get('session,secure', true),
           $config->get('session.httponly', true),
           SameSite::from($config->get('session.samesite', 'lax'))
        )
    ),
    EntityManagerServiceInterface::class => fn(EntityManagerInterface $entityManager) => new EntityManagerService($entityManager),
    ValidatorFactoryInterface::class => fn(ContainerInterface $container) => $container->get(
      ValidatorFactory::class
    ),
];
