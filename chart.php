<?php
require_once('classes/DBConnection.php');
$database = new DBConnection();
include_once('classes/component.php');
$components = component::fetchAllComponents();
foreach($components as $component) {
echo "<div class='col-12' id='chart" . $component['component_id'] . "'>";
$component = new component($component['component_id']);
        ?>
    <!--<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>-->
    <div class="card">
        <div class="card-header">
            <?php echo $component->getName(); ?>
        </div>
        <div class="card-block" id="chart<?php echo $component->getComponentId(); ?>">
            <script type="text/javascript">
                google.charts.load('current', {'packages':['corechart']});
                google.charts.setOnLoadCallback(drawChart);

                function drawChart() {
                    var data = google.visualization.arrayToDataTable([
                        ['Tijdstip', 'CPU LOAD', 'SPACE USED'],
                        <?php
                        $log = $component->fetchComponentLogs($component->getComponentId());
                        $time = new DateTime();
                        $time = $time->modify("-3 minutes");
                        for($i = 0; $i < 18; $i++) {
                            $time_index = $time->modify("+10 seconds");
                            $log = $component->fetchComponentLog($component->getComponentId(), $time_index);
                            if(isset($log)) {
                                $cpu_load = $log['cpu_load'] / 100;
                                $space_used = ($log['storage_used'] / $component->getMaxStorage());
                            } else {
                                $cpu_load = 0;
                                $space_used = 0;
                            }
                            $timestamp = $time->format('H:i:s');
                            echo "['$timestamp', $cpu_load, $space_used],";
                        }
                        ?>
                    ]);

                    var options = {
                        title: 'Component: <?php echo $component->getName(); ?>',
                        curveType: 'function',
                        legend: { position: 'bottom' },
                        vAxis: {
                            format:'#%',
                            viewWindowMode:'explicit',
                            viewWindow: {
                                max:1,
                                min:0
                            }},
                    };

                    var chart = new google.visualization.LineChart(document.getElementById('<?php echo $component->getName(); ?>'));
                    chart.draw(data, options);
                }
            </script>
            <div id="<?php echo $component->getName(); ?>" style="width: 100%; height: 500px"></div>
        </div>
        <div class="card-footer text-muted">
            Header
        </div>
    </div>
<?php

    echo "</div>";
}
?>