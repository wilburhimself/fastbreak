<?php

namespace Core\Helpers;

class HtmlHelper
{
    // Construye un link siguiendo los standares de la aplicacion
    public static function linkTo(string $text, ?string $controller = null, ?string $action = null, ?array $params = null): string
    {
        $attributes = self::extractAttributes($params);

        $output = '<a href="' . self::generateUrl($controller, $action, $params) . '"';
        $output .= self::buildHtmlAttributes($attributes);
        $output .= '>' . $text . '</a>';

        return $output;
    }

    private static function extractAttributes(?array $params): array
    {
        $attributes = [];
        if (isset($params['htmlid'])) {
            $attributes['id'] = $params['htmlid'];
            unset($params['htmlid']);
        }
        if (isset($params['htmlclass'])) {
            $attributes['class'] = $params['htmlclass'];
            unset($params['htmlclass']);
        }
        if (isset($params['htmlrel'])) {
            $attributes['rel'] = $params['htmlrel'];
            unset($params['htmlrel']);
        }
        if (isset($params['htmltitle'])) {
            $attributes['title'] = $params['htmltitle'];
            unset($params['htmltitle']);
        }
        if (isset($params['htmlconfirm'])) {
            $attributes['onclick'] = "return confirm('" . htmlspecialchars($params['htmlconfirm'], ENT_QUOTES) . "')";
            unset($params['htmlconfirm']);
        }

        return $attributes;
    }

    private static function generateUrl(?string $controller, ?string $action, ?array $params): string
    {
        return base_url() . construct_url($controller, $action, $params);
    }

    private static function buildHtmlAttributes(array $attributes): string
    {
        $html = '';
        foreach ($attributes as $key => $value) {
            $html .= ' ' . $key . '="' . htmlspecialchars($value, ENT_QUOTES) . '"';
        }
        return $html;
    }

    public static function anchor(string $text, string $url, ?string $attrs = null): string
    {
        $link_format = '<a href="%s" %s>%s</a>';
        return sprintf($link_format, base_url() . $url, $attrs ?? '', $text);
    }

    /* add stylesheet */
    public static function addCss(string $css, string $media = "screen"): string
    {
        return '<link type="text/css" rel="stylesheet" media="' . $media . '" href="' . base_url() . 'assets/stylesheets/' . $css . '.css" />';
    }

    /* add stylesheet */
    public static function addLess(string $css): string
    {
        return '<link type="text/css" rel="stylesheet/less" href="' . base_url() . 'assets/stylesheets/' . $css . '.less" />';
    }

    /* add javascript */
    public static function addJs(string $js): string
    {
        return '<script type="text/javascript" src="' . base_url() . 'assets/javascript/' . $js . '.js"></script>';
    }
}