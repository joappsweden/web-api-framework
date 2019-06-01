<?php

function Validation($table, $data)
{
  $currentModel = NULL;

  foreach (GetModels() as $model) {
    if ($model->getName() === $table) {
      $currentModel = $model;
    }
  }

  if (isset($currentModel)) {
    if (count($data) > 0) {
      foreach ($data as $key => $value) {
        $property = NULL;

        foreach ($currentModel->getProperties() as $name => $settings) {
          if ($key === $name) {
            $property[$name] = $settings;
          }
        }

        if (isset($property)) {
          if ($property[$key]['property'] === 'email') {
            if (filter_var($value, FILTER_VALIDATE_EMAIL)) {
              $data[$key] = $value;
            } else {
              Response([
                'error' => $key . ' must be an email address'
              ]);
            }
          } elseif ($property[$key]['property'] === 'password') {
            $data[$key] = sha1($value);
          } elseif ($property[$key]['property'] === 'createdAt') {
            $data[$key] = date('Y-m-d H:i:s');
          } elseif ($property[$key]['property'] === 'updatedAt') {
            $data[$key] = date('Y-m-d H:i:s');
          } elseif ($property[$key]['property'] === 'file') {
            $environmentUploadExtensions = Environment('upload.extensions');
            $extensions = explode(',', $environmentUploadExtensions);

            if (in_array(pathinfo($value, PATHINFO_EXTENSION), $extensions)) {
              $data[$key] = $value;
            } else {
              Response([
                'error' => $key . ' must be a valid file extension like ' . $environmentUploadExtensions
              ]);
            }
          }
        }
      }
    }
  }

  return $data;
}

?>
