<?php

abstract class View {
    public function render($templateFile) {
        include $templateFile;
    }

}
