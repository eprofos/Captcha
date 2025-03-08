<?php

declare(strict_types=1);

namespace Eprofos\Captcha\Renderer;

use Eprofos\Captcha\Config\CaptchaConfig;

/**
 * Interface for captcha renderers.
 *
 * @author Houssem TAYECH <houssem@eprofos.com>
 */
interface RendererInterface
{
    /**
     * Generates a captcha representation.
     *
     * @param string $code The captcha code to render
     * @param CaptchaConfig $config The captcha configuration
     * @return mixed The captcha representation (image)
     */
    public function render(string $code, CaptchaConfig $config): mixed;

    /**
     * Outputs the captcha representation to the browser.
     *
     * @return void
     */
    public function output(): void;

    /**
     * Saves the captcha representation to a file.
     *
     * @param string $path The path where to save the captcha
     * @return bool True if save successful, false otherwise
     */
    public function save(string $path): bool;
}
