<?php

namespace App;

use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

class Kernel extends BaseKernel
{
    use MicroKernelTrait;

    /**
     * @phpstan-ignore-next-line method.unused
     */
    private function configureRoutes(RoutingConfigurator $routes): void
    {
        // Public routes
        $routes->add('app_public_index', '/')
            ->controller('App\Controller\Public\PublicController::index')
            ->methods(['GET']);

        $routes->add('app_public_category', '/category/{id}')
            ->controller('App\Controller\Public\PublicController::category')
            ->methods(['GET'])
            ->requirements(['id' => '\d+']);

        $routes->add('app_public_news', '/news/{id}')
            ->controller('App\Controller\Public\PublicController::news')
            ->methods(['GET'])
            ->requirements(['id' => '\d+']);

        $routes->add('app_public_comment', '/news/{id}/comment')
            ->controller('App\Controller\Public\PublicController::comment')
            ->methods(['POST'])
            ->requirements(['id' => '\d+']);

        // Admin routes
        $routes->add('app_admin_index', '/admin')
            ->controller('App\Controller\Admin\AdminController::index')
            ->methods(['GET']);

        $routes->add('app_admin_login', '/admin/login')
            ->controller('App\Controller\Admin\AdminController::login')
            ->methods(['GET', 'POST']);

        $routes->add('app_admin_logout', '/admin/logout')
            ->controller('App\Controller\Admin\AdminController::logout')
            ->methods(['POST']);

        // Admin Category routes
        $routes->add('app_admin_category_index', '/admin/category')
            ->controller('App\Controller\Admin\AdminCategoryController::index')
            ->methods(['GET']);

        $routes->add('app_admin_category_new', '/admin/category/new')
            ->controller('App\Controller\Admin\AdminCategoryController::new')
            ->methods(['GET', 'POST']);

        $routes->add('app_admin_category_show', '/admin/category/{id}')
            ->controller('App\Controller\Admin\AdminCategoryController::show')
            ->methods(['GET'])
            ->requirements(['id' => '\d+']);

        $routes->add('app_admin_category_edit', '/admin/category/{id}/edit')
            ->controller('App\Controller\Admin\AdminCategoryController::edit')
            ->methods(['GET', 'POST'])
            ->requirements(['id' => '\d+']);

        $routes->add('app_admin_category_delete', '/admin/category/{id}')
            ->controller('App\Controller\Admin\AdminCategoryController::delete')
            ->methods(['POST'])
            ->requirements(['id' => '\d+']);

        // Admin News routes
        $routes->add('app_admin_news_index', '/admin/news')
            ->controller('App\Controller\Admin\AdminNewsController::index')
            ->methods(['GET']);

        $routes->add('app_admin_news_new', '/admin/news/new')
            ->controller('App\Controller\Admin\AdminNewsController::new')
            ->methods(['GET', 'POST']);

        $routes->add('app_admin_news_show', '/admin/news/{id}')
            ->controller('App\Controller\Admin\AdminNewsController::show')
            ->methods(['GET'])
            ->requirements(['id' => '\d+']);

        $routes->add('app_admin_news_edit', '/admin/news/{id}/edit')
            ->controller('App\Controller\Admin\AdminNewsController::edit')
            ->methods(['GET', 'POST'])
            ->requirements(['id' => '\d+']);

        $routes->add('app_admin_news_delete', '/admin/news/{id}')
            ->controller('App\Controller\Admin\AdminNewsController::delete')
            ->methods(['POST'])
            ->requirements(['id' => '\d+']);

        // Admin Comment routes
        $routes->add('app_admin_comment_index', '/admin/comment')
            ->controller('App\Controller\Admin\AdminCommentController::index')
            ->methods(['GET']);

        $routes->add('app_admin_comment_show', '/admin/comment/{id}')
            ->controller('App\Controller\Admin\AdminCommentController::show')
            ->methods(['GET'])
            ->requirements(['id' => '\d+']);

        $routes->add('app_admin_comment_delete', '/admin/comment/{id}')
            ->controller('App\Controller\Admin\AdminCommentController::delete')
            ->methods(['POST'])
            ->requirements(['id' => '\d+']);

        $routes->add('app_admin_news_comments', '/admin/news/{id}/comments')
            ->controller('App\Controller\Admin\AdminCommentController::newsByComments')
            ->methods(['GET'])
            ->requirements(['id' => '\d+']);
    }
}
