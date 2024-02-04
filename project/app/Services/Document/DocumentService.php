<?php

namespace App\Services\Document;

use App\Services\Document\{
    Handlers\TemplateDataBuilder,
    Handlers\VarsExtractor,
};
use PhpOffice\PhpWord\{
    Style\Font,
    TemplateProcessor,
    Element\TextRun,
};
use Exception;

class DocumentService 
{
    private string            $fileDocumentPath;
    private string            $fileDocumentLink;
    private string            $fileTemplatePath;
    private TemplateProcessor $templateProcessor;
    private string            $exe = '.docx';
    private int               $fontSize = 12;
    private Font              $font;

    public function __construct(
        private readonly VarsExtractor       $varsExtractor,
        private readonly TemplateDataBuilder $templateDataBuilder,
    )
    {
    }

    /**
     * @param string $template Template name as Claim
     *
     * @return string
     */
    public function createDocument(string $template): string
    {
        $this->setFile($template);

        $vars = $this->varsExtractor->getVars($this->fileTemplatePath);
        $varsValues = $this->templateDataBuilder->getValues($vars);

        $this->setTemplateProcessor();
        $this->setFont();
        $this->putTemplateVars($varsValues);

        return $this->saveOperation();
    }

    /**
     * @throws \Throwable
     */
    private function setFile(string $template): void
    {
        $this->fileDocumentPath = storage_path('Document_' . now()->toDateString() . $this->exe);
        $this->fileDocumentLink = 'Document_' . now()->toDateString() . $this->exe;

        $fileTemplatePath = storage_path('Templates/' . $template . 'Template.docx');

        throw_if(!file_exists($fileTemplatePath),
            Exception::class,
            __('Document: ' . $template . ' missing')
        );

        $this->fileTemplatePath = $fileTemplatePath;
    }

    private function setTemplateProcessor(): void
    {
        $this->templateProcessor = new TemplateProcessor($this->fileTemplatePath);
    }

    private function setFont(): void
    {
        $this->font = new Font();
        $this->font->setSize($this->fontSize);
    }

    private function putTemplateVars(array $varsValues): void
    {
        foreach($varsValues as $var => $value) {

            $complexValue = $this->getComplexValue($value);
            $this->templateProcessor->setComplexValue($var, $complexValue);
        }
    }

    private function getComplexValue(string $value): TextRun
    {
        $textRun = new TextRun();
        $textRun->addText($value, $this->font);

        return $textRun;
    }

    private function saveOperation(): string
    {
        $this->templateProcessor->saveAs($this->fileDocumentPath);

        return $this->fileDocumentLink;
    }
}