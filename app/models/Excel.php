<?php namespace app\models;

include_once '/data/vendor/autoload.php';

use constants\Constants;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

class Excel
{
    public static string $_file;

//    public function loading()
//    {
//        self::$_file = Constants::PATH_FILES . basename($_FILES["file"]["name"]);
//        $uploadOk = 1;
//        $fileType = strtolower(pathinfo(self::$_file, PATHINFO_EXTENSION));
//
//        if($fileType !== "xlsx") {
//            echo "Только файлы с расширением XLSX разрешены.";
//            $uploadOk = 0;
//        }
//
//        if ($uploadOk === 0) {
//            echo "Файл не был загружен.";
//        } else {
//            if (move_uploaded_file($_FILES["file"]["tmp_name"], self::$_file)) {
//                echo "Файл ". basename( $_FILES["file"]["name"]). " успешно загружен.";
//            } else {
//                echo "Произошла ошибка при загрузке файла.";
//            }
//        }
//    }
    public function initFile(): bool
    {
        self::$_file = Constants::PATH_FILES . basename($_FILES["file"]["name"]);
        $fileType = strtolower(pathinfo(self::$_file, PATHINFO_EXTENSION));

        if($fileType === "xlsx") {
            if (move_uploaded_file($_FILES["file"]["tmp_name"], self::$_file)) {
                return true;
            }
        }
        return false;
    }

    public function parser(): array
    {
        $result = [];
        $header = [];
        $tmp_result = [];
        $spreadsheet = IOFactory::load(self::$_file);

        $sheet = $spreadsheet->getActiveSheet();

        $highestRow = $sheet->getHighestRow();
        $highestColumn = $sheet->getHighestColumn();

        $highestColumnIndex = Coordinate::columnIndexFromString($highestColumn);

        for ($row = 1; $row <= 1; ++$row) {
            for ($col = 1; $col <= $highestColumnIndex; ++$col) {
                $value = $sheet->getCell([$col, $row])->getValue();

                $header[$col][$row] = $value;
            }
        }

        for ($row = 2; $row <= $highestRow; ++$row) {
            for ($col = 1; $col <= $highestColumnIndex; ++$col) {
                $value = $sheet->getCell([$col, $row])->getValue();

                $tmp_result[$col][$row] = $value;
            }
        }
        $spreadsheet->disconnectWorksheets();

        foreach ($tmp_result as $key=>$item) {
            foreach ($item as $value) {
                $result[$header[$key][1]][] = $value;
            }
        }

        return $result;
    }
}