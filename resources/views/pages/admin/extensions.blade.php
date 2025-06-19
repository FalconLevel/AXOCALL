@include('partials.admin.header')       
<div class="container-fluid mt-3">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Contacts</h4>
                    <div class="table-responsive">
                        <table class="table table-hover verticle-middle">
                            <thead>
                                <tr>
                                    <th scope="col">Task</th>
                                    <th scope="col">Progress</th>
                                    <th scope="col">Deadline</th>
                                    <th scope="col">Label</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Air Conditioner</td>
                                    <td>
                                        <div class="progress" style="height: 10px">
                                            <div class="progress-bar gradient-1" style="width: 70%;" role="progressbar"><span class="sr-only">70% Complete</span>
                                            </div>
                                        </div> 
                                    </td>
                                    <td>Apr 20,2018</td>
                                    <td><span class="label gradient-1 btn-rounded">70%</span>
                                    </td>
                                    <td><span><a href="#" data-toggle="tooltip" data-placement="top" title="Edit"><i class="fa fa-pencil color-muted m-r-5"></i> </a><a href="#" data-toggle="tooltip" data-placement="top" title="Close"><i class="fa fa-close color-danger"></i></a></span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Textiles</td>
                                    <td>
                                        <div class="progress" style="height: 10px">
                                            <div class="progress-bar gradient-2" style="width: 70%;" role="progressbar"><span class="sr-only">70% Complete</span>
                                            </div>
                                        </div> 
                                    </td>
                                    <td>May 27,2018</td>
                                    <td><span class="label gradient-2 btn-rounded">70%</span>
                                    </td>
                                    <td><span><a href="#" data-toggle="tooltip" data-placement="top" title="Edit"><i class="fa fa-pencil color-muted m-r-5"></i> </a><a href="#" data-toggle="tooltip" data-placement="top" title="Close"><i class="fa fa-close color-danger"></i></a></span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Milk Powder</td>
                                    <td>
                                        <div class="progress" style="height: 10px">
                                            <div class="progress-bar gradient-3" style="width: 70%;" role="progressbar"><span class="sr-only">70% Complete</span>
                                            </div>
                                        </div> 
                                    </td>
                                    <td>May 18,2018</td>
                                    <td><span class="label gradient-3 btn-rounded">70%</span>
                                    </td>
                                    <td><span><a href="#" data-toggle="tooltip" data-placement="top" title="Edit"><i class="fa fa-pencil color-muted m-r-5"></i> </a><a href="#" data-toggle="tooltip" data-placement="top" title="Close"><i class="fa fa-close color-danger"></i></a></span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Vehicles</td>
                                    <td>
                                        <div class="progress" style="height: 10px">
                                            <div class="progress-bar gradient-4" style="width: 70%;" role="progressbar"><span class="sr-only">70% Complete</span>
                                            </div>
                                        </div> 
                                    </td>
                                    <td>Mar 27,2018</td>
                                    <td><span class="label gradient-4 btn-rounded">70%</span>
                                    </td>
                                    <td><span><a href="#" data-toggle="tooltip" data-placement="top" title="Edit"><i class="fa fa-pencil color-muted m-r-5"></i> </a><a href="#" data-toggle="tooltip" data-placement="top" title="Close"><i class="fa fa-close color-danger"></i></a></span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Boats</td>
                                    <td>
                                        <div class="progress" style="height: 10px">
                                            <div class="progress-bar gradient-9" style="width: 70%;" role="progressbar"><span class="sr-only">70% Complete</span>
                                            </div>
                                        </div> 
                                    </td>
                                    <td>Jun 28,2018</td>
                                    <td><span class="label gradient-9 btn-rounded">70%</span>
                                    </td>
                                    <td><span><a href="#" data-toggle="tooltip" data-placement="top" title="Edit"><i class="fa fa-pencil color-muted m-r-5"></i> </a><a href="#" data-toggle="tooltip" data-placement="top" title="Close"><i class="fa fa-close color-danger"></i></a></span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Boats</td>
                                    <td>
                                        <div class="progress" style="height: 10px">
                                            <div class="progress-bar gradient-2" style="width: 70%;" role="progressbar"><span class="sr-only">70% Complete</span>
                                            </div>
                                        </div> 
                                    </td>
                                    <td>Aug 20,2018</td>
                                    <td><span class="label gradient-2 btn-rounded">70%</span>
                                    </td>
                                    <td><span><a href="#" data-toggle="tooltip" data-placement="top" title="Edit">
                                        <i class="fa fa-pencil text-success m-r-5"></i> </a>
                                        <a href="#" data-toggle="tooltip" data-placement="top" title="Close">
                                        <i class="fa fa-trash text-danger"></i></a></span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@include('partials.admin.footer')