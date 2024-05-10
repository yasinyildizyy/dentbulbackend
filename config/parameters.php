<?php

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use App\Constant\Locale;

return static function (ContainerConfigurator $containerConfigurator) {
    $containerConfigurator->parameters()
        ->set('uploads.slider', 'uploads/slider')
        ->set('uploads.cure', 'uploads/cure')
        ->set('uploads.customer_comment', 'uploads/customer-comment')
        ->set('uploads.page', 'uploads/page')
        ->set('uploads.gallery', 'uploads/gallery')
        ->set('uploads.doctor', 'uploads/doctor')
        ->set('uploads.blog_post', 'uploads/blog-post')
        ->set('uploads.editor', 'uploads/editor')
        ->set('support_locales', 'true' === $_ENV['IS_MULTIPLE_LOCALE'] ? Locale::LOCALES : [Locale::TR])
    ;
};
