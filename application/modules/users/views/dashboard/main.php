<div class="innerLR">

  <div class="row-fluid" style="margin: 5px 0px;">
    <div class="filter-bar">
      <form id="filter-data">
        <div>
          <label>Date Range : </label>
        </div>
        <div>
          <label>From </label>
          <div class="input-append">
            <input type="text" name="date_from" id="date_from" class="datepicker" style="width: 100px" value="<?php echo $date_from; ?>" required="">
            <span class="add-on glyphicons calendar"><i></i></span>
          </div>
        </div>
        <div>
          <label>To </label>
          <div class="input-append">
            <input type="text" name="date_to" id="date_to" class="datepicker" style="width: 100px" value="<?php echo $date_to; ?>" required="">
            <span class="add-on glyphicons calendar"><i></i></span>
          </div>
        </div>
        <div>
          <label>
            <button type="submit" class="btn btn-small">Generate</button>
          </label>
        </div>
        <div class="clearfix"></div>
      </form>
    </div>

    <div class="row-fluid">
      <div class="span6">
        <div class="widget">
          <div class="widget-head">
            <h4 class="heading glyphicons cardio"><i></i> Users Attempt Monitoring</h4>
          </div>
          <div class="widget-body">
            <div id="chartUsersAttemptMonitoring" style="height:400px"></div>
          </div>
        </div>
      </div>
      <div class="span6">
        <div class="widget">
          <div class="widget-head">
            <h4 class="heading glyphicons cardio"><i></i> Users Active Monitoring</h4>
          </div>
          <div class="widget-body">
            <div id="chartUsersActiveMonitoring" style="height:400px"></div>
          </div>
        </div>
      </div>
    </div>

    <div class="row-fluid">
      <div class="span6">
        <div class="widget">
          <div class="widget-head">
            <h4 class="heading glyphicons cardio"><i></i> Active (Logged In) User Monitoring by Region</h4>
          </div>
          <div class="widget-body">
            <div id="chartActiveUserMonitoringByRegion" style="height:400px"></div>
          </div>
        </div>
      </div>
      <div class="span6">
        <div class="widget">
          <div class="widget-head">
            <h4 class="heading glyphicons cardio"><i></i> Users Active (Logged In) by Group</h4>
          </div>
          <div class="widget-body">
            <div id="chartUsersActiveByGroupDivision" style="height:400px"></div>
          </div>
        </div>
      </div>
    </div>

    <!--  <div class="row-fluid">
        <div class="span6">
          <div class="widget">
            <div class="widget-head">
              <h4 class="heading glyphicons cardio"><i></i> Procentage Users Active (Logged In) by Group</h4>
            </div>
            <div class="widget-body">
              <div id="chartProcentageUsersActiveByGroupDivision" style="height:400px"></div>
            </div>
          </div>
        </div>
        <div class="span6"></div>
      </div>-->

  </div>
</div>
<script src="<?php echo base_url('assets/js/hehehe.js') ?>"></script>
<!--
<?php //if ($this->session->flashdata('trial_message') != '') { ?>
  <script>
    //coolNotification('Welcome to SiMS live version. Data that you have updated here will be recorded and no longer been ignored as previously in trial version. Enjoy work with SiMS!', 'error');
  </script>
  <?php
  //$this->session->set_flashdata('trial_message', '');
//}
?>
-->

<?php // if ($this->session->userdata['user_role'][0]['user_group_id'] == 1) { ?>
<script>
  var charts = {
    init: function() {
      this.chartActiveUserMonitoringByRegion.init();
      this.chartUsersAttemptMonitoring.init();
      this.chartUsersActiveMonitoring.init();
      this.chartUsersActiveByGroupDivision.init();
//      this.chartProcentageUsersActiveByGroupDivision.init();
    },
    url: '<?php echo base_url('dashboard_admin') ?>',
    chartActiveUserMonitoringByRegion: {
      init: function() {
        $.ajax({
          url: this.url,
          data: {report_type: 'load_data_chart__active_user_monitoring_by_region', date_from: $('#date_from').val(), date_to: $('#date_to').val(), data_label: 'numeric'},
          dataType: 'json',
          beforeSend: function() {
            $('#chartActiveUserMonitoringByRegion').block({
              message: '<img src="<?php echo base_url('assets/images/ajax-loader.gif') ?>" />',
              css: {
                width: '10%',
                padding: '10px'
              }
            });
          },
          success: function(json) {
            if (typeof json.series != 'undefined') {
              for (var i = 0; i < json.series.length; i++) {
                charts.chartActiveUserMonitoringByRegion.options.xAxis.categories = json.categories;
                charts.chartActiveUserMonitoringByRegion.options.series[i] = json.series[i];
              }
            }
            new Highcharts.Chart(charts.chartActiveUserMonitoringByRegion.options);
          },
          complete: function() {
            $('#chartActiveUserMonitoringByRegion').unblock();
          }
        });
      },
      options: {
        chart: {
          renderTo: 'chartActiveUserMonitoringByRegion',
          type: 'column'
        },
        title: {
          text: null
        },
        subtitle: {
          text: null
        },
        xAxis: {
          categories: [], //['HQ', 'Jabodetabek', 'West Java', 'Central Java', 'East Java'],
          title: {
            text: null
          },
          labels: {
            //rotation: -45,
            style: {
              color: '#c00505'
            }
          }
        },
        yAxis: {allowDecimals: false,
          allowDecimals: false,
                  min: 0,
          title: {
            text: null,
            align: 'middle', //'high'
          },
          labels: {
            overflow: 'justify'
          }
        },
        tooltip: {
          //valueSuffix: ' millions'
        },
        plotOptions: {
          column: {
            dataLabels: {
              enabled: true
            }
          }
        },
        credits: {
          enabled: false
        },
        series: [],
        lang: {
          noData: 'Unavailable data for this month.'
        },
        noData: {
          style: {
            fontWeight: 'normal',
            fontSize: '12px'
          }
        }
      },
    },
    chartUsersAttemptMonitoring: {
      init: function() {
        $.ajax({
          url: this.url,
          data: {report_type: 'load_data_chart__users_attempt_monitoring', date_from: $('#date_from').val(), date_to: $('#date_to').val(), data_label: 'numeric'},
          dataType: 'json',
          beforeSend: function() {
            $('#chartUsersAttemptMonitoring').block({
              message: '<img src="<?php echo base_url('assets/images/ajax-loader.gif') ?>" />',
              css: {
                width: '10%',
                padding: '10px'
              }
            });
          },
          success: function(json) {
            if (typeof json.series != 'undefined') {
              charts.chartUsersAttemptMonitoring.options.xAxis.categories = [];
              charts.chartUsersAttemptMonitoring.options.series = [];
              for (var i = 0; i < json.series.length; i++) {
                charts.chartUsersAttemptMonitoring.options.xAxis.categories = json.categories;
                charts.chartUsersAttemptMonitoring.options.series[i] = json.series[i];
              }
              new Highcharts.Chart(charts.chartUsersAttemptMonitoring.options);
            }
          },
          complete: function() {
            $('#chartUsersAttemptMonitoring').unblock();
          }
        });
      },
      options: {
        chart: {
          renderTo: 'chartUsersAttemptMonitoring',
          type: 'line'
        },
        title: {
          text: null
        },
        subtitle: {
          text: null
        },
        xAxis: {
          categories: [],
          title: {
            text: null
          },
          labels: {
            //rotation: -45,
            style: {
              color: '#c00505'
            }
          }
        },
        yAxis: {allowDecimals: false,
          min: 0,
          title: {
            text: null,
            align: 'middle', //'high'
          },
          labels: {
            overflow: 'justify'
          }
        },
        tooltip: {
          //valueSuffix: ' millions'
        },
        plotOptions: {
          line: {
            dataLabels: {
              enabled: true
            }
          }
        },
        credits: {
          enabled: false
        },
        series: [],
        lang: {
          noData: 'Unavailable data for this month.'
        },
        noData: {
          style: {
            fontWeight: 'normal',
            fontSize: '12px'
          }
        }
      },
    },
    chartUsersActiveMonitoring: {
      init: function() {
        $.ajax({
          url: this.url,
          data: {report_type: 'load_data_chart__users_active_monitoring', date_from: $('#date_from').val(), date_to: $('#date_to').val(), data_label: 'numeric'},
          dataType: 'json',
          beforeSend: function() {
            $('#chartUsersActiveMonitoring').block({
              message: '<img src="<?php echo base_url('assets/images/ajax-loader.gif') ?>" />',
              css: {
                width: '10%',
                padding: '10px'
              }
            });
          },
          success: function(json) {
            if (typeof json.series != 'undefined') {
              for (var i = 0; i < json.series.length; i++) {
                charts.chartUsersActiveMonitoring.options.xAxis.categories = json.categories;
                charts.chartUsersActiveMonitoring.options.series[i] = json.series[i];
              }
            }
            new Highcharts.Chart(charts.chartUsersActiveMonitoring.options);
          },
          complete: function() {
            $('#chartUsersActiveMonitoring').unblock();
          }
        });
      },
      options: {
        chart: {
          renderTo: 'chartUsersActiveMonitoring',
          type: 'line'
        },
        title: {
          text: null
        },
        subtitle: {
          text: null
        },
        xAxis: {
          categories: [],
          title: {
            text: null
          },
          labels: {
            //rotation: -45,
            style: {
              color: '#c00505'
            }
          }
        },
        yAxis: {allowDecimals: false,
          min: 0,
          title: {
            text: null,
            align: 'middle', //'high'
          },
          labels: {
            overflow: 'justify'
          }
        },
        tooltip: {
          //valueSuffix: ' millions'
        },
        plotOptions: {
          line: {
            dataLabels: {
              enabled: true
            }
          }
        },
        credits: {
          enabled: false
        },
        series: [],
        lang: {
          noData: 'Unavailable data for this month.'
        },
        noData: {
          style: {
            fontWeight: 'normal',
            fontSize: '12px'
          }
        }
      },
    },
    chartUsersActiveByGroupDivision: {
      init: function() {
        $.ajax({
          url: this.url,
          data: {report_type: 'load_data_chart__users_active_by_group_division', date_from: $('#date_from').val(), date_to: $('#date_to').val(), data_label: 'numeric'},
          dataType: 'json',
          beforeSend: function() {
            $('#chartUsersActiveByGroupDivision').block({
              message: '<img src="<?php echo base_url('assets/images/ajax-loader.gif') ?>" />',
              css: {
                width: '10%',
                padding: '10px'
              }
            });
          },
          success: function(json) {
            if (typeof json.series != 'undefined') {
              for (var i = 0; i < json.series.length; i++) {
                charts.chartUsersActiveByGroupDivision.options.xAxis.categories = json.categories;
                charts.chartUsersActiveByGroupDivision.options.series[i] = json.series[i];
              }
            }
            new Highcharts.Chart(charts.chartUsersActiveByGroupDivision.options);
          },
          complete: function() {
            $('#chartUsersActiveByGroupDivision').unblock();
          }
        });
      },
      options: {
        chart: {
          renderTo: 'chartUsersActiveByGroupDivision',
          type: 'column'
        },
        title: {
          text: null
        },
        subtitle: {
          text: null
        },
        xAxis: {
          categories: [],
          title: {
            text: null
          },
          labels: {
            //rotation: -45,
            style: {
              color: '#c00505'
            }
          }
        },
        yAxis: {allowDecimals: false,
          min: 0,
          title: {
            text: null,
            align: 'middle', //'high'
          },
          labels: {
            overflow: 'justify'
          }
        },
        tooltip: {
          //valueSuffix: ' millions'
        },
        plotOptions: {
          column: {
            dataLabels: {
              enabled: true
            }
          }
        },
        credits: {
          enabled: false
        },
        series: [],
        lang: {
          noData: 'Unavailable data for this month.'
        },
        noData: {
          style: {
            fontWeight: 'normal',
            fontSize: '12px'
          }
        }
      }
    },
    chartProcentageUsersActiveByGroupDivision: {
      init: function() {
        $.ajax({
          url: this.url,
          data: {report_type: 'load_data_chart__procentage_users_active_by_group_division', date_from: $('#date_from').val(), date_to: $('#date_to').val(), data_label: 'numeric'},
          dataType: 'json',
          beforeSend: function() {
            $('#chartProcentageUsersActiveByGroupDivision').block({
              message: '<img src="<?php echo base_url('assets/images/ajax-loader.gif') ?>" />',
              css: {
                width: '10%',
                padding: '10px'
              }
            });
          },
          success: function(json) {
            if (typeof json.series != 'undefined') {
              for (var i = 0; i < json.series.length; i++) {
                charts.chartProcentageUsersActiveByGroupDivision.options.xAxis.categories = json.categories;
                charts.chartProcentageUsersActiveByGroupDivision.options.series[i] = json.series[i];
              }
            }
            new Highcharts.Chart(charts.chartProcentageUsersActiveByGroupDivision.options);
          },
          complete: function() {
            $('#chartProcentageUsersActiveByGroupDivision').unblock();
          }
        });
      },
      options: {
        chart: {
          renderTo: 'chartProcentageUsersActiveByGroupDivision',
          type: 'column'
        },
        title: {
          text: null
        },
        subtitle: {
          text: null
        },
        xAxis: {
          categories: [],
          title: {
            text: null
          },
          labels: {
            //rotation: -45,
            style: {
              color: '#c00505'
            }
          }
        },
        yAxis: {allowDecimals: false,
          min: 0,
          title: {
            text: null,
            align: 'middle', //'high'
          },
          labels: {
            overflow: 'justify'
          }
        },
        tooltip: {
          //valueSuffix: ' millions'
        },
        plotOptions: {
          column: {
            dataLabels: {
              enabled: true
            }
          }
        },
        credits: {
          enabled: false
        },
        series: [],
        lang: {
          noData: 'Unavailable data for this month.'
        },
        noData: {
          style: {
            fontWeight: 'normal',
            fontSize: '12px'
          }
        }
      }
    }
  };

  $(function() {
    charts.init();

    $("#date_from").datepicker({
      dateFormat: "dd M yy",
      changeMonth: true,
      numberOfMonths: 1,
      changeYear: true,
      maxDate: '0d',
      onSelect: function(selectedDate, el) {
        $("#date_to").datepicker("option", "minDate", selectedDate);
        if ($('#date_to').val() == '') {
          $("#date_to").datepicker("setDate", "0d");
          $("#date_from").datepicker("option", "maxDate", '0d');
        }
      }
    });

    $('#date_to').datepicker({
      dateFormat: "dd M yy",
      changeMonth: true,
      numberOfMonths: 1,
      changeYear: true,
      maxDate: '0d',
      onSelect: function(selectedDate, el) {
        $("#date_from").datepicker("option", "maxDate", selectedDate);
      }
    });

    $('#filter-data').submit(function(e) {
      e.preventDefault();
      charts.chartUsersAttemptMonitoring.init();
      charts.chartUsersActiveMonitoring.init();
      charts.chartActiveUserMonitoringByRegion.init();
      charts.chartUsersActiveByGroupDivision.init();
    });

  });
</script>

<?php
// } ?>