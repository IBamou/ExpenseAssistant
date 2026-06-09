# Expense Filtering Tasks

## Phase 1: OpenSpec Artifacts
- [ ] Write proposal.md
- [ ] Write spec.md (behavioral requirements)
- [ ] Write design.md (technical decisions)
- [ ] Write tasks.md (this file)

## Phase 2: Livewire Component
- [ ] Create `app/Livewire/Expenses/Index.php` with category filter
- [ ] Create `resources/views/livewire/expenses/index.blade.php` with filter tabs + table

## Phase 3: Routes & Nav
- [ ] Add route `GET /expenses` in `routes/web.php`
- [ ] Add "Expenses" link to `layouts/app.blade.php` sidebar

## Phase 4: Verification
- [ ] Run `npm run build`
- [ ] Run `./vendor/bin/pint`
- [ ] Run `php artisan test` — all passing

## Dependencies
- ai-extraction feature (expenses must exist in DB)
- receipt-crud feature (receipts table)

## Verification
- [ ] All tasks completed
