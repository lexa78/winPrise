<?php
declare(strict_types=1);

namespace app\core;

use function str_replace;
use function sprintf;
use function ob_start;
use function ob_get_clean;
/**
 * Class View
 * @package app\core
 */
class View
{
    /** @var string  */
    public string $title = '';

    /**
     * @param string $view
     * @param array $params
     * @return string
     */
    public function renderView(string $view, array $params = []): string
    {
        $viewContent = $this->renderOnlyView($view, $params);
        $layoutContent = $this->layoutContent();

        return str_replace('{{content}}', $viewContent, $layoutContent);
    }

    /**
     * @param string $viewContent
     * @return string
     */
    public function renderContent(string $viewContent): string
    {
        $layoutContent = $this->layoutContent();

        return str_replace('{{content}}', $viewContent, $layoutContent);
    }

    /**
     * @return string
     */
    protected function layoutContent(): string
    {
        $layout = Application::$app->layout;
        if (Application::$app->controller instanceof Controller) {
            $layout = Application::$app->controller->layout;
        }
        ob_start();
        include_once sprintf('%s/views/layouts/%s.php', Application::$ROOT_DIR, $layout);
        return ob_get_clean();
    }

    /**
     * @param string $view
     * @param array $params
     * @return string
     */
    protected function renderOnlyView(string $view, array $params = []): string
    {
        foreach ($params as $key => $value) {
            $$key = $value;
        }

        ob_start();
        include_once sprintf('%s/views/%s.php', Application::$ROOT_DIR, $view);
        return ob_get_clean();
    }
}