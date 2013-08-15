    <div class="row-fluid">
        <div class="span12">
            Page rendered in <strong>{elapsed_time}</strong> seconds
        </div>
    </div>
</div>


    <footer class="footer">
        <div class="container">
            <p>Built by <a href="http://twitter.com/tommc1985" target="_blank">@tommc1985</a>.</p>
            <p>Codebase available to download for free from <a href="https://github.com/tommc1985/football-website" target="_blank">GitHub</a> and licensed under <a href="http://www.apache.org/licenses/LICENSE-2.0" target="_blank">Apache License v2.0</a>.</p>
            <p>Built with a combination of <a href="http://codeigniter.com" target="_blank">CodeIgniter</a>, <a href="http://getbootstrap.com/" target="_blank">Bootstrap 2.3.2</a>, <a href="http://fortawesome.github.io/Font-Awesome/icons" target="_blank">Font Awesome</a> &amp; <a href="http://www.chartjs.org/" target="_blank">ChartJS</a></p>
        </div>
    </footer>

<!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script type="text/javascript">
    var baseURL = '<?php echo site_url(); ?>';

    </script>
    <?php echo Assets::js(); ?>
    <script>
    <?php
    /*
    var chartObject = {
            id : 'position-progression',
            type : 'line',
            data : {
                labels : ["2/9", "9/9", "16/9", "23/9", "30/9", "7/10", "14/10", "21/10", "28/10", "4/11", "11/11", "18/11", "25/11", "2/12", "9/12", "16/12", "23/12", "5/1", "12/1", "19/1", "26/1", "2/2", "9/2", "16/2", "23/2", "2/3", "9/3", "16/3", "23/3", "30/3", "6/4", "13/4", "20/4"],
                datasets : [
                    {
            legend : [""],
            fillColor : 'rgba(151,187,205,0)',
            strokeColor : 'rgba(151,187,205,1)',
            pointColor : 'rgba(151,187,205,1)',
            pointStrokeColor : '#fff',
            data : [1,2,3,4,5,6,7,8,9,10,11,11,10,9,8,7,6,5,4,3,2,1]
        },
        {
            legend : [""],
            fillColor : 'rgba(220,220,220,0)',
            strokeColor : 'rgba(220,220,220,1)',
            pointColor : 'rgba(220,220,220,1)',
            pointStrokeColor : '#fff',
            data : [11,10,9,8,7,6,5,4,3,2,1,1,2,3,4,5,6,7,8,9,10,11]
        }
                ]
            },
            options : {
                scaleOverride: true,
                scaleStartValue : 11,
                scaleSteps : 10,
                scaleStepWidth : -1,
        }
    };
    charts[1] = chartObject;*/ ?>
    </script>
</body>
</html>