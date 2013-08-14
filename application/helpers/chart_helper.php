<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Chart Helper
 */
class Chart_helper
{
    /**
     * Constructor
     */
    public function __construct() { }

    /**
     * Init
     * @return NULL
     */
    public static function init()
    { ?>
        <script type="text/javascript">
        var charts = new Array();
        </script>
        <?php
    }

    /**
     * Build Chart Javwascript
     * @param  string $id      Unique ID
     * @param  string $type    Type of Chart (Bar, Line, etc)
     * @param  array $data     Chart Data
     * @param  array $options  Chart Options
     * @return NULL
     */
    public static function buildChart($id, $type, $data, $options = array())
    { ?>
        <canvas id="<?php echo $id; ?>" width="600" height="400"></canvas>
        <script type="text/javascript">
        var chartObject = {
            id : '<?php echo $id; ?>',
            type : '<?php echo $type; ?>',
            data : <?php echo self::buildData($data, $type); ?>,
            options : <?php echo self::buildOptions($options); ?>
        };

        charts['<?php echo $id; ?>'] = chartObject;
        </script>
        <?php
    }

    /**
     * Generated formatted JS data depending upon chart type
     * @param  array $data   Chart Data
     * @param  string $type  Type of Chart (Bar, Line, etc)
     * @return string        Formatted data
     */
    public static function buildData($data, $type)
    {
        switch ($type) {
            case 'polar':
            case 'pie':
            case 'doughnut':
                return self::buildSingleData($data);
        }

        return self::buildMultiData($data);
    }

    /**
     * Build Data for Polar, Pie or Doughnut Charts
     * @param  array $data   Chart Data
     * @return string        Formatted data
     */
    public static function buildSingleData($data)
    {
        $dataset = array();

        foreach ($data['datasets'][0]['dataset'] as $index => $value) {
            $colour = self::fetchColour($index);

            $dataset[] = "{
                value : {$value},
                color: '{$colour['strokeColor']}'
            }";
        }

        return "[
            " . implode(', ', $dataset) . "
        ]";
    }

     /**
     * Build Data for Line, Bar or Radar Charts
     * @param  array $data   Chart Data
     * @return string        Formatted data
     */
    public static function buildMultiData($data)
    {
        return "{
                labels : [" . self::buildLabels($data['labels']) . "],
                datasets : [
                    " . self::buildDatasets($data['datasets']) . "
                ]
            }";
    }

    /**
     * Build Labels
     * @param  array $labels  Labels
     * @return string        Formatted data
     */
    public static function buildLabels($labels)
    {
        if (!$labels) {
            return '';
        }

        $dataset = array();
        foreach ($labels as $label) {
            $processedLabels[] = htmlentities($label);
        }

        return '"' . implode('", "', $processedLabels) . '"';
    }

    /**
     * Build Formatted Datasets
     * @param  array $datasets  Datasets
     * @return string           Formatted data
     */
    public static function buildDatasets($datasets)
    {
        if (!$datasets) {
            return '';
        }

        $processedDatasets = array();
        foreach ($datasets as $index => $dataset) {
            $processedDatasets[] = self::buildDataset($dataset, $index);
        }

        return implode(",\r\n", $processedDatasets);
    }

    /**
     * Build Dataset
     * @param  array $dataset  Dataset
     * @param  int $index      Index
     * @return string          Formatted data
     */
    public static function buildDataset($dataset, $index)
    {
        $colour = self::fetchColour($index);

        return "{
            legend : '" . htmlentities(isset($dataset['legend']) ? $dataset['legend'] : '') . "',
            fillColor : '{$colour['fillColor']}',
            strokeColor : '{$colour['strokeColor']}',
            pointColor : '{$colour['pointColor']}',
            pointStrokeColor : '{$colour['pointStrokeColor']}',
            data : [" . implode(",", $dataset['dataset']) . "]
        }";
    }

    /**
     * Build Chart Options
     * @param  array $options  Chart Options
     * @return string          Formatted data
     */
    public static function buildOptions($options)
    {
        $data = array();

        foreach ($options as $key => $value) {

            if (!is_numeric($value) && !in_array($value, array('null', 'true', 'false'))) {
                $value = "'" . htmlentities($value) . "'";
            }

            $data[] = "{$key} : {$value}";
        }

        return "{
            " . implode(",\r\n", $data) . "
        }";
    }

    /**
     * Return a specified colour data
     * @param  int $index    The ID of the Colour
     * @return array         Specified Colour Details
     */
    public static function fetchColour($index)
    {
        $colours = array(
            array(
                'fillColor'        => "rgba(151,187,205,0.5)",
                'strokeColor'      => "rgba(151,187,205,1)",
                'pointColor'       => "rgba(151,187,205,1)",
                'pointStrokeColor' => "#fff",
            ),
            array(
                'fillColor'        => "rgba(220,220,220,0.5)",
                'strokeColor'      => "rgba(220,220,220,1)",
                'pointColor'       => "rgba(220,220,220,1)",
                'pointStrokeColor' => "#fff",
            ),
            array(
                'fillColor'        => "rgba(215,44,40,0.5)",
                'strokeColor'      => "rgba(215,44,40,1)",
                'pointColor'       => "rgba(215,44,40,1)",
                'pointStrokeColor' => "#fff",
            ),
            array(
                'fillColor'        => "rgba(100,230,100,0.5)",
                'strokeColor'      => "rgba(100,230,100,1)",
                'pointColor'       => "rgba(100,230,100,1)",
                'pointStrokeColor' => "#fff",
            ),
            array(
                'fillColor'        => "rgba(239,190,0,0.5)",
                'strokeColor'      => "rgba(239,190,0,1)",
                'pointColor'       => "rgba(239,190,0,1)",
                'pointStrokeColor' => "#fff",
            ),
            array(
                'fillColor'        => "rgba(205,151,187,0.5)",
                'strokeColor'      => "rgba(205,151,187,1)",
                'pointColor'       => "rgba(205,151,187,1)",
                'pointStrokeColor' => "#fff",
            ),
            array(
                'fillColor'        => "rgba(239,87,0,0.5)",
                'strokeColor'      => "rgba(239,87,0,1)",
                'pointColor'       => "rgba(239,87,0,1)",
                'pointStrokeColor' => "#fff",
            ),
            array(
                'fillColor'        => "rgba(93,92,203,0.5)",
                'strokeColor'      => "rgba(93,92,203,1)",
                'pointColor'       => "rgba(93,92,203,1)",
                'pointStrokeColor' => "#fff",
            ),
            array(
                'fillColor'        => "rgba(40,40,40,0.5)",
                'strokeColor'      => "rgba(40,40,40,1)",
                'pointColor'       => "rgba(40,40,40,1)",
                'pointStrokeColor' => "#fff",
            ),
            array(
                'fillColor'        => "rgba(50,115,61,0.5)",
                'strokeColor'      => "rgba(50,115,61,1)",
                'pointColor'       => "rgba(50,115,61,1)",
                'pointStrokeColor' => "#fff",
            ),
            array(
                'fillColor'        => "rgba(125,125,125,0.5)",
                'strokeColor'      => "rgba(125,125,125,1)",
                'pointColor'       => "rgba(125,125,125,1)",
                'pointStrokeColor' => "#fff",
            ),
        );

        return $colours[$index % count($colours)];
    }

    /**
     * Build Dataset from array of Labels/Value
     * @param  array $dataset  Dataset
     * @param  int $index      Index
     * @return string          Formatted data
     */
    public static function buildDatasetsFromLabelValueArray($data)
    {
        $labels = array();
        $dataset = array();

        $maxValue = 0;
        foreach ($data as $row) {
            $labels[]  = $row['label'];
            $dataset[] = $row['value'];

            if ($row['value'] > $maxValue) {
                $maxValue = $row['value'];
            }
        }

        return array(
            'labels' => $labels,
            'datasets' => array(array(
                'legend' => 'Thomas is great',
                'dataset' => $dataset),
            ),
            'maxValue' => $maxValue,
        );
    }

    /**
     * Return number of Scale Steps to include on Chart, based on Max Value
     * @param  int $maxValue      Highest value in data
     * @return int                Number of steps to display on graph
     */
    public static function scaleSteps($maxValue)
    {
        return ($maxValue / self::scaleWidth($maxValue));
    }

    /**
     * Return number of Scale Steps to include on Chart, based on Max Value
     * @param  int $maxValue      Highest value in data
     * @return int                Number of steps to display on graph
     */
    public static function scaleWidth($maxValue)
    {
        switch (true) {
            case $maxValue >= 200 :
                return 20;
                break;
            case $maxValue >= 50 :
                return 10;
                break;
            case $maxValue >= 30 :
                return 6;
                break;
            case $maxValue >= 10 :
                return 5;
                break;
        }

        return 1;
    }
}
