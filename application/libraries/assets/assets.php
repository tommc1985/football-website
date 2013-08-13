<?php
class Assets {
    protected static $_Instance;
    protected $_theme;
    protected static $_css;
    protected static $_js;

    public function __construct()
    {
        $this->_css = array();
        $this->_js  = array();
        $this->_theme = 'default';
    }

    /**
     * Get the Instance of itself, using the singleton methodology
     * @return object
     */
    public static function get_instance()
    {
        if (self::$_Instance === NULL) {
            self::$_Instance = new Assets();
        }
        return self::$_Instance;
    }

    /**
     * Add a CSS file to the stack
     * @param string $path        Path to the file
     * @param string $condition   Any conditions relating to the CSS file
     */
    public static function addCss($path, $condition = '')
    {
        self::$_Instance->_css[] = array(
            'path'      => $path,
            'condition' => $condition,
        );
    }

    /**
     * Add a JS file to the stack
     * @param string $path    Path to the file
     */
    public static function addJs($path)
    {
        self::$_Instance->_js[] = $path;
    }

    /**
     * Return String of CSS links
     * @return string
     */
    public static function css()
    {
        $string = '';
        foreach (self::$_Instance->_css as $cssFile) {
            if (strpos($cssFile['path'], 'http') === false) {
                $cssFile['path'] = site_url($cssFile['path']);
            }

            $individualString = "<link href='{$cssFile['path']}' rel='stylesheet'>\r\n";

            if ($cssFile['condition']) {
                $individualString = "<!--[if {$cssFile['condition']}]>\r\n{$individualString}<![endif]-->\r\n";
            }

            $string .= $individualString;
        }

        $string .= "<link href='" . site_url("assets/themes/" . self::$_Instance->_theme . "/css/style.css") . "' rel='stylesheet'>\r\n";

        return $string;
    }

    /**
     * Return String of JS links
     * @return object
     */
    public static function js()
    {
        $string = '';
        foreach (self::$_Instance->_js as $jsPath) {
            if (strpos($jsPath, 'http') === false) {
                $jsPath = site_url($jsPath);
            }

            $string .= "<script type='text/javascript' src='{$jsPath}'></script>\r\n";
        }

        return $string;
    }
} ?>