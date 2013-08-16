<?php
require_once('_base_frontend_model.php');

/**
 * Model for Content
 */
class Content_model extends Base_Frontend_Model {

    /**
     * Constructor
     * @return NULL
     */
    public function __construct()
    {
        // Call the Model constructor
        parent::__construct();

        $this->tableName = 'content';
    }

    /**
     * Fetch latest content
     * @param  string $type          Type of Content (page, article or news)
     * @param  int|false $limit      Number of instances to return
     * @return array                 Returned instances of content
     */
    public function fetchLatest($type, $limit = 5)
    {
        return $this->fetchAll(array('type' => $type), $limit, false, $this->getOrderBy(''), $this->getOrderBy('desc'));
    }

    /**
     * Return string of fields to order data by
     * @param  string $orderBy Fields passed
     * @return string          Processed string of fields
     */
    public function getOrderBy($orderBy)
    {
        return 'publish_date';
    }

    /**
     * Return "asc" or "desc" depending on value passed
     * @param  string $order Either "asc" or "desc"
     * @return string        Either "asc" or "desc"
     */
    public function getOrder($order)
    {
        if ($order == 'asc') {
            return 'asc';
        }

        return 'desc';
    }

}