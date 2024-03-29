<?php namespace app\models\responses;

use app\models\ErrorModel;
use common\Constants;
use XMLWriter;

class XMLFeed
{
    public function toCreateFeed(Response $response): string
    {
        $xmlWriter = new XMLWriter();
        header('Content-type: text/xml');

        $xmlWriter->openMemory();
        $xmlWriter->setIndent(true);
        $xmlWriter->startDocument('1.0', 'UTF-8');

        $xmlWriter->startElement('feed'); //открываем feed
        $xmlWriter->writeAttribute('version', '1.0');

        $xmlWriter->writeElement('code', $response->code);

        $xmlWriter->startElement('response');
        foreach ($response->response as $key => $item) {
            if (gettype($item) === 'array') {
                if ($response->code === Constants::SUCCESS_CODE) {
                    $xmlWriter->startElement('item');
                    foreach ($item as $subkey => $value) {
                        $xmlWriter->writeElement($subkey, $value);
                    }
                    $xmlWriter->endElement();
                } else {
                    $xmlWriter->startElement('exception');
                    foreach ($item as $subkey => $value) {
                        if (gettype($value) === 'array') {
                            $xmlWriter->startElement('trace');
                            foreach ($value as $path) {
                                $xmlWriter->writeElement('path', $path);
                            }
                            $xmlWriter->endElement();
                        } else {
//                            $xmlWriter->startElement('message');
                            $xmlWriter->writeElement($subkey, $value);
//                            $xmlWriter->endElement();
                        }
                    }
                    $xmlWriter->endElement();
                }
            } else if ($item instanceof ErrorModel) {
                $xmlWriter->startElement('error');
                foreach ($item as $place => $message) {
                    $xmlWriter->writeElement($place, $message);
                }
                $xmlWriter->endElement();
            } else {
                $xmlWriter->writeElement($key, $item);
            }
        }
        $xmlWriter->endElement();
        $xmlWriter->endElement(); //закрываем feed

        $xmlWriter->endDocument();

        return $xmlWriter->outputMemory();
    }

}