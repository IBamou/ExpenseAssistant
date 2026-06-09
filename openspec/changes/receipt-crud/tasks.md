# Receipt CRUD Tasks

## Phase 1: OpenSpec Artifacts
- [x] Write proposal.md
- [x] Write spec.md (behavioral requirements)
- [x] Write design.md (technical decisions)
- [x] Write tasks.md (this file)

## Phase 2: Data Layer
- [x] Create `app/Enums/ReceiptStatus.php`
- [x] Create migration for `receipts` table
- [x] Create migration for `expenses` table (with FK cascade)
- [x] Create `app/Models/Receipt.php` with casts and relationships
- [x] Create `app/Models/Expense.php` with casts and relationships
- [x] Add `hasMany(Receipt)` relationship to `User` model

## Phase 3: Controller & Validation
- [x] Create `app/Http/Requests/StoreReceiptRequest.php`
- [x] Create `app/Http/Controllers/ReceiptController.php`
- [x] Create `app/Policies/ReceiptPolicy.php` (+ `AuthServiceProvider`)
- [x] Add explicit Livewire routes in `routes/web.php`

## Phase 4: UI (Livewire + Alpine — replaced Breeze Blade)
- [x] Install Livewire (`livewire/livewire`)
- [x] Rewrite `layouts/app.blade.php` (sidebar nav, no Breeze)
- [x] Rewrite `layouts/guest.blade.php` (gradient bg, clean card)
- [x] Rewrite auth views (login, register, password, verify) — no Breeze components
- [x] Create `app/Livewire/Receipts/Index.php` — search + status filter + pagination
- [x] Create `app/Livewire/Receipts/Create.php` — textarea with live char count
- [x] Create `app/Livewire/Receipts/Show.php` — detail + expenses + Alpine delete modal
- [x] Rewrite profile views (update info, password, delete account)
- [x] Remove old Breeze components (13 files) + old receipt Blade views
- [x] Rewrite dashboard with stat cards + quick actions

## Phase 5: Verification
- [x] Run `php artisan migrate` — tables created
- [x] Run `npm run build` — assets compile
- [x] Run `./vendor/bin/pint` — code style passed
- [x] Run `php artisan test` — 25/25 passed

## Dependencies
- Laravel Breeze auth must be fully functional (already set up)
- Database connection must be configured (MySQL, already set up)

## Verification
- [x] All tasks completed
- [x] OpenSpec artifacts committed
