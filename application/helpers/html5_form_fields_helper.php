<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP 5.1.6 or newer
 *
 * @package        CodeIgniter
 * @author        ExpressionEngine Dev Team
 * @copyright    Copyright (c) 2008 - 2011, EllisLab, Inc.
 * @license        http://codeigniter.com/user_guide/license.html
 * @link        http://codeigniter.com
 * @since        Version 1.0
 * @filesource
 */

// ------------------------------------------------------------------------

/**
 * CodeIgniter Form Helpers
 *
 * @package        CodeIgniter
 * @subpackage    Helpers
 * @category    Helpers
 * @author        ExpressionEngine Dev Team
 * @link        http://codeigniter.com/user_guide/helpers/form_helper.html
 */

// ------------------------------------------------------------------------

/**
 * Color Input Field
 *
 * @access    public
 * @param    mixed
 * @param    string
 * @param    string
 * @return    string
 */
if ( ! function_exists('form_color'))
{
    function form_color($data = '', $value = '', $extra = '')
    {
        $defaults = array('type' => 'color', 'name' => (( ! is_array($data)) ? $data : ''), 'value' => $value);

        return "<input "._parse_form_attributes($data, $defaults).$extra." />";
    }
}

/**
 * Date Input Field
 *
 * @access    public
 * @param    mixed
 * @param    string
 * @param    string
 * @return    string
 */
if ( ! function_exists('form_date'))
{
    function form_date($data = '', $value = '', $extra = '')
    {
        $defaults = array('type' => 'date', 'name' => (( ! is_array($data)) ? $data : ''), 'value' => $value);

        return "<input "._parse_form_attributes($data, $defaults).$extra." />";
    }
}

/**
 * Datetime Input Field
 *
 * @access    public
 * @param    mixed
 * @param    string
 * @param    string
 * @return    string
 */
if ( ! function_exists('form_datetime'))
{
    function form_datetime($data = '', $value = '', $extra = '')
    {
        $defaults = array('type' => 'datetime', 'name' => (( ! is_array($data)) ? $data : ''), 'value' => $value);

        return "<input "._parse_form_attributes($data, $defaults).$extra." />";
    }
}

/**
 * Datetime Local Input Field
 *
 * @access    public
 * @param    mixed
 * @param    string
 * @param    string
 * @return    string
 */
if ( ! function_exists('form_datetime_local'))
{
    function form_datetime_local($data = '', $value = '', $extra = '')
    {
        $defaults = array('type' => 'datetime-local', 'name' => (( ! is_array($data)) ? $data : ''), 'value' => $value);

        return "<input "._parse_form_attributes($data, $defaults).$extra." />";
    }
}

/**
 * Email Input Field
 *
 * @access    public
 * @param    mixed
 * @param    string
 * @param    string
 * @return    string
 */
if ( ! function_exists('form_email'))
{
    function form_email($data = '', $value = '', $extra = '')
    {
        $defaults = array('type' => 'email', 'name' => (( ! is_array($data)) ? $data : ''), 'value' => $value);

        return "<input "._parse_form_attributes($data, $defaults).$extra." />";
    }
}

/**
 * Month Input Field
 *
 * @access    public
 * @param    mixed
 * @param    string
 * @param    string
 * @return    string
 */
if ( ! function_exists('form_month'))
{
    function form_month($data = '', $value = '', $extra = '')
    {
        $defaults = array('type' => 'month', 'name' => (( ! is_array($data)) ? $data : ''), 'value' => $value);

        return "<input "._parse_form_attributes($data, $defaults).$extra." />";
    }
}

/**
 * Number Input Field
 *
 * @access    public
 * @param    mixed
 * @param    string
 * @param    string
 * @return    string
 */
if ( ! function_exists('form_number'))
{
    function form_number($data = '', $value = '', $extra = '')
    {
        $defaults = array('type' => 'number', 'name' => (( ! is_array($data)) ? $data : ''), 'value' => $value);

        return "<input "._parse_form_attributes($data, $defaults).$extra." />";
    }
}

/**
 * Range Input Field
 *
 * @access    public
 * @param    mixed
 * @param    string
 * @param    string
 * @return    string
 */
if ( ! function_exists('form_range'))
{
    function form_range($data = '', $value = '', $extra = '')
    {
        $defaults = array('type' => 'range', 'name' => (( ! is_array($data)) ? $data : ''), 'value' => $value);

        return "<input "._parse_form_attributes($data, $defaults).$extra." />";
    }
}

/**
 * Search Input Field
 *
 * @access    public
 * @param    mixed
 * @param    string
 * @param    string
 * @return    string
 */
if ( ! function_exists('form_search'))
{
    function form_search($data = '', $value = '', $extra = '')
    {
        $defaults = array('type' => 'search', 'name' => (( ! is_array($data)) ? $data : ''), 'value' => $value);

        return "<input "._parse_form_attributes($data, $defaults).$extra." />";
    }
}

/**
 * Tel Input Field
 *
 * @access    public
 * @param    mixed
 * @param    string
 * @param    string
 * @return    string
 */
if ( ! function_exists('form_tel'))
{
    function form_tel($data = '', $value = '', $extra = '')
    {
        $defaults = array('type' => 'tel', 'name' => (( ! is_array($data)) ? $data : ''), 'value' => $value);

        return "<input "._parse_form_attributes($data, $defaults).$extra." />";
    }
}

/**
 * Time Input Field
 *
 * @access    public
 * @param    mixed
 * @param    string
 * @param    string
 * @return    string
 */
if ( ! function_exists('form_time'))
{
    function form_time($data = '', $value = '', $extra = '')
    {
        $defaults = array('type' => 'time', 'name' => (( ! is_array($data)) ? $data : ''), 'value' => $value);

        return "<input "._parse_form_attributes($data, $defaults).$extra." />";
    }
}

/**
 * URL Input Field
 *
 * @access    public
 * @param    mixed
 * @param    string
 * @param    string
 * @return    string
 */
if ( ! function_exists('form_url'))
{
    function form_url($data = '', $value = '', $extra = '')
    {
        $defaults = array('type' => 'url', 'name' => (( ! is_array($data)) ? $data : ''), 'value' => $value);

        return "<input "._parse_form_attributes($data, $defaults).$extra." />";
    }
}

/**
 * Week Input Field
 *
 * @access    public
 * @param    mixed
 * @param    string
 * @param    string
 * @return    string
 */
if ( ! function_exists('form_week'))
{
    function form_week($data = '', $value = '', $extra = '')
    {
        $defaults = array('type' => 'week', 'name' => (( ! is_array($data)) ? $data : ''), 'value' => $value);

        return "<input "._parse_form_attributes($data, $defaults).$extra." />";
    }
}