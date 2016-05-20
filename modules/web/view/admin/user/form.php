<?php $this->piece('common/head.php', array('menu'=>$menu, 'selected_menu'=>'/admin/user/index'));?>
<div class="col-lg-10">
    <ol class="breadcrumb">
        <li><a href="/admin/user/index">用户管理</a></li>
        <li class="active"><span class="active">添加</span></li>
    </ol>
    <form class="form-horizontal user-form" method="post">
        <div class="form-group">
            <label class="control-label col-lg-2 col-lg-offset-2" for="phone"><strong>手机:</strong></label>
            <div class="col-lg-5">
                <input class="form-control" autocomplete="off" type="text" name="phone"/>
            </div>
        </div>
        <div  class="form-group">
            <label class="control-label col-lg-2 col-lg-offset-2" for="password"><strong>密码:</strong></label>
            <div class="col-lg-5">
                <input class="form-control"  type="password" name="password"/>
            </div>
        </div>
        <div  class="form-group">
            <label class="control-label col-lg-2 col-lg-offset-2" for="password_confirm"><strong>确认密码:</strong></label>
            <div class="col-lg-5">
                <input class="form-control"  type="password" name="password_confirm"/>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-lg-2 col-lg-offset-2" for="code"><strong>角色:</strong></label>
            <div class="col-lg-5">

                <select class="form-control" name="role">
                    <?php foreach($roles as $key => $role):?>
                        <option value="<?php echo $key;?>"><?php echo $role;?></option>
                    <?php endforeach;?>
                </select>

            </div>
        </div>
        <div class="form-group">
            <div class="text-center">
                <button type="button" class="btn btn-primary submit">添加</button>
            </div>
        </div>

    </form>
</div>
<?php $this->piece('common/foot.php');?>
