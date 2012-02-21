<?php
namespace Type\Xml;


class WellformednessException extends \Exception {

    public function __construct($message, $code, $errors, $xmlString) {
        $this->errors = $errors;
        $xmlLines = explode("\n", $xmlString);
        foreach ($this->errors as $error) {
            $message .= "\n" . $this->formatError($error, $xmlLines);
        }
        //$message .= '"' . $xmlString . '"';
        parent::__construct($message, $code);
    }

    public function getErrors() {
        return $this->errors;
    }

    protected function formatError($error, $xml=array()) {
        $return = str_replace("\t", " ", $xml[$error->line - 1]) . "\n";
        $return .= str_repeat('-', $error->column) . "^\n";

        switch ($error->level) {
            case LIBXML_ERR_WARNING:
                $return .= "Warning $error->code: ";
                break;
            case LIBXML_ERR_ERROR:
                $return .= "Error $error->code: ";
                break;
            case LIBXML_ERR_FATAL:
                $return .= "Fatal Error $error->code: ";
                break;
        }

        $return .= trim($error->message) .
                "\n  Line: $error->line" .
                "\n  Column: $error->column";

        if ($error->file) {
            $return .= "\n  File: $error->file";
        }

        return "$return\n\n--------------------------------------------\n";
    }
}
?>
