<?php
App::uses('AppController', 'Controller');

class BooksController extends AppController {

	//Lista todos os livros
	public function getBooks(){
		$books = $this->Book->find('all', 
			array('fields' => array('title','author')));
		
		$books = Set::extract($books, '{n}.Book');
		echo json_encode($books);
		die();
	}
	
	//Lista o livro por Id ou por titulo (caso o titulo seja um inteiro)
	public function getBookByIdOrTitle($id_or_title = null){
		//Neste caso ja considera que $id_or_title é um inteiro positivo, definido em routes.php
		
		$book = $this->Book->read(
			array('Book.title','Book.author','Book.publisher','Book.isbn'),
			$id_or_title
		);

		$book = Set::extract($book, 'Book');
		
		if ($book == null){//Considera a pesquisa como uma pesquisa por titulo, ja que nao é o ID
			$book = $this->Book->find('all', 
				array(
				'fields' => array('title','author'),
				'conditions'=>array("Book.title LIKE '%$id_or_title%'")
			));
			
			$book = Set::extract($book, '{n}.Book');
		
		}
		
		echo json_encode($book);
		die();
	}
	
	//Lista os livros por titulo OU autor
	public function getBookByTitleOrAuthor($par = null){
		$books = $this->Book->find('all', 
			array(
			'fields' => array('title','author'),
			'conditions'=>array('OR' =>array('Book.title LIKE ' => "%$par%", 'Book.author LIKE '=> "%$par%"))
			));
		
		$books = Set::extract($books, '{n}.Book');
		echo json_encode($books);
		
		die();
	}
	
	//Lista os livros por titulo E autor
	public function getBookByTitleAndAuthor($title = null, $author = null){
		$books = $this->Book->find('all', 
			array(
			'fields' => array('title','author'),
			'conditions'=>array('AND' =>array('Book.title LIKE ' => "%$title%", 'Book.author LIKE '=> "%$author%"))
			));
		
		$books = Set::extract($books, '{n}.Book');
		echo json_encode($books);
		die();
	}

}
?>