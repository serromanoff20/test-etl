<?php namespace app\models;

include_once '/data/vendor/autoload.php';
include_once '/data/app/common/Constants.php';
include_once '/data/app/models/Model.php';

use app\models\Model;
use common\Constants;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

class Excel extends Model
{
    public static string $_file;

    public function initFile(): bool
    {
        self::$_file = Constants::PATH_FILES . basename($_FILES["file"]["name"]);
        $fileType = strtolower(pathinfo(self::$_file, PATHINFO_EXTENSION));

        if($fileType === "xlsx") {
            if (move_uploaded_file($_FILES["file"]["tmp_name"], self::$_file)) {
                return true;
            }
        }
        $this->setError(get_called_class(), 'Не удалось распарсить файл');
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