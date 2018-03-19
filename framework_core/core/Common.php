<?php
/**
 * Created by PhpStorm.
 * User: tianxin
 * Date: 2018/3/14
 * Time: 21:53
 */
defined('COERPATH') OR exit('No direct script access allowed');

/**
 *加载公共方法
 *加载基类并执行请求
 */

// ------------------------------------------------------------------------

/**
 * 如果php不存在is_php,自定义is_php函数
 */
if ( ! function_exists('is_php'))
{
	/**
	 * 判断当前系统运行php版本是否大于大于传进来的值
	 *
	 * @param	string
	 * @return	bool	TRUE证明当前系统运行php版本大于传进来的值
	 */
	function is_php($version)
	{
		static $_is_php;//定义方法静态变量
		$version = (string) $version;//版本强行转string
        //$_is_php之所以定义为静态是因为php脚本运行将is_php函数加载在了内存中,方法静态变量也被持久化在了内存中,
        //再一次请求过程中再调用此方法时可以不需要再判断,直接返回结果
		if ( ! isset($_is_php[$version]))
		{
		    //判断当前运行版本是否大于提供版本
			$_is_php[$version] = version_compare(PHP_VERSION, $version, '>=');
		}

		return $_is_php[$version];//返回TRUEorFALSE
	}
}

// ------------------------------------------------------------------------
/**
 * 如果php不存在is_really_writable函数,自定义is_really_writable数
 */
if ( ! function_exists('is_really_writable'))
{
	/**
	 * 测试文件可写性
	 * 如果文件在window服务器上属性为可读的话is_writable()返回true,
     * 如果safe_mode打开,那么在Unix服务器上is_writable()也是不可用的。
	 * safe_mode安全模式在php5.4被废弃
	 *
	 * @link	https://bugs.php.net/bug.php?id=54709
	 * @param	string
	 * @return	bool
	 */
	function is_really_writable($file)
	{
        /**
         * safe_mode安全模式5.4废弃了
         *DIRECTORY_SEPARATOR === '/'判断系统是unix服务器 DIRECTORY_SEPARATOR==='\'是window服务器
         */
		if (DIRECTORY_SEPARATOR === '/' && (is_php('5.4') OR ! ini_get('safe_mode')))
		{
		    //如果服务器是unix服务器并且php版本大于或等于5.4或者没开启安全模式
			return is_writable($file);//直接用is_writable函数判断文件是否可写
		}

		/*
		 * windows服务器判断文件是否可读
		 */
		if (is_dir($file))
		{
            /**
             * 随机生成文件名mt_rand()比rand()的范围更广,效率更高
             */
			$file = rtrim($file, '/').'/'.md5(mt_rand());
			//以写入模式创建新文件,不能创建返回false,a是写入模式,b是在window服务器下标识文件是二进制,如果不标识会出现一系列问题
            /*
             * fopen可以节省内存,因为它是二进制文件流的格式传输的,如果开启了安全模式的话,fopen会被停用
             * @符号代表不报任何错误,但如果碰到影响程序运行的错误,程序会终止,只不过不会显示报错信息而已
             */
			if (($fp = @fopen($file, 'ab')) === FALSE)
			{
				return FALSE;
			}

			fclose($fp);//关闭一个打开文件流，将文件流输出到磁盘或者关闭占用
			@chmod($file, 0777);//对文件赋予最高权限
			@unlink($file);//删除文件
			return TRUE;
		}
		elseif ( ! is_file($file) OR ($fp = @fopen($file, 'ab')) === FALSE)//如果不是一个文件或者不能写入打开此文件返回flase
		{
			return FALSE;
		}

		fclose($fp);//关闭文件
		return TRUE;
	}
}

// ------------------------------------------------------------------------
/**
 * 注册类文件
 */
if ( ! function_exists('load_class'))
{
	/**
	 * 注册启动类
	 *
     * 此函数差不多与单例相同,如果请求的类没有,他会被实例化成一个静态变量保存在此方法中
     * 如果存在此方法,则直接返回代表此实例化的静态变量
	 *
	 * @param	string	$class 是类名
	 * @param	string	$directory 是类存在的文件夹
	 * @param	mixed	类实例化构造函数需要的参数
	 * @return	object  返回一个示例化得类对象
	 */
	function &load_class($class, $directory = 'libraries', $param = NULL)
	{
		static $_classes = array();

		// 如果此示例化对象已存在,直接返回实例化对象
		if (isset($_classes[$class]))
		{
			return $_classes[$class];
		}

		$name = FALSE;

        //循环项目文件夹与CI核心代码文件夹,查找$directory文件夹
		foreach (array(FRONTENDDIR, COREPATH) as $path)
		{
			if (file_exists($path.$directory.'/'.$class.'.php'))//如果文件存在
			{
				$name = 'CI_'.$class;//在类前加前缀,是CI核心类里的规定
                //判断类是否存在,不调用__autoload方法,原因是调用__autoload太消耗资源,并且
				if (class_exists($name, FALSE) === FALSE)//如果还没有定义此类
				{
					require_once($path.$directory.'/'.$class.'.php');//加载文件,定义一下类
				}

				break;//退出,FRONTENDDIR文件夹查找文件优先级比COREPATH高
			}
		}

		// 如果请求要加载的类是自己定义的,有前缀名,看项目文件夹中存不存在
		if (file_exists(FRONTENDPATH.$directory.'/'.config_item('subclass_prefix').$class.'.php'))
		{
			$name = config_item('subclass_prefix').$class;//从config文件中获取前缀名并连接类名

			if (class_exists($name, FALSE) === FALSE)
			{
				require_once(FRONTENDPATH.$directory.'/'.$name.'.php');//加载文件,定义一下类
			}
		}

		// 以上两种方式都没找到加载的文件要报错
		if ($name === FALSE)
		{
			set_status_header(503);
			echo 'Unable to locate the specified class: '.$class.'.php';//输出
			exit(5); // CI自定义5退出是没有需要加载的类
		}

		//跟踪我们要加载的类,没有做引用,直接调用
		is_loaded($class);

        /**
         * 实例化类
         * 如果存在构造参数,加载构造参数
         */
		$_classes[$class] = isset($param)
			? new $name($param)
			: new $name();
		return $_classes[$class];//返回实例化对象
	}
}

// --------------------------------------------------------------------
/**
 * 判断类是否被加载
 */
if ( ! function_exists('is_loaded'))
{
	/**
	 * 判断哪些类被加载过了
	 * 此函数只在load_class新的实例化类事加载
	 * @param	string
	 * @return	array
	 */
	function &is_loaded($class = '')
	{
		static $_is_loaded = array();

		if ($class !== '')//如果有要加载的类名
		{
		    //键名是小写
			$_is_loaded[strtolower($class)] = $class;
		}

		return $_is_loaded;//返回已加载类的数组
	}
}

// ------------------------------------------------------------------------

if ( ! function_exists('get_config'))
{
	/**
     * 获取项目config配置并塞到一个数组中去
	 * @param	array
	 * @return	array
	 */
	function &get_config(Array $replace = array())
	{
		static $config;

		if (empty($config))//如果数组不是空的加载配置
		{
			$file_path = FRONTENDPATH.'config/config.php';//参数配置文件路径
			$found = FALSE;
			if (file_exists($file_path))
			{
				$found = TRUE;
				require($file_path);//加载
			}

			// Is the config file in the environment folder?
			if (file_exists($file_path = FRONTENDPATH.'config/'.ENVIRONMENT.'/config.php'))
			{
				require($file_path);//加载环境配置文件
			}
			elseif ( ! $found)
			{
				set_status_header(503);//没找到报503
				echo 'The configuration file does not exist.';
				exit(3); //退出因为配置文件的原因
			}

			// $config不是数组格式存在于文件中
			if ( ! isset($config) OR ! is_array($config))
			{
				set_status_header(503);
				echo 'Your config file does not appear to be formatted correctly.';
				exit(3); //退出因为配置文件的原因
			}
		}

		//可以通过$replace参数任意加载配置参数
		foreach ($replace as $key => $val)
		{
			$config[$key] = $val;
		}

		return $config;
	}
}

// ------------------------------------------------------------------------

if ( ! function_exists('config_item'))
{
	/**
	 * 获取指定的配置参数
     * 如果参数传空,获取全部变量
	 * @param	string
	 * @return	mixed
	 */
	function config_item($item)
	{
		static $_config;

		if (empty($_config))
		{
		    //如果$_config是空的话获取全部参数配置
            //这里是引用传递$_config[0]的指向内存地址是& get_config()函数的返回值地址
            /**
             * 静态变量在初始化是必须赋予定值,成员变量是可变的,在静态变量没有定义之前成员变量不存在
             * 所以不能直接引用静态变量
             * 以下其实可以看成
             * $_config=[];
             * $_config[0]=& get_config();
             */
			$_config[0] =& get_config();
		}

		return isset($_config[0][$item]) ? $_config[0][$item] : NULL;
	}
}

// ------------------------------------------------------------------------

if ( ! function_exists('get_mimes'))
{
	/**
	 * 加载资源的媒体类型
	 *
	 * @return	array
	 */
	function &get_mimes()
	{
		static $_mimes;

		if (empty($_mimes))
		{
			$_mimes = file_exists(FRONTENDPATH.'config/mimes.php')
				? include(FRONTENDPATH.'config/mimes.php')
				: array();

			if (file_exists(FRONTENDPATH.'config/'.ENVIRONMENT.'/mimes.php'))
			{
                /**
                 * array_merge 合并数组
                 * 如果有相同string类型的键,后面的覆盖前面的
                 * 如果有相同的数字键,往后排列
                 */
				$_mimes = array_merge($_mimes, include(FRONTENDPATH.'config/'.ENVIRONMENT.'/mimes.php'));
			}
		}

		return $_mimes;
	}
}

// ------------------------------------------------------------------------

if ( ! function_exists('is_https'))
{
	/**
	 * 判断是否https请求
	 * @return	bool
	 */
	function is_https()
	{
		if ( ! empty($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) !== 'off')
		{
			return TRUE;
		}
		elseif (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && strtolower($_SERVER['HTTP_X_FORWARDED_PROTO']) === 'https')
		{
			return TRUE;
		}
		elseif ( ! empty($_SERVER['HTTP_FRONT_END_HTTPS']) && strtolower($_SERVER['HTTP_FRONT_END_HTTPS']) !== 'off')
		{
			return TRUE;
		}

		return FALSE;
	}
}

// ------------------------------------------------------------------------

if ( ! function_exists('is_cli'))
{

	/**
     * 判断是否命令行请求
	 * @return 	bool
	 */
	function is_cli()
	{
		return (PHP_SAPI === 'cli' OR defined('STDIN'));
	}
}

// ------------------------------------------------------------------------

if ( ! function_exists('show_error'))
{
	/**
	 * 调用统一模板,加载异常类去渲染统一返回错误页面
	 * @param	string
	 * @param	int
	 * @param	string
	 * @return	void
	 */
	function show_error($message, $status_code = 500, $heading = 'An Error Was Encountered')
	{
		$status_code = abs($status_code);//abs取绝对值
		if ($status_code < 100)
		{
			$exit_status = $status_code + 9; //自定义运行退出number,最小为9,其他都已经定义过
			$status_code = 500;
		}
		else
		{
			$exit_status = 1; // 非正常退出
		}

		$_error =& load_class('Exceptions', 'core');//加载异常处理类
		echo $_error->show_error($heading, $message, 'error_general', $status_code);//渲染页面
		exit($exit_status);
	}
}

// ------------------------------------------------------------------------

if ( ! function_exists('show_404'))
{
	/**
     * 调用统一模板,加载异常类去渲染统一返回404错误页面
	 * @param	string
	 * @param	bool
	 * @return	void
	 */
	function show_404($page = '', $log_error = TRUE)
	{
		$_error =& load_class('Exceptions', 'core');
		$_error->show_404($page, $log_error);
		exit(4); //未知文件错误
	}
}

// ------------------------------------------------------------------------

if ( ! function_exists('log_message'))
{
	/**
	 * 日志记录
	 * @param	string	日志等级: 'error', 'debug' or 'info'
	 * @param	string	日志信息
	 * @return	void
	 */
	function log_message($level, $message)
	{
		static $_log;

		if ($_log === NULL)
		{
			$_log[0] =& load_class('Log', 'core');//加载日志类
		}

		$_log[0]->write_log($level, $message);
	}
}

// ------------------------------------------------------------------------

if ( ! function_exists('set_status_header'))
{
	/**
	 * 设置http头状态
	 *
	 * @param	int	the status code
	 * @param	string
	 * @return	void
	 */
	function set_status_header($code = 200, $text = '')
	{
		if (is_cli())//是cli运行，直接返回
		{
			return;
		}

		if (empty($code) OR ! is_numeric($code))//code必须是数字或者是字符串
		{
			show_error('Status codes must be numeric', 500);
		}

		if (empty($text))//没有$text自动给
		{
			is_int($code) OR $code = (int) $code;//$code强制转成int类型
			$stati = array(
				100	=> 'Continue',
				101	=> 'Switching Protocols',

				200	=> 'OK',
				201	=> 'Created',
				202	=> 'Accepted',
				203	=> 'Non-Authoritative Information',
				204	=> 'No Content',
				205	=> 'Reset Content',
				206	=> 'Partial Content',

				300	=> 'Multiple Choices',
				301	=> 'Moved Permanently',
				302	=> 'Found',
				303	=> 'See Other',
				304	=> 'Not Modified',
				305	=> 'Use Proxy',
				307	=> 'Temporary Redirect',

				400	=> 'Bad Request',
				401	=> 'Unauthorized',
				402	=> 'Payment Required',
				403	=> 'Forbidden',
				404	=> 'Not Found',
				405	=> 'Method Not Allowed',
				406	=> 'Not Acceptable',
				407	=> 'Proxy Authentication Required',
				408	=> 'Request Timeout',
				409	=> 'Conflict',
				410	=> 'Gone',
				411	=> 'Length Required',
				412	=> 'Precondition Failed',
				413	=> 'Request Entity Too Large',
				414	=> 'Request-URI Too Long',
				415	=> 'Unsupported Media Type',
				416	=> 'Requested Range Not Satisfiable',
				417	=> 'Expectation Failed',
				422	=> 'Unprocessable Entity',
				426	=> 'Upgrade Required',
				428	=> 'Precondition Required',
				429	=> 'Too Many Requests',
				431	=> 'Request Header Fields Too Large',

				500	=> 'Internal Server Error',
				501	=> 'Not Implemented',
				502	=> 'Bad Gateway',
				503	=> 'Service Unavailable',
				504	=> 'Gateway Timeout',
				505	=> 'HTTP Version Not Supported',
				511	=> 'Network Authentication Required',
			);

			if (isset($stati[$code]))
			{
				$text = $stati[$code];//存在已知code
			}
			else
			{
				show_error('No status text available. Please check your status code number or supply your own message text.', 500);
			}
		}

		//如果不是以cgi方式
		if (strpos(PHP_SAPI, 'cgi') === 0)
		{
			header('Status: '.$code.' '.$text, TRUE);
			return;
		}

		$server_protocol = (isset($_SERVER['SERVER_PROTOCOL']) && in_array($_SERVER['SERVER_PROTOCOL'], array('HTTP/1.0', 'HTTP/1.1', 'HTTP/2'), TRUE))
			? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.1';//判断是否是http协议
		header($server_protocol.' '.$code.' '.$text, TRUE, $code);
	}
}

// --------------------------------------------------------------------

if ( ! function_exists('_error_handler'))
{
	/**
	 * php 自定义错误处理程序,用户可能无法访问服务器,在CodeIgniter.php文件中定义,基于报错等级来处理记录日志
	 * @param	int	$severity
	 * @param	string	$message
	 * @param	string	$filepath
	 * @param	int	$line
	 * @return	void
	 */
	function _error_handler($severity, $message, $filepath, $line)
	{
	    //如果$severity是报错等级中的一个,$is_error为true
		$is_error = (((E_ERROR | E_PARSE | E_COMPILE_ERROR | E_CORE_ERROR | E_USER_ERROR) & $severity) === $severity);

		if ($is_error)
		{
			set_status_header(500);//设置信息头
		}
		/*
		*  如果当前报错等级不是处理填写的报错等级,直接不处理
        */
		if (($severity & error_reporting()) !== $severity)
		{
			return;
		}

		$_error =& load_class('Exceptions', 'core');//记录日志
		$_error->log_exception($severity, $message, $filepath, $line);

        /**
         * str_ireplace替换数组和字符串
         * 本次这里用''替换数组中的所有元素
         */
		if (str_ireplace(array('off', 'none', 'no', 'false', 'null'), '', ini_get('display_errors')))
		{
            //如果打开了报错显示,展示此报错信息
			$_error->show_php_error($severity, $message, $filepath, $line);
		}
		if ($is_error)
		{
			exit(1); // 报错退出
		}
	}
}

// ------------------------------------------------------------------------

if ( ! function_exists('_exception_handler'))
{
	/**
	 * 记录异常信息日志,只在display_errors为off的时候展现页面
	 *
	 * @param	Exception	$exception
	 * @return	void
	 */
	function _exception_handler($exception)
	{
		$_error =& load_class('Exceptions', 'core');
		//记录异常信息日志
		$_error->log_exception('error', 'Exception: '.$exception->getMessage(), $exception->getFile(), $exception->getLine());

		is_cli() OR set_status_header(500);//设立http报文头
        /**
         * str_ireplace替换数组和字符串
         * 本次这里用''替换数组中的所有元素
         */
		if (str_ireplace(array('off', 'none', 'no', 'false', 'null'), '', ini_get('display_errors')))
		{
		    //如果打开了报错显示,展示此报错信息
			$_error->show_exception($exception);
		}

		exit(1); //退出状态
	}
}

// ------------------------------------------------------------------------

if ( ! function_exists('_shutdown_handler'))
{
	/**
     * 自定义php脚本停止后运行函数
	 */
	function _shutdown_handler()
	{
        /**
         * error_get_last() 函数返回最后发生的错误（以关联数组的形式）。
         * 关联数组包含四个键：
         * [type] - 描述错误类型
         * [message] - 描述错误消息
         * [file] - 描述发生错误的文件
         * [line] - 描述发生错误的行号
         */
		$last_error = error_get_last();
		if (isset($last_error) &&
			($last_error['type'] & (E_ERROR | E_PARSE | E_CORE_ERROR | E_CORE_WARNING | E_COMPILE_ERROR | E_COMPILE_WARNING)))
		{
            /**
             * 如果错误的类型是以上的一个,执行自定义错误处理函数
             */
			_error_handler($last_error['type'], $last_error['message'], $last_error['file'], $last_error['line']);
		}
	}
}

// --------------------------------------------------------------------

if ( ! function_exists('remove_invisible_characters'))
{
	/**
	 * //删除不可见字符
	 *
	 * 这可以防止夹空字符之间的ASCII字符，如java \ 0script.
	 *
	 * @param	string
	 * @param	bool
	 * @return	string
	 */
	function remove_invisible_characters($str, $url_encoded = TRUE)
	{
		$non_displayables = array();

		// every control character except newline (dec 10),
		// carriage return (dec 13) and horizontal tab (dec 09)
		if ($url_encoded)
		{
			$non_displayables[] = '/%0[0-8bcef]/i';	// url encoded 00-08, 11, 12, 14, 15
			$non_displayables[] = '/%1[0-9a-f]/i';	// url encoded 16-31
			$non_displayables[] = '/%7f/i';	// url encoded 127
		}

		$non_displayables[] = '/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]+/S';	// 00-08, 11, 12, 14-31, 127

		do
		{
			$str = preg_replace($non_displayables, '', $str, -1, $count);
		}
		while ($count);

		return $str;
	}
}

// ------------------------------------------------------------------------

if ( ! function_exists('html_escape'))
{
	/**
	 * .返回html转义变量
	 *
	 * @param	mixed	$var		要转义的字符串或字符串数组.
	 * @param	bool	$double_encode	$double_encode设置为FALSE，防止两次泄漏.
	 * @return	mixed			结果是字符串或字符串数组.
	 */
	function html_escape($var, $double_encode = TRUE)
	{
		if (empty($var))
		{
			return $var;
		}

		if (is_array($var))
		{
		    //array_keys 获取所有的数组的key值组成一个新的数组
			foreach (array_keys($var) as $key)
			{
				$var[$key] = html_escape($var[$key], $double_encode);//递归
			}

			return $var;
		}

        /**
         *htmlspecialchars() 函数把预定义的字符转换为 HTML 实体
         *ENT_COMPAT - 默认。仅编码双引号。
         *ENT_QUOTES - 编码双引号和单引号。
         *ENT_NOQUOTES - 不编码任何引号
         * character-set 规定了要使用的字符集的字符串,从config文件中获取
         * $double_encode 把已经转义过的再次转义
         */
		return htmlspecialchars($var, ENT_QUOTES, config_item('charset'), $double_encode);
	}
}

// ------------------------------------------------------------------------

if ( ! function_exists('_stringify_attributes'))
{
	/**
     *Stringify属性用于HTML标记。
     *用于将字符串、数组或属性对象转换为字符串的辅助函数
	 *
	 * @param	mixed	string, array, object
	 * @param	bool
	 * @return	string
	 */
	function _stringify_attributes($attributes, $js = FALSE)
	{
		$atts = NULL;

		if (empty($attributes))
		{
			return $atts;
		}

		if (is_string($attributes))
		{
			return ' '.$attributes;
		}

		$attributes = (array) $attributes;

		foreach ($attributes as $key => $val)
		{
			$atts .= ($js) ? $key.'='.$val.',' : ' '.$key.'="'.$val.'"';
		}

		return rtrim($atts, ',');
	}
}

// ------------------------------------------------------------------------

if ( ! function_exists('function_usable'))
{
	/**
	 * suhosin拓展,关于php安全方面
	 */
	function function_usable($function_name)
	{
		static $_suhosin_func_blacklist;

		if (function_exists($function_name))
		{
			if ( ! isset($_suhosin_func_blacklist))
			{
                //extension_loaded()检查拓展是否被加载
				$_suhosin_func_blacklist = extension_loaded('suhosin')
					? explode(',', trim(ini_get('suhosin.executor.func.blacklist')))
					: array();
			}

			return ! in_array($function_name, $_suhosin_func_blacklist, TRUE);
		}

		return FALSE;
	}
}

/**
 * 这个是CodeIgniter.php需要加载的公共函数,每一个都进行了是否非系统函数的验证
 * 1. is_php($php_version) 判断当前php版本是否大于等于输入需要比较的php版本
 * 2. is_really_writable($file)判断当前文件是否可写,当然要分服务器判断
 * 3. &load_class($class, $directory = 'libraries', $param = NULL)注册加载类函数,将类的实例化对象扔进静态变量,返回需要的实例化对象
 * 4. &is_loaded($class) 在上一个加载类的函数调用时使用,判断类有没有加载,同样将类是否实例化结果扔进静态变量
 * 5. &get_config(Array $replace = array()) 获得config文件中的数组扔进静态变量,当然可以配置任意临时参数
 * 6. config_item($item)获取单个数组参数,
 * 7. &get_mimes() 读取mime配置获取全部判断文件类型数组
 * 8. is_https() 判断请求是否是https请求
 * 9. is_cli()判断请求是否是cli请求
 * 10 show_error($message, $status_code = 500, $heading = 'An Error Was Encountered') 展示统一报错页面
 * 11 show_404($page = '', $log_error = TRUE) 展示统一404页面
 * 12 log_message($level, $message)加载日志类,统一写日志
 * 13 set_status_header($code = 200, $text = '') 统一设置http信息头
 * 14 _error_handler($severity, $message, $filepath, $line)自定义错误处理
 * 15 _exception_handler($exception)自定义异常处理
 * 16  _shutdown_handler()自定义脚本停止回调函数
 * 17 remove_invisible_characters($str, $url_encoded = TRUE)删除不可见字符
 * 18 html_escape($var, $double_encode = TRUE) 将符号改成html实体
 * 19 _stringify_attributes($attributes, $js = FALSE) 将字符串、数组或属性对象转换为字符串
 * 20 suhosin拓展,关于php安全方面
 */