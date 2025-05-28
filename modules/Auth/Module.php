<?php
namespace Modules\Auth;

use Illuminate\Routing\Router;
use Modules\Auth\Controllers\Admin\RolesResourceController;
use Modules\Auth\Controllers\Admin\UsersController;
use Modules\Auth\Controllers\Admin\UsersResourceController;
use Modules\Auth\Controllers\AuthController;
use Modules\Auth\Repositories\RolesRepository;
use Modules\Auth\Repositories\UsersRepository;
use Modules\Auth\View\Widgets\AuthBlock;
use Modules\Auth\View\Widgets\Welcome;
use Sitefrog\Facades\RouteManager;
use Sitefrog\Models\User;
use Sitefrog\Modules\BaseModule;
use Sitefrog\View\Menu\MenuItem;
use Spatie\Permission\Models\Role;

class Module extends BaseModule
{
    public $name = 'Auth';
    public $hasViews = true;
    public $hasTranslations = true;

    public function load(): void
    {
        $this->registerRoutes(function(Router $router) {
            $router->name($this->getRouteName('login'))->any('/login', [AuthController::class, 'login']);
            $router->name($this->getRouteName('register'))->any('/register', [AuthController::class, 'register']);
            $router->name($this->getRouteName('profile'))->any('/profile', [AuthController::class, 'profile']);
            $router->name($this->getRouteName('logout'))->post('/logout', [AuthController::class, 'logout']);
        });

        $this->registerRoutes(function(Router $router) {
            $router->name($this->getRouteName('admin.users.index'))->get('users', [UsersController::class, 'index']);
            $router->name($this->getRouteName('admin.users.settings'))->get('users/settings', [UsersController::class, 'settings']);

            RouteManager::resource(
                router: $router,
                controller: UsersResourceController::class,
                url: 'users/list',
                name: $this->getRouteName('admin.users.list')
            );

            RouteManager::resource(
                router: $router,
                controller: RolesResourceController::class,
                url: 'users/roles',
                name: $this->getRouteName('admin.users.roles')
            );

        }, 'admin');

        $this->registerWidget('auth-block', AuthBlock::class);
        $this->registerWidget('welcome', Welcome::class);

        $this->registerAdminMenuItems([
            new MenuItem(
                title: 'users',
                url: sitefrogRoute('auth', 'admin.users.index'),
                children: [
                    new MenuItem(
                        title: __('sitefrog.auth::admin.users.settings'),
                        url: sitefrogRoute('auth', 'admin.users.settings')
                    ),
                    new MenuItem(
                        title: __('sitefrog.auth::admin.users.list'),
                        url: sitefrogRoute('auth', 'admin.users.list.index')
                    ),
                    new MenuItem(
                        title: __('sitefrog.auth::admin.roles.list'),
                        url: sitefrogRoute('auth', 'admin.users.roles.index')
                    )
                ]
            )
        ]);

        $this->registerRepository(User::class, UsersRepository::class);
        $this->registerRepository(Role::class, RolesRepository::class);

        $this->registerResourcePermissions('users', null, ['create', 'delete']);

    }

}
