<?php
/**
@desciption: 表单验证类
*/

class cls_validation {
	
	public $error_string		= '';
	public $error_array			= array();
	private $_rules				= array();
	private $_fields			= array();
	private $_error_messages	= array();
	private $_current_field  	= '';
	private $_post 				= '';

	public function set_fields($field, $label = ''){	
		$this->_fields[$field] = ($label!=''?$label:$field);
	}

	public function set_rules($field, $rules, $label = ''){
		$this->_rules[$field] = $rules;
		$this->set_fields($field,$label);
	}

	public function set_message($rule='', $msg='', $language='zh-cn'){
		if(empty($this->_error_messages)) {
			include(COMM_DIR.'lang/'.$language.'.php');
			$this->_error_messages = $lang;
		}
		
		if($rule != '' && $msg != ''){
			$this->_error_messages[$rule] = $msg;
		}		
	}
	
	private function clear_error(){
		$this->error_array = array();
		$this->_rules = array();
		$this->_fields = array();
		$this->_error_messages = array();
		$this->_current_field = '';
		$this->_post = '';
		$this->error_string = '';
	}
	
	/**
	 * 校验数据
	 *
	 * @param array $post
	 * @param array $rules
	 * @param array $message
	 * @return bool
	 */
	public function valid(&$post,$rules,$message = ''){
		if(!is_array($rules)) return false;
		$this->clear_error();//初始化
		$this->_post = &$post;
		
		foreach ($rules as $rule){
			$this->set_rules($rule['field'],$rule['rules'],$rule['label']);
		}
		
		if(is_array($message)){
			foreach ($message as $rule=>$msg){
				$this->set_message($rule,$msg);
			}
		}else{
			$this->set_message();
		}
		
		if (count($post) == 0 || count($this->_rules) == 0)
		{
			return false;
		}
							
		foreach ($this->_rules as $field => $rules) {
			$ex = explode('|', $rules);
			
			if ( ! in_array('required', $ex, TRUE)){
				if ( ! isset($post[$field]) || $post[$field] == ''){
					continue;
				}
			}
			
			if ( ! isset($post[$field]))
			{			
				if (in_array('isset', $ex, TRUE) || in_array('required', $ex))
				{
					
					$line = $this->_error_messages['isset'];
					
					$mfield = ( ! isset($this->_fields[$field])) ? $field : $this->_fields[$field];

					$this->error_array[$field] = sprintf($line, $mfield);
				}
						
				continue;
			}
	
			$this->_current_field = $field;

			foreach ($ex As $rule){	
				$callback = FALSE;
				if (substr($rule, 0, 9) == 'callback_')
				{
					$rule = substr($rule, 9);
					$callback = TRUE;
				}
				
				$param = FALSE;
				if (preg_match("/(.*?)\[(.*?)\]/", $rule, $match)){
					$rule	= $match[1];
					$param	= $match[2];
				}
				
				if ($callback === TRUE){
					if ( ! method_exists($this, $rule)){ 		
						continue;
					}
					
					$result = $this->$rule($post[$field], $param);
					
					if ( ! in_array('required', $ex, TRUE) AND $result !== FALSE){
						continue 2;
					}
					
				}else{				
					if ( ! method_exists($this, $rule)){
						if (function_exists($rule)){
							$post[$field] = $rule($post[$field]);
						}
											
						continue;
					}
					
					$result = $this->$rule($post[$field], $param);
				}
								
				if ($result === FALSE){
					$line = $this->_error_messages[$rule];
					
					$mfield = ( ! isset($this->_fields[$field])) ? $field : $this->_fields[$field];
					$mparam = empty($param)?'':$param;
					$this->error_array[$field] = sprintf($line, $mfield, $mparam);	
					continue 2;
				}			
			}
			
		}
		
		$total_errors = count($this->error_array);

		if ($total_errors == 0){
			return TRUE;
		}
		
		foreach ($this->error_array as $val){
			$this->error_string .= "$val\n";
		}

		return FALSE;
	}
	
	private function required($str){
		//如果元素为空，则返回FALSE
		if ( ! is_array($str))
		{
			return (trim($str) == '') ? FALSE : TRUE;
		}
		else
		{
			return ( ! empty($str));
		}
	}
	
	private function matches($str, $field){
		//如果表单元素的值与参数中对应的表单字段的值不相等，则返回FALSE
		if ( ! isset($this->_post[$field]))
		{
			return FALSE;
		}
		
		return ($str !== $this->_post[$field]) ? FALSE : TRUE;
	}
	
	private function min_length($str, $val){
		//如果表单元素值的字符长度少于参数中定义的数字，则返回FALSE
		if (preg_match("/[^0-9]/", $val))
		{
			return FALSE;
		}

		if (function_exists('mb_strlen'))
		{
			return (mb_strlen($str) < $val) ? FALSE : TRUE;		
		}

		return (strlen($str) < $val) ? FALSE : TRUE;
	}
	
	private function max_length($str, $val){
		//如果表单元素值的字符长度大于参数中定义的数字，则返回FALSE
		if (preg_match("/[^0-9]/", $val))
		{
			return FALSE;
		}
		
		if (function_exists('mb_strlen'))
		{
			return (mb_strlen($str) > $val) ? FALSE : TRUE;		
		}

		return (strlen($str) > $val) ? FALSE : TRUE;
	}
	
	private function exact_length($str, $val){
		//如果表单元素值的字符长度与参数中定义的数字不符，则返回FALSE
		if (preg_match("/[^0-9]/", $val))
		{
			return FALSE;
		}
	
		if (function_exists('mb_strlen'))
		{
			return (mb_strlen($str) != $val) ? FALSE : TRUE;		
		}

		return (strlen($str) != $val) ? FALSE : TRUE;
	}
	
	private function valid_email($str){
		//如果表单元素值包含不合法的email地址，则返回FALSE
		return ( ! preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $str)) ? FALSE : TRUE;
	}

	private function valid_emails($str){
		//如果表单元素值中任何一个值包含不合法的email地址（地址之间用英文逗号分割），则返回FALSE。
		if (strpos($str, ',') === FALSE)
		{
			return $this->valid_email(trim($str));
		}
		
		foreach(explode(',', $str) as $email)
		{
			if (trim($email) != '' && $this->valid_email(trim($email)) === FALSE)
			{
				return FALSE;
			}
		}
		
		return TRUE;
	}
	
	private function valid_username($str){
		return ( ! preg_match("/^[A-Za-z0-9]{5,20}$/i", $str)) ? FALSE : TRUE;
	}

	//公司编号
	private function valid_cnumber($str){
		return ( ! preg_match("/^[A-Za-z0-9]{5,10}$/i", $str)) ? FALSE : TRUE;
	}

	private function valid_ip($ip){
		//如果表单元素的值不是一个合法的IP地址，则返回FALSE。
		return ( ! preg_match("/^(\d{3})\.(\d{3})\.(\d{3})\.(\d{3})$/", $ip)) ? FALSE : TRUE;
	}
	
	private function alpha($str){
		//如果表单元素值中包含除字母以外的其他字符，则返回FALSE
		return ( ! preg_match("/^([a-z])+$/i", $str)) ? FALSE : TRUE;
	}
	
	private function alpha_numeric($str){
		//如果表单元素值中包含除字母和数字以外的其他字符，则返回FALSE
		return ( ! preg_match("/^([a-z0-9])+$/i", $str)) ? FALSE : TRUE;
	}
	
	private function alpha_dash($str){
		//如果表单元素值中包含除字母/数字/下划线/破折号以外的其他字符，则返回FALSE
		return ( ! preg_match("/^([-a-z0-9_-])+$/i", $str)) ? FALSE : TRUE;
	}
	
	private function numeric($str){
		//如果表单元素值中包含除数字以外的字符，则返回 FALSE
		return (bool)preg_match( '/^[\-+]?[0-9]*\.?[0-9]+$/', $str);

	}

	private function integer($str){
		//如果表单元素中包含除整数以外的字符，则返回FALSE
		return (bool)preg_match( '/^[\-+]?[0-9]+$/', $str);
	}

	private function is_natural($str){
		//如果表单元素值中包含了非自然数的其他数值 （其他数值不包括零），则返回FALSE。自然数形如：0,1,2,3....等等。
   		return (bool)preg_match( '/^[0-9]+$/', $str);
	}

	private function is_natural_no_zero($str){
		//如果表单元素值包含了非自然数的其他数值 （其他数值包括零），则返回FALSE。非零的自然数：1,2,3.....等等。
		if ( ! preg_match( '/^[0-9]+$/', $str))
		{
			return FALSE;
		}
	
		if ($str == 0)
		{
			return FALSE;
		}

		return TRUE;
	}
	
	private function valid_base64($str){
		//如果表单元素的值包含除了base64 编码字符之外的其他字符，则返回FALSE。
		return (bool) ! preg_match('/[^a-zA-Z0-9\/\+=]/', $str);
	}
}