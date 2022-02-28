<div class="col-sm-12">
    <div class="card-box table-responsive">
        <h4 class="header-title m-t-0 m-b-30">أدارة المستخدمين</h4>
        <div id="datatable_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer">
            <div class="row">
                <div class="col-sm-12">
                   
                    <!-- modal end  -->
                    <a class="btn btn-success" data-toggle="modal" href='#modal-id'>أضافة مستخدم جديد</a>
                    <div class="modal fade" id="modal-id">
                        <div id="modal_idd" class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-body">
                                    <form id="insertform" method="post">
                                            <div class="form-group">
                                                <div class="col-md ">
                                                    
                                                    <input      type="text" id="username" name="username"   class="form-control" placeholder="أسم المستخدم" required/>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="col-md ">
                                                    <input type="text" id="password"   name="password" class="form-control" placeholder="كلمة المرور" required />
                                                </div>
                                            </div>
                                            <div class="form-group">

                                            <label for="">أختر الصلاحية</label>

                                            <select name="validity"    id="validity" class="form-control" >
                                                 <option   value="1" required>مدير </option>
                                                <option value="0">مستخدم عادي </option>
                                            </select>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-default" data-dismiss="modal">أغلاق</button>
                                            <button type="button" id="save_add" class="btn btn-info"> حفظ</button>
                                        </div>
                                </div>
                            </div>
                        </div>
                        </form>
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
                    </div>




                    <!-- edit modal  -->


 
                     <div class="modal fade" id="modal-edit-id">
                        <div id="modal_idd" class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-body">
                                    <form id="editform" method="post">
                                            <div class="form-group">
                                                <div class="col-md ">
                                                    
                                                    <input      type="text" id="username_edit" name="username_edit"   class="form-control" placeholder="أسم المستخدم" required/>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="col-md ">
                                                    <input type="text" id="password_edit"   name="password_edit" class="form-control" placeholder="كلمة المرور" required />
                                                </div>
                                            </div>
                                            <div class="form-group">

                                            <label for="">أختر الصلاحية</label>

                                            <select name="validity"    id="validity_edit" class="form-control" >dit
                                                 <option   value="1" required>مدير </option>
                                                <option value="0">مستخدم عادي </option>
                                            </select>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-default" data-dismiss="modal">أغلاق</button>
                                            <button type="button" id="save_edit"  class="btn btn-info"> حفظ</button>
                                        </div>
                                </div>
                            </div>
                        </div>
                        </form>
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
                    </div>

                    <br><br>
                    <table id="userstable" class="table table-striped table-bordered dataTable no-footer" role="grid" aria-describedby="datatable_info">
                        <thead>
                            <td class="sorting_1">أسم المستخدم </td>
                            <td class="">كلمة المرور</td>
                            <td>الصلاحية</td>
                            <td class="">أجراء</td>
                            <td class=""> </td>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="<?php echo   base_url('dashbord_template/serverside_datatable/js/skCrudModalAjax.js')  ; ?>"></script>
 
<script>
    serverSide_datatable("manageUsers/list", "userstable");
    insertData("modal_idd","manageUsers/insert", "save_add", "manageUsers/list", "userstable");
    deleteData("manageUsers/delete", "manageUsers/list", "userstable");
    updateData("modal-edit-id","manageUsers/update", "save_edit", "manageUsers/list", "userstable");
    edit( "manageUsers/edit","modal-edit-id","save_edit");
</script>