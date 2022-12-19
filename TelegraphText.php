<?php

require_once 'index.php';


class TelegraphText {
    public $text, $title, $author, $published, $slug, $fileStorage;


    public function __construct(string $author, string $published, string $slug, string $fileStorage)
    {
        $this->fileStorage = $fileStorage;
        $this->author = $author;
        $this->published = new DateTime($published); //date('Y-m-d');
        $this->slug = $slug;
    }

    public function storeText(): void
    {
        $addTextArray = ['title' => $this, 'text' => $this->text,
            'author' => $this->author, 'published' => $this->published, 'fileStorage' => $this->fileStorage];
        file_put_contents($this->slug, serialize($addTextArray));
    }

    public function loadText()
    {
        if (file_exists($this->slug)) {
            $addTextArray = unserialize(file_get_contents($this->slug));
            $this->title = $addTextArray['title'];
            $this->text = $addTextArray['text'];
            $this->author = $addTextArray['author'];
            $this->published = $addTextArray['published'];
            $this->fileStorage = $addTextArray['fileStorage'];
        }

    }
    public function editText($text, $title){
        $this->text = $text;
        $this->title = $title;
    }
}

