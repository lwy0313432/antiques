<?php /* Smarty version Smarty-3.1.13, created on 2016-01-26 12:39:07
         compiled from "D:\code\cfca_php\views\index\login.tpl" */ ?>
<?php /*%%SmartyHeaderCode:104435683b1ab97e3f7-76146567%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '7f91de41952051131f10d63b8c86391fd03df35b' => 
    array (
      0 => 'D:\\code\\cfca_php\\views\\index\\login.tpl',
      1 => 1453362860,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '104435683b1ab97e3f7-76146567',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_5683b1ab986908_65523977',
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5683b1ab986908_65523977')) {function content_5683b1ab986908_65523977($_smarty_tpl) {?><!DOCTYPE html>
<html lang="ch-ZN">
<head>
    <meta charset="utf-8" />
    <title>登录</title>

    <meta name="description" content="User login page" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <!-- basic styles -->

    <link href="/assets/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="/assets/css/font-awesome.min.css" />

    <!--[if IE 7]>
    <link rel="stylesheet" href="/assets/css/font-awesome-ie7.min.css" />
    <![endif]-->

    <!-- page specific plugin styles -->

    <!-- fonts -->

    <link rel="stylesheet" href="/assets/css/ace-fonts.css" />

    <!-- ace styles -->

    <link rel="stylesheet" href="/assets/css/ace.min.css" />
    <link rel="stylesheet" href="/assets/css/ace-rtl.min.css" />

    <!--[if lte IE 8]>
    <link rel="stylesheet" href="/assets/css/ace-ie.min.css" />
    <![endif]-->

    <!-- inline styles related to this page -->
    <style type="text/css">
        body{font-family: '微软雅黑','宋体'}
    </style>
    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->

    <!--[if lt IE 9]>
    <script src="/assets/js/html5shiv.js"></script>
    <script src="/assets/js/respond.min.js"></script>
    <![endif]-->
</head>
<body class="login-layout">
<div class="main-container">
    <div class="main-content">
        <div class="row">
            <div class="col-sm-10 col-sm-offset-1">
                <div class="login-container">
                    
                    <div class="space-6"></div>
                    <div class="position-relative">
                        <div id="login-box" class="login-box visible widget-box no-border">
                            <div class="widget-body">
                                <div class="widget-main">
                                    <h4 class="header blue lighter bigger">
                                        <i class="icon-coffee green"></i>
                                        平台登录
                                    </h4>

                                    <div class="space-6"></div>

                                        <fieldset>
                                        	<label class="block clearfix">
														<span class="block input-icon input-icon-right">
															<select id="usertype" class="form-control">
															<option value='super'>--选择角色--</option>
															<option value='borrower'>借款人</option>
															<option value='corporation'>企业借款人</option>
															<option value='creditor'>债权人</option>
															<option value='guarantee'>担保公司</option>
															</select>
															<i class="icon-user"></i>
														</span>
                                            </label>
                                            
                                            <label class="block clearfix">
														<span class="block input-icon input-icon-right">
															<input id="username" type="text" class="form-control" placeholder="用户名" />
															<i class="icon-user"></i>
														</span>
                                            </label>

                                            <label class="block clearfix">
														<span class="block input-icon input-icon-right">
															<input id="password" type="password" class="form-control" placeholder="密码" />
															<i class="icon-lock"></i>
														</span>
                                            </label>
                                            <label class="block clearfix">
														<span class="block input-icon input-icon-right">
															<input id="vcode" type="text" class="form-control" placeholder="验证码" />
															<i class="icon-barcode"></i>

														</span>
                                                <img id="btn-vcode" src="/vcode"  style="width:100%;cursor: pointer" alt="看不清？点我刷新" title="看不清？点我刷新"/>
                                            </label>

                                            <div class="space"></div>
                                            <div class="err_msg" id="err_msg"></div>
                                            <div class="clearfix">
                                                <button id="btn-login" type="button" class="width-35 pull-right btn btn-sm btn-primary" >
                                                    <i class="icon-key"></i>
                                                    登录
                                                </button>
                                            </div>
                                            <div class="space-4"></div>
                                        </fieldset>
                                </div><!-- /widget-main -->


                            </div><!-- /widget-body -->
                        </div><!-- /login-box -->
                    </div><!-- /position-relative -->
                </div>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div>
</div><!-- /.main-container -->
<!-- basic scripts -->
<!--[if !IE]> -->
<script type="text/javascript">
    window.jQuery || document.write("<script src='/assets/js/jquery-2.0.3.min.js'>"+"<"+"/script>");
</script>

<!-- <![endif]-->

<!--[if IE]>
<script type="text/javascript">
    window.jQuery || document.write("<script src='/assets/js/jquery-1.10.2.min.js'>"+"<"+"/script>");
</script>
<![endif]-->

<script type="text/javascript">
    if("ontouchend" in document) document.write("<script src='/assets/js/jquery.mobile.custom.min.js'>"+"<"+"/script>");
</script>
<script src="app/common.js"></script>
<!-- inline scripts related to this page -->

<script type="text/javascript">
    $(document).keydown(function (event) {
            if (event.keyCode == 13) {
                $("#btn-login").trigger('click');
            }
           
     });
    function login() {
        window.location.href='/';
    }
    function refVcode()
    {
        var date=new Date();
        var rd=date.getTime();
        $("#btn-vcode").attr("src",'/vcode?rd='+rd);

    }
    $("#btn-vcode").on('click',function(){
        refVcode();
    });

    $("#btn-login").on('click',function(){
    	var usertype=$("#usertype").val();
        var username=$("#username").val();
        var password=$("#password").val();
        var vcode=$("#vcode").val();
        if(!YD.validator.IsNotEmpty(usertype))
        {
            $("#err_msg").html("选择您的角色");
            return;
        }
        
        if(!YD.validator.IsNotEmpty(username))
        {
            $("#err_msg").html("用户名不能为空");
            return;
        }
        if(!YD.validator.IsNotEmpty(password))
        {
            $("#err_msg").html("密码不能为空");
            return;
        }
        if(!YD.validator.IsNotEmpty(vcode))
        {
            $("#err_msg").html("验证码不能为空");
            return;
        }
        YD.ajax.dologin(usertype,username,password,vcode,{
            success:function(res){
                if(res.err_code=='0')
                {
                    var path=window.location.search;
                    if(path!="")
                    {
                        var index=path.indexOf('?path=');
                        if(index==0)
                        {
                            path=path.substr(6,path.length);
                            window.location.href='/#!/'+path;
                        }
                    }
                    
                    else
                    window.location.href="/";
                }
                else
                {
                    refVcode();
                    $("#err_msg").html(res.err_msg);
                }
            }
        })
    });
</script>
</body>
</html>
<?php }} ?>