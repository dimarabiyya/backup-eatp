<div class="innerLR">
  <!--<div class="row-fluid" style="margin: 5px 0px;">-->
  <!--<div class="filter-bar">-->
  <form id="filter-data" method="post" action="<?php echo base_url('export/user_history'); ?>">
    <div class="row">
      <div class="control-group span2">
        <label class="control-label">Report type</label>
        <div class="input">
          <?php
          $opt = array(
              '' => ' - Choose - ',
              'load_data_chart__users_attempt_monitoring' => 'User Attempt',
              'load_data_chart__users_active_monitoring' => 'User Active',
              'load_data_chart__active_user_monitoring_by_region' => 'User Active By Region',
              'load_data_chart__users_active_by_group_division' => 'User Active By Division',
              'load_data_chart__users_current_login' => 'User Login',
          );
          echo form_dropdown('report_type', $opt, '', 'id="report_type"');
          ?>
        </div>
      </div>
      <div class="control-group span2">
        <label class="control-label">Region</label>
        <div class="input">
          <?php
          echo form_dropdown('region', $region_opt, '', 'id="region" style="width:100%"');
          ?>
        </div>
      </div>
      <div class="control-group span2">
        <label class="control-label">Main Division</label>
        <div class="input">
          <?php
          echo form_dropdown('main_division', $main_division_opt, '', 'id="main_division" style="width:100%"');
          ?>
        </div>
      </div>
      <div class="control-group span2">
        <label class="control-label">Start Date</label>
        <div class="input-append">
          <input type="text" name="date_from2" id="date_from2" class="datepicker" style="width: 80%" value="" required="">
          <span class="add-on glyphicons calendar"><i></i></span>
        </div>
      </div>
      <div class="control-group span2">
        <label class="control-label">End Date</label>
        <div class="input-append">
          <input type="text" name="date_to2" id="date_to2" class="datepicker" style="width: 80%" value="" required="">
          <span class="add-on glyphicons calendar"><i></i></span>
        </div>
      </div>
      <div class="control-group span2">
        <label class="control-label">Data Label</label>
        <div class="input">
          <?php
          $opt = array(
              'numeric' => 'Numeric',
              'percent' => 'Procentage',
          );
          echo form_dropdown('data_label', $opt, '', 'id="data_label" style="width:100%"');
          ?>
        </div>	
      </div>
<!--      <div class="control-group span2">
        <label class="control-label">Search</label>
        <div class="input">
          <input name="q" id="q" type="text" placeholder="Type text to search .." autofocus="" style="width: 90%">
          <button  onclick="searchData()" class="btn" type="button"><i class="icon-cari" style="background-position: -48px 0;"></i></button>
        </div>	
      </div>-->
      <div class="control-group span2">
        <label class="control-label">&nbsp;</label>
        <div class="input">
          <button type="button" id="filter_btn" class="btn">Generate</button>
          <button type="submit" class="btn" id="export-btn">Export</button>
        </div>	
      </div>
    </div>
    <!--        <div>
              <label>Region : </label>
            </div>
            <div>
    <?php
    echo form_dropdown('region_id', $region_opt, '', 'id="region_id"');
    ?>
            </div>
            <div>
              <label>Report Name : </label>
            </div>
            <div>
    <?php
    $opt = array(
        '' => ' - Pilih - ',
        'load_data_chart__users_attempt_monitoring' => 'User Attempt',
        'load_data_chart__users_active_monitoring' => 'User Active',
        'load_data_chart__active_user_monitoring_by_region' => 'User Active By Region',
        'load_data_chart__users_active_by_group_division' => 'User Active By Division',
        'load_data_chart__users_current_login' => 'User Login',
    );
    echo form_dropdown('report_type', $opt, '', 'id="report_type"');
    ?>
            </div>
            <div>
              <label>From </label>
              <div class="input-append">
                <input type="text" name="date_from2" id="date_from2" class="datepicker" style="width: 100px" value="<?php echo $date_from; ?>">
                <span class="add-on glyphicons calendar"><i></i></span>
              </div>
            </div>
            <div>
              <label>To </label>
              <div class="input-append">
                <input type="text" name="date_to2" id="date_to2" class="datepicker" style="width: 100px" value="<?php echo $date_to; ?>">
                <span class="add-on glyphicons calendar"><i></i></span>
              </div>
            </div>
            <div>
              <label>Data label</label>
              <div>
    <?php
    $opt = array(
        'numeric' => 'Numeric',
        'percent' => 'Procentage',
    );
    echo form_dropdown('data_label', $opt, '', 'id="data_label"');
    ?>
              </div>
            </div>-->
    <!--    <div>
          <button type="button" id="filter_btn" class="btn btn-small">Generate</button>
          <button type="submit" class="btn btn-small" id="export-btn">Export</button>
        </div>-->
    <div class="clearfix"></div>
  </form>
  <!--</div>-->
  <!--</div>-->

  <div class="row-fluid">
    <div class="span12">
      <div class="widget">
        <div class="widget-head">
          <h4 class="heading glyphicons cardio"><i></i> Graph</h4>
        </div>
        <div class="widget-body">
          <div id="chart-detail" style="height:400px;"></div>
        </div>
      </div>
    </div>
  </div>
  <div class="row-fluid">
    <div class="span12">
      <legend>Summary</legend>
      <div style="min-width: 100%; overflow: auto;">
        <table id="summary" class="table table-bordered table-condensed table-hover" style="min-width: 100%; max-width: 1000%; width: max-content;">
          <thead style="background-color:#f8f8f8"></thead>
          <tbody></tbody>
          <tfoot style="background-color:#f8f8f8"></tfoot>
        </table>
      </div>
    </div>
  </div>
  <div class="row-fluid" id="detail" style="display: none">
    <div class="span12">
      <legend>Detail</legend>
      <div style="min-width: 100%; overflow: auto;">
        <table id="summary2" class="table table-bordered table-condensed table-hover">
          <thead style="background-color:#f8f8f8"></thead>
          <tbody></tbody>
          <tfoot style="background-color:#f8f8f8"></tfoot>
        </table>
      </div>
    </div>
  </div>

</div>

<script src="<?php echo base_url('assets/js/hehehe.js') ?>"></script>

<script>
  var charts2 = {
//    init: function() {
//      this.chartActiveUserMonitoringByRegion.init();
//      this.chartUsersAttemptMonitoring.init();
//      this.chartUsersActiveMonitoring.init();
//      this.chartUsersActiveByGroupDivision.init();
//      this.chartProcentageUsersActiveByGroupDivision.init();
//    },
    url: '<?php echo base_url('dashboard_admin') ?>',
    chartActiveUserMonitoringByRegion: {
      init: function() {
        $.ajax({
          url: this.url,
          data: {report_type: $('#report_type').val(), date_from: $('#date_from2').val(), date_to: $('#date_to2').val(), data_label: $('#data_label').val()},
          dataType: 'json',
          beforeSend: function() {
            $('#chart-detail').block({
              message: '<img src="<?php echo base_url('assets/images/ajax-loader.gif') ?>" />',
              css: {
                width: '10%',
                padding: '10px'
              }
            });
			
			$('#filter_btn, #export-btn').prop('disabled', true);
          },
          success: function(json) {
            if (typeof json.series != 'undefined') {
              charts2.chartActiveUserMonitoringByRegion.options.xAxis.categories = [];
              charts2.chartActiveUserMonitoringByRegion.options.series = [];
              for (var i = 0; i < json.series.length; i++) {
                charts2.chartActiveUserMonitoringByRegion.options.xAxis.categories = json.categories;
                charts2.chartActiveUserMonitoringByRegion.options.series[i] = json.series[i];
              }
              setSummaryActiveRegion(json);
            }
            new Highcharts.Chart(charts2.chartActiveUserMonitoringByRegion.options);
			
			$('#filter_btn, #export-btn').prop('disabled', false);
          },
          complete: function() {
            $('#chart-detail').unblock();
          }
        });
      },
      options: {
        chart: {
          renderTo: 'chart-detail',
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
              enabled: true,
              formatter: function() {
                if ($('#data_label').val() == 'numeric') {
                  return Highcharts.numberFormat(this.y, 0);
                } else {
                  if (this.series.name != 'User Registered') {
                    return Highcharts.numberFormat(this.y, 0) + '%';
                  } else {
                    return Highcharts.numberFormat(this.y, 0);
                  }
                }
              }
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
          data: {
            report_type: $('#report_type').val(), 
            date_from: $('#date_from2').val(), 
            date_to: $('#date_to2').val(), 
            data_label: $('#data_label').val(),
            region : $('#region').val(),
            main_division: $('#main_division').val()
          },
          dataType: 'json',
          beforeSend: function() {
            $('#chart-detail').block({
              message: '<img src="<?php echo base_url('assets/images/ajax-loader.gif') ?>" />',
              css: {
                width: '10%',
                padding: '10px'
              }
            });
			
			$('#filter_btn, #export-btn').prop('disabled', true);
          },
          success: function(json) {
            if (typeof json.series != 'undefined') {
              charts2.chartUsersAttemptMonitoring.options.xAxis.categories = [];
              charts2.chartUsersAttemptMonitoring.options.series = [];
              for (var i = 0; i < json.series.length; i++) {
                charts2.chartUsersAttemptMonitoring.options.xAxis.categories = json.categories;
                charts2.chartUsersAttemptMonitoring.options.series[i] = json.series[i];
              }
              new Highcharts.Chart(charts2.chartUsersAttemptMonitoring.options);
              setSummaryAttempt(json);
            }
			
			$('#filter_btn, #export-btn').prop('disabled', false);
          },
          complete: function() {
            $('#chart-detail').unblock();
          }
        });
      },
      options: {
        chart: {
          renderTo: 'chart-detail',
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
              enabled: true,
              formatter: function() {
                if ($('#data_label').val() == 'numeric') {
                  return Highcharts.numberFormat(this.y, 0);
                } else {
                  if (this.series.name == 'Attempt') {
                    return Highcharts.numberFormat(this.y, 0) + '%';
                  } else {
                    return Highcharts.numberFormat(this.y, 0);
                  }
                }
              }
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
          data: {report_type: $('#report_type').val(), date_from: $('#date_from2').val(), date_to: $('#date_to2').val(), data_label: $('#data_label').val()},
          dataType: 'json',
          beforeSend: function() {
            $('#chart-detail').block({
              message: '<img src="<?php echo base_url('assets/images/ajax-loader.gif') ?>" />',
              css: {
                width: '10%',
                padding: '10px'
              }
            });
			
			$('#filter_btn, #export-btn').prop('disabled', true);
          },
          success: function(json) {
            if (typeof json.series != 'undefined') {
              charts2.chartUsersActiveMonitoring.options.xAxis.categories = [];
              charts2.chartUsersActiveMonitoring.options.series = [];
              for (var i = 0; i < json.series.length; i++) {
                charts2.chartUsersActiveMonitoring.options.xAxis.categories = json.categories;
                charts2.chartUsersActiveMonitoring.options.series[i] = json.series[i];
              }
              setSummaryActive(json);
            }
            new Highcharts.Chart(charts2.chartUsersActiveMonitoring.options);
			
			$('#filter_btn, #export-btn').prop('disabled', false);
          },
          complete: function() {
            $('#chart-detail').unblock();
          }
        });
      },
      options: {
        chart: {
          renderTo: 'chart-detail',
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
              enabled: true,
              formatter: function() {
                if ($('#data_label').val() == 'numeric') {
                  return Highcharts.numberFormat(this.y, 0);
                } else {
                  if (this.series.name == 'Active (Logged In) Users') {
                    return Highcharts.numberFormat(this.y, 0) + '%';
                  } else {
                    return Highcharts.numberFormat(this.y, 0);
                  }
                }
              }
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
          data: {report_type: $('#report_type').val(), date_from: $('#date_from2').val(), date_to: $('#date_to2').val(), data_label: $('#data_label').val()},
          dataType: 'json',
          beforeSend: function() {
            $('#chart-detail').block({
              message: '<img src="<?php echo base_url('assets/images/ajax-loader.gif') ?>" />',
              css: {
                width: '10%',
                padding: '10px'
              }
            });
			
			$('#filter_btn, #export-btn').prop('disabled', true);
          },
          success: function(json) {
            if (typeof json.series != 'undefined') {
              charts2.chartUsersActiveByGroupDivision.options.xAxis.categories = [];
              charts2.chartUsersActiveByGroupDivision.options.series = [];
              for (var i = 0; i < json.series.length; i++) {
                charts2.chartUsersActiveByGroupDivision.options.xAxis.categories = json.categories;
                charts2.chartUsersActiveByGroupDivision.options.series[i] = json.series[i];
              }
              setSummaryDivision(json);
			  
			  $('#filter_btn, #export-btn').prop('disabled', false);
            }
            new Highcharts.Chart(charts2.chartUsersActiveByGroupDivision.options);
          },
          complete: function() {
            $('#chart-detail').unblock();
          }
        });
      },
      options: {
        chart: {
          renderTo: 'chart-detail',
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
              enabled: true,
              formatter: function() {
                if ($('#data_label').val() == 'numeric') {
                  return Highcharts.numberFormat(this.y, 0);
                } else {
                  if (this.series.name != 'User Registered') {
                    return Highcharts.numberFormat(this.y, 0) + '%';
                  } else {
                    return Highcharts.numberFormat(this.y, 0);
                  }
                }
              }
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
          data: {report_type: $('#report_type').val(), date_from: $('#date_from2').val(), date_to: $('#date_to2').val(), data_label: $('#data_label').val()},
          dataType: 'json',
          beforeSend: function() {
            $('#chart-detail').block({
              message: '<img src="<?php echo base_url('assets/images/ajax-loader.gif') ?>" />',
              css: {
                width: '10%',
                padding: '10px'
              }
            });
			
			$('#filter_btn, #export-btn').prop('disabled', true);
          },
          success: function(json) {
            if (typeof json.series != 'undefined') {
              for (var i = 0; i < json.series.length; i++) {
                charts2.chartProcentageUsersActiveByGroupDivision.options.xAxis.categories = json.categories;
                charts2.chartProcentageUsersActiveByGroupDivision.options.series[i] = json.series[i];
              }
            }
            new Highcharts.Chart(charts2.chartProcentageUsersActiveByGroupDivision.options);
			
			$('#filter_btn, #export-btn').prop('disabled', false);
          },
          complete: function() {
            $('#chart-detail').unblock();
          }
        });
      },
      options: {
        chart: {
          renderTo: 'chart-detail',
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
    chartUsersCurrentLogin: {
      init: function() {
        $.ajax({
          url: this.url,
          data: {report_type: $('#report_type').val(), date_to: '<?php echo date('d M Y'); ?>'},
          dataType: 'json',
          beforeSend: function() {
            $('#chart-detail').block({
              message: '<img src="<?php echo base_url('assets/images/ajax-loader.gif') ?>" />',
              css: {
                width: '10%',
                padding: '10px'
              }
            });
			
			$('#filter_btn, #export-btn').prop('disabled', true);
          },
          success: function(json) {
            if (typeof json.series != 'undefined') {
              charts2.chartUsersActiveByGroupDivision.options.xAxis.categories = [];
              charts2.chartUsersActiveByGroupDivision.options.series = [];
              for (var i = 0; i < json.series.length; i++) {
                charts2.chartUsersActiveByGroupDivision.options.xAxis.categories = json.categories;
                charts2.chartUsersActiveByGroupDivision.options.series[i] = json.series[i];
              }
              setSummaryUserLogin(json);
              setDetailSummaryUserLogin(json);
            }
            new Highcharts.Chart(charts2.chartUsersActiveByGroupDivision.options);
			
			$('#filter_btn, #export-btn').prop('disabled', false);
          },
          complete: function() {
            $('#chart-detail').unblock();
          }
        });
      },
      options: {
        chart: {
          renderTo: 'chart-detail',
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
              enabled: true,
              formatter: function() {
                if ($('#data_label').val() == 'numeric') {
                  return Highcharts.numberFormat(this.y, 0);
                } else {
                  if (this.series.name != 'User Registered') {
                    return Highcharts.numberFormat(this.y, 0) + '%';
                  } else {
                    return Highcharts.numberFormat(this.y, 0);
                  }
                }
              }
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
  };

  $(function() {
    $("#date_from2").datepicker({
      dateFormat: "dd M yy",
      changeMonth: true,
      numberOfMonths: 1,
      changeYear: true,
      maxDate: '0d',
      onSelect: function(selectedDate, el) {
        $("#date_to2").datepicker("option", "minDate", selectedDate);
        if ($('#date_to2').val() == '') {
          $("#date_to2").datepicker("setDate", "0d");
          $("#date_from2").datepicker("option", "maxDate", '0d');
        }
      }
    });

    $('#date_to2').datepicker({
      dateFormat: "dd M yy",
      changeMonth: true,
      numberOfMonths: 1,
      changeYear: true,
      maxDate: '0d',
      onSelect: function(selectedDate, el) {
        $("#date_from2").datepicker("option", "maxDate", selectedDate);
      }
    });

    $('#filter_btn').click(function(e) {
      $('#summary thead').html('');
      $('#summary tbody').html('');
      $('#summary tfoot').html('');
      $('#detail').hide();
      
      var warning='';
      if ($('#report_type').val() == '') {
        warning += '<div>* Report type is required.</div>';
      }
      
      if ($('#date_from2').val() == '') {
        warning += '<div>* Start date is required.</div>';
      }
      
      if ($('#date_to2').val() == '') {
        warning += '<div>* End date is required.</div>';
      }
      
      if (warning != '') {
        coolNotification(warning, 'warning');
        return false;
      }
      
//      charts2.chartDetail.init();
      var reportType = $('#report_type').val();
//      console.log(reportType);
      if (reportType == 'load_data_chart__users_attempt_monitoring') {
        charts2.chartUsersAttemptMonitoring.init();
      } else if (reportType == 'load_data_chart__users_active_monitoring') {
        charts2.chartUsersActiveMonitoring.init();
      } else if (reportType == 'load_data_chart__active_user_monitoring_by_region') {
        charts2.chartActiveUserMonitoringByRegion.init();
      } else if (reportType == 'load_data_chart__users_active_by_group_division') {
        charts2.chartUsersActiveByGroupDivision.init();
      } else if (reportType == 'load_data_chart__users_current_login') {
        charts2.chartUsersCurrentLogin.init();
        $('#detail').show();
      }
    });

    // Report Type
    $('#report_type').change(function() {
      if ($(this).val() == 'load_data_chart__users_current_login') {
        $('#date_from2').attr('disabled', 'disabled');
        $('#date_to2').attr('disabled', 'disabled');
        $('#data_label').attr('disabled', 'disabled');
        $('#export-btn').attr('disabled', 'disabled');
      } else {
        $('#date_from2').removeAttr('disabled');
        $('#date_to2').removeAttr('disabled');
        $('#data_label').removeAttr('disabled');
        $('#export-btn').removeAttr('disabled');
      }
    });
  });

  function setSummaryAttempt(json) {
    var thead = '<tr><th style="width:150px"></th>';
    $.each(json.categories, function(index, el) {
      thead += '<th class="center">' + el + '</th>';
    });
    thead += '</tr>';
    $('#summary thead').html(thead);

    var tbody;
    var attemp = [];
    var threshold = [];
    $.each(json.tdata, function(index, el) {
      tbody += '<tr>';
      tbody += '<td>' + el.name + '</td>';
      $.each(el.data, function(i, e) {
        tbody += '<td class="right">' + e + '</td>';
        if (index == 0) {
          threshold.push(e);
        } else {
          attemp.push(e);
        }
      });
      tbody += '</tr>';
    });
    $('#summary tbody').html(tbody);

    var tfoot = '<tr><th>Occupancy</th>';

    $.each(attemp, function(ii, ee) {
      tfoot += '<th class="right">' + Math.ceil(attemp[ii] / threshold[ii] * 100) + '%</th>';
    });

    tfoot += '</tr>';
    $('#summary tfoot').html(tfoot);
  }

  function setSummaryActive(json) {
    var thead = '<tr><th style="width:150px"></th>';
    $.each(json.categories, function(index, el) {
      thead += '<th class="center">' + el + '</th>';
    });
    thead += '</tr>';
    $('#summary thead').html(thead);

    var tbody;
    var attemp = [];
    var reg = [];
    $.each(json.tdata, function(index, el) {
      tbody += '<tr>';
      tbody += '<td>' + el.name + '</td>';
      $.each(el.data, function(i, e) {
        tbody += '<td class="right">' + e + '</td>';
        if (index == 0) {
          reg.push(e);
        } else {
          attemp.push(e);
        }
      });
      tbody += '</tr>';
    });
    $('#summary tbody').html(tbody);

    var tfoot = '<tr><th>Occupancy</th>';

    $.each(attemp, function(ii, ee) {
      tfoot += '<th class="right">' + Math.ceil(attemp[ii] / reg[ii] * 100) + '%</th>';
    });

    tfoot += '</tr>';
    $('#summary tfoot').html(tfoot);
  }

  function setSummaryActiveRegion(json) {
    if ($('#data_label').val() == 'numeric') {
      var thead = '<tr><th style="width:150px">Region</th>';
      $.each(json.series, function(index, el) {
        thead += '<th class="center">' + el.name + '</th>';
      });
      thead += '</tr>';
      $('#summary thead').html(thead);

      var arrData = [];
      var tbody;
      $.each(json.categories, function(index, el) {
        tbody += '<tr>';
        tbody += '<td>' + el + '</td>';
        var rowData = [];
        $.each(json.series, function(i, e) {
          if (i == 0 || $('#data_label').val() == 'numeric') {
            tbody += '<td class="right">' + e.data[index] + '</td>';
          } else {
            tbody += '<td class="right">' + e.data[index] + '%</td>';
          }
          rowData.push(e.data[index]);
        });
        arrData.push(rowData);
        tbody += '</tr>';
      });
      $('#summary tbody').html(tbody);

      var tfoot = '<tr><th>NATIONAL</th>';
      var rows, cols;
      rows = arrData.length;
      cols = arrData[0].length;
      if (rows < cols) {
        while (rows != cols) {
          arrData.push([]);
          for (var j = 0; j < cols; j++) {
            arrData[rows].push(0);
          }
          rows++;
        }
      }

      var sum = function(arr) {
        return arr.reduce(function(a, b) {
          return a + b;
        }, 0);
      };

// vertical sums
      var tfootData = [];
      arrData.map(function(row, i) {
        if (i < cols) {
          var tmpData = sum(arrData.map(function(row) {
            return row[i];
          }));
          tfootData.push(tmpData);
        }
        ;
      });

      $.each(tfootData, function(i, e) {
        if (i == 0 || $('#data_label').val() == 'numeric') {
          tfoot += '<th class="right">' + e + '</th>';
        } else {
          tfoot += '<th class="right">' + e + '%</th>';
        }
      });

      tfoot += '</tr>';
      $('#summary tfoot').html(tfoot);
    } else {
      $('#summary').html(json.html);
    }
  }

  function setSummaryDivision(json) {
    if ($('#data_label').val() == 'numeric') {
      var thead = '<tr><th style="width:150px">User Group</th>';
      $.each(json.series, function(index, el) {
        thead += '<th class="center">' + el.name + '</th>';
      });
      thead += '</tr>';
      $('#summary thead').html(thead);

      var arrData = [];
      var tbody;
      $.each(json.categories, function(index, el) {
        tbody += '<tr>';
        tbody += '<td>' + el + '</td>';
        var rowData = [];
        $.each(json.series, function(i, e) {
          if (i == 0 || $('#data_label').val() == 'numeric') {
            tbody += '<td class="right">' + e.data[index] + '</td>';
          } else {
            tbody += '<td class="right">' + e.data[index] + '%</td>';
          }
          rowData.push(e.data[index]);
        });
        arrData.push(rowData);
        tbody += '</tr>';
      });
      $('#summary tbody').html(tbody);

      var tfoot = '<tr><th>NATIONAL</th>';
      var rows, cols;
      rows = arrData.length;
      if (typeof arrData[0] != 'undefined') {
        cols = arrData[0].length;
      } else {
        cols = 0;
      }

      if (rows < cols) {
        while (rows != cols) {
          arrData.push([]);
          for (var j = 0; j < cols; j++) {
            arrData[rows].push(0);
          }
          rows++;
        }
      }

      var sum = function(arr) {
        return arr.reduce(function(a, b) {
          return a + b;
        }, 0);
      };

// vertical sums
      var tfootData = [];
      arrData.map(function(row, i) {
        if (i < cols) {
          var tmpData = sum(arrData.map(function(row) {
            return row[i];
          }));
          tfootData.push(tmpData);
        }
        ;
      });

      $.each(tfootData, function(i, e) {
        if (i == 0 || $('#data_label').val() == 'numeric') {
          tfoot += '<th class="right">' + e + '</th>';
        } else {
          tfoot += '<th class="right">' + e + '%</th>';
        }
      });

      tfoot += '</tr>';
      $('#summary tfoot').html(tfoot);
    } else {
      $('#summary').html(json.html);
    }
//    console.log('div');
  }

  function setSummaryUserLogin(json) {
    var thead = '<tr><th style="width:150px">User Division</th>';
    $.each(json.series, function(index, el) {
      thead += '<th class="center">' + el.name + '</th>';
    });
    thead += '</tr>';
    $('#summary thead').html(thead);

    var arrData = [];
    var tbody;
    $.each(json.categories, function(index, el) {
      tbody += '<tr>';
      tbody += '<td>' + el + '</td>';
      var rowData = [];
      $.each(json.series, function(i, e) {
        if (i == 0 || $('#data_label').val() == 'numeric') {
          tbody += '<td class="right">' + e.data[index] + '</td>';
        } else {
          tbody += '<td class="right">' + e.data[index] + '%</td>';
        }
        rowData.push(e.data[index]);
      });
      arrData.push(rowData);
      tbody += '</tr>';
    });
    $('#summary tbody').html(tbody);

    var tfoot = '<tr><th>NATIONAL</th>';
    var rows, cols;
    rows = arrData.length;
    if (typeof arrData[0] != 'undefined') {
      cols = arrData[0].length;
    } else {
      cols = 0;
    }

    if (rows < cols) {
      while (rows != cols) {
        arrData.push([]);
        for (var j = 0; j < cols; j++) {
          arrData[rows].push(0);
        }
        rows++;
      }
    }

    var sum = function(arr) {
      return arr.reduce(function(a, b) {
        return a + b;
      }, 0);
    };

// vertical sums
    var tfootData = [];
    arrData.map(function(row, i) {
      if (i < cols) {
        var tmpData = sum(arrData.map(function(row) {
          return row[i];
        }));
        tfootData.push(tmpData);
      }
      ;
    });

    $.each(tfootData, function(i, e) {
      if (i == 0 || $('#data_label').val() == 'numeric') {
        tfoot += '<th class="right">' + e + '</th>';
      } else {
        tfoot += '<th class="right">' + e + '%</th>';
      }
    });

    tfoot += '</tr>';
    $('#summary tfoot').html(tfoot);
  }

  function setDetailSummaryUserLogin(json) {
    // DETAIL
    var thead = '<tr><th class="center" style="width:5%">No</th>';
    thead += '<th class="center" style="width:30%">User Division</th>';
    thead += '<th class="center" style="width:35%">Username</th>';
    thead += '<th class="center" style="width:30%">Last Login</th>';
    thead += '</tr>';
    $('#summary2 thead').html(thead);

    var tbody;
    $.each(json.user_list, function(index, el) {
      index++;
      tbody += '<tr>';
      tbody += '<td class="center">' + index + '</td>';
      tbody += '<td>' + el.div_name + '</td>';
      tbody += '<td>' + el.user_name + '</td>';
      tbody += '<td>' + el.last_login + '</td>';
      tbody += '</tr>';
    });
    $('#summary2 tbody').html(tbody);
  }

</script>