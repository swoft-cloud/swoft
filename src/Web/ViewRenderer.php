<?php
/**
 * Created by PhpStorm.
 * User: inhere
 * Date: 2017-08-30
 * Time: 17:31
 */

namespace Swoft\Web;

use Swoft\App;
use Swoft\Bean\Annotation\Inject;
use Swoft\Helper\FileHelper;

/**
 * Class ViewRenderer - Render PHP view scripts
 *
 * @package Swoft\Web
 * @uses      ViewRenderer
 * @version   2017年08月14日
 * @author    inhere <in.798@qq.com>
 * @copyright Copyright 2010-2016 Swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class ViewRenderer
{
    /** @var string 视图存放基础路径 */
    protected $viewsPath;

    /** @var null|string 默认布局文件 */
    protected $layout;

    /** @var array Attributes for the view */
    protected $attributes;

    /** @var string Default view suffix. */
    protected $suffix = 'php';

    /** @var array Allowed suffix list. It use auto add suffix. */
    protected $suffixes = ['php', 'tpl', 'html'];

    /**
     * in layout file '...<body>{_CONTENT_}</body>...'
     *
     * @var string
     */
    protected $placeholder = '{_CONTENT_}';

    /**
     * constructor.
     *
     * @param string $viewsPath
     * @param string $layout
     * @param array $attributes
     */
    public function __construct($viewsPath = null, $layout = null, array $attributes = [])
    {
        $this->layout = $layout;
        $this->attributes = $attributes;

        $this->setViewsPath($this->viewsPath);
    }

    /**
     * Render a view, if layout file is setting, will use it.
     * throws RuntimeException if view file does not exist
     *
     * @param string $view
     * @param array $data extract data to view, cannot contain view as a key
     * @param string|null|false $layout override default layout file
     * @return string
     * @throws \Throwable
     */
    public function render($view, array $data = [], $layout = null)
    {
        $output = $this->fetch($view, $data);

        // False - will disable use layout file
        if ($layout === false) {
            return $output;
        }

        return $this->renderContent($output, $data, $layout);
    }

    /**
     * @param $view
     * @param array $data
     * @return string
     * @throws \Throwable
     */
    public function renderPartial($view, array $data = [])
    {
        return $this->fetch($view, $data);
    }

    /**
     * @param string $content
     * @param array $data
     * @param string|null $layout override default layout file
     * @return string
     * @throws \Throwable
     */
    public function renderBody($content, array $data = [], $layout = null)
    {
        return $this->renderContent($content, $data, $layout);
    }

    /**
     * @param string $content
     * @param array $data
     * @param string|null $layout override default layout file
     * @return string
     * @throws \Throwable
     */
    public function renderContent($content, array $data = [], $layout = null)
    {
        // render layout
        if ($layout = $layout ? : $this->layout) {
            $mark = $this->placeholder;
            $main = $this->fetch($layout, $data);
            $content = preg_replace("/$mark/", $content, $main, 1);
        }

        return $content;
    }

    /**
     * @param $view
     * @param array $data
     * @param bool $outputIt
     * @return string|null
     * @throws \Throwable
     */
    public function include($view, array $data = [], $outputIt = true)
    {
        if ($outputIt) {
            echo $this->fetch($view, $data);
            return null;
        }

        return $this->fetch($view, $data);
    }

    /**
     * Renders a view and returns the result as a string
     * throws RuntimeException if $viewsPath . $view does not exist
     *
     * @param string $view
     * @param array $data
     * @return mixed
     * @throws \Throwable
     */
    public function fetch($view, array $data = [])
    {
        $file = $this->getViewFile($view);

        if (! is_file($file)) {
            throw new \RuntimeException("cannot render '$view' because the view file does not exist. File: $file");
        }

        /*
        foreach ($data as $k=>$val) {
            if (in_array($k, array_keys($this->attributes))) {
                throw new \InvalidArgumentException("Duplicate key found in data and renderer attributes. " . $k);
            }
        }
        */
        $data = array_merge($this->attributes, $data);

        try {
            ob_start();
            $this->protectedIncludeScope($file, $data);
            $output = ob_get_clean();
        } catch (\Throwable $e) { // PHP 7+
            ob_end_clean();
            throw $e;
        }

        return $output;
    }

    /**
     * @param $view
     * @return string
     */
    public function getViewFile($view)
    {
        $view = $this->getRealView($view);

        return FileHelper::isAbsPath($view) ? $view : $this->getViewsPath() . $view;
    }

    /**
     * @param string $file
     * @param array $data
     */
    protected function protectedIncludeScope($file, array $data)
    {
        extract($data, EXTR_OVERWRITE);
        include $file;
    }

    /**
     * Get the attributes for the renderer
     *
     * @return array
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * Set the attributes for the renderer
     *
     * @param array $attributes
     */
    public function setAttributes(array $attributes)
    {
        $this->attributes = $attributes;
    }

    /**
     * Add an attribute
     *
     * @param $key
     * @param $value
     */
    public function addAttribute($key, $value)
    {
        $this->attributes[$key] = $value;
    }

    /**
     * Retrieve an attribute
     *
     * @param $key
     * @return mixed
     */
    public function getAttribute($key)
    {
        if (! isset($this->attributes[$key])) {
            return null;
        }

        return $this->attributes[$key];
    }

    /**
     * Get the view path
     *
     * @return string
     */
    public function getViewsPath()
    {
        return App::getAlias($this->viewsPath);
    }

    /**
     * Set the view path
     *
     * @param string $viewsPath
     */
    public function setViewsPath($viewsPath)
    {
        if ($viewsPath) {
            $this->viewsPath = rtrim($viewsPath, '/\\') . '/';
        }
    }

    /**
     * Get the layout file
     *
     * @return string
     */
    public function getLayout()
    {
        return $this->layout;
    }

    /**
     * Set the layout file
     *
     * @param string $layout
     */
    public function setLayout($layout)
    {
        $this->layout = rtrim($layout, '/\\');
    }

    /**
     * @return string
     */
    public function getPlaceholder(): string
    {
        return $this->placeholder;
    }

    /**
     * @param string $placeholder
     */
    public function setPlaceholder(string $placeholder)
    {
        $this->placeholder = $placeholder;
    }

    /**
     * @param string $view
     * @return string
     */
    protected function getRealView($view)
    {
        $sfx = FileHelper::getSuffix($view, true);
        $ext = $this->suffix;

        if ($sfx === $ext || in_array($sfx, $this->suffixes, true)) {
            return $view;
        }

        return $view . '.' . $ext;
    }

    /**
     * @return string
     */
    public function getSuffix(): string
    {
        return $this->suffix;
    }

    /**
     * @param string $suffix
     */
    public function setSuffix(string $suffix)
    {
        $this->suffix = $suffix;
    }
}
