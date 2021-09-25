<?php

namespace Sunnysideup\CleanerTinyMCEConfig\Forms;



interface HTMLEditorFieldShortConfigInterface
{

    public function getName() : string;

    public function getNumberOfRows() : int;

    public function DisablePlugins() : array;

    public function EnablePlugins() : array;

    public function AddButtons() : array;

    public function RemoveButtons() : array;

    public function BlockFormats() : array;

    public function setCustomConfig();
}
