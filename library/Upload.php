<?php

function Upload($roles)
{
  $dbh = new DatabaseHelper();
  $url = Url();

  if (isset($url[0]) && $url[0] == 'upload' && Access($roles) && $dbh->doesTableExists('file')) {
    $source = $_FILES['file_upload']['tmp_name'];
    $orginal = $_FILES['file_upload']['name'];
    $extension = pathinfo($orginal, PATHINFO_EXTENSION);

    if (in_array($extension, explode(',', Environment('upload.extensions')))) {
      $hashed = sha1($source.$orginal) . '.' . $extension;
      $upload = move_uploaded_file($source, './uploads/'.$hashed);

      if ($upload) {
        $user = GetUser();

        $save = $dbh->insert('file', [
          'name' => str_replace('.'.$extension, '', $orginal),
          'path' => './uploads/'.$hashed,
          'userId' => $user[0]['id']
        ]);

        Response([
          'result' => $save
        ]);
      }
    }
  }
}

?>
