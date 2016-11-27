<?php
/**
 * Yindou Framework
 *
 * stop & go to url
 *
 * @version $Id: func.dobreak.php 138 2015-06-16 07:24:21Z wuwenjia $
 **/

function dobreak($url=null)
{
    header('location:'.(null===$url ? $_SERVER['HTTP_REFERER'] : $url));
	die;
}
