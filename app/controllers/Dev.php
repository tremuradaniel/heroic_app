<?php
  class Dev extends Controller {
    public function phpInfo(): void
    {
      $this->view('dev/phpInfo');
    }
  }