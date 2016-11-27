<?php /* Smarty version Smarty-3.1.13, created on 2015-12-30 18:26:10
         compiled from "D:\code\cfca_php\views\common\error.tpl" */ ?>
<?php /*%%SmartyHeaderCode:1425683b1429e15b7-85036530%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'ba9b08b5ba6d9713ea9fc1a9fcd998c8564d00ce' => 
    array (
      0 => 'D:\\code\\cfca_php\\views\\common\\error.tpl',
      1 => 1433823493,
      2 => 'file',
    ),
    'edf099b71db668033e2bf92efb21c23f07427db1' => 
    array (
      0 => 'D:\\code\\cfca_php\\views\\common\\layout.tpl',
      1 => 1434086576,
      2 => 'file',
    ),
    '4a7adf637033ddd6eb583016b073cd0f4e3b7781' => 
    array (
      0 => 'D:\\code\\cfca_php\\views\\common\\header.tpl',
      1 => 1433390895,
      2 => 'file',
    ),
    '58fbb205cd180432112e3da51ef753aab4c937ae' => 
    array (
      0 => 'D:\\code\\cfca_php\\views\\common\\footer.tpl',
      1 => 1445486661,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1425683b1429e15b7-85036530',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'title' => 0,
    'keywords' => 0,
    'description' => 0,
    'webroot' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_5683b142b0b4d4_19634540',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5683b142b0b4d4_19634540')) {function content_5683b142b0b4d4_19634540($_smarty_tpl) {?><!doctype html>
<html lang="zh-CN">
<head>
    <title><?php echo $_smarty_tpl->tpl_vars['title']->value;?>
</title>
    <meta charset="utf-8">
    <meta name="keywords" content="<?php echo $_smarty_tpl->tpl_vars['keywords']->value;?>
" />
    <meta name="description" content="<?php echo $_smarty_tpl->tpl_vars['description']->value;?>
" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <!--全局CSS文件引用 S-->
    
    <link rel="stylesheet" href="<?php echo $_smarty_tpl->tpl_vars['webroot']->value;?>
style/css/reset.css?v=1"/>
    <link rel="stylesheet" href="<?php echo $_smarty_tpl->tpl_vars['webroot']->value;?>
style/css/main.css?v=1"/>
    <link rel="stylesheet" href="<?php echo $_smarty_tpl->tpl_vars['webroot']->value;?>
style/css/zepto.alert.css?v=1"/>
    
    <!--全局CSS文件引用 E-->
    <!--全局js引用 S-->
    

    <script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['webroot']->value;?>
style/js/zepto.min.js"></script>
    <script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['webroot']->value;?>
style/js/zepto.history.js"></script>
    <script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['webroot']->value;?>
style/js/zepto.alert.js"></script>
    <script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['webroot']->value;?>
style/js/common.js?v=7"></script>
    
    <!--全局js引用 E-->
</head>
<body>
    <!--头部文件引用 S-->
    
<div class="header">
<div class="nav-title">错误</div>
</div>


    <!--头部文件引用 E-->
    <!--内容 S-->
    
<div class="wrap">
	<div>
		<div>
			<h2>ERROE CODE:<?php echo (($tmp = @$_smarty_tpl->tpl_vars['error_code']->value)===null||$tmp==='' ? '未知错误' : $tmp);?>
</h2>
			<div>
				<p><?php echo (($tmp = @$_smarty_tpl->tpl_vars['error_msg']->value)===null||$tmp==='' ? '未知错误' : $tmp);?>
</p>
				<p><a href="<?php echo $_smarty_tpl->tpl_vars['webroot']->value;?>
">回到首页</a></p>
			</div>
		</div>
	</div>
</div>

    <!--内容 E-->
    <!--底部文件引用 S-->
    
        <?php /*  Call merged included template "./footer.tpl" */
$_tpl_stack[] = $_smarty_tpl;
 $_smarty_tpl = $_smarty_tpl->setupInlineSubTemplate('./footer.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0, '1425683b1429e15b7-85036530');
content_5683b142af8a26_58451407($_smarty_tpl);
$_smarty_tpl = array_pop($_tpl_stack); /*  End of included template "./footer.tpl" */?>
    
    <!--底部文件引用 E-->
    <!--本地js文件引用 S-->
    

    
    <!--本地js文件引用 E-->
    <!--全局js代码块 S-->
    

    
    <!--全局js代码块 E-->
    <!--本地js代码块 S-->
    

    
    <!--本地js代码块 E-->
</body>
</html><?php }} ?><?php /* Smarty version Smarty-3.1.13, created on 2015-12-30 18:26:10
         compiled from "D:\code\cfca_php\views\common\header.tpl" */ ?>
<?php if ($_valid && !is_callable('content_5683b142a19b14_69966830')) {function content_5683b142a19b14_69966830($_smarty_tpl) {?><div class="header">
    头部
</div><?php }} ?><?php /* Smarty version Smarty-3.1.13, created on 2015-12-30 18:26:10
         compiled from "D:\code\cfca_php\views\common\footer.tpl" */ ?>
<?php if ($_valid && !is_callable('content_5683b142af8a26_58451407')) {function content_5683b142af8a26_58451407($_smarty_tpl) {?><div class="footer">
    <div class="menu"><a href="/">首页</a><a href="/">帮助中心</a><a href="/">关于我们</a><a href="">电脑版</a></div>
    <div class="copyright">© 2015西施理财 xishilc.com</div>
</div><?php }} ?>