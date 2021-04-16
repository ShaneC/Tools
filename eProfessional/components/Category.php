<?php

# Extension for user authentication and results

class Category extends SQL {

	var $sysArticleTable = "categories"; // User table name, defaults to `categories`
	
	# Category Properties
	public $id, $name;
	
	public function Category( $CatID ){
	
		$category = $this->catRecall( $CatID );
		if( !$category )
			return false;
		
		$this->id = $category[$this->catSelectColumn('id')];
		$this->name = $category[$this->catSelectColumn('name')];
		return true;
		
	}
	
	public function returnCategory(){
		$temp['id'] = $this->id;
		$temp['name'] = $this->name;
		return $temp;		
	}
	
	private function catSelectColumn( $case ){
		# Determine which column to compare $value to
		switch( $case ){
			case 'id'   : return "cat_id";
			case 'name' : return "cat_name";
			default     : return "cat_id";
		}
	}
	
	protected function catSelectQ( $value, $case = "id", $selection = "*" ){
		# Prepare select query for this class
		if( empty( $selection ) || $selection == "*" )
			$selection = "*";
		else
			$selection = $this->encode( $selection );
		
		$column = $this->catSelectColumn( $case );	
		$value = $this->encode( $value );
		return  "SELECT $selection " .
				"FROM `" . $this->tableName( $this->sysArticleTable ) .
				"` WHERE `$column` = '$value' LIMIT 0,1";
	}
	
	private function catRecall( $value, $case = "id", $selection = NULL ){
		# Return an article from the database
		$this->connect();
		
		$query = $this->catSelectQ( $value, $case, $selection );
		$result = $this->select( $query );
		$this->close();
		
		if( $result ){
			return $result;
		}else return false;
	}

}