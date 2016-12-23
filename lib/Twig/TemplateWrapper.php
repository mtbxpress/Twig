<?php

/*
 * This file is part of Twig.
 *
 * (c) 2016 Fabien Potencier
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * Exposes a template to userland.
 *
 * @author Fabien Potencier <fabien@symfony.com>
 */
final class Twig_TemplateWrapper
{
    private $env;
    private $template;

    /**
     * This method is for internal use only and should never be called
     * directly (use Twig_Environment::load() instead).
     *
     * @internal
     */
    public function __construct(Twig_Environment $env, Twig_Template $template)
    {
        $this->env = $env;
        $this->template = $template;
    }

    /**
     * Renders the template.
     *
     * @param array $context An array of parameters to pass to the template
     */
    public function render($context = array()): string
    {
        return $this->template->render($context);
    }

    /**
     * Displays the template.
     *
     * @param array $context An array of parameters to pass to the template
     */
    public function display($context = array())
    {
        $this->template->display($context);
    }

    /**
     * Checks if a block is defined.
     *
     * @param array $context An array of parameters to pass to the template
     */
    public function hasBlock(string $name, $context = array()): bool
    {
        return $this->template->hasBlock($name, $context);
    }

    /**
     * Returns defined block names in the template.
     *
     * @param array $context An array of parameters to pass to the template
     *
     * @return string[] An array of defined template block names
     */
    public function getBlockNames($context = array())
    {
        return $this->template->getBlockNames($context);
    }

    /**
     * Renders a template block.
     *
     * @param array $context An array of parameters to pass to the template
     */
    public function renderBlock(string $name, $context = array()): string
    {
        $context = $this->env->mergeGlobals($context);
        $level = ob_get_level();
        ob_start();
        try {
            $this->template->displayBlock($name, $context);
        } catch (Exception $e) {
            while (ob_get_level() > $level) {
                ob_end_clean();
            }

            throw $e;
        } catch (Throwable $e) {
            while (ob_get_level() > $level) {
                ob_end_clean();
            }

            throw $e;
        }

        return ob_get_clean();
    }

    /**
     * Displays a template block.
     *
     * @param array $context An array of parameters to pass to the template
     */
    public function displayBlock(string $name, $context = array())
    {
        $this->template->displayBlock($name, $this->env->mergeGlobals($context));
    }

    public function getSourceContext(): Twig_Source
    {
        return $this->template->getSourceContext();
    }
}
