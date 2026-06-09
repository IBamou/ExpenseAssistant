# Receipt CRUD Tasks

## Phase 1: OpenSpec Artifacts
- [ ] Write proposal.md
- [ ] Write spec.md (behavioral requirements)
- [ ] Write design.md (technical decisions)
- [ ] Write tasks.md (this file)

## Phase 2: Data Layer
- [ ] Create `app/Enums/ReceiptStatus.php`
- [ ] Create migration for `receipts` table
- [ ] Create migration for `expenses` table (with FK cascade)
- [ ] Create `app/Models/Receipt.php` with casts and relationships
- [ ] Create `app/Models/Expense.php` with casts and relationships
- [ ] Add `hasMany(Receipt)` relationship to `User` model

## Phase 3: Controller & Validation
- [ ] Create `app/Http/Requests/StoreReceiptRequest.php`
- [ ] Create `app/Http/Controllers/ReceiptController.php` (index, create, store, show, destroy)
- [ ] Add resourceful routes to `routes/web.php`

## Phase 4: Views
- [ ] Create `resources/views/receipts/index.blade.php` (paginated list with status badges, expense count)
- [ ] Create `resources/views/receipts/create.blade.php` (textarea + validation errors + flash)
- [ ] Create `resources/views/receipts/show.blade.php` (source text, status, expense table)
- [ ] Add "My Receipts" link to `resources/views/layouts/navigation.blade.php`

## Phase 5: Verification
- [ ] Run `php artisan migrate` — verify tables created
- [ ] Run `npm run build` — verify assets compile
- [ ] Run `./vendor/bin/pint` — verify code style
- [ ] Run `php artisan test` — all passing

## Dependencies
- Laravel Breeze auth must be fully functional (already set up)
- Database connection must be configured (MySQL, already set up)

## Verification
- [ ] All tasks completed
- [ ] OpenSpec artifacts committed
