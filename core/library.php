<?php

namespace Core;

class Library {
    private Loader $load;

    public function __construct() {
        $this->load = new Loader();
    }
}
