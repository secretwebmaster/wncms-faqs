# WNCMS FAQs

A modular FAQ management package for **WNCMS** (Laravel-based CMS).  
This package provides backend CRUD interfaces, API routes, and frontend rendering for Frequently Asked Questions.

---

## Installation

Require the package via Composer:

```bash
composer require secretwebmaster/wncms-faqs
````

If you’re developing locally with `wncms-core` in a monorepo, you can also link it as a path repository.

---

## Configuration

The service provider `Secretwebmaster\WncmsFaqs\Providers\WncmsFaqsServiceProvider` is auto-discovered by Laravel.

After installation, run the migrations:

```bash
php artisan migrate
```

Optionally, seed example data:

```bash
php artisan db:seed --class=Secretwebmaster\\WncmsFaqs\\Database\\Seeders\\FaqSeeder
```

---

## Usage

The package automatically registers the following routes (depending on your WNCMS version):

* **Backend:** `/panel/faqs`
* **API:** `/api/v1/faqs`

Each FAQ supports translatable fields (if `wncms-translatable` is installed) and integrates with `FaqManager` for listing and filtering.

Example:

```php
use Secretwebmaster\WncmsFaqs\Services\Managers\FaqManager;

$faqs = (new FaqManager())->getList(['status' => 'active']);
```

---

## Directory Structure

```
wncms-faqs/
├── composer.json
├── database/
│   ├── migrations/
│   └── seeders/
├── resources/
│   ├── lang/
│   └── views/
├── routes/
├── src/
│   ├── Http/
│   ├── Models/
│   ├── Providers/
│   └── Services/
└── README.md
```

---

## Author

**Secretwebmaster**
[https://github.com/secretwebmaster](https://github.com/secretwebmaster)

---

## License

This package is open-sourced software licensed under the **MIT License**.
