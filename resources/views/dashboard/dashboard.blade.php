@extends('template.template')
@section('contenido')

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Producciones Actuales</h1>
                </div>
                <div class="col-sm-6">
                    {{-- 
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Inicio</a></li>
                        <li class="breadcrumb-item active">Dashboard</li>
                    </ol>
                    --}}
                    <div class="breadcrumb float-sm-right dropdown">
                        <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                          Camaronera
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                          <a class="dropdown-item" href="#">Action</a>
                          <a class="dropdown-item" href="#">Another action</a>
                          <a class="dropdown-item" href="#">Something else here</a>
                        </div>
                      </div>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-4">
                    <!-- DONUT CHART -->
                    <div class="card card-danger">
                        <div class="card-header">
                            <h3 class="card-title">Metrica 1</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
                                </button>
                                <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-times"></i></button>
                            </div>
                        </div>
                        <div class="card-body">
                            <canvas id="donutChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
                <div class="col-md-4">
                    <!-- PIE CHART -->
                    <div class="card card-danger">
                        <div class="card-header">
                            <h3 class="card-title">Metrica 2</h3>
            
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
                                </button>
                                <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-times"></i></button>
                            </div>
                        </div>
                        <div class="card-body">
                            <canvas id="pieChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
                <div class="col-md-4">
                    <!-- PIE CHART -->
                    <div class="card card-danger">
                        <div class="card-header">
                            <h3 class="card-title">Metrica 3</h3>
            
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
                                </button>
                                <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-times"></i></button>
                            </div>
                        </div>
                        <div class="card-body">
                            <canvas id="pieChart2" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
            </div>
        </div>
    </section>
    
    <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">Items</h3>

              <div class="card-tools">
                <div class="input-group input-group-sm" style="width: 150px;">
                    <div class="dropdown">
                        <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                          Piscina
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                          <a class="dropdown-item" href="#">Action</a>
                          <a class="dropdown-item" href="#">Another action</a>
                          <a class="dropdown-item" href="#">Something else here</a>
                        </div>
                      </div>
                </div>
              </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body table-responsive p-0" style="height: 300px;">
              <table class="table table-head-fixed text-nowrap">
                <thead>
                  <tr>
                    <th>ID</th>
                    <th>Param1</th>
                    <th>Date</th>
                    <th>param2</th>
                    <th>param3</th>
                    <th>param4</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>183</td>
                    <td>pool1</td>
                    <td>11-7-2014</td>
                    <td><span class="tag tag-success">Approved</span></td>
                    <td>Bacon ipsum dolor sit amet salami venison chicken flank fatback doner.</td>
                    <th>param4</th>
                  </tr>
                  <tr>
                    <td>219</td>
                    <td>pool1</td>
                    <td>11-7-2014</td>
                    <td><span class="tag tag-warning">Ended</span></td>
                    <td>Bacon ipsum dolor sit amet salami venison chicken flank fatback doner.</td>
                    <th>param4</th>
                  </tr>
                  <tr>
                    <td>657</td>
                    <td>pool1</td>
                    <td>11-7-2014</td>
                    <td><span class="tag tag-primary">Approved</span></td>
                    <td>Bacon ipsum dolor sit amet salami venison chicken flank fatback doner.</td>
                    <th>param4</th>
                  </tr>
                  <tr>
                    <td>175</td>
                    <td>pool1</td>
                    <td>11-7-2014</td>
                    <td><span class="tag tag-danger">Denied</span></td>
                    <td>Bacon ipsum dolor sit amet salami venison chicken flank fatback doner.</td>
                    <th>param4</th>
                  </tr>
                  <tr>
                    <td>134</td>
                    <td>pool1</td>
                    <td>11-7-2014</td>
                    <td><span class="tag tag-success">Approved</span></td>
                    <td>Bacon ipsum dolor sit amet salami venison chicken flank fatback doner.</td>
                    <th>param4</th>
                  </tr>
                  <tr>
                    <td>494</td>
                    <td>pool1</td>
                    <td>11-7-2014</td>
                    <td><span class="tag tag-warning">Ended</span></td>
                    <td>Bacon ipsum dolor sit amet salami venison chicken flank fatback doner.</td>
                    <th>param4</th>
                  </tr>
                  <tr>
                    <td>832</td>
                    <td>pool1</td>
                    <td>11-7-2014</td>
                    <td><span class="tag tag-primary">Approved</span></td>
                    <td>Bacon ipsum dolor sit amet salami venison chicken flank fatback doner.</td>
                    <th>param4</th>
                  </tr>
                  <tr>
                    <td>982</td>
                    <td>pool1</td>
                    <td>11-7-2014</td>
                    <td><span class="tag tag-danger">Denied</span></td>
                    <td>Bacon ipsum dolor sit amet salami venison chicken flank fatback doner.</td>
                    <th>param4</th>
                  </tr>
                </tbody>
              </table>
            </div>
            <!-- /.card-body -->
          </div>
          <!-- /.card -->
        </div>
      </div>
      <!-- /.row -->

    <script>
        $(function () {
            //-------------
            //- DONUT CHART -
            //-------------
            // Get context with jQuery - using jQuery's .get() method.
            var donutChartCanvas = $('#donutChart').get(0).getContext('2d')
            var donutData        = {
                labels: [
                    'Chrome', 
                    'IE',
                    'FireFox', 
                    'Safari', 
                    'Opera', 
                    'Navigator', 
                ],
                datasets: [
                {
                    data: [700,500,400,600,300,100],
                    backgroundColor : ['#f56954', '#00a65a', '#f39c12', '#00c0ef', '#3c8dbc', '#d2d6de'],
                }
                ]
            }
            var donutOptions     = {
                maintainAspectRatio : false,
                responsive : true,
            }
            //Create pie or douhnut chart
            // You can switch between pie and douhnut using the method below.
            var donutChart = new Chart(donutChartCanvas, {
                type: 'doughnut',
                data: donutData,
                options: donutOptions      
            })
        
            //-------------
            //- PIE CHART -
            //-------------
            // Get context with jQuery - using jQuery's .get() method.
            var pieChartCanvas = $('#pieChart').get(0).getContext('2d')
            var pieData        = donutData;
            var pieOptions     = {
                maintainAspectRatio : false,
                responsive : true,
            }
            //Create pie or douhnut chart
            // You can switch between pie and douhnut using the method below.
            var pieChart = new Chart(pieChartCanvas, {
                type: 'pie',
                data: pieData,
                options: pieOptions      
            })
        
            //-------------
            //- PIE CHART -
            //-------------
            // Get context with jQuery - using jQuery's .get() method.
            var pieChartCanvas2 = $('#pieChart2').get(0).getContext('2d')
            var pieData        = donutData;
            var pieOptions     = {
                maintainAspectRatio : false,
                responsive : true,
            }
            //Create pie or douhnut chart
            // You can switch between pie and douhnut using the method below.
            var pieChart = new Chart(pieChartCanvas2, {
                type: 'pie',
                data: pieData,
                options: pieOptions      
            })
    
        })
    </script>

@endsection