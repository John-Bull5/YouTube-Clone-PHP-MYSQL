<?php

class VideoDetailsForm
{
    private $con;

    public function __construct(PDO $con)
    {
        $this->con = $con;
    }
    public function createUploadForm()
    {
        $titleInput = $this->createTitleInput();
        $fileInput = $this->createFileInput();
        $descriptionInput = $this->createDescriptionInput();
        $privacyInput = $this->createPrivacyInput();
        $categoryInput = $this->createCategoryInput();
        $uploadBtn = $this->createUploadButton();
        return "
        <form action='processing.php' method='post' enctype='multipart/form-data'>
        $fileInput
        $titleInput
        $descriptionInput
        $privacyInput
        $categoryInput
        $uploadBtn
        </form>
        ";
    }

    private function createFileInput()
    {
        return '
        <div class="form-group">
            <input type="file" name="fileInput" class="form-control-file" required>
        </div>
        ';
    }
    private function createTitleInput()
    {
        return '
        <div class="form-group">
            <input type="text" name="titleInput" class="form-control" placeholder="Title" required>
        </div>
        ';
    }
    private function createDescriptionInput()
    {
        return '
        <div class="form-group"> 
            <textarea name="descriptionInput" rows="3" class="form-control" placeholder="Description" required></textarea>
        </div>
        ';
    }
    private function createPrivacyInput()
    {
        return '
        <div class="form-group">
            <select class="form-control" name="privacyInput">
                <option>select privacy</option>
                <option value="0">public</option>
                <option value="1">private</option>
            </select>
        </div>
        ';
    }

    private function createCategoryInput()
    {
        $html = '<div class="form-group">
            <select class="form-control" name="categoryInput">';

        $query = $this->con->prepare("SELECT * FROM categories");

        $query->execute();

        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $name = $row['name'];
            $id = $row['id'];
            $html .= "<option value='$id'>$name</option>";
        }
        $html .= '</select>
                </div>';
        return $html;
    }

    private function createUploadButton()
    {
        $html = '<button type="submit" name="uploadBtn" class="btn btn-primary">Upload</button>';
        return $html;
    }
}
