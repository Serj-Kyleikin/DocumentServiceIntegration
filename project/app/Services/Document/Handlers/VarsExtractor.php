<?php

namespace App\Services\Document\Handlers;

use PhpOffice\PhpWord\{
    IOFactory,
    Element\TextRun,
    PhpWord,
};

class VarsExtractor
{
    private string  $pattern = '/\${.+?}/';
    private PhpWord $extractor;
    private array   $elements;

    public function getVars(string $templatePath): array
    {
        $this->setExtractor($templatePath);
        $this->setElements();

        return $this->getElementsVars();
    }

    private function setExtractor(string $templatePath): void
    {
        $this->extractor = IOFactory::load($templatePath);
    }

    private function setElements(): void
    {
        $sections = $this->extractor->getSections();

        foreach($sections as $section) {
            foreach($section->getElements() as $element) {
                if($element instanceof TextRun) {
                    $this->elements[] = $element->getText();
                }
            }
        }
    }

    private function getElementsVars(): array
    {
        foreach($this->elements as $element) {

            if(preg_match_all($this->pattern, $element, $matches)) {

                foreach($matches[0] as $var) {
                    $vars[$var] = new Interpreter($var);
                }
            }
        }

        return $vars;
    }
}
