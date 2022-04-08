<?php
namespace Src;

class Post extends ControllerBase {

  protected function hookMountSelectAllItemsSQL() {
    return "
      SELECT
        id, title, body, author, author_picture, created_at
      FROM
        post;
    ";
  }


  protected function hookMountCreateItemSQL() {
    return "
      INSERT INTO post
        (title, body, author, author_picture)
      VALUES
        (:title, :body, :author, :author_picture);
    ";
  }

  //override in concret class
  protected function hookMountCreateItemFieldsMapValues($input) {
    return array(
        'title' => $input['title'],
        'body'  => $input['body'],
        'author' => $input['author'],
        'author_picture' => 'https://secure.gravatar.com/avatar/'.md5(strtolower($input['author'])).'.png?s=200',
      );
  }



  protected function hookMountUpdateItemSQL() {
    return "
      UPDATE post
      SET
        title = :title,
        body  = :body,
        author = :author,
        author_picture = :author_picture
      WHERE id = :id;
    ";
  }


  protected function hookMountUpdateItemFieldsMapValues($id, $input) {
    return array(
        'id' => (int) $id,
        'title' => $input['title'],
        'body'  => $input['body'],
        'author' => $input['author'],
        'author_picture' => 'https://secure.gravatar.com/avatar/'.md5($input['author']).'.png?s=200',
      );
  }



  protected function hookMountDeleteItemSQL() {
    return "
      DELETE FROM post
      WHERE id = :id;
    ";
  }

  protected function hookMountFindItemSQL() {
    return "
      SELECT
        id, title, body, author, author_picture, created_at
      FROM
        post
      WHERE id = :id;
    ";
  }  


  protected function validateRegister($input)
  {
    if (! isset($input['title'])) {
      return false;
    }
    if (! isset($input['body'])) {
      return false;
    }

    return true;
  }
}