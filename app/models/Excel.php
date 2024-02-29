<?php namespace app\models;

include_once '/data/vendor/autoload.php';

use constants\Constants;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

class Excel
{
    public static string $_file;

    public function loading()
    {
        self::$_file = Constants::PATH_FILES . basename($_FILES["file"]["name"]);
        $uploadOk = 1;
        $fileType = strtolower(pathinfo(self::$_file, PATHINFO_EXTENSION));

        if($fileType !== "xlsx") {
            echo "Только файлы с расширением XLSX разрешены.";
            $uploadOk = 0;
        }

        if ($uploadOk === 0) {
            echo "Файл не был загружен.";
        } else {
            if (move_uploaded_file($_FILES["file"]["tmp_name"], self::$_file)) {
                echo "Файл ". basename( $_FILES["file"]["name"]). " успешно загружен.";
            } else {
                echo "Произошла ошибка при загрузке файла.";
            }
        }
    }

    public function parser()
    {
        // Путь к вашему XLSX-файлу
//        $filePath = self::$_file;

        // Создаем экземпляр объекта IOFactory и загружаем файл
        $spreadsheet = IOFactory::load(self::$_file);

        // Получаем активный лист (первый лист)
        $sheet = $spreadsheet->getActiveSheet();

        // Получаем количество строк и столбцов
        $highestRow = $sheet->getHighestRow();
        $highestColumn = $sheet->getHighestColumn();

        // Преобразуем буквенное обозначение столбца в числовой индекс
        $highestColumnIndex = Coordinate::columnIndexFromString($highestColumn);

        // Цикл по строкам и столбцам для вывода данных
        for ($row = 1; $row <= $highestRow; ++$row) {
            for ($col = 1; $col <= $highestColumnIndex; ++$col) {
                // Получаем значение ячейки
                $value = $sheet->getCell([$col => $row])->getValue();
                // Выводим значение ячейки
                echo "($row, $col): $value\n";
            }
        }

        // Закрываем объект IOFactory
        $spreadsheet->disconnectWorksheets();
    }
}