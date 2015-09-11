<?php
/**
  * @author nanze
  * @link 
  * @todo 数据库备份
  * @copyright 811046@qq.com
  * @version 1.0
  * @lastupdate 2010-11-18
*/ 
class SqlbackupAction extends AdminCommAction{
	protected $db =  NULL;
	protected $dbName =  NULL;
	function _initialize()
	{
		parent::_initialize();
		// 获取数据库对象实例
		$this->db   =  Db::getInstance();
		$this->dbName   =  C('DB_NAME');
	}
	public function index() {
		$this->tables =$this->db->getTables($this->dbName);
		$this->display();
	}
	/**
     +----------------------------------------------------------
     * 导出SQL文件
     +----------------------------------------------------------
     * @access public
     +----------------------------------------------------------
     * @return void
     +----------------------------------------------------------
     */
	public function outputData() {


		@set_time_limit(300);
		$filename   =  empty($_POST['tableName'])?$this->dbName:(count($_POST['tableName'])>1?str_replace('.','_',$_SERVER['HTTP_HOST']).'_tables':implode(',',$_POST['tableName']));
		$filepath=TEMP_PATH.$filename.'.sql';
		if(file_exists("$filepath")){
			unlink("$filepath");
		}
		$filepath=TEMP_PATH.$filename.'.zip';
		if(file_exists("$filepath")){
			unlink("$filepath");
		}
		import("ORG.Net.Http");
		if(empty($_POST['tableName'])) {
			// 默认导出所有表
			$tables =$this->db->getTables($this->dbName);
		}else{
			// 导出指定表
			$tables  =  $_POST['tableName'];
		}
		$this->db->execute('USE '.$this->dbName);
		// 组装导出SQL
		foreach($tables as $key=>$table) {
			$sql  .= "-- \n-- 表的结构 `$table`\n-- \n";
			$info  = $this->db->query("SHOW CREATE TABLE  $table");
			$sql   .= $info[0]['Create Table'];
			$sql  .= ";\n-- \n-- 导出表中的数据 `$table`\n--\n";
			$count  =$this->db->query("SELECT count(*) as num FROM $table ");
			$tanumpro=$count[0][num];
			if($tanumpro>5000){
				for($i=0;$i<=$tanumpro;){
					$result  =$this->db->query("SELECT * FROM $table limit $i,5000");
					foreach($result as $key=>$val) {
						foreach ($val as $k=>$field){
							if(is_string($field)) {
								$val[$k] = '\''.$this->db->escape_string($field).'\'';
							}elseif(empty($field)){
								$val[$k] =  'NULL';
							}
						}
						$sql  .= "INSERT INTO `$table` VALUES (".implode(',',$val).");\n";
					}
					$i=$i+5000;
					$fp=fopen(TEMP_PATH.$filename.'.sql',"a+");
					fwrite($fp,$sql);
					fclose($fp);
					$sql='';
				}
			}else{
				$result  =$this->db->query("SELECT * FROM $table ");
				foreach($result as $key=>$val) {
					foreach ($val as $k=>$field){
						if(is_string($field)) {
							$val[$k] = '\''.$this->db->escape_string($field).'\'';
						}elseif(empty($field)){
							$val[$k] =  'NULL';
						}
					}
					$sql  .= "INSERT INTO `$table` VALUES (".implode(',',$val).");\n";
				}
				$fp=fopen(TEMP_PATH.$filename.'.sql',"a+");
				fwrite($fp,$sql);
				fclose($fp);
				$sql='';
			}
		}

		if(empty($_POST['zip'])) {
			//file_put_contents(TEMP_PATH.$filename.'.sql',trim($sql));
			Http::download (TEMP_PATH.$filename.'.sql');
		}else{
			/*echo TEMP_PATH.$filename.'.zip';
			echo $filename.'.sql';
			exit;*/
			$zip = new ZipArchive();
			if ($zip->open(TEMP_PATH.$filename.'.zip', ZIPARCHIVE::CREATE)!==TRUE) {
				exit("cannot open <$filename>\n");
			}
			// $zip->addFromString($filename.'.sql',trim($sql));
			$zip->addFile(TEMP_PATH.$filename.'.sql',$filename.'.sql');
			$zip->close();
			Http::download (TEMP_PATH.$filename.'.zip');
		}
	}
}

?>