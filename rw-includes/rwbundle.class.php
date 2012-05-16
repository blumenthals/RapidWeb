<?php

class RWBundle {
    public function loadJavascript($script) {
        throw new Exception("'$script' not found in any assets path");
    }
}
