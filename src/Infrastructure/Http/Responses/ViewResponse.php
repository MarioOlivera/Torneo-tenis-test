<?php
namespace Src\Infrastructure\Http\Responses;

class ViewResponse {
    private string $view;
    private array $data;

    public function __construct(string $view, array $data = [])
    {
        $this->view = $view;
        $this->data = $data;
    }

    public function render(): string {
        ob_start(); // buffer salida.
        extract($this->data); // creacion variables
        include $this->view; // incluir vista
        return ob_get_clean(); // devolver contenido
    }
}
