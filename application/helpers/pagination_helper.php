<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Pagination Helper
 */
class Pagination_helper
{
    /**
     * Constructor
     */
    public function __construct() { }

    /**
     * Return a Pagination's Common Config settings
     * @param  mixed $milestone    Milestone Object/Array
     * @return string              The Milestone Text
     */
    public static function settings()
    {
        $ci =& get_instance();
        $ci->load->config('pagination', true);

        return array(
            'full_tag_open'   => $ci->config->item('full_tag_open', 'pagination'),
            'full_tag_close'  => $ci->config->item('full_tag_close', 'pagination'),
            'first_tag_open'  => $ci->config->item('first_tag_open', 'pagination'),
            'first_tag_close' => $ci->config->item('first_tag_close', 'pagination'),
            'last_tag_open'   => $ci->config->item('last_tag_open', 'pagination'),
            'last_tag_close'  => $ci->config->item('last_tag_close', 'pagination'),
            'next_tag_open'   => $ci->config->item('next_tag_open', 'pagination'),
            'next_tag_close'  => $ci->config->item('next_tag_close', 'pagination'),
            'prev_tag_open'   => $ci->config->item('prev_tag_open', 'pagination'),
            'prev_tag_close'  => $ci->config->item('prev_tag_close', 'pagination'),
            'cur_tag_open'    => $ci->config->item('cur_tag_open', 'pagination'),
            'cur_tag_close'   => $ci->config->item('cur_tag_close', 'pagination'),
            'num_tag_open'    => $ci->config->item('num_tag_open', 'pagination'),
            'num_tag_close'   => $ci->config->item('num_tag_close', 'pagination'),
        );
    }
}