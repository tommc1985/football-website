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
    <script type="text/javascript">
      var _gaq = _gaq || [];
      _gaq.push(['_setAccount', '<?php echo Configuration::get("google_analytics_tracking_id"); ?>']);
      _gaq.push(['_trackPageview']);

      (function() {
        var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
        ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
        var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
      })();

    </script>
</body>
</html>