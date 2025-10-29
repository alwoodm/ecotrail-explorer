<?php
declare(strict_types=1);

require_once "ToArrayInterface.php";

class Site implements ToArrayInterface {
    private $id;
    private $slug;
    private $title;
    private $template;
    private $keywords;
    private $description;
    private $hero_title;
    private $hero_subtitle;
    private $cta_label;
    private $cta_target;
    private $navigation_order;

    public function __get(string $name) {
        return $this->$name ?? null;
    }

    public function __set(string $name, $value): void {
        if ($name === "id" && $this->id !== null) {
            return;
        }
        $this->$name = $value;
    }

    public function toArray(): array {
        return [
            "id" => $this->id,
            "slug" => $this->slug,
            "title" => $this->title,
            "template" => $this->template,
            "keywords" => $this->keywords,
            "description" => $this->description,
            "heroTitle" => $this->hero_title,
            "heroSubtitle" => $this->hero_subtitle,
            "ctaLabel" => $this->cta_label,
            "ctaTarget" => $this->cta_target,
            "navigationOrder" => $this->navigation_order,
        ];
    }
}
