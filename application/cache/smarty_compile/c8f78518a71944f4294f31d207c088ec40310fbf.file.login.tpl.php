<?php /* Smarty version Smarty-3.1.13, created on 2016-11-24 15:50:40
         compiled from "D:\src\www\views\index\login.tpl" */ ?>
<?php /*%%SmartyHeaderCode:3867583446ba4df061-65294301%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'c8f78518a71944f4294f31d207c088ec40310fbf' => 
    array (
      0 => 'D:\\src\\www\\views\\index\\login.tpl',
      1 => 1479973839,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '3867583446ba4df061-65294301',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_583446ba4e7537_60301188',
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_583446ba4e7537_60301188')) {function content_583446ba4e7537_60301188($_smarty_tpl) {?><html>
<head></head>
<body>
<form method='POST' action='/actionLogin'>
用户名：<input name='user_name' type='text' />
密码：<input name='password' type='password' />
<input type='submit' value='确定'/>
</form>


</body></html><?php }} ?>