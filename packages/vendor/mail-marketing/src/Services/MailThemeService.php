<?php

namespace Vendor\MailMarketing\Services;

use Illuminate\Support\Facades\View;

class MailThemeService
{
    protected $themes;
    protected $defaultTheme;

    public function __construct()
    {
        $this->themes = config('mail-marketing.themes', []);
        $this->defaultTheme = config('mail-marketing.default_theme', 'modern');
    }

    /**
     * Récupère tous les thèmes disponibles
     */
    public function getThemes(): array
    {
        return $this->themes;
    }

    /**
     * Récupère un thème spécifique
     */
    public function getTheme(string $theme): ?array
    {
        return $this->themes[$theme] ?? null;
    }

    /**
     * Rend un email avec le thème choisi
     */
    public function render(string $theme, array $data): string
    {
        $themeData = $this->getTheme($theme);
        
        if (!$themeData) {
            $theme = $this->defaultTheme;
            $themeData = $this->getTheme($theme);
        }
        
        $view = $themeData['view'] ?? 'mail-marketing::emails.themes.' . $theme;
        
        return View::make($view, $data)->render();
    }

    /**
     * Vérifie si un thème existe
     */
    public function themeExists(string $theme): bool
    {
        return isset($this->themes[$theme]);
    }

    /**
     * Récupère le thème par défaut
     */
    public function getDefaultTheme(): string
    {
        return $this->defaultTheme;
    }
}