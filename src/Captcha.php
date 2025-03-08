<?php

declare(strict_types=1);

namespace Eprofos\Captcha;

use Eprofos\Captcha\Config\CaptchaConfig;
use Eprofos\Captcha\Exception\CaptchaException;
use Eprofos\Captcha\Renderer\GdRenderer;
use Eprofos\Captcha\Renderer\RendererInterface;
use Eprofos\Captcha\Storage\SessionStorage;
use Eprofos\Captcha\Storage\StorageInterface;

/**
 * Main class for captcha generation and management.
 *
 * @author Houssem TAYECH <houssem@eprofos.com>
 */
class Captcha
{
    /**
     * Captcha configuration.
     *
     * @var CaptchaConfig
     */
    private CaptchaConfig $config;

    /**
     * Captcha code storage.
     *
     * @var StorageInterface|null
     */
    private ?StorageInterface $storage;

    /**
     * Renderer for generating captcha representation.
     *
     * @var RendererInterface
     */
    private RendererInterface $renderer;

    /**
     * Generated captcha code.
     *
     * @var string|null
     */
    private ?string $code = null;

    /**
     * Constructor.
     *
     * @param CaptchaConfig|null $config Captcha configuration
     * @param StorageInterface|null $storage Captcha code storage
     * @param RendererInterface|null $renderer Renderer for generating captcha representation
     */
    public function __construct(
        ?CaptchaConfig $config = null,
        ?StorageInterface $storage = null,
        ?RendererInterface $renderer = null
    ) {
        $this->config = $config ?? new CaptchaConfig();
        $this->storage = $storage;
        $this->renderer = $renderer ?? new GdRenderer();
    }

    /**
     * Generates a new captcha code.
     *
     * @return string The generated code
     */
    public function generate(): string
    {
        // Generate random code
        $this->code = $this->generateRandomCode(
            $this->config->getLength(),
            $this->config->getCharacterSet()
        );

        // Store the code if storage is defined
        if ($this->storage !== null) {
            $this->storage->store($this->code);
        }

        // Generate captcha representation
        $this->renderer->render($this->code, $this->config);

        return $this->code;
    }

    /**
     * Outputs the captcha representation to the browser.
     *
     * @return void
     */
    public function output(): void
    {
        if ($this->code === null) {
            throw new CaptchaException('No captcha has been generated. Call generate() first.');
        }

        $this->renderer->output();
    }

    /**
     * Saves the captcha representation to a file.
     *
     * @param string $path Path where to save the captcha
     * @return bool True if save successful, false otherwise
     */
    public function save(string $path): bool
    {
        if ($this->code === null) {
            throw new CaptchaException('No captcha has been generated. Call generate() first.');
        }

        return $this->renderer->save($path);
    }

    /**
     * Verifies if the provided code matches the captcha code.
     *
     * @param string $code Code to verify
     * @return bool True if code matches, false otherwise
     * @throws CaptchaException If no storage is defined
     */
    public function verify(string $code): bool
    {
        if ($this->storage === null) {
            throw new CaptchaException('No storage is defined. Unable to verify code.');
        }

        return $this->storage->verify($code, $this->config->isCaseSensitive());
    }

    /**
     * Gets the generated captcha code.
     *
     * @return string|null The captcha code or null if not generated
     */
    public function getCode(): ?string
    {
        return $this->code;
    }

    /**
     * Gets the captcha configuration.
     *
     * @return CaptchaConfig
     */
    public function getConfig(): CaptchaConfig
    {
        return $this->config;
    }

    /**
     * Sets the captcha configuration.
     *
     * @param CaptchaConfig $config
     * @return self
     */
    public function setConfig(CaptchaConfig $config): self
    {
        $this->config = $config;

        return $this;
    }

    /**
     * Gets the captcha code storage.
     *
     * @return StorageInterface|null
     */
    public function getStorage(): ?StorageInterface
    {
        return $this->storage;
    }

    /**
     * Sets the captcha code storage.
     *
     * @param StorageInterface|null $storage
     * @return self
     */
    public function setStorage(?StorageInterface $storage): self
    {
        $this->storage = $storage;

        return $this;
    }

    /**
     * Gets the renderer.
     *
     * @return RendererInterface
     */
    public function getRenderer(): RendererInterface
    {
        return $this->renderer;
    }

    /**
     * Sets the renderer.
     *
     * @param RendererInterface $renderer
     * @return self
     */
    public function setRenderer(RendererInterface $renderer): self
    {
        $this->renderer = $renderer;

        return $this;
    }

    /**
     * Generates a random code.
     *
     * @param int $length Code length
     * @param string $characterSet Character set to use
     * @return string The generated code
     */
    private function generateRandomCode(int $length, string $characterSet): string
    {
        $code = '';
        $characterSetLength = mb_strlen($characterSet);

        // Check that the character set is not empty
        if ($characterSetLength <= 0) {
            throw new CaptchaException('Character set cannot be empty.');
        }

        // Generate each character of the code
        for ($i = 0; $i < $length; $i++) {
            $randomIndex = random_int(0, $characterSetLength - 1);
            $code .= mb_substr($characterSet, $randomIndex, 1);
        }

        return $code;
    }

    /**
     * Creates a captcha instance with session storage.
     *
     * @param CaptchaConfig|null $config Captcha configuration
     * @param string $sessionKey Session key to store the code
     * @return self
     */
    public static function withSessionStorage(
        ?CaptchaConfig $config = null,
        string $sessionKey = 'eprofos_captcha_code'
    ): self {
        return new self(
            $config,
            new SessionStorage($sessionKey)
        );
    }
}
