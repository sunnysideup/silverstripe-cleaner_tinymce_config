<?php

namespace Sunnysideup\CleanerTinyMCEConfig\Forms;



interface HTMLEditorFieldShort_ConfigInterface
{

    public function setConfig($name = 'cms');

    public function getNumberOfRows() : int;

}
