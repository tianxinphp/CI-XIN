<?php
/**
 * Created by PhpStorm.
 * User: tianxin
 * Date: 2018/3/13
 * Time: 16:35
 */
//本文件是系统初始化文件
//没有从入口脚本访本文件就不能访问
defined('COREPATH') OR exit('No direct script access allowed');

/**
 *本系统使用CI框架版本
 */
	const CI_VERSION = '3.1.7';

/*
 * ------------------------------------------------------
 *  加载本应用配置文件,首先加载常量配置文件,先加载各自环境特殊的,后加载整体的
 * ------------------------------------------------------
 */
	if (file_exists(FRONTENDPATH.'config/'.ENVIRONMENT.'/constants.php'))
	{
		require_once(FRONTENDPATH.'config/'.ENVIRONMENT.'/constants.php');
	}

	if (file_exists(FRONTENDPATH.'config/constants.php'))
	{
		require_once(FRONTENDPATH.'config/constants.php');
	}

/*
 * ------------------------------------------------------
 *  加载全局公共方法
 * ------------------------------------------------------
 */
	require_once(COREPATH.'core/Common.php');


/*
 * ------------------------------------------------------
 * 对全局变量进行安全处理
 * ------------------------------------------------------
 */

if ( ! is_php('5.4'))//兼容5.4版本一下独有的安全机制
{
	ini_set('magic_quotes_runtime', 0);

	if ((bool) ini_get('register_globals'))//如果register_globals开了下来,php在接收传过来的参数时不会塞到_GET或其他全局变量里,会直接生成一个全局变量
	{
		$_protected = array(
			'_SERVER',
			'_GET',
			'_POST',
			'_FILES',
			'_REQUEST',
			'_SESSION',
			'_ENV',
			'_COOKIE',
			'GLOBALS',
			'HTTP_RAW_POST_DATA',
			'system_path',//这个及下面的两个已经在前面定义过了,所以不能吓传
			'application_folder',
			'view_folder',
			'_protected',
			'_registered'
		);

		$_registered = ini_get('variables_order');//php超全局变量解析顺序
		//按照EGPCS顺序解析全局变量,后面如有新值覆盖前面
		foreach (array('E' => '_ENV', 'G' => '_GET', 'P' => '_POST', 'C' => '_COOKIE', 'S' => '_SERVER') as $key => $superglobal)
		{
			if (strpos($_registered, $key) === FALSE)//如果没有下一个
			{
				continue;
			}

			foreach (array_keys($$superglobal) as $var)//将获得循环
			{
				if (isset($GLOBALS[$var]) && ! in_array($var, $_protected, TRUE))//在全局变量中也有这个参数,直接设置为null
				{
					$GLOBALS[$var] = NULL;
				}
			}
		}
	}
}


/*
 * ------------------------------------------------------
 *  定义一个自定义错误处理程序，以便我们可以记录PHP错误
 *  自定义错误处理函数
 *  自定义异常处理函数
 *  自定义脚本结束回调函数
 * ------------------------------------------------------
 */
	set_error_handler('_error_handler');
	set_exception_handler('_exception_handler');
	register_shutdown_function('_shutdown_handler');

/*
 * ------------------------------------------------------
 *  设置拓展前缀
 * ------------------------------------------------------
 *  如果在index.php中设置了关于此项目的拓展前缀配置,直接覆盖
 */
	if ( ! empty($assign_to_config['subclass_prefix']))
	{
		get_config(array('subclass_prefix' => $assign_to_config['subclass_prefix']));
	}

/*
 * ------------------------------------------------------
 *  composer 自动加载
 * ------------------------------------------------------
 */
	if ($composer_autoload = config_item('composer_autoload'))
	{
		if ($composer_autoload === TRUE)
		{
			file_exists(FRONTENDPATH.'vendor/autoload.php')
				? require_once(FRONTENDPATH.'vendor/autoload.php')
				: log_message('error', '$config[\'composer_autoload\'] is set to TRUE but '.FRONTENDPATH.'vendor/autoload.php was not found.');
		}
		elseif (file_exists($composer_autoload))
		{
			require_once($composer_autoload);
		}
		else
		{
			log_message('error', 'Could not find the specified $config[\'composer_autoload\'] path: '.$composer_autoload);
		}
	}

/*
 * ------------------------------------------------------
 *  脚本记时与内存使用运行
 * ------------------------------------------------------
 */
	$BM =& load_class('Benchmark', 'core');
	$BM->mark('total_execution_time_start');
	$BM->mark('loading_time:_base_classes_start');

/*
 * ------------------------------------------------------
 *  示例化钩子类
 * ------------------------------------------------------
 */
	$EXT =& load_class('Hooks', 'core');

/*
 * ------------------------------------------------------
 *  在整个应用程序启动之前的钩子
 * ------------------------------------------------------
 */
	$EXT->call_hook('pre_system');

/*
 * ------------------------------------------------------
 *  实例化配置类
 * ------------------------------------------------------
 *
 * 示例化配置类,如果有$assign_to_config数组将数组塞进去
 *
 */
	$CFG =& load_class('Config', 'core');

	if (isset($assign_to_config) && is_array($assign_to_config))
	{
		foreach ($assign_to_config as $key => $value)
		{
			$CFG->set_item($key, $value);
		}
	}

/*
 * ------------------------------------------------------
 * 设定默认字符集为此项目配置文件中
 * ------------------------------------------------------
 * 设定默认字符集
 * 如果加载了mbstring和iconv拓展,设定常量
 */
	$charset = strtoupper(config_item('charset'));
	ini_set('default_charset', $charset);

	if (extension_loaded('mbstring'))
	{
		define('MB_ENABLED', TRUE);
        //mbstring.internal_encoding参数从5.6才有
		@ini_set('mbstring.internal_encoding', $charset);
		//去除无效字符
		mb_substitute_character('none');
	}
	else
	{
		define('MB_ENABLED', FALSE);
	}

    //php 不鼓励ICONV_ENABLED预定义常量
	if (extension_loaded('iconv'))
	{
		define('ICONV_ENABLED', TRUE);
		//php5.6 有的参数iconv.internal_encoding
		@ini_set('iconv.internal_encoding', $charset);
	}
	else
	{
		define('ICONV_ENABLED', FALSE);
	}

	if (is_php('5.6'))
	{
		ini_set('php.internal_encoding', $charset);
	}

/*
 * ------------------------------------------------------
 *  加载兼容性函数
 * ------------------------------------------------------
 */

	require_once(COREPATH.'core/compat/mbstring.php');
	require_once(COREPATH.'core/compat/hash.php');
	require_once(COREPATH.'core/compat/password.php');
	require_once(COREPATH.'core/compat/standard.php');

/*
 * ------------------------------------------------------
 *  实例化utf8类
 * ------------------------------------------------------
 */
	$UNI =& load_class('Utf8', 'core');

/*
 * ------------------------------------------------------
 *  实例化url类
 * ------------------------------------------------------
 */
	$URI =& load_class('URI', 'core');

/*
 * ------------------------------------------------------
 *  实例化路由类并设置路由
 *  如果自己没配置的话自动加载项目文件夹下的config/router.php参数配置
 * ------------------------------------------------------
 */
	$RTR =& load_class('Router', 'core', isset($routing) ? $routing : NULL);

/*
 * ------------------------------------------------------
 *  实例化输出类
 * ------------------------------------------------------
 */
	$OUT =& load_class('Output', 'core');

/*
 * ------------------------------------------------------
 *	//下面是输出缓存的处理，这里允许我们自己写个hook来取替代CI原来Output类的缓存输出
//如果缓存命中则输出，并结束整个CI的单次生命周期。如果没有命中缓存，或没有启用缓存，那么将继续向下执行。  .
 * ------------------------------------------------------
 */
	if ($EXT->call_hook('cache_override') === FALSE && $OUT->_display_cache($CFG, $URI) === TRUE)
	{
		exit;
	}

/*
 * -----------------------------------------------------
 * 为xss和csrf支持加载安全
 * -----------------------------------------------------
 */
	$SEC =& load_class('Security', 'core');

/*
 * ------------------------------------------------------
 *  加载输入类并对全局进行消毒
 * ------------------------------------------------------
 */
	$IN	=& load_class('Input', 'core');

/*
 * ------------------------------------------------------
 *  加载语言类
 * ------------------------------------------------------
 */
	$LANG =& load_class('Lang', 'core');

/*
 * ------------------------------------------------------
 *  加载控制器
 * ------------------------------------------------------
 *
 */
	// 加载基类
	require_once COREPATH.'core/Controller.php';

	/**
	 * 加载实例对象
	 * @return CI_Controller
	 */
	function &get_instance()
	{
		return CI_Controller::get_instance();
	}

	if (file_exists(FRONTENDPATH.'core/'.$CFG->config['subclass_prefix'].'Controller.php'))
	{
		require_once FRONTENDPATH.'core/'.$CFG->config['subclass_prefix'].'Controller.php';
	}

	// Set a mark point for benchmarking
	$BM->mark('loading_time:_base_classes_end');//记录加载基本类的结束时间

/*
 * ------------------------------------------------------
 *  路由安全检查
 * ------------------------------------------------------
 *
 */

	$e404 = FALSE;
	$class = ucfirst($RTR->class);//将开头第一个字母变为大写
	$method = $RTR->method;//默认为index

    //$class变量不存在或者此类文件不存在
	if (empty($class) OR ! file_exists(FRONTENDPATH.'controllers/'.$RTR->directory.$class.'.php'))
	{
		$e404 = TRUE;
	}
	else
	{
		require_once(FRONTENDPATH.'controllers/'.$RTR->directory.$class.'.php');//加载类文件

		if ( ! class_exists($class, FALSE) OR $method[0] === '_' OR method_exists('CI_Controller', $method))
		{
			$e404 = TRUE;//类不存在或者,方法是方法开头不是_下标,或者在CI_Controller中已经有这个方法了
		}
		elseif (method_exists($class, '_remap'))
		{
			$params = array($method, array_slice($URI->rsegments, 2));//从$URI->rsegments中的第三个元素取出剩余参数
			$method = '_remap';
		}
		elseif ( ! method_exists($class, $method))//此类中没有此方法
		{
			$e404 = TRUE;
		}
		/**
         * is_callable是php4的构造函数,$method是构造函数他都会说不是
         *  method_exists只能探测非构造方法
		 */
		elseif ( ! is_callable(array($class, $method)))
		{
			$reflection = new ReflectionMethod($class, $method);//用反射机制
			if ( ! $reflection->isPublic() OR $reflection->isConstructor())//如果方法是构造方法或者不是公共方法
			{
				$e404 = TRUE;
			}
		}
	}

	if ($e404)
	{
		if ( ! empty($RTR->routes['404_override']))
		{
			if (sscanf($RTR->routes['404_override'], '%[^/]/%s', $error_class, $error_method) !== 2)
			{
				$error_method = 'index';
			}

			$error_class = ucfirst($error_class);

			if ( ! class_exists($error_class, FALSE))
			{
				if (file_exists(FRONTENDPATH.'controllers/'.$RTR->directory.$error_class.'.php'))
				{
					require_once(FRONTENDPATH.'controllers/'.$RTR->directory.$error_class.'.php');
					$e404 = ! class_exists($error_class, FALSE);
				}
				// Were we in a directory? If so, check for a global override
				elseif ( ! empty($RTR->directory) && file_exists(FRONTENDPATH.'controllers/'.$error_class.'.php'))
				{
					require_once(FRONTENDPATH.'controllers/'.$error_class.'.php');
					if (($e404 = ! class_exists($error_class, FALSE)) === FALSE)
					{
						$RTR->directory = '';
					}
				}
			}
			else
			{
				$e404 = FALSE;
			}
		}

		// Did we reset the $e404 flag? If so, set the rsegments, starting from index 1
		if ( ! $e404)
		{
			$class = $error_class;
			$method = $error_method;

			$URI->rsegments = array(
				1 => $class,
				2 => $method
			);
		}
		else
		{
			show_404($RTR->directory.$class.'/'.$method);
		}
	}

	if ($method !== '_remap')
	{
		$params = array_slice($URI->rsegments, 2);
	}

/*
 * ------------------------------------------------------
 *  定义钩子,在示例话控制器之前定义
 * ------------------------------------------------------
 */
	$EXT->call_hook('pre_controller');

/*
 * ------------------------------------------------------
 *  实例化我们访问的控制器
 * ------------------------------------------------------
 */
	//加载控制器时间开始
	$BM->mark('controller_execution_time_( '.$class.' / '.$method.' )_start');

	$CI = new $class();

/*
 * ------------------------------------------------------
 *  定义钩子,在调用方法之前定义
 * ------------------------------------------------------
 */
	$EXT->call_hook('post_controller_constructor');

/*
 * ------------------------------------------------------
 *  直接调用方法
 * ------------------------------------------------------
 */
    //直接调用
	call_user_func_array(array(&$CI, $method), $params);

	// 加载控制器时间结束
	$BM->mark('controller_execution_time_( '.$class.' / '.$method.' )_end');

/*
 * ------------------------------------------------------
 *  方法调用结束钩子
 * ------------------------------------------------------
 */
	$EXT->call_hook('post_controller');

/*
 * ------------------------------------------------------
 *  加载输出
 * ------------------------------------------------------
 */
	if ($EXT->call_hook('display_override') === FALSE)
	{
		$OUT->_display();
	}

/*
 * ------------------------------------------------------
 *  系统结束钩子
 * ------------------------------------------------------
 */
	$EXT->call_hook('post_system');


	/**
     * 这个是ci启动项加载页面
     * 1. 设置CI框架当前版本常量
     * 2. 加载一些常量(系统给的)
     */
