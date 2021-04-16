<?php

# Extension for user authentication and results

class Article extends SQL {

	var $sysArticleTable = "articles"; // User table name, defaults to `articles`
	var $sysArticleTheme = "epArticleView.tpl"; // Article viewing page name
	
	# Article Properties
	public $id, $title, $body, $timestamp, $authorID, $authorUser, $authorName, $cats, $tags;
	
	public function Article( $articleID, $renderAuthor = true ){
		
		global $sys, $output;
		
		$dbArticle = $this->articleRecallByID( $articleID );

		if( !$dbArticle ){
			# No article found matching specified ID
			$error = new Error();
			$error->error404();
		}
		
		$this->id = $articleID;
		$this->title = $this->decode( $dbArticle['article_title'] );
		$this->body = $this->decode( $dbArticle['article_body'] );
		
		$this->timestamp = $dbArticle['article_timestamp'];
		$this->authorID = $dbArticle['article_author'];
		$this->cats = $dbArticle['article_categories'];
		$this->tags = $dbArticle['article_tags'];
	
		if( $renderAuthor ){
			$author = $this->resolveAuthor();
			$this->authorName = $author['authorName'];
			$this->authorUser = $author['authorUser'];
		}
		
		$sys['FinalPage'] = $this->sysArticleTheme;
		return true;
		
	}
	
	public function returnArticle(){
	
		$temp['title'] = $this->title;
		$temp['body'] = $this->body;
		$temp['timestamp'] = $this->timestamp;
		$temp['authorID'] = $this->authorID;
		
		$temp['authorUser'] = $this->authorUser;
		$temp['authorName'] = $this->authorName;
		
		$temp['cats'] = $this->cats;
		$temp['tags'] = $this->tags;
		
		return $temp;
	
	}
	
	private function articlesSelectColumn( $case ){
		# Determine which column to compare $value to
		switch( $case ){
			case 'id'        : return "article_id";
			case 'timestamp' : return "article_timestamp";
			case 'author'    : return "article_author";
			case 'cats'      : return "article_categories";
			case 'tags'      : return "article_tags";
			case 'title'     : return "article_title";
			case 'body'      : return "article_body";
			default          : return "article_id";
		}
	}
	
	protected function articleSelectQ( $value, $case = "id", $selection = "*" ){
		# Prepare select query for this class
		if( empty( $selection ) || $selection == "*" )
			$selection = "*";
		else
			$selection = $this->encode( $selection );
		
		$column = $this->articlesSelectColumn( $case );	
		$value = $this->encode( $value );
		return  "SELECT $selection " .
				"FROM `" . $this->tableName( $this->sysArticleTable ) .
				"` WHERE `$column` = '$value' LIMIT 0,1";
	}
	
	private function articleRecallByID( $value, $selection = NULL ){
		# Return an article from the database
		$this->connect();
		
		$query = $this->articleSelectQ( $value, 'id', $selection );
		$result = $this->select( $query );
		$this->close();
		
		if( $result ){
			return $result;
		}else return false;
	}
	
	private function resolveAuthor(){
		
		$userDB = new UserSQL();
		$temp = $userDB->userRead( $this->authorID, 'id', 'user_name,user_display_name' );
		
		$user['authorUser'] = $this->decode( $temp['user_name'] );
		$user['authorName'] = $temp['user_display_name'];
		
		return $user;
		
	}

}