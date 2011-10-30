<?php

class OldTemplate extends View {
    public function render($templateFile) {
        global $VARIABLES;
        include $templateFile;
    }
}
